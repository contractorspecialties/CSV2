<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $estimate->estimate_number }} | Estimate Details</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white"
      x-data="photoMarkupStudio()">

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
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-4 mb-4 flex items-center gap-3 shadow-sm">
                <span class="text-lg">⚡</span>
                <p class="text-xs font-black uppercase tracking-tight">{{ session('status') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-900 rounded-2xl p-4 mb-4 flex items-center gap-3 shadow-sm">
                <span class="text-lg">⚠️</span>
                <p class="text-xs font-black uppercase tracking-tight">{{ session('error') }}</p>
            </div>
        @endif

        <div class="border-b border-slate-200 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div class="flex items-center gap-4">
                @if(!empty($estimate->company?->logo_path))
                    <div class="w-16 h-16 rounded-xl border border-slate-200 bg-white p-1 flex items-center justify-center shrink-0 overflow-hidden shadow-inner bg-slate-50">
                        <img src="{{ asset($estimate->company->logo_path) }}" class="w-full h-full object-contain" alt="Contractor Brand Mark">
                    </div>
                @endif
                <div>
                    <span class="text-[10px] bg-slate-900 text-slate-300 font-mono font-black px-2 py-0.5 rounded uppercase tracking-wider">
                        Current Status: {{ $estimate->status }}
                    </span>
                    <h1 class="text-2xl font-black text-slate-950 uppercase tracking-tight mt-1">Estimate {{ $estimate->estimate_number }}</h1>
                    <p class="text-sm text-slate-500 font-medium">Customer: <strong class="text-slate-900">{{ $estimate->customer->last_name }}, {{ $estimate->customer->first_name }}</strong></p>

                    <div class="flex flex-wrap items-center gap-3 mt-1.5">
                        @if(!empty($estimate->company?->name))
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">
                                Issued By: <span class="text-slate-700 font-bold">{{ $estimate->company->name }}</span>
                            </p>
                        @endif

                        @if(!empty($estimate->company?->slug))
                            <a href="{{ route('brand.show', ['slug' => $estimate->company->slug]) }}" target="_blank" class="inline-flex items-center gap-1 bg-orange-50 hover:bg-orange-100 border border-orange-200 text-[#f58613] text-[9px] font-black tracking-widest uppercase px-2 py-0.5 rounded shadow-sm transition-all select-none cursor-pointer">
                                ✨ Verified Trust Profile &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-2.5 flex items-center gap-2 shadow-sm">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 pl-1">Quick Actions:</span>
                <form action="/estimates/{{ $estimate->id }}/status" method="POST" class="flex gap-1">
                    @csrf
                    <input type="hidden" name="status" value="sent">
                    <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-black text-[10px] px-2.5 py-1.5 rounded uppercase tracking-wide cursor-pointer transition-all border-0">
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

            <div class="lg:col-span-2 space-y-6">

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

                @if($estimate->notes && str_contains($estimate->notes, '🚨 Homeowner Modification Request:'))
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 shadow-sm space-y-4">
                        <div>
                            <span class="text-[9px] bg-amber-600 text-white font-black px-2 py-0.5 rounded uppercase tracking-wider">Pending Customer Request</span>
                            <h3 class="text-sm font-black uppercase text-slate-900 mt-2">Client Feedback History</h3>
                        </div>
                        <div class="text-xs font-medium text-slate-800 bg-white/80 p-4 border border-amber-200/60 rounded-xl whitespace-pre-wrap leading-relaxed">
                            {{ $estimate->notes }}
                        </div>

                        <form action="/estimates/{{ $estimate->id }}/blueprint" method="POST" class="space-y-3 pt-2">
                            @csrf
                            <div>
                                <label for="response_notes" class="block text-[10px] font-black uppercase text-slate-500 mb-1">Update Scope Notes / Post Your Response</label>
                                <textarea id="response_notes" name="notes" rows="3" placeholder="Type clarification or updated contract parameters here..." class="w-full bg-white border border-slate-300 rounded-xl p-3 text-xs font-medium focus:outline-none focus:border-[#f58613] text-slate-900"></textarea>
                            </div>
                            <button type="submit" class="bg-slate-950 hover:bg-black text-white font-black text-xs py-2.5 px-4 rounded-xl uppercase tracking-wider transition-all cursor-pointer border-0">
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

            <div class="space-y-6">

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider">📢 Customer Dispatch Hub</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Send the project checkout portal straight to the client.</p>
                    </div>

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

                    <div class="space-y-2 pt-2">
                        <form action="/estimates/{{ $estimate->id }}/text-dispatch" method="POST">
                            @csrf
                            <button type="submit" @empty($estimate->customer->phone) disabled @endempty class="w-full bg-[#f58613] hover:bg-orange-600 disabled:bg-slate-100 disabled:text-slate-400 text-white font-black text-xs py-3.5 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer border-0">
                                📱 Send Link via Text Message Run
                            </button>
                        </form>

                        <form action="/estimates/{{ $estimate->id }}/email-dispatch" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-black text-xs py-3.5 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer border border-slate-950">
                                📧 Send Link via Official Email Trail
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider">📸 Field Site Visual Timeline</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Upload visual proof or job status photos directly to this folder profile.</p>
                    </div>

                    <form action="/estimates/{{ $estimate->id }}/attachments" method="POST" enctype="multipart/form-data" class="space-y-3" id="attachmentForm">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Select Field Capture Image</label>
                            <input type="file" id="studioFileInput" name="image" required accept="image/*" @change="loadPhotoToStudio($event)" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-slate-950 file:text-white hover:file:bg-black file:cursor-pointer cursor-pointer border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                        </div>
                        <div>
                            <input type="text" name="caption" placeholder="Short description (e.g., Finished framing pass)" class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 px-2.5 text-xs font-medium focus:outline-none focus:border-[#f58613] text-slate-900">
                        </div>
                        <button type="submit" class="w-full bg-slate-950 hover:bg-black text-white font-black text-xs py-2.5 rounded-lg uppercase tracking-wider transition-all cursor-pointer border-0">
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

    <div x-show="showStudio" x-cloak class="fixed inset-0 z-100 bg-slate-950 flex flex-col select-none" @window:resize.debounce.200="resizeCanvas()">

        <div class="bg-slate-900 border-b border-slate-800 px-4 h-16 shrink-0 flex items-center justify-between">
            <button type="button" @click="closeStudio()" class="text-slate-400 hover:text-white font-black text-xs tracking-widest uppercase cursor-pointer bg-transparent border-0">
                &larr; Cancel
            </button>
            <div class="flex items-center gap-3">
                <button type="button" @click="undoLastShape()" class="bg-slate-800 hover:bg-slate-700 text-slate-200 font-black text-xs px-3.5 py-2 rounded-xl uppercase tracking-widest cursor-pointer transition-all border-0">
                    ↩ Undo
                </button>
                <button type="button" @click="clearStudioCanvas()" class="bg-red-950/40 text-red-400 hover:bg-red-900/40 font-black text-xs px-3.5 py-2 rounded-xl uppercase tracking-widest cursor-pointer transition-all border-0">
                    🗑️ Clear
                </button>
            </div>
            <button type="button" @click="commitStudioMarkup()" class="bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs px-5 py-2.5 rounded-xl uppercase tracking-widest shadow transition-all active:scale-95 cursor-pointer border-0">
                Save Markup ✓
            </button>
        </div>

        <div class="flex-grow relative bg-slate-950 overflow-hidden flex items-center justify-center p-2">

            <div class="absolute left-3 top-1/2 -translate-y-1/2 bg-slate-900/90 backdrop-blur-md border border-slate-800 p-2.5 rounded-2xl flex flex-col gap-4 z-10 shadow-xl">
                <div class="space-y-2">
                    <span class="block text-[8px] font-black text-slate-500 uppercase tracking-wider text-center">Size</span>
                    <button type="button" @click="thickness = 2; textSize = 14" :class="thickness === 2 ? 'border-2 border-[#f58613] bg-slate-800' : 'border border-slate-700'" class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold cursor-pointer border-0">F</button>
                    <button type="button" @click="thickness = 6; textSize = 22" :class="thickness === 6 ? 'border-2 border-[#f58613] bg-slate-800' : 'border border-slate-700'" class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold cursor-pointer border-0">M</button>
                    <button type="button" @click="thickness = 12; textSize = 32" :class="thickness === 12 ? 'border-2 border-[#f58613] bg-slate-800' : 'border border-slate-700'" class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-base font-bold cursor-pointer border-0">B</button>
                </div>
            </div>

            <div class="absolute right-3 top-1/2 -translate-y-1/2 bg-slate-900/90 backdrop-blur-md border border-slate-800 p-2.5 rounded-2xl flex flex-col gap-3 z-10 shadow-xl">
                <span class="block text-[8px] font-black text-slate-500 uppercase tracking-wider text-center">Color</span>
                <button type="button" @click="color = '#f58613'" :class="color === '#f58613' ? 'ring-2 ring-white scale-110' : ''" class="w-6 h-6 rounded-full bg-[#f58613] cursor-pointer transition-transform border-0"></button>
                <button type="button" @click="color = '#eab308'" :class="color === '#eab308' ? 'ring-2 ring-white scale-110' : ''" class="w-6 h-6 rounded-full bg-yellow-500 cursor-pointer transition-transform border-0"></button>
                <button type="button" @click="color = '#dc2626'" :class="color === '#dc2626' ? 'ring-2 ring-white scale-110' : ''" class="w-6 h-6 rounded-full bg-red-600 cursor-pointer transition-transform border-0"></button>
                <button type="button" @click="color = '#ffffff'" :class="color === '#ffffff' ? 'ring-2 ring-orange-500 scale-110' : ''" class="w-6 h-6 rounded-full bg-white border border-slate-300 cursor-pointer transition-transform"></button>
                <button type="button" @click="color = '#0f172a'" :class="color === '#0f172a' ? 'ring-2 ring-white scale-110' : ''" class="w-6 h-6 rounded-full bg-slate-900 border border-slate-800 cursor-pointer transition-transform"></button>
            </div>

            <canvas id="studioCanvas"
                    class="max-w-full max-h-full shadow-2xl bg-black block touch-none"
                    @mousedown="startDrawing($event)"
                    @mousemove="drawMove($event)"
                    @mouseup="endDrawing($event)"
                    @mouseleave="endDrawing($event)"
                    @touchstart="startDrawing($event)"
                    @touchmove="drawMove($event)"
                    @touchend="endDrawing($event)">
            </canvas>
        </div>

        <div class="bg-slate-900 border-t border-slate-800 px-4 h-20 shrink-0 flex items-center justify-center gap-1.5 sm:gap-3 overflow-x-auto">
            <button type="button" @click="tool = 'pen'" :class="tool === 'pen' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-3.5 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0">
                <span>✏️</span> Pen
            </button>
            <button type="button" @click="tool = 'line'" :class="tool === 'line' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-3.5 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0">
                <span>📏</span> Line
            </button>
            <button type="button" @click="tool = 'arrow'" :class="tool === 'arrow' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-3.5 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0">
                <span>↗️</span> Arrow
            </button>
            <button type="button" @click="tool = 'box'" :class="tool === 'box' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-3.5 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0">
                <span>⬜</span> Box
            </button>
            <button type="button" @click="tool = 'circle'" :class="tool === 'circle' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-3.5 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0">
                <span>⚪</span> Circle
            </button>
            <button type="button" @click="tool = 'text'" :class="tool === 'text' ? 'bg-[#f58613] text-white font-black' : 'bg-slate-800 text-slate-400'" class="py-2.5 px-3.5 rounded-xl text-xs uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 border-0">
                <span>🔤</span> Text
            </button>
        </div>
    </div>

    <script>
        function photoMarkupStudio() {
            return {
                showStudio: false,
                tool: 'pen',
                color: '#f58613',
                thickness: 6,
                textSize: 22,

                canvas: null,
                ctx: null,
                bgImage: null,
                isDrawing: false,
                startX: 0,
                startY: 0,

                history: [],
                currentPoints: [],

                loadPhotoToStudio(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.bgImage = new Image();
                        this.bgImage.onload = () => {
                            this.showStudio = true;
                            this.history = [];
                            this.$nextTick(() => {
                                this.initCanvasElements();
                            });
                        };
                        this.bgImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },

                initCanvasElements() {
                    this.canvas = document.getElementById('studioCanvas');
                    this.ctx = this.canvas.getContext('2d');
                    this.resizeCanvas();
                },

                resizeCanvas() {
                    if (!this.canvas || !this.bgImage) return;

                    const maxWidth = window.innerWidth * 0.90;
                    const maxHeight = window.innerHeight * 0.70;

                    let newWidth = this.bgImage.width;
                    let newHeight = this.bgImage.height;

                    const ratio = Math.min(maxWidth / newWidth, maxHeight / newHeight);

                    this.canvas.width = newWidth * ratio;
                    this.canvas.height = newHeight * ratio;

                    this.redrawCanvasWorkspace();
                },

                redrawCanvasWorkspace() {
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                    this.ctx.drawImage(this.bgImage, 0, 0, this.canvas.width, this.canvas.height);
                    this.history.forEach(shape => {
                        this.drawShapePrimitive(shape);
                    });
                },

                getCoordinates(event) {
                    let clientX, clientY;

                    if (event.touches && event.touches.length > 0) {
                        clientX = event.touches[0].clientX;
                        clientY = event.touches[0].clientY;
                    } else {
                        clientX = event.clientX;
                        clientY = event.clientY;
                    }

                    const rect = this.canvas.getBoundingClientRect();
                    return {
                        x: clientX - rect.left,
                        y: clientY - rect.top
                    };
                },

                startDrawing(event) {
                    event.preventDefault();
                    const coords = this.getCoordinates(event);
                    this.isDrawing = true;
                    this.startX = coords.x;
                    this.startY = coords.y;

                    if (this.tool === 'pen') {
                        this.currentPoints = [{ x: coords.x, y: coords.y }];
                    } else if (this.tool === 'text') {
                        this.isDrawing = false;
                        const note = prompt("Enter text instruction to place at coordinates:");
                        if (note) {
                            this.history.push({
                                type: 'text',
                                x: this.startX,
                                y: this.startY,
                                text: note,
                                color: this.color,
                                size: this.textSize
                            });
                            this.redrawCanvasWorkspace();
                        }
                    }
                },

                drawMove(event) {
                    if (!this.isDrawing) return;
                    event.preventDefault();
                    const coords = this.getCoordinates(event);

                    this.redrawCanvasWorkspace();

                    const tempShape = {
                        type: this.tool,
                        startX: this.startX,
                        startY: this.startY,
                        endX: coords.x,
                        endY: coords.y,
                        color: this.color,
                        thickness: this.thickness,
                        points: this.currentPoints
                    };

                    if (this.tool === 'pen') {
                        this.currentPoints.push({ x: coords.x, y: coords.y });
                        tempShape.points = this.currentPoints;
                    }

                    this.drawShapePrimitive(tempShape);
                },

                endDrawing(event) {
                    if (!this.isDrawing) return;
                    this.isDrawing = false;
                    event.preventDefault();

                    const coords = this.getCoordinates(event) || { x: this.startX, y: this.startY };

                    if (this.tool === 'pen') {
                        this.history.push({
                            type: 'pen',
                            points: this.currentPoints,
                            color: this.color,
                            thickness: this.thickness
                        });
                    } else if (this.tool !== 'text') {
                        this.history.push({
                            type: this.tool,
                            startX: this.startX,
                            startY: this.startY,
                            endX: coords.x,
                            endY: coords.y,
                            color: this.color,
                            thickness: this.thickness
                        });
                    }

                    this.currentPoints = [];
                    this.redrawCanvasWorkspace();
                },

                drawShapePrimitive(shape) {
                    this.ctx.strokeStyle = shape.color;
                    this.ctx.fillStyle = shape.color;
                    this.ctx.lineWidth = shape.thickness;
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';

                    this.ctx.beginPath();

                    if (shape.type === 'pen' && shape.points && shape.points.length > 0) {
                        this.ctx.moveTo(shape.points[0].x, shape.points[0].y);
                        shape.points.forEach(p => this.ctx.lineTo(p.x, p.y));
                        this.ctx.stroke();
                    }
                    else if (shape.type === 'line') {
                        this.ctx.moveTo(shape.startX, shape.startY);
                        this.ctx.lineTo(shape.endX, shape.endY);
                        this.ctx.stroke();
                    }
                    else if (shape.type === 'box') {
                        this.ctx.rect(shape.startX, shape.startY, shape.endX - shape.startX, shape.endY - shape.startY);
                        this.ctx.stroke();
                    }
                    else if (shape.type === 'circle') {
                        const radius = Math.sqrt(Math.pow(shape.endX - shape.startX, 2) + Math.pow(shape.endY - shape.startY, 2));
                        this.ctx.arc(shape.startX, shape.startY, radius, 0, 2 * Math.PI);
                        this.ctx.stroke();
                    }
                    else if (shape.type === 'text') {
                        this.ctx.font = `bold ${shape.size}px sans-serif`;
                        this.ctx.fillText(shape.text, shape.x, shape.y);
                    }
                    else if (shape.type === 'arrow') {
                        const angle = Math.atan2(shape.endY - shape.startY, shape.endX - shape.startX);
                        const headLength = Math.max(shape.thickness * 3, 15);

                        this.ctx.moveTo(shape.startX, shape.startY);
                        this.ctx.lineTo(shape.endX, shape.endY);
                        this.ctx.stroke();

                        this.ctx.beginPath();
                        this.ctx.moveTo(shape.endX, shape.endY);
                        this.ctx.lineTo(shape.endX - headLength * Math.cos(angle - Math.PI / 6), shape.endY - headLength * Math.sin(angle - Math.PI / 6));
                        this.ctx.lineTo(shape.endX - headLength * Math.cos(angle + Math.PI / 6), shape.endY - headLength * Math.sin(angle + Math.PI / 6));
                        this.ctx.closePath();
                        this.ctx.fill();
                    }
                },

                undoLastShape() {
                    if (this.history.length > 0) {
                        this.history.pop();
                        this.redrawCanvasWorkspace();
                    }
                },

                clearStudioCanvas() {
                    this.history = [];
                    this.redrawCanvasWorkspace();
                },

                closeStudio() {
                    this.showStudio = false;
                    document.getElementById('studioFileInput').value = '';
                },

                commitStudioMarkup() {
                    this.canvas.toBlob((blob) => {
                        if (!blob) return;

                        const editedFile = new File([blob], "field_markup_capture.jpg", { type: "image/jpeg" });
                        const containerExchange = new DataTransfer();
                        containerExchange.items.add(editedFile);

                        document.getElementById('studioFileInput').files = containerExchange.files;
                        this.showStudio = false;

                        alert("⚡ Photo marked up successfully! Click 'Commit Progress Asset' to save to your project file loop.");
                    }, 'image/jpeg', 0.90);
                }
            }
        }
    </script>

</body>
</html>
