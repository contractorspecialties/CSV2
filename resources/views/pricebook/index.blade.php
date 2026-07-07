<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Book | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white"
      x-data="{
          showAddModal: false,
          showEditModal: false,
          editItem: { id: '', name: '', category: '', unit_type: 'flat_rate', base_unit_cost: 0, markup_percentage: 0, description: '' },
          openEditModal(item) {
              this.editItem = { ...item };
              this.showEditModal = true;
          }
      }">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-5xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[400px] max-w-[60%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>
            <a href="/dashboard" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl transition-all shadow-inner text-decoration-none">
                ← Dashboard
            </a>
        </div>
    </header>

    <main class="flex-grow max-w-4xl w-full mx-auto px-4 py-8 space-y-6">

        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-slate-200 pb-5">
            <div>
                <h1 class="text-3xl font-black text-slate-950 uppercase tracking-tight">Price Book</h1>
                <p class="text-base text-slate-500 font-bold mt-1">Set your standard company costs and markups. The system calculates your final customer prices automatically.</p>
            </div>
            <div>
                <button @click="showAddModal = true"
                        class="w-full sm:w-auto bg-[#f58613] hover:bg-orange-600 text-white font-black text-sm py-4 px-6 rounded-2xl uppercase tracking-wider transition-all shadow-xl active:scale-95 cursor-pointer border-0 outline-none">
                    + Add New Item
                </button>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-600 border border-emerald-700 text-white rounded-2xl text-xs font-black uppercase tracking-tight flex items-center gap-2 shadow-md">
                <span>👍</span> {{ session('status') }}
            </div>
        @endif

        <div class="hidden md:block bg-white border-2 border-slate-300 rounded-3xl shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-slate-300 text-[11px] uppercase tracking-wider font-mono font-black border-b border-slate-950">
                        <th class="py-4 px-5">Item Name & Details</th>
                        <th class="py-4 px-4">Category</th>
                        <th class="py-4 px-4 text-center">How You Charge</th>
                        <th class="py-4 px-4 text-right">Your Cost</th>
                        <th class="py-4 px-4 text-center">Markup</th>
                        <th class="py-4 px-4 text-right">Customer Price</th>
                        <th class="py-4 px-5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-bold text-slate-700">
                    @foreach($items as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-5">
                                <div class="font-black text-slate-950 text-base uppercase tracking-tight">{{ $item->name }}</div>
                                @if($item->description)
                                    <div class="text-xs text-slate-400 font-semibold max-w-sm mt-0.5 leading-normal italic whitespace-normal">{{ $item->description }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block bg-slate-100 border-2 border-slate-300 text-slate-700 font-black text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-lg">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center text-slate-500 uppercase tracking-tight text-xs font-black">
                                @switch($item->unit_type)
                                    @case('flat_rate') Flat Rate @break
                                    @case('sqft') Square Foot (SF) @break
                                    @case('linear_ft') Linear Foot (LF) @break
                                    @case('hourly') Per Hour @break
                                    @default {{ $item->unit_type }}
                                @endswitch
                            </td>
                            <td class="py-4 px-4 text-right font-mono text-slate-400">
                                ${{ number_format($item->base_unit_cost, 2) }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center font-mono font-black text-xs text-orange-600 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded-md">
                                    +{{ number_format($item->markup_percentage, 1) }}%
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right font-mono font-black text-base text-slate-950 bg-slate-50/50">
                                ${{ number_format($item->base_unit_cost * (1 + ($item->markup_percentage / 100)), 2) }}
                            </td>
                            <td class="py-4 px-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                            @click="openEditModal({{ json_encode($item) }})"
                                            class="text-xs font-black text-slate-600 hover:text-white bg-slate-100 hover:bg-slate-800 border border-slate-300 px-3 py-2 rounded-xl transition-colors cursor-pointer uppercase tracking-wider outline-none">
                                        Edit
                                    </button>
                                    <form action="/pricebook/{{ $item->id }}" method="POST" onsubmit="return confirm('Permanently delete this item from your price book?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-black text-red-500 hover:text-white bg-red-50 hover:bg-red-600 border border-red-200 px-3 py-2 rounded-xl transition-colors cursor-pointer uppercase tracking-wider border-0 outline-none">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="block md:hidden space-y-4">
            @foreach($items as $item)
                <div class="bg-white border-2 border-slate-300 rounded-3xl p-5 shadow-sm space-y-4 relative">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-3">
                        <div>
                            <span class="text-[9px] font-mono font-black bg-slate-100 border border-slate-300 text-slate-600 px-2 py-0.5 rounded uppercase tracking-wider">
                                {{ $item->category }}
                            </span>
                            <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight mt-1.5 leading-tight">{{ $item->name }}</h3>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    @click="openEditModal({{ json_encode($item) }})"
                                    class="bg-slate-100 border-2 border-slate-200 text-slate-700 font-black p-2.5 rounded-xl text-sm transition-colors border-0 cursor-pointer outline-none">
                                ✏️
                            </button>
                            <form action="/pricebook/{{ $item->id }}" method="POST" onsubmit="return confirm('Permanently delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-50 border-2 border-red-200 text-red-600 font-black p-2.5 rounded-xl text-sm transition-colors border-0 cursor-pointer outline-none">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($item->description)
                        <p class="text-xs text-slate-500 font-medium leading-relaxed italic">{{ $item->description }}</p>
                    @endif

                    <div class="grid grid-cols-2 gap-2.5 bg-slate-50 p-4 border border-slate-200 rounded-2xl font-mono text-xs">
                        <div class="space-y-1">
                            <span class="block text-[9px] font-sans font-black text-slate-400 uppercase tracking-wide">Your Base Cost</span>
                            <span class="block text-slate-900 font-bold">${{ number_format($item->base_unit_cost, 2) }} / @switch($item->unit_type)
                                @case('flat_rate') Job @break
                                @case('sqft') SF @break
                                @case('linear_ft') LF @break
                                @case('hourly') HR @break
                                @default Unit
                            @endswitch</span>
                        </div>
                        <div class="space-y-1 text-right">
                            <span class="block text-[9px] font-sans font-black text-slate-400 uppercase tracking-wide">Profit Markup</span>
                            <span class="inline-block text-orange-600 font-black">+{{ number_format($item->markup_percentage, 1) }}%</span>
                        </div>
                        <div class="col-span-2 pt-2 border-t border-slate-200 Fly-row flex justify-between items-baseline mt-1">
                            <span class="text-[10px] font-sans font-black text-slate-800 uppercase tracking-wider">Price to Customer:</span>
                            <span class="text-lg font-black text-emerald-600">${{ number_format($item->base_unit_cost * (1 + ($item->markup_percentage / 100)), 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($items->isEmpty())
            <div class="py-20 bg-white rounded-3xl border-4 border-dashed border-slate-300 text-center px-4 shadow-sm">
                <div class="text-4xl select-none mb-3">📋</div>
                <h3 class="text-2xl font-black text-slate-900 uppercase">Your Price Book is Empty</h3>
                <p class="text-slate-600 text-base font-bold max-w-sm mx-auto mt-1 mb-8">Add your standard jobs and materials here to pick them instantly when building estimates.</p>
                <button @click="showAddModal = true" class="bg-slate-900 hover:bg-black text-white font-black py-4 px-8 rounded-2xl text-base shadow-xl border-0 cursor-pointer outline-none">
                    Add Your First Item
                </button>
            </div>
        @endif

    </main>

    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" @click="showAddModal = false"></div>
        <div class="bg-white border-4 border-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl relative z-10 max-h-[92vh] overflow-y-auto" x-transition>
            <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-5">
                <h3 class="text-lg font-black uppercase text-slate-950 tracking-tight font-mono flex items-center gap-1.5"><span>📊</span> Add New Item</h3>
                <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-900 font-bold text-base bg-transparent border-0 cursor-pointer outline-none">✕</button>
            </div>
            <form action="/pricebook" method="POST" x-data="{ baseCost: 0, markupPercent: 0 }" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Item Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Roof Patching Repair" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-base font-bold focus:outline-none focus:border-slate-900 text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Category *</label>
                    <input type="text" name="category" required placeholder="e.g. Service Labor" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-base font-bold focus:outline-none focus:border-slate-900 text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">How do you charge for this? *</label>
                    <select name="unit_type" required class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-bold focus:outline-none focus:border-slate-900 cursor-pointer bg-white text-slate-900">
                        <option value="flat_rate">Flat Rate (Per Job)</option>
                        <option value="sqft">Square Foot (SF)</option>
                        <option value="linear_ft">Linear Foot (LF)</option>
                        <option value="hourly">Hourly Rate (HR)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Your Cost ($) *</label>
                        <input type="number" name="base_unit_cost" step="0.01" min="0" required x-model.number="baseCost" placeholder="0.00" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-base font-mono font-black focus:outline-none focus:border-slate-900 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Markup (%) *</label>
                        <input type="number" name="markup_percentage" step="0.1" min="0" required x-model.number="markupPercent" placeholder="0.0" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-base font-mono font-black focus:outline-none focus:border-slate-900 text-slate-900">
                    </div>
                </div>
                <div class="bg-slate-950 text-white p-4 rounded-2xl space-y-2 font-mono text-xs border border-slate-900">
                    <div class="flex justify-between font-bold"><span class="text-slate-400">Your Profit Margin:</span><span class="text-orange-500 font-black" x-text="'+$' + ((baseCost || 0) * ((markupPercent || 0) / 100)).toFixed(2)">+$0.00</span></div>
                    <div class="flex justify-between items-baseline pt-2 border-t border-slate-800 mt-2"><span class="text-[10px] font-sans font-black text-slate-200">Price to Customer:</span><span class="text-2xl font-black text-emerald-400" x-text="'$' + ((baseCost || 0) * (1 + ((markupPercent || 0) / 100))).toFixed(2)">$0.00</span></div>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Internal Notes (Customers won't see this)</label>
                    <textarea name="description" rows="2" placeholder="List standard tools, material requirements, or sizes..." class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl p-3 text-sm font-medium focus:outline-none focus:border-slate-900 text-slate-900"></textarea>
                </div>
                <div class="pt-2 flex justify-end gap-3">
                    <button type="button" @click="showAddModal = false" class="bg-slate-100 text-slate-700 font-black text-xs py-3.5 px-5 rounded-xl uppercase tracking-wider border-0 cursor-pointer outline-none">Cancel</button>
                    <button type="submit" class="bg-[#f58613] text-white font-black text-xs py-3.5 px-6 rounded-xl uppercase tracking-wider border-0 cursor-pointer outline-none">Save to Price Book</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" @click="showEditModal = false"></div>
        <div class="bg-white border-4 border-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl relative z-10 max-h-[92vh] overflow-y-auto shadow-black/40" x-transition>
            <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-5">
                <h3 class="text-lg font-black uppercase text-slate-950 tracking-tight font-mono flex items-center gap-1.5"><span>🔄</span> Edit Item</h3>
                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-900 font-bold text-base bg-transparent border-0 cursor-pointer outline-none">✕</button>
            </div>
            <form :action="'/pricebook/update/' + editItem.id" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Item Name *</label>
                    <input type="text" name="name" required x-model="editItem.name" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-base font-bold focus:outline-none focus:border-slate-900 text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Category *</label>
                    <input type="text" name="category" required x-model="editItem.category" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-base font-bold focus:outline-none focus:border-slate-900 text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">How do you charge for this? *</label>
                    <select name="unit_type" required x-model="editItem.unit_type" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-bold focus:outline-none focus:border-slate-900 cursor-pointer bg-white text-slate-900">
                        <option value="flat_rate">Flat Rate (Per Job)</option>
                        <option value="sqft">Square Foot (SF)</option>
                        <option value="linear_ft">Linear Foot (LF)</option>
                        <option value="hourly">Hourly Rate (HR)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Your Cost ($) *</label>
                        <input type="number" name="base_unit_cost" step="0.01" min="0" required x-model.number="editItem.base_unit_cost" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-base font-mono font-black focus:outline-none focus:border-slate-900 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Markup (%) *</label>
                        <input type="number" name="markup_percentage" step="0.1" min="0" required x-model.number="editItem.markup_percentage" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-base font-mono font-black focus:outline-none focus:border-slate-900 text-slate-900">
                    </div>
                </div>
                <div class="bg-slate-950 text-white p-4 rounded-2xl space-y-2 font-mono text-xs border border-slate-900">
                    <div class="flex justify-between font-bold"><span class="text-slate-400">Your Profit Margin:</span><span class="text-orange-500 font-black" x-text="'+$' + ((parseFloat(editItem.base_unit_cost) || 0) * ((parseFloat(editItem.markup_percentage) || 0) / 100)).toFixed(2)">+$0.00</span></div>
                    <div class="flex justify-between items-baseline pt-2 border-t border-slate-800 mt-2"><span class="text-[10px] font-sans font-black text-slate-200">Price to Customer:</span><span class="text-2xl font-black text-emerald-400" x-text="'$' + ((parseFloat(editItem.base_unit_cost) || 0) * (1 + ((parseFloat(editItem.markup_percentage) || 0) / 100))).toFixed(2)">$0.00</span></div>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-2">Internal Notes (Customers won't see this)</label>
                    <textarea name="description" rows="2" x-model="editItem.description" class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl p-3 text-sm font-medium focus:outline-none focus:border-slate-900 text-slate-900"></textarea>
                </div>
                <div class="pt-2 flex justify-end gap-3">
                    <button type="button" @click="showEditModal = false" class="bg-slate-100 text-slate-700 font-black text-xs py-3.5 px-5 rounded-xl uppercase tracking-wider border-0 cursor-pointer outline-none">Cancel</button>
                    <button type="submit" class="bg-slate-900 text-white font-black text-xs py-3.5 px-6 rounded-xl uppercase tracking-wider border-0 cursor-pointer outline-none">Save Changes ⚡</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
