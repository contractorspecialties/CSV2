<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Tools & Field Capabilities | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-full font-sans antialiased text-slate-800 bg-slate-50 flex flex-col justify-between selection:bg-[#f58613] selection:text-white">

    <!-- 🌐 NAVIGATION INJECT -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[280px] max-w-[50%] h-[60px] flex items-center">
                <a href="{{ route('welcome') }}"><img src="/images/header-logo.webp" alt="Logo" class="max-h-[55px] object-contain"></a>
            </div>
            <a href="/#login-anchor" class="bg-slate-950 hover:bg-slate-800 text-white font-black text-xs py-3.5 px-6 rounded-xl uppercase tracking-widest transition-all">
                Contractor Login →
            </a>
        </div>
    </header>

    <!-- 🚀 MAIN CAPABILITIES ANCHOR BODY -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-16 space-y-16">

        <div class="space-y-4 max-w-3xl text-left">
            <span class="text-xs font-black uppercase text-[#f58613] tracking-widest font-mono block">Engine Blueprint Overview</span>
            <h1 class="text-4xl md:text-5xl font-black text-slate-950 uppercase tracking-tight">Tools Mapped Out for Pure Field Velocity</h1>
            <p class="text-base md:text-lg text-slate-600 font-semibold leading-relaxed">
                This workspace is designed to eliminate double entry, streamline client records, and finalize money cycles before you pack up your tools.
            </p>
        </div>

        <!-- Dynamic Anchor Sections Skeleton Grid -->
        <div class="grid grid-cols-1 gap-12 border-t border-slate-200 pt-12">

            <!-- Target Section: Estimates -->
            <div id="estimates" class="bg-white border border-slate-200 rounded-2xl p-8 space-y-4 shadow-sm">
                <div class="text-3xl">📖</div>
                <h2 class="text-xl font-black text-slate-950 uppercase">Estimate Builder Matrix</h2>
                <p class="text-slate-600 text-sm font-semibold max-w-2xl">
                    [Placeholder Block: We will anchor your specialized pricing copy, markup calculation formulas, and proposal customization arguments right here.]
                </p>
            </div>

            <!-- Target Section: Invoices -->
            <div id="invoices" class="bg-white border border-slate-200 rounded-2xl p-8 space-y-4 shadow-sm">
                <div class="text-3xl">⚡</div>
                <h2 class="text-xl font-black text-slate-950 uppercase">Instant Single-Tap Invoicing</h2>
                <p class="text-slate-600 text-sm font-semibold max-w-2xl">
                    [Placeholder Block: We will details your immediate fulfillment run workflows and data consolidation hooks here.]
                </p>
            </div>

            <!-- Target Section: Text-To-Pay -->
            <div id="text-to-pay" class="bg-white border border-slate-200 rounded-2xl p-8 space-y-4 shadow-sm">
                <div class="text-3xl">💸</div>
                <h2 class="text-xl font-black text-slate-950 uppercase">Text-to-Pay Cashflow Rails</h2>
                <p class="text-slate-600 text-sm font-semibold max-w-2xl">
                    [Placeholder Block: We will explain your phone card signatures, compliance loops, and real-time bank deposit rails here.]
                </p>
            </div>

        </div>
    </main>

    <footer class="bg-slate-950 text-slate-500 py-8 text-center text-xs font-bold font-mono">
        &copy; 2026 ContractorSpecialties. Engineering Content Rails Stacked.
    </footer>

</body>
</html>
