<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

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
                                </td>
                                <td class="py-4 px-6 text-[11px] text-slate-600 space-y-0.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-slate-400">📧</span> <span class="font-semibold">{{ $customer->email }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-slate-400">📱</span> <span class="font-mono font-bold text-slate-900">{{ $customer->phone ?? 'No phone string recorded' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-right font-mono font-black text-sm text-slate-950">
                                    ${{ number_format($customer->lifetime_value, 2) }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="/estimates/create?customer_id={{ $customer->id }}" class="inline-block text-[10px] bg-slate-950 hover:bg-[#f58613] text-white hover:text-white font-black py-2 px-3.5 rounded-lg transition-all uppercase tracking-wider shadow-sm">
                                        📝 Open Estimate Canvas
                                    </a>
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

</body>
</html>
