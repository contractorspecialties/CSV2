<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class MagicAuthController extends Controller
{
    /**
     * Generate a secure sign-in token and email the login link to the user.
     */
    public function sendLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()->with('status', '📨 If your business email is registered, your secure login link has been sent.');
        }

        $token = Str::random(32);

        $user->update([
            'login_token' => $token,
            'token_expires_at' => now()->addMinutes(15),
        ]);

        $magicLink = route('magic.verify', ['token' => $token]);

        try {
            Mail::html("
                <div style=\"font-family: sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;\">
                    <h2 style=\"color: #0f172a; font-size: 16px; font-weight: bold;\">Workspace Login Link</h2>
                    <p style=\"font-size: 14px; color: #334155;\">Click the secure link below to open your contractor workspace panel. This access route is single-use and expires in 15 minutes.</p>
                    <div style=\"margin: 20px 0;\">
                        <a href=\"{$magicLink}\" rel=\"nofollow\" style=\"display: inline-block; background-color: #f58613; color: #ffffff; text-decoration: none; font-weight: bold; font-size: 13px; padding: 12px 20px; border-radius: 6px;\">Log Into Dashboard Direct →</a>
                    </div>
                    <p style=\"font-size: 11px; color: #94a3b8;\">If the button does not work, copy and paste this path directly into your address bar:<br><span style=\"font-family: monospace; color: #64748b;\">{$magicLink}</span></p>
                </div>
            ", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('⚡ Your Secure Dashboard Sign-In Link');
            });

            Log::info("🔑 Secure sign-in token compiled for {$user->email}: {$token}");

        } catch (\Exception $e) {
            Log::error("🚨 Mail service could not dispatch sign-in link: " . $e->getMessage());
        }

        return back()->with('status', '📨 If your business email is registered, your secure login link has been sent.');
    }

    /**
     * Display a secure intermediate bridge confirmation layout.
     */
    public function showVerifyBridge($token)
    {
        $user = User::where('login_token', $token)->first();

        if (!$user || Carbon::parse($user->token_expires_at)->isPast()) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 This login link has expired or is invalid. Please request a new secure link.']);
        }

        return response("
            <!DOCTYPE html>
            <html lang=\"en\" class=\"h-full bg-slate-50\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Secure Workspace Entrance</title>
                <script src=\"https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4\"></script>
            </head>
            <body class=\"flex flex-col justify-center min-h-full font-sans antialiased bg-slate-50 px-4 py-12\">
                <div class=\"w-full max-w-md mx-auto bg-white border border-slate-200 rounded-2xl shadow-xl p-8 text-center space-y-6\">
                    <div class=\"space-y-2\">
                        <div class=\"inline-flex items-center justify-center w-12 h-12 rounded-xl bg-orange-50 text-[#f58613] text-xl font-bold mb-1\">🏗️</div>
                        <h2 class=\"text-xl font-black text-slate-950 uppercase tracking-tight\">Contractor Specialties Portal</h2>
                        <p class=\"text-xs text-slate-500 font-semibold max-w-[280px] mx-auto leading-normal\">Click below to confirm your identity and open your secure workspace dashboard manager.</p>
                    </div>

                    <form action=\"" . route('magic.verify.submit', ['token' => $token]) . "\" method=\"POST\">
                        <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">
                        <button type=\"submit\" class=\"w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer\">
                            Securely Enter Dashboard →
                        </button>
                    </form>
                </div>
            </body>
            </html>
        ");
    }

    /**
     * Process human confirmation, stage tracking items, and issue the 2FA secure text.
     */
    public function processVerifyBridge($token)
    {
        $user = User::where('login_token', $token)->first();

        if (!$user || Carbon::parse($user->token_expires_at)->isPast()) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 This login link has expired or is invalid. Please request a new secure link.']);
        }

        if (empty($user->phone_2fa)) {
            $user->update(['login_token' => null, 'token_expires_at' => null]);
            Auth::login($user, true);
            return redirect()->route('dashboard')->with('status', '⚡ Welcome back! Save your mobile number to arm your text-code security options.');
        }

        $securityCode = strval(rand(100000, 999999));

        $user->update([
            'two_factor_code' => $securityCode,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        // Explicitly set tracking markers inside core session array
        session(['auth_2fa_user_id' => $user->id]);

        $company = DB::table('sc_companies')->where('id', $user->company_id)->first();
        $companyName = $company->name ?? 'ContractorSpecialties';
        $fromLine = $company->sms_phone_number ?? env('TELNYX_DEFAULT_FROM');

        if (!empty($fromLine)) {
            try {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('TELNYX_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])->post('https://api.telnyx.com/v2/messages', [
                    'from' => $fromLine,
                    'to'   => $user->phone_2fa,
                    'text' => "Your 6-digit security code for your {$companyName} account is: {$securityCode}. This code expires in 10 minutes.",
                ]);
            } catch (\Exception $e) {
                Log::error("🚨 Text security verification gateway could not send code: " . $e->getMessage());
            }
        }

        // Redirect to isolated form URL to ensure clean session state serialization down to client storage
        return redirect()->route('magic.2fa.view');
    }

    /**
     * Render the isolated 2FA text input panel view cleanly with native error reporting hooks.
     */
    public function showTwoFactorForm()
    {
        if (!session()->has('auth_2fa_user_id')) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 Your security verification session has expired. Request a new login link.']);
        }

        return response("
            <!DOCTYPE html>
            <html lang=\"en\" class=\"h-full bg-slate-50\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Account Verification | Security Gate</title>
                <script src=\"https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4\"></script>
            </head>
            <body class=\"flex flex-col justify-center min-h-full font-sans antialiased bg-slate-50 px-4 py-12\">
                <div class=\"w-full max-w-md mx-auto bg-white border border-slate-200 rounded-2xl shadow-xl p-8 space-y-6\">
                    <div class=\"text-center space-y-2\">
                        <div class=\"inline-flex items-center justify-center w-12 h-12 rounded-xl bg-orange-50 text-[#f58613] text-xl font-bold mb-2\">📱</div>
                        <h2 class=\"text-xl font-black text-slate-950 uppercase tracking-tight\">Confirm Your Identity</h2>
                        <p class=\"text-xs text-slate-500 font-semibold max-w-[280px] mx-auto leading-normal\">We just texted a 6-digit security code to your phone for extra verification.</p>
                    </div>

                    " . (session()->has('errors') ? "
                        <div class=\"p-3 bg-red-50 text-red-700 border border-red-200 rounded-xl text-xs font-bold text-center\">
                            " . session('errors')->first() . "
                        </div>
                    " : "") . "

                    <form action=\"" . route('magic.2fa') . "\" method=\"POST\" class=\"space-y-4\">
                        <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">
                        <div>
                            <label for=\"secure_code\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Enter 6-Digit Security Code</label>
                            <input type=\"text\" id=\"secure_code\" name=\"two_factor_code\" required maxlength=\"6\" placeholder=\"000000\" inputmode=\"numeric\" pattern=\"[0-9]*\" autocomplete=\"one-time-code\" class=\"w-full bg-slate-50 border border-slate-300 rounded-xl py-3 px-4 text-center text-lg font-mono font-black tracking-[0.5em] text-slate-900 focus:outline-none focus:border-[#f58613]\">
                        </div>

                        <button type=\"submit\" class=\"w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer\">
                            Verify Code & Log In →
                        </button>
                    </form>
                </div>
            </body>
            </html>
        ");
    }

    /**
     * Complete human text validation verification securely entirely using local server-side Carbon metrics.
     */
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|string|size:6',
        ]);

        $userId = session('auth_2fa_user_id');

        if (!$userId) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 Verification session expired. Please attempt sign-in again.']);
        }

        $user = User::find($userId);

        // Process expiration matching metrics in PHP to bypass MySQL system clock mismatch faults
        if (!$user || $user->two_factor_code !== $request->two_factor_code || Carbon::parse($user->two_factor_expires_at)->isPast()) {
            return redirect()->route('magic.2fa.view')->withErrors(['two_factor_code' => '🛑 The verification code entered is incorrect or has expired. Please verify your alerts and retry.']);
        }

        // Wipe security values simultaneously upon clean matching authentication parameters
        $user->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'login_token' => null,
            'token_expires_at' => null,
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        $request->session()->put('auth.password_confirmed_at', time());
        $request->session()->save();

        session()->forget('auth_2fa_user_id');

        return redirect()->route('dashboard')->with('status', '⚡ Verified successfully. Welcome back to your company workspace!');
    }

    /**
     * Terminate the session cleanly.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
