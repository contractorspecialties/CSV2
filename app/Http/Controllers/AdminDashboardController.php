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
        // Eager-load corporate company tenant groups safely across prefixes
        $users = User::with(['company' => function ($query) {
            $query->from((new \App\Models\User())->getTable() === 'sc_users' ? 'sc_companies' : 'sc_companies');
        }])->latest()->get();

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
