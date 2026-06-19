<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricebook | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white"
      x-data="{ showAddModal: false }">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-5xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[400px] max-w-[60%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>
            <a href="/dashboard" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl transition-all shadow-inner">
                ← Dashboard
            </a>
        </div>
    </header>

    <main class="flex-grow max-w-5xl w-full mx-auto px-4 py-8 space-y-6">

        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 uppercase tracking-tight">Services & Materials Pricing Matrix</h1>
                <p class="text-sm text-slate-500 font-medium">Manage base line item costs and apply automatic profit markups to streamline estimate calculations.</p>
            </div>
            <div>
                <button @click="showAddModal = true"
                        class="w-full sm:w-auto bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 px-5 rounded-xl uppercase tracking-wider transition-all shadow-md active:scale-95 cursor-pointer">
                    + Add Pricebook Item
                </button>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-xs font-black uppercase tracking-tight flex items-center gap-2 shadow-sm">
                <span>⚡</span> {{ session('status') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] uppercase tracking-wider font-black">
                            <th class="py-4 px-4">Item Catalog Parameters</th>
                            <th class="py-4 px-4">Category Group</th>
                            <th class="py-4 px-4 text-center">Unit Type Matrix</th>
                            <th class="py-4 px-4 text-right">Corporate Base Cost</th>
                            <th class="py-4 px-4 text-center">Applied Markup</th>
                            <th class="py-4 px-4 text-right">Calculated Retail Price</th>
                            <th class="py-4 px-4 text-center">Delete Row</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-xs text-slate-700">
                        @forelse($items as $item)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="font-black text-slate-950 text-sm uppercase tracking-tight">{{ $item->name }}</div>
                                    @if($item->description)
                                        <div class="text-[11px] text-slate-400 font-medium max-w-xs truncate mt-0.5 leading-normal italic">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-block bg-slate-100 border border-slate-200 text-slate-700 font-black text-[9px] uppercase tracking-wider px-2 py-1 rounded-md">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center font-bold text-slate-500 uppercase tracking-tight text-[11px]">
                                    @switch($item->unit_type)
                                        @case('flat_rate') Flat Rate @break
                                        @case('sqft') Sq. Ft. @break
                                        @case('linear_ft') Linear Ft. @break
                                        @case('hourly') Per Hour @break
                                        @default {{ $item->unit_type }}
                                    @endswitch
                                </td>
                                <td class="py-4 px-4 text-right font-mono font-bold text-slate-400">
                                    ${{ number_format($item->base_unit_cost, 2) }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center font-mono font-black text-[11px] text-orange-600 bg-orange-50 border border-orange-100 px-1.5 py-0.5 rounded">
                                        +{{ number_format($item->markup_percentage, 1) }}%
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right font-mono font-black text-sm text-slate-950 bg-slate-50/30">
                                    ${{ number_format($item->base_unit_cost * (1 + ($item->markup_percentage / 100)), 2) }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <form action="/pricebook/{{ $item->id }}" method="POST" onsubmit="return confirm('Permanently delete this item row from your pricebook portfolio?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-black text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-100 px-2 py-1 rounded-md transition-all cursor-pointer uppercase tracking-wider">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-16 text-center bg-slate-50/30 border border-dashed border-slate-200 rounded-b-2xl">
                                    <div class="text-3xl mb-2">📋</div>
                                    <span class="block text-xs font-black uppercase text-slate-400 tracking-widest">Your Portfolio Catalog is Empty</span>
                                    <span class="block text-xs text-slate-400 font-medium mt-1">Pre-program elements here to power rapid dynamic row lookups inside your estimation workspace.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div x-show="showAddModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-slate-950/75 backdrop-blur-2xl transition-opacity" @click="showAddModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showAddModal" x-transition
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl border border-slate-200 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-10">

                <div class="p-6 bg-black text-white flex justify-between items-center border-b border-slate-900">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📈</span>
                        <h3 class="font-black text-xs uppercase tracking-wider text-white">Create Pricebook Item Parameter</h3>
                    </div>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-white bg-slate-900 hover:bg-slate-800 rounded-full p-1 cursor-pointer">✕</button>
                </div>

                <form action="/pricebook" method="POST" x-data="{
                    baseCost: 0,
                    markupPercent: 0,
                    get profitDollars() {
                        return (parseFloat(this.baseCost) || 0) * ((parseFloat(this.markupPercent) || 0) / 100);
                    },
                    get finalRetailPrice() {
                        return (parseFloat(this.baseCost) || 0) + this.profitDollars;
                    }
                }" class="p-6 space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Item / Service Name</label>
                            <input type="text" name="name" required placeholder="e.g., Premium Yard Layover Cut" autocomplete="off"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-bold focus:outline-none focus:border-[#f58613]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Category Group</label>
                            <input type="text" name="category" required placeholder="e.g., Landscaping" autocomplete="off"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-bold focus:outline-none focus:border-[#f58613]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Metric Calculation Basis</label>
                        <select name="unit_type" required
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3 px-3 text-xs font-bold focus:outline-none focus:border-[#f58613] cursor-pointer bg-white">
                            <option value="flat_rate">Fixed Flat Rate Price</option>
                            <option value="sqft">Square Footage (Sq. Ft.)</option>
                            <option value="linear_ft">Linear Foot Run</option>
                            <option value="hourly">Hourly Labor Rate</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Internal Base Cost ($)</label>
                            <input type="number" name="base_unit_cost" step="0.01" min="0" required x-model.number="baseCost" placeholder="0.00" autocomplete="off"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-mono font-black focus:outline-none focus:border-[#f58613]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Applied Brand Markup (%)</label>
                            <input type="number" name="markup_percentage" step="0.1" min="0" required x-model.number="markupPercent" placeholder="0.0" autocomplete="off"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-mono font-black focus:outline-none focus:border-[#f58613]">
                        </div>
                    </div>

                    <div class="bg-slate-900 text-white p-4 rounded-xl space-y-2 font-mono text-xs shadow-inner">
                        <span class="block text-[9px] font-black uppercase tracking-widest text-slate-500">Live Profit Calculation Matrix</span>
                        <div class="flex justify-between font-medium">
                            <span class="text-slate-400">Net Markup Margin:</span>
                            <span class="text-orange-400 font-bold" x-text="'+$' + profitDollars.toFixed(2)">+$0.00</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-2 border-t border-slate-800 mt-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-200">Customer Contract Price:</span>
                            <span class="text-xl font-black text-emerald-400" x-text="'$' + finalRetailPrice.toFixed(2)">$0.00</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Scope Spec Guidelines (Optional)</label>
                        <textarea name="description" rows="2" placeholder="Internal parameters or material configuration metrics..."
                                  class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-medium focus:outline-none focus:border-[#f58613] leading-relaxed"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer">
                            Save Item to Pricebook portfolio ⚡
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
