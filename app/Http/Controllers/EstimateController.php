<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Estimate;
use App\Models\EstimateItem;
use App\Models\JobAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $customers = Customer::where('company_id', $companyId)
            ->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc')
            ->get();

        $pricebookItems = [];
        if (DB::connection()->getSchemaBuilder()->hasTable('pricebooks')) {
            $pricebookItems = DB::table('pricebooks')->where('company_id', $companyId)->get();
        }

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
            'customer_last_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'customer_address' => 'nullable|string|max:500',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'expires_at' => 'nullable|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0.00',
        ]);

        $companyId = Auth::user()->company_id;

        $estimate = DB::transaction(function () use ($validated, $companyId) {
            $customer = Customer::firstOrCreate(
                ['company_id' => $companyId, 'email' => $validated['customer_email']],
                [
                    'first_name' => $validated['customer_first_name'],
                    'last_name' => $validated['customer_last_name'],
                    'phone' => $validated['customer_phone'],
                    'billing_address' => $validated['customer_address'],
                ]
            );

            $estimateNumber = 'EST-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);

            $estimate = Estimate::create([
                'company_id' => $companyId,
                'customer_id' => $customer->id,
                'estimate_number' => $estimateNumber,
                'status' => 'draft',
                'tax_rate' => $validated['tax_rate'],
                'notes' => $validated['notes'],
                'expires_at' => $validated['expires_at'] ?? now()->addDays(30),
                'subtotal' => 0.00,
                'grand_total' => 0.00,
            ]);

            $calculatedSubtotal = 0;

            foreach ($validated['items'] as $itemData) {
                $itemTotal = $itemData['quantity'] * $itemData['unit_price'];
                $calculatedSubtotal += $itemTotal;

                EstimateItem::create([
                    'estimate_id' => $estimate->id,
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemTotal,
                ]);
            }

            $taxAmount = $calculatedSubtotal * ($validated['tax_rate'] / 100);
            $calculatedGrandTotal = $calculatedSubtotal + $taxAmount;

            $estimate->update([
                'subtotal' => $calculatedSubtotal,
                'grand_total' => $calculatedGrandTotal,
            ]);

            return $estimate;
        });

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
                'company_id' => $companyId,
                'customer_id' => $estimate->customer_id,
                'estimate_id' => $estimate->id,
                'title' => "Production Run: " . $estimate->estimate_number,
                'scheduled_at' => now(),
                'status' => 'scheduled'
            ]);
        }

        return back()->with('status', "🔄 Status changed to " . strtoupper($request->status));
    }

    /**
     * Intercept and handle progress attachment photo uploads from the field.
     */
    public function uploadAttachment(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
            'caption' => 'nullable|string|max:255'
        ]);

        $companyId = Auth::user()->company_id;
        $estimate = Estimate::where('company_id', $companyId)->findOrFail($id);

        if ($request->file('image')->isValid()) {
            $path = $request->file('image')->store('attachments', 'public');

            JobAttachment::create([
                'estimate_id' => $estimate->id,
                'file_path' => '/storage/' . $path,
                'file_type' => 'image',
                'caption' => $request->caption ?? 'Field status update log'
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
        // Safe contextual resolution via either database ID index mapping or Reference number string
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

        if ($action === 'schedule') {
            $estimate->update(['status' => 'approved']);

            // Programmatically inject and schedule their installation run
            \App\Models\Appointment::create([
                'company_id'  => $estimate->company_id,
                'customer_id' => $estimate->customer_id,
                'estimate_id' => $estimate->id,
                'title'       => "Production: " . $estimate->estimate_number,
                'scheduled_at'=> now()->addDays(2), // Mock placement 48 hours out
                'status'      => 'scheduled',
                'notes'       => 'Client portal scheduled auto-activation confirmation.'
            ]);

            return back()->with('status', '✍️ Project approved! Your job site mobilization window has been added straight to our master production dispatch board.');
        }

        if ($action === 'revision') {
            $request->validate(['notes' => 'required|string|max:1000']);

            // Log modification requests into notes for estimator evaluation
            $estimate->update([
                'notes' => "🚨 Homeowner Modification Request:\n" . $request->notes . "\n\n" . $estimate->notes
            ]);

            return back()->with('status', '💬 Your correction notes have been logged straight onto the blueprint ledger. Your project administrator will reach out shortly.');
        }

        return back();
    }
}
