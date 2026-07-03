<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    /**
     * Render the high-utility 5-step resilient onboarding interface.
     */
    public function showWizard(Request $request)
    {
        $user = Auth::user();

        // Safety check: If already onboarded, route directly to master workspace console
        if ($user && $user->onboarding_completed_at) {
            return redirect()->route('dashboard');
        }

        // Secure dynamic prefix extraction matching your model standardizations cleanly
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';

        // Self-heal the database schema layers defensively before running queries
        $this->ensureSchemaIsHealed($companyTable);

        $company = DB::table($companyTable)->where('id', $user->company_id)->first();

        // Extract current step state from session fallback or user database value
        $step = session('onboarding_active_step', 1);

        // Defensive check: Inspect if Stripe environment variables are live inside the server shell
        $stripeConfigured = !empty(env('STRIPE_SECRET'));

        return response("
            <!DOCTYPE html>
            <html lang=\"en\" class=\"h-full bg-slate-950 text-slate-200\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Workspace Core Calibration Setup | ContractorSpecialties</title>
                <script src=\"https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4\"></script>
                <script defer src=\"https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js\"></script>
                <style>
                    [x-cloak] { display: none !important; }
                </style>
            </head>
            <body class=\"min-h-full font-sans antialiased text-slate-200 bg-slate-950 flex flex-col justify-center px-4 py-20 selection:bg-[#f58613] selection:text-white relative\">

                <!-- 🛡️ FIXED EMERGENCY ACCESS DISMISSAL BANNER -->
                " . (session()->has('admin_impersonator_id') ? "
                    <div class=\"fixed top-0 inset-x-0 bg-amber-600 border-b border-amber-700 text-white font-sans text-center py-3.5 px-4 flex justify-between items-center z-[9999] shadow-lg shrink-0 text-left\">
                        <div class=\"flex items-center gap-2 text-xs font-black uppercase tracking-wider\">
                            🚨 INTERCEPT ACTIVE: Calibrating setup metrics for un-onboarded profile partition
                        </div>
                        <form action=\"" . route('admin.impersonate.stop') . "\" method=\"POST\" class=\"m-0\">
                            <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">
                            <button type=\"submit\" class=\"bg-slate-950 hover:bg-slate-900 border border-slate-900 text-amber-400 font-black text-[10px] py-1.5 px-3.5 rounded-lg uppercase tracking-widest transition-all cursor-pointer shadow-md\">
                                Disconnect Bridge & Return &rarr;
                            </button>
                        </form>
                    </div>
                " : "") . "

                <div class=\"w-full max-w-xl mx-auto bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 md:p-8 space-y-6\">

                    <!-- Tactical Progress Track Module -->
                    <div class=\"flex items-center justify-between border-b border-slate-800 pb-5\">
                        <div>
                            <span class=\"text-[10px] font-black uppercase text-[#f58613] tracking-widest\">Workspace Activation</span>
                            <h2 class=\"text-lg font-black text-white tracking-tight uppercase\">
                                " . match($step) {
                                    1 => "1. Core Dispatch Vitals",
                                    2 => "2. Identity & Compliance",
                                    3 => "3. Scope Calibration",
                                    4 => "4. Trust Signal Alignment",
                                    5 => "5. Financial Ledger & Crew",
                                    default => "Workspace Configuration"
                                } . "
                            </h2>
                        </div>
                        <div class=\"text-xs font-mono font-bold text-slate-500 bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-800 shadow-inner shrink-0\">
                            PHASE <span class=\"text-white font-black\">{$step}</span> OF 5
                        </div>
                    </div>

                    <!-- Progress Bar Layout -->
                    <div class=\"w-full h-1.5 bg-slate-950 rounded-full overflow-hidden flex gap-1 shadow-inner\">
                        " . implode('', array_map(function($i) use ($step) {
                            $color = $i <= $step ? 'bg-[#f58613]' : 'bg-slate-800';
                            return "<div class=\"h-full flex-1 transition-all duration-300 {$color}\"></div>";
                        }, range(1, 5))) . "
                    </div>

                    " . (session()->has('errors') ? "
                        <div class=\"p-4 bg-red-950/40 border border-red-900/60 text-red-400 rounded-xl text-xs font-bold shadow-inner\">
                            🛑 " . session('errors')->first() . "
                        </div>
                    " : "") . "

                    " . (session()->has('status') ? "
                        <div class=\"p-4 bg-emerald-950/40 border border-emerald-900/60 text-emerald-400 rounded-xl text-xs font-bold shadow-inner\">
                            ✓ " . session('status') . "
                        </div>
                    " : "") . "

                    <form action=\"" . route('onboarding.submit') . "\" method=\"POST\" enctype=\"multipart/form-data\" class=\"space-y-6\">
                        <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">
                        <input type=\"hidden\" name=\"current_step\" value=\"{$step}\">

                        <!-- PHASE 1: CORE DISPATCH VITALS -->
                        " . ($step === 1 ? "
                            <div class=\"space-y-5\">
                                <div class=\"p-4 bg-slate-950 border border-slate-800 rounded-xl flex items-start gap-3 shadow-inner\">
                                    <span class=\"text-base select-none\">⚡</span>
                                    <p class=\"text-[11px] text-slate-400 leading-normal font-medium\">
                                        <span class=\"font-black text-white uppercase text-[9px] block tracking-wide mb-0.5\">The Easy Win</span>
                                        Calibrate your baseline operating files. These metrics anchor your routing map and automated lead engines.
                                    </p>
                                </div>

                                <div class=\"grid grid-cols-2 gap-4\">
                                    <div>
                                        <label for=\"first_name\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Principal First Name</label>
                                        <input type=\"text\" id=\"first_name\" name=\"first_name\" required value=\"" . old('first_name', $user->first_name) . "\" placeholder=\"e.g., Jack\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                    <div>
                                        <label for=\"last_name\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Principal Last Name</label>
                                        <input type=\"text\" id=\"last_name\" name=\"last_name\" required value=\"" . old('last_name', $user->last_name) . "\" placeholder=\"e.g., Person\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                </div>

                                <div>
                                    <label for=\"company_name\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Company Logged Name</label>
                                    <input type=\"text\" id=\"company_name\" name=\"company_name\" required value=\"" . old('company_name', $company->name ?? '') . "\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                </div>

                                <div class=\"grid grid-cols-3 gap-4 items-end\">
                                    <div class=\"col-span-2\">
                                        <label for=\"city\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Central Operating Base (City)</label>
                                        <input type=\"text\" id=\"city\" name=\"city\" required value=\"" . old('city', $company->city ?? '') . "\" placeholder=\"e.g., Washington\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                    <div>
                                        <label for=\"state\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">State</label>
                                        <input type=\"text\" id=\"state\" name=\"state\" required maxlength=\"2\" value=\"" . old('state', $company->state ?? '') . "\" placeholder=\"NC\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm text-center font-mono font-black uppercase text-white shadow-inner focus:outline-none\">
                                    </div>
                                </div>

                                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                                    <div>
                                        <label for=\"trade\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Primary Specialty Trade</label>
                                        <select id=\"trade\" name=\"trade\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none appearance-none\">
                                            <option value=\"\">Select trade specialization...</option>
                                            " . implode('', array_map(function($t) use ($company) {
                                                $sel = (isset($company->trade) && $company->trade === $t) ? 'selected' : '';
                                                return "<option value=\"{$t}\" {$sel}>{$t}</option>";
                                            }, ['Roofing Architecture', 'Landscape Development', 'Lawn Care Operations', 'HVAC Climatic Systems', 'Plumbing Frameworks', 'Electrical Infrastructure', 'General Contracting Management'])) . "
                                        </select>
                                    </div>
                                    <div>
                                        <label for=\"service_radius_miles\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Dispatch Scope (Radius Miles)</label>
                                        <input type=\"number\" id=\"service_radius_miles\" name=\"service_radius_miles\" required value=\"" . old('service_radius_miles', $company->service_radius_miles ?? '25') . "\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-mono font-bold text-white shadow-inner focus:outline-none\">
                                    </div>
                                </div>

                                <div class=\"pt-4 flex justify-end\">
                                    <button type=\"submit\" name=\"direction\" value=\"next\" class=\"w-full md:w-auto bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-8 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer text-center\">
                                        Lock Progress & Continue &rarr;
                                    </button>
                                </div>
                            </div>
                        " : "") . "

                        <!-- PHASE 2: IDENTITY & COMPLIANCE -->
                        " . ($step === 2 ? "
                            <div class=\"space-y-5\">
                                <div class=\"p-4 bg-slate-950 border border-slate-800 rounded-xl flex items-start gap-3 shadow-inner\">
                                    <span class=\"text-base select-none\">🗺️</span>
                                    <p class=\"text-[11px] text-slate-400 leading-normal font-medium\">
                                        <span class=\"font-black text-white uppercase text-[9px] block tracking-wide mb-0.5\">Build Your Public Profile</span>
                                        We are gathering SEO gold to establish your public lead-generation footprint. Frame your trust assets perfectly.
                                    </p>
                                </div>

                                <div class=\"grid grid-cols-1 md:grid-cols-3 gap-4\">
                                    <div class=\"md:col-span-1\">
                                        <label for=\"years_experience\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Years in Business</label>
                                        <input type=\"number\" id=\"years_experience\" name=\"years_experience\" required value=\"" . old('years_experience', $company->years_experience ?? '0') . "\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                    <div class=\"md:col-span-1\">
                                        <label for=\"license_number\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">License Registration #</label>
                                        <input type=\"text\" id=\"license_number\" name=\"license_number\" placeholder=\"Optional\" value=\"" . old('license_number', $company->license_number ?? '') . "\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                    <div class=\"md:col-span-1\">
                                        <label for=\"is_insured\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">General Liability Insured</label>
                                        <select id=\"is_insured\" name=\"is_insured\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none appearance-none\">
                                            <option value=\"1\" " . (old('is_insured', $company->is_insured ?? '') == '1' ? 'selected' : '') . ">Yes, Active Policy</option>
                                            <option value=\"0\" " . (old('is_insured', $company->is_insured ?? '') == '0' ? 'selected' : '') . ">No Coverage Active</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for=\"company_bio_short\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Company Profile Punchline (Short Hook)</label>
                                    <input type=\"text\" id=\"company_bio_short\" name=\"company_bio_short\" placeholder=\"Profiles with hooks get 3-5x more clicks\" value=\"" . old('company_bio_short', $company->company_bio_short ?? '') . "\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                </div>

                                <div>
                                    <label for=\"company_bio_long\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Detailed Bio Operations Summary (SEO Content Layer)</label>
                                    <textarea id=\"company_bio_long\" name=\"company_bio_long\" rows=\"4\" required placeholder=\"Describe your team history, warranties, and local commitments...\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-medium text-slate-200 shadow-inner focus:outline-none font-sans\">" . old('company_bio_long', $company->company_bio_long ?? '') . "</textarea>
                                </div>

                                <div>
                                    <label class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Corporate Brand Logo</label>
                                    <div class=\"border-2 border-dashed border-slate-800 hover:border-slate-700 rounded-2xl p-6 bg-slate-950 text-center transition-colors relative\">
                                        <input type=\"file\" name=\"logo\" accept=\"image/*\" class=\"absolute inset-0 w-full h-full opacity-0 cursor-pointer\">
                                        <div class=\"text-xs font-bold text-slate-400\">
                                            📸 <span class=\"text-[#f58613]\">Click to upload brand logo</span> or drag file here
                                            " . (!empty($company->logo_path) ? "<p class=\"text-emerald-400 font-mono text-[10px] mt-1\">✓ Existing logo asset saved in profile</p>" : "<p class=\"text-[10px] text-slate-600 font-mono mt-1\">PNG, JPG, or WEBP up to 2MB</p>") . "
                                        </div>
                                    </div>
                                </div>

                                <div class=\"grid grid-cols-3 gap-4 pt-4\">
                                    <button type=\"submit\" name=\"direction\" value=\"back\" class=\"bg-slate-950 border border-slate-800 hover:bg-slate-800 text-slate-400 font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all cursor-pointer text-center\">&larr; Back</button>
                                    <button type=\"submit\" name=\"direction\" value=\"next\" class=\"col-span-2 bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer text-center\">Commit & Continue &rarr;</button>
                                </div>
                            </div>
                        " : "") . "

                        <!-- PHASE 3: SCOPE CALIBRATION -->
                        " . ($step === 3 ? "
                            <div class=\"space-y-5\">
                                <div class=\"p-4 bg-slate-950 border border-slate-800 rounded-xl flex items-start gap-3 shadow-inner\">
                                    <span class=\"text-base select-none\">🔨</span>
                                    <p class=\"text-[11px] text-slate-400 leading-normal font-medium\">
                                        <span class=\"font-black text-white uppercase text-[9px] block tracking-wide mb-0.5\">Service Scope Calibration</span>
                                        Check off your functional operational assets. Write 1-2 sentences about each field capability later for automated copy updates.
                                    </p>
                                </div>

                                <div>
                                    <label class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-3\">Select Active Field Capabilities</label>
                                    <div class=\"grid grid-cols-1 md:grid-cols-2 gap-3\">
                                        " . implode('', array_map(function($service) use ($company) {
                                            $tags = isset($company->service_tags) ? json_decode($company->service_tags, true) : [];
                                            if (!is_array($tags)) $tags = [];
                                            $checked = in_array($service, $tags) ? 'checked' : '';
                                            return "
                                                <label class=\"flex items-center gap-3 p-3 bg-slate-950 border border-slate-800 rounded-xl text-xs font-bold text-white cursor-pointer hover:border-[#f58613] transition-all shadow-inner\">
                                                    <input type=\"checkbox\" name=\"service_tags[]\" value=\"{$service}\" {$checked} class=\"accent-[#f58613] w-4 h-4 shrink-0\">
                                                    <span>{$service}</span>
                                                </label>
                                            ";
                                        }, ['Full Tear-Off Replacement', 'Emergency Structural Patching', 'Storm Damage Remediation', 'Preventative Inspection Maintenance', 'Gutter System Integrations', 'Commercial Property Overhauls', 'Complete Site Layout Renovations', 'Routine Cleanings'])) . "
                                    </div>
                                </div>

                                <div>
                                    <label for=\"emergency_availability\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">24/7 Rapid Response Dispatch Capability</label>
                                    <select id=\"emergency_availability\" name=\"emergency_availability\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none appearance-none\">
                                        <option value=\"0\" " . (old('emergency_availability', $company->emergency_availability ?? '') == '0' ? 'selected' : '') . ">No, Standard Working Shifts Only</option>
                                        <option value=\"1\" " . (old('emergency_availability', $company->emergency_availability ?? '') == '1' ? 'selected' : '') . ">Yes, Available for Emergency Overtime Callouts</option>
                                    </select>
                                </div>

                                <div>
                                    <label class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Showcase Portfolio Work Image</label>
                                    <div class=\"border-2 border-dashed border-slate-800 hover:border-slate-700 rounded-2xl p-6 bg-slate-950 text-center transition-colors relative\">
                                        <input type=\"file\" name=\"portfolio_img\" accept=\"image/*\" class=\"absolute inset-0 w-full h-full opacity-0 cursor-pointer\">
                                        <div class=\"text-xs font-bold text-slate-400\">
                                            🏗️ <span class=\"text-[#f58613]\">Upload an image of recent field execution</span> or drag file here
                                            " . (!empty($company->portfolio_image_path) ? "<p class=\"text-emerald-400 font-mono text-[10px] mt-1\">✓ Showcase asset active on system disk</p>" : "<p class=\"text-[10px] text-slate-600 font-mono mt-1\">High resolution image up to 4MB</p>") . "
                                        </div>
                                    </div>
                                </div>

                                <div class=\"grid grid-cols-3 gap-4 pt-4\">
                                    <button type=\"submit\" name=\"direction\" value=\"back\" class=\"bg-slate-950 border border-slate-800 hover:bg-slate-800 text-slate-400 font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all cursor-pointer text-center\">&larr; Back</button>
                                    <button type=\"submit\" name=\"direction\" value=\"next\" class=\"col-span-2 bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer text-center\">Commit & Continue &rarr;</button>
                                </div>
                            </div>
                        " : "") . "

                        <!-- PHASE 4: TRUST SIGNALS -->
                        " . ($step === 4 ? "
                            <div class=\"space-y-5\">
                                <div class=\"p-4 bg-slate-950 border border-slate-800 rounded-xl flex items-start gap-3 shadow-inner\">
                                    <span class=\"text-base select-none\">📈</span>
                                    <p class=\"text-[11px] text-slate-400 leading-normal font-medium\">
                                        <span class=\"font-black text-white uppercase text-[9px] block tracking-wide mb-0.5\">Social Proof Integration</span>
                                        Drop in external reputation endpoints to link reviews automatically and reinforce conversion signals on public directories.
                                    </p>
                                </div>

                                " . (function() use ($company) {
                                    $links = isset($company->social_links) ? json_decode($company->social_links, true) : [];
                                    if (!is_array($links)) $links = [];
                                    $google = $links['google'] ?? '';
                                    $facebook = $links['facebook'] ?? '';
                                    $yelp = $links['yelp'] ?? '';

                                    return "
                                        <div class=\"space-y-4\">
                                            <div>
                                                <label for=\"google_review_link\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Google Business Profile Review URL</label>
                                                <input type=\"url\" id=\"google_review_link\" name=\"google_review_link\" placeholder=\"https://g.page/r/.../review\" value=\"" . old('google_review_link', $google) . "\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                            </div>
                                            <div>
                                                <label for=\"facebook_link\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Facebook Corporate Page URL</label>
                                                <input type=\"url\" id=\"facebook_link\" name=\"facebook_link\" placeholder=\"https://facebook.com/yourbusiness\" value=\"" . old('facebook_link', $facebook) . "\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                            </div>
                                            <div>
                                                <label for=\"yelp_link\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Yelp Business Directory URL</label>
                                                <input type=\"url\" id=\"yelp_link\" name=\"yelp_link\" placeholder=\"https://yelp.com/biz/your-slug\" value=\"" . old('yelp_link', $yelp) . "\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                            </div>
                                        </div>
                                    ";
                                })() . "

                                <div class=\"grid grid-cols-3 gap-4 pt-4\">
                                    <button type=\"submit\" name=\"direction\" value=\"back\" class=\"bg-slate-950 border border-slate-800 hover:bg-slate-800 text-slate-400 font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all cursor-pointer text-center\">&larr; Back</button>
                                    <button type=\"submit\" name=\"direction\" value=\"next\" class=\"col-span-2 bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer text-center\">Commit & Continue &rarr;</button>
                                </div>
                            </div>
                        " : "") . "

                        <!-- PHASE 5: FINANCIAL LEDGER & WORKFLOWS -->
                        " . ($step === 5 ? "
                            <div class=\"space-y-5\">
                                <div class=\"p-4 bg-slate-950 border border-slate-800 rounded-xl flex items-start gap-3 shadow-inner\">
                                    <span class=\"text-base select-none\">💵</span>
                                    <p class=\"text-[11px] text-slate-400 leading-normal font-medium\">
                                        <span class=\"font-black text-white uppercase text-[9px] block tracking-wide mb-0.5\">Operational SaaS Ledger Setup</span>
                                        Calibrate your tax thresholds, crew structures, and automatic invoicing parameters to deploy your workspace live.
                                    </p>
                                </div>

                                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                                    <div>
                                        <label for=\"crew_structure\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Crew Infrastructure Alignment</label>
                                        <select id=\"crew_structure\" name=\"crew_structure\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none appearance-none\">
                                            <option value=\"solo\" " . (old('crew_structure', $company->crew_structure ?? '') === 'solo' ? 'selected' : '') . ">Solo Operator (Single Line)</option>
                                            <option value=\"multi-crew\" " . (old('crew_structure', $company->crew_structure ?? '') === 'multi-crew' ? 'selected' : '') . ">Multi-Crew Routing Structure</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for=\"invoice_preferences\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Billing Dispatch Target</label>
                                        <select id=\"invoice_preferences\" name=\"invoice_preferences\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none appearance-none\">
                                            <option value=\"digital_only\" " . (old('invoice_preferences', $company->invoice_preferences ?? '') === 'digital_only' ? 'selected' : '') . ">Digital Link via Interactive SMS Terminal</option>
                                            <option value=\"cod\" " . (old('invoice_preferences', $company->invoice_preferences ?? '') === 'cod' ? 'selected' : '') . ">Collect On Delivery (Field Approvals)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class=\"grid grid-cols-2 gap-4\">
                                    <div>
                                        <label for=\"tax_rate\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Default Service Tax Rate (%)</label>
                                        <input type=\"number\" id=\"tax_rate\" name=\"default_tax_rate\" step=\"0.01\" value=\"" . old('default_tax_rate', $company->default_tax_rate ?? '8.10') . "\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                    <div>
                                        <label for=\"invoice_sequence\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Estimate/Invoice Starting Serial</label>
                                        <input type=\"number\" id=\"invoice_sequence\" name=\"starting_invoice_number\" value=\"" . old('starting_invoice_number', $company->starting_invoice_number ?? '1000') . "\" required class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-mono font-bold text-white shadow-inner focus:outline-none\">
                                    </div>
                                </div>

                                <div>
                                    <label for=\"deposit_rules\" class=\"block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5\">Mobilization Deposit Guidelines (Contract Rules)</label>
                                    <textarea id=\"deposit_rules\" name=\"deposit_rules\" rows=\"3\" placeholder=\"e.g., A 50% material mobilization deposit is required on all projects exceeding $2,500 prior to scheduling...\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-medium text-slate-200 shadow-inner focus:outline-none font-sans\">" . old('deposit_rules', $company->deposit_rules ?? '') . "</textarea>
                                </div>

                                " . ($stripeConfigured ? "
                                    <div class=\"p-4 bg-emerald-950/30 border border-emerald-900/60 rounded-xl text-xs font-bold text-emerald-400 shadow-inner\">
                                        ✅ Merchant Config recognized. Deploying locks your secure payment parameters on registration complete.
                                    </div>
                                " : "
                                    <div class=\"p-4 bg-amber-950/40 border border-amber-900/60 rounded-xl text-xs font-medium text-amber-400 shadow-inner leading-relaxed\">
                                        ⚙️ <span class=\"font-black uppercase text-[10px]\">Developer Sandbox Processing Active</span><br>
                                        Stripe tokens unpopulated inside host shell environment. Fallback processor simulation bypasses live checks for local workspace confirmation.
                                    </div>
                                ") . "

                                <div class=\"grid grid-cols-3 gap-4 pt-4\">
                                    <button type=\"submit\" name=\"direction\" value=\"back\" class=\"bg-slate-950 border border-slate-800 hover:bg-slate-800 text-slate-400 font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all cursor-pointer text-center\">&larr; Back</button>
                                    <button type=\"submit\" name=\"direction\" value=\"next\" class=\"col-span-2 bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer text-center\">
                                        " . ($stripeConfigured ? "Deploy & Activate Control Deck ⚡" : "Simulate Live Field Deployment ⚡") . "
                                    </button>
                                </div>
                            </div>
                        " : "") . "

                    </form>
                </div>
            </body>
            </html>
        ");
    }

    /**
     * Process progressive setup phases defensively and commit metrics live to disk memory.
     */
    public function processWizard(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->onboarding_completed_at) {
            return redirect()->route('dashboard');
        }

        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';

        // Secure baseline table updates ahead of parameter mutation steps
        $this->ensureSchemaIsHealed($companyTable);

        $currentStep = (int)$request->input('current_step', 1);
        $direction = $request->input('direction', 'next');

        if ($direction === 'back') {
            $prev = max(1, $currentStep - 1);
            session(['onboarding_active_step' => $prev]);
            return redirect()->route('onboarding.view')->withInput();
        }

        try {
            if ($currentStep === 1) {
                $validated = $request->validate([
                    'first_name'           => 'required|string|max:255',
                    'last_name'            => 'required|string|max:255',
                    'company_name'         => 'required|string|max:255',
                    'city'                 => 'required|string|max:255',
                    'state'                => 'required|string|size:2',
                    'trade'                => 'required|string|max:255',
                    'service_radius_miles' => 'required|integer|min:1|max:500',
                ]);

                $user->update([
                    'first_name' => $validated['first_name'],
                    'last_name'  => $validated['last_name'],
                ]);

                $citySlug = Str::slug($validated['city']);
                $stateSlug = strtolower($validated['state']);
                $isPubliclyListed = false;

                if (Schema::hasTable($prefix . 'directory_cities')) {
                    $record = DB::table($prefix . 'directory_cities')
                        ->where('slug', $citySlug)
                        ->where('state_code', $stateSlug)
                        ->first();
                    if ($record && $record->status === 'active') {
                        $isPubliclyListed = true;
                    }
                }

                DB::table($companyTable)->where('id', $user->company_id)->update([
                    'name'                 => $validated['company_name'],
                    'city'                 => $validated['city'],
                    'state'                => strtoupper($validated['state']),
                    'city_slug'            => $citySlug,
                    'state_slug'           => $stateSlug,
                    'trade'                => $validated['trade'],
                    'service_radius_miles' => $validated['service_radius_miles'],
                    'is_publicly_listed'   => $isPubliclyListed,
                    'updated_at'           => now(),
                ]);

                session(['onboarding_active_step' => 2]);
                return redirect()->route('onboarding.view')->with('status', 'Core dispatch vitals committed.');
            }

            if ($currentStep === 2) {
                $validated = $request->validate([
                    'years_experience'  => 'required|integer|min:0',
                    'license_number'    => 'nullable|string|max:100',
                    'is_insured'        => 'required|boolean',
                    'company_bio_short' => 'required|string|max:255',
                    'company_bio_long'  => 'required|string|max:2000',
                    'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                ]);

                $updatePayload = [
                    'years_experience'  => $validated['years_experience'],
                    'license_number'    => $validated['license_number'],
                    'is_insured'        => $validated['is_insured'],
                    'company_bio_short' => $validated['company_bio_short'],
                    'company_bio_long'  => $validated['company_bio_long'],
                    'updated_at'        => now(),
                ];

                if ($request->hasFile('logo')) {
                    $file = $request->file('logo');
                    $name = 'logo_' . $user->company_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    if (!file_exists(public_path('uploads/logos'))) {
                        mkdir(public_path('uploads/logos'), 0755, true);
                    }
                    $file->move(public_path('uploads/logos'), $name);
                    $updatePayload['logo_path'] = 'uploads/logos/' . $name;
                }

                DB::table($companyTable)->where('id', $user->company_id)->update($updatePayload);

                session(['onboarding_active_step' => 3]);
                return redirect()->route('onboarding.view')->with('status', 'Compliance profiles structural layer compiled.');
            }

            if ($currentStep === 3) {
                $validated = $request->validate([
                    'service_tags'           => 'required|array|min:1',
                    'emergency_availability' => 'required|boolean',
                    'portfolio_img'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                ]);

                $updatePayload = [
                    'service_tags'           => json_encode($validated['service_tags']),
                    'emergency_availability' => $validated['emergency_availability'],
                    'updated_at'             => now(),
                ];

                if ($request->hasFile('portfolio_img')) {
                    $file = $request->file('portfolio_img');
                    $name = 'showcase_' . $user->company_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    if (!file_exists(public_path('uploads/portfolio'))) {
                        mkdir(public_path('uploads/portfolio'), 0755, true);
                    }
                    $file->move(public_path('uploads/portfolio'), $name);
                    $updatePayload['portfolio_image_path'] = 'uploads/portfolio/' . $name;
                }

                DB::table($companyTable)->where('id', $user->company_id)->update($updatePayload);

                session(['onboarding_active_step' => 4]);
                return redirect()->route('onboarding.view')->with('status', 'Functional capability checkboxes logged.');
            }

            if ($currentStep === 4) {
                $validated = $request->validate([
                    'google_review_link' => 'nullable|url|max:255',
                    'facebook_link'      => 'nullable|url|max:255',
                    'yelp_link'          => 'nullable|url|max:255',
                ]);

                DB::table($companyTable)->where('id', $user->company_id)->update([
                    'social_links' => json_encode([
                        'google'   => $validated['google_review_link'],
                        'facebook' => $validated['facebook_link'],
                        'yelp'     => $validated['yelp_link'],
                    ]),
                    'updated_at'   => now(),
                ]);

                session(['onboarding_active_step' => 5]);
                return redirect()->route('onboarding.view')->with('status', 'External trust signal nodes mapped.');
            }

            if ($currentStep === 5) {
                $validated = $request->validate([
                    'crew_structure'          => 'required|string|max:50',
                    'invoice_preferences'     => 'required|string|max:50',
                    'default_tax_rate'        => 'required|numeric|min:0',
                    'starting_invoice_number' => 'required|integer|min:1',
                    'deposit_rules'           => 'nullable|string|max:1000',
                ]);

                DB::beginTransaction();

                DB::table($companyTable)->where('id', $user->company_id)->update([
                    'crew_structure'          => $validated['crew_structure'],
                    'invoice_preferences'     => $validated['invoice_preferences'],
                    'default_tax_rate'        => $validated['default_tax_rate'],
                    'starting_invoice_number' => $validated['starting_invoice_number'],
                    'deposit_rules'           => $validated['deposit_rules'],
                    'onboarding_completed_at' => now(),
                    'updated_at'              => now(),
                ]);

                $user->onboarding_completed_at = now();
                $user->save();

                DB::commit();

                session()->forget('onboarding_active_step');
                Log::info("Onboarding funnel verified complete. Workspace ID: {$user->company_id} launched into active state layout.");

                return redirect()->route('dashboard')->with('status', '⚡ Workspace fully calibrated and deployed live!');
            }

        } catch (\Exception $e) {
            if ($currentStep === 5) {
                DB::rollBack();
            }
            Log::error("Onboarding pipeline checkpoint engine fault at Step {$currentStep}: " . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'An operational parameters fault occurred. Verify inputs and retry.']);
        }

        return redirect()->route('onboarding.view');
    }

    /**
     * Safe Plugin-Driven Database Self-Healing Structural Schema Guard for Companies.
     */
    private function ensureSchemaIsHealed(string $tableName): void
    {
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->nullable();
                $table->string('city', 255)->nullable();
                $table->string('state', 10)->nullable();
                $table->string('city_slug', 255)->nullable()->index();
                $table->string('state_slug', 10)->nullable()->index();
                $table->string('trade', 255)->nullable();
                $table->integer('service_radius_miles')->default(25);
                $table->boolean('is_publicly_listed')->default(0)->index();
                $table->integer('years_experience')->default(0);
                $table->string('license_number', 100)->nullable();
                $table->boolean('is_insured')->default(1);
                $table->string('company_bio_short', 255)->nullable();
                $table->text('company_bio_long')->nullable();
                $table->string('logo_path', 255)->nullable();
                $table->text('service_tags')->nullable();
                $table->boolean('emergency_availability')->default(0);
                $table->string('portfolio_image_path', 255)->nullable();
                $table->text('social_links')->nullable();
                $table->string('crew_structure', 50)->default('solo');
                $table->string('invoice_preferences', 50)->default('digital_only');
                $table->decimal('default_tax_rate', 5, 2)->default(0.00);
                $table->integer('starting_invoice_number')->default(1000);
                $table->text('deposit_rules')->nullable();
                $table->timestamp('onboarding_completed_at')->nullable()->index();
                $table->timestamps();
            });
        } else {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'name')) { $table->string('name', 255)->nullable(); }
                if (!Schema::hasColumn($tableName, 'city')) { $table->string('city', 255)->nullable(); }
                if (!Schema::hasColumn($tableName, 'state')) { $table->string('state', 10)->nullable(); }
                if (!Schema::hasColumn($tableName, 'city_slug')) { $table->string('city_slug', 255)->nullable()->index(); }
                if (!Schema::hasColumn($tableName, 'state_slug')) { $table->string('state_slug', 10)->nullable()->index(); }
                if (!Schema::hasColumn($tableName, 'trade')) { $table->string('trade', 255)->nullable(); }
                if (!Schema::hasColumn($tableName, 'service_radius_miles')) { $table->integer('service_radius_miles')->default(25); }
                if (!Schema::hasColumn($tableName, 'is_publicly_listed')) { $table->boolean('is_publicly_listed')->default(0)->index(); }
                if (!Schema::hasColumn($tableName, 'years_experience')) { $table->integer('years_experience')->default(0); }
                if (!Schema::hasColumn($tableName, 'license_number')) { $table->string('license_number', 100)->nullable(); }
                if (!Schema::hasColumn($tableName, 'is_insured')) { $table->boolean('is_insured')->default(1); }
                if (!Schema::hasColumn($tableName, 'company_bio_short')) { $table->string('company_bio_short', 255)->nullable(); }
                if (!Schema::hasColumn($tableName, 'company_bio_long')) { $table->text('company_bio_long')->nullable(); }
                if (!Schema::hasColumn($tableName, 'logo_path')) { $table->string('logo_path', 255)->nullable(); }
                if (!Schema::hasColumn($tableName, 'service_tags')) { $table->text('service_tags')->nullable(); }
                if (!Schema::hasColumn($tableName, 'emergency_availability')) { $table->boolean('emergency_availability')->default(0); }
                if (!Schema::hasColumn($tableName, 'portfolio_image_path')) { $table->string('portfolio_image_path', 255)->nullable(); }
                if (!Schema::hasColumn($tableName, 'social_links')) { $table->text('social_links')->nullable(); }
                if (!Schema::hasColumn($tableName, 'crew_structure')) { $table->string('crew_structure', 50)->default('solo'); }
                if (!Schema::hasColumn($tableName, 'invoice_preferences')) { $table->string('invoice_preferences', 50)->default('digital_only'); }
                if (!Schema::hasColumn($tableName, 'default_tax_rate')) { $table->decimal('default_tax_rate', 5, 2)->default(0.00); }
                if (!Schema::hasColumn($tableName, 'starting_invoice_number')) { $table->integer('starting_invoice_number')->default(1000); }
                if (!Schema::hasColumn($tableName, 'deposit_rules')) { $table->text('deposit_rules')->nullable(); }
                if (!Schema::hasColumn($tableName, 'onboarding_completed_at')) { $table->timestamp('onboarding_completed_at')->nullable()->index(); }
            });
        }
    }
}
