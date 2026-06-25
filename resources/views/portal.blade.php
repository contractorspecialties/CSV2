<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Proposal Portal | {{ $estimate->estimate_number }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-5xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[350px] max-w-[60%] h-[90px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[75px] object-contain object-left">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] bg-slate-900 text-slate-400 font-mono font-black px-3 py-1.5 rounded-xl uppercase tracking-widest shadow-inner">
                    🔒 Encrypted Client Link
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
            <div class="space-y-1">
                <span class="text-[9px] font-mono font-black tracking-widest text-[#f58613] uppercase bg-orange-950/40 border border-orange-900/30 px-2 py-0.5 rounded-md">Official Digital Contract</span>
                <h1 class="text-xl font-black uppercase tracking-tight pt-1">Project Proposal & Agreement Canvas</h1>
                <p class="text-slate-400 text-xs font-medium">
                    Prepared Exclusively For: <span class="text-white font-black">{{ $estimate->customer->first_name }} {{ $estimate->customer->last_name }}</span>
                    @if($estimate->items->isNotEmpty())
                        • <span class="text-slate-300 font-bold italic">Scope: {{ Str::limit($estimate->items->first()->description, 60) }}</span>
                    @endif
                </p>
            </div>
            <div class="text-xs font-mono font-black text-slate-400 shrink-0 bg-black/40 px-3 py-2 rounded-xl border border-slate-800 shadow-inner">
                BID REF ID: {{ $estimate->estimate_number }}
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4 text-center sm:text-left flex-col sm:flex-row">
                <div class="w-14 h-14 rounded-xl bg-slate-50 border border-slate-100 p-1 shrink-0 overflow-hidden flex items-center justify-center">
                    <img src="{{ !empty($estimate->company->logo_path) ? asset($estimate->company->logo_path) : asset('images/placeholder-logo.webp') }}" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="text-base font-black text-slate-950 uppercase tracking-tight">{{ $estimate->company->name ?? 'Verified Partner Contractor' }}</h2>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <span class="text-[#f58613]">★ 4.9 (42 Reviews)</span>
                        <span>•</span>
                        <span>📍 {{ $estimate->company->city ?? 'Local' }}, {{ strtoupper($estimate->company->state ?? 'USA') }}</span>
                    </div>
                </div>
            </div>
            <div class="shrink-0 w-full sm:w-auto">
                <a href="/brand/{{ !empty($estimate->company->slug) ? $estimate->company->slug : 'staged-profile' }}" target="_blank" class="block text-center bg-slate-950 hover:bg-black text-white font-black text-[10px] uppercase tracking-widest py-3 px-5 rounded-xl border border-slate-900 shadow transition-colors">
                    🌐 View Credentials & Portfolio &rarr;
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider">Itemized Project Specifications</h3>
                        <span class="text-[9px] bg-slate-200 text-slate-600 font-mono font-black px-2 py-0.5 rounded uppercase">Fulfillment Blueprint</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-black uppercase text-slate-400 bg-slate-50/20">
                                    <th class="py-3 px-6">Specification Parameter & Materials Description</th>
                                    <th class="py-3 px-4 text-center w-24">Volume</th>
                                    <th class="py-3 px-6 text-right w-32">Line Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                                @foreach($estimate->items as $item)
                                    <tr class="hover:bg-slate-50/40 transition-colors">
                                        <td class="py-4 px-6 font-bold text-slate-900 text-sm leading-normal whitespace-pre-line">
                                            <div class="flex items-center justify-between gap-4">
                                                <span>{{ $item->description }}</span>
                                                @if(isset($item->is_taxable) && $item->is_taxable)
                                                    <span class="bg-slate-100 text-slate-400 font-mono text-[8px] uppercase font-black px-1.5 py-0.5 rounded border border-slate-200 shrink-0">Taxable</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-center font-mono text-slate-500 font-bold bg-slate-50/30">{{ number_format($item->quantity, 1) }}</td>
                                        <td class="py-4 px-6 text-right font-mono font-black text-slate-950">${{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-slate-50/80 border-t border-slate-100 p-6 flex justify-end">
                        <div class="w-full sm:w-72 font-mono text-xs text-slate-600 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-400 uppercase tracking-wider">Base Scope Subtotal:</span>
                                <span class="font-black text-slate-900">${{ number_format($estimate->subtotal, 2) }}</span>
                            </div>
                            @if($estimate->tax_rate > 0)
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-slate-400 uppercase tracking-wider">Local Sales Tax ({{ number_format($estimate->tax_rate, 2) }}%):</span>
                                    <span class="font-black text-slate-900">+${{ number_format($estimate->grand_total - $estimate->subtotal, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center pt-2.5 border-t border-slate-200 text-sm">
                                <span class="font-black text-slate-800 uppercase tracking-wider">Total Certified Invoice:</span>
                                <span class="text-base font-black text-emerald-600">${{ number_format($estimate->grand_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!empty($estimate->company->warranty_details))
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200/60 rounded-2xl p-5 shadow-sm flex items-start gap-4">
                        <span class="text-2xl shrink-0">🛡️</span>
                        <div class="space-y-0.5">
                            <h4 class="text-xs font-black uppercase text-orange-950 tracking-wide">Workmanship Quality Guarantee Included</h4>
                            <p class="text-xs text-orange-900 font-semibold leading-relaxed">
                                This production blueprint is fully backed by <span class="font-black text-slate-950">{{ $estimate->company->warranty_details }}</span> terms directly protecting your property deployment lines.
                            </p>
                        </div>
                    </div>
                @endif

                @if(!empty($estimate->notes))
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-3">
                        <h4 class="font-black text-xs text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            📋 Historical Execution Specifications & Notes
                        </h4>
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 text-xs font-medium text-slate-700 leading-relaxed max-h-48 overflow-y-auto font-sans whitespace-pre-wrap shadow-inner">
                            {{ $estimate->notes }}
                        </div>
                    </div>
                @endif

                @if($attachments->isNotEmpty())
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                        <div class="border-b border-slate-100 pb-2">
                            <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider flex items-center gap-2">📸 Project Site Imagery & Visual Specs</h3>
                            <p class="text-slate-400 text-[11px] font-medium mt-0.5">Photographic parameters recorded directly from the project site layout lines.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($attachments as $media)
                                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50 shadow-sm group hover:border-slate-400 transition-all">
                                    <div class="w-full h-48 bg-black overflow-hidden relative">
                                        <img src="{{ asset($media->file_path) }}" alt="Project Documentation" class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-[1.01]">
                                    </div>
                                    <div class="p-3 bg-white text-[11px] font-bold text-slate-700 border-t border-slate-100/80 flex items-center gap-2">
                                        <span class="text-slate-400 shrink-0">📍 Tag:</span>
                                        <span class="truncate italic font-medium text-slate-600">{{ $media->caption ?? 'Field status snapshot documentation' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div x-data="{ currentConsole: 'main', sigName: '' }" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm min-h-[340px] flex flex-col justify-between border-t-4 border-t-slate-950">

                    <div x-show="currentConsole === 'main'" class="space-y-4 contents">
                        <div class="border-b border-slate-100 pb-3">
                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border shadow-sm
                                {{ $estimate->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                {{ $estimate->status === 'sent' ? 'bg-orange-50 text-orange-700 border-orange-200' : '' }}
                                {{ $estimate->status === 'draft' ? 'bg-slate-100 text-slate-700 border-slate-200' : '' }}
                                {{ $estimate->status === 'pending_deposit' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                            ">
                                Proposal Status: {{ str_replace('_', ' ', strtoupper($estimate->status)) }}
                            </span>
                            <h3 class="font-black text-base text-slate-950 mt-2 tracking-tight">Contract Authorization</h3>
                        </div>

                        @if($estimate->status !== 'approved' && $estimate->status !== 'closed')
                            <div class="space-y-2.5 flex-grow flex flex-col justify-center">
                                <button type="button" @click="currentConsole = 'schedule'" class="w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-4 rounded-xl uppercase tracking-widest transition-all shadow-md flex items-center justify-between active:scale-[0.99] cursor-pointer border-0 outline-none">
                                    <span>✍️ Approve & Authorize</span>
                                    <span class="text-sm font-black">&rarr;</span>
                                </button>

                                <button type="button" @click="currentConsole = 'revision'" class="w-full bg-white hover:bg-slate-50 border-2 border-slate-200 text-slate-800 font-black text-xs py-3.5 px-4 rounded-xl uppercase tracking-wider transition-all shadow-sm flex items-center justify-between active:scale-[0.99] cursor-pointer outline-none">
                                    <span>💬 Request Scope Revisions</span>
                                    <span class="text-sm font-black">&rarr;</span>
                                </button>
                            </div>
                        @endif

                        @if($estimate->status === 'approved' || $estimate->status === 'closed')
                            <div class="p-5 bg-emerald-50 border border-emerald-100 rounded-xl text-center space-y-2 flex-grow flex flex-col justify-center shadow-inner">
                                <span class="text-3xl block animate-bounce">⚡</span>
                                <h4 class="font-black text-sm text-emerald-950 uppercase tracking-tight">Contract Active & Locked</h4>
                                <p class="text-[11px] text-emerald-800 font-medium leading-relaxed">This production plan is formally approved. Material scheduling and mobilization assets have been safely allocated to the dispatch queue.</p>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-slate-100 text-[10px] font-bold text-slate-400 leading-normal text-center uppercase tracking-wide">
                            Need immediate dispatch support?<br>
                            <span class="text-slate-600">Contact your estimator via the live terminal notes.</span>
                        </div>
                    </div>

                    <div x-show="currentConsole === 'schedule'" x-cloak style="display: none;" class="space-y-4 contents">
                        <div class="border-b border-slate-100 pb-2">
                            <h3 class="font-black text-sm text-slate-950 uppercase tracking-wider">Review Terms & Authorize</h3>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Authorizing registers your job contract straight into our crew scheduling layout loops.</p>
                        </div>

                        <div class="p-4 rounded-xl border font-mono text-xs space-y-1 shadow-inner bg-slate-50 border-slate-200">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-400 uppercase">Mobilization Deposit:</span>
                                <span class="font-black text-base {{ $estimate->deposit_amount > 0 ? 'text-amber-600' : 'text-slate-900' }}">
                                    ${{ number_format($estimate->deposit_amount, 2) }}
                                </span>
                            </div>

                            @if($estimate->deposit_amount > 0)
                                <div class="pt-2 mt-1 border-t border-slate-200 text-[10px] text-amber-700 font-sans font-bold leading-normal">
                                    ⚠️ Upfront mobilization funding is required to secure your field production run placement window.
                                </div>
                            @endif
                            @if(empty($estimate->deposit_amount) || $estimate->deposit_amount <= 0)
                                <div class="pt-2 mt-1 border-t border-slate-200 text-[10px] text-slate-400 font-sans font-medium leading-normal italic">
                                    *No upfront deposit required for initial site setup.
                                </div>
                            @endif
                        </div>

                        <form action="/portal/action/{{ $estimate->id }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="action" value="schedule">

                            <div>
                                <label class="block text-[9px] font-black uppercase text-slate-400 mb-1 tracking-wider">Digital Signature Sign-off</label>
                                <input type="text" name="signature_name" x-model="sigName" required placeholder="Type your full legal name to authorize..." class="w-full bg-slate-50 border border-slate-300 focus:border-emerald-600 rounded-xl py-3 px-3.5 text-xs font-bold uppercase placeholder:normal-case tracking-wide focus:outline-none shadow-inner text-slate-900">
                                <p class="text-[9px] text-slate-400 font-medium mt-1 leading-normal">By signing, you agree to the project specs and line items detailed inside this contract framework.</p>
                            </div>

                            <button type="submit" :disabled="sigName.trim().length < 3" class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-100 disabled:text-slate-400 text-white font-black text-xs py-4 px-4 rounded-xl uppercase tracking-widest transition-all shadow-md active:scale-[0.99] cursor-pointer text-center border-0 outline-none">
                                @if($estimate->deposit_amount > 0)
                                    Sign Contract & Pay Deposit 💳
                                @else
                                    Sign Contract & Book Job ⚡
                                @endif
                            </button>

                            <button type="button" @click="currentConsole = 'main'" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-500 font-black text-[10px] py-2.5 rounded-xl uppercase tracking-wide cursor-pointer text-center transition-colors border-0 outline-none">
                                &larr; Go Back
                            </button>
                        </form>
                    </div>

                    <div x-show="currentConsole === 'revision'" x-cloak style="display: none;" class="space-y-4 contents">
                        <div class="border-b border-slate-100 pb-2">
                            <h3 class="font-black text-sm text-slate-950 uppercase tracking-wider">Request Scope Revisions</h3>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Drop your adjustment instructions below. Your estimator will be immediately flagged over carrier text networks to drop revisions.</p>
                        </div>

                        <form action="/portal/action/{{ $estimate->id }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="action" value="revision">
                            <div>
                                <label class="block text-[9px] font-black uppercase text-slate-400 mb-1 tracking-wider">Adjustment Details</label>
                                <textarea name="notes" rows="5" required placeholder="e.g., The raw footprint layout lines look solid. Can we adjust line item #2 to expand paving pathway scope, and look into pulling scheduling up to next Tuesday morning?" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-xs font-medium focus:outline-none focus:border-[#f58613] leading-normal shadow-inner text-slate-800"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-slate-950 hover:bg-black text-white font-black text-xs py-3.5 px-4 rounded-xl uppercase tracking-wider transition-all shadow-md active:scale-[0.99] cursor-pointer text-center border-0 outline-none">
                                Submit Revision Instructions &rarr;
                            </button>
                            <button type="button" @click="currentConsole = 'main'" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-500 font-black text-[10px] py-2.5 rounded-xl uppercase tracking-wide cursor-pointer text-center transition-colors border-0 outline-none">
                                Cancel Revisions
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </main>

</body>
</html>
