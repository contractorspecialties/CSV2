<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ContractorSpecialties | Professional Tools for Small Field Businesses</title>
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
                <span class="text-[10px] font-mono font-black uppercase text-slate-500 tracking-widest hidden sm:inline">System Connection Secure</span>
            </div>
        </div>
    </header>

    <!-- MAIN PRODUCT VISION & VALUE STRATEGY GRID -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-12 md:py-16 space-y-16">

        <!-- Hero Pitch Zone -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-900 text-white rounded-full text-xs font-black uppercase tracking-wider">
                    ⚡ Professional Infrastructure Engine
                </div>
                <h1 class="text-4xl sm:text-5xl font-black text-slate-950 tracking-tight leading-tight uppercase">
                    The same tools as your <br class="hidden sm:inline">
                    biggest competition. <br>
                    <span class="text-[#f58613]">Priced for real business.</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-600 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Large exterior companies dominate local markets because they possess dedicated administrative and software budgets. We leveled the playing field. Get identical estimating precision, seamless mobile payment workflows, and automated customer review generation—minus the corporate bloat.
                </p>
            </div>

            <!-- Solid White Workspace Entry Hub Card Box -->
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
        </section>

        <!-- CORE FEATURE MATRIX GRID -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-slate-200 pt-12">

            <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-xl">📋</div>
                <h4 class="font-black text-base text-slate-950 uppercase tracking-tight">Standardized Bidding</h4>
                <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                    Build job templates within your private company pricebook. Protect your bottom-line markups and output completely uniform, professional project estimates directly from your phone while standing in the customer's driveway.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-xl">⚡</div>
                <h4 class="font-black text-base text-slate-950 uppercase tracking-tight">Instant Text-to-Pay</h4>
                <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                    Homeowners pay through a secure link delivered straight to their mobile device. They approve line-item details, supply a digital touchscreen signature, and settle invoices via Stripe without complex registration steps or app downloads.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-xl">🌐</div>
                <h4 class="font-black text-base text-slate-950 uppercase tracking-tight">Public Search Profiles</h4>
                <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                    Every active account maintains a clean, public SEO profile connected to our regional directories. It hosts verified project history and customer ratings, driving consistent organic leads straight to your communication pipeline.
                </p>
            </div>

        </section>

        <!-- TRANSPARENT CORPORATE PRICING MATRIX -->
        <section class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="text-center max-w-xl mx-auto space-y-2">
                <h2 class="text-2xl font-black text-slate-950 uppercase tracking-tight">No-Nonsense Small Business Pricing</h2>
                <p class="text-xs text-slate-500 font-medium">We don't play games with hidden fees or volatile percentage-cut models. Pick the bracket that matches your field headcount and scale indefinitely.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">

                <!-- Solo Account -->
                <div class="border-2 border-slate-100 rounded-2xl p-6 space-y-4 relative bg-slate-50/50">
                    <div class="space-y-1">
                        <span class="inline-block bg-slate-900 text-white font-black text-[9px] uppercase tracking-wider px-2 py-0.5 rounded">Solo Operator</span>
                        <h4 class="text-lg font-black text-slate-950 uppercase">Single User Access</h4>
                    </div>
                    <div class="flex items-baseline gap-1 font-mono">
                        <span class="text-3xl font-black text-slate-950">$24.95</span>
                        <span class="text-xs font-bold text-slate-400 uppercase">/ month</span>
                    </div>
                    <ul class="text-xs text-slate-600 font-bold space-y-2 border-t border-slate-200/60 pt-4">
                        <li class="flex items-center gap-2">✓ Full Mobile Pricebook Engine</li>
                        <li class="flex items-center gap-2">✓ Unlimited Estimate & Text Dispatches</li>
                        <li class="flex items-center gap-2">✓ Integrated Local SEO Directory Profile</li>
                    </ul>
                </div>

                <!-- Small Crew Account -->
                <div class="border-2 border-slate-950 rounded-2xl p-6 space-y-4 relative bg-white shadow-md ring-2 ring-slate-950/5">
                    <div class="absolute -top-2.5 right-4 bg-[#f58613] text-white font-black text-[9px] uppercase tracking-widest px-2.5 py-0.5 rounded-full">
                        Most Popular
                    </div>
                    <div class="space-y-1">
                        <span class="inline-block bg-slate-900 text-white font-black text-[9px] uppercase tracking-wider px-2 py-0.5 rounded">Crew Mode</span>
                        <h4 class="text-lg font-black text-slate-950 uppercase">Up to 5 Users</h4>
                    </div>
                    <div class="flex items-baseline gap-1 font-mono">
                        <span class="text-3xl font-black text-slate-950">$39.95</span>
                        <span class="text-xs font-bold text-slate-400 uppercase">/ month</span>
                    </div>
                    <ul class="text-xs text-slate-600 font-bold space-y-2 border-t border-slate-200/60 pt-4">
                        <li class="flex items-center gap-2">✓ Everything in Solo Operator</li>
                        <li class="flex items-center gap-2">✓ Shared Pricebook Matrix for Office & Field</li>
                        <li class="flex items-center gap-2">✓ Individual Crew Sub-Logins & Assignment Trackers</li>
                    </ul>
                </div>

            </div>

            <!-- Dormancy Safety Net Policy Notice -->
            <div class="max-w-2xl mx-auto bg-slate-50 border border-slate-200 p-4 rounded-xl text-center">
                <span class="text-[10px] font-black uppercase text-[#f58613] tracking-widest block mb-1">🛡️ The Operational Safety Net Guarantee</span>
                <p class="text-xs font-semibold text-slate-600 leading-normal">
                    We support cyclical businesses. If your work encounters a seasonal slow period or your crew goes dormant and does not actively execute an estimate or receive a customer checkout payment through the platform during a billing cycle, your subscription fees are systematically waived for that month.
                </p>
            </div>
        </section>

    </main>

    <!-- PITCH BLACK HIGH-CONTRAST COMPLIANCE FOOTER -->
    <footer class="border-t border-slate-900 bg-black text-slate-400 py-12">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

            <!-- Fixed 400x400 Left Anchor Framework Container -->
            <div class="md:col-span-5 flex flex-col items-center md:items-start gap-4">
                <div class="w-[400px] max-w-full aspect-square bg-slate-950 border border-slate-900 rounded-2xl overflow-hidden shadow-lg flex items-center justify-center">
                    <img src="/images/footer-logo.webp" alt="Corporate Brand Mark" class="w-full h-full object-contain p-4">
                </div>
                <div class="text-xs font-medium text-slate-500 text-center md:text-left mt-1">
                    &copy; 2026 ContractorSpecialties.<br>
                    All corporate directories and security lines secure via sc_ tracking barriers.
                </div>
            </div>

            <!-- Structured Tool, Directory & Resource Compliance Link Columns -->
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
