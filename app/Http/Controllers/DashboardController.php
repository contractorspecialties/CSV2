<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Estimate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the contractor dashboard with live metrics, active estimates, and a dynamic weekly scheduling array.
     */
    public function index()
    {
        $companyId = Auth::user()->company_id;

        // 1. Compile Pipeline Financial Indicators safely
        $draftCount = Estimate::where('company_id', $companyId)->where('status', 'draft')->count();
        $sentCount = Estimate::where('company_id', $companyId)->where('status', 'sent')->count();
        $bookedRevenue = Estimate::where('company_id', $companyId)->where('status', 'approved')->sum('grand_total');

        // 2. Fetch All Active Pipeline Estimates to populate the tracking ledger
        $estimates = Estimate::where('company_id', $companyId)
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Fetch Recent Customers with dynamic lifetime approved calculations
        $recentCustomers = Customer::where('company_id', $companyId)
            ->withSum(['estimates as lifetime_value' => function ($query) {
                $query->where('status', 'approved');
            }], 'grand_total')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($customer) {
                $customer->lifetime_value = $customer->lifetime_value ?? 0.00;
                return $customer;
            });

        // 4. Dynamic Calendar Matrix Engine (Monday through Sunday)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $daysOfWeek = [];

        for ($i = 0; $i < 7; $i++) {
            $currentDayDate = $startOfWeek->copy()->addDays($i);

            // Query live database appointment volume for this specific corporate date unit
            $jobsCount = Appointment::where('company_id', $companyId)
                ->whereDate('scheduled_at', $currentDayDate->toDateString())
                ->count();

            // Determine semantic UI state flags dynamically
            if ($currentDayDate->isToday()) {
                $status = 'today';
            } elseif ($currentDayDate->isPast()) {
                $status = 'past';
            } elseif ($currentDayDate->isWeekend()) {
                $status = 'weekend';
            } else {
                $status = 'active';
            }

            $daysOfWeek[] = [
                'name'   => $currentDayDate->format('D'),
                'num'    => $currentDayDate->format('j'),
                'status' => $status,
                'jobs'   => $jobsCount,
            ];
        }

        return view('dashboard', compact('estimates', 'recentCustomers', 'daysOfWeek', 'draftCount', 'sentCount', 'bookedRevenue'));
    }
}
