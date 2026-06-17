<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PricebookItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the simplified contractor dashboard terminal.
     */
    public function index()
    {
        $companyId = Auth::user()->company_id;

        // Fetch recent customers to populate the overview table
        $recentCustomers = Customer::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('recentCustomers'));
    }
}
