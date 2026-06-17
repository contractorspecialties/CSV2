<?php

use App\Http\Controllers\MagicAuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PricebookController;
use App\Http\Controllers\QuoteController;
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
Route::post('/login/magic', [MagicAuthController::class, 'sendLink'])->name('magic.send');
Route::get('/login/verify/{token}', [MagicAuthController::class, 'verifyToken'])->name('magic.verify');
Route::post('/logout', [MagicAuthController::class, 'logout'])->name('logout');

// Authenticated Contractor Workspace
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers Management
    Route::get('/customers/export', [CustomerController::class, 'exportCsv'])->name('customers.export');
    Route::resource('customers', CustomerController::class);

    // Pricebook Management
    Route::resource('pricebook', PricebookController::class)->only(['index', 'store', 'destroy']);

    // Estimates & Job Operational Processing
    Route::post('/estimates/{id}/blueprint', [QuoteController::class, 'saveBlueprint']);
    Route::post('/estimates/{id}/text-dispatch', [QuoteController::class, 'sendEstimateSms']);
    Route::post('/estimates/{id}/close-job', [QuoteController::class, 'closeJob']);
    Route::resource('estimates', QuoteController::class);
});

// Homeowner Viewport Portal Frames
Route::get('/portal', function () { return view('portal'); })->name('portal');
Route::get('/portal/checkout/{token}', [QuoteController::class, 'checkout'])->name('portal.checkout');
Route::get('/portal/success/{token}', [QuoteController::class, 'paymentSuccess'])->name('quotes.payment.success');
