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
<body class="flex flex-col min-h-full font-sans antialiased selection:bg-amber-500 selection:text-slate-950"
      x-data="{ showAddModal: false }">

    <header class="bg-slate-950 border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded bg-amber-500 flex items-center justify-center font-black text-slate-950 text-base">
                    CS
                </div>
                <h2 class="font-black text-lg text-white tracking-tight">
                    Company <span class="text-amber-500">Pricebook</span>
                </h2>
            </div>
            <a href="/dashboard" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider bg-slate-900 border border-slate-800 px-3 py-2 rounded transition-all">
                ← Dashboard
            </a>
        </div>
    </header>

    <main class="flex-grow max-w-5xl w-full mx-auto px-4 py-8 space-y-6">

        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 uppercase tracking-tight">Services & Materials Pricing</h1>
                <p class="text-sm text-slate-500 font-medium">Manage your base item costs and automatically apply standard profit markups before sending estimates.</p>
            </div>
            <div>
                <button @click="showAddModal = true"
                        class="w-full sm:w-auto bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs py-3 px-5 rounded-xl uppercase tracking-wider transition-all shadow-md active:scale-95 cursor-pointer">
                    + Add Pricebook Item
                </button>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm font-black flex items-center gap-2 shadow-sm">
                <span>⚡</span> {{ session('status') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-950 text-slate-400 text-xs uppercase tracking-wider font-black border-b border-slate-900">
                            <th class="py-3.5 px-4">Item Name</th>
                            <th class="py-3.5 px-4">Category</th>
                            <th class="py-3.5 px-4 text-center">Pricing Unit</th>
                            <th class="py-3.5 px-4 text-right">My Base Cost</th>
                            <th class="py-3.5 px-4 text-center">Markup</th>
                            <th class="py-3.5 px-4 text-right">Final Customer Price</th>
                            <th class="py-3.5 px-4 text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($items as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="font-black text-slate-950 text-base">{{ $item->name }}</div>
                                    @if($item->description)
                                        <div class="text-xs text-slate-400 font-normal max-w-xs truncate italic">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-block bg-slate-100 border border-slate-200 text-slate-700 font-black text-[10px] uppercase tracking-wider px-2 py-1 rounded-md">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center text-xs font-bold text-slate-600 uppercase tracking-tight">
                                    @switch($item->unit_type)
                                        @case('flat_rate') Flat Rate @break
                                        @case('sqft') Sq. Ft. @break
                                        @case('linear_ft') Linear Ft. @break
                                        @case('hourly') Per Hour @break
                                        @default {{ $item->unit_type }}
                                    @endswitch
                                </td>
                                <td class="py-4 px-4 text-right font-mono font-bold text-slate-500">
                                    ${{ number_format($item->base_unit_cost, 2) }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center font-mono font-black text-xs text-orange-600 bg-orange-50 border border-orange-200/50 px-2 py-0.5 rounded">
                                        +{{ number_format($item->markup_percentage, 1) }}%
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right font-mono font-black text-base text-slate-950 bg-slate-50/50">
                                    ${{ number_format($item->base_unit_cost * (1 + ($item->markup_percentage / 100)), 2) }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <form action="/pricebook/{{ $item->id }}" method="POST" onsubmit="return confirm('Permanently delete this item from your pricebook?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-black text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-200/40 px-2.5 py-1 rounded-md transition-all cursor-pointer">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-16 text-center bg-slate-50/50 border border-dashed border-slate-200 rounded-b-xl">
                                    <div class="text-3xl mb-2">📋</div>
                                    <span class="block text-sm font-black uppercase text-slate-400 tracking-widest">Your Pricebook is Empty</span>
                                    <span class="block text-xs text-slate-400 font-medium mt-1">Add items here to quickly auto-fill pricing rows when building new job estimates.</span>
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

                <div class="p-5 bg-slate-950 text-white flex justify-between items-center border-b border-slate-900">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📈</span>
                        <h3 class="font-black text-sm uppercase tracking-wider text-white">Create Pricebook Item</h3>
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
                            <label for="name" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1">Item / Service Name</label>
                            <input type="text" id="name" name="name" required placeholder="e.g., Premium Yard Layover Cut" autocomplete="off"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-3 text-sm font-bold focus:outline-none focus:border-amber-500 shadow-inner">
                        </div>
                        <div>
                            <label for="category" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1">Category Group</label>
                            <input type="text" id="category" name="category" required placeholder="e.g., Landscaping" autocomplete="off"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-3 text-sm font-bold focus:outline-none focus:border-amber-500 shadow-inner">
                        </div>
                    </div>

                    <div>
                        <label for="unit_type" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1">How Do You Charge For This?</label>
                        <select id="unit_type" name="unit_type" required
                                class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2.5 px-3 text-sm font-bold focus:outline-none focus:border-amber-500 cursor-pointer bg-white">
                            <option value="flat_rate">Fixed Flat Rate Price</option>
                            <option value="sqft">Square Footage (Sq. Ft.)</option>
                            <option value="linear_ft">Linear Foot Run</option>
                            <option value="hourly">Hourly Labor Rate</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                        <div>
                            <label for="base_unit_cost" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1">Your Base Cost ($)</label>
                            <input type="number" id="base_unit_cost" name="base_unit_cost" step="0.01" min="0" required x-model.number="baseCost" placeholder="0.00" autocomplete="off"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-3 text-sm font-black focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label for="markup_percentage" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1">Profit Markup Profit (%)</label>
                            <input type="number" id="markup_percentage" name="markup_percentage" step="0.1" min="0" required x-model.number="markupPercent" placeholder="0.0" autocomplete="off"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-3 text-sm font-black focus:outline-none focus:border-amber-500">
                        </div>
                    </div>

                    <div class="bg-slate-900 text-white p-4 rounded-xl space-y-2 font-mono text-xs shadow-inner">
                        <span class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Live Profit Calculation</span>
                        <div class="flex justify-between font-medium">
                            <span class="text-slate-400">Net Dollar Markup Profit:</span>
                            <span class="text-orange-400 font-bold" x-text="'+$' + profitDollars.toFixed(2)">+$0.00</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-2 border-t border-slate-800 mt-2">
                            <span class="text-[11px] font-black uppercase tracking-wider text-slate-200">Customer Contract Price:</span>
                            <span class="text-xl font-black text-emerald-400" x-text="'$' + finalRetailPrice.toFixed(2)">$0.00</span>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1">Scope / Material Notes (Optional)</label>
                        <textarea id="description" name="description" rows="2" placeholder="Internal specs or customer descriptions..."
                                  class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-3 text-xs font-medium focus:outline-none focus:border-amber-500"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs py-3.5 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer">
                            Save Item to Pricebook ⚡
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
