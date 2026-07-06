<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Estimate;
use App\Models\EstimateItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PublicEstimateController extends Controller
{
    /**
     * Render the high-conversion split-pane interactive estimate builder canvas.
     */
    public function showBuilder(Request $request, $state = null, $city = null, $trade = null)
    {
        $this->ensureLeadSchemaIsHealed();

        $seoState = $state ? strtoupper($state) : 'NC';
        $seoCity = $city ? ucwords(str_replace('-', ' ', $city)) : 'Local';
        $seoTradeSlug = $trade ? strtolower($trade) : 'contractor';

        $tradeMap = [
            'roofing' => [
                'title' => 'Roofing Architecture',
                'company' => "{$seoCity} Roofing Specialists",
                'desc' => 'Tear-off, underlayment installation, premium architectural shingles, and flashing integration.',
                'rate' => 8450.00
            ],
            'landscaping' => [
                'title' => 'Landscape Development',
                'company' => "{$seoCity} Landscape Design",
                'desc' => 'Complete backyard hardscape grading, retaining wall block laying, and sod delivery.',
                'rate' => 4200.00
            ],
            'lawn-care' => [
                'title' => 'Lawn Care Operations',
                'company' => "{$seoCity} Turf & Lawn Operations",
                'desc' => 'Seasonal aeration, overseeding, premium fertilizer application, and perimeter weed control.',
                'rate' => 245.00
            ],
            'hvac' => [
                'title' => 'HVAC Climatic Systems',
                'company' => "{$seoCity} Climate Systems",
                'desc' => 'High-efficiency heat pump installation, digital thermostat calibration, and duct pass verification.',
                'rate' => 6800.00
            ],
            'plumbing' => [
                'title' => 'Plumbing Frameworks',
                'company' => "{$seoCity} Professional Plumbing",
                'desc' => 'Tankless water heater conversion, copper manifold tie-in, and structural drain line drop runs.',
                'rate' => 3100.00
            ],
            'electrical' => [
                'title' => 'Electrical Infrastructure',
                'company' => "{$seoCity} Power & Electrical",
                'desc' => '200-Amp service panel upgrade, dedicated equipment circuit runs, and code compliance mapping.',
                'rate' => 2850.00
            ],
            'general-contracting' => [
                'title' => 'General Contracting Management',
                'company' => "{$seoCity} Builders & Contracting",
                'desc' => 'Tenant build-out framing, structural header setting, and multi-trade project oversight.',
                'rate' => 12500.00
            ]
        ];

        $activeTrade = $tradeMap[$seoTradeSlug] ?? [
            'title' => 'Home Service Specialty',
            'company' => $city ? "{$seoCity} Specialty Contractors" : 'My Trade Company',
            'desc' => 'Professional field service delivery and materials mobilization package.',
            'rate' => 1250.00
        ];

        $pageTitle = $city
            ? "Free Pro {$activeTrade['title']} Estimate Template | {$seoCity}, {$seoState}"
            : "Free Professional Estimate Generator & Invoice Builder | ContractorSpecialties";

        $metaDesc = $city
            ? "Build and send itemized professional project proposals instantly in {$seoCity}, {$seoState}. Customized for local {$activeTrade['title']} businesses. No registration wall required."
            : "Frictionless B2B invoice and estimate creator canvas tool built specifically for professional trade groups and field dispatch teams.";

        return response("
            <!DOCTYPE html>
            <html lang=\"en\" class=\"h-full bg-slate-950 text-slate-200\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>{$pageTitle}</title>
                <meta name=\"description\" content=\"{$metaDesc}\">
                <script src=\"https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4\"></script>
                <script defer src=\"https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js\"></script>

                <script type=\"application/ld+json\">
                {
                  \"@context\": \"https://schema.org\",
                  \"@type\": \"FAQPage\",
                  \"mainEntity\": [{
                    \"@type\": \"Question\",
                    \"name\": \"How do I text an estimate to a client in {$seoCity}?\",
                    \"acceptedAnswer\": {
                      \"@type\": \"Answer\",
                      \"text\": \"You can use this free canvas tool to compile and email your proposal. To unlock automated text message (SMS) delivery vectors from our compliant carrier network in {$seoState}, simply register and verify your profile shortcut.\"
                    }
                  },{
                    \"@type\": \"Question\",
                    \"name\": \"Can I save my itemized pricebook items?\",
                    \"acceptedAnswer\": {
                      \"@type\": \"Answer\",
                      \"text\": \"The public sandbox environment features zero data retention to keep operations anonymous. Upgrading to a premium secure account locks your local pricebook matrices, client rosters, and active scheduling boards permanently.\"
                    }
                  }]
                }
                </script>

                <style>
                    [x-cloak] { display: none !important; }
                    ::-webkit-scrollbar { width: 6px; height: 6px; }
                    ::-webkit-scrollbar-track { background: #020617; }
                    ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
                    ::-webkit-scrollbar-thumb:hover { background: #334155; }
                </style>
            </head>
            <body class=\"min-h-full font-sans antialiased bg-slate-950 text-slate-200 selection:bg-[#f58613] selection:text-white flex flex-col justify-between\" x-data=\"publicBuilder()\">

                <header class=\"bg-slate-900/80 border-b border-slate-800/60 backdrop-blur-md sticky top-0 z-50 shadow-sm shrink-0\">
                    <div class=\"max-w-7xl mx-auto px-4 h-20 flex items-center justify-between\">
                        <div class=\"h-12 flex items-center\">
                            <img src=\"/images/header-logo.webp\" alt=\"ContractorSpecialties Logo\" class=\"h-full w-auto max-h-[44px] object-contain\">
                        </div>
                        <div class=\"flex items-center gap-4\">
                            <span class=\"text-[10px] font-black text-[#f58613] uppercase tracking-widest bg-slate-950 px-3 py-1.5 border border-slate-800/80 rounded-lg shadow-inner hidden sm:inline-block\">
                                pSEO Engine Node: {$seoCity} / {$seoState}
                            </span>
                            <a href=\"/login\" class=\"text-xs font-black text-slate-300 hover:text-white uppercase tracking-wider bg-slate-950 hover:bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl transition-all shadow-sm text-decoration-none\">
                                Sign In &rarr;
                            </a>
                        </div>
                    </div>
                </header>

                <main class=\"flex-grow max-w-7xl mx-auto p-4 lg:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start\">

                    <div class=\"lg:col-span-5 bg-slate-900 border border-slate-800/80 rounded-2xl p-5 md:p-6 space-y-6 shadow-xl max-h-[calc(100vh-140px)] lg:overflow-y-auto\">
                        <div>
                            <span class=\"text-[10px] font-black uppercase text-[#f58613] tracking-widest\">" . ($city ? "{$seoCity} Pro Tool Sandbox" : "Instant Growth Engine") . "</span>
                            <h1 class=\"text-xl font-black text-white tracking-tight uppercase mt-0.5\">" . ($city ? "{$activeTrade['title']} Template" : "Free Proposal Generator") . "</h1>
                            <p class=\"text-xs text-slate-400 font-medium mt-1 leading-relaxed\">Build and email an itemized proposal to a client in 45 seconds. No credit card, no entry blocks.</p>
                        </div>

                        " . (session()->has('status') ? "
                            <div class=\"p-4 bg-emerald-950/40 border border-emerald-900/60 text-emerald-400 rounded-xl text-xs font-bold shadow-inner flex items-start gap-2.5\">
                                <span>🚀</span> <div>" . session('status') . "</div>
                            </div>
                        " : "") . "

                        " . (session()->has('errors') ? "
                            <div class=\"p-4 bg-red-950/40 border border-red-900/60 text-red-400 rounded-xl text-xs font-bold shadow-inner\">
                                🛑 " . session('errors')->first() . "
                            </div>
                        " : "") . "

                        <form action=\"/free-estimate-generator/submit\" method=\"POST\" class=\"space-y-5 m-0\">
                            <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">

                            <div class=\"space-y-3\">
                                <h3 class=\"text-xs font-black uppercase text-slate-400 tracking-wider flex items-center gap-2 border-b border-slate-800/80 pb-2\">
                                    <span>🏢</span> Your Business Details
                                </h3>
                                <div class=\"grid grid-cols-2 gap-3\">
                                    <div>
                                        <label class=\"block text-[9px] font-black uppercase text-slate-500 tracking-wider mb-1\">Company Name</label>
                                        <input type=\"text\" name=\"company_name\" required x-model=\"companyName\" placeholder=\"e.g., Jack's Lawns\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-2.5 px-3 text-xs font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                    <div>
                                        <label class=\"block text-[9px] font-black uppercase text-slate-500 tracking-wider mb-1\">Your Professional Email</label>
                                        <input type=\"email\" name=\"contractor_email\" required x-model=\"contractorEmail\" placeholder=\"jack@lawncare.com\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-2.5 px-3 text-xs font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                </div>
                            </div>

                            <div class=\"space-y-3\">
                                <h3 class=\"text-xs font-black uppercase text-slate-400 tracking-wider flex items-center gap-2 border-b border-slate-800/80 pb-2\">
                                    <span>👤</span> Homeowner Target
                                </h3>
                                <div class=\"grid grid-cols-2 gap-3\">
                                    <div>
                                        <label class=\"block text-[9px] font-black uppercase text-slate-500 tracking-wider mb-1\">Client Full Name</label>
                                        <input type=\"text\" name=\"client_name\" required x-model=\"clientName\" placeholder=\"e.g., Martha Smith\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-2.5 px-3 text-xs font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                    <div>
                                        <label class=\"block text-[9px] font-black uppercase text-slate-500 tracking-wider mb-1\">Client Email</label>
                                        <input type=\"email\" name=\"client_email\" required x-model=\"client_email\" placeholder=\"martha@gmail.com\" class=\"w-full bg-slate-500/5 border border-slate-800 focus:border-[#f58613] rounded-xl py-2.5 px-3 text-xs font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                </div>
                            </div>

                            <div class=\"space-y-3.5\">
                                <div class=\"flex justify-between items-center border-b border-slate-800/80 pb-2\">
                                    <h3 class=\"text-xs font-black uppercase text-slate-400 tracking-wider flex items-center gap-2\">
                                        <span>🔨</span> Scope Line Matrix
                                    </h3>
                                    <button type=\"button\" @click=\"addItem()\" class=\"bg-slate-950 hover:bg-slate-800 border border-slate-800 text-[#f58613] hover:text-orange-400 text-[10px] font-black uppercase py-1.5 px-3 rounded-lg tracking-wider transition-all cursor-pointer outline-none\">
                                        + Add Line
                                    </button>
                                </div>

                                <div class=\"space-y-2 max-h-56 overflow-y-auto pr-1\">
                                    <template x-for=\"(item, index) in items\" :key=\"item.id\">
                                        <div class=\"grid grid-cols-12 gap-2 items-center bg-slate-950 p-3 border border-slate-800/80 rounded-xl relative\">
                                            <div class=\"col-span-6\">
                                                <input type=\"text\" :name=\"'items[' + index + '][description]'\" required x-model=\"item.description\" placeholder=\"Service Description\" class=\"w-full bg-slate-900 border border-slate-800 rounded-lg py-2 px-2.5 text-xs font-medium text-white focus:outline-none focus:border-[#f58613]\">
                                            </div>
                                            <div class=\"col-span-2\">
                                                <input type=\"number\" step=\"any\" :name=\"'items[' + index + '][quantity]'\" required x-model.number=\"item.quantity\" min=\"0.01\" class=\"w-full bg-slate-900 border border-slate-800 rounded-lg py-2 px-1 text-xs font-mono font-black text-center text-white focus:outline-none focus:border-[#f58613]\">
                                            </div>
                                            <div class=\"col-span-3\">
                                                <input type=\"number\" step=\"0.01\" :name=\"'items[' + index + '][unit_price]'\" required x-model.number=\"item.unit_price\" placeholder=\"Rate\" class=\"w-full bg-slate-900 border border-slate-800 rounded-lg py-2 px-1 text-xs font-mono font-black text-right text-white focus:outline-none focus:border-[#f58613]\">
                                            </div>
                                            <div class=\"col-span-1 flex justify-center\">
                                                <button type=\"button\" @click=\"removeItem(index)\" :disabled=\"items.length === 1\" class=\"text-red-500 disabled:opacity-20 bg-transparent font-bold text-xs hover:text-red-400 border-0 outline-none cursor-pointer\">✕</button>
                                            </div>
                                            <input type=\"hidden\" :name=\"'items[' + index + '][is_taxable]'\" value=\"1\">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class=\"grid grid-cols-2 gap-3 border-t border-slate-800/80 pt-3.5\">
                                <div>
                                    <label class=\"block text-[9px] font-black uppercase text-slate-500 tracking-wider mb-1\">Sales Tax Rate (%)</label>
                                    <input type=\"number\" name=\"tax_rate\" step=\"0.01\" min=\"0\" max=\"100\" x-model.number=\"taxRate\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-2.5 px-3 text-xs font-mono font-black text-white focus:outline-none\">
                                </div>
                                <div>
                                    <label class=\"block text-[9px] font-black uppercase text-slate-500 tracking-wider mb-1\">Upfront Deposit Required (%)</label>
                                    <input type=\"number\" name=\"deposit_percentage\" min=\"0\" max=\"100\" x-model.number=\"depositPercentage\" class=\"w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-2.5 px-3 text-xs font-mono font-black text-white focus:outline-none\">
                                </div>
                            </div>

                            <div class=\"pt-3\">
                                <button type=\"submit\" class=\"w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-6 rounded-xl uppercase tracking-widest shadow-lg transition-all active:scale-[0.99] cursor-pointer border-0 outline-none text-center\">
                                    Dispatch Digital Proposal &rarr;
                                </button>
                                <p class=\"text-[10px] text-slate-500 text-center font-bold mt-2.5 leading-normal\">
                                    ⚠️ Secure Sandbox Mode: Delivery routes directly via email vector. SMS workflows and persistent file archives require validated workspace authorization profiles.
                                </p>
                            </div>
                        </form>
                    </div>

                    <div class=\"lg:col-span-7 bg-slate-900 border border-slate-800/80 rounded-2xl p-1 shadow-2xl flex flex-col max-h-[calc(100vh-140px)] min-h-[500px] overflow-hidden sticky top-24\">
                        <div class=\"bg-slate-950 px-4 py-2.5 flex items-center justify-between text-slate-400 text-[10px] font-black uppercase tracking-wider border-b border-slate-800/80\">
                            <div class=\"flex items-center gap-2\">
                                <span class=\"w-2 h-2 rounded-full bg-[#f58613] animate-pulse\"></span>
                                Live Interactive Client Interface Simulation
                            </div>
                            <span class=\"font-mono text-slate-600\">Format: Mobile Portal View</span>
                        </div>

                        <div class=\"flex-grow bg-slate-950 p-4 overflow-y-auto flex items-start justify-center\">
                            <div class=\"w-full max-w-md bg-white text-slate-900 rounded-2xl shadow-xl overflow-hidden border border-slate-200\">
                                <div class=\"bg-slate-900 p-5 text-white flex justify-between items-start\">
                                    <div class=\"space-y-1\">
                                        <div class=\"text-xs font-black uppercase text-[#f58613] tracking-widest font-mono\">Project Proposal</div>
                                        <h2 class=\"text-lg font-black tracking-tight uppercase leading-tight\" x-text=\"companyName || '" . addslashes($activeTrade['company']) . "'\"></h2>
                                    </div>
                                    <div class=\"text-right font-mono text-[10px] font-bold text-slate-400\">
                                        REF: <span class=\"text-white font-black\">EST-1001</span>
                                    </div>
                                </div>

                                <div class=\"p-5 border-b border-slate-100 bg-slate-50/50 grid grid-cols-2 gap-4 text-xs font-medium\">
                                    <div>
                                        <span class=\"block text-[9px] font-black uppercase text-slate-400 tracking-wider mb-0.5\">Prepared For:</span>
                                        <strong class=\"text-slate-900 font-black text-sm uppercase block tracking-tight\" x-text=\"clientName || 'Martha Smith'\"></strong>
                                        <span class=\"text-slate-500 truncate block mt-0.5\" x-text=\"client_email || 'martha@gmail.com'\"></span>
                                    </div>
                                    <div class=\"text-right\">
                                        <span class=\"block text-[9px] font-black uppercase text-slate-400 tracking-wider mb-0.5\">Issued Timeline:</span>
                                        <span class=\"text-slate-900 font-mono font-black block\">" . now()->format('M j, Y') . "</span>
                                        <span class=\"text-slate-400 font-bold block mt-0.5\">Terms: Net 0 upon dispatch</span>
                                    </div>
                                </div>

                                <div class=\"p-5 space-y-3\">
                                    <span class=\"block text-[9px] font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-1.5\">Itemized Scope Outlines</span>
                                    <div class=\"space-y-2\">
                                        <template x-for=\"item in items\" :key=\"item.id\">
                                            <div class=\"flex justify-between items-baseline text-xs border-b border-slate-50 pb-2\">
                                                <div class=\"pr-4\">
                                                    <span class=\"font-black text-slate-900 uppercase block tracking-tight\" x-text=\"item.description || 'Custom Field Execution Line'\"></span>
                                                    <span class=\"text-slate-400 text-[10px] font-bold font-mono\" x-text=\"'Qty: ' + (item.quantity || 1) + ' @ $' + (parseFloat(item.unit_price) || 0).toFixed(2)\"></span>
                                                </div>
                                                <span class=\"font-mono font-black text-slate-950 shrink-0 text-right\" x-text=\"'$' + ((parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0)).toFixed(2)\"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class=\"bg-slate-50 p-5 border-t border-slate-100 font-mono text-xs text-slate-500 space-y-2\">
                                    <div class=\"flex justify-between font-bold\">
                                        <span>Subtotal Base Cost:</span>
                                        <span class=\"text-slate-900 font-black\" x-text=\"'$' + subtotal.toFixed(2)\"></span>
                                    </div>
                                    <div class=\"flex justify-between font-bold\" x-show=\"taxRate > 0\">
                                        <span x-text=\"'Sales Surcharges (' + taxRate + '%):'\"></span>
                                        <span class=\"text-slate-900 font-black\" x-text=\"'+$' + taxTotal.toFixed(2)\"></span>
                                    </div>
                                    <div class=\"flex justify-between font-bold text-orange-600\" x-show=\"depositPercentage > 0\">
                                        <span x-text=\"'Mobilization Deposit (' + depositPercentage + '%):'\"></span>
                                        <span class=\"font-black\" x-text=\"'$' + depositTotal.toFixed(2)\"></span>
                                    </div>
                                    <div class=\"flex justify-between font-black text-sm text-slate-950 pt-2 border-t border-slate-200/80\">
                                        <span class=\"uppercase tracking-wider font-sans text-xs\">Guaranteed Bid:</span>
                                        <span class=\"text-emerald-600 text-lg\" x-text=\"'$' + grandTotal.toFixed(2)\"></span>
                                    </div>
                                </div>

                                <div class=\"p-5 bg-white border-t border-slate-100 space-y-3\">
                                    <div class=\"bg-slate-50 border border-slate-200 rounded-xl p-3 text-[11px] font-medium text-slate-500 leading-normal text-center\">
                                        🔒 Authenticated Client Signature Interface Active below.
                                    </div>
                                    <div class=\"grid grid-cols-1 gap-2.5\">
                                        <div class=\"w-full bg-slate-900 text-white font-black text-center py-3.5 rounded-xl uppercase text-[10px] tracking-widest opacity-40 select-none\">
                                            ✍️ Authorize & Sign Proposal
                                        </div>
                                    </div>
                                    <div class=\"text-center pt-2 border-t border-slate-100\">
                                        <span class=\"text-[9px] font-black uppercase text-slate-400 tracking-wider\">
                                            Powered by <span class=\"text-[#f58613]\">ContractorSpecialties</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class=\"bg-slate-900 border-t border-slate-800/80 py-5 px-4 text-center shrink-0 text-xs font-bold text-slate-500 font-mono tracking-wide\">
                    &copy; " . date('Y') . " ContractorSpecialties Network. Programmatic SEO Landing Layer [{$seoState}].
                </footer>

                <script>
                    function publicBuilder() {
                        return {
                            items: [{
                                id: Date.now(),
                                description: '" . addslashes($activeTrade['desc']) . "',
                                quantity: 1,
                                unit_price: " . $activeTrade['rate'] . "
                            }],
                            companyName: '" . addslashes($activeTrade['company']) . "',
                            contractorEmail: '',
                            clientName: '',
                            client_email: '',
                            taxRate: 7.25,
                            depositPercentage: 0,

                            addItem() {
                                this.items.push({ id: Date.now() + Math.random(), description: '', quantity: 1, unit_price: 0.00 });
                            },
                            removeItem(index) {
                                if (this.items.length > 1) this.items.splice(index, 1);
                            },
                            get subtotal() {
                                return this.items.reduce((sum, item) => sum + ((parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0)), 0);
                            },
                            get taxTotal() {
                                return this.subtotal * ((parseFloat(this.taxRate) || 0) / 100);
                            },
                            get grandTotal() {
                                return this.subtotal + this.taxTotal;
                            },
                            get depositTotal() {
                                return this.grandTotal * ((parseFloat(this.depositPercentage) || 0) / 100);
                            }
                        };
                    }
                </script>
            </body>
            </html>
        ");
    }

    /**
     * Intercept incoming client metrics and record them inside the isolated public leads vault.
     */
    public function storeLeadPayload(Request $request)
    {
        $this->ensureLeadSchemaIsHealed();

        $validated = $request->validate([
            'company_name'       => 'required|string|max:255',
            'contractor_email'   => 'required|email|max:255',
            'client_name'        => 'required|string|max:255',
            'client_email'       => 'required|email|max:255',
            'tax_rate'           => 'required|numeric|min:0|max:100',
            'deposit_percentage' => 'required|numeric|min:0|max:100',
            'items'              => 'required|array|min:1',
            'items.*.description'=> 'required|string|max:255',
            'items.*.quantity'   => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0.00',
        ]);

        $secureToken = Str::uuid()->toString();

        try {
            DB::table($this->getLeadTableName())->insert([
                'token'            => $secureToken,
                'contractor_email' => strtolower($validated['contractor_email']),
                'lead_payload'     => json_encode($validated),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            $payloadObj = (object)$validated;

            $calculatedSubtotal = 0;
            foreach ($validated['items'] as $it) {
                $calculatedSubtotal += ($it['quantity'] * $it['unit_price']);
            }
            $calculatedTax = $calculatedSubtotal * ($validated['tax_rate'] / 100);
            $calculatedGrandTotal = $calculatedSubtotal + $calculatedTax;

            // Route 1: Dispatch standard proposal package layout to homeowner
            Mail::send([], [], function ($message) use ($payloadObj, $calculatedGrandTotal) {
                $message->to($payloadObj->client_email)
                    ->subject("New Project Proposal from {$payloadObj->company_name}")
                    ->html("
                        <div style=\"font-family: Arial, sans-serif; padding: 32px; max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;\">
                            <h2 style=\"color: #0f172a; font-size: 20px; margin-bottom: 4px; text-transform: uppercase;\">Project Proposal Dispatched</h2>
                            <p style=\"font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 24px;\">Powered via ContractorSpecialties Public Network Nodes</p>
                            <p style=\"font-size: 15px; color: #334155;\">Hello {$payloadObj->client_name},</p>
                            <p style=\"font-size: 15px; color: #334155; line-height: 1.6;\">An itemized service value breakdown package totaling <strong>$" . number_format($calculatedGrandTotal, 2) . "</strong> has been compiled and issued to your coordinates by the project management desk at <strong>{$payloadObj->company_name}</strong>.</p>
                        </div>
                    ");
            });

            // Route 2: Dispatch the zero-friction conversion link token loop to the contractor
            Mail::send([], [], function ($message) use ($payloadObj, $secureToken, $calculatedGrandTotal) {
                $claimUrl = url("/free-estimate-generator/claim/{$secureToken}");
                $message->to($payloadObj->contractor_email)
                    ->subject("🎁 Claim Your Estimate Workspace & Live Lead ($" . number_format($calculatedGrandTotal, 2) . ")")
                    ->html("
                        <div style=\"font-family: Arial, sans-serif; padding: 32px; max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;\">
                            <h2 style=\"color: #f58613; font-size: 22px; margin-bottom: 4px; text-transform: uppercase;\">Estimate Ready to Claim</h2>
                            <p style=\"font-size: 15px; color: #334155; line-height: 1.6;\">Your proposal for <strong>{$payloadObj->client_name}</strong> has been successfully built and dispatched.</p>
                            <p style=\"font-size: 15px; color: #334155; line-height: 1.6;\">To lock this estimate permanently onto a live Kanban dashboard, unlock automated SMS client tracking tools, and collect digital sign-offs, claim your free profile below.</p>
                            <div style=\"margin: 32px 0; text-align: center;\">
                                <a href=\"{$claimUrl}\" style=\"background-color: #0f172a; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;\">Claim Proposal & Open Dashboard &rarr;</a>
                            </div>
                        </div>
                    ");
            });

            return back()->with('status', "🚀 Proposal successfully compiled and dispatched to {$validated['client_email']}! A claim handshake notification has been dropped into your professional inbox at {$validated['contractor_email']}.");

        } catch (\Exception $e) {
            Log::error("🚨 PLG Lead Generation engine checkpoint failure: " . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'An operational routing fault occurred inside the sandbox core. Verify entries and retry.']);
        }
    }

    /**
     * Render the frictionless token claim bridge viewport.
     */
    public function showClaimPage(Request $request, $token)
    {
        $lead = DB::table($this->getLeadTableName())->where('token', $token)->first();

        if (!$lead) {
            abort(404, 'Claim token link has expired or has already been materialized into a full user profile.');
        }

        $payload = json_encode(json_decode($lead->lead_payload));

        return response("
            <!DOCTYPE html>
            <html lang=\"en\" class=\"h-full bg-slate-950 text-slate-200\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Claim Your Estimate Workspace | ContractorSpecialties</title>
                <script src=\"https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4\"></script>
            </head>
            <body class=\"min-h-full font-sans antialiased bg-slate-950 flex flex-col justify-center px-4 py-20 relative\">
                <div class=\"w-full max-w-md mx-auto bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 md:p-8 space-y-6 text-center\" x-data=\"{ loading: false, data: {$payload} }\">

                    <div class=\"space-y-2\">
                        <span class=\"text-4xl block\">🎁</span>
                        <span class=\"text-[10px] font-black uppercase text-[#f58613] tracking-widest block\">Frictionless Handshake Intercept</span>
                        <h1 class=\"text-xl font-black text-white tracking-tight uppercase\">Claim Your Active Workspace</h1>
                        <p class=\"text-xs text-slate-400 font-medium max-w-xs mx-auto leading-relaxed\">Deploy your company structure live and lock your estimate payload straight onto an active production Kanban system track.</p>
                    </div>

                    <div class=\"bg-slate-950 border border-slate-800 p-4 rounded-xl text-left font-mono text-xs space-y-2 text-slate-400 shadow-inner\">
                        <div class=\"flex justify-between border-b border-slate-900 pb-1.5\"><span class=\"font-bold\">Inherited Entity:</span> <span class=\"text-white font-black uppercase\" x-text=\"data.company_name\"></span></div>
                        <div class=\"flex justify-between border-b border-slate-900 pb-1.5\"><span class=\"font-bold\">Anchor Routing:</span> <span class=\"text-slate-300 truncate max-w-[200px]\" x-text=\"data.contractor_email\"></span></div>
                        <div class=\"flex justify-between\"><span class=\"font-bold\">Staged Document:</span> <span class=\"text-[#f58613] font-black\">EST-1001 (Active Sent)</span></div>
                    </div>

                    <form action=\"" . url("/free-estimate-generator/claim/{$token}") . "\" method=\"POST\" class=\"m-0\" @submit=\"loading = true\">
                        <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">

                        <button type=\"submit\" :disabled=\"loading\" class=\"w-full bg-[#f58613] hover:bg-orange-600 disabled:bg-slate-800 text-white font-black text-xs py-4 px-6 rounded-xl uppercase tracking-widest shadow-xl transition-all active:scale-[0.99] cursor-pointer border-0 outline-none flex items-center justify-center gap-2\">
                            <span x-show=\"!loading\">Materialize Account Instantly ⚡</span>
                            <span x-show=\"loading\" class=\"animate-pulse\">Compiling Core Disk Clusters...</span>
                        </button>
                    </form>

                    <div class=\"text-[10px] text-slate-600 font-bold leading-normal\">
                        By clicking, your profile token generates an active organization. You will skip standard password setup and land directly on your live, functional command deck layout.
                    </div>
                </div>
            </body>
            </html>
        ");
    }

    /**
     * Unpack the JSON payload data block, commit standard database models, log them in, and destroy the lead row token.
     */
    public function processClaim(Request $request, $token)
    {
        $this->ensureLeadSchemaIsHealed();

        $lead = DB::table($this->getLeadTableName())->where('token', $token)->first();

        if (!$lead) {
            return redirect()->route('welcome')->withErrors(['security' => 'Operational token mismatch or account asset has already loaded.']);
        }

        $payload = json_decode($lead->lead_payload, true);

        // Extract systemic naming prefix elements
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';

        // Secure unique account constraints defensively ahead of transaction pass
        $existingUser = User::where('email', strtolower($payload['contractor_email']))->first();
        if ($existingUser) {
            return redirect()->route('welcome')->withErrors(['security' => 'This contractor email coordinate already has an active workspace configuration file loaded on disk memory. Please log in directly.']);
        }

        DB::beginTransaction();

        try {
            // 1. Materialize Company File Node
            $companyId = DB::table($prefix . 'companies')->insertGetId([
                'name'                     => $payload['company_name'],
                'trade'                    => 'Roofing Architecture', // Fallback baseline initialization state
                'service_radius_miles'     => 25,
                'crew_structure'           => 'solo',
                'invoice_preferences'      => 'digital_only',
                'default_tax_rate'         => $payload['tax_rate'],
                'starting_invoice_number'  => 1000,
                'created_at'               => now(),
                'updated_at'               => now(),
            ]);

            // 2. Materialize Core User File Node
            $user = new User();
            $user->company_id = $companyId;
            $user->first_name = 'Contractor';
            $user->last_name = 'Manager';
            $user->email = strtolower($payload['contractor_email']);
            $user->password = bcrypt(Str::random(32)); // Passwordless token entry architecture standard
            $user->is_admin = false;
            $user->onboarding_completed_at = null; // Forces them to view onboarding controller if they want to tighten properties
            $user->save();

            // 3. Materialize Client CRM Node
            $client = new Client();
            $client->company_id = $companyId;
            $client->client_name = $payload['client_name'];
            $client->email = strtolower($payload['client_email']);
            $client->save();

            // Calculate precise aggregate math matrices
            $calculatedSubtotal = 0;
            foreach ($payload['items'] as $item) {
                $calculatedSubtotal += ($item['quantity'] * $item['unit_price']);
            }
            $calculatedTax = $calculatedSubtotal * ($payload['tax_rate'] / 100);
            $calculatedGrandTotal = $calculatedSubtotal + $calculatedTax;

            // 4. Materialize Estimate Node
            $estimate = new Estimate();
            $estimate->company_id = $companyId;
            $estimate->customer_id = $client->id;
            $estimate->estimate_number = 'EST-1001';
            $estimate->status = 'sent'; // Sets up within active 'Dispatched' pipeline lane
            $estimate->tax_rate = $payload['tax_rate'];
            $estimate->deposit_amount = $calculatedGrandTotal * ($payload['deposit_percentage'] / 100);
            $estimate->subtotal = $calculatedSubtotal;
            $estimate->grand_total = $calculatedGrandTotal;
            $estimate->expires_at = now()->addDays(30);
            $estimate->save();

            // 5. Materialize Estimate Row Matrix Elements
            foreach ($payload['items'] as $itemData) {
                $itemTotal = $itemData['quantity'] * $itemData['unit_price'];

                $row = new EstimateItem();
                $row->estimate_id = $estimate->id;
                $row->description = $itemData['description'];
                $row->quantity    = $itemData['quantity'];
                $row->unit_price  = $itemData['unit_price'];
                $row->total_price = $itemTotal;
                $row->is_taxable  = 1;
                $row->save();
            }

            // Scrub the public lead staging row to prevent signature replays
            DB::table($this->getLeadTableName())->where('token', $token)->delete();

            DB::commit();

            // Login token bypass pass to launch them into dashboard framework with zero entry friction
            Auth::loginUsingId($user->id);

            Log::info("🚀 PLG Growth Hook Converted. Company ID {$companyId} materialized natively from Token {$token}.");

            return redirect()->route('dashboard')->with('status', '⚡ Welcome aboard! Your public sandbox proposal has been materialized into your live corporate workspace deck.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("🚨 Critical failure during PLG row materialization handshake transaction: " . $e->getMessage());
            return redirect()->route('public.estimate.builder')->withErrors(['error' => 'An entry fault occurred while compiling your disk partitions. Please contact tech ops.']);
        }
    }

    /**
     * Resolve prefix configurations dynamically matching standard user data abstractions.
     */
    private function getLeadTableName(): string
    {
        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        return $prefix . 'public_leads';
    }

    /**
     * Safe Plugin-Driven Database Self-Healing Schema Provisioner.
     */
    private function ensureLeadSchemaIsHealed(): void
    {
        $tableName = $this->getLeadTableName();

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('token', 100)->unique()->index();
                $table->string('contractor_email', 255)->index();
                $table->json('lead_payload');
                $table->timestamps();
            });
        }
    }
}
