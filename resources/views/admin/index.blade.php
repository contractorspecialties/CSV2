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
                <a href="/dashboard" class="bg-slate-900 hover:bg-slate-800 text-amber-400 font-black text-[10px] py-2 px-3.5 rounded-lg uppercase tracking-wider transition-all border border-slate-800 cursor-pointer">
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

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-8 space-y-8">

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
            <div class="bg-white border-slate-250 rounded-2xl p-5 shadow-md space-y-1 border-l-4 border-l-emerald-500">
                <span class="text-xs font-black uppercase tracking-wider text-slate-400 block">Network Revenue Booked</span>
                <div class="text-3xl font-black text-emerald-600 font-mono">${{ number_format($globalTelemetry['booked_revenue'], 2) }}</div>
                <span class="text-[10px] text-slate-500 block font-medium uppercase tracking-tight">Aggregated Approved Contracts</span>
            </div>
        </section>

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
                            <th class="p-3.5 text-right">System Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($users as $row)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-3.5">
                                    <div class="font-black text-slate-950 text-base">
                                        {{ $row->company->name ?? 'Unprovisioned Organization' }}
                                    </div>
                                    <div class="text-xs text-slate-400 font-mono mt-0.5">
                                        📧 {{ $row->email }} • 🗓️ Registered {{ $row->created_at->format('M j, Y') }}
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
                                    @if($row->id !== auth()->id())
                                        <form action="{{ route('admin.toggle-rights', ['id' => $row->id]) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="bg-white hover:bg-slate-950 hover:text-white border border-slate-300 text-slate-600 font-black text-[9px] py-1.5 px-2.5 rounded-lg transition-all uppercase tracking-wider cursor-pointer shadow-sm">
                                                {{ $row->is_admin ? 'Demote Rank' : 'Promote Admin' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-400 italic pr-2">Your Session Node</span>
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
