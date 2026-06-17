<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Your Estimate | ContractorSpecialties Portal</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-100 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-4xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[400px] max-w-[70%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-mono font-black uppercase text-slate-400 tracking-widest">Verified Secure Portal</span>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-4xl w-full mx-auto px-4 py-10 space-y-6" x-data="{ signed: false }">

        <div class="bg-white border border-slate-200 rounded-3xl shadow-xl overflow-hidden">

            <div class="p-6 sm:p-8 bg-slate-950 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-900">
                <div class="space-y-1">
                    <span class="text-[10px] font-mono font-black text-[#f58613] uppercase tracking-widest block">Job Estimate Summary</span>
                    <h1 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-white">Apex Exterior Specialists</h1>
                </div>
                <div class="text-left sm:text-right text-xs font-semibold text-slate-400 space-y-0.5">
                    <div>📱 Office Contact: (555) 019-2834</div>
                    <div>📅 Issued On: {{ now()->format('M j, Y') }}</div>
                </div>
            </div>

            <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block mb-1">Prepared For:</span>
                    <div class="text-base font-black text-slate-950">Marcus Vance</div>
                    <div class="text-xs text-slate-600 font-semibold mt-0.5">marcus.vance@domain.com</div>
                </div>
                <div class="sm:text-right">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block mb-1">Estimate Tracking Line:</span>
                    <div class="text-sm font-mono font-black text-slate-900 uppercase">EST-2026-9041</div>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-4">
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block">Project Scope Specifications</span>

                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left text-sm font-medium">
                        <thead>
                            <tr class="bg-slate-950 text-slate-400 text-xs uppercase tracking-wider font-bold">
                                <th class="py-3 px-4">Description of Work</th>
                                <th class="py-3 px-4 text-center">Qty</th>
                                <th class="py-3 px-4 text-right">Price</th>
                                <th class="py-3 px-4 text-right">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="font-black text-slate-950">Premium Exterior Siding Overhaul</div>
                                    <div class="text-xs text-slate-400 font-normal mt-0.5 italic">Includes complete surface scraping, chemical clean prep, and commercial paint sealant.</div>
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-black text-slate-700">1.0</td>
                                <td class="py-4 px-4 text-right font-mono font-bold text-slate-500">$1,850.00</td>
                                <td class="py-4 px-4 text-right font-mono font-black text-slate-950">$1,850.00</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="font-black text-slate-950">Haul-Away Fees & Trash Management</div>
                                    <div class="text-xs text-slate-400 font-normal mt-0.5 italic">Complete site clearance protection.</div>
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-black text-slate-700">1.0</td>
                                <td class="py-4 px-4 text-right font-mono font-bold text-slate-500">$350.00</td>
                                <td class="py-4 px-4 text-right font-mono font-black text-slate-950">$350.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
                    <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Contractor Scope Notes</span>
                    <p class="text-xs text-slate-600 font-semibold italic">Work will initiate precisely at 7:30 AM following material staging. All staging zones will be entirely cleared before workforce departure.</p>
                </div>

                <div class="flex flex-col items-end pt-4 font-mono text-xs text-slate-600 space-y-1.5 border-t border-slate-100">
                    <div class="flex gap-4">
                        <span class="w-36 text-right font-bold text-slate-400 uppercase">Line Item Subtotal:</span>
                        <span class="w-24 text-right font-black text-slate-900">$2,200.00</span>
                    </div>
                    <div class="flex gap-4">
                        <span class="w-36 text-right font-bold text-slate-400 uppercase">Sales Tax (6.5%):</span>
                        <span class="w-24 text-right font-black text-slate-900">+$143.00</span>
                    </div>
                    <div class="flex gap-4 text-sm pt-2 border-t border-slate-200">
                        <span class="w-36 text-right font-black text-slate-800 uppercase">Final Contract Price:</span>
                        <span class="w-24 text-right font-black text-emerald-600 text-base">$2,343.00</span>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-slate-50 border-t border-slate-100 grid grid-cols-1 md:grid-cols-12 gap-6">

                <div class="md:col-span-7 space-y-3">
                    <h3 class="text-base font-black uppercase tracking-tight text-slate-950">Draw Digital Signature to Approve</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                        By providing your trace mark inside the digital engine box, you acknowledge absolute acceptance of the line item scope frameworks and final financial contract valuations listed above.
                    </p>

                    <div class="w-full h-32 bg-white rounded-xl border border-slate-300 relative shadow-inner overflow-hidden cursor-crosshair">
                        <canvas id="sig-pad" class="absolute inset-0 w-full h-full"></canvas>
                        <div class="absolute bottom-2 right-2 flex gap-1">
                            <button type="button" id="clear-sig" class="bg-slate-100 text-slate-600 font-black text-[9px] uppercase tracking-widest px-2 py-1 rounded border border-slate-300">
                                Reset Pad
                            </button>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-5 flex flex-col justify-end">
                    <form action="#" method="POST" id="checkout-form" class="space-y-3">
                        @csrf
                        <input type="hidden" id="sig-data" name="signature_payload">

                        <button type="submit" id="submit-btn" disabled
                                class="w-full bg-[#f58613] hover:bg-orange-600 disabled:bg-slate-200 disabled:text-slate-400 font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer">
                            Accept Scope & Submit Payment ⚡
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </main>

    <footer class="border-t border-slate-900 bg-black text-slate-400 py-12 mt-auto">
        <div class="max-w-4xl mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <div class="md:col-span-5 flex flex-col items-center md:items-start gap-4">
                <div class="w-[400px] max-w-full aspect-square bg-slate-950 border border-slate-900 rounded-2xl overflow-hidden shadow-lg flex items-center justify-center">
                    <img src="/images/footer-logo.webp" alt="Corporate Brand Mark" class="w-full h-full object-contain p-4">
                </div>
                <div class="text-xs font-medium text-slate-500 text-center md:text-left mt-1">
                    &copy; 2026 ContractorSpecialties.<br>
                    Secure transactional processing routes enabled via isolated tracking layers.
                </div>
            </div>
            <div class="md:col-span-7 grid grid-cols-2 gap-6 text-xs font-bold uppercase tracking-wider md:pt-4">
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Security Frameworks</span>
                    <a href="#" class="text-slate-400 pointer-events-none">Stripe Level 1 PCI Protected</a>
                    <a href="#" class="text-slate-400 pointer-events-none">256-Bit SSL Data Encryption</a>
                </div>
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Contract Terms</span>
                    <a href="/privacy" class="text-slate-500 hover:text-[#f58613]">Privacy Protocol</a>
                    <a href="/terms" class="text-slate-500 hover:text-[#f58613]">Terms of Use</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('sig-pad');
            const clearBtn = document.getElementById('clear-sig');
            const submitBtn = document.getElementById('submit-btn');
            const sigInput = document.getElementById('sig-data');
            const ctx = canvas.getContext('2d');

            let drawing = false;
            let strokeCount = 0;

            // Normalize coordinate mapping bounds inside viewport grids
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                ctx.scale(ratio, ratio);
                ctx.lineWidth = 2.5;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#021d48';
            }
            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            function startDraw(e) {
                drawing = true;
                ctx.beginPath();
                const pos = getPos(e);
                ctx.moveTo(pos.x, pos.y);
            }

            function draw(e) {
                if (!drawing) return;
                e.preventDefault();
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
                strokeCount++;
                if (strokeCount > 10) {
                    submitBtn.removeAttribute('disabled');
                    sigInput.value = canvas.toDataURL();
                }
            }

            function stopDraw() { drawing = false; }

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return { x: clientX - rect.left, y: clientY - rect.top };
            }

            canvas.addEventListener('mousedown', startDraw);
            canvas.addEventListener('mousemove', draw);
            window.addEventListener('mouseup', stopDraw);

            canvas.addEventListener('touchstart', startDraw);
            canvas.addEventListener('touchmove', draw);
            window.addEventListener('touchend', stopDraw);

            clearBtn.addEventListener('click', () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                submitBtn.setAttribute('disabled', 'true');
                sigInput.value = '';
                strokeCount = 0;
            });
        });
    </script>
</body>
</html>
