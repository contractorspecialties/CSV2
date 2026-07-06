<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Estimate;
use App\Models\EstimateItem;
use App\Models\JobAttachment;
use App\Models\PricebookItem;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class EstimateController extends Controller
{
    /**
     * Render the interactive multi-row estimation canvas workspace.
     */
    public function create(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $customers = Client::where('company_id', $companyId)
            ->orderBy('client_name', 'asc')
            ->get();

        $customers->each(function($client) {
            $parts = explode(' ', trim($client->client_name ?? ''), 2);
            $client->first_name = $parts[0] ?? 'Client';
            $client->last_name = $parts[1] ?? ' ';
            $client->billing_address = $client->address;
        });

        $pricebookItems = PricebookItem::where('company_id', $companyId)
            ->orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $preselectedCustomerId = $request->query('customer_id');

        return view('estimates.create', compact('customers', 'pricebookItems', 'preselectedCustomerId'));
    }

    /**
     * Commit the dynamic multi-row estimation matrix and map inline customers safely.
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        if (isset($inputData['expires_at']) && trim($inputData['expires_at']) === '') {
            $inputData['expires_at'] = null;
        }
        if (isset($inputData['deposit_amount']) && trim($inputData['deposit_amount']) === '') {
            $inputData['deposit_amount'] = null;
        }

        if (isset($inputData['items']) && is_array($inputData['items'])) {
            foreach ($inputData['items'] as $key => $item) {
                $inputData['items'][$key]['save_to_pricebook'] = (isset($item['save_to_pricebook']) && ($item['save_to_pricebook'] === 'true' || $item['save_to_pricebook'] === 'on' || $item['save_to_pricebook'] == 1)) ? true : false;
                $inputData['items'][$key]['is_taxable'] = (isset($item['is_taxable']) && ($item['is_taxable'] === 'true' || $item['is_taxable'] === 'on' || $item['is_taxable'] == 1)) ? true : false;
            }
        }

        $request->merge($inputData);
        $this->healEstimateTablesSchema();

        $validated = $request->validate([
            'customer_id'         => 'nullable|integer',
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name'  => 'required|string|max:255',
            'customer_email'      => 'required|email|max:255',
            'customer_phone'      => 'nullable|string|max:30',
            'customer_address'    => 'nullable|string|max:500',
            'tax_rate'            => 'required|numeric|min:0|max:100',
            'deposit_amount'      => 'nullable|numeric|min:0',
            'notes'               => 'nullable|string',
            'expires_at'          => 'nullable|date|after_or_equal:today',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0.00',
            'items.*.is_taxable'  => 'nullable',
            'items.*.save_to_pricebook' => 'nullable',
            'caption'             => 'nullable|string|max:255'
        ]);

        $companyId = Auth::user()->company_id;

        $estimate = DB::transaction(function () use ($validated, $companyId, $request) {
            $customer = null;
            $fullName = trim($validated['customer_first_name'] . ' ' . $validated['customer_last_name']);

            if (!empty($validated['customer_id'])) {
                $customer = Client::where('company_id', $companyId)->find($validated['customer_id']);
            }

            if (!$customer && !empty($validated['customer_email'])) {
                $customer = Client::where('company_id', $companyId)
                    ->where('email', $validated['customer_email'])
                    ->first();
            }

            if (!$customer) {
                $customer = new Client();
                $customer->company_id = $companyId;
            }

            $customer->client_name = $fullName;
            $customer->email = $validated['customer_email'];
            $customer->phone_number = $validated['customer_phone'];
            $customer->address = $validated['customer_address'];
            $customer->save();

            $userTable = (new \App\Models\User())->getTable();
            $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
            $company = DB::table($prefix . 'companies')->where('id', $companyId)->first();
            $startingBaseline = isset($company->starting_invoice_number) ? (int)$company->starting_invoice_number : 1000;

            $lastEstimate = Estimate::where('company_id', $companyId)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastEstimate) {
                if (preg_match('/(\d+)/', $lastEstimate->estimate_number, $matches)) {
                    $nextSequence = max($startingBaseline, (int)$matches[1] + 1);
                } else {
                    $nextSequence = $startingBaseline + 1;
                }
            } else {
                $nextSequence = $startingBaseline;
            }

            $estimateNumber = 'EST-' . $nextSequence;

            $estimate = new Estimate();
            $estimate->company_id = $companyId;
            $estimate->customer_id = $customer->id;
            $estimate->estimate_number = $estimateNumber;
            $estimate->status = 'draft';
            $estimate->tax_rate = $validated['tax_rate'];
            $estimate->deposit_amount = $validated['deposit_amount'] ?? 0.00;
            $estimate->notes = $validated['notes'];
            $estimate->expires_at = $validated['expires_at'] ?? now()->addDays(30);
            $estimate->subtotal = 0.00;
            $estimate->grand_total = 0.00;
            $estimate->save();

            $calculatedSubtotal = 0;
            $taxableSubtotal = 0;

            foreach ($validated['items'] as $itemData) {
                $itemTotal = $itemData['quantity'] * $itemData['unit_price'];
                $calculatedSubtotal += $itemTotal;

                if (!empty($itemData['is_taxable'])) {
                    $taxableSubtotal += $itemTotal;
                }

                $item = new EstimateItem();
                $item->estimate_id = $estimate->id;
                $item->description = $itemData['description'];
                $item->quantity = $itemData['quantity'];
                $item->unit_price = $itemData['unit_price'];
                $item->total_price = $itemTotal;
                $item->is_taxable = !empty($itemData['is_taxable']) ? 1 : 0;
                $item->save();

                if (!empty($itemData['save_to_pricebook'])) {
                    $cleanDesc = Str::limit($itemData['description'], 100);
                    $pbItem = PricebookItem::where('company_id', $companyId)->where('name', $cleanDesc)->first();
                    if (!$pbItem) {
                        $pbItem = new PricebookItem();
                        $pbItem->company_id = $companyId;
                        $pbItem->name = $cleanDesc;
                    }
                    $pbItem->category = 'Field Created Services';
                    $pbItem->base_unit_cost = $itemData['unit_price'];
                    $pbItem->markup_percentage = 0.00;
                    $pbItem->unit_type = 'Each';
                    $pbItem->save();
                }
            }

            $taxAmount = $taxableSubtotal * ($validated['tax_rate'] / 100);
            $calculatedGrandTotal = $calculatedSubtotal + $taxAmount;

            $estimate->subtotal = $calculatedSubtotal;
            $estimate->grand_total = $calculatedGrandTotal;
            $estimate->save();

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $securedPath = $this->compressAndVaultFieldPhoto($file, $estimate->id);

                $attachment = new JobAttachment();
                $attachment->estimate_id = $estimate->id;
                $attachment->file_path = $securedPath;
                $attachment->file_type = 'image';
                $attachment->caption = $request->caption ?? 'Initial project scope capture';
                $attachment->save();
            }

            return $estimate;
        });

        return redirect()->route('dashboard')->with('status', "⚡ Estimate {$estimate->estimate_number} successfully compiled.");
    }

    /**
     * Display the dedicated control frame for a single estimate.
     */
    public function show($id)
    {
        $companyId = Auth::user()->company_id;

        $estimate = Estimate::where('company_id', $companyId)
            ->with(['customer', 'items'])
            ->where(function ($query) use ($id) {
                if (is_numeric($id)) {
                    $query->where('id', $id);
                } else {
                    $query->where('estimate_number', $id);
                }
            })
            ->firstOrFail();

        if ($estimate->customer) {
            $parts = explode(' ', trim($estimate->customer->client_name ?? ''), 2);
            $estimate->customer->first_name = $parts[0] ?? 'Client';
            $estimate->customer->last_name = $parts[1] ?? ' ';
        }

        $attachments = JobAttachment::where('estimate_id', $estimate->id)->get();

        $attachments->each(function($asset) {
            $asset->secure_url = URL::temporarySignedRoute(
                'estimates.attachments.stream',
                now()->addMinutes(60),
                ['id' => (string) $asset->id]
            );
        });

        return view('estimates.show', compact('estimate', 'attachments'));
    }

    /**
     * Update the estimate status inline to unlock pipeline testing states.
     */
    public function updateStatus(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->findOrFail($id);

        $request->validate(['status' => 'required|in:draft,sent,approved,closed']);
        $estimate->update(['status' => $request->status]);

        if ($request->status === 'approved') {
            \App\Models\Appointment::create([
                'company_id'   => $companyId,
                'customer_id'  => $estimate->customer_id,
                'estimate_id'  => $estimate->id,
                'title'        => "Production Run: " . $estimate->estimate_number,
                'scheduled_at' => now(),
                'status'       => 'scheduled'
            ]);
        }

        return back()->with('status', "🔄 Status changed to " . strtoupper($request->status));
    }

    /**
     * Record a contractor response and issue an updated alert tracking notification hook.
     */
    public function saveBlueprint(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->with('customer')->findOrFail($id);

        $request->validate(['notes' => 'required|string|max:2000']);

        $updatedNotes = "⚡ Contractor Response Modification (" . now()->format('m/d H:i') . "):\n" . $request->notes . "\n\n" . $estimate->notes;

        $estimate->update([
            'notes'  => $updatedNotes,
            'status' => 'sent'
        ]);

        if (!empty($estimate->customer->phone_number)) {
            $cleanClientPhone = $this->normalizeToE164($estimate->customer->phone_number);
            $portalLink = route('portal.checkout', ['token' => $estimate->estimate_number]);

            $userTable = (new \App\Models\User())->getTable();
            $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
            $company = DB::table($prefix . 'companies')->where('id', $companyId)->first();
            $fromLine = $company->sms_phone_number ?? env('TELNYX_DEFAULT_FROM');

            if (!empty($fromLine) && !empty($cleanClientPhone)) {
                try {
                    \App\Jobs\SendPortalSms::dispatch(
                        $cleanClientPhone,
                        "Hello " . ($estimate->customer->first_name ?? 'Client') . ", we updated your proposal. View the modifications here: " . $portalLink,
                        $fromNumber
                    );
                } catch (\Exception $e) {
                    Log::error("🚨 Telnyx inline dispatch failure: " . $e->getMessage());
                }
            }
        }

        return back()->with('status', '⚡ Estimate blueprint modifications saved and notification scheduled.');
    }

    /**
     * Display dedicated control frame for single estimate.
     */
    public function showEstimateControl($id)
    {
        return $this->show($id);
    }

    /**
     * Send estimate notification via SMS pipeline.
     */
    public function sendEstimateSms(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->with('customer')->findOrFail($id);

        if (empty($estimate->customer->phone_number)) {
            return back()->withErrors(['sms' => '🛑 Customer has no active mobile telephone configuration listed in their profile registry.']);
        }

        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $company = DB::table($prefix . 'companies')->where('id', $companyId)->first();

        $senderNumber = $company->sms_number ?? config('services.telnyx.fallback_toll_free');
        if (empty($senderSystemNumber)) {
            return back()->withErrors(['sms' => '🛑 No active operational SMS text link is provisioned on your company account file ledger.']);
        }

        $cleanDestination = $this->normalizeToE164($estimate->customer->phone_number);
        $checkoutRouteLink = route('portal.checkout', ['token' => $estimate->estimate_number]);
        $messageText = "Hello " . ($estimate->customer->first_name ?? 'Client') . ", your project estimate details are compiled. View your customized proposal link here: " . $checkoutRouteLink . " Reply STOP to opt out.";

        try {
            // Outbound Telnyx pipeline standard call route initialization
            $attachment = [];
            \App\Services\TelnyxService::sendSms($senderLine, $cleanDestination, $messageText);

            DB::table($prefix . 'estimates')->where('id', $estimate->id)->update([
                'status' => 'sent',
                'updated_at' => now()
            ]);

            return back()->with('status', "🔒 Digital tracking estimate proposal texted to client successfully via lane {$senderLine}.");
        } catch (\Exception $e) {
            Log::error("🚨 Telnyx Dispatch failure run: " . $e->getMessage());
            return back()->withErrors(['sms' => 'Outbound carrier transit pipeline failed. Check system logs logs.']);
        }
    }

    /**
     * Send estimate notification via transaction email channel wrappers.
     */
    public function sendEstimateEmail(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->with(['customer', 'items'])->findOrFail($id);

        $customer = $estimate->customer;

        try {
            $portalLink = route('portal.checkout', ['token' => $estimate->estimate_number]);

            Mail::send([], [], function ($message) use ($estimate, $customer, $portalLink) {
                $message->to($customer->email)
                    ->subject("Project Proposal Dispatched - Estimate #{$estimate->estimate_number}")
                    ->html("
                        <div style=\"font-family: Arial, sans-serif; padding: 32px; max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;\">
                            <h2 style=\"color: #0f172a; font-size: 22px; margin-bottom: 4px; text-transform: uppercase;\">Project Proposal Ready</h2>
                            <p style=\"font-size: 15px; color: #334155; line-height: 1.6;\">Hello {$customer->client_name},</p>
                            <p style=\"font-size: 15px; color: #334155; line-height: 1.6;\">Your itemized service value breakdown package totaling <strong>$" . number_format($estimate->grand_total, 2) . "</strong> has been compiled and issued to your coordinates.</p>
                            <div style=\"margin: 32px 0; text-align: center;\">
                                <a href=\"{$portalLink}\" style=\"background-color: #f58613; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; text-transform: uppercase;\">Review & Approve Proposal &rarr;</a>
                            </div>
                        </div>
                    ");
            });

            $estimate->update(['status' => 'sent']);
            return back()->with('status', '📧 Project proposal transaction successfully emailed.');
        } catch (\Exception $e) {
            Log::error('SMTP standard mailer dispatch failure: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to execute SMTP delivery payload tracking configurations.']);
        }
    }

    /**
     * Dispatch canned message alerts via background communication loops.
     */
    public function sendCannedSms(Request $request, $id)
    {
        $request->validate([
            'message_type' => 'required|in:on_my_way,running_late,need_parts,progress_update'
        ]);

        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->with('customer')->findOrFail($id);

        if (!$estimate->customer || empty($estimate->customer->phone_number)) {
            return back()->with('error', '🛑 Cannot execute field dispatch: Client profile phone number is missing.');
        }

        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $company = DB::table($prefix . 'companies')->where('id', $companyId)->first();

        $fromLine = $company->sms_phone_number ?? env('TELNYX_DEFAULT_FROM');
        $companyName = $company->name ?? 'Our Crew';
        $portalLink = route('portal.checkout', ['token' => $estimate->estimate_number]);

        switch ($request->message_type) {
            case 'on_my_way':
                $body = "🚚 On My Way: This is {$companyName}. Our crew is en route to your property now and will be arriving shortly!";
                break;
            case 'running_late':
                $body = "⏱️ Running Late: This is {$companyName}. We are running slightly behind schedule on our previous field stop but are en route to your property now. Thank you for your patience!";
                break;
            case 'need_parts':
                $body = "🔧 Status Update: This is {$companyName}. We need to secure additional material components to complete your current production line pass. Stepping off-site briefly, crew will return shortly.";
                break;
            case 'progress_update':
                $body = "📸 Project Update: This is {$companyName}. We have logged fresh visual updates onto your secure tracking blueprint. Review progress rows here: {$portalLink}";
                break;
        }

        try {
            $cleanClientPhone = $this->normalizeToE164($estimate->customer->phone_number);

            \App\Services\TelnyxService::sendSms($fromLine, $cleanClientPhone, $body);

            DB::table($prefix . 'sms_histories')->insert([
                'company_id'   => $companyId,
                'client_id'    => $estimate->customer_id,
                'estimate_id'  => $estimate->id,
                'direction'    => 'outbound',
                'from_number'  => $fromLine,
                'to_number'    => $cleanClientPhone,
                'message_body' => $body,
                'created_at'   => now(),
                'updated_at'   => now()
            ]);

            return back()->with('status', '⚡ Canned field update successfully dispatched to client.');
        } catch (\Exception $e) {
            Log::error("🚨 Canned SMS execution failure: " . $e->getMessage());
            return back()->withErrors(['sms' => '🛑 Failed to route template payload to carrier network.']);
        }
    }

    /**
     * Inbound SMS transaction webhook interceptor.
     */
    public function handleTelnyxWebhook(Request $request)
    {
        $incomingData = $request->all();

        if (!isset($incomingData['data']['event_type']) || $incomingData['data']['event_type'] !== 'message.received') {
            return response()->json(['status' => 'ignored'], 200);
        }

        $messagePayload = $incomingData['data']['payload'];
        $senderLine     = trim($messagePayload['from']['phone_number'] ?? '');
        $receiverLine   = trim($messagePayload['to'][0]['phone_number'] ?? '');
        $incomingBody   = trim($messagePayload['text'] ?? '');
        $cleanKeyword   = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $incomingBody));

        if (empty($senderLine) || empty($incomingBody)) {
            return response()->json(['status' => 'empty_payload'], 200);
        }

        $this->healEstimateTablesSchema();

        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        $lastOutbound = DB::table($prefix . 'sms_histories')
            ->where('to_number', $senderLine)
            ->where('direction', 'outbound')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastOutbound) {
            Log::info("📨 Inbound SMS text from unmapped tracking line {$senderLine}: {$incomingBody}");
            return response()->json(['status' => 'unmapped_thread_logged'], 200);
        }

        DB::table($prefix . 'sms_histories')->insert([
            'company_id'   => $lastOutbound->company_id,
            'client_id'    => $lastOutbound->client_id,
            'estimate_id'  => $lastOutbound->estimate_id,
            'direction'    => 'inbound',
            'from_number'  => $senderLine,
            'to_number'    => $receiverLine,
            'message_body' => $incomingBody,
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        if ($lastOutbound->estimate_id && ($cleanKeyword === 'APPROVE' || $cleanKeyword === 'ACCEPT' || $cleanKeyword === 'YES')) {
            $latestEstimate = Estimate::withoutGlobalScopes()->find($lastOutbound->estimate_id);

            if ($latestEstimate && ($latestEstimate->status === 'draft' || $latestEstimate->status === 'sent')) {

                $latestEstimate->status = 'approved';
                $latestEstimate->signature_name = "AUTHORIZED VIA MOBILE SMS TEXT";
                $latestEstimate->signed_at = now();
                $latestEstimate->save();

                \App\Models\Appointment::create([
                    'company_id'   => $latestEstimate->company_id,
                    'customer_id'  => $latestEstimate->customer_id,
                    'estimate_id'  => $latestEstimate->id,
                    'title'        => "SMS Booking: " . $latestEstimate->estimate_number,
                    'scheduled_at' => now()->addDays(2),
                    'status'       => 'scheduled',
                    'notes'        => "Automated workflow triggered via bi-directional keyword lookup: '{$incomingBody}'"
                ]);

                Log::info("🚀 Estimate {$latestEstimate->estimate_number} programmatically APPROVED via text authorization thread.");
            }
        }

        return response()->json(['status' => 'received_and_logged'], 200);
    }

    /**
     * Remove the specified estimate and all nested child dependencies safely.
     */
    public function destroy($id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->findOrFail($id);

        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        DB::transaction(function () use ($estimate, $prefix) {
            EstimateItem::where('estimate_id', $estimate->id)->delete();

            $attachments = JobAttachment::where('estimate_id', $estimate->id)->get();
            foreach ($attachments as $fileAsset) {
                if (Storage::disk('local')->exists($fileAsset->file_path)) {
                    Storage::disk('local')->delete($fileAsset->file_path);
                }
                $fileAsset->delete();
            }

            $estimate->delete();
        });

        return redirect()->route('dashboard')->with('status', '🗑 Permanent history record successfully removed.');
    }

    /**
     * Close out an active production run and launch the background review invite loop.
     */
    public function closeJob(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;

        $estimate = Estimate::where('company_id', $companyId)->with('customer')->findOrFail($id);
        $estimate->update(['status' => 'closed']);

        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        $company = DB::table($prefix . 'companies')->where('id', $companyId)->first();

        if ($estimate->customer && !empty($estimate->customer->phone_number)) {
            $fromLine = $company->sms_phone_number ?? env('TELNYX_DEFAULT_FROM');

            if (!empty($fromLine)) {
                $reviewLink = !empty($company->google_review_link)
                    ? $company->google_review_link
                    : route('brand.show', ['slug' => $company->slug ?? 'staged-profile']);

                $clientParts = explode(' ', trim($estimate->customer->client_name ?? ''));
                $clientFirstName = $clientParts[0] ?? 'Client';

                $smsMessageBody = "Hi {$clientFirstName}, thank you for choosing " . ($company->name ?? 'our crew') . "! We have finalized your project logs. Could you take 30 seconds to share your experience and review our field work here? " . $reviewLink;
                $cleanClientPhone = $this->normalizeToE164($estimate->customer->phone_number);

                try {
                    \App\Services\TelnyxService::sendSms($fromLine, $cleanClientPhone, $smsMessageBody);

                    DB::table($prefix . 'sms_histories')->insert([
                        'company_id'   => $companyId,
                        'client_id'    => $estimate->customer_id,
                        'estimate_id'  => $estimate->id,
                        'direction'    => 'outbound',
                        'from_number'  => $fromLine,
                        'to_number'    => $cleanClientPhone,
                        'message_body' => "Hi {$clientFirstName}, thank you for choosing us! Leave us a review here: " . $reviewLink,
                        'created_at'   => now(),
                        'updated_at'   => now()
                    ]);
                } catch (\Exception $e) {
                    Log::error("🚨 Reputation funnel SMS invite dispatch failure on job close: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('dashboard')->with('status', '📦 Operational job run marked CLOSED and cleanly archived inside history logs. Local review funnel request dispatched.');
    }

    /**
     * Processes high-resolution files into WebP format down to a strict 1200px ceiling.
     */
    private function compressAndVaultFieldPhoto(UploadedFile $file, int $estimateId): string
    {
        $rawBuffer = file_get_contents($file->getRealPath());
        $sourceImage = @imagecreatefromstring($rawBuffer);

        if (!$sourceImage) {
            $fallbackName = 'attachments/' . $estimateId . '_' . Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('local')->put($fallbackName, $rawBuffer);
            return $fallbackName;
        }

        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);
        $maxDimension = 1200;

        if ($width > $maxDimension || $height > $maxDimension) {
            if ($width > $height) {
                $targetWidth = $maxDimension;
                $targetHeight = (int) ($height * ($maxDimension / $width));
            } else {
                $targetHeight = $maxDimension;
                $targetWidth = (int) ($width * ($maxDimension / $height));
            }

            $canvasImage = imagecreatetruecolor($targetWidth, $targetHeight);

            imagealphablending($canvasImage, false);
            imagesavealpha($canvasImage, true);

            imagecopyresampled($canvasImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
            imagedestroy($sourceImage);
            $sourceImage = $canvasImage;
        }

        ob_start();
        imagewebp($sourceImage, null, 80);
        $compressedData = ob_get_clean();
        imagedestroy($sourceImage);

        $vaultName = 'attachments/' . $estimateId . '_' . Str::random(12) . '_' . time() . '.webp';
        Storage::disk('local')->put($vaultName, $compressedData);

        return $vaultName;
    }

    /**
     * Internal utility rule helper to normalize phone numbers strictly into valid E.164 structures.
     */
    private function normalizeToE164(?string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone ?? '');
        if (strlen($digits) === 10) {
            return '+1' . $digits;
        } elseif (strlen($digits) === 11 && str_starts_with($digits, '1')) {
            return '+' . $digits;
        }
        return '+' . $digits;
    }

    /**
     * Upload an estimate attachment card, compressing assets on-the-fly into isolated vaults.
     */
    public function uploadAttachment(Request $request, $id)
    {
        $request->validate([
            'image'   => 'required|image|max:12288',
            'caption' => 'nullable|string|max:255'
        ]);

        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->findOrFail($id);

        if ($request->file('image')->isValid()) {
            $file = $request->file('image');
            $securedPath = $this->compressAndVaultFieldPhoto($file, $estimate->id);

            JobAttachment::create([
                'estimate_id' => $estimate->id,
                'file_path'   => $securedPath,
                'file_type'   => 'image',
                'caption'     => $request->caption ?? 'Field status update log'
            ]);

            return back()->with('status', '📸 Progress photo successfully bound to project history archive.');
        }

        return back()->with('error', 'Failed to read media asset configuration.');
    }

    /**
     * Authenticates incoming temporary signatures before serving file stream vectors.
     */
    public function streamAttachment(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            abort(403, '🛑 Security access token expired or operational signature mismatch.');
        }

        $attachment = JobAttachment::findOrFail($id);

        if (!Storage::disk('local')->exists($attachment->file_path)) {
            abort(404, 'Requested project file asset does not exist.');
        }

        $fileContents = Storage::disk('local')->get($attachment->file_path);

        return response($fileContents, 200)->header('Content-Type', 'image/webp');
    }

    /**
     * Safe Plugin-Driven Database Self-Healing Structural Schema Guard.
     */
    private function healEstimateTablesSchema(): void
    {
        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        $estimateTable = (new Estimate())->getTable();
        $itemsTable = (new EstimateItem())->getTable();

        $companiesTable = $prefix . 'companies';
        if (Schema::hasTable($companiesTable)) {
            Schema::table($companiesTable, function (Blueprint $table) use ($companiesTable) {
                if (!Schema::hasColumn($companiesTable, 'sms_phone_number')) {
                    $table->string('sms_phone_number', 50)->nullable();
                }
                if (!Schema::hasColumn($companiesTable, 'stripe_link')) {
                    $table->string('stripe_link', 500)->nullable();
                }
                if (!Schema::hasColumn($companiesTable, 'paypal_link')) {
                    $table->string('paypal_link', 500)->nullable();
                }
                if (!Schema::hasColumn($companiesTable, 'zelle_handle')) {
                    $table->string('zelle_handle', 255)->nullable();
                }
                if (!Schema::hasColumn($companiesTable, 'billing_instructions')) {
                    $table->text('billing_instructions')->nullable();
                }
                if (!Schema::hasColumn($companiesTable, 'google_review_link')) {
                    $table->string('google_review_link', 500)->nullable();
                }
            });
        }

        if (!Schema::hasTable($prefix . 'sms_histories')) {
            Schema::create($prefix . 'sms_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->index();
                $table->unsignedInteger('client_id')->nullable()->index();
                $table->unsignedInteger('estimate_id')->nullable()->index();
                $table->string('direction', 20);
                $table->string('from_number', 50);
                $table->string('to_number', 50);
                $table->text('message_body');
                $table->timestamps();
            });
        }

        if (Schema::hasTable($estimateTable)) {
            Schema::table($estimateTable, function (Blueprint $table) use ($estimateTable) {
                if (!Schema::hasColumn($estimateTable, 'deposit_amount')) {
                    $table->decimal('deposit_amount', 15, 2)->default(0.00)->after('tax_rate');
                }
                if (!Schema::hasColumn($estimateTable, 'signature_name')) {
                    $table->string('signature_name')->nullable()->after('status');
                }
                if (!Schema::hasColumn($estimateTable, 'signed_at')) {
                    $table->timestamp('signed_at')->nullable()->after('signature_name');
                }
            });
        }

        if (Schema::hasTable($itemsTable)) {
            Schema::table($itemsTable, function (Blueprint $table) use ($itemsTable) {
                if (!Schema::hasColumn($itemsTable, 'is_taxable')) {
                    $table->boolean('is_taxable')->default(1)->after('total_price');
                }
            });
        }
    }
}
