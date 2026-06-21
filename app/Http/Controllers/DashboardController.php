<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Estimate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the contractor dashboard with live metrics, active estimates, and an interactive week scheduler.
     */
    public function index()
    {
        $companyId = Auth::user()->company_id;

        // 1. Compile Pipeline Financial Indicators safely from the active tenant space
        $draftCount = Estimate::where('company_id', $companyId)->where('status', 'draft')->count();
        $sentCount = Estimate::where('company_id', $companyId)->where('status', 'sent')->count();
        $bookedRevenue = Estimate::where('company_id', $companyId)->where('status', 'approved')->sum('grand_total');

        // 2. Fetch All Company Estimates with Customer Profiles for the Kanban Lane Compilation
        $allEstimates = Estimate::where('company_id', $companyId)
            ->with('customer')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Separate estimates cleanly into dedicated Kanban operational lane arrays
        $kanbanBids = [
            'draft'    => $allEstimates->where('status', 'draft')->values(),
            'sent'     => $allEstimates->where('status', 'sent')->values(),
            'approved' => $allEstimates->where('status', 'approved')->values(),
            'closed'   => $allEstimates->where('status', 'closed')->values(),
        ];

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

        // 4. Optimized Calendar Matrix Engine (Single database fetch grouped in memory for performance)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SUNDAY);

        $weekAppointments = Appointment::where('company_id', $companyId)
            ->whereBetween('scheduled_at', [$startOfWeek->copy()->startOfDay(), $endOfWeek->copy()->endOfDay()])
            ->with(['customer', 'estimate'])
            ->get()
            ->groupBy(function ($appt) {
                return $appt->scheduled_at->toDateString();
            });

        $daysOfWeek = [];

        for ($i = 0; $i < 7; $i++) {
            $currentDayDate = $startOfWeek->copy()->addDays($i);
            $dateString = $currentDayDate->toDateString();

            // Extract pre-loaded appointments for this calendar date node
            $dayJobs = $weekAppointments->get($dateString, collect());

            // Determine semantic UI state flags dynamically to wire Tailwind border/background highlights
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
                'name'         => $currentDayDate->format('D'),
                'num'          => $currentDayDate->format('j'),
                'full_date'    => $currentDayDate->format('l, F jS'),
                'status'       => $status,
                'jobs_count'   => $dayJobs->count(),
                'appointments' => $dayJobs->map(function ($job) {
                    return [
                        'id'              => $job->id,
                        'title'           => $job->title,
                        'time'            => $job->scheduled_at->format('g:i A'),
                        'status'          => $job->status,
                        'notes'           => $job->notes,
                        'customer_name'   => $job->customer ? "{$job->customer->first_name} {$job->customer->last_name}" : 'Unassigned Client',
                        'estimate_id'     => $job->estimate_id,
                        'estimate_number' => $job->estimate ? $job->estimate->estimate_number : null,
                    ];
                })->toArray(),
            ];
        }

        // Pass the structural kanbanBids matrix array down to the view layout variables smoothly
        return view('dashboard', compact('kanbanBids', 'recentCustomers', 'daysOfWeek', 'draftCount', 'sentCount', 'bookedRevenue'));
    }
}
