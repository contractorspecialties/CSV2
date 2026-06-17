<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
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
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-950 text-slate-100 selection:bg-amber-500 selection:text-slate-950">

    <!-- HEADER NAVIGATION -->
    <header class="bg-slate-950 border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">

            <!-- Horizontal Logo Token Matching Dashboard -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-amber-400 font-mono text-xs tracking-widest uppercase font-black shadow-inner">
                    <span>⚡</span>
                    <span>CS_PLATFORM_LOGO</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-mono font-black uppercase text-slate-500 tracking-widest">Network Secure</span>
            </div>
        </div>
    </header>

    <!-- HERO & ACCELERATED SIGN-IN AREA -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-12 md:py-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

        <!-- Value Pitch Column -->
        <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-500/10 border border-amber-500/20 rounded-full text-amber-400 text-xs font-black uppercase tracking-wider">
                ✨ Built Strictly For Field Service Contractors
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight">
                Win more jobs. <br class="hidden sm:inline">
                Get paid <span class="text-amber-500 underline decoration-amber-500/30">on the spot</span>.
            </h1>
            <p class="text-base sm:text-lg text-slate-400 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                Stop chasing paperwork and playing phone tag. Write professional estimates using your custom pricebook, send secure payment links straight to your customer's phone via text, and lock down digital signatures in seconds.
            </p>

            <!-- Bulleted Value Matrix Checklist -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm font-bold text-slate-300 pt-4 max-w-md mx-auto lg:mx-0">
                <div class="flex items-center gap-2.5">
                    <span class="text-amber-500 text-base">✓</span> Zero Password Friction
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="text-amber-500 text-base">✓</span> Built-In Profit Protectors
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="text-amber-500 text-base">✓</span> Dynamic Phone Backdrops
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="text-amber-500 text-base">✓</span> Auto-Text Review Loops
                </div>
            </div>
        </div>

        <!-- Frictionless Magic Link Access Card Box -->
        <div class="lg:col-span-5 bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl relative">
            <div class="absolute -top-3 -right-3 w-12 h-12 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-center text-xl shadow-lg">
                🔑
            </div>

            <div class="space-y-2 border-b border-slate-800 pb-4 mb-6">
                <h3 class="text-xl font-black text-white uppercase tracking-tight">Contractor Workspace</h3>
                <p class="text-xs text-slate-400 font-medium">Enter your email address below. We will instantly email you a secure magic access key link—no passwords required.</p>
            </div>

            <!-- Validation Error Fallbacks -->
            @if($errors->any())
                <div class="p-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-xs font-bold mb-4">
                    Please provide a valid registered contractor email address.
                </div>
            @endif

            @if(session('status'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-xs font-black mb-4 flex items-center gap-2">
                    <span>📨</span> {{ session('status') }}
                </div>
            @endif

            <!-- Magic Sign-In Form Core -->
            <form action="{{ route('magic.send') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-[10px] font-black uppercase text-slate-500 tracking-widest mb-1.5">Registered Email Address</label>
                    <input type="email" id="email" name="email" required autocomplete="email" placeholder="name@yourcompany.com"
                           class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white placeholder:text-slate-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 shadow-inner">
                </div>

                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow-lg shadow-amber-500/10 transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer">
                    Request Secure Access Link →
                </button>
            </form>

            <div class="pt-6 mt-6 border-t border-slate-800 text-[10px] text-slate-500 text-center font-mono uppercase tracking-wider">
                Secured via isolating sc_ tracking barriers
            </div>
        </div>
    </main>

    <!-- THREE-COLUMN PRODUCT PROOF AREA -->
    <section class="border-t border-slate-900 bg-slate-950/50 py-16">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Feature Item 1 -->
            <div class="bg-slate-900/40 p-6 rounded-2xl border border-slate-900 space-y-3">
                <div class="text-2xl">📝</div>
                <h4 class="font-black text-base text-white uppercase tracking-tight">Thumb-Friendly Estimates</h4>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    Build professional job sheets right from the field. Pull items straight from your standardized corporate pricebook with pre-calculated target margins to make sure you never accidentally underbid a contract again.
                </p>
            </div>

            <!-- Feature Item 2 -->
            <div class="bg-slate-900/40 p-6 rounded-2xl border border-slate-900 space-y-3">
                <div class="text-2xl">⚡</div>
                <h4 class="font-black text-base text-white uppercase tracking-tight">Instant Text-to-Pay</h4>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    Skip the client portal logins and password requirements. Your clients click a secure magic link in a text message, view their estimate, trace their touchscreen signature, and pay instantly via Stripe right from their mobile browser.
                </p>
            </div>

            <!-- Feature Item 3 -->
            <div class="bg-slate-900/40 p-6 rounded-2xl border border-slate-900 space-y-3">
                <div class="text-2xl">🔄</div>
                <h4 class="font-black text-base text-white uppercase tracking-tight">Automated Reviews</h4>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    The second a job is settled and paid, the app deploys an automated outbound text loop directly to your client’s cell hardware. It catches them at the point of maximum satisfaction to stack up five-star Google reviews.
                </p>
            </div>

        </div>
    </section>

    <!-- NEW DEEP SYSTEM FOOTER MATCHING INTERNAL DASHBOARD LAYOUTS -->
    <footer class="border-t border-slate-900 bg-slate-950 py-8 mt-auto">
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-6">

            <!-- Horizontal Footer Logo Space Container -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-2.5 py-1 bg-slate-900 border border-slate-800 rounded text-slate-400 font-mono text-[10px] tracking-widest uppercase font-black">
                    <span>⚡</span>
                    <span>CS_SYSTEM_CORE_FOOTER</span>
                </div>
                <div class="text-xs text-slate-500 font-medium">
                    &copy; 2026 ContractorSpecialties. All infrastructure data lanes active.
                </div>
            </div>

            <!-- Strategic Partner / Subcontractor Entry Portals -->
            <div class="flex flex-wrap items-center justify-center gap-4 text-[10px] font-black uppercase tracking-widest">
                <a href="/login/partner" class="text-slate-500 hover:text-amber-500 transition-colors bg-slate-900/50 border border-slate-900 px-3.5 py-2 rounded-lg">
                    General Contractor Portal
                </a>
                <a href="/login/subcontractor" class="text-slate-500 hover:text-amber-500 transition-colors bg-slate-900/50 border border-slate-900 px-3.5 py-2 rounded-lg">
                    Crew Login
                </a>
            </div>

        </div>
    </footer>

</body>
</html>
