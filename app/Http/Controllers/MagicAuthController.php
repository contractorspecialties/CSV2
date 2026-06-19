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

        $token = Str::random(64);

        $user->update([
            'login_token' => $token,
            'token_expires_at' => now()->addMinutes(15),
        ]);

        $magicLink = route('magic.verify', ['token' => $token]);

        try {
            Mail::raw("Hello, click the link below to log securely into your ContractorSpecialties company dashboard. This link will expire in 15 minutes for your security.\n\n{$magicLink}", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('⚡ Your Secure Dashboard Sign-In Link');
            });

            Log::info("🔑 Secure sign-in link written for {$user->email}: {$magicLink}");

        } catch (\Exception $e) {
            Log::error("🚨 Mail service could not dispatch sign-in link: " . $e->getMessage());

            if (config('app.env') === 'local' || config('app.env') === 'staging') {
                Log::info("💡 Safety Fallback Intercept: Copy-paste this URL path: {$magicLink}");
            }
        }

        return back()->with('status', '📨 If your business email is registered, your secure login link has been sent.');
    }

    /**
     * Validate the sign-in link and either enforce text code security or bypass to dashboard.
     */
    public function verifyToken($token)
    {
        $user = User::where('login_token', $token)
            ->where('token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 This login link has expired or is invalid. Please request a new secure link.']);
        }

        $user->update([
            'login_token' => null,
            'token_expires_at' => null,
        ]);

        // Frictionless bypass if no mobile line is configured yet
        if (empty($user->phone_2fa)) {
            Auth::login($user, true);
            return redirect()->route('dashboard')->with('status', '⚡ Welcome back! To protect your account, save your mobile number under the Security panel to enable text code confirmation.');
        }

        $securityCode = strval(rand(100000, 999999));

        $user->update([
            'two_factor_code' => $securityCode,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

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

                    <form action=\"" . route('magic.2fa') . "\" method=\"POST\" class=\"space-y-4\">
                        <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">
                        <div>
                            <label for=\"secure_code\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Enter 6-Digit Security Code</label>
                            <input type=\"text\" id=\"secure_code\" name=\"two_factor_code\" required maxlength=\"6\" placeholder=\"000000\" autocomplete=\"one-time-code\" class=\"w-full bg-slate-50 border border-slate-300 rounded-xl py-3 px-4 text-center text-lg font-mono font-black tracking-[0.5em] text-slate-900 focus:outline-none focus:border-[#f58613]\">
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
     * Process the texted 6-digit verification code to complete account authorization.
     */
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|string|size:6',
        ]);

        $userId = session('auth_2fa_user_id');

        if (!$userId) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 Your session has expired. Please sign in again.']);
        }

        $user = User::where('id', $userId)
            ->where('two_factor_code', $request->two_factor_code)
            ->where('two_factor_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['two_factor_code' => '🛑 The code you entered is incorrect or has expired. Please double-check your mobile alerts and try again.']);
        }

        $user->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);

        // Fix: Force persistent device locking using standard remember tokens to survive cellular IP hops
        Auth::login($user, true);
        $request->session()->regenerate();

        // Force explicit session write to disk/memory storage before launching redirect headers
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
