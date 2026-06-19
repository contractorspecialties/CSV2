<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Portal | {{ $estimate->estimate_number }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 h-20 flex items-center justify-between">
            <div class="w-[300px] max-w-[60%] h-[80px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[70px] object-contain object-left">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] bg-emerald-950 text-emerald-400 font-mono font-black px-2 py-0.5 rounded uppercase tracking-wider">
                    Secure Client Line
                </span>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-5xl w-full mx-auto px-4 py-8 space-y-6">

        @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-5 flex items-center gap-3 shadow-sm">
                <span class="text-xl">⚡</span>
                <p class="text-xs font-black uppercase tracking-tight leading-normal">{{ session('status') }}</p>
            </div>
        @endif

        <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-md border border-slate-950 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-black uppercase tracking-tight">Project Proposal Workspace</h1>
                <p class="text-slate-400 text-xs mt-1 font-medium">Review specifications, request timeline adjustments, or activate your job schedule loop.</p>
            </div>
            <div class="text-xs font-mono font-black text-slate-400 shrink-0 bg-black/40 px-3 py-1.5 rounded-lg border border-slate-800">
                REF ID: {{ $estimate->estimate_number }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider">Itemized Scope Overview</h3>
                    </div>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase text-slate-400 bg-slate-50/20">
                                <th class="py-3 px-6">Specification Description</th>
                                <th class="py-3 px-4 text-center">Volume</th>
                                <th class="py-3 px-6 text-right">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                            @foreach($estimate->items as $item)
                                <tr>
                                    <td class="py-4 px-6 font-bold text-slate-900 text-sm leading-normal whitespace-pre-line">{{ $item->description }}</td>
                                    <td class="py-4 px-4 text-center font-mono text-slate-500">{{ number_format($item->quantity, 1) }}</td>
                                    <td class="py-4 px-6 text-right font-mono font-black text-slate-950">${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="bg-slate-50/80 border-t border-slate-100 p-6 flex justify-end">
                        <div class="w-64 font-mono text-xs text-slate-600 space-y-1.5">
                            <div class="flex justify-between">
                                <span class="font-bold text-slate-400 uppercase">Subtotal:</span>
                                <span class="font-black text-slate-900">${{ number_format($estimate->subtotal, 2) }}</span>
                            </div>
                            @if($estimate->tax_rate > 0)
                                <div class="flex justify-between">
                                    <span class="font-bold text-slate-400 uppercase">Local Tax / Assessment:</span>
                                    <span class="font-black text-slate-900">+${{ number_format($estimate->subtotal * ($estimate->tax_rate / 100), 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between pt-2 border-t border-slate-200 text-sm">
                                <span class="font-black text-slate-800 uppercase">Project Total:</span>
                                <span class="text-base font-black text-slate-950">${{ number_format($estimate->grand_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($attachments->isNotEmpty())
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                        <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider flex items-center gap-2">📸 Field Site Visual Timeline</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($attachments as $media)
                                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                                    <img src="{{ $media->file_path }}" alt="Project Documentation" class="w-full h-40 object-cover">
                                    <div class="p-2.5 bg-white text-[11px] font-medium text-slate-600">
                                        {{ $media->caption }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6">

                <div x-data="{ currentConsole: 'main' }" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm min-h-[300px] flex flex-col justify-between">

                    <div x-show="currentConsole === 'main'" class="space-y-4 contents">
                        <div class="border-b border-slate-100 pb-2">
                            <span class="text-[10px] text-[#f58613] font-black uppercase tracking-wider block">Proposal State: {{ strtoupper($estimate->status) }}</span>
                            <h3 class="font-black text-base text-slate-950 mt-0.5">Choose Next Step</h3>
                        </div>

                        @if($estimate->status !== 'approved' && $estimate->status !== 'closed')
                            <div class="space-y-2.5 flex-grow">
                                <button @click="currentConsole = 'schedule'" class="w-full bg-slate-950 hover:bg-black text-white font-black text-xs py-3.5 px-4 rounded-xl uppercase tracking-wider transition-all shadow-md flex items-center justify-between cursor-pointer">
                                    <span>✍️ Approve & Schedule</span>
                                    <span>→</span>
                                </button>

                                <button @click="currentConsole = 'revision'" class="w-full bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 font-black text-xs py-3.5 px-4 rounded-xl uppercase tracking-wider transition-all shadow-sm flex items-center justify-between cursor-pointer">
                                    <span>💬 Request Clarification</span>
                                    <span>→</span>
                                </button>
                            </div>
                        @else
                            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-center space-y-2 flex-grow flex flex-col justify-center">
                                <span class="text-2xl block">📅</span>
                                <h4 class="font-black text-sm text-emerald-950 uppercase tracking-tight">Project Active & Scheduled</h4>
                                <p class="text-xs text-emerald-800 font-medium leading-normal">This project outline is approved. The deployment mobilization routine is locked onto our active grid.</p>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-slate-100 text-[11px] font-medium text-slate-400 leading-normal text-center">
                            Need rapid coordination? Reach our central support desk any time.
                        </div>
                    </div>

                    <div x-show="currentConsole === 'schedule'" x-cloak style="display: none;" class="space-y-4 contents">
                        <div class="border-b border-slate-100 pb-2">
                            <h3 class="font-black text-sm text-slate-950">Lock In Production Window</h3>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Approving logs your project directly into our dispatch loop.</p>
                        </div>

                        <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-xs text-slate-700 space-y-1">
                            <div class="flex justify-between">
                                <span class="font-bold text-slate-400">Standard Deposit:</span>
                                <span class="font-black text-slate-900">$0.00</span>
                            </div>
                            <div class="flex justify-between text-[10px] text-slate-400">
                                <span>*Deposit due at site mobilization launch.</span>
                            </div>
                        </div>

                        <form action="/portal/action/{{ $estimate->id }}" method="POST" class="space-y-2">
                            @csrf
                            <input type="hidden" name="action" value="schedule">
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs py-3.5 px-4 rounded-xl uppercase tracking-wider transition-all shadow-md cursor-pointer">
                                Confirm & Book Installation Run ⚡
                            </button>
                            <button type="button" @click="currentConsole = 'main'" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-[10px] py-2 rounded-lg uppercase tracking-wide cursor-pointer">
                                ← Go Back
                            </button>
                        </form>
                    </div>

                    <div x-show="currentConsole === 'revision'" x-cloak style="display: none;" class="space-y-4 contents">
                        <div class="border-b border-slate-100 pb-2">
                            <h3 class="font-black text-sm text-slate-950">Request Clarification / Changes</h3>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Leave notes below. Your estimator will be immediately flagged to verify changes.</p>
                        </div>

                        <form action="/portal/action/{{ $estimate->id }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="action" value="revision">
                            <div>
                                <textarea name="notes" rows="4" required placeholder="e.g., Can we look at expanding the side pathway coverage layout, or shift execution to next Thursday afternoon?" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-xs font-medium focus:outline-none focus:border-[#f58613] leading-normal"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-slate-950 hover:bg-black text-white font-black text-xs py-3 px-4 rounded-xl uppercase tracking-wider transition-all cursor-pointer">
                                Submit Correction Note →
                            </button>
                            <button type="button" @click="currentConsole = 'main'" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-[10px] py-2 rounded-lg uppercase tracking-wide cursor-pointer">
                                Cancel
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </main>

</body>
</html>
