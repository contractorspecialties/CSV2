<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Estimate;
use App\Models\EstimateItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstimateController extends Controller
{
    /**
     * Commit the dynamic multi-row estimation matrix and map inline customers safely.
     */
    public function store(Request $request)
    {
        // 1. Enforce strict data input arrays
        $validated = $request->validate([
            // Customer Inputs
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'customer_address' => 'nullable|string|max:500',

            // Estimate Parameters
            'tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'expires_at' => 'nullable|date|after:today',

            // Dynamic Nested Line Item Rows
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0.00',
        ]);

        $companyId = Auth::user()->company_id;

        // 2. Wrap operations inside a single secure database transaction
        $estimate = DB::transaction(function () use ($validated, $companyId) {

            // 3. Smart Customer Check: Find existing customer or create a new profile inline
            $customer = Customer::firstOrCreate(
                [
                    'company_id' => $companyId,
                    'email' => $validated['customer_email']
                ],
                [
                    'first_name' => $validated['customer_first_name'],
                    'last_name' => $validated['customer_last_name'],
                    'phone' => $validated['customer_phone'],
                    'billing_address' => $validated['customer_address'],
                ]
            );

            // 4. Generate a unique, scannable corporate tracking reference string
            $estimateNumber = 'EST-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);

            // 5. Spin up the top-level estimate profile tracking frame
            $estimate = Estimate::create([
                'company_id' => $companyId,
                'customer_id' => $customer->id,
                'estimate_number' => $estimateNumber,
                'status' => 'draft',
                'tax_rate' => $validated['tax_rate'],
                'notes' => $validated['notes'],
                'expires_at' => $validated['expires_at'] ?? now()->addDays(30),
                'subtotal' => 0.00, // Will update via internal loop math below
                'grand_total' => 0.00,
            ]);

            $calculatedSubtotal = 0;

            // 6. Process financial logic on the backend for absolute verification security
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

            // 7. Apply corporate taxation calculations
            $taxAmount = $calculatedSubtotal * ($validated['tax_rate'] / 100);
            $calculatedGrandTotal = $calculatedSubtotal + $taxAmount;

            // 8. Commit the absolute calculated financial valuations to the row entry
            $estimate->update([
                'subtotal' => $calculatedSubtotal,
                'grand_total' => $calculatedGrandTotal,
            ]);

            return $estimate;
        });

        return redirect()->route('dashboard')->with('status', "⚡ Estimate {$estimate->estimate_number} successfully compiled and linked to customer records.");
    }
}
