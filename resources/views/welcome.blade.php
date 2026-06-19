<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
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

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto px-4 h-24 flex items-center justify-between">

            <div class="w-[400px] max-w-[65%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>

            <div class="flex items-center gap-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-mono font-black uppercase text-slate-500 tracking-widest hidden sm:inline">Secure Access Route</span>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-12 md:py-16 space-y-20">

        <section class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-950 tracking-tight leading-tight uppercase">
                    Professional Tools for Small Field Businesses — <br>
                    <span class="text-[#f58613]">Without the Ridiculous Price Tag</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-600 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Estimate, invoice, message customers, manage jobs, and get paid — all from one simple tool built for the owner who still answers the phone.
                </p>
            </div>

            <div id="access-hub" class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xl relative">
                <div class="absolute -top-3 -right-3 w-12 h-12 rounded-xl bg-slate-900 text-[#f58613] border border-slate-800 flex items-center justify-center text-xl shadow-md font-bold">
                    🔑
                </div>

                <div class="space-y-2 border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">Get Your Secure Access Link</h3>
                    <p class="text-xs text-slate-500 font-semibold max-w-[280px] leading-normal">No passwords. No setup fees. No commitment. Just tools that make your business run smoother.</p>
                </div>

                @if($errors->any())
                    <div class="p-3 bg-red-50 text-red-700 border border-red-200 rounded-xl text-xs font-bold mb-4 space-y-1 shadow-sm">
                        @foreach($errors->all() as $error)
                            <div class="flex items-start gap-1.5">
                                <span class="shrink-0">⚠️</span>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
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
                        <label for="email" class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1.5">Your Account Email</label>
                        <input type="email" id="email" name="email" required autocomplete="email" placeholder="name@yourcompany.com"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3 px-4 text-sm font-bold text-slate-950 placeholder:text-slate-400 focus:outline-none focus:border-[#f58613] focus:ring-1 focus:ring-[#f58613] shadow-inner">
                    </div>

                    <button type="submit" class="w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer">
                        Request Secure Access Link →
                    </button>

                    <div class="pt-3 border-t border-slate-100 text-[10px] text-slate-400 leading-normal font-medium">
                        By requesting an access link, you agree to receive automated account confirmations, system status alerts, and operational estimate notifications via text message from ContractorSpecialties. Consent is completely voluntary and is not a condition of purchase. Message frequency varies based on project utilization loops. Message & data rates may apply. Reply <span class="font-bold text-slate-700 tracking-tight">STOP</span> to instantly block lines, or <span class="font-bold text-slate-700 tracking-tight">HELP</span> for engineering routing diagnostics. View our <a href="/privacy" class="underline hover:text-[#f58613] transition-colors">Privacy Policy</a> and <a href="/terms" class="underline hover:text-[#f58613] transition-colors">Terms of Use</a>.
                    </div>
                </form>
            </div>
        </section>

        <section class="space-y-8 border-t border-slate-200 pt-16">
            <div class="text-center max-w-xl mx-auto">
                <h2 class="text-2xl font-black text-slate-950 uppercase tracking-tight">Everything You Need to Run the Field</h2>
                <p class="text-xs text-slate-500 font-medium">Simple, professional workflows designed to remove friction from your day‑to‑day operations.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                    <span class="text-xl block">Build Estimates in Minutes</span>
                    <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                        No spreadsheets. No guesswork. No “I’ll get that to you tonight” lies. Just clean, consistent estimates built from your standardized pricebook.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                    <span class="text-xl block">Convert Estimates to Invoices Instantly</span>
                    <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                        One tap. No retyping. No double entry. You’ve got better things to do — like actually doing the job.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                    <span class="text-xl block">Quick Messaging to Customers</span>
                    <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                        Send updates, reminders, approvals, and payment links right from the field. Fast communication = fewer cancellations and fewer “just checking in” texts.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                    <span class="text-xl block">Capture and Send Job Photos Instantly</span>
                    <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                        Snap photos, mark them up, and attach them to estimates or invoices. Protect your margins. Protect your sanity. Protect yourself from “I didn’t agree to that.”
                    </p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                    <span class="text-xl block">Calendar Reminders & Job Tracking</span>
                    <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                        Never forget a follow‑up, appointment, or scheduled job again. Your calendar finally works with you instead of against you.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                    <span class="text-xl block">On‑the‑Spot Add‑On Invoices</span>
                    <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                        Customer wants “one more thing” while you’re already there? Tap, add, send — and get paid before you leave.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                    <span class="text-xl block">Text‑to‑Pay for Faster Cashflow</span>
                    <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                        Your customer taps a secure link, signs, and pays. No portals. No apps. No chasing checks. Just money in the bank.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                    <span class="text-xl block">Automated Review Collection</span>
                    <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                        After payment, the system sends a timed review request. You get more Google reviews without begging, bribing, or reminding.
                    </p>
                </div>

            </div>
        </section>

        <section class="bg-slate-900 rounded-3xl p-6 sm:p-10 text-white shadow-md grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            <div class="md:col-span-8 space-y-3">
                <span class="text-xs font-black uppercase text-[#f58613] tracking-widest block">Unified Lead Generation Layouts</span>
                <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tight">Your Business Profile — Simple, Search‑Friendly, and Professional</h3>
                <p class="text-sm text-slate-300 font-medium leading-relaxed">
                    Every account includes a public profile that showcases your services, reviews, service area, and contact info. It’s SEO‑friendly, it's not a new website but it will be seen — it’s simply a clean, trustworthy place for customers to learn who you are.
                </p>
            </div>
            <div class="md:col-span-4 flex justify-center md:justify-end">
                <div class="px-5 py-3 bg-slate-950 border border-slate-800 rounded-xl font-mono text-xs text-amber-400 font-bold shadow-inner">
                    🌐 public-directory-node
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-12 border-t border-slate-200 pt-16 items-start">

            <div class="space-y-4">
                <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">Built for the Small and Solo Contractor</h3>
                <p class="text-sm text-slate-600 font-semibold leading-relaxed">
                    Whether you’re a one "man" show, or have a small crew, you deserve the same tools the big companies use — without the big‑company overhead. We focus entirely on:
                </p>
                <ul class="text-xs text-slate-700 font-black space-y-2 font-mono uppercase pl-1">
                    <li class="flex items-center gap-2"><span class="text-[#f58613]">▪</span> Speed</li>
                    <li class="flex items-center gap-2"><span class="text-[#f58613]">▪</span> Simplicity</li>
                    <li class="flex items-center gap-2"><span class="text-[#f58613]">▪</span> Profit protection</li>
                    <li class="flex items-center gap-2"><span class="text-[#f58613]">▪</span> Real‑world workflows</li>
                    <li class="flex items-center gap-2"><span class="text-[#f58613]">▪</span> Pricing that respects small business margins</li>
                </ul>
                <p class="text-xs text-slate-500 font-medium italic">No fluff. No bloat. No enterprise nonsense.</p>
            </div>

            <div class="space-y-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">We’re Here to Make Contractors More Profitable — Period</h3>
                <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                    Not with dashboards you’ll never open. Not with features you don’t need. Not with pricing that punishes small business. Just simple, powerful tools that help you:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-bold text-slate-900 pt-2">
                    <div class="flex items-center gap-2">⚡ Win more jobs</div>
                    <div class="flex items-center gap-2">⚡ Get paid faster</div>
                    <div class="flex items-center gap-2">⚡ Protect your margins</div>
                    <div class="flex items-center gap-2">⚡ Build your reputation</div>
                    <div class="col-span-2 flex items-center gap-2">⚡ Grow at your pace</div>
                </div>
            </div>

        </section>

        <section class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-8">
            <div class="text-center max-w-xl mx-auto space-y-2">
                <h2 class="text-2xl font-black text-slate-950 uppercase tracking-tight">Straightforward Monthly Pricing</h2>
                <p class="text-xs text-slate-500 font-medium">Simple, honest access tiers constructed to respect small field operation parameters.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-2xl mx-auto">
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 space-y-3">
                    <span class="text-xs font-black bg-slate-900 text-white px-2.5 py-0.5 rounded-md uppercase tracking-wider inline-block">Solo Account</span>
                    <div class="flex items-baseline gap-1 font-mono">
                        <span class="text-3xl font-black text-slate-950">$24.95</span>
                        <span class="text-xs font-bold text-slate-400 uppercase">/ month</span>
                    </div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-tight">Single User Access Parameters</p>
                </div>

                <div class="bg-white border-2 border-slate-950 rounded-2xl p-6 space-y-3 shadow-md">
                    <span class="text-xs font-black bg-[#f58613] text-white px-2.5 py-0.5 rounded-md uppercase tracking-wider inline-block">Crew Account</span>
                    <div class="flex items-baseline gap-1 font-mono">
                        <span class="text-3xl font-black text-slate-950">$39.95</span>
                        <span class="text-xs font-bold text-slate-400 uppercase">/ month</span>
                    </div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-tight">Up to 5 Active Field Users</p>
                </div>
            </div>

            <div class="max-w-xl mx-auto text-center font-bold text-xs text-slate-500 leading-normal border-t border-slate-100 pt-6">
                More than 5? We’ll work out a fair rate when you’re ready. <br class="hidden sm:inline">
                <span class="text-slate-900 block mt-2 font-black">
                    And yes — if you don’t use it that month, it’s free. It’s our policy. Not a sales pitch.
                </span>
            </div>
        </section>

    </main>

    <footer class="border-t border-slate-900 bg-black text-slate-400 py-12">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

            <div class="md:col-span-5 flex flex-col items-center md:items-start gap-4">
                <div class="w-[400px] max-w-full aspect-square bg-slate-950 border border-slate-900 rounded-2xl overflow-hidden shadow-lg flex items-center justify-center">
                    <img src="/images/footer-logo.webp" alt="Corporate Brand Mark" class="w-full h-full object-contain p-4">
                </div>
                <div class="text-xs font-medium text-slate-500 text-center md:text-left mt-1">
                    &copy; 2026 ContractorSpecialties.<br>
                    ContractorSpecialties is owned and operated by Contractor Service Pros LLC.<br>
                    All corporate directories and security lines secure.
                </div>
            </div>

            <div class="md:col-span-7 grid grid-cols-2 sm:grid-cols-4 gap-6 text-xs font-bold uppercase tracking-wider md:pt-4">
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Tools & Engine</span>
                    <a href="#access-hub" class="text-slate-400 hover:text-[#f58613] transition-colors">Estimate Creator</a>
                    <a href="#access-hub" class="text-slate-400 hover:text-[#f58613] transition-colors">Pricebook Matrix</a>
                    <a href="#access-hub" class="text-slate-400 hover:text-[#f58613] transition-colors">Text-to-Pay Rails</a>
                </div>
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Directories</span>
                    <a href="/advertise" class="text-slate-400 hover:text-[#f58613] transition-colors">Advertise With Us</a>
                    <a href="/contractor-directory" class="text-slate-400 hover:text-[#f58613] transition-colors">Public Directory</a>
                    <a href="/leads" class="text-slate-400 hover:text-[#f58613] transition-colors">Resource Funnels</a>
                </div>
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Legal & Policy</span>
                    <a href="/privacy" class="text-slate-400 hover:text-[#f58613] transition-colors normal-case">Privacy Policy</a>
                    <a href="/terms" class="text-slate-400 hover:text-[#f58613] transition-colors normal-case">Terms of Use</a>
                </div>
                <div class="flex flex-col gap-2.5">
                    <span class="text-[10px] text-slate-600 tracking-widest font-black">Secure Entry</span>
                    <a href="/login/partner" class="text-slate-500 hover:text-white transition-colors bg-slate-900 border border-slate-800 px-3 py-2 rounded-lg text-center truncate">General Contractor</a>
                    <a href="/login/subcontractor" class="text-slate-500 hover:text-white transition-colors bg-slate-900 border border-slate-800 px-3 py-2 rounded-lg text-center truncate mt-1">Sub-Portal</a>
                    <a href="/tutorial" class="text-[#f58613] hover:text-orange-500 transition-colors normal-case mt-1.5 font-black tracking-wide italic">How-To Manual 📺</a>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
