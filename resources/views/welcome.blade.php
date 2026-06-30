<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
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
<body x-data="{ openTools: false }" @click.outside="openTools = false" class="min-h-full font-sans antialiased text-slate-900 bg-slate-50 flex flex-col justify-between selection:bg-[#f58613] selection:text-white">

    <!-- 🌐 TOP GLOBAL HEADER NAV RAIL WITH EMBEDDED HIGH-PRESENCE LOGO -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm transition-all select-none">
        <div class="max-w-7xl mx-auto px-4 h-24 md:h-28 flex items-center justify-between relative">

            <!-- Beefed-Up Responsive Brand Container -->
            <div class="w-[260px] md:w-[420px] max-w-[50%] h-[70px] md:h-[95px] flex items-center">
                <a href="{{ route('welcome') }}" class="inline-block border-0 outline-none w-full">
                    <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[60px] md:max-h-[85px] object-contain object-left">
                </a>
            </div>

            <!-- Heavyweight Navigation Rail -->
            <nav class="flex items-center gap-1 md:gap-3">

                <!-- MEGA MENU TRIGGER -->
                <div class="relative">
                    <button @click="openTools = !openTools" type="button"
                            class="text-base font-black uppercase text-slate-800 hover:text-slate-950 tracking-widest px-4 py-3 rounded-xl border-b-2 transition-all flex items-center gap-1 cursor-pointer outline-none border-transparent hover:bg-slate-50"
                            :class="openTools ? 'bg-slate-50 border-[#f58613] text-slate-950' : ''">
                        <span>Tools</span>
                        <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="openTools ? 'rotate-180 text-[#f58613]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- MEGA MENU DRO-DOWN DECK -->
                    <div x-show="openTools" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-0 lg:-left-32 top-full mt-2 w-[92vw] sm:w-[600px] bg-white border-2 border-slate-200 rounded-2xl shadow-2xl p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 z-50 overflow-hidden">

                        <div class="absolute top-0 inset-x-0 h-1.5 bg-[#f58613]"></div>

                        <!-- Column 1: Bidding & Invoicing -->
                        <div class="space-y-3.5">
                            <span class="text-xs font-black uppercase tracking-wider text-slate-400 block font-mono border-b border-slate-100 pb-1">Bidding & Invoicing</span>

                            <a href="/capabilities#estimates" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">📖</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Estimate Builder</h4>
                                    <p class="text-[11px] text-slate-500 font-bold leading-normal mt-0.5">Build clean, consistent field quotes from your pricebook matrix.</p>
                                </div>
                            </a>

                            <a href="/capabilities#invoices" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">⚡</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Instant Invoicing</h4>
                                    <p class="text-[11px] text-slate-500 font-bold leading-normal mt-0.5">Convert approved proposals to billing entries with one single tap.</p>
                                </div>
                            </a>

                            <a href="/capabilities#text-to-pay" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">💸</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Text-To-Pay Rails</h4>
                                    <p class="text-[11px] text-slate-500 font-bold leading-normal mt-0.5">Accelerate your cashflow. Customers sign, tap, and pay on mobile.</p>
                                </div>
                            </a>
                        </div>

                        <!-- Column 2: Operations & Visibility -->
                        <div class="space-y-3.5">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block font-mono border-b border-slate-100 pb-1">Operations & Visibility</span>

                            <a href="/capabilities#calendar" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">📅</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Scheduling Calendar</h4>
                                    <p class="text-[11px] text-slate-500 font-bold leading-normal mt-0.5">Lock down dispatch density. Track field runs and route assignments.</p>
                                </div>
                            </a>

                            <a href="/capabilities#photos" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">📸</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Job Site Photos</h4>
                                    <p class="text-[11px] text-slate-500 font-bold leading-normal mt-0.5">Snap, markup, and log field pictures directly onto client profiles.</p>
                                </div>
                            </a>

                            <a href="/capabilities#reviews" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">📈</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Review Automated Engine</h4>
                                    <p class="text-[11px] text-slate-500 font-bold leading-normal mt-0.5">Boost your local Google search footprint on job completion automatically.</p>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                <a href="/pricing-matrix" class="text-base font-black uppercase text-slate-800 hover:text-slate-950 tracking-widest px-4 py-3 rounded-xl border-b-2 border-transparent hover:border-[#f58613] hover:bg-slate-50 transition-all hidden md:inline-block">
                    Pricing
                </a>

                <a href="/about-framework" class="text-base font-black uppercase text-slate-800 hover:text-slate-950 tracking-widest px-4 py-3 rounded-xl border-b-2 border-transparent hover:border-[#f58613] hover:bg-slate-50 transition-all hidden md:inline-block">
                    Why ContractorSpecialties
                </a>

                <div class="flex items-center gap-1.5 px-3 hidden lg:flex">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                    <span class="text-[10px] font-black uppercase text-slate-500 tracking-wider font-mono">Live</span>
                </div>

                <a href="#login-anchor" class="bg-slate-950 hover:bg-slate-800 text-white font-black text-sm py-4 px-6 rounded-xl uppercase tracking-widest transition-all shadow-md border border-slate-900 ml-1">
                    Contractor Login →
                </a>
            </nav>
        </div>
    </header>

    <!-- 🚀 HERO & OUTDOOR-READABLE LIGHT LOG-IN REGISTRY LAYER -->
    <section class="relative bg-gradient-to-b from-slate-100 via-white to-slate-50 pt-16 pb-24 border-b border-slate-200 px-4">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Hero Left Value Pitch Column -->
            <div class="lg:col-span-7 space-y-6 text-left">
                <div class="inline-flex items-center gap-2 bg-white border border-slate-200 rounded-full py-1.5 px-4 shadow-sm">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#f58613] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#f58613]"></span>
                    </span>
                    <span class="text-xs font-black uppercase tracking-widest text-[#f58613]">Operational System Status Active</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-black text-slate-950 uppercase tracking-tight leading-none max-w-2xl">
                    Run Your Field Business Like a Pro — <span class="text-[#f58613]">Without Paying Enterprise Prices</span>
                </h1>

                <p class="text-base md:text-xl text-slate-700 font-bold leading-relaxed max-w-xl">
                    Estimate, invoice, message customers, track jobs, and get paid — all from one simple tool built for the owner who still answers the phone.
                </p>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 pt-3">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto bg-[#f58613] hover:bg-orange-600 text-white font-black text-sm py-4.5 px-10 rounded-xl tracking-widest uppercase shadow-lg transition-all active:scale-[0.99] text-center text-decoration-none border-0 cursor-pointer">
                        Get Started →
                    </a>
                    <div class="text-left">
                        <div class="text-sm font-black text-slate-950 uppercase tracking-wide">It's a lot cheaper than you think.</div>
                        <div class="text-xs text-slate-500 font-bold mt-0.5">No credit card required to build workspace. No nonsense.</div>
                    </div>
                </div>
            </div>

            <!-- Operator Login Panel Column (Dark References Removed Completely) -->
            <div id="login-anchor" class="lg:col-span-5 bg-white border-2 border-slate-950 rounded-2xl shadow-xl p-6 md:p-8 space-y-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-[#f58613]/5 to-transparent rounded-bl-full pointer-events-none"></div>

                <div class="space-y-1.5 border-b border-slate-100 pb-3">
                    <span class="text-xs font-black text-[#f58613] uppercase tracking-widest block font-mono">Secure Token Dispatch</span>
                    <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Operator Dashboard Access</h2>
                    <p class="text-sm text-slate-700 leading-normal font-bold">
                        Enter your business email. We’ll send a secure token link that drops you straight into your command center center.
                    </p>
                </div>

                <!-- 🔔 SECURE SUCCESS TRACK: Displays flashed magic messages clearly under sunlight -->
                @if(session('status'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl text-xs font-black shadow-inner flex items-center gap-2">
                        <span class="text-lg">👍</span>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if(session('errors') || $errors->any())
                    <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-xs font-black shadow-inner">
                        🛑 {{ session('errors') ? session('errors')->first() : $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('magic.send') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="login_email" class="block text-xs font-black uppercase text-slate-800 tracking-wider mb-2">Business Registration Email</label>
                        <input type="email" id="login_email" name="email" required placeholder="name@yourcompany.com" value="{{ old('email') }}" autocomplete="email"
                               class="w-full bg-slate-50 border-2 border-slate-300 focus:border-slate-950 rounded-xl py-4 px-4 text-base font-bold text-slate-950 shadow-inner focus:outline-none placeholder:text-slate-400">
                    </div>

                    <button type="submit" class="w-full bg-slate-950 hover:bg-slate-800 text-white font-black text-xs py-4 px-4 rounded-xl uppercase tracking-widest shadow transition-all active:scale-[0.99] cursor-pointer outline-none border-0">
                        Request Access Link ⚡
                    </button>
                </form>

                <p class="text-[10px] text-slate-600 font-bold leading-normal text-left pt-2 border-t border-slate-100">
                    <span class="font-black uppercase block tracking-wide text-slate-800 mb-0.5">SMS Disclosure notice:</span>
                    By requesting an access link, you agree to receive transactional account confirmations and system alerts from ContractorSpecialties. Msg/data rates may apply. Reply STOP to opt out.
                </p>

                <div class="pt-2 text-center text-sm font-black text-slate-700 border-t border-slate-100">
                    New partner? <a href="{{ route('register') }}" class="text-[#f58613] font-black underline hover:text-orange-500 transition-colors">Provision your workspace →</a>
                </div>
            </div>

        </div>
    </section>

    <!-- 🛠️ HIGH-UTILITY FUNCTIONAL VALUE MATRIX SECTION -->
    <section id="features" class="max-w-7xl w-full mx-auto px-4 py-20 space-y-10 text-center">
        <div class="space-y-3 max-w-2xl mx-auto">
            <span class="text-xs font-black uppercase text-[#f58613] tracking-widest font-mono block">Engine Core Features Matrix</span>
            <h2 class="text-3xl font-black text-slate-950 uppercase tracking-tight">Everything You Need to Run the Field — Without the Friction</h2>
            <div class="h-1.5 w-16 bg-[#f58613] mx-auto rounded-full mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-4">

            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 text-left space-y-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-400 shadow-md">
                <span class="text-2xl block select-none">📖</span>
                <h3 class="text-base font-black text-slate-950 uppercase tracking-wide">Build Estimates in Minutes</h3>
                <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                    No spreadsheets. No guesswork. No “I’ll get that to you tonight.” Just clean, consistent estimates built from your pricebook matrix.
                </p>
            </div>

            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 text-left space-y-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-400 shadow-md">
                <span class="text-2xl block select-none">⚡</span>
                <h3 class="text-base font-black text-slate-950 uppercase tracking-wide">Convert Estimates to Invoices Instantly</h3>
                <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                    One tap. No retyping. No double entry. Push active dispatch metrics directly down the production payment track immediately.
                </p>
            </div>

            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 text-left space-y-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-400 shadow-md">
                <span class="text-2xl block select-none">💬</span>
                <h3 class="text-base font-black text-slate-950 uppercase tracking-wide">Quick Messaging to Customers</h3>
                <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                    Send updates, reminders, approvals, and secure payment links right from the field terminal straight to client devices.
                </p>
            </div>

            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 text-left space-y-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-400 shadow-md">
                <span class="text-2xl block select-none">📸</span>
                <h3 class="text-base font-black text-slate-950 uppercase tracking-wide">Capture & Send Job Photos</h3>
                <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                    Snap photos, mark them up, attach them to estimates or invoices — protect your margins and your operational sanity.
                </p>
            </div>

            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 text-left space-y-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-400 shadow-md">
                <span class="text-2xl block select-none">📅</span>
                <h3 class="text-base font-black text-slate-950 uppercase tracking-wide">Calendar Reminders & Job Tracking</h3>
                <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                    Never forget a follow-up or route stop appointment again. Lock your daily dispatch layout density down into precise visual nodes.
                </p>
            </div>

            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 text-left space-y-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-400 shadow-md">
                <span class="text-2xl block select-none">➕</span>
                <h3 class="text-base font-black text-slate-950 uppercase tracking-wide">On-the-Spot Add-On Invoices</h3>
                <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                    Customer wants “one more thing”? Tap, add, send — capture those field changes and get paid before you leave the work site.
                </p>
            </div>

            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 text-left space-y-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-400 shadow-md">
                <span class="text-2xl block select-none">💸</span>
                <h3 class="text-base font-black text-slate-950 uppercase tracking-wide">Text-to-Pay for Faster Cashflow</h3>
                <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                    Your customer taps, signs, pays. No complicated consumer portals. No chasing bad paper checks around town.
                </p>
            </div>

            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 text-left space-y-3 transition-all duration-300 hover:-translate-y-1 hover:border-slate-400 shadow-md lg:col-span-2">
                <span class="text-2xl block select-none">📈</span>
                <h3 class="text-base font-black text-slate-950 uppercase tracking-wide">Automated Review Collection</h3>
                <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                    Timed review requests boost your local Google Map footprint automatically without begging, linking social verification nodes right on completion.
                </p>
            </div>

        </div>
    </section>

    <!-- 🌐 PUBLIC DIRECTORY REPUTATION MODULE -->
    <section class="border-t border-b border-slate-200 bg-white px-4 py-24 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#f58613/2%_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
        <div class="max-w-4xl mx-auto space-y-8 relative z-10">
            <div class="space-y-2">
                <span class="text-xs font-black uppercase text-[#f58613] tracking-widest font-mono block">Programmatic SEO Framework</span>
                <h2 class="text-3xl font-black text-slate-950 uppercase tracking-tight">Your Business Profile — Clean, Search-Friendly, and Trustworthy</h2>
                <div class="h-1.5 w-16 bg-[#f58613] mx-auto rounded-full mt-4"></div>
            </div>

            <p class="text-base md:text-lg text-slate-600 font-semibold leading-relaxed max-w-2xl mx-auto">
                Every account includes a public profile showcasing your services, reviews, service area, and contact info. It’s not a new website — it’s a simple, credible place for customers to learn who you are.
            </p>

            <div class="w-full max-w-xl mx-auto bg-slate-50 border-2 border-slate-200 rounded-2xl p-6 text-left shadow-lg font-sans space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-950 flex items-center justify-center text-xl font-bold select-none text-[#f58613]">🏗️</div>
                        <div>
                            <h4 class="text-base font-black text-slate-950 uppercase tracking-wide">Apex Roofing Architecture</h4>
                            <p class="text-xs text-slate-500 font-mono font-bold">📍 Washington, NC • Verified Active Profile</p>
                        </div>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-1 text-xs font-mono font-black text-emerald-700 uppercase tracking-wider shadow-inner">
                        ✓ General Liability Insured
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-xs font-mono font-black">
                    <div class="bg-white border border-slate-200 p-2.5 rounded-xl"><span class="text-slate-400 block mb-0.5 uppercase text-[9px]">Exp</span>15+ Years</div>
                    <div class="bg-white border border-slate-200 p-2.5 rounded-xl"><span class="text-slate-400 block mb-0.5 uppercase text-[9px]">Scope</span>25 Mi Radius</div>
                    <div class="bg-white border border-slate-200 p-2.5 rounded-xl"><span class="text-slate-400 block mb-0.5 uppercase text-[9px]">Reviews</span>⭐⭐⭐⭐★ (4.9)</div>
                </div>
                <div class="text-xs text-slate-600 font-semibold leading-relaxed bg-white p-3 rounded-xl border border-slate-200 italic">
                    "Specializing in full tear-off replacements and emergency structural storm maintenance across North Carolina..."
                </div>
            </div>
        </div>
    </section>

    <!-- 👤 IDENTITY & MISSION SECTION -->
    <section class="max-w-7xl w-full mx-auto px-4 py-24 grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

        <div class="bg-white border-2 border-slate-200 rounded-3xl p-6 md:p-8 space-y-5 shadow-sm text-left">
            <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight border-b border-slate-100 pb-3">Built for the Small and Solo Contractor</h3>
            <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                Whether you’re a one-man show or running a small crew, you deserve the same tools the big companies use — without the big-company overhead. We focus on:
            </p>
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-mono font-black uppercase text-slate-800">
                <li class="flex items-center gap-2 bg-slate-50 border border-slate-200 p-3 rounded-xl shadow-inner"><span class="text-[#f58613] text-sm">⚡</span> Speed</li>
                <li class="flex items-center gap-2 bg-slate-50 border border-slate-200 p-3 rounded-xl shadow-inner"><span class="text-[#f58613] text-sm">⚡</span> Simplicity</li>
                <li class="flex items-center gap-2 bg-slate-50 border border-slate-200 p-3 rounded-xl shadow-inner"><span class="text-[#f58613] text-sm">⚡</span> Profit protection</li>
                <li class="flex items-center gap-2 bg-slate-50 border border-slate-200 p-3 rounded-xl shadow-inner"><span class="text-[#f58613] text-sm">⚡</span> Real-world workflows</li>
            </ul>
            <div class="p-4 bg-slate-950 border border-slate-950 rounded-xl text-xs font-black text-slate-300 italic text-center">
                "Pricing that respects small business margins. No fluff. No bloat. No enterprise nonsense."
            </div>
        </div>

        <div class="space-y-6 text-left md:pt-2">
            <div class="space-y-2">
                <span class="text-xs font-black uppercase text-[#f58613] tracking-widest font-mono block">Our Core Framework</span>
                <h3 class="text-2xl md:text-3xl font-black text-slate-950 uppercase tracking-tight">We’re Here to Make Contractors More Profitable — Period</h3>
                <div class="h-1.5 w-16 bg-[#f58613] rounded-full mt-4"></div>
            </div>
            <p class="text-sm md:text-base text-slate-600 font-semibold leading-relaxed">
                Not with dashboard analytics layers you’ll never open. Not with complex bloated features you don’t need. Not with pricing structures that punish small business operators.
            </p>
            <div class="space-y-3">
                <div class="text-sm font-black text-slate-900 uppercase tracking-wide">Just simple, powerful tools that help you:</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-black text-slate-950 uppercase">
                    <div class="flex items-center gap-2 bg-white border border-slate-200 py-2 px-3 rounded-lg"><span>⚡</span> Win more jobs</div>
                    <div class="flex items-center gap-2 bg-white border border-slate-200 py-2 px-3 rounded-lg"><span>⚡</span> Get paid faster</div>
                    <div class="flex items-center gap-2 bg-white border border-slate-200 py-2 px-3 rounded-lg"><span>⚡</span> Protect your margins</div>
                    <div class="flex items-center gap-2 bg-white border border-slate-200 py-2 px-3 rounded-lg"><span>⚡</span> Build your reputation</div>
                    <div class="flex items-center gap-2 bg-white border border-slate-200 py-2 px-3 rounded-lg"><span>⚡</span> Grow at your pace</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 💳 PRICING MATRIX SECTION -->
    <section id="pricing" class="bg-slate-100 border-t border-slate-200 px-4 py-24 text-center">
        <div class="max-w-7xl mx-auto space-y-12">
            <div class="space-y-2 max-w-xl mx-auto">
                <span class="text-xs font-black uppercase text-[#f58613] tracking-widest font-mono block">Transparent Ledger Parameters</span>
                <h2 class="text-3xl font-black text-slate-950 uppercase tracking-tight">Straightforward Monthly Pricing</h2>
                <div class="h-1.5 w-16 bg-[#f58613] mx-auto rounded-full mt-4"></div>
                <p class="text-sm text-slate-600 font-black pt-1">Simple, honest tiers built for real field operations. It's a lot cheaper than you think.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto items-start">

                <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 md:p-8 space-y-4 text-left shadow-sm">
                    <div>
                        <h4 class="text-base font-black text-slate-950 uppercase tracking-wide">Solo Account</h4>
                        <div class="text-4xl font-black font-mono text-slate-950 mt-1">$24.95<span class="text-xs font-sans text-slate-500 font-bold block sm:inline"> / mo</span></div>
                    </div>
                    <p class="text-xs text-slate-600 font-bold leading-normal">Single user access. Perfect for the solo trade practitioner handling their own dispatch tracks.</p>
                    <div class="border-t border-slate-100 pt-3 text-xs font-mono font-black text-slate-600 uppercase space-y-2">
                        <div class="flex items-center gap-1.5 text-slate-950">✓ 1 Active Command User</div>
                        <div class="flex items-center gap-1.5">✓ Global Pricebook Matrix</div>
                        <div class="flex items-center gap-1.5">✓ Unlimited Estimate Builds</div>
                    </div>
                </div>

                <div class="bg-white border-4 border-[#f58613] rounded-2xl p-6 md:p-8 space-y-4 text-left shadow-xl relative">
                    <div class="absolute -top-3.5 left-6 bg-[#f58613] text-white text-[10px] font-black uppercase px-3 py-0.5 rounded-full tracking-wider shadow font-mono">Most Active Fleet Deck</div>
                    <div>
                        <h4 class="text-base font-black text-slate-950 uppercase tracking-wide">Crew Account</h4>
                        <div class="text-4xl font-black font-mono text-[#f58613] mt-1">$39.95<span class="text-xs font-sans text-slate-500 font-bold block sm:inline"> / mo</span></div>
                    </div>
                    <p class="text-xs text-slate-600 font-bold leading-normal">Up to 5 active field users. Built for expanding trade crews require dense local route synchronization updates.</p>
                    <div class="border-t border-slate-100 pt-3 text-xs font-mono font-black text-slate-600 uppercase space-y-2">
                        <div class="flex items-center gap-1.5 text-slate-950">✓ Up to 5 Active Crew Seats</div>
                        <div class="flex items-center gap-1.5 text-slate-950">✓ Synchronized Routing Calendar</div>
                        <div class="flex items-center gap-1.5 text-slate-950">✓ Advanced Photo-Vault Storage</div>
                    </div>
                </div>

                <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 md:p-8 space-y-4 text-left shadow-sm">
                    <div>
                        <h4 class="text-base font-black text-slate-950 uppercase tracking-tight mt-1 font-mono">Enterprise Operations</h4>
                        <div class="text-3xl font-black text-slate-950 uppercase tracking-tight mt-1 font-mono">Fair Rates</div>
                    </div>
                    <p class="text-xs text-slate-600 font-bold leading-normal">More than 5 active field users? We will work out a fair, tailored rate matching your dispatch volume dependencies perfectly.</p>
                    <div class="border-t border-slate-100 pt-3 text-xs font-mono font-black text-slate-600 uppercase space-y-2">
                        <div class="flex items-center gap-1.5 text-slate-950">✓ Dedicated Volume Scaling</div>
                        <div class="flex items-center gap-1.5">✓ Infinite Cross-Tenant Blueprints</div>
                        <div class="flex items-center gap-1.5">✓ Full Control Console Anchors</div>
                    </div>
                </div>

            </div>

            <div class="max-w-xl mx-auto p-4 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-500 italic leading-relaxed shadow-xs">
                "And yes — <span class="text-slate-950 font-black not-italic">if you don’t use it that month, it’s free</span>. It’s our policy. Not a sales pitch."
            </div>
        </div>
    </section>

    <!-- 🔒 FOOTER LEGAL DECK CONTAINER -->
    <footer class="border-t border-slate-200 bg-slate-950 text-slate-400 py-12 text-sm font-bold">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

            <div class="md:col-span-5 flex flex-col items-center md:items-start gap-4">
                <div class="w-[240px] max-w-[full] aspect-square bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg flex items-center justify-center p-2 shrink-0">
                    <img src="/images/footer-logo.webp" alt="Corporate Brand Mark" class="w-full h-full object-contain p-2">
                </div>
                <div class="text-[11px] font-medium text-slate-500 text-center md:text-left leading-normal">
                    &copy; 2026 ContractorSpecialties.<br>
                    ContractorSpecialties is owned and operated by Contractor Service Pros LLC.<br>
                    All multi-tenant database clusters and communication networks securely protected.
                </div>
            </div>

            <div class="md:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-6 text-[11px] uppercase tracking-wider md:pt-4">
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-700 tracking-widest font-black">System Capabilities</span>
                    <a href="/capabilities" class="text-slate-500 hover:text-[#f58613] transition-colors text-decoration-none">Core Utilities</a>
                    <a href="/pricing-matrix" class="text-slate-500 hover:text-[#f58613] transition-colors text-decoration-none">Pricing Matrix</a>
                </div>
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-700 tracking-widest font-black">Legal & Security</span>
                    <a href="{{ route('legal.privacy') }}" class="text-slate-500 hover:text-[#f58613] transition-colors normal-case text-decoration-none">Privacy Policy</a>
                    <a href="{{ route('legal.terms') }}" class="text-slate-500 hover:text-[#f58613] transition-colors normal-case text-decoration-none">Terms of Use</a>
                </div>
                <div class="flex flex-col gap-2.5 col-span-2 sm:col-span-1">
                    <span class="text-[10px] text-slate-700 tracking-widest font-black">Secure Terminals</span>
                    <a href="/login/partner" class="text-slate-500 hover:text-white transition-colors bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-lg text-center truncate text-decoration-none">General Contractor</a>
                    <a href="/login/subcontractor" class="text-slate-500 hover:text-white transition-colors bg-slate-950 border border-slate-800 px-3 py-1.5 rounded-lg text-center truncate mt-1 text-decoration-none">Sub-Portal Access</a>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
