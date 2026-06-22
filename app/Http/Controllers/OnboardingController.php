<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    /**
     * Render the unified, high-utility onboarding wizard interface.
     */
    public function showWizard(Request $request)
    {
        $user = Auth::user();

        // Safety check: If they are already onboarded, don't let them re-run the initial wizard layout
        if ($user && $user->onboarding_completed_at) {
            return redirect()->route('dashboard');
        }

        return response("
            <!DOCTYPE html>
            <html lang=\"en\" class=\"h-full bg-slate-900\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Workspace Setup Framework</title>
                <script src=\"https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4\"></script>
                <script defer src=\"https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js\"></script>
            </head>
            <body class=\"min-h-full font-sans antialiased text-slate-200 bg-slate-950 flex flex-col justify-center px-4 py-12 selection:bg-[#f58613] selection:text-white\">

                <div x-data=\"{ step: 1 }\" class=\"w-full max-w-xl mx-auto bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-8 space-y-8\">

                    <div class=\"flex items-center justify-between border-b border-slate-800 pb-5\">
                        <div>
                            <span class=\"text-[10px] font-black uppercase text-[#f58613] tracking-widest\">Workspace Activation</span>
                            <h2 class=\"text-xl font-black text-white tracking-tight uppercase\" x-show=\"step === 1\">1. Personal Vitals</h2>
                            <h2 class=\"text-xl font-black text-white tracking-tight uppercase\" x-show=\"step === 2\">2. Business Blueprint</h2>
                            <h2 class=\"text-xl font-black text-white tracking-tight uppercase\" x-show=\"step === 3\">3. System Defaults</h2>
                        </div>
                        <div class=\"text-xs font-mono font-bold text-slate-500 bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-800 shadow-inner\">
                            STEP <span class=\"text-white font-black\" x-text=\"step\"></span> OF 3
                        </div>
                    </div>

                    " . (session()->has('errors') ? "
                        <div class=\"p-4 bg-red-950/50 border border-red-900 text-red-400 rounded-xl text-xs font-bold shadow-inner\">
                            🛑 " . session('errors')->first() . "
                        </div>
                    " : "") . "

                    <form action=\"" . route('onboarding.submit') . "\" method=\"POST\" class=\"space-y-6\">
                        <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">

                        <div x-show=\"step === 1\" class=\"space-y-5\" x-transition:enter=\"transition ease-out duration-200\" x-transition:enter-start=\"opacity-0 transform translate-x-2\">
                            <div class=\"grid grid-cols-2 gap-4\">
                                <div>
                                    <label for=\"first_name\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">First Name</label>
                                    <input type=\"text\" id=\"first_name\" name=\"first_name\" required value=\"" . old('first_name') . "\" placeholder=\"John\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                </div>
                                <div>
                                    <label for=\"last_name\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Last Name</label>
                                    <input type=\"text\" id=\"last_name\" name=\"last_name\" required value=\"" . old('last_name') . "\" placeholder=\"Doe\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                </div>
                            </div>
                            <div>
                                <label for=\"personal_phone\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Direct Mobile Number (For 2FA Security Security)</label>
                                <input type=\"tel\" id=\"personal_phone\" name=\"phone_2fa\" required value=\"" . old('phone_2fa') . "\" placeholder=\"+1 (555) 000-0000\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                            </div>

                            <div class=\"pt-4\">
                                <button type=\"button\" @click=\"step = 2\" class=\"w-full bg-slate-800 hover:bg-slate-700 text-white font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer text-center\">
                                    Continue Setup Pipeline &rarr;
                                </button>
                            </div>
                        </div>

                        <div x-show=\"step === 2\" class=\"space-y-5\" x-cloak x-transition:enter=\"transition ease-out duration-200\" x-transition:enter-start=\"opacity-0 transform translate-x-2\">
                            <div>
                                <label for=\"trade_classification\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Primary Trade Classification</label>
                                <select id=\"trade_classification\" name=\"trade\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                    <option value=\"\">Select your trade specialization...</option>
                                    <option value=\"plumbing\">Plumbing Frameworks</option>
                                    <option value=\"electrical\">Electrical Infrastructure</option>
                                    <option value=\"hvac\">HVAC Climatic Systems</option>
                                    <option value=\"roofing\">Roofing Architecture</option>
                                    <option value=\"landscaping\">Landscape Development</option>
                                    <option value=\"general-contracting\">General Contracting Management</option>
                                </select>
                            </div>
                            <div>
                                <label for=\"company_address\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Corporate Office Street Address</label>
                                <input type=\"text\" id=\"company_address\" name=\"address\" required value=\"" . old('address') . "\" placeholder=\"123 Main St, Suite 100\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                            </div>
                            <div class=\"grid grid-cols-3 gap-4\">
                                <div class=\"col-span-2\">
                                    <label for=\"city\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">City</label>
                                    <input type=\"text\" id=\"city\" name=\"city\" required value=\"" . old('city') . "\" placeholder=\"Phoenix\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                </div>
                                <div>
                                    <label for=\"state\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">State</label>
                                    <input type=\"text\" id=\"state\" name=\"state\" required maxlength=\"2\" value=\"" . old('state') . "\" placeholder=\"AZ\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm text-center font-mono font-black uppercase text-white shadow-inner focus:outline-none\">
                                </div>
                            </div>

                            <div class=\"grid grid-cols-3 gap-4 pt-4\">
                                <button type=\"button\" @click=\"step = 1\" class=\"bg-slate-950 border border-slate-800 hover:bg-slate-900 text-slate-400 font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all cursor-pointer text-center\">&larr; Back</button>
                                <button type=\"button\" @click=\"step = 3\" class=\"col-span-2 bg-slate-800 hover:bg-slate-700 text-white font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer text-center\">Next Parameters &rarr;</button>
                            </div>
                        </div>

                        <div x-show=\"step === 3\" class=\"space-y-5\" x-cloak x-transition:enter=\"transition ease-out duration-200\" x-transition:enter-start=\"opacity-0 transform translate-x-2\">
                            <p class=\"text-xs text-slate-400 leading-relaxed font-medium bg-slate-950/40 p-4 border border-slate-800/80 rounded-xl shadow-inner\">
                                ⚡ <strong>Instant Platform Clearance Activation:</strong> Completing this form activates your core SaaS invoice systems and job tracking software. Your company configuration defaults will populate your personal portal immediately.
                            </p>
                            <div class=\"grid grid-cols-2 gap-4\">
                                <div>
                                    <label for=\"tax_rate\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Default Tax Rate (%)</label>
                                    <input type=\"number\" id=\"tax_rate\" name=\"default_tax_rate\" step=\"0.01\" value=\"8.10\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                </div>
                                <div>
                                    <label for=\"invoice_sequence\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Estimate/Invoice Prefix Starting Number</label>
                                    <input type=\"number\" id=\"invoice_sequence\" name=\"starting_invoice_number\" value=\"1000\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-mono font-bold text-white shadow-inner focus:outline-none\">
                                </div>
                            </div>

                            <div class=\"grid grid-cols-3 gap-4 pt-4\">
                                <button type=\"button\" @click=\"step = 2\" class=\"bg-slate-950 border border-slate-800 hover:bg-slate-900 text-slate-400 font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all cursor-pointer text-center\">&larr; Back</button>
                                <button type=\"submit\" class=\"col-span-2 bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer text-center\">
                                    Launch Enterprise Dashboard &rarr;
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </body>
            </html>
        ");
    }

    /**
     * Process configuration payload, evaluate hyper-local directory constraints, and unlock SaaS platform access.
     */
    public function processWizard(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->onboarding_completed_at) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'first_name'              => 'required|string|max:255',
            'last_name'               => 'required|string|max:255',
            'phone_2fa'               => 'required|string|max:30',
            'trade'                   => 'required|string|max:255',
            'address'                 => 'required|string|max:255',
            'city'                    => 'required|string|max:255',
            'state'                   => 'required|string|size:2',
            'default_tax_rate'        => 'required|numeric|min:0',
            'starting_invoice_number' => 'required|integer|min:1',
        ]);

        // Secure dynamic prefix extraction logic mapping framework standardizations cleanly
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        try {
            DB::beginTransaction();

            // 1. Hydrate User Identity Records
            $user->first_name = $validated['first_name'];
            $user->last_name = $validated['last_name'];
            $user->phone_2fa = $validated['phone_2fa'];
            $user->onboarding_completed_at = now();
            $user->save();

            // 2. Resolve Hyper-Local City Constraints against the Directory Database
            $citySlug = Str::slug($validated['city']);
            $stateSlug = strtolower($validated['state']);

            // Search the directory map to see if this explicit city block has been built or flagged active
            $cityDirectoryRecord = DB::table($prefix . 'directory_cities')
                ->where('slug', $citySlug)
                ->where('state_code', $stateSlug)
                ->first();

            // If the market city is live and verified, flag the profile publicly listed instantly.
            // If the market is unlaunched or doesn't exist yet, it defaults to false (Staging Mode)
            // This isolates unlaunched cities from index crawlers while keeping dashboard system access open.
            $isPubliclyListed = false;
            if ($cityDirectoryRecord && $cityDirectoryRecord->status === 'active') {
                $isPubliclyListed = true;
            }

            // 3. Hydrate Corporate Workspace Parameters
            DB::table($prefix . 'companies')
                ->where('id', $user->company_id)
                ->update([
                    'trade'                    => $validated['trade'],
                    'address'                  => $validated['address'],
                    'city'                     => $validated['city'],
                    'state'                    => strtoupper($validated['state']),
                    'city_slug'                => $citySlug,
                    'state_slug'               => $stateSlug,
                    'is_publicly_listed'       => $isPubliclyListed,
                    'default_tax_rate'         => $validated['default_tax_rate'],
                    'starting_invoice_number'  => $validated['starting_invoice_number'],
                    'updated_at'               => now(),
                ]);

            DB::commit();

            Log::info("🚀 Onboarding pipeline successful for Workspace ID: {$user->company_id}. City Staging Flag: " . ($isPubliclyListed ? 'LIVE' : 'STAGED/HIDDEN'));

            return redirect()->route('dashboard')->with('status', '⚡ Configuration locked. Welcome to your active company control hub!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("🚨 Onboarding configuration pipeline engine failure: " . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'An operational logic fault occurred during profile configuration. Please check your data fields and retry.']);
        }
    }
}
