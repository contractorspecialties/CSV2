<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white"
      x-data="{
          showEditModal: false,
          activeCustomer: { id: '', name: '', email: '', phone: '', address: '' }
      }">

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

        @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                <span class="text-lg">⚡</span>
                <p class="text-xs font-black uppercase tracking-tight">{{ session('status') }}</p>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 uppercase tracking-tight">Customer Accounts Ledger</h1>
                <p class="text-sm text-slate-500 font-medium">Manage historical target profile identities and trace contextual lifetime billing values.</p>
            </div>
            <div class="flex gap-2">
                <a href="/customers/create" class="bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3 px-5 rounded-xl uppercase tracking-wider transition-all shadow-md active:scale-95 text-center flex items-center justify-center">
                    + Add New Profile
                </a>
                <a href="/customers/export" class="bg-slate-900 hover:bg-black text-white border border-slate-800 font-black text-xs py-3 px-5 rounded-xl uppercase tracking-wider transition-all shadow-md active:scale-95 text-center flex items-center justify-center">
                    📥 Download CSV Ledger
                </a>
            </div>
        </div>

        <form action="/customers" method="GET" class="flex gap-2 max-w-md">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone number, email..."
                   class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-medium focus:outline-none focus:border-[#f58613] shadow-sm">
            <button type="submit" class="bg-slate-950 hover:bg-black text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider cursor-pointer transition-all">
                Filter
            </button>
        </form>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] uppercase tracking-wider font-black">
                            <th class="py-4 px-6">Customer Details</th>
                            <th class="py-4 px-6">Contact Channels</th>
                            <th class="py-4 px-6 text-right">Lifetime Value (LTV)</th>
                            <th class="py-4 px-6 text-center">Operational Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-xs text-slate-700">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-black text-slate-950 text-sm uppercase tracking-tight">{{ $customer->last_name }}, {{ $customer->first_name }}</div>
                                    <div class="text-[9px] text-slate-400 font-mono mt-0.5">SYS-ID: SC-CUST-{{ sprintf('%04d', $customer->id) }}</div>
                                    @if($customer->billing_address)
                                        <div class="text-[10px] text-slate-400 mt-1 truncate max-w-xs">📍 {{ $customer->billing_address }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-[11px] text-slate-600 space-y-0.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-slate-400">📧</span> <span class="font-semibold">{{ $customer->email }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-slate-400">📱</span> <span class="font-mono font-bold text-slate-900">{{ $customer->phone ?? 'No phone recorded' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-right font-mono font-black text-sm text-slate-950 bg-slate-50/20">
                                    ${{ number_format($customer->lifetime_value, 2) }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="/estimates/create?customer_id={{ $customer->id }}" class="text-[10px] bg-slate-950 hover:bg-black text-white font-black py-2 px-3 rounded-lg transition-all uppercase tracking-wider shadow-sm">
                                            📝 Estimate
                                        </a>
                                        <button type="button"
                                                @click="activeCustomer = {
                                                    id: '{{ $customer->id }}',
                                                    name: '{{ $customer->first_name }} {{ $customer->last_name }}',
                                                    email: '{{ $customer->email }}',
                                                    phone: '{{ $customer->phone ?? '' }}',
                                                    address: '{{ $customer->billing_address ?? '' }}'
                                                }; showEditModal = true"
                                                class="text-[10px] bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-800 font-black py-2 px-3 rounded-lg transition-all uppercase tracking-wider cursor-pointer shadow-sm">
                                            🛠️ Edit
                                        </button>
                                        <form action="/customers/{{ $customer->id }}" method="POST" onsubmit="return confirm('Scrub this client account profile from records completely?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[10px] bg-red-50 hover:bg-red-100 border border-red-100 text-red-600 font-black py-2 px-2.5 rounded-lg transition-all cursor-pointer">
                                                ✕
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center text-slate-400 font-medium italic text-sm">
                                    No customer accounts match your active system filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div x-show="showEditModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-slate-950/75 backdrop-blur-2xl transition-opacity" @click="showEditModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showEditModal" x-transition
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl border border-slate-200 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-10">

                <div class="p-6 bg-black text-white flex justify-between items-center border-b border-slate-900">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">⚙️</span>
                        <h3 class="font-black text-xs uppercase tracking-wider text-white">Adjust Customer Parameters</h3>
                    </div>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-white bg-slate-900 hover:bg-slate-800 rounded-full p-1 cursor-pointer">✕</button>
                </div>

                <form :action="'/customers/' + activeCustomer.id" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Full Client Name</label>
                        <input type="text" name="name" required x-model="activeCustomer.name" placeholder="John Doe"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-bold focus:outline-none focus:border-[#f58613]">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Email Address</label>
                            <input type="email" name="email" required x-model="activeCustomer.email" placeholder="client@example.com"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-bold focus:outline-none focus:border-[#f58613]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Mobile Contact Line</label>
                            <input type="text" name="phone" x-model="activeCustomer.phone" placeholder="+1XXXXXXXXXX"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-mono font-bold focus:outline-none focus:border-[#f58613]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Primary Project Site / Billing Location</label>
                        <input type="text" name="billing_address" x-model="activeCustomer.address" placeholder="123 Main Street"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-xs font-bold focus:outline-none focus:border-[#f58613]">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer">
                            Commit Parameter Overwrites ⚡
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
