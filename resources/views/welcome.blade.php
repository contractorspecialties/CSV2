<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-200">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Command Engine | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-full font-sans antialiased text-slate-200 bg-slate-950 flex flex-col justify-between selection:bg-[#f58613] selection:text-white">

    <!-- 🌐 TOP GLOBAL HEADER NAV RAIL -->
    <header class="bg-black/40 border-b border-slate-900 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <div class="w-[280px] max-w-[50%] h-[60px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[50px] object-contain object-left">
            </div>
            <div class="flex items-center gap-4">
                <a href="#features" class="text-xs font-black uppercase text-slate-400 hover:text-white tracking-wider transition-colors text-decoration-none hidden md:inline-block">Capabilities</a>
                <a href="#pricing" class="text-xs font-black uppercase text-slate-400 hover:text-white tracking-wider transition-colors text-decoration-none hidden md:inline-block">Pricing Matrix</a>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <a href="#login-anchor" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-200 font-black text-[10px] py-2.5 px-4 rounded-xl uppercase tracking-wider transition-all shadow-md text-decoration-none">
                    Command Console Login →
                </a>
            </div>
        </div>
    </header>

    <!-- 🚀 HERO & RESILIENT ACCESS ROUTING INTERFACE CONTAINER -->
    <section class="relative bg-gradient-to-b from-slate-900 via-slate-950 to-black pt-16 pb-20 border-b border-slate-900 px-4">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Hero Left Value Pitch Column -->
            <div class="lg:col-span-7 space-y-6 text-left">
                <div class="inline-flex items-center gap-2 bg-slate-900 border border-slate-800 rounded-full py-1 px-3 shadow-inner">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#f58613] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#f58613]"></span>
                    </span>
                    <span class="text-[9px] font-black uppercase tracking-widest text-[#f58613]">Operational System Status Active</span>
                </div>

                <h1 class="text-3xl md:text-5xl font-black text-white uppercase tracking-tight leading-none max-w-2xl">
                    Run Your Field Business Like a Pro — <span class="text-[#f58613]">Without Paying Enterprise Prices</span>
                </h1>

                <p class="text-sm md:text-base text-slate-400 font-medium leading-relaxed max-w-xl">
                    Estimate, invoice, message customers, track jobs, and get paid — all from one simple tool built for the owner who still answers the phone.
                </p>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 pt-2">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-8 rounded-xl tracking-widest uppercase shadow-lg transition-all active:scale-[0.99] text-center text-decoration-none border-0 cursor-pointer">
                        Get Started Free →
                    </a>
                    <div class="text-left">
                        <div class="text-xs font-black text-white uppercase tracking-wide">It's a lot cheaper than you think.</div>
                        <div class="text-[10px] text-slate-500 font-semibold mt-0.5">No credit card required to build workspace. No nonsense.</div>
                    </div>
                </div>
            </div>

            <!-- Operator Login Right Panel Column -->
            <div id="login-anchor" class="lg:col-span-5 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 md:p-8 space-y-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-[#f58613]/10 to-transparent rounded-bl-full pointer-events-none"></div>

                <div class="space-y-1">
                    <span class="text-[9px] font-black text-[#f58613] uppercase tracking-widest block font-mono">Secure Token Dispatch</span>
                    <h2 class="text-lg font-black text-white uppercase tracking-tight">Operator Dashboard Access</h2>
                    <p class="text-[11px] text-slate-400 leading-normal font-medium">
                        Enter your business email. We’ll send a secure token link that drops you straight into your command center.
                    </p>
                </div>

                @if(session('errors'))
                    <div class="p-3 bg-red-950/40 border border-red-900/60 text-red-400 rounded-xl text-[11px] font-bold shadow-inner">
                        🛑 {{ session('errors')->first() }}
                    </div>
                @endif

                <form action="{{ route('login.magic') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label for="login_email" class="block text-[9px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Business Registration Email</label>
                        <input type="email" id="login_email" name="email" required placeholder="name@yourcompany.com" value="{{ old('email') }}" autocomplete="email"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none placeholder:text-slate-600">
                    </div>

                    <button type="submit" class="w-full bg-slate-950 hover:bg-black border border-slate-800 hover:border-slate-700 text-white font-black text-xs py-3.5 px-4 rounded-xl uppercase tracking-widest shadow transition-all active:scale-[0.99] cursor-pointer outline-none">
                        Request Access Link ⚡
                    </button>
                </form>

                <p class="text-[9px] text-slate-500 font-medium leading-normal text-left pt-1 border-t border-slate-800/60">
                    <span class="font-black uppercase block tracking-wide text-slate-400 mb-0.5">SMS Disclosure notice:</span>
                    By requesting an access link, you agree to receive transactional account confirmations and system alerts from ContractorSpecialties. Msg/data rates may apply. Reply STOP to opt out.
                </p>

                <div class="pt-2 text-center text-xs font-bold text-slate-400 border-t border-slate-800/60">
                    New partner? <a href="{{ route('register') }}" class="text-[#f58613] font-black underline hover:text-orange-500 transition-colors">Provision your workspace →</a>
                </div>
            </div>

        </div>
    </section>

    <!-- 🛠️ HIGH-UTILITY FUNCTIONAL VALUE MATRIX SECTION -->
    <section id="features" class="max-w-7xl w-full mx-auto px-4 py-20 space-y-10 text-center">
        <div class="space-y-2 max-w-2xl mx-auto">
            <span class="text-[10px] font-black uppercase text-[#f58613] tracking-widest font-mono block">Engine Core Features Matrix</span>
            <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tight">Everything You Need to Run the Field — Without the Friction</h2>
            <div class="h-1 w-12 bg-[#f58613] mx-auto rounded-full mt-3"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-4">

            <!-- Card 1 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 text-left space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:border-slate-700 shadow-md">
                <span class="text-2xl block select-none">📖</span>
                <h3 class="text-sm font-black text-white uppercase tracking-wide">Build Estimates in Minutes</h3>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    No spreadsheets. No guesswork. No “I’ll get that to you tonight.” Just clean, consistent estimates built from your pricebook matrix.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 text-left space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:border-slate-700 shadow-md">
                <span class="text-2xl block select-none">⚡</span>
                <h3 class="text-sm font-black text-white uppercase tracking-wide">Convert Estimates to Invoices Instantly</h3>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    One tap. No retyping. No double entry. Push active dispatch metrics directly down the production payment track immediately.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 text-left space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:border-slate-700 shadow-md">
                <span class="text-2xl block select-none">💬</span>
                <h3 class="text-sm font-black text-white uppercase tracking-wide">Quick Messaging to Customers</h3>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    Send updates, reminders, approvals, and secure payment links right from the field terminal straight to client devices.
                </p>
            </div>

            <!-- Card 4 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 text-left space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:border-slate-700 shadow-md">
                <span class="text-2xl block select-none">📸</span>
                <h3 class="text-sm font-black text-white uppercase tracking-wide">Capture & Send Job Photos</h3>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    Snap photos, mark them up, attach them to estimates or invoices — protect your margins and your operational sanity.
                </p>
            </div>

            <!-- Card 5 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 text-left space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:border-slate-700 shadow-md">
                <span class="text-2xl block select-none">📅</span>
                <h3 class="text-sm font-black text-white uppercase tracking-wide">Calendar Reminders & Job Tracking</h3>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    Never forget a follow-up or route stop appointment again. Lock your daily dispatch layout density down into precise visual nodes.
                </p>
            </div>

            <!-- Card 6 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 text-left space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:border-slate-700 shadow-md">
                <span class="text-2xl block select-none">➕</span>
                <h3 class="text-sm font-black text-white uppercase tracking-wide">On-the-Spot Add-On Invoices</h3>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    Customer wants “one more thing”? Tap, add, send — capture those field changes and get paid before you leave the work site.
                </p>
            </div>

            <!-- Card 7 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 text-left space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:border-slate-700 shadow-md">
                <span class="text-2xl block select-none">💸</span>
                <h3 class="text-sm font-black text-white uppercase tracking-wide">Text-to-Pay for Faster Cashflow</h3>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    Your customer taps, signs, pays. No complicated consumer portals. No chasing bad paper checks around town.
                </p>
            </div>

            <!-- Card 8 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 text-left space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:border-slate-700 shadow-md lg:col-span-2">
                <span class="text-2xl block select-none">📈</span>
                <h3 class="text-sm font-black text-white uppercase tracking-wide">Automated Review Collection</h3>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    Timed review requests boost your local Google Map footprint automatically without begging, linking social verification nodes right on completion.
                </p>
            </div>

        </div>
    </section>

    <!-- 🌐 SEMANTIC HTMl PUBLIC DIRECTORY REPUTATION MODULE -->
    <section class="border-t border-b border-slate-900 bg-slate-950 px-4 py-20 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#f58613/4%_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
        <div class="max-w-4xl mx-auto space-y-8 relative z-10">
            <div class="space-y-2">
                <span class="text-[10px] font-black uppercase text-[#f58613] tracking-widest font-mono block">Programmatic SEO Framework</span>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tight">Your Business Profile — Clean, Search-Friendly, and Trustworthy</h2>
                <div class="h-1 w-12 bg-[#f58613] mx-auto rounded-full mt-3"></div>
            </div>

            <p class="text-sm md:text-base text-slate-400 font-medium leading-relaxed max-w-2xl mx-auto">
                Every account includes a public profile showcasing your services, reviews, service area, and contact info. It’s not a new website — it’s a simple, credible place for customers to learn who you are.
            </p>

            <!-- Styled Public Component Mock Template Matrix -->
            <div class="w-full max-w-xl mx-auto bg-slate-900 border border-slate-800 rounded-2xl p-5 text-left shadow-2xl font-sans space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-800 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-center text-xl font-bold select-none text-[#f58613]">🏗️</div>
                        <div>
                            <h4 class="text-sm font-black text-white uppercase tracking-wide">Apex Roofing Architecture</h4>
                            <p class="text-[10px] text-slate-500 font-mono">📍 Washington, NC • Verified Active Profile</p>
                        </div>
                    </div>
                    <div class="bg-emerald-950/40 border border-emerald-900/60 rounded-lg px-2.5 py-1 text-[9px] font-mono font-black text-emerald-400 uppercase tracking-wider shadow-inner">
                        ✓ General Liability Insured
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-[10px] font-mono font-black">
                    <div class="bg-slate-950 border border-slate-800/80 p-2.5 rounded-xl"><span class="text-slate-500 block mb-0.5 uppercase text-[8px]">Exp</span>15+ Years</div>
                    <div class="bg-slate-950 border border-slate-800/80 p-2.5 rounded-xl"><span class="text-slate-500 block mb-0.5 uppercase text-[8px]">Scope</span>25 Mi Radius</div>
                    <div class="bg-slate-950 border border-slate-800/80 p-2.5 rounded-xl"><span class="text-slate-500 block mb-0.5 uppercase text-[8px]">Reviews</span>⭐⭐⭐⭐★ (4.9)</div>
                </div>
                <div class="text-[11px] text-slate-400 font-medium leading-relaxed bg-slate-950/60 p-3 rounded-xl border border-slate-800/40 italic">
                    "Specializing in full tear-off replacements and emergency structural storm maintenance across North Carolina..."
                </div>
            </div>
        </div>
    </section>

    <!-- 👤 IDENTITY & MISSION SYSTEM ANALYSIS SECTION -->
    <section class="max-w-7xl w-full mx-auto px-4 py-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

        <!-- Identity Section Panel -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 space-y-5 shadow-md text-left">
            <h3 class="text-lg font-black text-white uppercase tracking-tight border-b border-slate-800 pb-3">Built for the Small and Solo Contractor</h3>
            <p class="text-xs md:text-sm text-slate-400 leading-relaxed font-medium">
                Whether you’re a one-man show or running a small crew, you deserve the same tools the big companies use — without the big-company overhead. We focus on:
            </p>
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-mono font-black uppercase text-slate-200">
                <li class="flex items-center gap-2 bg-slate-950 border border-slate-800/80 p-3 rounded-xl shadow-inner"><span class="text-[#f58613]">⚡</span> Speed</li>
                <li class="flex items-center gap-2 bg-slate-950 border border-slate-800/80 p-3 rounded-xl shadow-inner"><span class="text-[#f58613]">⚡</span> Simplicity</li>
                <li class="flex items-center gap-2 bg-slate-950 border border-slate-800/80 p-3 rounded-xl shadow-inner"><span class="text-[#f58613]">⚡</span> Profit protection</li>
                <li class="flex items-center gap-2 bg-slate-950 border border-slate-800/80 p-3 rounded-xl shadow-inner"><span class="text-[#f58613]">⚡</span> Real-world workflows</li>
            </ul>
            <div class="p-3 bg-slate-950 border border-slate-800/60 rounded-xl text-[11px] font-bold text-slate-400 italic text-center">
                "Pricing that respects small business margins. No fluff. No bloat. No enterprise nonsense."
            </div>
        </div>

        <!-- Mission Section Panel -->
        <div class="space-y-6 text-left md:pt-2">
            <div class="space-y-2">
                <span class="text-[10px] font-black uppercase text-[#f58613] tracking-widest font-mono block">Our Core Core Framework</span>
                <h3 class="text-2xl font-black text-white uppercase tracking-tight">We’re Here to Make Contractors More Profitable — Period</h3>
                <div class="h-1 w-12 bg-[#f58613] rounded-full mt-3"></div>
            </div>
            <p class="text-xs md:text-sm text-slate-400 leading-relaxed font-medium">
                Not with dashboard analytics layers you’ll never open. Not with complex bloated features you don’t need. Not with pricing structures that punish small business operators.
            </p>
            <div class="space-y-2.5">
                <div class="text-xs font-black text-slate-300 uppercase tracking-wide">Just simple, powerful tools that help you:</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-black text-white uppercase">
                    <div class="flex items-center gap-2"><span>⚡</span> Win more jobs</div>
                    <div class="flex items-center gap-2"><span>⚡</span> Get paid faster</div>
                    <div class="flex items-center gap-2"><span>⚡</span> Protect your margins</div>
                    <div class="flex items-center gap-2"><span>⚡</span> Build your reputation</div>
                    <div class="flex items-center gap-2"><span>⚡</span> Grow at your pace</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 💳 PRICING MATRIX SECTION -->
    <section id="pricing" class="bg-gradient-to-b from-black to-slate-950 border-t border-slate-900 px-4 py-20 text-center">
        <div class="max-w-7xl mx-auto space-y-12">
            <div class="space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-black uppercase text-[#f58613] tracking-widest font-mono block">Transparent Ledger Parameters</span>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tight">Straightforward Monthly Pricing</h2>
                <div class="h-1 w-12 bg-[#f58613] mx-auto rounded-full mt-3"></div>
                <p class="text-xs text-slate-400 font-bold pt-1">Simple, honest tiers built for real field operations. It's a lot cheaper than you think.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto items-start">

                <!-- Solo Account -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 space-y-4 text-left shadow-md">
                    <div>
                        <h4 class="text-sm font-black text-white uppercase tracking-wide">Solo Account</h4>
                        <div class="text-3xl font-black font-mono text-white mt-1">$24.95<span class="text-xs font-sans text-slate-500 font-bold block sm:inline"> / mo</span></div>
                    </div>
                    <p class="text-xs text-slate-400 font-medium leading-normal">Single user access. Perfect for the solo trade practitioner handling their own dispatch tracks.</p>
                    <div class="border-t border-slate-800 pt-3 text-[10px] font-mono font-black text-slate-400 uppercase space-y-2">
                        <div class="flex items-center gap-1.5 text-slate-300">✓ 1 Active Command User</div>
                        <div class="flex items-center gap-1.5">✓ Global Pricebook Matrix</div>
                        <div class="flex items-center gap-1.5">✓ Unlimited Estimate Builds</div>
                    </div>
                </div>

                <!-- Crew Account -->
                <div class="bg-slate-900 border-2 border-[#f58613] rounded-2xl p-6 md:p-8 space-y-4 text-left shadow-2xl relative">
                    <div class="absolute -top-3 left-6 bg-[#f58613] text-white text-[9px] font-black uppercase px-2.5 py-0.5 rounded-full tracking-wider shadow font-mono">Most Active Fleet Deck</div>
                    <div>
                        <h4 class="text-sm font-black text-white uppercase tracking-wide">Crew Account</h4>
                        <div class="text-3xl font-black font-mono text-[#f58613] mt-1">$39.95<span class="text-xs font-sans text-slate-500 font-bold block sm:inline"> / mo</span></div>
                    </div>
                    <p class="text-xs text-slate-400 font-medium leading-normal">Up to 5 active field users. Built for expanding trade crews require dense local route synchronization updates.</p>
                    <div class="border-t border-slate-800 pt-3 text-[10px] font-mono font-black text-slate-400 uppercase space-y-2">
                        <div class="flex items-center gap-1.5 text-white">✓ Up to 5 Active Crew Seats</div>
                        <div class="flex items-center gap-1.5 text-white">✓ Synchronized Routing Calendar</div>
                        <div class="flex items-center gap-1.5 text-white">✓ Advanced Photo-Vault Storage</div>
                    </div>
                </div>

                <!-- Custom Account -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 space-y-4 text-left shadow-md">
                    <div>
                        <h4 class="text-sm font-black text-white uppercase tracking-wide">Enterprise Operations</h4>
                        <div class="text-2xl font-black text-white uppercase tracking-tight mt-1.5 font-mono">Fair Rates</div>
                    </div>
                    <p class="text-xs text-slate-400 font-medium leading-normal">More than 5 active field users? We will work out a fair, tailored rate matching your dispatch volume dependencies perfectly.</p>
                    <div class="border-t border-slate-800 pt-3 text-[10px] font-mono font-black text-slate-400 uppercase space-y-2">
                        <div class="flex items-center gap-1.5 text-slate-300">✓ Dedicated Volume Scaling</div>
                        <div class="flex items-center gap-1.5">✓ Infinite Cross-Tenant Blueprints</div>
                        <div class="flex items-center gap-1.5">✓ Full Control Console Anchors</div>
                    </div>
                </div>

            </div>

            <!-- Custom Use Waiver Policy Card -->
            <div class="max-w-xl mx-auto p-4 bg-slate-900/60 border-2 border-dashed border-slate-800 rounded-2xl text-xs font-medium text-slate-400 italic leading-relaxed">
                "And yes — <span class="text-white font-black not-italic">if you don’t use it that month, it’s free</span>. It’s our policy. Not a sales pitch."
            </div>
        </div>
    </section>

    <!-- 🔒 FOOTER LEGAL DECK CONTAINER -->
    <footer class="border-t border-slate-900 bg-black text-slate-500 py-12 text-sm font-bold">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

            <div class="md:col-span-5 flex flex-col items-center md:items-start gap-4">
                <div class="w-[240px] max-w-[full] aspect-square bg-slate-950 border border-slate-900 rounded-2xl overflow-hidden shadow-lg flex items-center justify-center p-2 shrink-0">
                    <img src="/images/footer-logo.webp" alt="Corporate Brand Mark" class="w-full h-full object-contain p-2">
                </div>
                <div class="text-[11px] font-medium text-slate-600 text-center md:text-left leading-normal">
                    &copy; 2026 ContractorSpecialties.<br>
                    ContractorSpecialties is owned and operated by Contractor Service Pros LLC.<br>
                    All multi-tenant database clusters and communication networks securely protected.
                </div>
            </div>

            <div class="md:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-6 text-[11px] uppercase tracking-wider md:pt-4">
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-700 tracking-widest font-black">System Capabilities</span>
                    <a href="#features" class="text-slate-500 hover:text-[#f58613] transition-colors text-decoration-none">Core Utilities</a>
                    <a href="#pricing" class="text-slate-500 hover:text-[#f58613] transition-colors text-decoration-none">Pricing Matrix</a>
                </div>
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-700 tracking-widest font-black">Legal & Security</span>
                    <a href="{{ route('legal.privacy') }}" class="text-slate-500 hover:text-[#f58613] transition-colors normal-case text-decoration-none">Privacy Policy</a>
                    <a href="{{ route('legal.terms') }}" class="text-slate-500 hover:text-[#f58613] transition-colors normal-case text-decoration-none">Terms of Use</a>
                </div>
                <div class="flex flex-col gap-2.5 col-span-2 sm:col-span-1">
                    <span class="text-[10px] text-slate-700 tracking-widest font-black">Secure Terminals</span>
                    <a href="/login/partner" class="text-slate-500 hover:text-white transition-colors bg-slate-950 border border-slate-900 px-3 py-1.5 rounded-lg text-center truncate text-decoration-none">General Contractor</a>
                    <a href="/login/subcontractor" class="text-slate-500 hover:text-white transition-colors bg-slate-950 border border-slate-900 px-3 py-1.5 rounded-lg text-center truncate mt-1 text-decoration-none">Sub-Portal Access</a>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
