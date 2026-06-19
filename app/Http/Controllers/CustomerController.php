<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CustomerController extends Controller
{
    /**
     * Display the prioritized CRM directory loop with active lookup variables.
     */
    public function index(Request $request)
    {
        $query = Customer::where('company_id', Auth::user()->company_id)
            ->withSum(['estimates as lifetime_value' => function($q) {
                $q->where('status', 'approved');
            }], 'grand_total');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('last_name', 'asc')->get()->map(function ($customer) {
            $customer->lifetime_value = $customer->lifetime_value ?? 0.00;
            return $customer;
        });

        return view('customers.index', compact('customers'));
    }

    /**
     * Render fast field context customer creation card.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Commit a fresh target account profile inline.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'billing_address' => 'nullable|string|max:500'
        ]);

        $parts = explode(' ', trim($validated['name']), 2);
        $firstName = $parts[0];
        $lastName = $parts[1] ?? ' ';

        Customer::updateOrCreate(
            [
                'company_id' => Auth::user()->company_id,
                'email' => $validated['email'],
            ],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $validated['phone'] ?? null,
                'billing_address' => $validated['billing_address'] ?? null,
            ]
        );

        return redirect()->route('customers.index')->with('status', '⚡ New customer profile successfully saved to your list.');
    }

    /**
     * Process inline CRM profile edits securely.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::where('company_id', Auth::user()->company_id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'billing_address' => 'nullable|string|max:500'
        ]);

        $parts = explode(' ', trim($validated['name']), 2);
        $firstName = $parts[0];
        $lastName = $parts[1] ?? ' ';

        $customer->update([
            'first_name'      => $firstName,
            'last_name'       => $lastName,
            'email'           => $validated['email'],
            'phone'           => $validated['phone'] ?? null,
            'billing_address' => $validated['billing_address'] ?? null,
        ]);

        return redirect()->route('customers.index')->with('status', '🔄 Customer profile details successfully updated.');
    }

    /**
     * Purge a customer profile record from the tenant partition.
     */
    public function destroy($id)
    {
        $customer = Customer::where('company_id', Auth::user()->company_id)->findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('status', '🗑️ Customer account cleanly scrubbed from company directory registries.');
    }

    /**
     * Stream memory-isolated CSV file straight down port pipelines for CRM logging backup.
     */
    public function exportCsv()
    {
        $customers = Customer::where('company_id', Auth::user()->company_id)
            ->withSum(['estimates as lifetime_value' => function($q) {
                $q->where('status', 'approved');
            }], 'grand_total')
            ->orderBy('last_name', 'asc')
            ->get();

        $fileName = 'customer_list_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'First Name', 'Last Name', 'Email Address', 'Phone Number', 'Billing Address', 'Lifetime Value ($)'];

        $callback = function() use($customers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->first_name,
                    $customer->last_name,
                    $customer->email,
                    $customer->phone ?? 'N/A',
                    $customer->billing_address ?? 'N/A',
                    number_format($customer->lifetime_value ?? 0.00, 2, '.', '')
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
