<?php

namespace App\Http\Controllers;

use App\Models\Client;
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

        // 2. Fetch All Company Estimates with Client Profiles for the Kanban Lane Compilation
        $estimates = Estimate::where('company_id', $companyId)
            ->with('customer') // Points to the re-routed customer() relation returning Client models
            ->orderBy('updated_at', 'desc')
            ->get();

        // Inject compatibility attributes onto the loaded client objects for the Kanban layout display names
        $estimates->each(function($estimate) {
            if ($estimate->customer) {
                $parts = explode(' ', trim($estimate->customer->client_name ?? ''), 2);
                $estimate->customer->first_name = $parts[0] ?? 'Client';
                $estimate->customer->last_name = $parts[1] ?? ' ';
                $estimate->customer->billing_address = $estimate->customer->address;
            }
        });

        // Separate estimates cleanly into dedicated Kanban operational lane arrays
        $kanbanBids = [
            'draft'    => $estimates->filter(fn($e) => strtolower($e->status) === 'draft')->values(),
            'sent'     => $estimates->filter(fn($e) => strtolower($e->status) === 'sent')->values(),
            'approved' => $estimates->filter(fn($e) => strtolower($e->status) === 'approved')->values(),
            'closed'   => $estimates->filter(fn($e) => strtolower($e->status) === 'closed')->values(),
        ];

        // 3. Fetch Recent Clients with dynamic lifetime approved calculations mapped to the new sc_clients structure
        $recentCustomers = Client::where('company_id', $companyId)
            ->withSum(['estimates as lifetime_value' => function ($query) {
                $query->where('status', 'approved');
            }], 'grand_total')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($client) {
                $client->lifetime_value = $client->lifetime_value ?? 0.00;

                // Provide split-name compatibility parameters for the right-hand dashboard card layout
                $parts = explode(' ', trim($client->client_name ?? ''), 2);
                $client->first_name = $parts[0] ?? 'Client';
                $client->last_name = $parts[1] ?? ' ';

                return $client;
            });

        // 4. Optimized Calendar Matrix Engine (Decoupled from model relationship hooks for stability)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SUNDAY);

        $weekAppointments = Appointment::where('company_id', $companyId)
            ->whereBetween('scheduled_at', [$startOfWeek->copy()->startOfDay(), $endOfWeek->copy()->endOfDay()])
            ->with(['estimate'])
            ->get();

        // Pull unique client links across the schedule window to process names manually in system memory
        $clientIds = $weekAppointments->pluck('customer_id')->filter()->unique();
        $clientsMap = Client::whereIn('id', $clientIds)->get()->keyBy('id');

        $groupedAppointments = $weekAppointments->groupBy(function ($appt) {
            return $appt->scheduled_at->toDateString();
        });

        $daysOfWeek = [];

        for ($i = 0; $i < 7; $i++) {
            $currentDayDate = $startOfWeek->copy()->addDays($i);
            $dateString = $currentDayDate->toDateString();

            // Extract pre-loaded appointments for this calendar date node
            $dayJobs = $groupedAppointments->get($dateString, collect());

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
                'appointments' => $dayJobs->map(function ($job) use ($clientsMap) {
                    $matchedClient = $clientsMap->get($job->customer_id);

                    return [
                        'id'              => $job->id,
                        'title'           => $job->title,
                        'time'            => $job->scheduled_at->format('g:i A'),
                        'status'          => $job->status,
                        'notes'           => $job->notes,
                        'customer_name'   => $matchedClient ? $matchedClient->client_name : 'Unassigned Client',
                        'estimate_id'     => $job->estimate_id,
                        'estimate_number' => $job->estimate ? $job->estimate->estimate_number : null,
                    ];
                })->toArray(),
            ];
        }

        return view('dashboard', compact('estimates', 'kanbanBids', 'recentCustomers', 'daysOfWeek', 'draftCount', 'sentCount', 'bookedRevenue'));
    }
}