<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Compile systemic list metadata records for tracking company workspaces.
     */
    public function index()
    {
        // Pull all users from the active configuration table
        $users = User::latest()->get();

        // Dynamically detect any customized database prefix rules (like 'sc_') from the active User model table name
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';

        // Extract all valid company ID anchors to execute a single-pass hydration map
        $companyIds = $users->pluck('company_id')->filter()->unique();

        // Fetch company data directly from the table to sidestep missing Eloquent relationships entirely
        $companies = DB::table($companyTable)
            ->whereIn('id', $companyIds)
            ->get()
            ->keyBy('id');

        // Map the company database row assets back to the user instances in memory for view structure compatibility
        foreach ($users as $user) {
            $user->company = $companies->get($user->company_id);
        }

        return view('admin.index', compact('users'));
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
