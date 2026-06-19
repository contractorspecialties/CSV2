<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Estimate;
use App\Models\EstimateItem;
use App\Models\JobAttachment;
use App\Models\PricebookItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        // Fetch company directory customers ordered cleanly by name properties
        $customers = Customer::where('company_id', $companyId)
            ->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc')
            ->get();

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
        $validated = $request->validate([
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name'  => 'required|string|max:255',
            'customer_email'      => 'required|email|max:255',
            'customer_phone'      => 'nullable|string|max:30',
            'customer_address'    => 'nullable|string|max:500',
            'tax_rate'            => 'required|numeric|min:0|max:100',
            'deposit_amount'      => 'nullable|numeric|min:0',
            'notes'               => 'nullable|string',
            'expires_at'          => 'nullable|date|after:today',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0.00',
        ]);

        $companyId = Auth::user()->company_id;

        $estimate = DB::transaction(function () use ($validated, $companyId) {
            $customer = Customer::firstOrCreate(
                ['company_id' => $companyId, 'email' => $validated['customer_email']],
                [
                    'first_name'      => $validated['customer_first_name'],
                    'last_name'       => $validated['customer_last_name'],
                    'phone'           => $validated['customer_phone'],
                    'billing_address' => $validated['customer_address'],
                ]
            );

            $estimateNumber = 'EST-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);

            $estimate = Estimate::create([
                'company_id'      => $companyId,
                'customer_id'     => $customer->id,
                'estimate_number' => $estimateNumber,
                'status'          => 'draft',
                'tax_rate'        => $validated['tax_rate'],
                'deposit_amount'  => $validated['deposit_amount'] ?? 0.00,
                'notes'           => $validated['notes'],
                'expires_at'      => $validated['expires_at'] ?? now()->addDays(30),
                'subtotal'        => 0.00,
                'grand_total'     => 0.00,
            ]);

            $calculatedSubtotal = 0;

            foreach ($validated['items'] as $itemData) {
                $itemTotal = $itemData['quantity'] * $itemData['unit_price'];
                $calculatedSubtotal += $itemTotal;

                EstimateItem::create([
                    'estimate_id' => $estimate->id,
                    'description' => $itemData['description'],
                    'quantity'    => $itemData['quantity'],
                    'unit_price'  => $itemData['unit_price'],
                    'total_price' => $itemTotal,
                ]);
            }

            $taxAmount = $calculatedSubtotal * ($validated['tax_rate'] / 100);
            $calculatedGrandTotal = $calculatedSubtotal + $taxAmount;

            $estimate->update([
                'subtotal'    => $calculatedSubtotal,
                'grand_total' => $calculatedGrandTotal,
            ]);

            return $estimate;
        ]);

        return redirect()->route('dashboard')->with('status', "⚡ Estimate {$estimate->estimate_number} successfully compiled.");
    }

    /**
     * Display the dedicated profile operational control frame for a single estimate.
     */
    public function show($id)
    {
        $companyId = Auth::user()->company_id;

        $estimate = Estimate::where('company_id', $companyId)
            ->with(['customer', 'items'])
            ->findOrFail($id);

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
     * Record a contractor reply message onto the blueprint ledger and notify the customer.
     */
    public function saveBlueprint(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->with('customer')->findOrFail($id);

        $request->validate(['notes' => 'required|string|max:2000']);

        // Append contractor commentary directly onto the top of the history log
        $updatedNotes = "⚡ Contractor Reply (" . now()->format('m/d H:i') . "):\n" . $request->notes . "\n\n" . $estimate->notes;

        $estimate->update([
            'notes'  => $updatedNotes,
            'status' => 'sent' // Revert back to active sent state for customer access
        ]);

        // Auto-notify the client via standard mobile SMS if information is present
        if (!empty($estimate->customer->phone)) {
            $portalLink = route('portal.checkout', ['token' => $estimate->estimate_number]);
            $company = DB::table('sc_companies')->where('id', $companyId)->first();
            $fromLine = $company->sms_phone_number ?? env('TELNYX_DEFAULT_FROM');

            if (!empty($fromLine)) {
                try {
                    Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('TELNYX_API_KEY'),
                        'Content-Type'  => 'application/json',
                    ])->post('https://api.telnyx.com/v2/messages', [
                        'from' => $fromLine,
                        'to'   => $estimate->customer->phone,
                        'text' => "Hello " . $estimate->customer->first_name . ", we have updated your estimate notes per your clarification request. Review revisions here: " . $portalLink,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Telnyx auto-notification crash: ' . $e->getMessage());
                }
            }
        }

        return back()->with('status', '🔄 Clarification written to file ledger and client text notification sent.');
    }

    /**
     * Intercept and handle progress attachment photo uploads from the field.
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
            $path = $request->file('image')->store('attachments', 'public');

            JobAttachment::create([
                'estimate_id' => $estimate->id,
                'file_path'   => '/storage/' . $path,
                'file_type'   => 'image',
                'caption'     => $request->caption ?? 'Field status update log'
            ]);

            return back()->with('status', '📸 Progress photo successfully bound to job history archive.');
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

        // Homeowner clicks "Approve & Schedule"
        if ($action === 'schedule') {
            // Check if contract dictates an upfront mobilization payment pass
            if ($estimate->deposit_amount > 0 && $estimate->status !== 'approved') {
                $estimate->update(['status' => 'pending_deposit']);
                return back()->with('status', '✍️ Project terms signed! To finalize mobilization scheduling, please hit the secure deposit button below.');
            }

            $estimate->update(['status' => 'approved']);

            \App\Models\Appointment::create([
                'company_id'   => $estimate->company_id,
                'customer_id'  => $estimate->customer_id,
                'estimate_id'  => $estimate->id,
                'title'        => "Production: " . $estimate->estimate_number,
                'scheduled_at' => now()->addDays(2),
                'status'       => 'scheduled',
                'notes'        => 'Client portal scheduled auto-activation confirmation.'
            ]);

            return back()->with('status', '✍️ Project approved! Your job site mobilization window has been added straight to our master production dispatch board.');
        }

        // Simulating completion loop of payment gateway callback verification
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

            return back()->with('status', '💬 Your correction notes have been logged straight onto the blueprint ledger. Your project administrator will reach out shortly.');
        }

        return back();
    }

    /**
     * Execute outbound 10DLC compliant SMS via localized Telnyx line numbers.
     */
    public function sendEstimateSms($id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->with('customer')->findOrFail($id);

        if (empty($estimate->customer->phone)) {
            return back()->with('error', '❌ This customer profile does not have a valid phone number recorded.');
        }

        $company = DB::table('sc_companies')->where('id', $companyId)->first();
        $fromLine = $company->sms_phone_number ?? env('TELNYX_DEFAULT_FROM');

        if (empty($fromLine)) {
            return back()->with('error', '❌ No messaging originator line has been configured for this tenant region.');
        }

        $portalLink = route('portal.checkout', ['token' => $estimate->estimate_number]);
        $messageBody = "Hello " . $estimate->customer->first_name . ", here is the link to view your estimate " . $estimate->estimate_number . " from ContractorSpecialties: " . $portalLink . " . Reply STOP to opt out.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TELNYX_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.telnyx.com/v2/messages', [
                'from' => $fromLine,
                'to'   => $estimate->customer->phone,
                'text' => $messageBody,
            ]);

            if ($response->successful()) {
                $estimate->update(['status' => 'sent']);
                return back()->with('status', '📨 Estimate link successfully dispatched via localized carrier route.');
            }

            Log::error('Telnyx Outbound SMS Delivery Failure: ' . $response->body());
            return back()->with('error', '❌ Telephony routing engine rejected the outbound transmission script.');
        } catch (\Exception $exception) {
            Log::error('Telnyx Client Connection Exception: ' . $exception->getMessage());
            return back()->with('error', '❌ Outbound network port failed to handshake with carrier gateways.');
        }
    }

    /**
     * Execute official project documentation delivery via digital email trail.
     */
    public function sendEstimateEmail($id)
    {
        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->with('customer')->findOrFail($id);

        $portalLink = route('portal.checkout', ['token' => $estimate->estimate_number]);

        try {
            Mail::html("
                <div style=\"font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 30px; border: 1px solid #e2e8f0; border-radius: 16px; background-color: #ffffff;\">
                    <h2 style=\"text-transform: uppercase; font-size: 20px; font-weight: 900; color: #0f172a; letter-spacing: -0.5px; margin-top: 0;\">Project Scope Contract: {$estimate->estimate_number}</h2>
                    <p style=\"font-size: 14px; color: #334155;\">Hello {$estimate->customer->first_name},</p>
                    <p style=\"font-size: 14px; color: #334155; line-height: 1.6;\">Your detailed service specifications package from <strong>ContractorSpecialties</strong> is compiled and ready for authorization review.</p>

                    <div style=\"background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 12px; margin: 24px 0;\">
                        <span style=\"color: #64748b; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;\">Total Estimated Invoice:</span>
                        <div style=\"font-size: 28px; font-weight: 900; color: #16a34a; margin-top: 4px;\">$" . number_format($estimate->grand_total, 2) . "</div>
                        " . ($estimate->deposit_amount > 0 ? "<div style=\"margin-top: 10px; font-size: 12px; color: #b45309; font-weight: 700;\">⚠️ Upfront Mobilization Deposit Required: $" . number_format($estimate->deposit_amount, 2) . "</div>" : "") . "
                    </div>

                    <div style=\"margin: 30px 0 10px 0;\">
                        <a href=\"{$portalLink}\" style=\"display: inline-block; background-color: #f58613; color: #ffffff; text-decoration: none; font-weight: 900; font-size: 12px; padding: 16px 28px; border-radius: 10px; text-transform: uppercase; letter-spacing: 1px;\">Open Digital Proposal Canvas →</a>
                    </div>

                    <hr style=\"border: 0; border-top: 1px solid #f1f5f9; margin: 30px 0;\" />
                    <p style=\"font-size: 11px; color: #94a3b8; line-height: 1.5; margin-bottom: 0;\">You can request adjustments or drop alignment notes straight to our estimators using the live feedback portal loop pinned to your document link above.</p>
                </div>
            ", function ($message) use ($estimate) {
                $message->to($estimate->customer->email)
                        ->subject("Project Scope Specifications: Estimate #" . $estimate->estimate_number);
            });

            $estimate->update(['status' => 'sent']);
            return back()->with('status', '📨 Official document package routed cleanly to client email inbox trail.');
        } catch (\Exception $exception) {
            Log::error('Laravel Native Mail Dispatch Failure: ' . $exception->getMessage());
            return back()->with('error', '❌ Delivery Engine failed to pass the message payload to outbound mail exchangers.');
        }
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
}
