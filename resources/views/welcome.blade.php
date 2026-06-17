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

    <!-- HERO DISPLAY HEADER NAV -->
    <header class="bg-slate-950 border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">

            <!-- Exact 350px width layout matching internal system view rules -->
            <div class="w-[350px] max-w-[60%] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-10 object-contain object-left">
            </div>

            <div class="flex items-center gap-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-mono font-black uppercase text-slate-500 tracking-widest hidden sm:inline">Secure Access Route</span>
            </div>
        </div>
    </header>

    <!-- CONTENT VALUE MATRICES & AUTHENTICATION PORTAL CARD -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-12 md:py-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

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

        <!-- PASSWORDLESS AUTH HUB CARD -->
        <div class="lg:col-span-5 bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl relative">
            <div class="absolute -top-3 -right-3 w-12 h-12 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-center text-xl shadow-lg">
                🔑
            </div>

            <div class="space-y-2 border-b border-slate-800 pb-4 mb-6">
                <h3 class="text-xl font-black text-white uppercase tracking-tight">Contractor Login</h3>
                <p class="text-xs text-slate-400 font-medium">Enter your email address below. We will instantly email you a secure magic access key link—no passwords required.</p>
            </div>

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
        </div>
    </main>

    <!-- PRODUCT PROOF DECK -->
    <section class="border-t border-slate-900 bg-slate-950/50 py-16">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-slate-900/40 p-6 rounded-2xl border border-slate-900 space-y-3">
                <div class="text-2xl">📝</div>
                <h4 class="font-black text-base text-white uppercase tracking-tight">Thumb-Friendly Estimates</h4>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    Build professional job sheets right from the field. Pull items straight from your standardized corporate pricebook with pre-calculated target margins to make sure you never accidentally underbid a contract again.
                </p>
            </div>
            <div class="bg-slate-900/40 p-6 rounded-2xl border border-slate-900 space-y-3">
                <div class="text-2xl">⚡</div>
                <h4 class="font-black text-base text-white uppercase tracking-tight">Instant Text-to-Pay</h4>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    Skip the client portal logins and password requirements. Your clients click a secure magic link in a text message, view their estimate, trace their touchscreen signature, and pay instantly via Stripe right from their mobile browser.
                </p>
            </div>
            <div class="bg-slate-900/40 p-6 rounded-2xl border border-slate-900 space-y-3">
                <div class="text-2xl">🔄</div>
                <h4 class="font-black text-base text-white uppercase tracking-tight">Automated Reviews</h4>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    The second a job is settled and paid, the app deploys an automated outbound text loop directly to your client’s cell hardware. It catches them at the point of maximum satisfaction to stack up five-star Google reviews.
                </p>
            </div>
        </div>
    </section>

    <!-- REBUILT COMPLIANCE-DRIVEN DEEP FOOTER -->
    <footer class="border-t border-slate-900 bg-slate-950 text-slate-400 py-12">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

            <!-- Left Area Anchor Node -->
            <div class="md:col-span-4 flex flex-col items-center md:items-start gap-4">
                <img src="/images/footer-logo.webp" alt="CS Platform Identity" class="w-20 h-20 rounded-xl bg-slate-900 border border-slate-800 object-contain p-2 shadow-md">
                <div class="text-xs font-medium text-slate-500 text-center md:text-left">
                    &copy; 2026 ContractorSpecialties.<br>
                    All structural data channels active.
                </div>
            </div>

            <!-- Multi-Column Link Tree Blocks -->
            <div class="md:col-span-8 grid grid-cols-2 sm:grid-cols-3 gap-6 text-xs font-bold uppercase tracking-wider">
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Legal Channels</span>
                    <a href="/privacy" class="text-slate-400 hover:text-amber-500 transition-colors">Privacy Policy</a>
                    <a href="/terms" class="text-slate-400 hover:text-amber-500 transition-colors">Terms of Use</a>
                    <a href="/compliance" class="text-slate-400 hover:text-amber-500 transition-colors">A2P Messaging Laws</a>
                </div>
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Grow With Us</span>
                    <a href="/advertise" class="text-slate-400 hover:text-amber-500 transition-colors">Advertise with Us</a>
                    <a href="/directories" class="text-slate-400 hover:text-amber-500 transition-colors">Lead Funnels</a>
                    <a href="/support" class="text-slate-400 hover:text-amber-500 transition-colors">Help Desk Support</a>
                </div>
                <div class="flex flex-col gap-2.5 col-span-2 sm:col-span-1">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Secure Gateways</span>
                    <a href="/login/partner" class="text-slate-500 hover:text-amber-400 transition-colors bg-slate-900/60 border border-slate-900 px-3 py-2 rounded-lg text-center truncate">General Contractor</a>
                    <a href="/login/subcontractor" class="text-slate-500 hover:text-amber-400 transition-colors bg-slate-900/60 border border-slate-900 px-3 py-2 rounded-lg text-center truncate mt-1">Subcontractor Access</a>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
