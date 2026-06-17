<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MagicAuthController extends Controller
{
    /**
     * Generate a passwordless login token and dispatch the magic access link.
     */
    public function sendLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Guard against unlisted sign-in attempts without leaking account existence profiles
        if (!$user) {
            return back()->with('status', '📨 If your business email is registered in our directory, a secure access link has been sent.');
        }

        // Generate secure login token strings
        $token = Str::random(64);

        $user->update([
            'login_token' => $token,
            'token_expires_at' => now()->addMinutes(15),
        ]);

        $magicLink = route('magic.verify', ['token' => $token]);

        try {
            // Simple text message delivery frame
            Mail::raw("Hello, click the link below to securely log into your ContractorSpecialties workspace. This link will expire in 15 minutes.\n\n{$magicLink}", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('⚡ Your Secure Workspace Access Link');
            });

            // Safety backup entry inside logs for quick manual verification testing
            Log::info("🔑 Magic Sign-In Link generated for {$user->email}: {$magicLink}");

        } catch (\Exception $e) {
            Log::error("🚨 Mail service failed to dispatch magic link: " . $e->getMessage());

            // Local sandbox fallback so you can always log in during testing even if SMTP is dark
            if (config('app.env') === 'local' || config('app.env') === 'staging') {
                Log::info("💡 Staging Safety Intercept: Link written to log file. Copy-paste this route: {$magicLink}");
            }
        }

        return back()->with('status', '📨 If your business email is registered in our directory, a secure access link has been sent.');
    }

    /**
     * Validate incoming access link and authenticate the session safely.
     */
    public function verifyToken($token)
    {
        $user = User::where('login_token', $token)
            ->where('token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->route('welcome')->withErrors(['email' => '🛑 Access link has expired or is invalid. Please request a new secure key link.']);
        }

        // Expire token immediately after first consumption to secure workspace parameters
        $user->update([
            'login_token' => null,
            'token_expires_at' => null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('status', '⚡ Workspace successfully authenticated. Welcome back!');
    }

    /**
     * Terminate the operational session frame cleanly.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
