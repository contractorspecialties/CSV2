<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased selection:bg-amber-500 selection:text-slate-950">

    <div x-data="{
        showInvoiceModal: false,
        showInstallModal: false,
        showApptModal: false,
        selectedCustomer: '',
        activeAppt: { id: '', title: '', date: '', notes: '', customerName: '' }
    }" class="contents">

        <!-- MAIN WORKSPACE HEADER -->
        <header class="bg-slate-950 border-b border-slate-900 sticky top-0 z-50 shadow-md">
            <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">

                <!-- Horizontal Logo & Brand Space -->
                <div class="flex items-center gap-4">
                    <!-- Scalable Horizontal Logo Placement Container -->
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-amber-400 font-mono text-xs tracking-widest uppercase font-black shadow-inner">
                        <span>⚡</span>
                        <span>CS_PLATFORM_LOGO</span>
                    </div>

                    <h2 class="font-black text-lg text-white tracking-tight hidden sm:block">
                        Apex Exterior Specialists <span class="text-amber-500 text-xs font-mono font-normal ml-1 bg-slate-900 px-2 py-0.5 border border-slate-800 rounded">Solo Mode</span>
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-slate-400 font-black text-xs uppercase tracking-widest hidden md:inline-block">
                        {{ now()->format('l, F jS') }}
                    </span>
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                </div>
            </div>
        </header>

        <!-- MAIN GRID FRAMEWORK STREAMS -->
        <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-8 space-y-8">

            <!-- ACTION DECK -->
            <section class="grid grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4">
                <a href="/estimates/create" class="relative flex flex-col items-center justify-center aspect-square bg-gradient-to-b from-amber-500 to-orange-500 rounded-2xl shadow-md border border-amber-600/30 active:scale-95 transition-all group overflow-hidden cursor-pointer">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">📝</span>
                    <span class="text-xs font-black text-slate-950 uppercase tracking-wider text-center px-1">New Estimate</span>
                </a>

                <a href="/customers/create" class="relative flex flex-col items-center justify-center aspect-square bg-white border-2 border-slate-200 rounded-2xl shadow-sm hover:border-amber-500 active:scale-95 transition-all group overflow-hidden cursor-pointer">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">👥</span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider text-center px-1">Add Customer</span>
                </a>

                <a href="/pricebook" class="relative flex flex-col items-center justify-center aspect-square bg-white border-2 border-slate-200 rounded-2xl shadow-sm hover:border-slate-800 active:scale-95 transition-all group overflow-hidden cursor-pointer">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">📖</span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider text-center px-1">Pricebook</span>
                </a>

                <button @click="showInvoiceModal = true" class="relative flex flex-col items-center justify-center aspect-square bg-white border-2 border-slate-200 rounded-2xl shadow-sm hover:border-amber-500 active:scale-95 transition-all group overflow-hidden cursor-pointer outline-none">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">⚡</span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider text-center px-1">Quick Bill</span>
                </button>

                <button @click="showInstallModal = true" class="relative flex flex-col items-center justify-center aspect-square bg-slate-900 border-2 border-slate-950 rounded-2xl shadow-md text-amber-500 active:scale-95 transition-all group overflow-hidden cursor-pointer outline-none">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">📱</span>
                    <span class="text-xs font-black uppercase tracking-wider text-center px-1 text-slate-200">App Shortcut</span>
                </button>

                <a href="#" class="relative flex flex-col items-center justify-center aspect-square bg-slate-900 border-2 border-slate-950 rounded-2xl shadow-md text-amber-500 active:scale-95 transition-all group overflow-hidden cursor-pointer">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">🌐</span>
                    <span class="text-xs font-black uppercase tracking-wider text-center px-1 text-slate-200">My Review Page</span>
                </a>
            </section>

            <!-- CASH SNAPSHOTS -->
            <section class="bg-slate-900 border border-slate-950 rounded-2xl text-white p-6 shadow-lg grid grid-cols-1 sm:grid-cols-3 gap-6 divide-y sm:divide-y-0 sm:divide-x divide-slate-800">
                <div class="space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Sent Bids</span>
                    <div class="text-3xl font-black text-amber-400 font-mono">$14,250.00</div>
                    <span class="text-xs text-slate-500 block font-medium">Bids awaiting homeowner sign-off</span>
                </div>
                <div class="pt-4 sm:pt-0 sm:pl-6 space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Unpaid Jobs</span>
                    <div class="text-3xl font-black text-orange-500 font-mono">$4,810.00</div>
                    <span class="text-xs text-slate-500 block font-medium">Active projects currently billed out</span>
                </div>
                <div class="pt-4 sm:pt-0 sm:pl-6 space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Collected This Month</span>
                    <div class="text-3xl font-black text-emerald-400 font-mono">$18,940.00</div>
                    <span class="text-xs text-slate-500 block font-medium">Auto-text review requests sent out</span>
                </div>
            </section>

            <!-- WEEKLY SCHEDULE GRID -->
            <section class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-black text-base tracking-tight text-slate-950 uppercase flex items-center gap-2">
                        📅 Schedule Overview (This Week)
                    </h3>
                    <span class="text-xs font-mono font-black text-slate-400 uppercase tracking-widest">June 2026</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-7 gap-2">
                    @php
                        $daysOfWeek = [
                            ['name' => 'Mon', 'num' => '15', 'status' => 'past', 'jobs' => 2],
                            ['name' => 'Tue', 'num' => '16', 'status' => 'past', 'jobs' => 3],
                            ['name' => 'Wed', 'num' => '17', 'status' => 'today', 'jobs' => 1],
                            ['name' => 'Thu', 'num' => '18', 'status' => 'active', 'jobs' => 4],
                            ['name' => 'Fri', 'num' => '19', 'status' => 'active', 'jobs' => 2],
                            ['name' => 'Sat', 'num' => '20', 'status' => 'weekend', 'jobs' => 0],
                            ['name' => 'Sun', 'num' => '21', 'status' => 'weekend', 'jobs' => 0],
                        ];
                    @endphp

                    @foreach($daysOfWeek as $day)
                        <div class="p-3 rounded-xl border flex flex-col justify-between h-24 transition-all
                            {{ $day['status'] === 'today' ? 'bg-amber-500 border-amber-600 text-slate-950 shadow-sm ring-2 ring-amber-500/20' : '' }}
                            {{ $day['status'] === 'past' ? 'bg-slate-50 border-slate-200 opacity-60 text-slate-400' : '' }}
                            {{ $day['status'] === 'active' ? 'bg-slate-50 border-slate-200 hover:border-slate-400 text-slate-900' : '' }}
                            {{ $day['status'] === 'weekend' ? 'bg-slate-100/50 border-slate-200 text-slate-400 border-dashed' : '' }}
                        ">
                            <div class="flex justify-between items-baseline">
                                <span class="text-xs font-black uppercase tracking-wider">{{ $day['name'] }}</span>
                                <span class="text-lg font-mono font-black">{{ $day['num'] }}</span>
                            </div>
                            <div>
                                @if($day['jobs'] > 0)
                                    <span class="text-[10px] font-black uppercase px-1.5 py-0.5 rounded block text-center truncate
                                        {{ $day['status'] === 'today' ? 'bg-slate-950 text-white' : 'bg-slate-950 text-amber-400' }}
                                    ">
                                        {{ $day['jobs'] }} {{ $day['jobs'] === 1 ? 'Job' : 'Jobs' }}
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold text-slate-400 block text-center italic">Clear</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- DATABASE RECENT CUSTOMERS -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-black text-base tracking-tight text-slate-950 uppercase flex items-center gap-2">
                            📋 Recent Customers Added
                        </h3>
                        <a href="/customers" class="text-xs font-bold text-amber-600 hover:underline uppercase tracking-wider">Full Customer List →</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-xs uppercase text-slate-400 border-b border-slate-100 font-bold tracking-wider">
                                    <th class="pb-3">Name</th>
                                    <th class="pb-3">Contact</th>
                                    <th class="pb-3 text-right">Lifetime Sales</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @forelse($recentCustomers as $customer)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-4 font-black text-slate-950 text-base">
                                            {{ $customer->last_name }}, {{ $customer->first_name }}
                                        </td>
                                        <td class="py-4 text-xs text-slate-600 space-y-0.5">
                                            <div>📧 {{ $customer->email }}</div>
                                            <div>📱 {{ $customer->phone }}</div>
                                        </td>
                                        <td class="py-4 text-right font-mono font-black text-slate-950 text-base">
                                            ${{ number_format($customer->lifetime_value, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-8 text-center text-slate-400 font-bold">
                                            No customers recorded in database. Click "Add Customer" to get started.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- RIGHT COLUMN RUNNING SERVICES -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div class="border-b border-slate-100 pb-3">
                            <h3 class="font-black text-base tracking-tight text-slate-950 uppercase">
                                🔄 Ongoing Repeating Work
                            </h3>
                        </div>
                        <div class="space-y-3">
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex justify-between items-center group">
                                <div>
                                    <div class="text-sm font-black text-slate-950">Bi-Weekly Lawn Cut</div>
                                    <div class="text-xs text-slate-500 font-medium">Miller Estate • Visit 4 of 12</div>
                                </div>
                                <div class="text-sm font-mono font-black text-slate-800 bg-white border border-slate-200 px-2 py-1 rounded shadow-sm">$140/visit</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-amber-500/5 border border-amber-500/10 rounded-xl text-center text-xs font-bold text-slate-700 leading-normal">
                        ⚡ Scheduled service runs update customer lifetime revenue values automatically.
                    </div>
                </div>
            </section>
        </main>

        <!-- ================= MODALS ================= -->

        <!-- QUICK BILL OVERLAY -->
        <div x-show="showInvoiceModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showInvoiceModal" x-transition.opacity class="fixed inset-0 bg-slate-950/75 backdrop-blur-2xl transition-opacity" @click="showInvoiceModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showInvoiceModal" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl border-2 border-slate-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-10">
                    <div class="p-6 relative space-y-4">
                        <button @click="showInvoiceModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-full p-1.5 cursor-pointer">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <h3 class="text-xl font-black text-slate-950 flex items-center">⚡ Send Quick Bill</h3>
                        <p class="text-sm text-slate-600 font-medium">Invoice a customer instantly. They will receive a text link to view and pay on their phone.</p>

                        <form action="#" method="POST" class="space-y-4">
                            <div>
                                <label class="block text-xs font-black uppercase text-slate-500 mb-1">Select Customer</label>
                                <select x-model="selectedCustomer" required class="w-full rounded-lg border border-slate-300 py-2.5 px-3 text-sm font-bold bg-white focus:outline-none focus:border-amber-500">
                                    <option value="" disabled selected>-- Choose from list --</option>
                                    @foreach($recentCustomers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->last_name }}, {{ $customer->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-slate-500 mb-1">Work Description</label>
                                <input type="text" placeholder="e.g., Haul-away & extra tree trimming" required class="w-full rounded-lg border border-slate-300 py-2.5 px-3 text-sm font-medium focus:outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-slate-500 mb-1">Amount ($)</label>
                                <input type="number" step="0.01" placeholder="0.00" required class="w-full rounded-lg border border-slate-300 py-2.5 px-3 text-sm font-black focus:outline-none focus:border-amber-500">
                            </div>
                            <button type="submit" :disabled="!selectedCustomer" class="w-full bg-amber-500 hover:bg-amber-400 disabled:bg-slate-100 disabled:text-slate-400 font-black text-xs py-3 px-4 rounded tracking-wider uppercase shadow-md cursor-pointer transition-all">
                                Send Payment Link via Text →
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- HOMESCREEN APP SHORTCUT OVERLAY -->
        <div x-show="showInstallModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showInstallModal" x-transition.opacity class="fixed inset-0 bg-slate-950/75 backdrop-blur-2xl transition-opacity" @click="showInstallModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showInstallModal" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl border-2 border-slate-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full relative z-10">
                    <div class="p-6 relative space-y-4">
                        <button @click="showInstallModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-full p-1.5 cursor-pointer">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <h3 class="text-xl font-black text-slate-950 flex items-center">📱 Save to Phone Home Screen</h3>
                        <p class="text-xs font-bold text-slate-500">Add this app directly to your phone screen layout for instant access on site.</p>
                        <div class="space-y-3 text-xs text-slate-700 font-bold">
                            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-lg">
                                <span class="text-amber-700 block font-black mb-1">iPhone & iPad (Safari)</span>
                                Tap the <span class="text-slate-900">"Share"</span> symbol below, scroll down, and select <span class="text-slate-900">"Add to Home Screen"</span>.
                            </div>
                            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-lg">
                                <span class="text-amber-700 block font-black mb-1">Android Mobile (Chrome)</span>
                                Tap the options dots icon in the top right and choose <span class="text-slate-900">"Add to Home Screen"</span>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NEW DEEP SYSTEM FOOTER -->
        <footer class="border-t border-slate-900 bg-slate-950 py-6 mt-auto">
            <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">

                <!-- Horizontal Footer Logo Space -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-2.5 py-1 bg-slate-900 border border-slate-800 rounded text-slate-400 font-mono text-[10px] tracking-widest uppercase font-black">
                        <span>⚡</span>
                        <span>CS_SYSTEM_CORE_FOOTER</span>
                    </div>
                    <div class="text-xs text-slate-500 font-medium">
                        &copy; 2026 ContractorSpecialties.
                    </div>
                </div>

                <!-- Strategic Partner / Subcontractor Entry Portals -->
                <div class="flex items-center gap-4 text-xs font-bold uppercase tracking-wider">
                    <a href="/login/partner" class="text-slate-500 hover:text-amber-500 transition-colors bg-slate-900/40 border border-slate-900 px-3 py-1.5 rounded-lg">
                        General Contractor Portal
                    </a>
                    <a href="/login/subcontractor" class="text-slate-500 hover:text-amber-500 transition-colors bg-slate-900/40 border border-slate-900 px-3 py-1.5 rounded-lg">
                        Crew Login
                    </a>
                </div>

            </div>
        </footer>

    </div>
</body>
</html>
