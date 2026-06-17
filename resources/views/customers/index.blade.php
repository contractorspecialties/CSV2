<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col min-h-full font-sans antialiased selection:bg-amber-500 selection:text-slate-950">

    <header class="bg-slate-950 border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded bg-amber-500 flex items-center justify-center font-black text-slate-950 text-base">CS</div>
                <h2 class="font-black text-lg text-white tracking-tight">Customer <span class="text-amber-500">Directory</span></h2>
            </div>
            <a href="/dashboard" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider bg-slate-900 border border-slate-800 px-3 py-2 rounded transition-all">← Dashboard</a>
        </div>
    </header>

    <main class="flex-grow max-w-5xl w-full mx-auto px-4 py-8 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 uppercase tracking-tight">Customer Accounts</h1>
                <p class="text-sm text-slate-500 font-medium">All accounts recorded in your corporate company directory.</p>
            </div>
            <div class="flex gap-2">
                <a href="/customers/create" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs py-2.5 px-4 rounded-xl uppercase tracking-wider transition-all shadow-md active:scale-95 text-center">
                    + Add Customer
                </a>
                <a href="/customers/export" class="bg-slate-900 hover:bg-slate-800 text-white border border-slate-950 font-black text-xs py-2.5 px-4 rounded-xl uppercase tracking-wider transition-all shadow-md active:scale-95 text-center">
                    📥 Download CSV
                </a>
            </div>
        </div>

        <form action="/customers" method="GET" class="flex gap-2 max-w-md">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone number, email..."
                   class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm font-medium focus:outline-none focus:border-amber-500 shadow-sm">
            <button type="submit" class="bg-slate-950 hover:bg-black text-white px-4 py-2 rounded-lg text-xs font-black uppercase tracking-wider cursor-pointer">Filter</button>
        </form>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-950 text-slate-400 text-xs uppercase tracking-wider font-black border-b border-slate-900">
                            <th class="py-3 px-4">Customer Details</th>
                            <th class="py-3 px-4">Contact Information</th>
                            <th class="py-3 px-4 text-right">Lifetime Sales (LTV)</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="font-black text-slate-950 text-base">{{ $customer->last_name }}, {{ $customer->first_name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">ID: Custom-{{ $customer->id }}</div>
                                </td>
                                <td class="py-4 px-4 text-xs text-slate-600 space-y-0.5">
                                    <div>📧 {{ $customer->email }}</div>
                                    <div>📱 {{ $customer->phone }}</div>
                                </td>
                                <td class="py-4 px-4 text-right font-mono font-black text-base text-slate-950">
                                    ${{ number_format($customer->lifetime_value, 2) }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="/estimates/create?customer_id={{ $customer->id }}" class="text-xs bg-slate-100 hover:bg-amber-500 hover:text-slate-950 border border-slate-200 font-black py-1.5 px-3 rounded transition-all uppercase tracking-wider">
                                        📝 Write Estimate
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-slate-400 font-bold text-base">No customer accounts match your active filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
