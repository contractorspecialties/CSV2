<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    public function showBuilder(Request $request)
    {
        $this->ensureLeadSchemaIsHealed();

        return response("
            <!DOCTYPE html>
            <html lang=\"en\" class=\"h-full bg-slate-950 text-slate-200\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Free Professional Estimate Generator | ContractorSpecialties</title>
                <script src=\"https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4\"></script>
                <script defer src=\"https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js\"></script>
                <style>
                    [x-cloak] { display: none !important; }
                    /* Custom scrollbar matching core brand styles */
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
                            <span class=\"text-[10px] font-black text-slate-500 uppercase tracking-widest bg-slate-950 px-3 py-1.5 border border-slate-800/80 rounded-lg shadow-inner hidden sm:inline-block\">
                                Utility Funnel Node Active
                            </span>
                            <a href=\"/login\" class=\"text-xs font-black text-slate-300 hover:text-white uppercase tracking-wider bg-slate-950 hover:bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl transition-all shadow-sm text-decoration-none\">
                                Sign In &rarr;
                            </a>
                        </div>
                    </div>
                </header>

                <main class=\"flex-grow max-w-7xl w-full mx-auto p-4 lg:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start\">

                    <!-- LEFT COLUMN: DYNAMIC CONTROL PANEL -->
                    <div class=\"lg:col-span-5 bg-slate-900 border border-slate-800/80 rounded-2xl p-5 md:p-6 space-y-6 shadow-xl max-h-[calc(100vh-140px)] lg:overflow-y-auto\">
                        <div>
                            <span class=\"text-[10px] font-black uppercase text-[#f58613] tracking-widest\">Instant Growth Engine</span>
                            <h1 class=\"text-xl font-black text-white tracking-tight uppercase mt-0.5\">Free Proposal Generator</h1>
                            <p class=\"text-xs text-slate-400 font-medium mt-1 leading-relaxed\">Build and email an itemized proposal to a client in 45 seconds. No credit card, no entry blocks.</p>
                        </div>

                        " . (session()->has('status') ? "
                            <div class=\"p-4 bg-emerald-950/40 border border-emerald-900/60 text-emerald-400 rounded-xl text-xs font-bold shadow-inner flex items-start gap-2.5\">
                                <span>🎉</span> <div>" . session('status') . "</div>
                            </div>
                        " : "") . "

                        " . (session()->has('errors') ? "
                            <div class=\"p-4 bg-red-950/40 border border-red-900/60 text-red-400 rounded-xl text-xs font-bold shadow-inner\">
                                🛑 " . session('errors')->first() . "
                            </div>
                        " : "") . "

                        <form action=\"/free-estimate-generator/submit\" method=\"POST\" class=\"space-y-5 m-0\">
                            <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">

                            <!-- Section A: Identity Captures -->
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

                            <!-- Section B: Homeowner Destination -->
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
                                        <label class=\"block text-[9px] font-black uppercase text-slate-500 tracking-wider mb-1\">Client Email (Secure Route)</label>
                                        <input type=\"email\" name=\"client_email\" required x-model=\"client_email\" placeholder=\"martha@gmail.com\" class=\"w-full bg-slate-500/5 border border-slate-800 focus:border-[#f58613] rounded-xl py-2.5 px-3 text-xs font-semibold text-white shadow-inner focus:outline-none\">
                                    </div>
                                </div>
                            </div>

                            <!-- Section C: Matrix Parameters -->
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
                                            <!-- Structural Serial Data Bindings -->
                                            <input type=\"hidden\" :name=\"'items[' + index + '][is_taxable]'\" value=\"1\">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Section D: Additional Parameters -->
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

                    <!-- RIGHT COLUMN: STANDALONE DIGITAL PORTAL PREVIEW -->
                    <div class=\"lg:col-span-7 bg-slate-900 border border-slate-800/80 rounded-2xl p-1 shadow-2xl flex flex-col max-h-[calc(100vh-140px)] min-h-[500px] overflow-hidden sticky top-24\">

                        <!-- Mini Preview Banner Header -->
                        <div class=\"bg-slate-950 px-4 py-2.5 flex items-center justify-between text-slate-400 text-[10px] font-black uppercase tracking-wider border-b border-slate-800/80\">
                            <div class=\"flex items-center gap-2\">
                                <span class=\"w-2 h-2 rounded-full bg-[#f58613] animate-pulse\"></span>
                                Live Interactive Client Interface Simulation
                            </div>
                            <span class=\"font-mono text-slate-600\">Format: Mobile Portal View</span>
                        </div>

                        <!-- Inner Document View Screen Simulation -->
                        <div class=\"flex-grow bg-slate-950 p-4 overflow-y-auto flex items-start justify-center\">
                            <div class=\"w-full max-w-md bg-white text-slate-900 rounded-2xl shadow-xl overflow-hidden border border-slate-200\">

                                <!-- Document Header -->
                                <div class=\"bg-slate-900 p-5 text-white flex justify-between items-start\">
                                    <div class=\"space-y-1\">
                                        <div class=\"text-xs font-black uppercase text-[#f58613] tracking-widest font-mono\">Project Proposal</div>
                                        <h2 class=\"text-lg font-black tracking-tight uppercase leading-tight\" x-text=\"companyName || 'Your Business Name'\"></h2>
                                    </div>
                                    <div class=\"text-right font-mono text-[10px] font-bold text-slate-400\">
                                        REF: <span class=\"text-white font-black\">EST-1001</span>
                                    </div>
                                </div>

                                <!-- Client Meta Block -->
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

                                <!-- Line Items Display -->
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

                                <!-- Financial Accumulator Summary -->
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

                                <!-- Mock Portal Sign-off Actions -->
                                <div class=\"p-5 bg-white border-t border-slate-100 space-y-3\">
                                    <div class=\"bg-slate-50 border border-slate-200 rounded-xl p-3 text-[11px] font-medium text-slate-500 leading-normal text-center\">
                                        🔒 Authenticated Client Signature Interface Active below.
                                    </div>
                                    <div class=\"grid grid-cols-1 gap-2.5\">
                                        <div class=\"w-full bg-slate-900 text-white font-black text-center py-3.5 rounded-xl uppercase text-[10px] tracking-widest opacity-40 select-none\">
                                            ✍️ Authorize & Sign Proposal
                                        </div>
                                    </div>

                                    <!-- SaaS Platform Attribution Watermark -->
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
                    &copy; " . date('Y') . " ContractorSpecialties Network. Public Sandbox Provisioning Route.
                </footer>

                <script>
                    function publicBuilder() {
                        return {
                            items: [{ id: Date.now(), description: '', quantity: 1, unit_price: 0.00 }],
                            companyName: '',
                            contractorEmail: '',
                            clientName: '',
                            client_email: '',
                            taxRate: 0,
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
     * Intercept the incoming client metrics and dump them straight into the isolated payload database block.
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

            // Construct transactional vector variables for email routing
            $payloadObj = (object)$validated;

            // Generate clean calculation summaries on the backend for the HTML wrapper
            $calculatedSubtotal = 0;
            foreach ($validated['items'] as $it) {
                $calculatedSubtotal += ($it['quantity'] * $it['unit_price']);
            }
            $calculatedTax = $calculatedSubtotal * ($validated['tax_rate'] / 100);
            $calculatedGrandTotal = $calculatedSubtotal + $calculatedTax;

            // Send notification to the homeowner via standard email framework
            Mail::send([], [], function ($message) use ($payloadObj, $calculatedGrandTotal) {
                $message->to($payloadObj->client_email)
                    ->subject("New Project Proposal from {$payloadObj->company_name}")
                    ->html("
                        <div style=\"font-family: Arial, sans-serif; padding: 32px; max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;\">
                            <h2 style=\"color: #0f172a; font-size: 20px; margin-bottom: 4px; text-transform: uppercase;\">Project Proposal Dispatched</h2>
                            <p style=\"font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 24px;\">Powered via ContractorSpecialties Public Network Nodes</p>

                            <p style=\"font-size: 15px; color: #334155;\">Hello {$payloadObj->client_name},</p>
                            <p style=\"font-size: 15px; color: #334155; line-height: 1.6;\">An itemized service value breakdown package totaling <strong>$" . number_format($calculatedGrandTotal, 2) . "</strong> has been compiled and issued to your coordinates by the project management desk at <strong>{$payloadObj->company_name}</strong>.</p>

                            <hr style=\"border: 0; border-top: 1px solid #f1f5f9; margin: 24px 0;\">
                            <p style=\"font-size: 13px; color: #64748b;\">The contractor has been issued an individual magic configuration handshake token link to monitor this file live. For project updates, reach out to them directly at {$payloadObj->contractor_email}.</p>
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
