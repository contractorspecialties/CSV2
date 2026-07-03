<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Bid | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white"
      x-data="estimateForm()">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-5xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[400px] max-w-[60%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>
            <a href="/dashboard" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl transition-all shadow-inner text-decoration-none">
                &larr; Cancel & Exit
            </a>
        </div>
    </header>

    <main class="flex-grow max-w-5xl w-full mx-auto px-4 py-8">

        @if ($errors->any())
            <div class="bg-red-50 border-4 border-red-300 text-red-900 rounded-2xl p-5 mb-6 space-y-2 shadow-sm">
                <div class="flex items-center gap-2 font-black text-sm uppercase tracking-wider text-red-800">
                    <span>🛑</span> Form Validation Guard Blocked Submission:
                </div>
                <ul class="list-disc list-inside text-xs font-bold text-red-700 space-y-1 pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="border-b border-slate-200 pb-4 mb-6">
            <h1 class="text-3xl font-black text-slate-950 uppercase tracking-tight">Compile New Job Estimate</h1>
            <p class="text-base text-slate-500 font-bold mt-1">Select a customer, build service line items, and lock parameters.</p>
        </div>

        <form action="/estimates" method="POST" enctype="multipart/form-data" @submit="clearLocalCache()" class="space-y-8">
            @csrf

            <input type="hidden" name="customer_id" x-model="customer_id">

            <div class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="border-b border-slate-200 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="font-black text-lg text-slate-900 uppercase tracking-wider">1. Customer Information</h3>
                        <p class="text-xs text-slate-400 font-bold mt-0.5">Link an existing customer record or map a new lead entry right from the field.</p>
                    </div>

                    <div class="flex p-1.5 bg-slate-100 rounded-2xl max-w-xs border-2 border-slate-200 w-full sm:w-auto">
                        <button type="button"
                                @click="customerSource = 'directory'; loadDirectoryProfile(document.getElementById('customer_select').value)"
                                :class="customerSource === 'directory' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-900 bg-transparent'"
                                class="flex-1 text-center py-2.5 px-4 rounded-xl text-xs font-black uppercase tracking-wider transition-all cursor-pointer border-0 outline-none">
                            Directory
                        </button>
                        <button type="button"
                                @click="customerSource = 'new'; clearCustomerFields()"
                                :class="customerSource === 'new' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-900 bg-transparent'"
                                class="flex-1 text-center py-2.5 px-4 rounded-xl text-xs font-black uppercase tracking-wider transition-all cursor-pointer border-0 outline-none">
                            + New Lead
                        </button>
                    </div>
                </div>

                <div class="space-y-2" x-show="customerSource === 'directory'" x-transition>
                    <label for="customer_select" class="block text-xs font-black uppercase text-slate-500 tracking-wider">Choose Customer</label>
                    <select id="customer_select"
                            @change="loadDirectoryProfile($event.target.value)"
                            class="w-full bg-slate-50 border-4 border-slate-300 rounded-2xl py-4 px-5 text-lg font-bold text-slate-900 focus:outline-none focus:border-slate-900 cursor-pointer">
                        <option value="">-- Choose an existing customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_select') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->last_name }}, {{ $customer->first_name }} ({{ $customer->phone_number ?? 'No Phone Contact Saved' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 pt-2">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">First Name *</label>
                        <input type="text" name="customer_first_name" required x-model="customer_first_name" placeholder="John"
                               class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-bold focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Last Name *</label>
                        <input type="text" name="customer_last_name" required x-model="customer_last_name" placeholder="Doe"
                               class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-bold focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Email Address *</label>
                        <input type="email" name="customer_email" required x-model="customer_email" placeholder="johndoe@example.com"
                               class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-bold focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Phone Number</label>
                        <input type="text" name="customer_phone" x-model="customer_phone" placeholder="(555) 123-4567"
                               class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-bold focus:outline-none focus:border-slate-900">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Project / Billing Address</label>
                        <input type="text" name="customer_address" x-model="customer_address" placeholder="123 Construction Way, Suite 100"
                               class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-bold focus:outline-none focus:border-slate-900">
                    </div>
                </div>
            </div>

            <div class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="border-b border-slate-200 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="font-black text-lg text-slate-900 uppercase tracking-wider">2. Scope of Work & Bid Details</h3>
                    </div>
                    <button type="button" @click="addItem()" class="bg-slate-900 hover:bg-black text-white font-black text-xs py-3 px-5 rounded-xl uppercase tracking-wider transition-all cursor-pointer border-0 outline-none">
                        + Add Line Service
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="item.id">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50 p-5 border-2 border-slate-200 rounded-2xl relative group">

                            <div class="md:col-span-3">
                                <label class="block text-[11px] font-black uppercase text-slate-400 mb-2 tracking-wide">Import From Pricebook Matrix</label>
                                <select @change="loadPricebookItem(index, $event.target.value)" class="w-full bg-white border-2 border-slate-300 rounded-xl py-3 px-3 text-sm font-bold focus:outline-none focus:border-slate-900 cursor-pointer">
                                    <option value="">-- Choose Pre-priced Item --</option>
                                    <template x-for="pItem in pricebook" :key="pItem.id">
                                        <option :value="pItem.id" x-text="pItem.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="md:col-span-4">
                                <label class="block text-[11px] font-black uppercase text-slate-400 mb-2 tracking-wide">Description of Work / Materials</label>
                                <input type="text" :name="'items[' + index + '][description]'" required x-model="item.description" placeholder="e.g., Premium Exterior Siding Treatment"
                                       class="w-full bg-white border-2 border-slate-300 rounded-xl py-3 px-3 text-sm font-bold focus:outline-none focus:border-slate-900">
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 md:col-span-4 gap-3">
                                <div>
                                    <label class="block text-[11px] font-black uppercase text-slate-400 mb-2 tracking-wide text-center">Qty</label>
                                    <input type="number" step="any" :name="'items[' + index + '][quantity]'" required x-model.number="item.quantity" min="0.01"
                                           class="w-full bg-white border-2 border-slate-300 rounded-xl py-3 px-2 text-sm font-mono font-black focus:outline-none focus:border-slate-900 text-center">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black uppercase text-slate-400 mb-2 tracking-wide text-right">Rate ($)</label>
                                    <input type="number" step="0.01" :name="'items[' + index + '][unit_price]'" required x-model.number="item.unit_price" placeholder="0.00"
                                           class="w-full bg-white border-2 border-slate-300 rounded-xl py-3 px-2 text-sm font-mono font-black focus:outline-none focus:border-slate-900 text-right">
                                </div>

                                <div class="flex flex-col items-center justify-center pb-1 select-none">
                                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-tight">Tax</label>
                                    <input type="hidden" :name="'items[' + index + '][is_taxable]'" value="0">
                                    <input type="checkbox" :name="'items[' + index + '][is_taxable]'" value="1" x-model="item.is_taxable" class="w-5 h-5 rounded border-slate-300 text-[#f58613] focus:ring-[#f58613] cursor-pointer">
                                </div>

                                <div class="flex flex-col items-center justify-center pb-1 select-none">
                                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-tight">Save</label>
                                    <input type="hidden" :name="'items[' + index + '][save_to_pricebook]'" value="0">
                                    <input type="checkbox" :name="'items[' + index + '][save_to_pricebook]'" value="1" x-model="item.save_to_pricebook" class="w-5 h-5 rounded border-slate-300 text-[#f58613] focus:ring-[#f58613] cursor-pointer">
                                </div>
                            </div>

                            <div class="md:col-span-1 flex items-center justify-center pt-2 md:pt-0">
                                <button type="button" @click="removeItem(index)" :disabled="items.length === 1"
                                        class="text-sm font-black text-red-500 disabled:opacity-30 bg-red-50 border border-red-200 w-11 h-11 rounded-xl flex items-center justify-center hover:bg-red-100 transition-all cursor-pointer border-0 outline-none">
                                    ✕
                                </button>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="border-b border-slate-200 pb-3">
                    <h3 class="font-black text-lg text-slate-900 uppercase tracking-wider">3. 📸 Job Site Evidence & Photo Markup</h3>
                    <p class="text-xs text-slate-400 font-bold mt-0.5">Capture pre-existing structural issues or measure dimensions inside the markup suite.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <div class="md:col-span-2 space-y-5">
                        <input type="file" id="studioFileInput" name="image" class="hidden">
                        <input type="file" id="cameraInputDriver" accept="image/*" capture="environment" class="hidden" @change="loadPhotoToStudio($event)">
                        <input type="file" id="galleryInputDriver" accept="image/*" class="hidden" @change="loadPhotoToStudio($event)">

                        <div>
                            <span class="block text-xs font-black uppercase text-slate-500 mb-3 tracking-wider">Acquire Scope Image Link</span>
                            <div class="grid grid-cols-2 gap-4">
                                <button type="button" @click="document.getElementById('cameraInputDriver').click()" class="bg-slate-900 hover:bg-black text-white font-black text-sm py-4 px-4 rounded-2xl uppercase tracking-wider shadow transition-all active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer border-0 outline-none">
                                    📷 Take Live Photo
                                </button>
                                <button type="button" @click="document.getElementById('galleryInputDriver').click()" class="bg-white border-2 border-slate-300 hover:border-slate-800 text-slate-800 hover:text-slate-950 font-black text-sm py-4 px-4 rounded-2xl uppercase tracking-wider shadow-sm transition-all active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer outline-none">
                                    🖼️ Open Gallery
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Photo Caption / Log Details</label>
                            <input type="text" name="caption" value="{{ old('caption') }}" placeholder="e.g., Water rot parameters logged on bottom siding course"
                                   class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-bold focus:outline-none focus:border-slate-900">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <span class="block text-xs font-black uppercase text-slate-500 tracking-wider">Active Workspace Thumbnail</span>
                        <div class="flex flex-col items-center justify-center bg-slate-100 border-2 border-slate-300 rounded-2xl aspect-video relative overflow-hidden group shadow-inner">
                            <div class="absolute inset-0 w-full h-full" x-show="hasMarkupAttached" x-cloak>
                                <img :src="markupPreviewUrl" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center gap-1.5 transition-all cursor-pointer" @click="launchMarkupStudioWithCurrentImage()">
                                    <span class="text-xl">✏️</span>
                                    <span class="text-[10px] font-black uppercase text-white tracking-widest bg-[#f58613] px-3 py-1.5 rounded-xl shadow">Edit Studio Revisions</span>
                                </div>
                                <div class="absolute bottom-3 left-3 bg-emerald-600 text-white font-black text-[9px] uppercase tracking-wider px-2.5 py-1 rounded-md shadow">
                                    ✓ Markup Anchored
                                </div>
                            </div>

                            <div class="absolute inset-0 w-full h-full" x-show="hasImageSelected && !hasMarkupAttached" x-cloak>
                                <img :src="rawPreviewUrl" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center gap-2 cursor-pointer" @click="launchMarkupStudioWithCurrentImage()">
                                    <span class="text-xl animate-pulse">🎨</span>
                                    <span class="text-[10px] font-black uppercase text-white tracking-widest bg-[#f58613] hover:bg-orange-600 px-4 py-2 rounded-xl shadow transition-colors border-0">
                                        Launch Sketch Pad Tools
                                    </span>
                                </div>
                                <div class="absolute bottom-3 left-3 bg-amber-500 text-slate-950 font-black text-[9px] uppercase tracking-wider px-2.5 py-1 rounded-md shadow">
                                    ⚠️ Raw Captured Image
                                </div>
                            </div>

                            <div class="text-center text-slate-400 p-6 space-y-1 select-none" x-show="!hasImageSelected">
                                <span class="text-3xl block">🖼️</span>
                                <span class="text-xs font-black uppercase tracking-wider block text-slate-400">No Target Photo Linked</span>
                            </div>
                        </div>

                        <div class="flex gap-2" x-show="hasImageSelected" x-cloak>
                            <button type="button" @click="launchMarkupStudioWithCurrentImage()" class="flex-1 bg-slate-900 hover:bg-black text-slate-200 hover:text-white border-0 font-black text-xs py-2.5 rounded-xl uppercase tracking-wider text-center transition-all shadow-sm cursor-pointer outline-none">
                                Open Editor
                            </button>
                            <button type="button" @click="purgeActivePhotoSelection()" class="bg-red-50 hover:bg-red-100 border-2 border-red-200 text-red-600 font-black text-xs px-4 rounded-xl uppercase transition-colors cursor-pointer outline-none">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-200 pb-3">
                        <label class="flex items-center gap-3 font-black text-base text-slate-900 uppercase tracking-wider cursor-pointer select-none">
                            <input type="checkbox" name="is_recurring" x-model="isRecurring" class="rounded border-slate-300 text-[#f58613] focus:ring-[#f58613] w-5 h-5">
                            🔄 Setup as Ongoing Recurring Job
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-4" x-show="isRecurring" x-cloak x-transition>
                        <div>
                            <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Billing Interval</label>
                            <select name="recurrence_interval" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-3 text-sm font-bold focus:outline-none focus:border-slate-900 bg-white cursor-pointer">
                                <option value="weekly">Weekly Rotations</option>
                                <option value="bi_weekly">Bi-Weekly Rotations</option>
                                <option value="monthly">Monthly Rotations</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Total Project Visits</label>
                            <input type="number" name="recurrence_cycles" min="1" placeholder="e.g. 12"
                                   class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-3 text-sm font-mono font-black focus:outline-none focus:border-slate-900">
                        </div>
                    </div>
                </div>

                <div class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-200 pb-3">
                        <h3 class="font-black text-base text-slate-900 uppercase tracking-wider">💰 Invoicing Rules & Expirations</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="flex items-center gap-2 font-black text-xs text-slate-500 uppercase tracking-wider mb-3 cursor-pointer select-none">
                                <input type="checkbox" name="require_deposit" x-model="requireDeposit" class="rounded border-slate-300 text-[#f58613] focus:ring-[#f58613] w-4 h-4">
                                Upfront Deposit
                            </label>
                            <input type="number" name="deposit_amount" step="0.01" placeholder="0.00" x-show="requireDeposit" x-cloak x-transition x-model.number="depositAmount"
                                   class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-3 text-sm font-mono font-black focus:outline-none focus:border-slate-900">
                        </div>
                        <div>
                            <label for="tax_rate" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-3">Sales Tax (%)</label>
                            <input type="number" id="tax_rate" name="tax_rate" step="0.01" min="0" max="100" x-model.number="taxRate" placeholder="0.00" required
                                   class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-3 text-sm font-mono font-black focus:outline-none focus:border-slate-900">
                        </div>
                        <div>
                            <label for="expires_at" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-3">Expiration Date</label>
                            <input type="date" id="expires_at" name="expires_at"
                                   class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-3 text-sm font-semibold focus:outline-none focus:border-slate-900 bg-white text-slate-700 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-2">
                <label for="notes" class="block text-xs font-black uppercase text-slate-500 tracking-wider">Proposal Terms / Homeowner Scope Notes</label>
                <p class="text-xs font-bold text-slate-400 mb-2">Scope constraints, materials lists, or payment schedules. The customer WILL see these details on their phone portal layout.</p>
                <textarea id="notes" name="notes" rows="3" placeholder="Provide extra detail about scope parameters, timeline variations, or site footprint clearances..."
                          class="w-full bg-slate-50 border-2 border-slate-300 rounded-2xl p-4 text-base font-medium focus:outline-none focus:border-slate-900">{{ old('notes') }}</textarea>
            </div>

            <div class="bg-white border-2 border-slate-900 rounded-3xl p-6 shadow-lg flex flex-col sm:flex-row justify-between items-center gap-6 ring-4 ring-[#f58613]/5">
                <div class="font-mono text-xs text-slate-600 space-y-1.5 w-full sm:w-auto">
                    <div class="flex justify-between sm:justify-start gap-4">
                        <span class="w-36 font-bold uppercase tracking-wider text-slate-400">Base Project Cost:</span>
                        <span class="font-black text-slate-900 text-sm" x-text="'$' + subtotal.toFixed(2)">$0.00</span>
                    </div>
                    <div class="flex justify-between sm:justify-start gap-4" x-show="taxRate > 0" x-cloak>
                        <span class="w-36 font-bold uppercase tracking-wider text-slate-400">Tax Surcharges:</span>
                        <span class="font-black text-slate-900 text-sm" x-text="'+$' + taxTotal.toFixed(2)">+$0.00</span>
                    </div>
                    <div class="flex justify-between sm:justify-start gap-4" x-show="requireDeposit && depositAmount > 0" x-cloak>
                        <span class="w-36 font-bold uppercase tracking-wider text-slate-400">Required Deposit:</span>
                        <span class="font-black text-orange-600 text-sm" x-text="'$' + parseFloat(depositAmount).toFixed(2)">$0.00</span>
                    </div>
                    <div class="flex justify-between sm:justify-start gap-4 pt-2 border-t-2 border-slate-100">
                        <span class="w-36 font-black uppercase tracking-wider text-slate-800">Total Bid Amount:</span>
                        <span class="text-2xl font-black text-emerald-600" x-text="'$' + grandTotal.toFixed(2)">$0.00</span>
                    </div>
                </div>

                <div class="w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto bg-[#f58613] hover:bg-orange-600 text-white font-black text-lg py-5 px-10 rounded-2xl uppercase tracking-widest shadow-xl transform active:scale-98 border-0 cursor-pointer outline-none">
                        Generate & Lock Bid ⚡
                    </button>
                </div>
            </div>
        </form>
    </main>

    <div x-show="showStudio" x-cloak class="fixed inset-0 z-100 bg-slate-950 flex flex-col select-none" @window:resize.debounce.200="resizeCanvas()">
        <div class="bg-slate-900 border-b border-slate-800 px-4 h-16 shrink-0 flex items-center justify-between">
            <button type="button" @click="closeStudio()" class="text-slate-400 hover:text-white font-black text-xs tracking-widest uppercase cursor-pointer bg-transparent border-0 outline-none">
                &larr; Cancel
            </button>
            <div class="flex items-center gap-3">
                <button type="button" @click="undoLastShape()" class="bg-slate-800 hover:bg-slate-700 text-slate-200 font-black text-xs px-3.5 py-2 rounded-xl uppercase tracking-widest cursor-pointer transition-all border-0 outline-none">
                    &larr; Undo
                </button>
                <button type="button" @click="clearStudioCanvas()" class="bg-red-950/40 text-red-400 hover:bg-red-900/40 font-black text-xs px-3.5 py-2 rounded-xl uppercase tracking-widest cursor-pointer transition-all border-0 outline-none">
                    🗑️ Clear
                </button>
            </div>
            <button type="button" @click="commitStudioMarkup()" class="bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs px-5 py-2.5 rounded-xl uppercase tracking-widest shadow transition-all active:scale-95 cursor-pointer border-0 outline-none">
                Save Markup ✓
            </button>
        </div>

        <div class="flex-grow relative bg-slate-950 overflow-hidden flex items-center justify-center p-2">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 bg-slate-900/90 backdrop-blur-md border border-slate-800 p-2.5 rounded-2xl flex flex-col gap-4 z-10 shadow-xl">
                <div class="space-y-2">
                    <span class="block text-[8px] font-black text-slate-500 uppercase tracking-wider text-center">Size</span>
                    <button type="button" @click="thickness = 2; textSize = 14" :class="thickness === 2 ? 'border-2 border-[#f58613] bg-slate-800' : 'border border-slate-700'" class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold cursor-pointer border-0">F</button>
                    <button type="button" @click="thickness = 6; textSize = 22" :class="thickness === 6 ? 'border-2 border-[#f58613] bg-slate-800' : 'border border-slate-700'" class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold cursor-pointer border-0">M</button>
                    <button type="button" @click="thickness = 12; textSize = 32" :class="thickness === 12 ? 'border-2 border-[#f58613] bg-slate-800' : 'border border-slate-700'" class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-base font-bold cursor-pointer border-0">B</button>
                </div>
            </div>

            <div class="absolute right-3 top-1/2 -translate-y-1/2 bg-slate-900/90 backdrop-blur-md border border-slate-800 p-2.5 rounded-2xl flex flex-col gap-3 z-10 shadow-xl">
                <span class="block text-[8px] font-black text-slate-500 uppercase tracking-wider text-center">Color</span>
                <button type="button" @click="color = '#f58613'" :class="color === '#f58613' ? 'ring-2 ring-white scale-110' : ''" class="w-6 h-6 rounded-full bg-[#f58613] cursor-pointer transition-transform border-0 outline-none"></button>
                <button type="button" @click="color = '#eab308'" :class="color === '#eab308' ? 'ring-2 ring-white scale-110' : ''" class="w-6 h-6 rounded-full bg-yellow-500 cursor-pointer transition-transform border-0 outline-none"></button>
                <button type="button" @click="color = '#dc2626'" :class="color === '#dc2626' ? 'ring-2 ring-white scale-110' : ''" class="w-6 h-6 rounded-full bg-red-600 cursor-pointer transition-transform border-0 outline-none"></button>
                <button type="button" @click="color = '#ffffff'" :class="color === '#ffffff' ? 'ring-2 ring-orange-500 scale-110' : ''" class="w-6 h-6 rounded-full bg-white border border-slate-300 cursor-pointer transition-transform outline-none"></button>
                <button type="button" @click="color = '#0f172a'" :class="color === '#0f172a' ? 'ring-2 ring-white scale-110' : ''" class="w-6 h-6 rounded-full bg-slate-900 border border-slate-800 cursor-pointer transition-transform outline-none"></button>
            </div>

            <canvas id="studioCanvas"
                    class="max-w-full max-h-full shadow-2xl bg-black block touch-none"
                    @mousedown="startDrawing($event)"
                    @mousemove="drawMove($event)"
                    @mouseup="endDrawing($event)"
                    @mouseleave="endDrawing($event)"
                    @touchstart="startDrawing($event)"
                    @touchmove="drawMove($event)"
                    @touchend="endDrawing($event)">
            </canvas>
        </div>

        <div class="bg-slate-900 border-t border-slate-800 px-4 h-20 shrink-0 flex items-center justify-center gap-1.5 sm:gap-3 overflow-x-auto">
            <button type="button" @click="tool = 'pen'" :class="tool === 'pen' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0 outline-none">
                <span>✏️</span> Pen
            </button>
            <button type="button" @click="tool = 'line'" :class="tool === 'line' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0 outline-none">
                <span>Line</span> Line
            </button>
            <button type="button" @click="tool = 'box'" :class="tool === 'box' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0 outline-none">
                <span>Box</span> Box
            </button>
            <button type="button" @click="tool = 'circle'" :class="tool === 'circle' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0 outline-none">
                <span>Circle</span> Circle
            </button>
            <button type="button" @click="tool = 'text'" :class="tool === 'text' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0 outline-none">
                <span>🔤</span> Text
            </button>
        </div>
    </div>

    <script>
        function estimateForm() {
            return {
                items: [],
                taxRate: @js(old('tax_rate', 0)),
                requireDeposit: @js(old('require_deposit') ? true : false),
                depositAmount: @js(old('deposit_amount', 0)),
                isRecurring: @js(old('is_recurring') ? true : false),

                pricebook: @js($pricebookItems ?? []),
                customersList: @js($customers ?? []),

                customerSource: @js(old('customerSource', 'directory')),
                customer_id: @js(old('customer_id', '')),
                customer_first_name: @js(old('customer_first_name', '')),
                customer_last_name: @js(old('customer_last_name', '')),
                customer_email: @js(old('customer_email', '')),
                customer_phone: @js(old('customer_phone', '')),
                customer_address: @js(old('customer_address', '')),

                showStudio: false,
                tool: 'pen',
                color: '#f58613',
                thickness: 6,
                textSize: 22,
                canvas: null,
                ctx: null,
                bgImage: null,
                isDrawing: false,
                startX: 0,
                startY: 0,
                history: [],
                currentPoints: [],

                hasImageSelected: false,
                hasMarkupAttached: false,
                rawPreviewUrl: '',
                markupPreviewUrl: '',

                init() {
                    const savedCache = localStorage.getItem('cs_estimate_draft_cache');
                    if (savedCache) {
                        try {
                            const parsed = JSON.parse(savedCache);
                            this.customerSource = parsed.customerSource ?? 'directory';
                            this.customer_id = parsed.customer_id ?? '';
                            this.customer_first_name = parsed.customer_first_name ?? '';
                            this.customer_last_name = parsed.customer_last_name ?? '';
                            this.customer_email = parsed.customer_email ?? '';
                            this.customer_phone = parsed.customer_phone ?? '';
                            this.customer_address = parsed.customer_address ?? '';
                            this.taxRate = parsed.taxRate ?? 0;
                            this.requireDeposit = parsed.requireDeposit ?? false;
                            this.depositAmount = parsed.depositAmount ?? 0;
                            this.isRecurring = parsed.isRecurring ?? false;

                            if (parsed.items && parsed.items.length > 0) {
                                this.items = parsed.items;
                            }
                        } catch (e) {
                            console.error("Failed to parse dead-zone local storage cache string keys.", e);
                        }
                    }

                    if (this.items.length === 0) {
                        @if(old('items'))
                            @foreach(old('items') as $idx => $oldItem)
                                this.items.push({
                                    id: {{ microtime(true) * 1000 + $idx }},
                                    description: @js($oldItem['description'] ?? ''),
                                    quantity: parseFloat(@js($oldItem['quantity'] ?? 1)) || 1,
                                    unit_price: parseFloat(@js($oldItem['unit_price'] ?? 0.00)) || 0.00,
                                    is_taxable: @js(!isset($oldItem['is_taxable']) || $oldItem['is_taxable'] == 1 || $oldItem['is_taxable'] === 'true' || $oldItem['is_taxable'] === 'on'),
                                    save_to_pricebook: @js($oldItem['save_to_pricebook'] == 1 || $oldItem['save_to_pricebook'] === 'true' || $oldItem['save_to_pricebook'] === 'on')
                                });
                            @endforeach
                        @else
                            this.items.push({ id: Date.now(), description: '', quantity: 1, unit_price: 0.00, is_taxable: true, save_to_pricebook: false });
                        @endif
                    }

                    const preselectedId = '{{ $preselectedCustomerId ?? "" }}';
                    if (preselectedId && !this.customer_first_name) {
                        this.loadDirectoryProfile(preselectedId);
                    }

                    this.$watch('items', () => this.persistToLocalCache(), { deep: true });
                    this.$watch('customerSource', () => this.persistToLocalCache());
                    this.$watch('customer_first_name', () => this.persistToLocalCache());
                    this.$watch('customer_last_name', () => this.persistToLocalCache());
                    this.$watch('customer_email', () => this.persistToLocalCache());
                    this.$watch('customer_phone', () => this.persistToLocalCache());
                    this.$watch('customer_address', () => this.persistToLocalCache());
                    this.$watch('taxRate', () => this.persistToLocalCache());
                    this.$watch('requireDeposit', () => this.persistToLocalCache());
                    this.$watch('depositAmount', () => this.persistToLocalCache());
                    this.$watch('isRecurring', () => this.persistToLocalCache());
                },

                persistToLocalCache() {
                    const cacheMatrix = {
                        customerSource: this.customerSource,
                        customer_id: this.customer_id,
                        customer_first_name: this.customer_first_name,
                        customer_last_name: this.customer_last_name,
                        customer_email: this.customer_email,
                        customer_phone: this.customer_phone,
                        customer_address: this.customer_address,
                        taxRate: this.taxRate,
                        requireDeposit: this.requireDeposit,
                        depositAmount: this.depositAmount,
                        isRecurring: this.isRecurring,
                        items: this.items
                    };
                    localStorage.setItem('cs_estimate_draft_cache', JSON.stringify(cacheMatrix));
                },

                clearLocalCache() {
                    localStorage.removeItem('cs_estimate_draft_cache');
                },

                addItem() {
                    this.items.push({ id: Date.now() + Math.random(), description: '', quantity: 1, unit_price: 0.00, is_taxable: true, save_to_pricebook: false });
                },
                removeItem(index) {
                    if (this.items.length > 1) this.items.splice(index, 1);
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
                        this.customer_id = match.id;
                        this.customer_first_name = match.first_name;
                        this.customer_last_name = match.last_name;
                        this.customer_email = match.email;
                        this.customer_phone = match.phone_number || '';
                        this.customer_address = match.address || match.billing_address || '';
                    } else {
                        this.clearCustomerFields();
                    }
                },
                clearCustomerFields() {
                    this.customer_id = '';
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
                    const taxableAggregate = this.items.reduce((sum, item) => {
                        return sum + (item.is_taxable ? ((parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0)) : 0);
                    }, 0);
                    return taxableAggregate * ((parseFloat(this.taxRate) || 0) / 100);
                },
                get grandTotal() {
                    return this.subtotal + this.taxTotal;
                },

                loadPhotoToStudio(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.rawPreviewUrl = e.target.result;
                        this.hasImageSelected = true;
                        this.hasMarkupAttached = false;

                        this.bgImage = new Image();
                        this.bgImage.onload = () => {
                            this.showStudio = true;
                            this.history = [];
                            this.$nextTick(() => this.initCanvasElements());
                        };
                        this.bgImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },
                launchMarkupStudioWithCurrentImage() {
                    if (!this.rawPreviewUrl) return;
                    this.bgImage = new Image();
                    this.bgImage.onload = () => {
                        this.showStudio = true;
                        this.$nextTick(() => this.initCanvasElements());
                    };
                    this.bgImage.src = this.rawPreviewUrl;
                },
                purgeActivePhotoSelection() {
                    this.hasImageSelected = false;
                    this.hasMarkupAttached = false;
                    this.rawPreviewUrl = '';
                    this.markupPreviewUrl = '';
                    this.history = [];
                    document.getElementById('studioFileInput').value = '';
                    document.getElementById('cameraInputDriver').value = '';
                    document.getElementById('galleryInputDriver').value = '';
                },
                initCanvasElements() {
                    this.canvas = document.getElementById('studioCanvas');
                    this.ctx = this.canvas.getContext('2d');
                    this.resizeCanvas();
                },
                resizeCanvas() {
                    if (!this.canvas || !this.bgImage) return;
                    const maxWidth = window.innerWidth * 0.90;
                    const maxHeight = window.innerHeight * 0.70;
                    const ratio = Math.min(maxWidth / this.bgImage.width, maxHeight / this.bgImage.height);
                    this.canvas.width = this.bgImage.width * ratio;
                    this.canvas.height = this.bgImage.height * ratio;
                    this.redrawCanvasWorkspace();
                },
                redrawCanvasWorkspace() {
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                    this.ctx.drawImage(this.bgImage, 0, 0, this.canvas.width, this.canvas.height);
                    this.history.forEach(shape => this.drawShapePrimitive(shape));
                },
                getCoordinates(event) {
                    let touch = null;
                    if (event.touches && event.touches.length > 0) {
                        touch = event.touches[0];
                    } else if (event.changedTouches && event.changedTouches.length > 0) {
                        touch = event.changedTouches[0];
                    }
                    let clientX = touch ? touch.clientX : event.clientX;
                    let clientY = touch ? touch.clientY : event.clientY;
                    const rect = this.canvas.getBoundingClientRect();
                    return { x: clientX - rect.left, y: clientY - rect.top };
                },
                startDrawing(event) {
                    event.preventDefault();
                    const coords = this.getCoordinates(event);
                    this.isDrawing = true;
                    this.startX = coords.x;
                    this.startY = coords.y;
                    if (this.tool === 'pen') {
                        this.currentPoints = [{ x: coords.x, y: coords.y }];
                    } else if (this.tool === 'text') {
                        this.isDrawing = false;
                        const note = prompt("Enter text instruction to place at coordinates:");
                        if (note) {
                            this.history.push({ type: 'text', x: this.startX, y: this.startY, text: note, color: this.color, size: this.textSize });
                            this.redrawCanvasWorkspace();
                        }
                    }
                },
                drawMove(event) {
                    if (!this.isDrawing) return;
                    event.preventDefault();
                    const coords = this.getCoordinates(event);
                    this.redrawCanvasWorkspace();
                    const tempShape = { type: this.tool, startX: this.startX, startY: this.startY, endX: coords.x, endY: coords.y, color: this.color, thickness: this.thickness, points: this.currentPoints };
                    if (this.tool === 'pen') {
                        this.currentPoints.push({ x: coords.x, y: coords.y });
                        tempShape.points = this.currentPoints;
                    }
                    this.drawShapePrimitive(tempShape);
                },
                endDrawing(event) {
                    if (!this.isDrawing) return;
                    this.isDrawing = false;
                    event.preventDefault();
                    const coords = this.getCoordinates(event) || { x: this.startX, y: this.startY };
                    if (this.history === undefined) this.history = [];
                    if (this.tool === 'pen') {
                        this.history.push({ type: 'pen', points: this.currentPoints, color: this.color, thickness: this.thickness });
                    } else if (this.tool !== 'text') {
                        this.history.push({ type: this.tool, startX: this.startX, startY: this.startY, endX: coords.x, endY: coords.y, color: this.color, thickness: this.thickness });
                    }
                    this.currentPoints = [];
                    this.redrawCanvasWorkspace();
                },
                drawShapePrimitive(shape) {
                    this.ctx.strokeStyle = shape.color;
                    this.ctx.fillStyle = shape.color;
                    this.ctx.lineWidth = shape.thickness;
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';
                    this.ctx.beginPath();
                    if (shape.type === 'pen' && shape.points && shape.points.length > 0) {
                        this.ctx.moveTo(shape.points[0].x, shape.points[0].y);
                        shape.points.forEach(p => this.ctx.lineTo(p.x, p.y));
                        this.ctx.stroke();
                    } else if (shape.type === 'line') {
                        this.ctx.moveTo(shape.startX, shape.startY);
                        this.ctx.lineTo(shape.endX, shape.endY);
                        this.ctx.stroke();
                    } else if (shape.type === 'box') {
                        this.ctx.rect(shape.startX, shape.startY, shape.endX - shape.startX, shape.endY - shape.startY);
                        this.ctx.stroke();
                    } else if (shape.type === 'circle') {
                        const radius = Math.sqrt(Math.pow(shape.endX - shape.startX, 2) + Math.pow(shape.endY - shape.startY, 2));
                        this.ctx.arc(shape.startX, shape.startY, radius, 0, 2 * Math.PI);
                        this.ctx.stroke();
                    } else if (shape.type === 'text') {
                        this.ctx.font = `bold ${shape.size}px sans-serif`;
                        this.ctx.fillText(shape.text, shape.x, shape.y);
                    } else if (shape.type === 'arrow') {
                        const angle = Math.atan2(shape.endY - shape.startY, shape.endX - shape.startX);
                        const headLength = Math.max(shape.thickness * 3, 15);
                        this.ctx.moveTo(shape.startX, shape.startY);
                        this.ctx.lineTo(shape.endX, shape.endY);
                        this.ctx.stroke();
                        this.ctx.beginPath();
                        this.ctx.moveTo(shape.endX, shape.endY);
                        this.ctx.lineTo(shape.endX - headLength * Math.cos(angle - Math.PI / 6), shape.endY - headLength * Math.sin(angle - Math.PI / 6));
                        this.ctx.lineTo(shape.endX - headLength * Math.cos(angle + Math.PI / 6), shape.endY - headLength * Math.sin(angle + Math.PI / 6));
                        this.ctx.closePath();
                        this.ctx.fill();
                    }
                },
                undoLastShape() {
                    if (this.history.length > 0) {
                        this.history.pop();
                        this.redrawCanvasWorkspace();
                    }
                },
                clearStudioCanvas() {
                    this.history = [];
                    this.redrawCanvasWorkspace();
                },
                closeStudio() {
                    this.showStudio = false;
                },
                commitStudioMarkup() {
                    this.canvas.toBlob((blob) => {
                        if (!blob) return;
                        const editedFile = new File([blob], "field_markup_capture.jpg", { type: "image/jpeg" });
                        const containerExchange = new DataTransfer();
                        containerExchange.items.add(editedFile);
                        document.getElementById('studioFileInput').files = containerExchange.files;
                        this.markupPreviewUrl = this.canvas.toDataURL('image/jpeg');
                        this.hasMarkupAttached = true;
                        this.showStudio = false;
                    }, 'image/jpeg', 0.90);
                }
            };
        }
    </script>
</body>
</html>
