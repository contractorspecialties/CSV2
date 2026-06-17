<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Estimate | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-5xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[400px] max-w-[60%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>
            <a href="/dashboard" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl transition-all shadow-inner">
                ← Cancel & Exit
            </a>
        </div>
    </header>

    <main class="flex-grow max-w-5xl w-full mx-auto px-4 py-8" x-data="{
        items: [{ description: '', quantity: 1, unit_price: 0.00, save_to_pricebook: false }],
        taxRate: 0,
        requireDeposit: false,
        depositAmount: 0,
        isRecurring: false,
        pricebook: {{ json_encode($pricebookItems ?? []) }},
        customersList: {{ json_encode($customers ?? []) }},

        // Inline Customer Management State
        customerSource: 'directory',
        customer_first_name: '',
        customer_last_name: '',
        customer_email: '',
        customer_phone: '',
        customer_address: '',

        init() {
            // Check if there is a preselected customer forwarded from a directory route
            const preselectedId = '{{ $preselectedCustomerId ?? '' }}';
            if (preselectedId) {
                this.loadDirectoryProfile(preselectedId);
            }
        },
        addItem() {
            this.items.push({ description: '', quantity: 1, unit_price: 0.00, save_to_pricebook: false });
        },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },
        loadPricebookItem(index, itemId) {
            const match = this.pricebook.find(i => i.id == itemId);
            if (match) {
                this.items[index].description = match.name;
                const finalCost = parseFloat(match.base_unit_cost) * (1 + (parseFloat(match.markup_percentage) / 100));
                this.items[index].unit_price = finalCost.toFixed(2);
            }
        },
        loadDirectoryProfile(id) {
            const match = this.customersList.find(c => c.id == id);
            if (match) {
                this.customer_first_name = match.first_name;
                this.customer_last_name = match.last_name;
                this.customer_email = match.email;
                this.customer_phone = match.phone || '';
                this.customer_address = match.billing_address || '';
            } else {
                this.clearCustomerFields();
            }
        },
        clearCustomerFields() {
            this.customer_first_name = '';
            this.customer_last_name = '';
            this.customer_email = '';
            this.customer_phone = '';
            this.customer_address = '';
        },
        get subtotal() {
            return this.items.reduce((sum, item) => sum + ((parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0)), 0);
        },
        get taxTotal() {
            return this.subtotal * ((parseFloat(this.taxRate) || 0) / 100);
        },
        get grandTotal() {
            return this.subtotal + this.taxTotal;
        }
    }">

        <div class="border-b border-slate-200 pb-4 mb-6">
            <h1 class="text-2xl font-black text-slate-950 uppercase tracking-tight">Compile New Job Estimate</h1>
            <p class="text-sm text-slate-500 font-medium">Select a customer directory profile, map out your service line items, and configure deposit or billing loops.</p>
        </div>

        <form action="/estimates" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="font-black text-sm text-slate-900 uppercase tracking-wider">1. Customer Account Profile</h3>
                        <p class="text-xs text-slate-400 font-medium">Link a historical client or input a brand new lead inline.</p>
                    </div>

                    <div class="flex p-1 bg-slate-100 rounded-xl max-w-xs border border-slate-200/60">
                        <button type="button"
                                @click="customerSource = 'directory'; loadDirectoryProfile(document.getElementById('customer_select').value)"
                                :class="customerSource === 'directory' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                class="flex-1 text-center py-1.5 px-3 rounded-lg text-xs font-black uppercase tracking-wider transition-all cursor-pointer">
                            Directory
                        </button>
                        <button type="button"
                                @click="customerSource = 'new'; clearCustomerFields()"
                                :class="customerSource === 'new' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                class="flex-1 text-center py-1.5 px-3 rounded-lg text-xs font-black uppercase tracking-wider transition-all cursor-pointer">
                            + New Lead
                        </button>
                    </div>
                </div>

                <div class="max-w-md space-y-1" x-show="customerSource === 'directory'" x-transition>
                    <label for="customer_select" class="block text-xs font-black uppercase text-slate-500">Choose Active Profile</label>
                    <select id="customer_select"
                            @change="loadDirectoryProfile($event.target.value)"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3 px-4 text-sm font-bold focus:outline-none focus:border-[#f58613] bg-white cursor-pointer">
                        <option value="" {{ !($preselectedCustomerId ?? null) ? 'selected' : '' }}>-- Select a contractor directory profile --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ ($preselectedCustomerId ?? null) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->last_name }}, {{ $customer->first_name }} ({{ $customer->phone ?? 'No Phone' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 pt-2">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">First Name *</label>
                        <input type="text" name="customer_first_name" required x-model="customer_first_name" placeholder="John"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-semibold focus:outline-none focus:border-[#f58613]">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Last Name *</label>
                        <input type="text" name="customer_last_name" required x-model="customer_last_name" placeholder="Doe"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-semibold focus:outline-none focus:border-[#f58613]">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Email Address *</label>
                        <input type="email" name="customer_email" required x-model="customer_email" placeholder="johndoe@example.com"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-semibold focus:outline-none focus:border-[#f58613]">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Phone Number</label>
                        <input type="text" name="customer_phone" x-model="customer_phone" placeholder="(555) 123-4567"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-semibold focus:outline-none focus:border-[#f58613]">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Project / Billing Address</label>
                        <input type="text" name="customer_address" x-model="customer_address" placeholder="123 Construction Way, Suite 100"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-semibold focus:outline-none focus:border-[#f58613]">
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-2 flex justify-between items-center">
                    <h3 class="font-black text-sm text-slate-900 uppercase tracking-wider">2. Job Line Items & Specifications</h3>
                    <button type="button" @click="addItem()" class="bg-slate-950 hover:bg-black text-white font-black text-xs py-1.5 px-3 rounded-lg uppercase tracking-wider transition-all cursor-pointer">
                        + Add Custom Row
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end bg-slate-50/50 p-4 border border-slate-200 rounded-xl relative group">

                            <div class="md:col-span-3">
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Auto-Fill from Pricebook</label>
                                <select @change="loadPricebookItem(index, $event.target.value)" class="w-full bg-white border border-slate-300 rounded-lg py-2 px-2.5 text-xs font-bold focus:outline-none focus:border-[#f58613] cursor-pointer">
                                    <option value="">-- Choose Item --</option>
                                    <template x-for="pItem in pricebook" :key="pItem.id">
                                        <option :value="pItem.id" x-text="pItem.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="md:col-span-4">
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Service / Material Description</label>
                                <input type="text" :name="`items[${index}][description]`" required x-model="item.description" placeholder="e.g., Premium Exterior Siding Treatment"
                                       class="w-full bg-white border border-slate-300 rounded-lg py-2 px-2.5 text-xs font-semibold focus:outline-none focus:border-[#f58613]">
                            </div>

                            <div class="md:col-span-1.5">
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Quantity</label>
                                <input type="number" step="any" :name="`items[${index}][quantity]`" required x-model.number="item.quantity" min="0.01"
                                       class="w-full bg-white border border-slate-300 rounded-lg py-2 px-2.5 text-xs font-mono font-black focus:outline-none focus:border-[#f58613] text-center">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Contract Price ($)</label>
                                <input type="number" step="0.01" :name="`items[${index}][unit_price]`" required x-model.number="item.unit_price" placeholder="0.00"
                                       class="w-full bg-white border border-slate-300 rounded-lg py-2 px-2.5 text-xs font-mono font-black focus:outline-none focus:border-[#f58613] text-right">
                            </div>

                            <div class="md:col-span-1.5 flex items-center justify-between gap-2 h-9 pb-0.5">
                                <label class="flex items-center gap-1 cursor-pointer select-none">
                                    <input type="checkbox" :name="`items[${index}][save_to_pricebook]`" x-model="item.save_to_pricebook" class="rounded border-slate-300 text-[#f58613] focus:ring-[#f58613]">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tight">Save</span>
                                </label>
                                <button type="button" @click="removeItem(index)" :disabled="items.length === 1"
                                        class="text-xs font-black text-red-500 disabled:opacity-30 bg-red-50 border border-red-200/40 px-2 py-1 rounded hover:bg-red-100 transition-all cursor-pointer">
                                    ✕
                                </button>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <label class="flex items-center gap-2 font-black text-sm text-slate-900 uppercase tracking-wider cursor-pointer">
                            <input type="checkbox" name="is_recurring" x-model="isRecurring" class="rounded border-slate-300 text-[#f58613] focus:ring-[#f58613] w-4 h-4">
                            🔄 Setup as Repeating Ongoing Service
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-3" x-show="isRecurring" x-cloak x-transition>
                        <div>
                            <label class="block text-xs font-black uppercase text-slate-500 mb-1">Billing Interval</label>
                            <select name="recurrence_interval" class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-3 text-xs font-bold focus:outline-none focus:border-[#f58613] bg-white cursor-pointer">
                                <option value="weekly">Weekly Rotations</option>
                                <option value="bi_weekly">Bi-Weekly Rotations</option>
                                <option value="monthly">Monthly Rotations</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase text-slate-500 mb-1">Total Target Visits</label>
                            <input type="number" name="recurrence_cycles" min="1" placeholder="e.g., 12"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-3 text-xs font-mono font-black focus:outline-none focus:border-[#f58613]">
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="font-black text-sm text-slate-900 uppercase tracking-wider">💰 Invoicing Rules & Surcharges</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="flex items-center gap-2 font-black text-xs text-slate-600 uppercase tracking-wider mb-2 cursor-pointer">
                                <input type="checkbox" name="require_deposit" x-model="requireDeposit" class="rounded border-slate-300 text-[#f58613] focus:ring-[#f58613]">
                                Request Upfront Deposit
                            </label>
                            <input type="number" name="deposit_amount" step="0.01" placeholder="0.00" x-show="requireDeposit" x-cloak x-transition x-model.number="depositAmount"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-3 text-xs font-mono font-black focus:outline-none focus:border-[#f58613]">
                        </div>
                        <div>
                            <label for="tax_rate" class="block text-xs font-black uppercase text-slate-500 mb-1">Local Sales Tax Rate (%)</label>
                            <input type="number" id="tax_rate" name="tax_rate" step="0.01" min="0" max="100" x-model.number="taxRate" placeholder="0.00"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-3 text-xs font-mono font-black focus:outline-none focus:border-[#f58613]">
                        </div>
                    </div>
                </div>

            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-2">
                <label for="notes" class="block text-xs font-black uppercase text-slate-500 tracking-wider">Internal Job Scope Notes (Visible to Homeowner)</label>
                <textarea id="notes" name="notes" rows="3" placeholder="Provide extra detail about scope parameters, material standards, or specific arrival updates..."
                          class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-xs font-medium focus:outline-none focus:border-[#f58613]"></textarea>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-md flex flex-col sm:flex-row justify-between items-center gap-6">

                <div class="font-mono text-xs text-slate-600 space-y-1 w-full sm:w-auto">
                    <div class="flex justify-between sm:justify-start gap-4">
                        <span class="w-32 font-bold uppercase tracking-wider text-slate-400">Net Materials Subtotal:</span>
                        <span class="font-black text-slate-900" x-text="'$' + subtotal.toFixed(2)">$0.00</span>
                    </div>
                    <div class="flex justify-between sm:justify-start gap-4" x-show="taxRate > 0" x-cloak>
                        <span class="w-32 font-bold uppercase tracking-wider text-slate-400">Sales Surcharges Tax:</span>
                        <span class="font-black text-slate-900" x-text="'+$' + taxTotal.toFixed(2)">+$0.00</span>
                    </div>
                    <div class="flex justify-between sm:justify-start gap-4 pt-2 border-t border-slate-100">
                        <span class="w-32 font-black uppercase tracking-wider text-slate-800">Final Estimate Value:</span>
                        <span class="text-lg font-black text-emerald-600" x-text="'$' + grandTotal.toFixed(2)">$0.00</span>
                    </div>
                </div>

                <div class="w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-8 rounded-xl uppercase tracking-widest shadow transition-all active:scale-[0.99] cursor-pointer">
                        Compile & Save Estimate ⚡
                    </button>
                </div>

            </div>

        </form>
    </main>

</body>
</html>
