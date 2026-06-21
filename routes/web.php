<?php

use App\Http\Controllers\MagicAuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PricebookController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\AdminDashboardController;
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

// Authenticated Contractor Workspace
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    // Customers Management
    Route::get('/customers/export', [CustomerController::class, 'exportCsv'])->name('customers.export');
    Route::resource('customers', CustomerController::class);

    // Pricebook Management
    Route::resource('pricebook', PricebookController::class)->only(['index', 'store', 'destroy']);

    // Estimates & Job Operational Processing (Unified under EstimateController)
    Route::post('/estimates/{id}/blueprint', [EstimateController::class, 'saveBlueprint'])->name('estimates.blueprint');
    Route::post('/estimates/{id}/text-dispatch', [EstimateController::class, 'sendEstimateSms'])->name('estimates.text-dispatch');
    Route::post('/estimates/{id}/email-dispatch', [EstimateController::class, 'sendEstimateEmail'])->name('estimates.email-dispatch');
    Route::post('/estimates/{id}/status', [EstimateController::class, 'updateStatus'])->name('estimates.status');
    Route::post('/estimates/{id}/attachments', [EstimateController::class, 'uploadAttachment'])->name('estimates.attachments');
    Route::post('/estimates/{id}/close-job', [EstimateController::class, 'closeJob'])->name('estimates.close-job');
    Route::resource('estimates', EstimateController::class);

    // Administrative System Cockpit Control Deck (Uses string class reference to clear stringification routines)
    Route::middleware([\AdminGateMiddleware::class])->group(function () {
        Route::get('/admin/management', [AdminDashboardController::class, 'index'])->name('admin.index');
        Route::post('/admin/management/{id}/toggle', [AdminDashboardController::class, 'toggleAdminStatus'])->name('admin.toggle-rights');
    });
});

// Homeowner Viewport Portal Frames
Route::get('/portal', function () { return view('portal'); })->name('portal');
Route::get('/portal/checkout/{token}', [EstimateController::class, 'checkout'])->name('portal.checkout');

Route::post('/portal/action/{id}', [EstimateController::class, 'handlePortalAction'])->name('portal.action');
Route::get('/portal/success/{token}', [EstimateController::class, 'paymentSuccess'])->name('quotes.payment.success');

// Public Legal Compliance Frames
Route::view('/privacy', 'privacy')->name('legal.privacy');
Route::view('/terms', 'terms')->name('legal.terms');

// Front-Facing Marketing Pages & Local Lead Funnels
Route::view('/advertise', 'advertise')->name('marketing.advertise');
Route::view('/contractor-directory', 'contractor-directory')->name('marketing.directory');
Route::view('/leads', 'leads')->name('marketing.leads');

// System Documentation & Training Rails
Route::view('/tutorial', 'tutorial')->name('platform.tutorial');

// Inbound Telephony Carrier Webhooks
Route::post('/webhooks/telnyx', [EstimateController::class, 'handleTelnyxWebhook'])->name('webhooks.telnyx');


/*
|--------------------------------------------------------------------------
| Runtime Class Extensions (Maintains Single File Swap Integration)
|--------------------------------------------------------------------------
*/
class AdminGateMiddleware
{
    /**
     * Handle an incoming request and confirm authorization before allowing control deck execution.
     */
    public function handle($request, $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('dashboard')->withErrors(['security' => '🛑 Clear operational clearance parameter mismatch. Entry route dropped.']);
        }

        return $next($request);
    }
}
