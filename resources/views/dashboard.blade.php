<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100 text-slate-900">
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
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <div x-data="{
        showInvoiceModal: false,
        showInstallModal: false,
        showApptModal: false,
        selectedCustomer: '',
        activeAppt: { id: '', title: '', date: '', notes: '', customerName: '' },
        selectedDayJobs: [],
        selectedDayName: ''
    }" class="contents">

        <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
            <div class="max-w-6xl mx-auto px-4 h-24 flex items-center justify-between">
                <div class="w-[400px] max-w-[65%] h-[100px] flex items-center">
                    <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-slate-400 font-black text-xs uppercase tracking-widest hidden md:inline-block">
                        {{ now()->format('l, F jS') }}
                    </span>
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                </div>
            </div>
        </header>

        <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-8 space-y-8">

            @if(session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                    <span class="text-lg">⚡</span>
                    <p class="text-xs font-black uppercase tracking-tight">{{ session('status') }}</p>
                </div>
            @endif

            <section class="grid grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4">
                <a href="/estimates/create" class="relative flex flex-col items-center justify-center aspect-square bg-gradient-to-b from-[#f58613] to-orange-600 rounded-2xl shadow-sm hover:shadow-md active:scale-95 transition-all group overflow-hidden cursor-pointer">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">_</span>
                    <span class="text-xs font-black text-white uppercase tracking-wider text-center px-1">New Estimate</span>
                </a>

                <a href="/customers/create" class="relative flex flex-col items-center justify-center aspect-square bg-white border border-slate-200 rounded-2xl shadow-sm hover:border-[#f58613] active:scale-95 transition-all group overflow-hidden cursor-pointer">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">_</span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider text-center px-1">Add Customer</span>
                </a>

                <a href="/pricebook" class="relative flex flex-col items-center justify-center aspect-square bg-white border border-slate-200 rounded-2xl shadow-sm hover:border-slate-800 active:scale-95 transition-all group overflow-hidden cursor-pointer">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">_</span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider text-center px-1">Pricebook Matrix</span>
                </a>

                <button @click="showInvoiceModal = true" class="relative flex flex-col items-center justify-center aspect-square bg-white border border-slate-200 rounded-2xl shadow-sm hover:border-[#f58613] active:scale-95 transition-all group overflow-hidden cursor-pointer outline-none">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">_</span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider text-center px-1">Quick Bill</span>
                </button>

                <button @click="showInstallModal = true" class="relative flex flex-col items-center justify-center aspect-square bg-slate-900 border border-slate-950 rounded-2xl shadow-sm text-[#f58613] active:scale-95 transition-all group overflow-hidden cursor-pointer outline-none">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">_</span>
                    <span class="text-xs font-black uppercase tracking-wider text-center px-1 text-slate-200">App Shortcut</span>
                </button>

                <a href="#" class="relative flex flex-col items-center justify-center aspect-square bg-slate-900 border border-slate-950 rounded-2xl shadow-sm text-[#f58613] active:scale-95 transition-all group overflow-hidden cursor-pointer">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">_</span>
                    <span class="text-xs font-black uppercase tracking-wider text-center px-1 text-slate-200">My Review Page</span>
                </a>
            </section>

            <section class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm grid grid-cols-1 sm:grid-cols-3 gap-6 divide-y sm:divide-y-0 sm:divide-x divide-slate-200">
                <div class="space-y-1">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">Draft Estimates</span>
                    <div class="text-3xl font-black text-slate-900 font-mono">{{ $draftCount }}</div>
                    <span class="text-xs text-slate-500 block font-medium">Quotes currently being compiled</span>
                </div>
                <div class="pt-4 sm:pt-0 sm:pl-6 space-y-1">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">Sent Estimates</span>
                    <div class="text-3xl font-black text attachment-[#f58613] font-mono">{{ $sentCount }}</div>
                    <span class="text-xs text-slate-500 block font-medium">Bids out for customer review</span>
                </div>
                <div class="pt-4 sm:pt-0 sm:pl-6 space-y-1">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">Booked Revenue</span>
                    <div class="text-3xl font-black text-emerald-600 font-mono">${{ number_format($bookedRevenue, 2) }}</div>
                    <span class="text-xs text-slate-500 block font-medium">Total value of approved contracts</span>
                </div>
            </section>

            <section class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-black text-sm tracking-tight text-slate-900 uppercase flex items-center gap-2">
                        📅 Weekly Job Schedule Overview
                    </h3>
                    <span class="text-xs font-mono font-black text-slate-400 uppercase tracking-widest">{{ now()->format('F Y') }}</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-7 gap-2">
                    @foreach($daysOfWeek as $day)
                        <div @click="selectedDayJobs = {{ json_encode($day['appointments']) }}; selectedDayName = '{{ $day['full_date'] }}'; showApptModal = true;"
                             class="p-3 rounded-xl border flex flex-col justify-between h-24 transition-all cursor-pointer hover:shadow-md hover:scale-[1.02] group select-none
                            {{ $day['status'] === 'today' ? 'bg-[#f58613] border-[#f58613] text-white shadow-sm ring-2 ring-[#f58613]/20' : '' }}
                            {{ $day['status'] === 'past' ? 'bg-slate-50 border-slate-200 opacity-60 text-slate-400' : '' }}
                            {{ $day['status'] === 'active' ? 'bg-slate-50 border-slate-200 text-slate-900' : '' }}
                            {{ $day['status'] === 'weekend' ? 'bg-slate-100/50 border-slate-200 text-slate-400 border-dashed' : '' }}
                        ">
                            <div class="flex justify-between items-baseline">
                                <span class="text-xs font-black uppercase tracking-wider group-hover:text-slate-950 {{ $day['status'] === 'today' ? 'group-hover:text-black' : '' }}">{{ $day['name'] }}</span>
                                <span class="text-lg font-mono font-black">{{ $day['num'] }}</span>
                            </div>
                            <div>
                                @if($day['jobs_count'] > 0)
                                    <span class="text-[10px] font-black uppercase px-1.5 py-0.5 rounded block text-center truncate
                                        {{ $day['status'] === 'today' ? 'bg-black text-white' : 'bg-slate-900 text-white' }}
                                    ">
                                        {{ $day['jobs_count'] }} {{ $day['jobs_count'] === 1 ? 'Job Scheduled' : 'Jobs Scheduled' }}
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold text-slate-400 block text-center italic group-hover:text-slate-600">No Jobs</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider">Active Estimates & Quotes</h3>
                    <span class="text-[10px] bg-slate-200 text-slate-700 font-black px-2 py-0.5 rounded uppercase tracking-wide">Live Pipeline Tracking</span>
                </div>

                @if($estimates->isEmpty())
                    <div class="p-12 text-center max-w-md mx-auto space-y-3">
                        <div class="w-12 h-12 bg-slate-100 border border-slate-200 text-slate-400 text-lg flex items-center justify-center rounded-xl mx-auto shadow-inner">📂</div>
                        <h4 class="font-black text-sm text-slate-900 uppercase tracking-tight">No Active Estimates Found</h4>
                        <p class="text-xs text-slate-400 font-medium">Click "New Estimate" at the top action desk to log your first client quote sheet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-black uppercase text-slate-400 bg-slate-50/20">
                                    <th class="py-3 px-6">Estimate No.</th>
                                    <th class="py-3 px-6">Customer Name</th>
                                    <th class="py-3 px-6 text-right">Total Price</th>
                                    <th class="py-3 px-6 text-center">Status</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach($estimates as $estimate)
                                    <tr class="hover:bg-slate-50/80 font-semibold transition-colors">
                                        <td class="py-4 px-6 font-mono font-black text-slate-950">{{ $estimate->estimate_number }}</td>
                                        <td class="py-4 px-6 font-bold text-slate-900">
                                            {{ $estimate->customer->last_name }}, {{ $estimate->customer->first_name }}
                                        </td>
                                        <td class="py-4 px-6 text-right font-mono font-black text-slate-950">${{ number_format($estimate->grand_total, 2) }}</td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider
                                                {{ $estimate->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : '' }}
                                                {{ $estimate->status === 'sent' ? 'bg-orange-50 text-orange-700 border border-orange-200/60' : '' }}
                                                {{ $estimate->status === 'draft' ? 'bg-slate-100 text-slate-700 border border-slate-200/40' : '' }}
                                            ">
                                                {{ $estimate->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <a href="/estimates/{{ $estimate->id }}" class="text-[#f58613] hover:text-orange-600 font-black uppercase text-[10px] tracking-wider">
                                                Open Estimate →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-black text-sm tracking-tight text-slate-900 uppercase flex items-center gap-2">
                            👥 Recent Customers Added
                        </h3>
                        <a href="/customers" class="text-xs font-bold text-[#f58613] hover:underline uppercase tracking-wider">Full Directory →</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-xs uppercase text-slate-400 border-b border-slate-100 font-bold tracking-wider">
                                    <th class="pb-3">Name</th>
                                    <th class="pb-3">Contact Details</th>
                                    <th class="pb-3 text-right">Total Revenue Generated</th>
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
                                            No customers added yet. Tap "Add Customer" to launch directory routes.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                        <div class="border-b border-slate-100 pb-3">
                            <h3 class="font-black text-sm tracking-tight text-slate-900 uppercase flex items-center gap-1.5">
                                🔒 Text Login Security (2FA)
                            </h3>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Configure your phone number to receive secure login verification texts.</p>
                        </div>

                        <form action="/user/security-phone" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label for="phone_2fa" class="block text-[10px] font-black uppercase text-slate-400 mb-1 tracking-wide">Secure Mobile Line</label>
                                <input type="tel" id=\"phone_2fa\" name="phone_2fa" placeholder="e.g., +19195551234" value="{{ auth()->user()->phone_2fa ?? '' }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-sm font-mono font-bold focus:outline-none focus:border-[#f58613]">
                            </div>

                            <button type="submit" class="w-full bg-slate-950 hover:bg-black text-white font-black text-xs py-2.5 px-4 rounded-xl uppercase tracking-wider shadow transition-all active:scale-[0.99] cursor-pointer">
                                Save Secure Number ⚡
                            </button>
                        </form>

                        <div class="text-[9px] text-slate-400 leading-normal font-medium italic text-center pt-1">
                            Status: {{ auth()->user()->phone_2fa ? '🟢 Direct 2FA Security Channel Enabled' : '🟡 Unsecured Fallback Routing Mode Active' }}
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                        <div class="border-b border-slate-100 pb-3">
                            <h3 class="font-black text-sm tracking-tight text-slate-900 uppercase">
                                🔄 Active Recurring Agreements
                            </h3>
                        </div>
                        <div class="space-y-3">
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex justify-between items-center group">
                                <div>
                                    <div class="text-sm font-black text-slate-950">Bi-Weekly Lawn Run</div>
                                    <div class="text-xs text-slate-500 font-medium">Miller Estate • Visit 4 of 12</div>
                                </div>
                                <div class="text-sm font-mono font-black text-slate-800 bg-white border border-slate-200 px-2 py-1 rounded shadow-sm">$140/visit</div>
                            </div>
                        </div>
                        <div class="p-4 bg-slate-100 border border-slate-200 rounded-xl text-center text-xs font-bold text-slate-600 leading-normal">
                            Revenue tracking updates automatically as jobs are paid.
                        </div>
                    </div>

                </div>
            </section>
        </main>

        <div x-show="showInvoiceModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showInvoiceModal" x-transition.opacity class="fixed inset-0 bg-slate-950/75 backdrop-blur-2xl transition-opacity" @click="showInvoiceModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showInvoiceModal" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl border border-slate-300 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-10">
                    <div class="p-6 relative space-y-4">
                        <button @click="showInvoiceModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-full p-1.5 cursor-pointer">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <h3 class="text-xl font-black text-slate-950 flex items-center">⚡ Send Quick Bill</h3>
                        <p class="text-sm text-slate-600 font-medium">Invoice a customer instantly. They will receive a text link to view and pay on their phone.</p>

                        <form action="#" method="POST" class="space-y-4">
                            <div>
                                <label class="block text-xs font-black uppercase text-slate-500 mb-1">Select Customer</label>
                                <select x-model="selectedCustomer" required class="w-full rounded-lg border border-slate-300 py-2.5 px-3 text-sm font-bold bg-white focus:outline-none focus:border-[#f58613]">
                                    <option value="" disabled selected>-- Choose from list --</option>
                                    @foreach($recentCustomers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->last_name }}, {{ $customer->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-slate-500 mb-1">Work Description</label>
                                <input type="text" placeholder="e.g., Extra debris haul-away" required class="w-full rounded-lg border border-slate-300 py-2.5 px-3 text-sm font-medium focus:outline-none focus:border-[#f58613]">
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-slate-500 mb-1">Amount ($)</label>
                                <input type="number" step="0.01" placeholder="0.00" required class="w-full rounded-lg border border-slate-300 py-2.5 px-3 text-sm font-black focus:outline-none focus:border-[#f58613]">
                            </div>
                            <button type="submit" :disabled="!selectedCustomer" class="w-full bg-[#f58613] hover:bg-orange-600 text-white disabled:bg-slate-100 disabled:text-slate-400 font-black text-xs py-3 px-4 rounded tracking-wider uppercase shadow-md cursor-pointer transition-all">
                                Send Payment Link via Text →
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showApptModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showApptModal" x-transition.opacity class="fixed inset-0 bg-slate-950/75 backdrop-blur-2xl transition-opacity" @click="showApptModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showApptModal" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl border border-slate-300 transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full relative z-10">
                    <div class="p-6 relative space-y-4">
                        <button @click="showApptModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-full p-1.5 cursor-pointer">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>

                        <div>
                            <span class="text-[10px] bg-slate-950 text-slate-300 font-mono font-black px-2 py-0.5 rounded uppercase tracking-wider">Daily Schedule Details</span>
                            <h3 class="text-xl font-black text-slate-950 mt-1" x-text="selectedDayName"></h3>
                        </div>

                        <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                            <template x-for="job in selectedDayJobs" :key="job.id">
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2 hover:border-slate-300 transition-all text-left">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <h4 class="font-black text-slate-950 text-sm uppercase tracking-tight" x-text="job.title"></h4>
                                            <p class="text-xs font-semibold text-slate-600 mt-0.5">
                                                👤 Client: <span class="text-slate-900 font-bold" x-text="job.customer_name"></span>
                                            </p>
                                        </div>
                                        <span class="font-mono font-black text-xs text-white bg-[#f58613] px-2 py-0.5 rounded shadow-sm shrink-0" x-text="job.time"></span>
                                    </div>

                                    <div x-show="job.notes" class="p-2.5 bg-white border border-slate-100 rounded-lg text-xs font-medium text-slate-500 italic">
                                        <span class="font-bold text-slate-400 block not-italic uppercase text-[9px] tracking-wide mb-0.5">Field Instructions:</span>
                                        <span x-text="job.notes"></span>
                                    </div>

                                    <div class="flex justify-between items-center pt-2 border-t border-slate-100 text-xs">
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wide border bg-emerald-50 text-emerald-700 border-emerald-200" x-text="job.status"></span>
                                        <template x-if="job.estimate_id">
                                            <a :href="'/estimates/' + job.estimate_id" class="text-[#f58613] hover:text-orange-600 font-black uppercase text-[10px] tracking-widest flex items-center gap-1">
                                                Open Estimate →
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <div x-show="selectedDayJobs.length === 0" class="text-center py-8 text-slate-400 font-bold italic text-xs">
                                No jobs scheduled for this calendar date block.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="border-t border-slate-900 bg-black text-slate-400 py-12">
            <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

                <div class="md:col-span-5 flex flex-col items-center md:items-start gap-4">
                    <div class="w-[400px] max-w-full aspect-square bg-slate-950 border border-slate-900 rounded-2xl overflow-hidden shadow-lg flex items-center justify-center">
                        <img src="/images/footer-logo.webp" alt="Corporate Brand Mark" class="w-full h-full object-contain p-4">
                    </div>
                    <div class="text-xs font-medium text-slate-500 text-center md:text-left mt-1">
                        &copy; 2026 ContractorSpecialties.<br>
                        ContractorSpecialties is owned and operated by Contractor Service Pros LLC.<br>
                        All company databases and communication networks secure.
                    </div>
                </div>

                <div class="md:col-span-7 grid grid-cols-2 sm:grid-cols-4 gap-6 text-xs font-bold uppercase tracking-wider md:pt-4">
                    <div class="flex flex-col gap-2.5">
                        <span class="text-[10px] text-slate-600 tracking-widest font-black">Tools & System</span>
                        <a href="/estimates" class="text-slate-400 hover:text-[#f58613] transition-colors">Estimate Creator</a>
                        <a href="/pricebook" class="text-slate-400 hover:text-[#f58613] transition-colors">Pricebook Matrix</a>
                        <a href="/billing" class="text-slate-400 hover:text-[#f58613] transition-colors">Text-to-Pay Rails</a>
                    </div>
                    <div class="flex flex-col gap-2.5">
                        <span class="text-[10px] text-slate-600 tracking-widest font-black">Directories</span>
                        <a href="/advertise" class="text-slate-400 hover:text-[#f58613] transition-colors">Advertise With Us</a>
                        <a href="/contractor-directory" class="text-slate-400 hover:text-[#f58613] transition-colors">Public Directory</a>
                        <a href="/leads" class="text-slate-400 hover:text-[#f58613] transition-colors">Resource Funnels</a>
                    </div>
                    <div class="flex flex-col gap-2.5">
                        <span class="text-[10px] text-slate-600 tracking-widest font-black">Legal & Policy</span>
                        <a href="/privacy" class="text-slate-400 hover:text-[#f58613] transition-colors normal-case">Privacy Policy</a>
                        <a href="/terms" class="text-slate-400 hover:text-[#f58613] transition-colors normal-case">Terms of Use</a>
                    </div>
                    <div class="flex flex-col gap-2.5">
                        <span class="text-[10px] text-slate-600 tracking-widest font-black">Secure Entry</span>
                        <a href="/login/partner" class="text-slate-500 hover:text-white transition-colors bg-slate-900 border border-slate-800 px-3 py-2 rounded-lg text-center truncate">General Contractor</a>
                        <a href="/login/subcontractor" class="text-slate-500 hover:text-white transition-colors bg-slate-900 border border-slate-800 px-3 py-2 rounded-lg text-center truncate mt-1">Sub-Portal</a>
                        <a href="/tutorial" class="text-[#f58613] hover:text-orange-500 transition-colors normal-case mt-1.5 font-black tracking-wide italic">How-To Manual 📺</a>
                    </div>
                </div>

            </div>
        </footer>

    </div>
</body>
</html>
