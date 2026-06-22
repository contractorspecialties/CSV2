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
     * Handle the registration of a new contractor and provision their workspace architecture.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email',
        ], [
            'email.unique' => '🛑 This professional email address is already registered to a workspace engine framework.',
        ]);

        // Resolve customized database prefix schemas dynamically matching model structures safely
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        // Algorithmic slug compiler with dynamic collision safety checks
        $slug = Str::slug($validated['company_name']);
        $baseSlug = $slug;
        $counter = 1;

        while (DB::table($prefix . 'companies')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        // Securely provision company tenant block specifying explicit slug criteria
        $companyId = DB::table($prefix . 'companies')->insertGetId([
            'name'       => $validated['company_name'],
            'slug'       => $slug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Use direct instance instantiation to bypass mass-assignment fillable protection limits
        $user = new User();
        $user->email = $validated['email'];
        $user->company_id = $companyId;

        // Satisfy database schema strictness with clean placeholder assignments
        $user->first_name = 'Contractor';
        $user->last_name = 'Owner';
        $user->save();

        // Package instant verification token using a bulletproof pipe (|) delimiter
        $randomPart = Str::random(32);
        $expirationEpoch = time() + (60 * 15); // 15 Minute window
        $combinedToken = $randomPart . '|' . $expirationEpoch;

        $user->login_token = $combinedToken;
        $user->token_expires_at = now()->addMinutes(15);
        $user->save();

        $magicLink = route('magic.verify', ['token' => $combinedToken]);

        // OFFLOAD OUTBOUND SMTP HANDSHAKE ASYNC TO ELIMINATE USER WEB BUFFERING LATENCY
        dispatch(function () use ($user, $magicLink, $slug) {
            try {
                Mail::raw("Welcome to ContractorSpecialties! Click the secure activation link below to verify your email and launch your new company management workspace dashboard layout:\n\n{$magicLink}", function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('⚡ Your New Company Workspace Activation Route');
                });
                Log::info("🏗️ Fresh company workspace provisioned for {$user->email} with slug identifier: {$slug}");
            } catch (\Exception $e) {
                Log::error("🚨 Onboarding activation mail failed transmission sequence: " . $e->getMessage());
            }
        })->afterResponse();

        return redirect()->route('welcome')->with('status', '🏗️ Your company workspace has been successfully provisioned! Check your inbox for your direct activation link.');
    }

    /**
     * Generate a secure token with an embedded timestamp and email the link to the user.
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

        // Embed expiration using a bulletproof pipe (|) delimiter to protect against alphanumeric string collisions
        $randomPart = Str::random(32);
        $expirationEpoch = time() + (60 * 15); // Valid for exactly 15 minutes
        $combinedToken = $randomPart . '|' . $expirationEpoch;

        $user->login_token = $combinedToken;
        $user->token_expires_at = now()->addMinutes(15);
        $user->save();

        $magicLink = route('magic.verify', ['token' => $combinedToken]);

        // OFFLOAD SYSTEM TRANSMISSION LOOP OUT OF ACTIVE REQUEST STREAM
        dispatch(function () use ($user, $magicLink, $combinedToken) {
            try {
                Mail::raw("Hello, click the link below to log securely into your ContractorSpecialties company dashboard. This link will expire in 15 minutes for your security.\n\n{$magicLink}", function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('⚡ Your Secure Dashboard Sign-In Link');
                });
                Log::info("🔑 Secure token compiled for {$user->email}: {$combinedToken}");
            } catch (\Exception $e) {
                Log::error("🚨 Mail service could not dispatch sign-in link: " . $e->getMessage());
            }
        })->afterResponse();

        return back()->with('status', '📨 If your business email is registered, your secure login link has been sent.');
    }

    /**
     * Display a secure intermediate bridge confirmation layout without burning the token.
     */
    public function showVerifyBridge($token)
    {
        $user = User::where('login_token', $token)->first();

        if (!$user) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 This login link has already been used or is invalid. Please request a new secure link.']);
        }

        // Safely extract the raw epoch integer using the guaranteed unique pipe string separator
        $parts = explode('|', $token);
        $expiresAtEpoch = isset($parts[1]) ? (int)$parts[1] : 0;

        if (time() > $expiresAtEpoch) {
            $user->login_token = null;
            $user->token_expires_at = null;
            $user->save();
            return redirect()->route('welcome')->withErrors(['email' => '🛑 This secure login link has expired. Please request a new link.']);
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
            <body class=\"flex flex-col justify-center min-h-full font-sans antialiased bg-slate-50 px-4 py-12 selection:bg-[#f58613] selection:text-white\">
                <div class=\"w-full max-w-md mx-auto bg-white border border-slate-200 rounded-2xl shadow-xl p-8 text-center space-y-6\">
                    <div class=\"space-y-2\">
                        <div class=\"inline-flex items-center justify-center w-12 h-12 rounded-xl bg-orange-50 text-[#f58613] text-xl font-bold mb-1 shadow-sm\">🏗️</div>
                        <h2 class=\"text-xl font-black text-slate-950 uppercase tracking-tight\">Contractor Specialties Portal</h2>
                        <p class=\"text-xs text-slate-500 font-semibold max-w-[280px] mx-auto leading-normal\">Click below to confirm your identity and open your secure workspace dashboard manager.</p>
                    </div>

                    <form action=\"" . route('magic.verify.submit', ['token' => $token]) . "\" method=\"POST\">
                        <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">
                        <button type=\"submit\" class=\"w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer\">
                            Securely Enter Dashboard &rarr;
                        </button>
                    </form>
                </div>
            </body>
            </html>
        ");
    }

    /**
     * Process user confirmation and send the 2FA code via Telnyx.
     */
    public function processVerifyBridge($token)
    {
        $user = User::where('login_token', $token)->first();

        if (!$user) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 This login link has expired or is invalid. Please request a new secure link.']);
        }

        $parts = explode('|', $token);
        $expiresAtEpoch = isset($parts[1]) ? (int)$parts[1] : 0;

        if (time() > $expiresAtEpoch) {
            $user->login_token = null;
            $user->token_expires_at = null;
            $user->save();
            return redirect()->route('welcome')->withErrors(['email' => '🛑 This secure login link has expired. Please request a new link.']);
        }

        if (empty($user->phone_2fa)) {
            $user->login_token = null;
            $user->token_expires_at = null;
            $user->save();

            Auth::login($user, true);
            return redirect()->route('dashboard')->with('status', '⚡ Welcome back! Save your mobile number to arm your text-code security options.');
        }

        $securityCode = strval(rand(100000, 999999));

        // Store 2FA variables entirely in session memory to avoid timezone lag
        session([
            'auth_2fa_user_id'    => $user->id,
            'auth_2fa_code'       => $securityCode,
            'auth_2fa_expires_at' => time() + (60 * 10), // Valid for 10 minutes
        ]);

        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        $company = DB::table($prefix . 'companies')->where('id', $user->company_id)->first();
        $companyName = $company->name ?? 'ContractorSpecialties';
        $fromLine = $company->sms_phone_number ?? env('TELNYX_DEFAULT_FROM');

        // OFFLOAD THE TELNYX OUTBOUND DISPATCH NETWORK PORT TRANSACTION ASYNC
        if (!empty($fromLine)) {
            dispatch(function () use ($fromLine, $user, $companyName, $securityCode) {
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
            })->afterResponse();
        }

        return redirect()->route('magic.2fa.view');
    }

    /**
     * Render the isolated 2FA text input panel view layout.
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
            <body class=\"flex flex-col justify-center min-h-full font-sans antialiased bg-slate-50 px-4 py-12 selection:bg-[#f58613] selection:text-white\">
                <div class=\"w-full max-w-md mx-auto bg-white border border-slate-200 rounded-2xl shadow-xl p-8 space-y-6\">
                    <div class=\"text-center space-y-2\">
                        <div class=\"inline-flex items-center justify-center w-12 h-12 rounded-xl bg-orange-50 text-[#f58613] text-xl font-bold mb-2 shadow-sm\">📱</div>
                        <h2 class=\"text-xl font-black text-slate-950 uppercase tracking-tight\">Confirm Your Identity</h2>
                        <p class=\"text-xs text-slate-500 font-semibold max-w-[280px] mx-auto leading-normal\">We just texted a 6-digit security code to your phone for extra verification.</p>
                    </div>

                    " . (session()->has('errors') ? "
                        <div class=\"p-3 bg-red-50 text-red-700 border border-red-200 rounded-xl text-xs font-bold text-center shadow-inner\">
                            " . session('errors')->first() . "
                        </div>
                    " : "") . "

                    <form action=\"" . route('magic.2fa') . "\" method=\"POST\" class=\"space-y-4\">
                        <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">
                        <div>
                            <label for=\"secure_code\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Enter 6-Digit Security Code</label>
                            <input type=\"text\" id=\"secure_code\" name=\"two_factor_code\" required maxlength=\"6\" placeholder=\"000000\" inputmode=\"numeric\" pattern=\"[0-9]*\" autocomplete=\"one-time-code\" class=\"w-full bg-slate-50 border border-slate-300 rounded-xl py-3 px-4 text-center text-lg font-mono font-black tracking-[0.5em] text-slate-900 focus:outline-none focus:border-[#f58613] shadow-inner\">
                        </div>

                        <button type=\"submit\" class=\"w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer\">
                            Verify Code & Log In &rarr;
                        </button>
                    </form>
                </div>
            </body>
            </html>
        ");
    }

    /**
     * Complete text code authorization using session parameters.
     */
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|string|size:6',
        ]);

        $userId    = session('auth_2fa_user_id');
        $savedCode = session('auth_2fa_code');
        $expiresAt = session('auth_2fa_expires_at');

        if (!$userId || !$savedCode || !$expiresAt) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 Your verification session has expired. Please sign in again.']);
        }

        if (time() > $expiresAt || $savedCode !== $request->two_factor_code) {
            return redirect()->route('magic.2fa.view')->withErrors(['two_factor_code' => '🛑 The verification code entered is incorrect or has expired. Please verify your alerts and retry.']);
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 Target profile identity could not be verified.']);
        }

        // Wipe single-use token details from the database now that authentication is complete
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->login_token = null;
        $user->token_expires_at = null;
        $user->save();

        Auth::login($user, true);
        $request->session()->regenerate();

        $request->session()->put('auth.password_confirmed_at', time());
        $request->session()->save();

        session()->forget(['auth_2fa_user_id', 'auth_2fa_code', 'auth_2fa_expires_at']);

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
