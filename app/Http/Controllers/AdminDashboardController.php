<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    /**
     * Compile systemic list metadata records for tracking company workspaces.
     */
    public function index()
    {
        // 1. Sniff table structures and customized database prefix layouts cleanly
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';
        $estimatesTable = $prefix . 'estimates';

        // 2. Compute Global Platform Telemetry Cross-Tenant Arrays
        $totalWorkspaces = Schema::hasTable($companyTable) ? DB::table($companyTable)->count() : 0;
        $totalEstimates = Schema::hasTable($estimatesTable) ? DB::table($estimatesTable)->count() : 0;
        $globalBookedRevenue = Schema::hasTable($estimatesTable) ? DB::table($estimatesTable)->where('status', 'approved')->sum('grand_total') : 0.00;
        $globalSentBids = Schema::hasTable($estimatesTable) ? DB::table($estimatesTable)->where('status', 'sent')->count() : 0;

        $globalTelemetry = [
            'total_workspaces' => $totalWorkspaces,
            'total_estimates'  => $totalEstimates,
            'booked_revenue'   => $globalBookedRevenue,
            'sent_bids'        => $globalSentBids,
        ];

        // 3. Gather Contractor Workspace Rows
        $users = User::latest()->get();
        $companyIds = $users->pluck('company_id')->filter()->unique();

        $companies = Schema::hasTable($companyTable)
            ? DB::table($companyTable)->whereIn('id', $companyIds)->get()->keyBy('id')
            : collect();

        // 4. Gather Financial Pipeline Totals Breakdown Per Tenant Node
        $pipelineMetrics = collect();
        if (Schema::hasTable($estimatesTable)) {
            $pipelineMetrics = DB::table($estimatesTable)
                ->select('company_id',
                    DB::raw('count(*) as estimates_count'),
                    DB::raw('sum(case when status = "approved" then grand_total else 0 end) as booked_revenue')
                )
                ->whereIn('company_id', $companyIds)
                ->groupBy('company_id')
                ->get()
                ->keyBy('company_id');
        }

        // 5. Hydrate Memory Matrix and Calculate Trust Profile Progress Scores
        foreach ($users as $user) {
            $company = $companies->get($user->company_id);
            $user->company = $company;

            // Initialize default baseline tracking metrics
            $user->estimates_count = 0;
            $user->booked_revenue = 0.00;
            $user->profile_completion = 0;

            if ($company) {
                // Attach individual tenant financial logs
                $metrics = $pipelineMetrics->get($company->id);
                $user->estimates_count = $metrics->estimates_count ?? 0;
                $user->booked_revenue = $metrics->booked_revenue ?? 0.00;

                // Programmatically parse our 8 Trust Signal fields to measure registration quality
                $points = 0;
                if (!empty($company->name)) $points += 20;
                if (!empty($company->logo_path)) $points += 15;
                if (!empty($company->company_bio)) $points += 15;
                if (!empty($company->work_philosophy)) $points += 15;
                if (!empty($company->license_number)) $points += 15;
                if (!empty($company->insurance_badge) && $company->insurance_badge == 1) $points += 10;
                if (!empty($company->gallery_paths) && count(json_decode($company->gallery_paths, true) ?? []) > 0) $points += 10;

                $user->profile_completion = $points;
            }
        }

        return view('admin.index', compact('users', 'globalTelemetry'));
    }

    /**
     * Modify targeted structural privilege statuses safely from the central console deck.
     */
    public function toggleAdminStatus($id)
    {
        $user = User::findOrFail($id);

        // Security Guardrail: Prevent modifying your own active operational profile
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => '🛑 Core safety violation: Cannot strip administrative parameters from your active terminal session.']);
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $statusMessage = $user->is_admin
            ? "🔒 Elevated administrative authorization granted to {$user->email}."
            : "⚠️ System privileges retracted for {$user->email}.";

        return back()->with('status', $statusMessage);
    }
}
