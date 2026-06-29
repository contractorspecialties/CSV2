<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Tools & Field Capabilities | ContractorSpecialties</title>
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
                            class="text-sm font-black uppercase text-slate-950 tracking-widest px-4 py-3 rounded-xl border-b-2 border-[#f58613] bg-slate-50 transition-all flex items-center gap-1 cursor-pointer outline-none shadow-xs">
                        <span>Tools</span>
                        <svg class="w-4 h-4 text-[#f58613] transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- MEGA MENU DROP-DOWN DECK -->
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

                <a href="/pricing-matrix" class="text-sm font-black uppercase text-slate-700 hover:text-slate-950 tracking-widest px-4 py-3 rounded-xl border-b-2 border-transparent hover:border-[#f58613] hover:bg-slate-50 transition-all hidden md:inline-block">
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

    <!-- 🚀 HERO CAPABILITIES VALUE BLOCK -->
    <section class="bg-gradient-to-b from-slate-100 via-white to-slate-50 pt-16 pb-20 border-b border-slate-200 px-4 text-center md:text-left">
        <div class="max-w-7xl mx-auto space-y-4">
            <div class="inline-flex items-center bg-white border border-slate-200 rounded-full py-1 px-3.5 shadow-xs text-xs font-black uppercase tracking-widest text-[#f58613] font-mono">
                Everything You Need to Run the Field — Without the Friction
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-slate-950 uppercase tracking-tight max-w-4xl leading-none">
                Tools Mapped Out for Pure Field Velocity
            </h1>
            <p class="text-base md:text-xl text-slate-600 font-semibold max-w-3xl leading-relaxed">
                This workspace is engineered to eliminate back-office double entry, streamline client records, and finalize money cycles right from your phone before you even pack up your tools.
            </p>
        </div>
    </section>

    <!-- 🧬 THOROUGH FEATURES DEEP DIVE SYSTEM -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-16 space-y-12">
        <div class="grid grid-cols-1 gap-10">

            <!-- MODULE 1: ESTIMATES -->
            <div id="estimates" class="bg-white border-2 border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-6 items-start scroll-mt-28">
                <div class="lg:col-span-4 space-y-3">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 text-2xl shadow-xs select-none">📖</div>
                    <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Build Estimates in Minutes</h2>
                    <p class="text-xs font-mono uppercase font-black text-[#f58613]">Eliminate Bidding Bottlenecks</p>
                </div>
                <div class="lg:col-span-8 bg-slate-50 p-6 rounded-xl border border-slate-200 space-y-4 text-left">
                    <p class="text-sm text-slate-700 font-semibold leading-relaxed">
                        Stop running your company out of vehicle side notebooks or late-night kitchen table spreadsheets. Build professional estimates line-by-item directly on site using your pre-set material and labor variables.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-bold text-slate-600">
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">No Guesswork Bidding:</span> Pull values straight from your dynamic Pricebook Matrix.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Instant Digital Delivery:</span> Dispatches clean SMS portal links immediately to customer lines.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Margin Protection:</span> Built-in total parameters lock down your markup thresholds accurately.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Unlimited Capacity:</span> Draft and execute as many open job proposals as your market area requires.</p></div>
                    </div>
                </div>
            </div>

            <!-- MODULE 2: INVOICES -->
            <div id="invoices" class="bg-white border-2 border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-6 items-start scroll-mt-28">
                <div class="lg:col-span-4 space-y-3">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 text-2xl shadow-xs select-none">⚡</div>
                    <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Convert Estimates to Invoices Instractions</h2>
                    <p class="text-xs font-mono uppercase font-black text-[#f58613]">Zero Retyping or Double Entry</p>
                </div>
                <div class="lg:col-span-8 bg-slate-50 p-6 rounded-xl border border-slate-200 space-y-4 text-left">
                    <p class="text-sm text-slate-700 font-semibold leading-relaxed">
                        When a homeowner gives the green light, your paperwork should keep pace. Convert approved estimates into final, billing-ready invoices with a single tap of your screen, dropping human transposition errors to zero.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-bold text-slate-600">
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Single-Tap Conversion:</span> Shifts project details straight into payment tracking with no data loss.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">On-The-Spot Modifications:</span> Adjust quantities or add unexpected materials right from the field terminal view.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Automated Balance Fields:</span> Tracks progressive deposits, change orders, and remaining job variables cleanly.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Clean Auditing Trails:</span> Links customer profiles directly down from historical logs to live cash ledgers.</p></div>
                    </div>
                </div>
            </div>

            <!-- MODULE 3: TEXT-TO-PAY -->
            <div id="text-to-pay" class="bg-white border-2 border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-6 items-start scroll-mt-28">
                <div class="lg:col-span-4 space-y-3">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 text-2xl shadow-xs select-none">💸</div>
                    <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Text-to-Pay for Faster Cashflow</h2>
                    <p class="text-xs font-mono uppercase font-black text-[#f58613]">Stop Chasing Bad Paper Checks</p>
                </div>
                <div class="lg:col-span-8 bg-slate-50 p-6 rounded-xl border border-slate-200 space-y-4 text-left">
                    <p class="text-sm text-slate-700 font-semibold leading-relaxed">
                        Give homeowners the easiest way possible to square their accounts. Customers receive a secure link on their mobile line, view their itemized statement, sign off on the production, and process credit rails instantly.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-bold text-slate-600">
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Frictionless Signatures:</span> Customers authorize project completion digitally on their own viewport screens.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">No Complicated Portals:</span> Eliminates usernames or complex passwords for the homeowner.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Secure Merchant Rails:</span> Fully encrypted payment capture tokens ensure maximum ledger protection.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Accelerated Deposits:</span> Clears balances out of outstanding arrays and down to your business checking track fast.</p></div>
                    </div>
                </div>
            </div>

            <!-- MODULE 4: CALENDAR -->
            <div id="calendar" class="bg-white border-2 border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-6 items-start scroll-mt-28">
                <div class="lg:col-span-4 space-y-3">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 text-2xl shadow-xs select-none">📅</div>
                    <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Calendar Reminders & Job Tracking</h2>
                    <p class="text-xs font-mono uppercase font-black text-[#f58613]">Maximize Local Route Density</p>
                </div>
                <div class="lg:col-span-8 bg-slate-50 p-6 rounded-xl border border-slate-200 space-y-4 text-left">
                    <p class="text-sm text-slate-700 font-semibold leading-relaxed">
                        Keep your field crew routing synchronized effortlessly. Tap any highlighted date node on your Master Production Schedule to pull up operational instructions, scope specs, and customer profiles in real time.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-bold text-slate-600">
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Tactile Weekday Mapping:</span> Clean, punchy card blocks display stops clearly on phone displays without multi-layer menus.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Integrated Field Notes:</span> Keep your field crews updated with critical safety access codes and property specs.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Follow-up Security:</span> Built-in appointment trackers protect against missed leads or project delays.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Real-Time Fluid Sync:</span> Fleet modifications adjust instantly across all active logged operator handles.</p></div>
                    </div>
                </div>
            </div>

            <!-- MODULE 5: PHOTOS -->
            <div id="photos" class="bg-white border-2 border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-6 items-start scroll-mt-28">
                <div class="lg:col-span-4 space-y-3">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 text-2xl shadow-xs select-none">📸</div>
                    <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Capture & Send Job Photos</h2>
                    <p class="text-xs font-mono uppercase font-black text-[#f58613]">Protect Margins and Proof of Work</p>
                </div>
                <div class="lg:col-span-8 bg-slate-50 p-6 rounded-xl border border-slate-200 space-y-4 text-left">
                    <p class="text-sm text-slate-700 font-semibold leading-relaxed">
                        Document everything right from the field. Snap clear condition records, markup repair points with arrows or circles, and log them permanently onto client profiles to shut down false liability claims or callbacks instantly.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-bold text-slate-600">
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">On-Site Visual Markup:</span> Overlay field notes directly onto photos before sharing links with homeowners.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Permanent History Vault:</span> Storage frameworks pin media directly to matching estimates and billing trails.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Transparent Proof Matrix:</span> Send clear before-and-after summaries to build instant customer trust.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Shared Crew Visibility:</span> Field crews can reference original diagnostic files before unloading tools.</p></div>
                    </div>
                </div>
            </div>

            <!-- MODULE 6: REVIEWS -->
            <div id="reviews" class="bg-white border-2 border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-6 items-start scroll-mt-28">
                <div class="lg:col-span-4 space-y-3">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 text-2xl shadow-xs select-none">📈</div>
                    <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Automated Review Collection</h2>
                    <p class="text-xs font-mono uppercase font-black text-[#f58613]">Dominate Local Maps Search</p>
                </div>
                <div class="lg:col-span-8 bg-slate-50 p-6 rounded-xl border border-slate-200 space-y-4 text-left">
                    <p class="text-sm text-slate-700 font-semibold leading-relaxed">
                        Build your local trade reputation without manual chasing. When a fulfillment run is closed in your Kanban board, our engine auto-sends a timed, polite review link directly to the customer's mobile device while their satisfaction is highest.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-bold text-slate-600">
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Timed Text Triggers:</span> Fires review links immediately upon job completion for maximum conversion.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Google Optimization:</span> Direct pipeline routing channels customers straight to your Map verification targets.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Hands-off Marketing:</span> Collect constant reviews while staying focused on your active field work.</p></div>
                        <div class="flex items-start gap-2"><span class="text-[#f58613]">✔</span> <p><span class="text-slate-950 font-black">Trust Signal Boost:</span> Display verified customer reviews prominently on your integrated public profile page.</p></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ⚡ TARGET CALL TO ACTION BANNER -->
        <div class="bg-gradient-to-br from-slate-900 to-black rounded-3xl p-8 md:p-12 text-center text-white shadow-2xl space-y-6 max-w-4xl mx-auto border border-slate-800 mt-12">
            <div class="space-y-2">
                <span class="text-xs font-mono font-black uppercase tracking-widest text-[#f58613]">Instant Onboarding Channel</span>
                <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight">Equip Your Field Operation Today</h2>
                <p class="text-xs md:text-sm text-slate-400 font-semibold max-w-md mx-auto">
                    It's a lot cheaper than you think. No credit card requested to build your partition. No contracts. Just heavy tools that protect your margins.
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
    <footer class="border-t border-slate-200 bg-slate-950 text-slate-400 py-12 text-sm font-bold mt-20">
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
