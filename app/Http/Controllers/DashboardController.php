<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the simplified contractor dashboard terminal with aggregated live data metrics.
     */
    public function index()
    {
        $companyId = Auth::user()->company_id;

        // Fetch recent customers and calculate their lifetime work value safely via DB aggregates
        $recentCustomers = Customer::where('company_id', $companyId)
            ->withSum(['estimates as lifetime_value' => function ($query) {
                $query->where('status', 'approved');
            }], 'grand_total')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($customer) {
                // Sanitize null values to absolute float representations for template number formatters
                $customer->lifetime_value = $customer->lifetime_value ?? 0.00;
                return $customer;
            });

        return view('dashboard', compact('recentCustomers'));
    }
}
