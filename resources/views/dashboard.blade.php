<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Command Engine | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ openTools: false }" @click.outside="openTools = false" class="min-h-full font-sans antialiased text-slate-800 bg-slate-50 flex flex-col justify-between selection:bg-[#f58613] selection:text-white">

    <!-- 🛡️ SECURE INTERCEPT SYSTEM DISCONNECT TERMINAL BANNER -->
    @if(session()->has('admin_impersonator_id'))
        <div class="bg-amber-600 border-b border-amber-700 text-white font-sans text-center py-3.5 px-4 flex justify-between items-center z-[9999] sticky top-0 shadow-lg text-left select-none w-full shrink-0">
            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider">
                🚨 INTERCEPT ACTIVE: Currently operating within Contractor Dashboard partition view
            </div>
            <form action="{{ route('admin.impersonate.stop') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="bg-slate-950 hover:bg-slate-900 border border-slate-900 text-amber-400 font-black text-[10px] py-1.5 px-3.5 rounded-lg uppercase tracking-widest transition-all cursor-pointer shadow-md focus:outline-none">
                    Disconnect Bridge & Return &rarr;
                </button>
            </form>
        </div>
    @endif

    @php
        // Dynamic Multi-Tenant Framework Account Verification Logs
        $userTable = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $currentCompany = \Illuminate\Support\Facades\DB::table($prefix . 'companies')->where('id', auth()->user()->company_id)->first();

        $hasMerchantConfigured = !empty($currentCompany->stripe_link) || !empty($currentCompany->paypal_link) || !empty($currentCompany->zelle_handle);
        $hasReputationConfigured = !empty($currentCompany->google_review_link);
    @endphp

    <div x-data="{
        showInstallModal: false,
        showApptModal: false,
        selectedDayJobs: [],
        selectedDayName: ''
    }" class="contents">

        <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
            <div class="max-w-7xl mx-auto px-4 h-24 flex items-center justify-between">
                <div class="w-[400px] max-w-[45%] h-[100px] flex items-center">
                    <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <span class="text-slate-400 font-black text-xs uppercase tracking-widest hidden lg:inline-block">
                        {{ now()->format('l, F jS') }}
                    </span>

                    @auth
                        <a href="{{ route('workspace.profile.edit') }}" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-200 hover:text-white font-black text-[10px] py-2.5 px-3.5 sm:px-4 rounded-xl uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-sm text-decoration-none cursor-pointer">
                            <span>🎨</span>
                            <span class="hidden sm:inline">Brand Profile</span>
                        </a>

                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.index') }}" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-amber-400 hover:text-amber-300 font-black text-[10px] py-2.5 px-3.5 sm:px-4 rounded-xl uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-sm text-decoration-none cursor-pointer">
                                <span>⚙️</span>
                                <span class="hidden sm:inline">Admin Desk</span>
                            </a>
                        @endif

                        <a href="{{ route('logout') }}" class="bg-slate-900 hover:bg-red-950/40 border border-slate-800 hover:border-red-900/40 text-slate-400 hover:text-red-400 font-black text-[10px] py-2.5 px-3.5 sm:px-4 rounded-xl uppercase tracking-wider transition-all shadow-sm text-decoration-none cursor-pointer">
                            Sign Out
                        </a>
                    @endauth

                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></div>
                </div>
            </div>
        </header>

        <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-8 space-y-6">

            @if(session('status'))
                <div class="bg-emerald-600 border border-emerald-700 text-white rounded-2xl p-4 flex items-center gap-3 shadow-md">
                    <span class="text-lg">👍</span>
                    <p class="text-xs font-black uppercase tracking-tight">{{ session('status') }}</p>
                </div>
            @endif

            @if($errors->has('security'))
                <div class="bg-red-600 border border-red-700 text-white rounded-2xl p-4 flex items-center gap-3 shadow-md">
                    <span class="text-lg">🛑</span>
                    <p class="text-xs font-black uppercase tracking-tight">{{ $errors->first('security') }}</p>
                </div>
            @endif

            <!-- 🚨 MULTI-ALERT SYSTEM CONTROL BLOCKS -->
            <div class="space-y-3">
                <!-- 💳 Merchant Account Checklist Alert -->
                @if(!$hasMerchantConfigured)
                    <div class="bg-amber-50 border-2 border-amber-300 rounded-3xl p-5 flex flex-col md:flex-row justify-between items-center gap-4 shadow-sm">
                        <div class="flex items-start gap-3 text-center md:text-left flex-col md:flex-row">
                            <span class="text-2xl block mx-auto md:mx-0">⚠️</span>
                            <div>
                                <h4 class="text-sm font-black text-amber-950 uppercase tracking-tight">Direct Payout Merchant Channels Disconnected</h4>
                                <p class="text-xs text-amber-800 font-bold mt-0.5 leading-normal">Your payment collection layout is defaulted to manual check rules. Link your individual Stripe links, PayPal strings, or Zelle handles to clear customers to fund upfront project deposits directly into your bank lines.</p>
                            </div>
                        </div>
                        <a href="/workspace/billing" class="w-full md:w-auto text-center bg-amber-600 hover:bg-amber-700 text-white font-black text-[10px] py-3 px-5 rounded-xl uppercase tracking-wider text-decoration-none shadow shadow-amber-700/20 shrink-0 transition-colors">
                            Configure Merchant Channels &rarr;
                        </a>
                    </div>
                @endif

                <!-- 📡 reputation Manager Checklist Alert -->
                @if(!$hasReputationConfigured)
                    <div class="bg-blue-50 border-2 border-blue-300 rounded-3xl p-5 flex flex-col md:flex-row justify-between items-center gap-4 shadow-sm">
                        <div class="flex items-start gap-3 text-center md:text-left flex-col md:flex-row">
                            <span class="text-2xl block mx-auto md:mx-0">⭐</span>
                            <div>
                                <h4 class="text-sm font-black text-blue-950 uppercase tracking-tight">Automated Reputation Engine Inactive</h4>
                                <p class="text-xs text-blue-800 font-bold mt-0.5 leading-normal">The system review request loop is unlinked. Supply your Google Business Review link inside your Merchant Settings screen to automatically text clients direct review requests the exact second you close out active operational jobs.</p>
                            </div>
                        </div>
                        <a href="/workspace/billing" class="w-full md:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white font-black text-[10px] py-3 px-5 rounded-xl uppercase tracking-wider text-decoration-none shadow shadow-blue-700/20 shrink-0 transition-colors">
                            Activate Review Loop &rarr;
                        </a>
                    </div>
                @endif
            </div>

            <section class="grid grid-cols-3 md:grid-cols-6 gap-4">
                <a href="/estimates/create" class="relative flex flex-col items-center justify-center aspect-square bg-gradient-to-b from-[#f58613] to-orange-600 rounded-3xl shadow-md hover:shadow-xl active:scale-95 transition-all group overflow-hidden cursor-pointer text-decoration-none border-0">
                    <span class="text-4xl mb-2 group-hover:scale-110 transition-transform">📝</span>
                    <span class="text-xs font-black text-white uppercase tracking-wider text-center px-1">New Estimate</span>
                </a>

                <a href="/workspace/crm" class="relative flex flex-col items-center justify-center aspect-square bg-white border-4 border-slate-900 rounded-3xl shadow-md hover:border-[#f58613] hover:shadow-xl active:scale-95 transition-all group overflow-hidden cursor-pointer text-decoration-none">
                    <span class="text-4xl mb-2 group-hover:scale-110 transition-transform">🗂️</span>
                    <span class="text-xs font-black text-slate-950 uppercase tracking-wider text-center px-1">Client Roster</span>
                </a>

                <a href="/pricebook" class="relative flex flex-col items-center justify-center aspect-square bg-white border-2 border-slate-300 rounded-3xl shadow-md hover:border-slate-900 hover:shadow-xl active:scale-95 transition-all group overflow-hidden cursor-pointer text-decoration-none">
                    <span class="text-4xl mb-2 group-hover:scale-110 transition-transform">📖</span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider text-center px-1">Pricebook Matrix</span>
                </a>

                <a href="{{ route('workspace.billing.quick') }}" class="relative flex flex-col items-center justify-center aspect-square bg-white border-2 border-slate-300 rounded-3xl shadow-md hover:border-slate-900 hover:shadow-xl active:scale-95 transition-all group overflow-hidden cursor-pointer text-decoration-none">
                    <span class="text-4xl mb-2 group-hover:scale-110 transition-transform">⚡</span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider text-center px-1">Quick Bill</span>
                </a>

                <button @click="showInstallModal = true" class="relative flex flex-col items-center justify-center aspect-square bg-slate-900 border-2 border-slate-950 rounded-3xl shadow-md text-[#f58613] hover:shadow-xl active:scale-95 transition-all group overflow-hidden cursor-pointer outline-none">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">📱</span>
                    <span class="text-xs font-black uppercase tracking-wider text-center px-1 text-slate-200">App Shortcut</span>
                </button>

                <a href="/workspace/billing" class="relative flex flex-col items-center justify-center aspect-square bg-white border-2 border-slate-300 rounded-3xl shadow-md hover:border-slate-900 hover:shadow-xl active:scale-95 transition-all group overflow-hidden cursor-pointer text-decoration-none">
                    <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">💳</span>
                    <span class="text-xs font-black uppercase tracking-wider text-center px-1 text-slate-800">Merchant Desk</span>
                </a>
            </section>

            <section class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm grid grid-cols-1 sm:grid-cols-3 gap-6 divide-y sm:divide-y-0 sm:divide-x divide-slate-200">
                <div class="space-y-1">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">Draft Estimates</span>
                    <div class="text-4xl font-black text-slate-900 font-mono">{{ $draftCount }}</div>
                    <span class="text-xs text-slate-500 block font-bold">Quotes currently built in progress</span>
                </div>
                <div class="pt-4 sm:pt-0 sm:pl-6 space-y-1">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">Sent Estimates</span>
                    <div class="text-4xl font-black text-[#f58613] font-mono">{{ $sentCount }}</div>
                    <span class="text-xs text-slate-500 block font-bold">Dispatched customer link files</span>
                </div>
                <div class="pt-4 sm:pt-0 sm:pl-6 space-y-1">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">Booked Revenue</span>
                    <div class="text-4xl font-black text-emerald-600 font-mono">${{ number_format($bookedRevenue, 2) }}</div>
                    <span class="text-xs text-slate-500 block font-bold">Total approved production values</span>
                </div>
            </section>

            <section class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="border-b border-slate-200 pb-3">
                    <h3 class="font-black text-lg text-slate-900 uppercase tracking-wider">Production Dispatch Calendar</h3>
                    <p class="text-xs text-slate-400 font-bold">Tap any highlighted weekday node to review scheduled route stops and unroll field instructions.</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                    @foreach($daysOfWeek as $day)
                        <div @click="selectedDayJobs = {{ json_encode($day['appointments']) }}; selectedDayName = '{{ $day['full_date'] }}'; showApptModal = true;"
                             class="p-3 rounded-2xl border-2 flex flex-col justify-between h-28 transition-all cursor-pointer hover:shadow-md hover:scale-[1.02] group select-none
                            {{ $day['status'] === 'today' ? 'bg-gradient-to-b from-[#f58613] to-orange-600 border-[#f58613] text-white shadow-md ring-4 ring-[#f58613]/10' : '' }}
                            {{ $day['status'] === 'past' ? 'bg-slate-50 border-slate-200 opacity-50 text-slate-400' : '' }}
                            {{ $day['status'] === 'active' ? 'bg-slate-50 border-slate-300 text-slate-900 hover:border-slate-900' : '' }}
                            {{ $day['status'] === 'weekend' ? 'bg-slate-100/60 border-slate-200 text-slate-400 border-dashed' : '' }}
                        ">
                            <div class="flex justify-between items-baseline">
                                <span class="text-xs font-black uppercase tracking-wider {{ $day['status'] === 'today' ? 'text-white' : 'text-slate-500' }}">{{ $day['name'] }}</span>
                                <span class="text-xl font-mono font-black">{{ $day['num'] }}</span>
                            </div>
                            <div>
                                @if($day['jobs_count'] > 0)
                                    <span class="text-[9px] font-black uppercase px-1.5 py-1 rounded-lg block text-center truncate
                                        {{ $day['status'] === 'today' ? 'bg-black text-white' : 'bg-slate-900 text-white' }}
                                    ">
                                        {{ $day['jobs_count'] }} {{ $day['jobs_count'] === 1 ? 'Stop' : 'Stops' }}
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold text-slate-400 block text-center italic group-hover:text-slate-600">No Runs</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-slate-900 text-white p-4 rounded-2xl shadow-md border-b-4 border-slate-950">
                    <div>
                        <h3 class="font-black text-sm uppercase tracking-wider text-slate-100">📋 Proposal Pipeline Kanban Board</h3>
                        <p class="text-slate-400 text-[11px] font-bold">Track and update bid parameters from draft generation down through archive completion states.</p>
                    </div>
                    <span class="text-[10px] bg-slate-800 text-emerald-400 font-mono font-black px-3 py-1 rounded-xl uppercase border border-slate-700">
                        Active Bids Tracked: {{ $estimates->count() }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-start">

                    <div class="bg-slate-100 border-2 border-slate-300 rounded-2xl p-3 space-y-3 shadow-inner">
                        <div class="flex justify-between items-center bg-white px-3 py-2 rounded-xl border-2 border-slate-200 shadow-sm">
                            <span class="text-xs font-black uppercase tracking-wider text-slate-600"> 🛠️ 1. Drafts</span>
                            <span class="font-mono text-xs font-black text-slate-900 bg-slate-200 px-2 py-0.5 rounded-md">{{ count($kanbanBids['draft']) }}</span>
                        </div>
                        <div class="space-y-3 max-h-[600px] overflow-y-auto pr-0.5">
                            @forelse($kanbanBids['draft'] as $bid)
                                <div class="bg-white border-2 border-slate-300 rounded-2xl p-4 shadow-sm space-y-3 hover:border-slate-900 transition-all relative group">
                                    <a href="/estimates/{{ $bid->id }}" class="block space-y-2 text-decoration-none">
                                        <div class="flex justify-between items-start">
                                            <span class="text-[10px] font-mono font-black text-slate-400 block tracking-tight group-hover:text-[#f58613] transition-colors">{{ $bid->estimate_number }} 🔗</span>
                                            <span class="text-sm font-mono font-black text-slate-900">${{ number_format($bid->grand_total, 2) }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-sm text-slate-950 uppercase truncate mt-1">{{ $bid->customer->client_name ?? 'Unmapped Client' }}</h4>
                                            <p class="text-[10px] text-slate-400 font-bold truncate mt-0.5">Compiled {{ $bid->created_at->format('M j, Y') }}</p>
                                        </div>
                                    </a>
                                    <div class="pt-2.5 border-t border-slate-200 flex items-center justify-between gap-2">
                                        <form action="/estimates/{{ $bid->id }}/status" method="POST" class="inline-block flex-1">
                                            @csrf
                                            <input type="hidden" name="status" value="sent">
                                            <button type="submit" class="w-full bg-slate-50 border-2 border-slate-300 hover:bg-[#f58613] hover:text-white text-slate-800 text-[10px] font-black uppercase py-2 px-2 rounded-xl transition-colors cursor-pointer text-center outline-none">
                                                Send Out &rarr;
                                            </button>
                                        </form>
                                        <form action="/estimates/{{ $bid->id }}" method="POST" class="inline-block" onsubmit="return confirm('🛑 Permanently scrub this quote draft record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-50 hover:bg-red-600 border-2 border-red-200 text-red-600 hover:text-white font-black text-xs p-2 rounded-xl transition-colors cursor-pointer outline-none">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-[11px] text-slate-400 font-bold italic bg-white/50 border-2 border-dashed border-slate-200 rounded-xl">No active drafts</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-slate-100 border-2 border-slate-300 rounded-2xl p-3 space-y-3 shadow-inner">
                        <div class="flex justify-between items-center bg-white px-3 py-2 rounded-xl border-2 border-slate-200 shadow-sm">
                            <span class="text-xs font-black uppercase tracking-wider text-[#f58613]">📡 2. Dispatched</span>
                            <span class="font-mono text-xs font-black text-white bg-[#f58613] px-2 py-0.5 rounded-md">{{ count($kanbanBids['sent']) }}</span>
                        </div>
                        <div class="space-y-3 max-h-[600px] overflow-y-auto pr-0.5">
                            @forelse($kanbanBids['sent'] as $bid)
                                <div class="bg-white border-2 border-slate-300 rounded-2xl p-4 shadow-sm space-y-3 hover:border-[#f58613] transition-all group">
                                    <a href="/estimates/{{ $bid->id }}" class="block space-y-2 text-decoration-none">
                                        <div class="flex justify-between items-start">
                                            <span class="text-[10px] font-mono font-black text-slate-400 block tracking-tight">{{ $bid->estimate_number }} 🔗</span>
                                            <span class="text-sm font-mono font-black text-slate-900">${{ number_format($bid->grand_total, 2) }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-sm text-slate-950 uppercase truncate mt-1">{{ $bid->customer->client_name ?? 'Unmapped Client' }}</h4>
                                            <p class="text-[10px] text-slate-400 font-bold truncate mt-0.5">Awaiting signature approval</p>
                                        </div>
                                    </a>
                                    <div class="pt-2.5 border-t border-slate-200 flex gap-2">
                                        <form action="/estimates/{{ $bid->id }}/status" method="POST" class="inline-block flex-1">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="w-full bg-emerald-50 border-2 border-emerald-300 hover:bg-emerald-600 text-emerald-700 hover:text-white text-[10px] font-black uppercase py-2 px-2 rounded-xl transition-colors cursor-pointer text-center outline-none">
                                                ✓ Force Approve
                                            </button>
                                        </form>
                                        <form action="/estimates/{{ $bid->id }}/status" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="status" value="draft">
                                            <button type="submit" class="bg-slate-50 hover:bg-slate-200 border-2 border-slate-300 text-slate-600 font-black text-xs py-2 px-2.5 rounded-xl transition-colors cursor-pointer outline-none" title="Pull Back to Draft">
                                                &larr;
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-[11px] text-slate-400 font-bold italic bg-white/50 border-2 border-dashed border-slate-200 rounded-xl">No proposals outstanding</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-slate-100 border-2 border-slate-300 rounded-2xl p-3 space-y-3 shadow-inner">
                        <div class="flex justify-between items-center bg-white px-3 py-2 rounded-xl border-2 border-slate-200 shadow-sm">
                            <span class="text-xs font-black uppercase tracking-wider text-emerald-700">🏗️ 3. Approved</span>
                            <span class="font-mono text-xs font-black text-white bg-emerald-600 px-2 py-0.5 rounded-md">{{ count($kanbanBids['approved']) }}</span>
                        </div>
                        <div class="space-y-3 max-h-[600px] overflow-y-auto pr-0.5">
                            @forelse($kanbanBids['approved'] as $bid)
                                <div class="bg-white border-2 border-emerald-300 rounded-2xl p-4 shadow-sm space-y-3 hover:border-emerald-600 transition-all group">
                                    <a href="/estimates/{{ $bid->id }}" class="block space-y-2 text-decoration-none">
                                        <div class="flex justify-between items-start">
                                            <span class="text-[10px] font-mono font-black text-slate-400 block tracking-tight">{{ $bid->estimate_number }} 🔗</span>
                                            <span class="text-sm font-mono font-black text-emerald-600">${{ number_format($bid->grand_total, 2) }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-sm text-slate-950 uppercase truncate mt-1">{{ $bid->customer->client_name ?? 'Unmapped Client' }}</h4>
                                            <p class="text-[10px] text-emerald-600 font-black tracking-tight uppercase text-[8px] mt-1 bg-emerald-50 border border-emerald-100 inline-block px-1.5 py-0.5 rounded shadow-sm">⚡ Active Production Order</p>
                                        </div>
                                    </a>
                                    <div class="pt-2.5 border-t border-slate-200">
                                        <form action="/estimates/{{ $bid->id }}/close-job" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full bg-slate-900 border-2 border-slate-950 hover:bg-black text-white text-[10px] font-black uppercase py-2 px-2 rounded-xl transition-colors cursor-pointer text-center outline-none">
                                                📦 Close & Archive Run
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-[11px] text-slate-400 font-bold italic bg-white/50 border-2 border-dashed border-slate-200 rounded-xl">No active crew setups</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-slate-100 border-2 border-slate-300 rounded-2xl p-3 space-y-3 shadow-inner">
                        <div class="flex justify-between items-center bg-white px-3 py-2 rounded-xl border-2 border-slate-200 shadow-sm">
                            <span class="text-xs font-black uppercase tracking-wider text-slate-400">🔒 4. Archived</span>
                            <span class="font-mono text-xs font-black text-slate-500 bg-slate-200 px-2 py-0.5 rounded-md">{{ count($kanbanBids['closed']) }}</span>
                        </div>
                        <div class="space-y-3 max-h-[600px] overflow-y-auto pr-0.5">
                            @forelse($kanbanBids['closed'] as $bid)
                                <div class="bg-white/80 border-2 border-slate-200 opacity-70 rounded-2xl p-4 shadow-sm space-y-3 hover:opacity-100 transition-all">
                                    <a href="/estimates/{{ $bid->id }}" class="block space-y-2 text-decoration-none">
                                        <div class="flex justify-between items-start">
                                            <span class="text-[10px] font-mono font-black text-slate-400 block tracking-tight">{{ $bid->estimate_number }} 🔗</span>
                                            <span class="text-sm font-mono font-bold text-slate-500">${{ number_format($bid->grand_total, 2) }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-sm text-slate-700 uppercase truncate mt-1">{{ $bid->customer->client_name ?? 'Unmapped Client' }}</h4>

                                            <!-- 📈 REPUTATION EXPANSION FUNNEL TRANSPARENCY MARKERS -->
                                            @if($hasReputationConfigured)
                                                <p class="text-[10px] text-emerald-600 font-black uppercase tracking-tight text-[8px] mt-1 bg-emerald-50 border border-emerald-100 inline-block px-1.5 py-0.5 rounded shadow-sm">✓ Review Request Dispatched</p>
                                            @else
                                                <p class="text-[10px] text-amber-600 font-black uppercase tracking-tight text-[8px] mt-1 bg-amber-50 border border-amber-100 inline-block px-1.5 py-0.5 rounded shadow-sm">⚠️ Review Loop Skipped</p>
                                            @endif
                                        </div>
                                    </a>
                                    <div class="pt-2 border-t border-slate-200">
                                        <form action="/estimates/{{ $bid->id }}/status" method="POST" class="w-full">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="w-full bg-white border-2 border-slate-300 text-slate-500 text-[9px] font-black uppercase py-1.5 px-2 rounded-xl hover:bg-slate-100 transition-all cursor-pointer text-center outline-none">
                                                &larr; Return to Active
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-[11px] text-slate-400 font-bold italic bg-white/50 border-2 border-dashed border-slate-200 rounded-xl">No historical records closed</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <h3 class="font-black text-base tracking-tight text-slate-900 uppercase flex items-center gap-2">
                            🗂️ Recent Customers Logged
                        </h3>
                        <a href="/workspace/crm" class="text-xs font-black text-[#f58613] hover:text-orange-600 uppercase tracking-wider text-decoration-none">Open CRM Desk &rarr;</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-xs uppercase text-slate-400 border-b border-slate-200 font-mono font-black tracking-wider">
                                    <th class="pb-3">Customer Parameters</th>
                                    <th class="pb-3">Contact Metrics</th>
                                    <th class="pb-3 text-right">Lifetime Invoice Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-bold text-sm">
                                @forelse($recentCustomers as $customer)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-4 font-black text-slate-950 text-base uppercase tracking-tight">
                                            {{ $customer->client_name }}
                                        </td>
                                        <td class="py-4 text-xs text-slate-600 font-medium space-y-1">
                                            <div class="truncate max-w-[220px]">📧 {{ $customer->email }}</div>
                                            <div>📱 {{ $customer->phone_number }}</div>
                                        </td>
                                        <td class="py-4 text-right font-mono font-black text-slate-950 text-base">
                                            ${{ number_format($customer->lifetime_value, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-8 text-center text-slate-400 font-bold italic text-xs">
                                            No active customer profiles logged inside company directory networks yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-4">
                        <div class="border-b border-slate-200 pb-3">
                            <h3 class="font-black text-sm tracking-tight text-slate-900 uppercase">
                                🔒 Carrier Secure Entry (2FA)
                            </h3>
                            <p class="text-[11px] text-slate-400 font-bold mt-0.5">Configure your phone number to receive secure login verification tokens instantly.</p>
                        </div>

                        <form action="/user/security-phone" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 tracking-wide">Secure Mobile Line</label>
                                <input type="text" name="phone_2fa" placeholder="e.g. (919) 555-1234" value="{{ auth()->user()->phone_2fa ?? '' }}" required class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-base font-mono font-bold focus:outline-none focus:border-slate-900 text-slate-900">
                            </div>

                            <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-black text-xs py-3.5 px-4 rounded-xl uppercase tracking-wider shadow transition-all active:scale-[0.99] cursor-pointer border-0 outline-none">
                                Save Number Verified ✓
                            </button>
                        </form>
                    </div>

                    <div class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-4">
                        <div class="border-b border-slate-200 pb-3">
                            <label class="flex items-center gap-3 font-black text-sm text-slate-900 uppercase tracking-wider cursor-pointer select-none">
                                🔄 Ongoing Recurring Job Matrix
                            </label>
                        </div>
                        <div class="text-xs text-slate-400 font-bold leading-normal">
                            Multi-visit service rotation schedules track completely within client estimate line files.
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- APP SHORTCUT INSTALLATION MODAL DIAGRAM VIEW -->
        <div x-show="showInstallModal" x-cloak style="display: none;" class="fixed inset-0 z-100 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" @click="showInstallModal = false"></div>
            <div class="bg-white border-4 border-slate-950 rounded-3xl max-w-sm w-full p-6 shadow-2xl relative z-10" x-transition>
                <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-4">
                    <h3 class="text-sm font-black uppercase tracking-tight text-slate-950 font-mono">📱 Mobile Workspace Shortcut</h3>
                    <button type="button" @click="showInstallModal = false" class="text-slate-400 hover:text-slate-950 font-black text-sm bg-transparent border-0 cursor-pointer outline-none">✕</button>
                </div>
                <div class="space-y-4 text-left text-xs font-semibold text-slate-600 leading-relaxed">
                    <p>To lock this command engine straight onto your phone field layout as a standalone mobile application utility wrapper:</p>
                    <ol class="list-decimal list-inside space-y-2 font-bold pl-0.5">
                        <li>Open this web route within your phone's native browser app.</li>
                        <li>Tap the <span class="text-slate-900 font-black">"Share" (iOS)</span> or <span class="text-slate-900 font-black">"Menu" (Android)</span> command token icon.</li>
                        <li>Select <span class="text-[#f58613] font-black">"Add to Home Screen"</span> straight from the option rail.</li>
                    </ol>
                    <p class="text-[10px] text-slate-400 italic">Bypasses registration barriers and unlocks instant field workspace load speeds natively.</p>
                </div>
            </div>
        </div>

        <!-- APPOINTMENT / STOP SCHEDULE MANAGEMENT MODAL CONTAINER -->
        <div x-show="showApptModal" x-cloak style="display: none;" class="fixed inset-0 z-100 flex items-center justify-center p-4 overflow-y-auto">
            <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" @click="showApptModal = false"></div>

            <div class="bg-white border-4 border-slate-950 rounded-3xl max-w-xl w-full p-6 shadow-2xl relative z-10 max-h-[90vh] overflow-y-auto" x-transition>
                <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-4">
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-tight text-slate-950 font-mono">📋 Route Details</h3>
                        <p class="text-[10px] text-slate-400 font-bold mt-0.5" x-text="selectedDayName"></p>
                    </div>
                    <button type="button" @click="showApptModal = false" class="text-slate-400 hover:text-slate-950 font-black text-sm bg-transparent border-0 cursor-pointer outline-none">✕</button>
                </div>

                <div class="space-y-3 max-h-60 overflow-y-auto text-left">
                    <template x-for="appt in selectedDayJobs" :key="appt.id">
                        <div class="p-3 bg-slate-50 border-2 border-slate-200 rounded-xl space-y-1">
                            <div class="flex justify-between items-baseline gap-2">
                                <h4 class="font-black text-slate-900 text-sm uppercase truncate" x-text="appt.title"></h4>
                                <span class="text-[10px] font-mono font-black px-1.5 py-0.5 rounded bg-slate-900 text-white" x-text="appt.status"></span>
                            </div>
                            <p class="text-xs font-bold text-slate-600" x-text="'👤 Customer: ' + appt.customer_name"></p>
                            <p x-show="appt.notes" class="text-[11px] text-slate-500 font-medium italic bg-white p-2 rounded border border-slate-100 block whitespace-pre-line" x-text="appt.notes"></p>
                        </div>
                    </template>
                    <div x-show="selectedDayJobs.length === 0" class="text-center py-6 text-slate-400 font-bold italic text-xs">No project route allocations mapped to this workspace date.</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
