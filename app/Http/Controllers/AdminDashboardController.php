<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    /**
     * Compile systemic list metadata records for tracking company workspaces.
     */
    public function index()
    {
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';
        $estimatesTable = $prefix . 'estimates';

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

        $users = User::latest()->get();
        $companyIds = $users->pluck('company_id')->filter()->unique();

        $companies = Schema::hasTable($companyTable)
            ? DB::table($companyTable)->whereIn('id', $companyIds)->get()->keyBy('id')
            : collect();

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

        foreach ($users as $user) {
            $company = $companies->get($user->company_id);
            $user->company = $company;

            $user->estimates_count = 0;
            $user->booked_revenue = 0.00;
            $user->profile_completion = 0;

            if ($company) {
                $metrics = $pipelineMetrics->get($company->id);
                $user->estimates_count = $metrics->estimates_count ?? 0;
                $user->booked_revenue = $metrics->booked_revenue ?? 0.00;

                $points = 0;
                if (!empty($company->name)) $points += 20;
                if (!empty($company->logo_path)) $points += 15;
                if (!empty($company->company_bio_short) || !empty($company->company_bio_long)) $points += 15;
                if (!empty($company->primary_specialty) || !empty($company->trade)) $points += 15;
                if (!empty($company->license_number)) $points += 15;
                if (isset($company->is_insured) && $company->is_insured == 1) $points += 10;
                if (!empty($company->service_tags) && count(json_decode($company->service_tags, true) ?? []) > 0) $points += 10;

                $user->profile_completion = min(100, $points);
            }
        }

        return view('admin.index', compact('users', 'globalTelemetry'));
    }

    /**
     * Override company profile fields straight from the master cockpit desk.
     */
    public function updateCompany(Request $request, $id)
    {
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';

        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'trade'                => 'required|string|max:255',
            'city'                 => 'required|string|max:255',
            'state'                => 'required|string|size:2',
            'service_radius_miles' => 'required|integer|min:1',
            'is_publicly_listed'   => 'required|boolean',
        ]);

        if (!DB::table($companyTable)->where('id', $id)->exists()) {
            return back()->withErrors(['error' => '🛑 Targeting mismatch: Designated company workspace tracking record not found.']);
        }

        DB::table($companyTable)->where('id', $id)->update([
            'name'                 => $validated['name'],
            'trade'                => $validated['trade'],
            'city'                 => $validated['city'],
            'state'                => strtoupper($validated['state']),
            'service_radius_miles' => $validated['service_radius_miles'],
            'is_publicly_listed'   => $validated['is_publicly_listed'],
            'updated_at'           => now(),
        ]);

        Log::warning("⚠️ Master Admin Override applied on Company Partition ID: {$id} by User ID: " . auth()->id());
        return back()->with('status', '🔒 Corporate workspace parameters adjusted successfully.');
    }

    /**
     * Intercept a contractor session node and safely shift auth perspectives.
     */
    public function impersonate($id)
    {
        $targetUser = User::findOrFail($id);

        if ($targetUser->id === auth()->id()) {
            return back()->withErrors(['error' => '🛑 Loop error: Interception loop targeted at active profile node rejected.']);
        }

        // Lock your genuine admin signature credentials down into persistent session space
        session(['admin_impersonator_id' => auth()->id()]);

        // Shift security tokens over to the contractor
        Auth::loginUsingId($targetUser->id);

        Log::info("🔑 Platform Override Activated: Admin ID " . session('admin_impersonator_id') . " shifted perspectives into Contractor Account ID: {$id}");

        return redirect()->route('dashboard')->with('status', "💼 Terminal bridge connected. Operating as Principal Operator: {$targetUser->email}");
    }

    /**
     * Disconnect active interception loops and revert session signatures.
     */
    public function stopImpersonating()
    {
        if (!session()->has('admin_impersonator_id')) {
            return redirect()->route('dashboard');
        }

        $adminId = session()->pull('admin_impersonator_id');
        Auth::loginUsingId($adminId);

        Log::info("🧹 Platform Override Terminated: Reverted session credentials back to Admin Profile ID: {$adminId}");

        return redirect()->route('admin.index')->with('status', '🔒 Terminal hook disconnected. Safely returned to Platform Over-Watch Console.');
    }

    /**
     * Modify targeted structural privilege statuses safely from the central console deck.
     */
    public function toggleAdminStatus($id)
    {
        $user = User::findOrFail($id);
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

    /**
     * Execute a cascading clean-sweep purge on an operational workspace.
     */
    public function destroyWorkspace($id)
    {
        $targetUser = User::findOrFail($id);
        if ($targetUser->id === auth()->id()) {
            return back()->withErrors(['error' => '🛑 Core safety violation: Purge request rejected. Cannot drop your own active identity account.']);
        }

        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyId = $targetUser->company_id;

        try {
            DB::beginTransaction();

            $tenantTables = ['estimates', 'clients', 'pricebook', 'estimates_blueprint'];
            foreach ($tenantTables as $tableBase) {
                $tableName = $prefix . $tableBase;
                if (Schema::hasTable($tableName)) {
                    DB::table($tableName)->where('company_id', $companyId)->delete();
                }
            }

            User::where('company_id', $companyId)->delete();

            $companyTable = $prefix . 'companies';
            if (Schema::hasTable($companyTable)) {
                DB::table($companyTable)->where('id', $companyId)->delete();
            }

            DB::commit();
            Log::alert("🚨 CASCADING PURGE EXECUTED: Workspace ID {$companyId} wiped by Admin ID: " . auth()->id());

            return back()->with('status', '🧹 Workspace partition and all nested tracking records completely dropped from storage.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("🚨 Administrative purge routine failure on Workspace ID {$companyId}: " . $e->getMessage());
            return back()->withErrors(['error' => 'An operational fault occurred during the cascading deletion run. Workspace drop aborted.']);
        }
    }
}
