<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transparent Tier Pricing Matrix | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ openTools: false }" @click.outside="openTools = false" class="min-h-full font-sans antialiased text-slate-800 bg-slate-50 flex flex-col justify-between selection:bg-[#f58613] selection:text-white">

    <!-- 🌐 TOP GLOBAL HEADER NAV RAIL WITH INTEGRATED MEGA MENU -->
    <header class="bg-white/95 border-b border-slate-200 backdrop-blur-md sticky top-0 z-50 shadow-xs transition-all select-none">
        <div class="max-w-7xl mx-auto px-4 h-24 flex items-center justify-between relative">

            <!-- Logo Block -->
            <div class="w-[280px] max-w-[45%] h-[60px] flex items-center">
                <a href="{{ route('welcome') }}" class="inline-block border-0 outline-none">
                    <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[55px] object-contain object-left">
                </a>
            </div>

            <!-- Heavyweight Navigation Rail -->
            <nav class="flex items-center gap-1 md:gap-2">

                <!-- MEGA MENU TRIGGER -->
                <div class="relative">
                    <button @click="openTools = !openTools" type="button"
                            class="text-sm font-black uppercase text-slate-700 hover:text-slate-950 tracking-widest px-4 py-3 rounded-xl border-b-2 transition-all flex items-center gap-1 cursor-pointer outline-none border-transparent hover:bg-slate-50"
                            :class="openTools ? 'bg-slate-50 border-[#f58613] text-slate-950' : ''">
                        <span>Tools</span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="openTools ? 'rotate-180 text-[#f58613]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
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

                        <!-- Column 1: Core Estimation & Financial Pipeline -->
                        <div class="space-y-3.5">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block font-mono border-b border-slate-100 pb-1">Bidding & Invoicing</span>

                            <a href="/capabilities#estimates" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">📖</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Estimate Builder</h4>
                                    <p class="text-[11px] text-slate-500 font-medium leading-normal mt-0.5">Build clean, consistent field quotes from your pricebook matrix.</p>
                                </div>
                            </a>

                            <a href="/capabilities#invoices" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">⚡</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Instant Invoicing</h4>
                                    <p class="text-[11px] text-slate-500 font-medium leading-normal mt-0.5">Convert approved proposals to billing entries with one single tap.</p>
                                </div>
                            </a>

                            <a href="/capabilities#text-to-pay" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">💸</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Text-To-Pay Rails</h4>
                                    <p class="text-[11px] text-slate-500 font-medium leading-normal mt-0.5">Accelerate your cashflow. Customers sign, tap, and pay on mobile.</p>
                                </div>
                            </a>
                        </div>

                        <!-- Column 2: Field Management & Growth Rails -->
                        <div class="space-y-3.5">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block font-mono border-b border-slate-100 pb-1">Operations & Visibility</span>

                            <a href="/capabilities#calendar" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">📅</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Scheduling Calendar</h4>
                                    <p class="text-[11px] text-slate-500 font-medium leading-normal mt-0.5">Lock down dispatch density. Track field runs and route assignments.</p>
                                </div>
                            </a>

                            <a href="/capabilities#photos" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">📸</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Job Site Photos</h4>
                                    <p class="text-[11px] text-slate-500 font-medium leading-normal mt-0.5">Snap, markup, and log field pictures directly onto client profiles.</p>
                                </div>
                            </a>

                            <a href="/capabilities#reviews" class="group flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 text-decoration-none transition-colors">
                                <span class="text-xl p-2 bg-slate-100 rounded-lg group-hover:bg-white border border-transparent group-hover:border-slate-200 shadow-xs">📈</span>
                                <div>
                                    <h4 class="text-xs font-black text-slate-950 uppercase tracking-wide group-hover:text-[#f58613]">Review Automated Engine</h4>
                                    <p class="text-[11px] text-slate-500 font-medium leading-normal mt-0.5">Boost your local Google search footprint on job completion automatically.</p>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                <a href="/pricing-matrix" class="text-sm font-black uppercase text-slate-950 tracking-widest px-4 py-3 rounded-xl border-b-2 border-[#f58613] bg-slate-50 transition-all hidden md:inline-block">
                    Pricing
                </a>

                <a href="/about-framework" class="text-sm font-black uppercase text-slate-700 hover:text-slate-950 tracking-widest px-4 py-3 rounded-xl border-b-2 border-transparent hover:border-[#f58613] hover:bg-slate-50 transition-all hidden md:inline-block">
                    Why ContractorSpecialties
                </a>

                <div class="flex items-center gap-1.5 px-3 hidden lg:flex">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider font-mono">Live</span>
                </div>

                <a href="/#login-anchor" class="bg-slate-950 hover:bg-slate-800 text-white font-black text-xs py-4 px-6 rounded-xl uppercase tracking-widest transition-all shadow-md border border-slate-900 ml-1">
                    Contractor Login →
                </a>
            </nav>
        </div>
    </header>

    <!-- 🚀 HERO VALUE BLOCK -->
    <section class="bg-gradient-to-b from-slate-100 via-white to-slate-50 pt-16 pb-20 border-b border-slate-200 px-4 text-center md:text-left">
        <div class="max-w-7xl mx-auto space-y-4">
            <div class="inline-flex items-center bg-white border border-slate-200 rounded-full py-1 px-3.5 shadow-xs text-xs font-black uppercase tracking-widest text-slate-500 font-mono">
                Clear. Honest. Straightforward. Built for the owner who still answers the phone.
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-slate-950 uppercase tracking-tight max-w-4xl leading-none">
                Simple Pricing That Respects Small Business Margins
            </h1>
            <p class="text-base md:text-xl text-slate-600 font-semibold max-w-2xl leading-relaxed">
                No tiers. No upsells. No “AI credits.” Just the tools you actually use — at a price that doesn’t punch you in the wallet.
            </p>
        </div>
    </section>

    <!-- 💳 CORE PRICING GRID TIERS -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-16 space-y-16">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- Plan 1: Solo Operator -->
            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 md:p-8 space-y-6 shadow-sm flex flex-col justify-between h-full">
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-2xl block mb-1">💵</span>
                            <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Solo Operator Plan</h2>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black font-mono text-slate-950 block">$24.95</span>
                            <span class="text-[10px] font-mono uppercase font-black text-slate-400">per month</span>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 font-semibold leading-relaxed">
                        Perfect for the one‑truck operator, the handyman, the specialist, or the owner who runs everything themselves.
                    </p>

                    <div class="space-y-2 pt-2">
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider font-mono block">Everything Included:</span>
                        <div class="grid grid-cols-1 gap-2 text-xs font-black uppercase text-slate-800">
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Full estimate builder</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Pricebook matrix</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Text‑to‑Pay</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Job photos & markup</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Customer messaging</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Calendar reminders</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Automated review requests</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Public business profile</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Unlimited jobs & customers</div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 bg-slate-50/50 p-4 rounded-xl mt-6">
                    <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1">Who this is for:</span>
                    <p class="text-xs text-slate-600 font-semibold leading-normal">
                        The contractor who wants to look professional, work faster, and get paid without chasing checks — but doesn’t need a whole crew account.
                    </p>
                </div>
            </div>

            <!-- Plan 2: Crew Plan -->
            <div class="bg-white border-4 border-[#f58613] rounded-2xl p-6 md:p-8 space-y-6 shadow-xl flex flex-col justify-between h-full relative">
                <div class="absolute -top-3.5 left-6 bg-[#f58613] text-white text-[10px] font-black uppercase px-3 py-0.5 rounded-full tracking-wider font-mono">Fleet Standard</div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-2xl block mb-1">👷‍♂️</span>
                            <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Crew Plan</h2>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black font-mono text-[#f58613] block">$39.95</span>
                            <span class="text-[10px] font-mono uppercase font-black text-slate-400">per month</span>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 font-semibold leading-relaxed">
                        For small teams who need everyone on the same page. Includes everything inside the Solo layer out‑of‑the‑box.
                    </p>

                    <div class="space-y-2 pt-2">
                        <span class="text-[10px] font-black uppercase text-[#f58613] tracking-wider font-mono block">Plus Crew Configurations:</span>
                        <div class="grid grid-cols-1 gap-2 text-xs font-black uppercase text-slate-950">
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Up to 5 active field users</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Shared job photos vault</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Shared customer messaging</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Shared uniform pricebook</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Crew‑wide scheduling matrix</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Team‑wide job tracking</div>
                            <div class="flex items-center gap-2"><span class="text-[#f58613]">✓</span> Multi‑user estimate workflow</div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 bg-slate-50 p-4 rounded-xl mt-6">
                    <span class="block text-[10px] font-black uppercase text-[#f58613] tracking-wider mb-1">Who this is for:</span>
                    <p class="text-xs text-slate-600 font-semibold leading-normal">
                        Small crews, family businesses, and growing operations that need everyone working from the same playbook.
                    </p>
                </div>
            </div>

            <!-- Plan 3: Custom Scale Override -->
            <div class="bg-white border-2 border-slate-200 rounded-2xl p-6 md:p-8 space-y-6 shadow-sm text-left h-full flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="border-b border-slate-100 pb-4">
                        <span class="text-2xl block mb-1">🚀</span>
                        <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">More Than 5 Users?</h2>
                        <span class="text-sm font-mono font-black text-[#f58613] block mt-1">Tailored Volume Adjustments</span>
                    </div>

                    <p class="text-sm text-slate-600 font-semibold leading-relaxed">
                        We’ll work out a fair rate matching your fleet constraints perfectly.
                    </p>
                    <p class="text-xs text-slate-500 font-bold leading-normal">
                        No enterprise nonsense. No forced high-pressure sales calls. No “demo verification required.” Just straightforward pricing that makes complete operational sense for your business radius.
                    </p>
                </div>

                <div class="bg-slate-950 text-white rounded-xl p-4 mt-8 text-center text-xs font-mono font-black uppercase tracking-wider">
                    🛠️ Zero Friction Onboarding Channel
                </div>
            </div>

        </div>

        <!-- 🧾 THE PROMISE CALLOUT STRIP -->
        <div class="bg-white border-2 border-slate-950 rounded-2xl p-6 md:p-8 text-left shadow-lg relative overflow-hidden max-w-4xl mx-auto">
            <div class="absolute right-0 bottom-0 text-7xl opacity-5 select-none pointer-events-none font-black">🧾</div>
            <div class="space-y-2">
                <h3 class="text-base md:text-lg font-black text-slate-950 uppercase tracking-tight flex items-center gap-2">
                    🧾 Our Promise: If You Don’t Use It That Month, It’s Free.
                </h3>
                <p class="text-sm text-slate-600 font-semibold leading-relaxed">
                    If you don’t send a single estimate, invoice, or job update — we don’t charge you. It’s our explicit operational platform policy. Not a sales gimmick.
                </p>
            </div>
        </div>

        <!-- 🔧 DETAILED FEATURE CHECKS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-200 pt-16">

            <div class="space-y-4">
                <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">🔧 What’s Included in Every Plan</h3>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider font-mono">Everything you need to run the field without the friction:</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-black uppercase text-slate-700">
                    <div>✓ Clean, professional estimates</div>
                    <div>✓ One‑tap invoices</div>
                    <div>✓ Text‑to‑Pay processing</div>
                    <div>✓ Job photos with markup</div>
                    <div>✓ Customer line messaging</div>
                    <div>✓ Calendar reminders</div>
                    <div>✓ Automated review collection</div>
                    <div>✓ Public business profile</div>
                    <div>✓ Secure login with token access</div>
                    <div>✓ No contracts or ties</div>
                    <div>✓ No setup fees</div>
                    <div>✓ No hidden ledger charges</div>
                </div>
            </div>

            <div class="space-y-4 bg-white border border-slate-200 rounded-2xl p-6 shadow-xs">
                <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">🛠️ Why Contractors Choose ContractorSpecialties</h3>
                <div class="space-y-2.5 text-xs font-semibold text-slate-600 leading-normal">
                    <div class="flex items-start gap-1.5"><span class="text-[#f58613] font-bold">•</span> <p>Because it’s built explicitly for real field work — <span class="text-slate-950 font-black">not office people</span>.</p></div>
                    <div class="flex items-start gap-1.5"><span class="text-[#f58613] font-bold">•</span> <p>Because it’s simple, incredibly fast, and runs flawlessly on mobile layout screens under direct glare bounds.</p></div>
                    <div class="flex items-start gap-1.5"><span class="text-[#f58613] font-bold">•</span> <p>Because it doesn’t nickel‑and‑dime your accounts with volatile usage caps or add‑on upgrades.</p></div>
                    <div class="flex items-start gap-1.5"><span class="text-[#f58613] font-bold">•</span> <p>Because it helps you look professional without needing a 20‑person office staff sitting behind a computer screen.</p></div>
                </div>
            </div>

        </div>

        <!-- ⚡ TARGET CALL TO ACTION BANNER -->
        <div class="bg-gradient-to-br from-slate-900 to-black rounded-3xl p-8 md:p-12 text-center text-white shadow-2xl space-y-6 max-w-4xl mx-auto border border-slate-800">
            <div class="space-y-2">
                <span class="text-xs font-mono font-black uppercase tracking-widest text-[#f58613]">Instant Deployment Terminal</span>
                <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight">Deploy Your Workspace Deck Right Now</h2>
                <p class="text-xs md:text-sm text-slate-400 font-semibold max-w-md mx-auto">
                    No credit card details requested. No commitment penalties. Just tools that make your operational day significantly easier.
                </p>
            </div>
            <div>
                <a href="{{ route('register') }}" class="inline-block bg-[#f58613] hover:bg-orange-600 text-white font-black text-sm py-4.5 px-10 rounded-xl tracking-widest uppercase shadow-lg transition-all active:scale-[0.99] text-decoration-none border-0 cursor-pointer">
                    Get Started →
                </a>
            </div>
        </div>

    </main>

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
                    <a href="/login/partner" class="text-slate-500 hover:text-white transition-colors bg-slate-950 border border-slate-900 px-3 py-1.5 rounded-lg text-center truncate text-decoration-none">General Contractor</a>
                    <a href="/login/subcontractor" class="text-slate-500 hover:text-white transition-colors bg-slate-950 border border-slate-900 px-3 py-1.5 rounded-lg text-center truncate mt-1 text-decoration-none">Sub-Portal Access</a>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
