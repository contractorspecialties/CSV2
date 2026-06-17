<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::where('company_id', Auth::user()->company_id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('last_name')->get();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
        ]);

        // Clean string parser splits names safely for the database rows
        $parts = explode(' ', trim($validated['name']), 2);
        $firstName = $parts[0];
        $lastName = $parts[1] ?? ' ';

        Customer::updateOrCreate(
            [
                'company_id' => Auth::user()->company_id,
                'email' => $validated['email'],
                'phone' => $validated['phone']
            ],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]
        );

        return redirect()->route('customers.index')->with('status', '⚡ New customer profile successfully saved to your list.');
    }

    public function exportCsv()
    {
        $customers = Customer::where('company_id', Auth::user()->company_id)->orderBy('last_name')->get();

        $fileName = 'customer_list_export_' . time() . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'First Name', 'Last Name', 'Email Address', 'Phone Number', 'Lifetime Value ($)'];

        $callback = function() use($customers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->first_name,
                    $customer->last_name,
                    $customer->email,
                    $customer->phone,
                    $customer->lifetime_value
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
