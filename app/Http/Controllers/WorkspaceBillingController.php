<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkspaceBillingController extends Controller
{
    /**
     * Display the merchant payment gateway configuration engine settings panel.
     */
    public function edit()
    {
        $companyId = Auth::user()->company_id;

        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        $company = DB::table($prefix . 'companies')->where('id', $companyId)->first();

        return view('workspace.billing.settings', compact('company'));
    }

    /**
     * Update direct financial landing assets securely without intermediate escrow retention rules.
     */
    public function update(Request $request)
    {
        $request->validate([
            'stripe_link'          => 'nullable|url|max:500',
            'paypal_link'          => 'nullable|url|max:500',
            'zelle_handle'         => 'nullable|string|max:255',
            'billing_instructions' => 'nullable|string|max:2000',
        ]);

        $companyId = Auth::user()->company_id;

        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        DB::table($prefix . 'companies')->where('id', $companyId)->update([
            'stripe_link'          => $request->stripe_link,
            'paypal_link'          => $request->paypal_link,
            'zelle_handle'         => $request->zelle_handle,
            'billing_instructions' => $request->billing_instructions,
            'updated_at'           => now(),
        ]);

        return back()->with('status', '💳 Direct customer payout parameters safely locked onto company profile ledger.');
    }
}
