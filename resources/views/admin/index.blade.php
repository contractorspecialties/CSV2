<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Cockpit Command Hub | Admin Desk</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-[300px] flex items-center">
                    <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[75px] object-contain object-left">
                </div>
                <a href="/dashboard" class="bg-slate-900 hover:bg-slate-800 text-amber-400 font-black text-[10px] py-2 px-3.5 rounded-lg uppercase tracking-wider transition-all border border-slate-800 cursor-pointer text-decoration-none">
                    &larr; Operations Room
                </a>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-[10px] bg-red-950/40 border border-red-900/30 text-red-400 font-mono font-black px-3 py-1.5 rounded-xl uppercase tracking-widest shadow-inner">
                    🛑 Platform Over-Watch Terminal Active
                </span>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-8 space-y-6">

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-900 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                <span class="text-lg">🛑</span>
                <p class="text-xs font-black uppercase tracking-tight">{{ $errors->first() }}</p>
            </div>
        @endif

        @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                <span class="text-lg">⚡</span>
                <p class="text-xs font-black uppercase tracking-tight">{{ session('status') }}</p>
            </div>
        @endif

        <!-- Master Global Telemetry Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-1">
                <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">System Tenants</span>
                <div class="text-3xl font-black text-slate-900 font-mono">{{ $globalTelemetry['total_workspaces'] }}</div>
                <span class="text-[10px] text-slate-500 block font-medium uppercase tracking-tight">Provisioned Company Profiles</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-1">
                <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">Global Bids Out</span>
                <div class="text-3xl font-black text-[#f58613] font-mono">{{ $globalTelemetry['sent_bids'] }}</div>
                <span class="text-[10px] text-slate-500 block font-medium uppercase tracking-tight">Estimates Out for Signature</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-1">
                <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">Total Pipeline Specs</span>
                <div class="text-3xl font-black text-slate-900 font-mono">{{ $globalTelemetry['total_estimates'] }}</div>
                <span class="text-[10px] text-slate-500 block font-medium uppercase tracking-tight">Cumulative Compiled Estimates</span>
            </div>
            <div class="bg-white border border-slate-250 rounded-2xl p-5 shadow-md space-y-1 border-l-4 border-l-emerald-500">
                <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">Network Revenue Booked</span>
                <div class="text-3xl font-black text-emerald-600 font-mono">${{ number_format($globalTelemetry['booked_revenue'], 2) }}</div>
                <span class="text-[10px] text-slate-500 block font-medium uppercase tracking-tight">Aggregated Approved Contracts</span>
            </div>
        </section>

        <!-- 🛡️ HIGH-PRESENCE ANTI-BOT BULK CLEANER TOOL STRIP -->
        <section class="bg-white border-2 border-red-200 rounded-2xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden" x-data="{ openBulkPurge: false }">
            <div class="space-y-1 z-10">
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase text-red-600 tracking-widest font-mono">Automated Threat Mitigation Vault</span>
                </div>
                <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Stale Un-Onboarded Ghost Accounts</h3>
                <p class="text-xs text-slate-500 font-medium leading-normal max-w-xl">
                    There are currently <strong class="text-red-600 font-black font-mono bg-red-50 px-1.5 py-0.5 rounded border border-red-200/60">{{ $globalTelemetry['ghost_profiles_count'] }}</strong> transient profiles that abandoned setup over 24 hours ago. Wipe them out in a single transaction sweep to recover space allocations.
                </p>
            </div>

            <div class="shrink-0 z-10 w-full md:w-auto text-right">
                <button type="button" @click="openBulkPurge = !openBulkPurge" class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-black text-xs py-3 px-5 rounded-xl uppercase tracking-wider shadow-md border border-red-700 transition-all cursor-pointer outline-none">
                    Bulk Purge Ghost Rows 🧹
                </button>
            </div>

            <!-- Expandable confirmation block -->
            <div x-show="openBulkPurge" x-cloak x-transition class="w-full border-t border-slate-100 pt-4 mt-2 grid grid-cols-1 gap-3 text-left z-10 col-span-full" x-data="{ bulkConfirm: '' }">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                    <div class="text-xs font-black text-red-600 uppercase tracking-wide">⚠️ CRITICAL INLINE ACTION FORM: CASCADING PLATFORM PURGE</div>
                    <p class="text-[11px] text-slate-500 leading-normal">
                        Executing this command permanently drops all un-onboarded parent company partitions, their nested un-verified administrator user nodes, and any sandbox estimates generated during that cycle. This action cannot be reversed.
                    </p>
                    <form action="{{ route('admin.workspace.bulk-purge') }}" method="POST" class="flex flex-col sm:flex-row items-end gap-3 m-0">
                        @csrf
                        <div class="flex-grow max-w-sm">
                            <label class="block text-[9px] font-black uppercase text-slate-400 tracking-wider mb-1">Type <span class="text-red-500 font-mono font-black">PURGE STALE</span> to clear queues</label>
                            <input type="text" x-model="bulkConfirm" placeholder="Verification string" class="w-full bg-white border border-slate-300 focus:border-red-500 rounded-lg py-2 px-3 text-xs font-bold text-slate-950 focus:outline-none shadow-sm">
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto justify-end">
                            <button type="button" @click="openBulkPurge = false; bulkConfirm = ''" class="bg-white border border-slate-300 text-slate-500 font-black text-[10px] py-2 px-4 rounded-lg uppercase tracking-wider cursor-pointer">Abort</button>
                            <button type="submit" :disabled="bulkConfirm !== 'PURGE STALE'" class="bg-red-600 disabled:bg-slate-200 disabled:text-slate-400 disabled:border-slate-200 disabled:cursor-not-allowed text-white font-black text-[10px] py-2 px-4 rounded-lg uppercase tracking-widest shadow border border-red-700">
                                Confirm Cascade Wipe &rarr;
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Main Workspace Registry Table -->
        <section class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-black text-sm tracking-tight text-slate-900 uppercase flex items-center gap-2">
                    📊 Platform Multi-Tenant Audit Grid
                </h3>
                <span class="text-[10px] bg-slate-900 text-white font-mono font-black px-2.5 py-0.5 rounded uppercase">Master System Registry</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="text-xs uppercase text-slate-400 border-b border-slate-100 font-bold tracking-wider bg-slate-50/40">
                            <th class="p-3.5">Account Identity & Organization</th>
                            <th class="p-3.5 text-center">Trust Completeness</th>
                            <th class="p-3.5 text-center">Bids Issued</th>
                            <th class="p-3.5 text-right">Approved Value</th>
                            <th class="p-3.5 text-center">Access Rank</th>
                            <th class="p-3.5 text-right">System Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($users as $row)
                            <tr class="hover:bg-slate-50/80 transition-colors" x-data="{ openPanel: null }">
                                <td class="p-3.5">
                                    <div class="font-black text-slate-950 text-base">
                                        {{ $row->company->name ?? 'Unprovisioned Organization' }}
                                    </div>
                                    <div class="text-xs text-slate-400 font-mono mt-0.5">
                                        Workspace ID: <span class="text-slate-600 font-bold">#{{ $row->company->id ?? 'N/A' }}</span> • 📧 {{ $row->email }} • 🗓️ Registered {{ $row->created_at->format('M j, Y') }}
                                    </div>
                                </td>

                                <td class="p-3.5 text-center">
                                    <div class="w-full max-w-[100px] bg-slate-100 rounded-full h-2.5 mx-auto overflow-hidden shadow-inner border border-slate-200/50">
                                        <div class="h-full rounded-full transition-all
                                            {{ $row->profile_completion < 40 ? 'bg-red-500' : '' }}
                                            {{ $row->profile_completion >= 40 && $row->profile_completion < 80 ? 'bg-amber-500' : '' }}
                                            {{ $row->profile_completion >= 80 ? 'bg-emerald-500' : '' }}
                                        " style="width: {{ $row->profile_completion }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-mono font-black mt-1 block text-slate-500">{{ $row->profile_completion }}% Score</span>
                                </td>

                                <td class="p-3.5 text-center font-mono font-black text-slate-900">
                                    {{ $row->estimates_count }}
                                </td>

                                <td class="p-3.5 text-right font-mono font-black text-slate-950 text-base">
                                    ${{ number_format($row->booked_revenue, 2) }}
                                </td>

                                <td class="p-3.5 text-center">
                                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wide border shadow-sm
                                        {{ $row->is_admin ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}
                                    ">
                                        {{ $row->is_admin ? '👑 Super Admin' : 'Tenant Operator' }}
                                    </span>
                                </td>

                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($row->id !== auth()->id())
                                            <form action="{{ route('admin.impersonate', ['id' => $row->id]) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-black text-[9px] py-1.5 px-2.5 rounded-lg transition-all uppercase tracking-wider cursor-pointer shadow-sm border border-amber-600">
                                                    Login As
                                                </button>
                                            </form>
                                        @endif

                                        @if($row->company)
                                            <button type="button" @click="openPanel = (openPanel === 'edit' ? null : 'edit')" class="bg-slate-900 text-amber-400 font-black text-[9px] py-1.5 px-2.5 rounded-lg border border-slate-800 hover:bg-slate-800 transition-all uppercase tracking-wider cursor-pointer shadow-sm">
                                                Calibrate
                                            </button>
                                        @endif

                                        @if($row->id !== auth()->id())
                                            <button type="button" @click="openPanel = (openPanel === 'purge' ? null : 'purge')" class="border border-red-200 bg-red-50 text-red-700 font-black text-[9px] py-1.5 px-2.5 rounded-lg hover:bg-red-100 transition-all uppercase tracking-wider cursor-pointer shadow-sm">
                                                Purge
                                            </button>

                                            <form action="{{ route('admin.toggle-rights', ['id' => $row->id]) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="bg-white hover:bg-slate-100 border border-slate-300 text-slate-600 font-black text-[9px] py-1.5 px-2.5 rounded-lg transition-all uppercase tracking-wider cursor-pointer shadow-sm">
                                                    {{ $row->is_admin ? 'Demote' : 'Promote' }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-400 italic pr-2">Active Node</span>
                                        @endif
                                    </div>

                                    <!-- EXPANDABLE CALIBRATION PANEL -->
                                    @if($row->company)
                                        <div x-show="openPanel === 'edit'" x-cloak x-transition class="mt-4 p-4 bg-slate-950 border border-slate-800 rounded-xl text-left space-y-4 shadow-inner">
                                            <div class="text-xs font-black text-white uppercase tracking-wider border-b border-slate-800 pb-2">
                                                🔧 Manual Override Parameters: {{ $row->company->name }}
                                            </div>
                                            <form action="{{ route('admin.company.update', ['id' => $row->company->id]) }}" method="POST" class="space-y-4">
                                                @csrf
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-[9px] font-black uppercase text-slate-400 tracking-wider mb-1">Company Real Name</label>
                                                        <input type="text" name="name" value="{{ $row->company->name }}" required class="w-full bg-slate-900 border border-slate-800 focus:border-[#f58613] rounded-lg py-2 px-3 text-xs font-bold text-white shadow-inner focus:outline-none">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[9px] font-black uppercase text-slate-400 tracking-wider mb-1">Trade Specialty Classification</label>
                                                        <input type="text" name="trade" value="{{ $row->company->trade ?? '' }}" required class="w-full bg-slate-900 border border-slate-800 focus:border-[#f58613] rounded-lg py-2 px-3 text-xs font-bold text-white shadow-inner focus:outline-none">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-3 gap-3">
                                                    <div class="col-span-2">
                                                        <label class="block text-[9px] font-black uppercase text-slate-400 tracking-wider mb-1">Central Base (City)</label>
                                                        <input type="text" name="city" value="{{ $row->company->city ?? '' }}" required class="w-full bg-slate-900 border border-slate-800 focus:border-[#f58613] rounded-lg py-2 px-3 text-xs font-bold text-white shadow-inner focus:outline-none">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[9px] font-black uppercase text-slate-400 tracking-wider mb-1">State</label>
                                                        <input type="text" name="state" value="{{ $row->company->state ?? '' }}" required maxlength="2" class="w-full bg-slate-900 border border-slate-800 focus:border-[#f58613] rounded-lg py-2 px-3 text-xs text-center font-mono font-black uppercase text-white shadow-inner focus:outline-none">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-[9px] font-black uppercase text-slate-400 tracking-wider mb-1">Dispatch Radius (Miles)</label>
                                                        <input type="number" name="service_radius_miles" value="{{ $row->company->service_radius_miles ?? 25 }}" required class="w-full bg-slate-900 border border-slate-800 focus:border-[#f58613] rounded-lg py-2 px-3 text-xs font-mono font-bold text-white shadow-inner focus:outline-none">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[9px] font-black uppercase text-slate-400 tracking-wider mb-1">SEO Directory Status</label>
                                                        <select name="is_publicly_listed" class="w-full bg-slate-900 border border-slate-800 focus:border-[#f58613] rounded-lg py-2 px-3 text-xs font-bold text-white shadow-inner focus:outline-none">
                                                            <option value="1" {{ (isset($row->company->is_publicly_listed) && $row->company->is_publicly_listed == 1) ? 'selected' : '' }}>LIVE Listed on Directory</option>
                                                            <option value="0" {{ (isset($row->company->is_publicly_listed) && $row->company->is_publicly_listed == 0) ? 'selected' : '' }}>STAGED / Hidden Profile</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                                                    <button type="button" @click="openPanel = null" class="bg-slate-900 border border-slate-800 text-slate-400 font-black text-[9px] py-2 px-4 rounded-lg uppercase tracking-wider cursor-pointer">Cancel</button>
                                                    <button type="submit" class="bg-[#f58613] text-white font-black text-[9px] py-2 px-4 rounded-lg uppercase tracking-wider cursor-pointer shadow-md">Apply Calibration</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif

                                    <!-- EXPANDABLE PURGE PANEL (Fixed Inline Alpine Syntax Definition Block) -->
                                    @if($row->id !== auth()->id())
                                        <div x-show="openPanel === 'purge'" x-cloak x-transition class="mt-4 p-4 bg-red-950/20 border border-red-900/40 rounded-xl text-left space-y-3 shadow-inner" x-data="{ confirmWord: '' }">
                                            <div class="text-xs font-black text-red-400 uppercase tracking-wider flex items-center gap-1">
                                                ⚠️ CRITICAL MANEUVER: Cascading Clean-Sweep Purge
                                            </div>
                                            <p class="text-[11px] text-slate-400 leading-normal">
                                                This will completely delete user <span class="font-bold text-slate-900">"{{ $row->email }}"</span>, their associated corporate workspace record, and erase all child logs (estimates, clients, blueprints, pricebooks) cross-tenant. <span class="text-red-500 font-bold">This cannot be undone.</span>
                                            </p>
                                            <form action="{{ route('admin.workspace.purge', ['id' => $row->id]) }}" method="POST" class="space-y-3">
                                                @csrf
                                                <div>
                                                    <label class="block text-[9px] font-black uppercase text-slate-500 tracking-wider mb-1">Type <span class="text-red-500 font-mono font-black">DELETE</span> to confirm terminal wipe</label>
                                                    <input type="text" x-model="confirmWord" placeholder="Verification string" class="w-full bg-slate-50 border border-slate-300 focus:border-red-500 rounded-lg py-2 px-3 text-xs font-bold text-slate-950 focus:outline-none shadow-inner">
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click="openPanel = null; confirmWord = ''" class="bg-white border border-slate-300 text-slate-500 font-black text-[9px] py-2 px-4 rounded-lg uppercase tracking-wider cursor-pointer shadow-sm">Abort</button>
                                                    <button type="submit" :disabled="confirmWord !== 'DELETE'" class="bg-red-600 disabled:bg-slate-200 disabled:text-slate-400 disabled:border-slate-200 disabled:cursor-not-allowed text-white font-black text-[9px] py-2 px-4 rounded-lg uppercase tracking-wider cursor-pointer shadow-md border border-red-700">
                                                        Execute System Purge 🧹
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400 font-bold italic">
                                    No user profiles or active company registries found across the system tables.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>
