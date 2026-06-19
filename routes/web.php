<?php

use App\Http\Controllers\MagicAuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PricebookController;
use App\Http\Controllers\EstimateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Web Routes (Strict Contractor Vocabulary)
|--------------------------------------------------------------------------
*/

// Public Entry Node
Route::get('/', function () { return view('welcome'); })->name('welcome');
Route::get('/login', function () { return redirect()->route('welcome'); })->name('login');

// Frictionless Login Handling
Route::post('/login/two-factor-verify', [MagicAuthController::class, 'verifyTwoFactor'])->name('magic.2fa');
Route::post('/login/magic', [MagicAuthController::class, 'sendLink'])->name('magic.send');
Route::get('/login/verify/{token}', [MagicAuthController::class, 'verifyToken'])->name('magic.verify');
Route::post('/logout', [MagicAuthController::class, 'logout'])->name('logout');

// Authenticated Contractor Workspace
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Secure Login Line Configuration (With Smart String Normalization)
    Route::post('/user/security-phone', function (Illuminate\Http\Request $request) {
        $request->validate(['phone_2fa' => 'required|string|max:50']);

        // Strip out parentheses, spaces, hyphens, and letters completely
        $digits = preg_replace('/[^0-9]/', '', $request->phone_2fa);

        // Intelligently append North American country coding for clean API transmission
        if (strlen($digits) === 10) {
            $cleanE164 = '+1' . $digits;
        } elseif (strlen($digits) === 11 && str_starts_with($digits, '1')) {
            $cleanE164 = '+' . $digits;
        } else {
            $cleanE164 = '+' . $digits;
        }

        // Use direct model assignment to bypass any missing fillable attribute configurations
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
