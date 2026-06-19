<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $estimate->estimate_number }} | Estimate Details</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[400px] max-w-[60%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>
            <a href="/dashboard" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl transition-all shadow-inner">
                ← Back to Dashboard
            </a>
        </div>
    </header>

    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-8 space-y-6">

        @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                <span class="text-lg">⚡</span>
                <p class="text-xs font-black uppercase tracking-tight">{{ session('status') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-900 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                <span class="text-lg">⚠️</span>
                <p class="text-xs font-black uppercase tracking-tight">{{ session('error') }}</p>
            </div>
        @endif

        <!-- TOP BAR: JOB HEADER & MANUAL STATUS CONTROLS -->
        <div class="border-b border-slate-200 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-[10px] bg-slate-900 text-slate-300 font-mono font-black px-2 py-0.5 rounded uppercase tracking-wider">
                    Current Status: {{ $estimate->status }}
                </span>
                <h1 class="text-2xl font-black text-slate-950 uppercase tracking-tight mt-1">Estimate {{ $estimate->estimate_number }}</h1>
                <p class="text-sm text-slate-500 font-medium">Customer: <strong class="text-slate-900">{{ $estimate->customer->last_name }}, {{ $estimate->customer->first_name }}</strong></p>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-2.5 flex items-center gap-2 shadow-sm">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 pl-1">Quick Actions:</span>
                <form action="/estimates/{{ $estimate->id }}/status" method="POST" class="flex gap-1">
                    @csrf
                    <input type="hidden" name="status" value="sent">
                    <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-black text-[10px] px-2.5 py-1.5 rounded uppercase tracking-wide cursor-pointer transition-all">
                        Mark Sent
                    </button>
                </form>
                <form action="/estimates/{{ $estimate->id }}/status" method="POST" class="flex gap-1">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200/50 font-black text-[10px] px-2.5 py-1.5 rounded uppercase tracking-wide cursor-pointer transition-all">
                        Force Approve
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT/MAIN COLUMN: ITEMS & NOTES -->
            <div class="lg:col-span-2 space-y-6">

                <!-- LINE ITEM TABLE -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider">Job Scope & Line Items</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-black uppercase text-slate-400 bg-slate-50/20">
                                    <th class="py-3 px-6">Description</th>
                                    <th class="py-3 px-4 text-center">Qty</th>
                                    <th class="py-3 px-4 text-right">Unit Price</th>
                                    <th class="py-3 px-6 text-right">Total Price</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                                @foreach($estimate->items as $item)
                                    <tr>
                                        <td class="py-4 px-6 font-bold text-slate-900 text-sm">{{ $item->description }}</td>
                                        <td class="py-4 px-4 text-center font-mono">{{ number_format($item->quantity, 2) }}</td>
                                        <td class="py-4 px-4 text-right font-mono">${{ number_format($item->unit_price, 2) }}</td>
                                        <td class="py-4 px-6 text-right font-mono font-black text-slate-950">${{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- TOTALS DRAWER -->
                    <div class="bg-slate-50/80 border-t border-slate-100 p-6 flex justify-end">
                        <div class="w-64 font-mono text-xs text-slate-600 space-y-1.5">
                            <div class="flex justify-between">
                                <span class="font-bold text-slate-400 uppercase">Subtotal:</span>
                                <span class="font-black text-slate-900">${{ number_format($estimate->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between" {{ $estimate->tax_rate == 0 ? 'style=display:none' : '' }}>
                                <span class="font-bold text-slate-400 uppercase">Tax ({{ $estimate->tax_rate }}%):</span>
                                <span class="font-black text-slate-900">+${{ number_format($estimate->subtotal * ($estimate->tax_rate / 100), 2) }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-slate-200 text-sm">
                                <span class="font-black text-slate-800 uppercase">Total:</span>
                                <span class="text-base font-black text-emerald-600">${{ number_format($estimate->grand_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CLIENT CHANGE REQUEST / QUESTION DESK -->
                @if($estimate->notes && str_contains($estimate->notes, '🚨 Homeowner Modification Request:'))
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 shadow-sm space-y-4">
                        <div>
                            <span class="text-[9px] bg-amber-600 text-white font-black px-2 py-0.5 rounded uppercase tracking-wider">Pending Customer Request</span>
                            <h3 class="text-sm font-black uppercase text-slate-900 mt-2">Client Feedback History</h3>
                        </div>
                        <div class="text-xs font-medium text-slate-800 bg-white/80 p-4 border border-amber-200/60 rounded-xl whitespace-pre-wrap leading-relaxed">
                            {{ $estimate->notes }}
                        </div>

                        <!-- FAST REPLY FORM -->
                        <form action="/estimates/{{ $estimate->id }}/blueprint" method="POST" class="space-y-3 pt-2">
                            @csrf
                            <div>
                                <label for="response_notes" class="block text-[10px] font-black uppercase text-slate-500 mb-1">Update Scope Notes / Post Your Response</label>
                                <textarea id="response_notes" name="notes" rows="3" placeholder="Type clarification or updated contract parameters here..." class="w-full bg-white border border-slate-300 rounded-xl p-3 text-xs font-medium focus:outline-none focus:border-[#f58613]"></textarea>
                            </div>
                            <button type="submit" class="bg-slate-950 hover:bg-black text-white font-black text-xs py-2.5 px-4 rounded-xl uppercase tracking-wider transition-all cursor-pointer">
                                Save Notes & Re-Send Link ⚡
                            </button>
                        </form>
                    </div>
                @else
                    @if($estimate->notes)
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-2">
                            <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Internal Project Scope Notes</span>
                            <p class="text-xs font-medium text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $estimate->notes }}</p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- RIGHT COLUMN: CHANNELS & PHOTOS -->
            <div class="space-y-6">

                <!-- UNIFIED CUSTOMER DISPATCH HUB -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider">📢 Customer Dispatch Hub</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Send the project checkout portal straight to the client.</p>
                    </div>

                    <!-- Contact Context Details -->
                    <div class="space-y-2 text-xs font-medium">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 flex justify-between items-center">
                            <div>
                                <span class="text-[9px] text-slate-400 font-black uppercase block">Mobile Target</span>
                                <span class="font-mono font-bold text-slate-900">{{ $estimate->customer->phone ?? 'No Phone Logged' }}</span>
                            </div>
                            <span class="text-[9px] {{ $estimate->customer->phone ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }} border font-mono font-black px-1.5 py-0.5 rounded uppercase">
                                {{ $estimate->customer->phone ? 'Ready' : 'Missing' }}
                            </span>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 flex justify-between items-center">
                            <div>
                                <span class="text-[9px] text-slate-400 font-black uppercase block">Email Target</span>
                                <span class="font-sans font-bold text-slate-900 truncate max-w-[150px] block">{{ $estimate->customer->email }}</span>
                            </div>
                            <span class="text-[9px] bg-emerald-50 text-emerald-700 border border-emerald-200 border font-mono font-black px-1.5 py-0.5 rounded uppercase">
                                Ready
                            </span>
                        </div>
                    </div>

                    <!-- Dispatch Routing Vectors -->
                    <div class="space-y-2 pt-2">
                        <!-- SMS DISPATCH ACTION -->
                        <form action="/estimates/{{ $estimate->id }}/text-dispatch" method="POST">
                            @csrf
                            <button type="submit" @empty($estimate->customer->phone) disabled @endempty class="w-full bg-[#f58613] hover:bg-orange-600 disabled:bg-slate-100 disabled:text-slate-400 text-white font-black text-xs py-3.5 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer">
                                📱 Send Link via Text Message Run
                            </button>
                        </form>

                        <!-- EMAIL DISPATCH ACTION -->
                        <form action="/estimates/{{ $estimate->id }}/email-dispatch" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-black text-xs py-3.5 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer border border-slate-950">
                                📧 Send Link via Official Email Trail
                            </button>
                        </form>
                    </div>
                </div>

                <!-- FIELD PROGRESS PHOTOS PORTFOLIO -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider">📸 Field Site Visual Timeline</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Upload visual proof or job status photos directly to this folder profile.</p>
                    </div>

                    <form action="/estimates/{{ $estimate->id }}/attachments" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Select Field Capture Image</label>
                            <input type="file" name="image" required accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-slate-950 file:text-white hover:file:bg-black file:cursor-pointer cursor-pointer border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                        </div>
                        <div>
                            <input type="text" name="caption" placeholder="Short description (e.g., Finished framing pass)" class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-2.5 text-xs font-medium focus:outline-none focus:border-[#f58613]">
                        </div>
                        <button type="submit" class="w-full bg-slate-950 hover:bg-black text-white font-black text-xs py-2.5 rounded-lg uppercase tracking-wider transition-all cursor-pointer">
                            Commit Progress Asset ⚡
                        </button>
                    </form>

                    <div class="pt-2 border-t border-slate-100 space-y-3">
                        @forelse($attachments as $media)
                            <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50 shadow-sm">
                                <img src="{{ $media->file_path }}" alt="Field Upload Log" class="w-full h-auto object-cover max-h-48">
                                <div class="p-2.5 bg-white text-[11px] font-medium text-slate-700 flex justify-between items-center">
                                    <span>{{ $media->caption }}</span>
                                    <span class="text-[9px] text-slate-400 font-mono font-bold">{{ $media->created_at->format('m/d H:i') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 border-2 border-dashed border-slate-200 rounded-xl text-slate-400 italic text-xs font-medium">
                                No site attachments linked yet.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>
