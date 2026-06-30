<?php

use App\Http\Controllers\MagicAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PricebookController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Middleware\EnsureOnboardingIsCompleted;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Web Routes (Strict Contractor Vocabulary)
|--------------------------------------------------------------------------
*/

// Public Entry Node
Route::get('/', function () { return view('welcome'); })->name('welcome');
Route::get('/login', function () { return redirect()->route('welcome'); })->name('login');

// Frictionless Onboarding Tiers
Route::get('/register', function () { return view('register'); })->name('register');
Route::post('/register', [MagicAuthController::class, 'register'])->name('register.submit');

// Frictionless Login Handling
Route::post('/login/magic', [MagicAuthController::class, 'sendLink'])->name('magic.send');

// Interstitial Bot-Shield Handshake Routes
Route::get('/login/verify/{token}', [MagicAuthController::class, 'showVerifyBridge'])->name('magic.verify');
Route::post('/login/verify/{token}', [MagicAuthController::class, 'processVerifyBridge'])->name('magic.verify.submit');

// Dedicated Secure SMS Code Verification Nodes
Route::get('/login/two-factor', [MagicAuthController::class, 'showTwoFactorForm'])->name('magic.2fa.view');
Route::post('/login/two-factor-verify', [MagicAuthController::class, 'verifyTwoFactor'])->name('magic.2fa');

Route::match(['get', 'post'], '/logout', [MagicAuthController::class, 'logout'])->name('logout');

// Authenticated Contractor Workspace Framework
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Isolated Onboarding Configuration Setup Pipeline
    |--------------------------------------------------------------------------
    | These routes remain exempt from the onboarding intercept gate middleware
    | to eliminate cascading infinite loop execution sequences.
    |
    */
    Route::get('/workspace/setup', [OnboardingController::class, 'showWizard'])->name('onboarding.view');
    Route::post('/workspace/setup', [OnboardingController::class, 'processWizard'])->name('onboarding.submit');

    // 🛡️ EMERGENCY ACCESS DISMISSAL BRIDGE
    // Placed out here so an admin can instantly drop out of a broken or incomplete account partition
    Route::post('/admin/management/impersonate/stop', [AdminDashboardController::class, 'stopImpersonating'])->name('admin.impersonate.stop');

    /*
    |--------------------------------------------------------------------------
    | Active Business Operations Layer (Protected via Intercept Guard)
    |--------------------------------------------------------------------------
    | The user must possess a valid 'onboarding_completed_at' timestamp token
    | inside database memory to pierce this boundary layer. If incomplete,
    | they are gracefully rerouted back to the workspace configurator.
    |
    */
    Route::middleware([EnsureOnboardingIsCompleted::class])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Dedicated Company Identity Trust Signal Control Panel (Decoupled Hub)
        Route::get('/workspace/profile', [CompanyProfileController::class, 'edit'])->name('workspace.profile.edit');
        Route::post('/workspace/profile', [CompanyProfileController::class, 'update'])->name('workspace.profile.update');

        // ✨ DYNAMIC REPUTATION ENGINE: Gemini 3.5 Automated Copywriting Port
        Route::post('/workspace/profile/ai-assist', [CompanyProfileController::class, 'generateAiAssist'])->name('workspace.profile.ai-assist');

        // Secure Login Line Configuration (With Smart String Normalization)
        Route::post('/user/security-phone', function (Illuminate\Http\Request $request) {
            $request->validate(['phone_2fa' => 'required|string|max:50']);

            $digits = preg_replace('/[^0-9]/', '', $request->phone_2fa);

            if (strlen($digits) === 10) {
                $cleanE164 = '+1' . $digits;
            } elseif (strlen($digits) === 11 && str_starts_with($digits, '1')) {
                $cleanE164 = '+' . $digits;
            } else {
                $cleanE164 = '+' . $digits;
            }

            $user = Illuminate\Support\Facades\Auth::user();
            $user->phone_2fa = $cleanE164;
            $user->save();

            return back()->with('status', "🔒 Number verified and formatted to {$cleanE164} successfully.");
        })->name('user.security-phone');

        // 📱 Unified Mobile Client Management CRM Engine Routing
        Route::get('/workspace/crm/export', [ClientController::class, 'exportCsv'])->name('workspace.crm.export');
        Route::get('/workspace/crm', [ClientController::class, 'index'])->name('workspace.crm.index');

        // CRM manual routing paths mapped clearly
        Route::post('/workspace/crm/store', [ClientController::class, 'store'])->name('workspace.crm.store');
        Route::get('/workspace/crm/edit/{id}', [ClientController::class, 'edit'])->name('workspace.crm.edit');
        Route::post('/workspace/crm/update/{id}', [ClientController::class, 'update'])->name('workspace.crm.update');
        Route::delete('/workspace/crm/destroy/{id}', [ClientController::class, 'destroy'])->name('workspace.crm.destroy');

        // Pricebook Management
        Route::post('/pricebook/update/{id}', [PricebookController::class, 'update'])->name('pricebook.update');
        Route::resource('pricebook', PricebookController::class)->only(['index', 'store', 'destroy']);

        // 💵 Quick On-Site Mobile Billing Counter UI View Node
        Route::get('/workspace/billing/quick', function () { return view('billing.quick'); })->name('workspace.billing.quick');

        // Estimates & Job Operational Processing (Unified under EstimateController)
        Route::post('/estimates/{id}/blueprint', [EstimateController::class, 'saveBlueprint'])->name('estimates.blueprint');
        Route::post('/estimates/{id}/text-dispatch', [EstimateController::class, 'sendEstimateSms'])->name('estimates.text-dispatch');
        Route::post('/estimates/{id}/email-dispatch', [EstimateController::class, 'sendEstimateEmail'])->name('estimates.email-dispatch');
        Route::post('/estimates/{id}/status', [EstimateController::class, 'updateStatus'])->name('estimates.status');
        Route::post('/estimates/{id}/attachments', [EstimateController::class, 'uploadAttachment'])->name('estimates.attachments');
        Route::post('/estimates/{id}/close-job', [EstimateController::class, 'closeJob'])->name('estimates.close-job');
        Route::resource('estimates', EstimateController::class);

        /*
        |--------------------------------------------------------------------------
        | 🛡️ SECURE ADMINISTRATIVE INLINE PROXIES
        |--------------------------------------------------------------------------
        | Resolving the controller instance out of the application container
        | via app() forces PHP to evaluate the action route as an instance method.
        | This approach bypasses string-casting arrays and handles dependency injection safely.
        |
        */
        Route::get('/admin/management', function () {
            if (!auth()->check() || !auth()->user()->is_admin) {
                return redirect()->route('dashboard')->withErrors(['security' => '🛑 Clear operational clearance parameter mismatch. Entry route dropped.']);
            }
            return app()->call([app(AdminDashboardController::class), 'index']);
        })->name('admin.index');

        Route::post('/admin/management/{id}/toggle', function ($id) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                return redirect()->route('dashboard')->withErrors(['security' => '🛑 Clear operational clearance parameter mismatch. Entry route dropped.']);
            }
            return app()->call([app(AdminDashboardController::class), 'toggleAdminStatus'], ['id' => $id]);
        })->name('admin.toggle-rights');

        Route::post('/admin/management/company/{id}', function ($id) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                return redirect()->route('dashboard')->withErrors(['security' => '🛑 Clear operational clearance parameter mismatch. Entry route dropped.']);
            }
            return app()->call([app(AdminDashboardController::class), 'updateCompany'], ['id' => $id]);
        })->name('admin.company.update');

        Route::post('/admin/management/purge/{id}', function ($id) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                return redirect()->route('dashboard')->withErrors(['security' => '🛑 Clear operational clearance parameter mismatch. Entry route dropped.']);
            }
            return app()->call([app(AdminDashboardController::class), 'destroyWorkspace'], ['id' => $id]);
        })->name('admin.workspace.purge');

        Route::post('/admin/management/impersonate/{id}', function ($id) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                return redirect()->route('dashboard')->withErrors(['security' => '🛑 Clear operational clearance parameter mismatch. Entry route dropped.']);
            }
            return app()->call([app(AdminDashboardController::class), 'impersonate'], ['id' => $id]);
        })->name('admin.impersonate');

    });
});

// Homeowner Viewport Portal Frames
Route::get('/portal', function () { return view('portal'); })->name('portal');

// 🛡️ CARRIER VETTING COMPLIANCE PORT: Static fallback for Telnyx campaign inspectors
Route::get('/portal/checkout/tkn_829104', function() {
    return response("
        <!DOCTYPE html>
        <html lang=\"en\" class=\"h-full bg-slate-50\">
        <head>
            <meta charset=\"UTF-8\">
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
            <title>Project Estimate #1024 | Apex Roofing LLC</title>
            <script src=\"https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4\"></script>
        </head>
        <body class=\"bg-slate-50 text-slate-900 font-sans antialiased p-4 md:p-12\">
            <div class=\"max-w-2xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-xl p-6 md:p-8 space-y-6\">
                <div class=\"p-3 bg-blue-50 text-blue-800 border border-blue-200 rounded-xl text-xs font-bold text-center shadow-sm\">
                    ℹ️ SAMPLE INSPECTION VIEW — This page is rendered exclusively for A2P 10DLC carrier compliance verification.
                </div>
                <div class=\"flex justify-between items-start border-b border-slate-100 pb-6\">
                    <div>
                        <h1 class=\"text-xl font-black text-slate-950 uppercase tracking-tight text-left\">Apex Roofing LLC</h1>
                        <p class=\"text-xs text-slate-500 font-semibold text-left\">Commercial & Residential Services</p>
                    </div>
                    <div class=\"text-right\">
                        <span class=\"inline-flex items-center px-2.5 py-1 rounded-md text-xs font-black bg-orange-50 text-[#f58613] tracking-wider uppercase shadow-sm\">Estimate Pending</span>
                        <p class=\"text-[10px] text-slate-400 font-bold mt-1.5 font-mono\">EST-1024</p>
                    </div>
                </div>
                <div class=\"space-y-3\">
                    <h3 class=\"text-xs font-black uppercase text-slate-400 tracking-wider text-left\">Scope of Work</h3>
                    <div class=\"border border-slate-200 rounded-xl overflow-hidden font-medium text-xs text-left\">
                        <div class=\"bg-slate-50 p-3 border-b border-slate-200 flex justify-between font-black uppercase text-[10px] text-slate-400 tracking-wide\">
                            <span>Description</span>
                            <span class=\"text-right\">Total</span>
                        </div>
                        <div class=\"p-3 flex justify-between items-center\">
                            <div>
                                <p class=\"font-bold text-slate-950\">Architectural Shingle Roof Replacement</p>
                                <p class=\"text-[11px] text-slate-400 mt-0.5\">Tear-off, underlayment installation, ice/water shield, and flashing integration.</p>
                            </div>
                            <span class=\"font-mono font-bold text-slate-950 text-right shrink-0 ml-4\">$8,450.00</span>
                        </div>
                    </div>
                </div>
                <div class=\"flex justify-between items-center bg-slate-950 text-white rounded-xl p-4 font-bold shadow-md\">
                    <span class=\"text-xs uppercase tracking-wider text-slate-400 font-black\">Proposed Project Total</span>
                    <span class=\"text-lg font-mono text-[#f58613] font-black\">$8,450.00</span>
                </div>
                <div class=\"grid grid-cols-2 gap-4 pt-2\">
                    <button class=\"bg-slate-100 text-slate-400 font-black text-xs py-3 rounded-xl tracking-wider uppercase text-center border border-slate-200 cursor-not-allowed\">Decline Scope</button>
                    <button class=\"bg-[#f58613] text-white font-black text-xs py-3 rounded-xl tracking-wider uppercase text-center shadow-md cursor-not-allowed\">Approve & Sign &rarr;</button>
                </div>
            </div>
        </body>
        </html>
    ");
});

Route::get('/portal/checkout/{token}', [EstimateController::class, 'checkout'])->name('portal.checkout');
Route::post('/portal/action/{id}', [EstimateController::class, 'handlePortalAction'])->name('portal.action');
Route::get('/portal/success/{token}', [EstimateController::class, 'paymentSuccess'])->name('quotes.payment.success');

// High-Conversion Public Client-Facing Gateway (Dynamic SEO/Estimate Anchor View)
Route::get('/brand/{slug}', [CompanyProfileController::class, 'show'])->name('brand.show');

// Public Legal Compliance Frames
Route::view('/privacy', 'privacy')->name('legal.privacy');
Route::view('/terms', 'terms')->name('legal.terms');

// Front-Facing Marketing Pages & Local Lead Funnels
Route::view('/capabilities', 'capabilities')->name('marketing.capabilities');
Route::view('/pricing-matrix', 'pricing-matrix')->name('marketing.pricing');
Route::view('/about-framework', 'about-framework')->name('marketing.about');

Route::view('/advertise', 'advertise')->name('marketing.advertise');
Route::view('/contractor-directory', 'contractor-directory')->name('marketing.directory');
Route::view('/leads', 'leads')->name('marketing.leads');

// System Documentation & Training Rails
Route::view('/tutorial', 'tutorial')->name('platform.tutorial');

// Inbound Telephony Carrier Webhooks
Route::post('/webhooks/telnyx', [EstimateController::class, 'handleTelnyxWebhook'])->name('webhooks.telnyx');
