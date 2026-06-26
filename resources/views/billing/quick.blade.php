<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Field Bill | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white"
      x-data="quickBillTerminal()">

    <!-- HIGH-VISIBILITY HEADER -->
    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-3xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[400px] max-w-[55%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>
            <a href="/dashboard" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl transition-all shadow-inner text-decoration-none">
                &larr; Dashboard
            </a>
        </div>
    </header>

    <div class="py-8 flex-grow">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">

            <div class="border-b border-slate-200 pb-4 mb-6">
                <h1 class="text-3xl font-black text-slate-950 uppercase tracking-tight">Quick Field Bill</h1>
                <p class="text-base text-slate-500 font-bold mt-1">Collect immediate on-site payments, generate invoice receipts, or fire text links.</p>
            </div>

            <!-- CORE INLINE TERMINAL FORM -->
            <form @submit.prevent="processFieldCollection()" class="space-y-6">

                <!-- GIANT DIGIT INPUT BLOCK -->
                <div class="bg-slate-900 border-4 border-slate-950 rounded-3xl p-6 shadow-xl relative overflow-hidden text-white">
                    <label class="block text-xs font-mono font-black text-slate-400 uppercase tracking-widest mb-2">Enter Charge Amount ($)</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-4xl md:text-5xl font-black text-slate-500 font-mono select-none">$</span>
                        <input type="number"
                               step="0.01"
                               min="0.01"
                               required
                               x-model.number="chargeAmount"
                               placeholder="0.00"
                               class="block w-full bg-slate-950 border-2 border-slate-800 rounded-2xl py-5 pl-14 pr-4 text-4xl md:text-5xl font-mono font-black text-emerald-400 focus:outline-none focus:border-[#f58613] placeholder:text-slate-800">
                    </div>
                </div>

                <!-- TAX & CLIENT FAST TRACKERS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- SALES TAX CONFIG -->
                    <div class="bg-white border-2 border-slate-300 rounded-3xl p-5 space-y-3 shadow-sm">
                        <span class="block text-xs font-black uppercase text-slate-500 tracking-wider">Local Sales Tax Option</span>
                        <div class="flex items-center justify-between gap-4 p-2.5 bg-slate-50 rounded-xl border border-slate-200">
                            <label class="flex items-center gap-2 font-bold text-sm text-slate-700 cursor-pointer select-none">
                                <input type="checkbox" x-model="applyTax" class="w-5 h-5 rounded border-slate-300 text-[#f58613] focus:ring-[#f58613]">
                                Apply Sales Tax Surcharge
                            </label>
                            <div class="flex items-center gap-1 max-w-[90px]" x-show="applyTax" x-cloak x-transition>
                                <input type="number" step="0.01" x-model.number="taxRate" class="w-full bg-white border border-slate-300 rounded-lg py-1 px-2 font-mono font-black text-xs text-right">
                                <span class="text-xs font-black text-slate-500">%</span>
                            </div>
                        </div>
                    </div>

                    <!-- CLIENT METADATA PLACEHOLDER -->
                    <div class="bg-white border-2 border-slate-300 rounded-3xl p-5 space-y-3 shadow-sm">
                        <span class="block text-xs font-black uppercase text-slate-500 tracking-wider">Customer Reference Information</span>
                        <input type="text" x-model="clientIdentifier" placeholder="Customer Name or Job Reference No."
                               class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-base font-bold text-slate-900 focus:outline-none focus:border-slate-900 placeholder:text-slate-400">
                    </div>
                </div>

                <!-- GIANT METRIC MOBILITY SELECTION MATRIX -->
                <div class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-4">
                    <div>
                        <h3 class="font-black text-base text-slate-900 uppercase tracking-wider">Select Collection Path</h3>
                        <p class="text-xs text-slate-400 font-bold mt-0.5">How is the client paying you for this run right now?</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        <!-- CASH -->
                        <button type="button"
                                @click="paymentMethod = 'cash'"
                                :class="paymentMethod === 'cash' ? 'border-slate-900 bg-slate-900 text-white shadow-lg' : 'border-slate-300 hover:border-slate-800 text-slate-700 bg-white'"
                                class="border-4 rounded-2xl py-5 px-4 text-center transition transform active:scale-98 flex flex-col items-center justify-center gap-2 cursor-pointer outline-none">
                            <span class="text-3xl">💵</span>
                            <span class="text-sm font-black uppercase tracking-wider">Collected Cash</span>
                        </button>

                        <!-- CHECK -->
                        <button type="button"
                                @click="paymentMethod = 'check'"
                                :class="paymentMethod === 'check' ? 'border-slate-900 bg-slate-900 text-white shadow-lg' : 'border-slate-300 hover:border-slate-800 text-slate-700 bg-white'"
                                class="border-4 rounded-2xl py-5 px-4 text-center transition transform active:scale-98 flex flex-col items-center justify-center gap-2 cursor-pointer outline-none">
                            <span class="text-3xl">📝</span>
                            <span class="text-sm font-black uppercase tracking-wider">Collected Check</span>
                        </button>

                        <!-- TEXT PAY LINK -->
                        <button type="button"
                                @click="paymentMethod = 'text_link'"
                                :class="paymentMethod === 'text_link' ? 'border-slate-900 bg-slate-900 text-white shadow-lg' : 'border-slate-300 hover:border-slate-800 text-slate-700 bg-white'"
                                class="border-4 rounded-2xl py-5 px-4 text-center transition transform active:scale-98 flex flex-col items-center justify-center gap-2 cursor-pointer outline-none">
                            <span class="text-3xl">📱</span>
                            <span class="text-sm font-black uppercase tracking-wider">Text Pay Link</span>
                        </button>

                        <!-- SWIPE / CARD TERMINAL -->
                        <button type="button"
                                @click="paymentMethod = 'card_swipe'"
                                :class="paymentMethod === 'card_swipe' ? 'border-slate-900 bg-slate-900 text-white shadow-lg' : 'border-slate-300 hover:border-slate-800 text-slate-700 bg-white'"
                                class="border-4 rounded-2xl py-5 px-4 text-center transition transform active:scale-98 flex flex-col items-center justify-center gap-2 cursor-pointer outline-none">
                            <span class="text-3xl">💳</span>
                            <span class="text-sm font-black uppercase tracking-wider">Swipe Credit Card</span>
                        </button>
                    </div>
                </div>

                <!-- CALCULATION DRAWER SUMMARY & LOG RUN TRIGGER -->
                <div class="bg-white border-2 border-slate-900 rounded-3xl p-6 shadow-xl flex flex-col sm:flex-row justify-between items-center gap-6 ring-4 ring-[#f58613]/5">
                    <div class="font-mono text-xs text-slate-600 space-y-1.5 w-full sm:w-auto">
                        <div class="flex justify-between sm:justify-start gap-4">
                            <span class="w-32 font-bold uppercase tracking-wider text-slate-400">Net Base Run:</span>
                            <span class="font-black text-slate-900 text-sm" x-text="'$' + (chargeAmount || 0).toFixed(2)">$0.00</span>
                        </div>
                        <div class="flex justify-between sm:justify-start gap-4" x-show="applyTax && taxRate > 0" x-cloak>
                            <span class="w-32 font-bold uppercase tracking-wider text-slate-400">Sales Surcharges:</span>
                            <span class="font-black text-slate-900 text-sm" x-text="'+$' + computedTax.toFixed(2)">+$0.00</span>
                        </div>
                        <div class="flex justify-between sm:justify-start gap-4 pt-2 border-t-2 border-slate-100">
                            <span class="w-32 font-black uppercase tracking-wider text-slate-800">Grand Total Due:</span>
                            <span class="text-2xl font-black text-emerald-600" x-text="'$' + computedTotal.toFixed(2)">$0.00</span>
                        </div>
                    </div>

                    <div class="w-full sm:w-auto">
                        <button type="submit"
                                :disabled="!chargeAmount || chargeAmount <= 0"
                                class="w-full sm:w-auto bg-[#f58613] hover:bg-orange-600 disabled:opacity-30 disabled:pointer-events-none text-white font-black text-lg py-5 px-10 rounded-2xl uppercase tracking-widest shadow-xl transition-all transform active:scale-98 border-0 cursor-pointer outline-none">
                            Process Transaction ⚡
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- REVENUE MATHEMATICS DRIVER SCRIPT -->
    <script>
        function quickBillTerminal() {
            return {
                chargeAmount: '',
                applyTax: false,
                taxRate: 7.5,
                clientIdentifier: '',
                paymentMethod: 'cash',

                get computedTax() {
                    if (!this.applyTax || !this.chargeAmount) return 0;
                    return (parseFloat(this.chargeAmount) || 0) * ((parseFloat(this.taxRate) || 0) / 100);
                },
                get computedTotal() {
                    return (parseFloat(this.chargeAmount) || 0) + this.computedTax;
                },
                processFieldCollection() {
                    let summary = `⚡ Field Collection Intent Processed!\n\n` +
                                  `Reference: ${this.clientIdentifier || 'General Walk-In/Counter Sale'}\n` +
                                  `Total Charge: $${this.computedTotal.toFixed(2)} (${this.paymentMethod.toUpperCase()})`;

                    alert(summary);

                    // Reset terminal parameters on successful local logging sequence
                    this.chargeAmount = '';
                    this.clientIdentifier = '';
                    this.applyTax = false;
                }
            };
        }
    </script>
</body>
</html>
