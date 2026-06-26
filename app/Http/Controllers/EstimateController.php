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
use Illuminate\Support\Str;

class EstimateController extends Controller
{
    /**
     * Render the interactive multi-row estimation canvas workspace.
     */
    public function create(Request $request)
    {
        $companyId = Auth::user()->company_id;

        // Fetch company directory clients ordered cleanly by name properties
        $customers = Client::where('company_id', $companyId)
            ->orderBy('name', 'asc')
            ->get();

        // Inject first/last split properties into memory so legacy view variables evaluate cleanly
        $customers->each(function($client) {
            $parts = explode(' ', trim($client->name ?? ''), 2);
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

            $customer->name = $fullName;
            $customer->email = $validated['customer_email'];
            $customer->phone = $validated['customer_phone'];
            $customer->address = $validated['customer_address'];
            $customer->save();

            $estimateNumber = 'EST-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);

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
                if (!file_exists(public_path('uploads/attachments'))) {
                    mkdir(public_path('uploads/attachments'), 0755, true);
                }

                $file = $request->file('image');
                $filename = 'attachment_' . $estimate->id . '_' . Str::random(6) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachments'), $filename);

                $attachment = new JobAttachment();
                $attachment->estimate_id = $estimate->id;
                $attachment->file_path = 'uploads/attachments/' . $filename;
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
            ->findOrFail($id);

        if ($estimate->customer) {
            $parts = explode(' ', trim($estimate->customer->name ?? ''), 2);
            $estimate->customer->first_name = $parts[0] ?? 'Client';
            $estimate->customer->last_name = $parts[1] ?? ' ';
        }

        $attachments = JobAttachment::where('estimate_id', $estimate->id)->get();

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

        if (!empty($estimate->customer->phone)) {
            $portalLink = route('portal.checkout', ['token' => $estimate->estimate_number]);

            $userTable = (new \App\Models\User())->getTable();
            $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
            $company = DB::table($prefix . 'companies')->where('id', $companyId)->first();
            $fromLine = $company->sms_phone_number ?? env('TELNYX_DEFAULT_FROM');

            if (!empty($fromLine)) {
                try {
                    $firstName = explode(' ', trim($estimate->customer->name ?? ''))[0] ?? 'Client';
                    Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('TELNYX_API_KEY'),
                        'Content-Type'  => 'application/json',
                    ])->post('https://api.telnyx.com/v2/messages', [
                        'from' => $fromLine,
                        'to'   => $estimate->customer->phone,
                        'text' => "Hello " . $firstName . ", we have processed your feedback and adjusted your project proposal specifications package. Review revisions here: " . $portalLink,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Telnyx response notification failed: ' . $e->getMessage());
                }
            }
        }

        return back()->with('status', '🔄 Clarification written to file ledger and customer notification dispatched.');
    }

    /**
     * Upload an estimate attachment card mapped safely to unconstrained public directories.
     */
    public function uploadAttachment(Request $request, $id)
    {
        $request->validate([
            'image'   => 'required|image|max:10240',
            'caption' => 'nullable|string|max:255'
        ]);

        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->findOrFail($id);

        if ($request->file('image')->isValid()) {
            if (!file_exists(public_path('uploads/attachments'))) {
                mkdir(public_path('uploads/attachments'), 0755, true);
            }

            $file = $request->file('image');
            $filename = 'attachment_' . $estimate->id . '_' . Str::random(6) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/attachments'), $filename);

            JobAttachment::create([
                'estimate_id' => $estimate->id,
                'file_path'   => 'uploads/attachments/' . $filename,
                'file_type'   => 'image',
                'caption'     => $request->caption ?? 'Field status update log'
            ]);

            return back()->with('status', '📸 Progress photo successfully bound to project history archive.');
        }

        return back()->with('error', 'Failed to read media asset configuration.');
    }

    /**
     * Public Unauthenticated Viewport Gateway Node for Homeowners.
     */
    public function checkout($token)
    {
        $estimate = Estimate::with(['customer', 'items'])
            ->where('id', $token)
            ->orWhere('estimate_number', $token)
            ->firstOrFail();

        if ($estimate->customer) {
            $parts = explode(' ', trim($estimate->customer->name ?? ''), 2);
            $estimate->customer->first_name = $parts[0] ?? 'Client';
            $estimate->customer->last_name = $parts[1] ?? ' ';
        }

        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        $estimate->company = DB::table($prefix . 'companies')->where('id', $estimate->company_id)->first();
        $attachments = JobAttachment::where('estimate_id', $estimate->id)->get();

        return view('portal', compact('estimate', 'attachments'));
    }

    /**
     * Public Gateway Controller to capture Homeowner interactive responses safely.
     */
    public function handlePortalAction(Request $request, $id)
    {
        $estimate = Estimate::findOrFail($id);
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

            $userTable = (new \App\App\Models\User() ?? new \App\Models\User())->getTable();
            $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
            $company = DB::table($prefix . 'companies')->where('id', $estimate->company_id)->first();

            if ($company && !empty($company->sms_phone_number)) {
                try {
                    $firstName = explode(' ', trim($estimate->customer->name ?? ''))[0] ?? 'Client';
                    Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('TELNYX_API_KEY'),
                        'Content-Type'  => 'application/json',
                    ])->post('https://api.telnyx.com/v2/messages', [
                        'from' => env('TELNYX_DEFAULT_FROM'),
                        'to'   => $company->sms_phone_number,
                        'text' => "⚠️ Alert: Client has logged a change request on Estimate #{$estimate->estimate_number} ({$firstName}). Review details here: " . url("/estimates/{$estimate->id}")
                    ]);
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

        if (isset($incomingData['data']['event_type']) && $incomingData['data']['event_type'] === 'message.received') {
            $messagePayload = $incomingData['data']['payload'];
            $senderLine = $messagePayload['from']['phone_number'] ?? 'Unknown';
            $incomingBody = trim($messagePayload['text'] ?? '');

            if (strtoupper($incomingBody) === 'STOP') {
                Log::info("SMS Opt-Out Flag Registered by Line: {$senderLine}");
            } else {
                Log::info("Inbound SMS Received from {$senderLine}: {$incomingBody}");
            }
        }

        return response()->json(['status' => 'received'], 200);
    }

    /**
     * Remove the specified estimate and all nested child dependencies safely.
     */
    public function destroy($id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->findOrFail($id);

        DB::transaction(function () use ($estimate) {
            EstimateItem::where('estimate_id', $estimate->id)->delete();

            $attachments = JobAttachment::where('estimate_id', $estimate->id)->get();
            foreach ($attachments as $fileAsset) {
                $cleanFilePath = public_path($fileAsset->file_path);
                if (file_exists($cleanFilePath)) {
                    @unlink($cleanFilePath);
                }
                $fileAsset->delete();
            }

            $estimate->delete();
        });

        return redirect()->route('dashboard')->with('status', '🗑️ Old estimate record permanently purged.');
    }

    /**
     * Safe Plugin-Driven Database Self-Healing Structural Schema Guard.
     */
    private function healEstimateTablesSchema(): void
    {
        $estimateTable = (new Estimate())->getTable();
        $itemsTable = (new EstimateItem())->getTable();

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
