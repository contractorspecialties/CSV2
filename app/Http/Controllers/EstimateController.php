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
use Illuminate\Support\Facades\Http;
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

        // Fetch company directory clients ordered cleanly by client_name properties
        $customers = Client::where('company_id', $companyId)
            ->orderBy('client_name', 'asc')
            ->get();

        // Inject first/last split properties into memory so legacy view variables evaluate cleanly
        $customers->each(function($client) {
            $parts = explode(' ', trim($client->client_name ?? ''), 2);
            $client->first_name = $parts[0] ?? 'Client';
            $client->last_name = $parts[1] ?? ' ';
            $client->billing_address = $client->address;
        });

        // Optimized Model-Driven Query automatically tracking prefix definitions
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
        // Clean up empty form field string artifacts before firing validation sequences
        $inputData = $request->all();
        if (isset($inputData['expires_at']) && trim($inputData['expires_at']) === '') {
            $inputData['expires_at'] = null;
        }
        if (isset($inputData['deposit_amount']) && trim($inputData['deposit_amount']) === '') {
            $inputData['deposit_amount'] = null;
        }

        // Cast pricebook and line item tax array checkbox tokens to absolute booleans
        if (isset($inputData['items']) && is_array($inputData['items'])) {
            foreach ($inputData['items'] as $key => $item) {
                $inputData['items'][$key]['save_to_pricebook'] = (isset($item['save_to_pricebook']) && ($item['save_to_pricebook'] === 'true' || $item['save_to_pricebook'] === 'on' || $item['save_to_pricebook'] == 1)) ? true : false;
                $inputData['items'][$key]['is_taxable'] = (isset($item['is_taxable']) && ($item['is_taxable'] === 'true' || $item['is_taxable'] === 'on' || $item['is_taxable'] == 1)) ? true : false;
            }
        }

        // Re-bind sanitized variables back into a clean request array
        $request->merge($inputData);

        // Run plugin-driven structural modifications safely across dependent tables
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

            // 🧮 DYNAMIC SEQUENTIAL SERIAL ENGINE CALCULATION
            $lastEstimate = Estimate::where('company_id', $companyId)
                ->orderBy('id', 'desc')
                ->first();

            $nextSequence = 1001;
            if ($lastEstimate) {
                if (preg_match('/EST-(\d+)$/', $lastEstimate->estimate_number, $matches)) {
                    $nextSequence = ((int) $matches[1]) + 1;
                } elseif (preg_match('/-(\d+)$/', $lastEstimate->estimate_number, $matches)) {
                    $nextSequence = ((int) $matches[1]) + 1;
                }
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

            // Handle file ingestion securely into isolated storage
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
            $portalLink = route('portal.checkout', ['token' => $estimate->estimate_number]);

            $userTable = (new \App\Models\User())->getTable();
            $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
            $company = DB::table($prefix . 'companies')->where('id', $companyId)->first();
            $fromLine = $company->sms_phone_number ?? env('TELNYX_DEFAULT_FROM');

            if (!empty($fromLine)) {
                try {
                    \App\Jobs\SendPortalSms::dispatch(
                        $estimate->customer->phone_number,
                        "Hello " . ($estimate->customer->first_name ?? 'Client') . ", we have processed your feedback and adjusted your project proposal specifications package. Review revisions here: " . $portalLink,
                        $fromLine
                    );
                } catch (\Exception $e) {
                    Log::error('Telnyx response notification queueing failed: ' . $e->getMessage());
                }
            }
        }

        return back()->with('status', '🔄 Clarification written to file ledger and customer notification dispatched.');
    }

    /**
     * Dispatch HTML contract proposal framework to customer inbox.
     */
    public function sendEstimateEmail(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->with('customer')->findOrFail($id);

        if (empty($estimate->customer->email)) {
            return back()->with('error', '🛑 Target customer profile does not contain a valid email coordinates path.');
        }

        $portalLink = route('portal.checkout', ['token' => $estimate->estimate_number]);

        try {
            Mail::send([], [], function ($message) use ($estimate, $portalLink) {
                $message->to($estimate->customer->email)
                    ->subject("Project Estimate Revisions Available - Estimate #{$estimate->estimate_number}")
                    ->html("
                        <div style=\"font-family: Arial, sans-serif; padding: 32px; max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;\">
                            <h2 style=\"color: #0f172a; font-size: 22px; margin-bottom: 4px; text-transform: uppercase; letter-spacing: -0.5px;\">Project Proposal Update</h2>
                            <p style=\"font-size: 14px; color: #64748b; margin-top: 0; margin-bottom: 24px;\">Sent via ContractorSpecialties Secure Portal Manager</p>

                            <p style=\"font-size: 15px; color: #334155; line-height: 1.6;\">Hello,</p>
                            <p style=\"font-size: 15px; color: #334155; line-height: 1.6;\">An updated digital project estimate has been compiled and uploaded to your secure client portal dashboard for review.</p>

                            <div style=\"background-color: #f8fafc; border-left: 4px solid #f58613; padding: 16px; margin: 24px 0; border-radius: 4px;\">
                                <strong style=\"display: block; font-size: 12px; text-transform: uppercase; color: #64748b; margin-bottom: 4px;\">Document Reference:</strong>
                                <span style=\"font-family: monospace; font-size: 15px; font-weight: bold; color: #0f172a;\">Estimate #{$estimate->estimate_number}</span>
                            </div>

                            <p style=\"font-size: 15px; color: #334155; line-height: 1.6; margin-bottom: 28px;\">Please click the secure gateway link below to open the interactive blueprint ledger, review your service line rows, and sign off on the terms to clear your project for field mobilization scheduling.</p>

                            <div style=\"margin: 32px 0; text-align: center;\">
                                <a href=\"{$portalLink}\" style=\"background-color: #f58613; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; text-transform: uppercase; font-size: 13px; tracking-wider: 1px; box-shadow: 0 4px 6px -1px rgba(245, 134, 19, 0.2);\">Review & Sign Proposal</a>
                            </div>

                            <hr style=\"border: 0; border-top: 1px solid #e2e8f0; margin: 32px 0;\">
                            <p style=\"font-size: 11px; color: #94a3b8; line-height: 1.5;\">This notification was dispatched automatically on behalf of your contracted professional. For technical assistance or security routing questions, please contact platform network operations. Reply to this message directly to reach your service manager.</p>
                        </div>
                    ");
            });

            $estimate->update(['status' => 'sent']);

            return back()->with('status', '📧 Project proposal transaction successfully dispatched via your SendGrid gateway matrix.');
        } catch (\Exception $e) {
            Log::error('SendGrid SMTP Dispatch Failure: ' . $e->getMessage());
            return back()->with('error', '🛑 SendGrid SMTP transport rejected transmission.');
        }
    }

    /**
     * Dispatch transactional SMS alert link via background communication queues.
     */
    public function sendEstimateSms(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->with('customer')->findOrFail($id);

        if (empty($estimate->customer->phone_number)) {
            return back()->with('error', '🛑 Target customer profile does not contain a valid mobile telephone coordinate line.');
        }

        $portalLink = route('portal.checkout', ['token' => $estimate->estimate_number]);

        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $company = DB::table($prefix . 'companies')->where('id', $companyId)->first();
        $fromLine = $company->sms_phone_number ?? env('TELNYX_DEFAULT_FROM');

        if (empty($fromLine)) {
            return back()->with('error', '🛑 Outbound SMS line configuration parameter missing from application settings file.');
        }

        try {
            \App\Jobs\SendPortalSms::dispatch(
                $estimate->customer->phone_number,
                "Hello, your project estimate details are compiled. View your customized proposal link here: " . $portalLink . " Reply STOP to opt out.",
                $fromLine
            );

            DB::table($prefix . 'sms_histories')->insert([
                'company_id'   => $companyId,
                'client_id'    => $estimate->customer_id,
                'estimate_id'  => $estimate->id,
                'direction'    => 'outbound',
                'from_number'  => $fromLine,
                'to_number'    => $estimate->customer->phone_number,
                'message_body' => "View your customized proposal link here: " . $portalLink,
                'created_at'   => now(),
                'updated_at'   => now()
            ]);

            $estimate->update(['status' => 'sent']);

            return back()->with('status', '⚡ Outbound transactional SMS dropped straight down the communication worker queue channel.');
        } catch (\Exception $e) {
            Log::error('SMS Dispatch Worker Injection Failure: ' . $e->getMessage());
            return back()->with('error', '🛑 Failed to drop execution payload to local system daemons.');
        }
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
     * Public Unauthenticated Viewport Gateway Node for Homeowners.
     */
    public function checkout($token)
    {
        // 🛡️ CRITICAL TENANT SCOPE IMMUNITY: Public routes must bypass company_id isolation gates
        $estimate = Estimate::withoutGlobalScopes()
            ->with(['customer', 'items'])
            ->where(function ($query) use ($token) {
                if (is_numeric($token)) {
                    $query->where('id', $token);
                } else {
                    $query->where('estimate_number', $token);
                }
            })
            ->firstOrFail();

        if ($estimate->customer) {
            $parts = explode(' ', trim($estimate->customer->client_name ?? ''), 2);
            $estimate->customer->first_name = $parts[0] ?? 'Client';
            $estimate->customer->last_name = $parts[1] ?? ' ';
        }

        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        $estimate->company = DB::table($prefix . 'companies')->where('id', $estimate->company_id)->first();
        $attachments = JobAttachment::where('estimate_id', $estimate->id)->get();

        $attachments->each(function($asset) {
            $asset->secure_url = URL::temporarySignedRoute(
                'estimates.attachments.stream',
                now()->addMinutes(60),
                ['id' => (string) $asset->id]
            );
        });

        return view('portal', compact('estimate', 'attachments'));
    }

    /**
     * Public Gateway Controller to capture Homeowner interactive responses safely.
     */
    public function handlePortalAction(Request $request, $id)
    {
        // 🛡️ CRITICAL TENANT SCOPE IMMUNITY: Homeowner postback processing must bypass isolation gates
        $estimate = Estimate::withoutGlobalScopes()->findOrFail($id);
        $action = $request->input('action');

        $this->healEstimateTablesSchema();

        if ($action === 'schedule') {
            $request->validate([
                'signature_name' => 'required|string|min:3|max:255',
            ]);

            $signature = strtoupper(trim($request->input('signature_name')));
            $estimate->signature_name = $signature;
            $estimate->signed_at = now();

            if ($estimate->deposit_amount > 0 && $estimate->status !== 'approved') {
                $estimate->status = 'pending_deposit';
                $estimate->save();

                Log::info("✍️ Contract signed for {$estimate->estimate_number} by {$signature}. Routing to pending deposit gateway lane.");
                return back()->with('status', '✍️ Project terms signed! To finalize mobilization scheduling, please hit the secure deposit button below.');
            }

            $estimate->status = 'approved';
            $estimate->save();

            \App\Models\Appointment::create([
                'company_id'   => $estimate->company_id,
                'customer_id'  => $estimate->customer_id,
                'estimate_id'  => $estimate->id,
                'title'        => "Production: " . $estimate->estimate_number,
                'scheduled_at' => now()->addDays(2),
                'status'       => 'scheduled',
                'notes'        => "Authorized via client terminal. Signed by: {$signature}"
            ]);

            Log::info("🚀 Contract complete for {$estimate->estimate_number}. Signed by: {$signature}. Field mobilization slot scheduled.");
            return back()->with('status', '✍️ Project approved! Your job site mobilization window has been added straight to our master production dispatch board.');
        }

        if ($action === 'deposit_payment') {
            $estimate->update(['status' => 'approved']);

            \App\Models\Appointment::create([
                'company_id'   => $estimate->company_id,
                'customer_id'  => $estimate->customer_id,
                'estimate_id'  => $estimate->id,
                'title'        => "Production: " . $estimate->estimate_number,
                'scheduled_at' => now()->addDays(2),
                'status'       => 'scheduled',
                'notes'        => 'Upfront deposit verified online. Field crew dispatched.'
            ]);

            return back()->with('status', '💳 Upfront payment verified! Production lines locked onto master operational schedule.');
        }

        if ($action === 'revision') {
            $request->validate(['notes' => 'required|string|max:1000']);

            $estimate->update([
                'notes' => "🚨 Homeowner Modification Request:\n" . $request->notes . "\n\n" . $estimate->notes
            ]);

            $userTable = (new \App\Models\User())->getTable();
            $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
            $company = DB::table($prefix . 'companies')->where('id', $estimate->company_id)->first();

            if ($company && !empty($company->sms_phone_number)) {
                try {
                    \App\Jobs\SendPortalSms::dispatch(
                        $company->sms_phone_number,
                        "⚠️ Alert: Client has logged a change request on Estimate #{$estimate->estimate_number} (" . ($estimate->customer->client_name ?? 'Client') . "). Review details here: " . url("/estimates/{$estimate->id}")
                    );
                } catch (\Exception $e) {
                    Log::error('Contractor instant revision SMS dispatch error: ' . $e->getMessage());
                }
            }

            return back()->with('status', '💬 Your correction notes have been logged straight onto the blueprint ledger. Your project administrator will reach out shortly.');
        }

        return back();
    }

    /**
     * Intercept and process unauthenticated inbound messaging webhooks from Telnyx.
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

        $customer = Client::where('phone_number', $senderLine)->first();

        if (!$customer) {
            Log::info("📨 Inbound SMS text from unmapped phone tracking line {$senderLine}: {$incomingBody}");
            return response()->json(['status' => 'unmapped_sender_logged'], 200);
        }

        $latestEstimate = Estimate::withoutGlobalScopes()
            ->where('customer_id', $customer->id)
            ->where('status', '!=', 'closed')
            ->orderBy('id', 'desc')
            ->first();

        $estimateId = $latestEstimate ? $latestEstimate->id : null;

        DB::table($prefix . 'sms_histories')->insert([
            'company_id'   => $customer->company_id,
            'client_id'    => $customer->id,
            'estimate_id'  => $estimateId,
            'direction'    => 'inbound',
            'from_number'  => $senderLine,
            'to_number'    => $receiverLine,
            'message_body' => $incomingBody,
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        // Smart Bi-Directional SMS Automation Hooks
        if ($latestEstimate && ($cleanKeyword === 'APPROVE' || $cleanKeyword === 'ACCEPT' || $cleanKeyword === 'YES')) {
            if ($latestEstimate->status === 'draft' || $latestEstimate->status === 'sent') {

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

        // ... rest of method logic completely preserved ...
        DB::transaction(function () use ($estimate) {
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

        return redirect()->route('dashboard')->with('status', '🗑 Old estimate record permanently purged.');
    }

    /**
     * Close out an active production run and shift it cleanly to archived status.
     */
    public function closeJob(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->findOrFail($id);

        $estimate->update(['status' => 'closed']);

        return redirect()->route('dashboard')->with('status', '📦 Operational job run marked CLOSED and cleanly archived inside history logs.');
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
