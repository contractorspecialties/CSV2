<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ContractorSpecialties | Simple Estimates & Text-to-Pay</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <!-- PITCH BLACK HIGH-CONTRAST NAVIGATION HEADER -->
    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto px-4 h-24 flex items-center justify-between">

            <!-- Precise 400x100 Brand Asset Allocation Box -->
            <div class="w-[400px] max-w-[65%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>

            <div class="flex items-center gap-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-mono font-black uppercase text-slate-500 tracking-widest hidden sm:inline">Portal Secure</span>
            </div>
        </div>
    </header>

    <!-- CLEAN HIGH-CONTRAST LIGHT PRODUCT SHOWCASE SECTION -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-12 md:py-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

        <!-- Utility Driven Value Presentation -->
        <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-900 border border-slate-800 rounded-full text-white text-xs font-black uppercase tracking-wider">
                ✨ Core Tools & Professional Resources
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-950 tracking-tight leading-tight">
                Win more jobs. <br class="hidden sm:inline">
                Get paid <span class="text-[#f58613] underline decoration-[#f58613]/30">on the spot</span>.
            </h1>
            <p class="text-base sm:text-lg text-slate-600 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                Stop chasing paperwork and playing phone tag. Write professional field estimates using your unified company pricebook, deploy text links straight to your customer's hardware device, and gather digital signatures in seconds.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm font-bold text-slate-700 pt-4 max-w-md mx-auto lg:mx-0">
                <div class="flex items-center gap-2.5">
                    <span class="text-[#f58613] text-base">✓</span> Zero Password Friction
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="text-[#f58613] text-base">✓</span> Central Directory Infrastructure
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="text-[#f58613] text-base">✓</span> Margin Profit Protectors
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="text-[#f58613] text-base">✓</span> Auto-Text Review Loops
                </div>
            </div>
        </div>

        <!-- SOLID WHITE WORKSPACE LOGIN HUB CARD -->
        <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xl relative">
            <div class="absolute -top-3 -right-3 w-12 h-12 rounded-xl bg-slate-900 text-[#f58613] border border-slate-800 flex items-center justify-center text-xl shadow-md font-bold">
                🔑
            </div>

            <div class="space-y-2 border-b border-slate-100 pb-4 mb-6">
                <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">Contractor Entry</h3>
                <p class="text-xs text-slate-500 font-medium">Input your business account email. Our system will immediately issue a secure passwordless link directly to your inbox.</p>
            </div>

            @if($errors->any())
                <div class="p-3 bg-red-50 text-red-700 border border-red-200 rounded-lg text-xs font-bold mb-4">
                    Please provide a valid registered contractor email address.
                </div>
            @endif

            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-black mb-4 flex items-center gap-2">
                    <span>📨</span> {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('magic.send') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1.5">Registered Email Address</label>
                    <input type="email" id="email" name="email" required autocomplete="email" placeholder="name@yourcompany.com"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3 px-4 text-sm font-bold text-slate-950 placeholder:text-slate-400 focus:outline-none focus:border-[#f58613] focus:ring-1 focus:ring-[#f58613] shadow-inner">
                </div>

                <button type="submit" class="w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer">
                    Request Secure Access Link →
                </button>
            </form>
        </div>
    </main>

    <!-- PITCH BLACK HIGH-CONTRAST COMPLIANCE FOOTER -->
    <footer class="border-t border-slate-900 bg-black text-slate-400 py-12">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

            <!-- Fixed 400x400 Footprint Box Area Alignment -->
            <div class="md:col-span-5 flex flex-col items-center md:items-start gap-4">
                <div class="w-[400px] max-w-full aspect-square bg-slate-950 border border-slate-900 rounded-2xl overflow-hidden shadow-lg flex items-center justify-center">
                    <img src="/images/footer-logo.webp" alt="Corporate Brand Mark" class="w-full h-full object-contain p-4">
                </div>
                <div class="text-xs font-medium text-slate-500 text-center md:text-left mt-1">
                    &copy; 2026 ContractorSpecialties.<br>
                    All communication and structural lines secure via sc_ barriers.
                </div>
            </div>

            <!-- Directory Resources & Corporate Links -->
            <div class="md:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-6 text-xs font-bold uppercase tracking-wider md:pt-4">
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Tools & Engine</span>
                    <a href="/estimates" class="text-slate-400 hover:text-[#f58613] transition-colors">Estimate Creator</a>
                    <a href="/pricebook" class="text-slate-400 hover:text-[#f58613] transition-colors">Pricebook Matrix</a>
                    <a href="/billing" class="text-slate-400 hover:text-[#f58613] transition-colors">Text-to-Pay Rails</a>
                </div>
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Directories</span>
                    <a href="/advertise" class="text-slate-400 hover:text-[#f58613] transition-colors">Advertise With Us</a>
                    <a href="/contractor-directory" class="text-slate-400 hover:text-[#f58613] transition-colors">Public Directory</a>
                    <a href="/leads" class="text-slate-400 hover:text-[#f58613] transition-colors">Resource Funnels</a>
                </div>
                <div class="flex flex-col gap-2.5 col-span-2 sm:col-span-1">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Secure Entry</span>
                    <a href="/login/partner" class="text-slate-500 hover:text-white transition-colors bg-slate-900 border border-slate-800 px-3 py-2 rounded-lg text-center truncate">General Contractor</a>
                    <a href="/login/subcontractor" class="text-slate-500 hover:text-white transition-colors bg-slate-900 border border-slate-800 px-3 py-2 rounded-lg text-center truncate mt-1">Subcontractor Portal</a>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
