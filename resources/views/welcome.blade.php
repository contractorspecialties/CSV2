<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ContractorSpecialties | One Tool. 83.4% Fewer Headaches.</title>
    <meta name="description" content="Simple estimates, invoicing, scheduling, and crew flow built for real contractors. Zero corporate nonsense.">

    <!-- Client Runtime Core Engines -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased selection:bg-[#f58613] selection:text-white">

    <!-- Top Utility Trim Banner -->
    <div class="bg-slate-900 text-center py-2.5 px-4 border-b border-slate-800 text-[10px] font-black uppercase tracking-widest select-none text-slate-400">
        ⚡ Built for Field Crews &bull; Zero Glass Conference Room Fluff
    </div>

    <!-- MAIN HERO CONTENT SECTION (RESPONSIVE ASYMMETRICAL SPLIT) -->
    <div class="max-w-6xl mx-auto px-4 pt-10 pb-16">
        <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">

            <!-- Left Side (Desktop) / Top (Mobile): Action Media Frame -->
            <div class="w-full lg:w-1/2 shrink-0">
                <div class="relative rounded-3xl overflow-hidden border border-slate-800 bg-slate-900 aspect-video lg:aspect-[4/3] shadow-2xl shadow-orange-500/5 group">

                    <!-- Core Asset Target Drop Frame -->
                    <!-- Pro tip: Replace this image path with a local mp4 video track loop if preferred later today -->
                    <img src="{{ asset('images/hero-CS1.webp') }}"
                         fallback-src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=1200"
                         alt="Contractor building field estimates on a mobile phone on-site"
                         class="w-full h-full object-cover mix-blend-luminosity group-hover:mix-blend-normal transition-all duration-500 transform scale-100 group-hover:scale-[1.01]">

                    <!-- Ambient Grid Overlay Elements -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 bg-slate-950/80 backdrop-blur border border-slate-800 rounded-xl px-3 py-1.5 text-[9px] font-black uppercase tracking-widest text-slate-400 select-none">
                        📸 Field Sandbox Preview Loop
                    </div>
                </div>
            </div>

            <!-- Right Side (Desktop) / Bottom (Mobile): Text Hook Content Block -->
            <div class="w-full lg:w-1/2 space-y-6 text-center lg:text-left">
                <div class="space-y-3">
                    <span class="inline-block bg-orange-500/10 text-[#f58613] text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-orange-500/20 shadow-sm">
                        Operational Command Center
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black italic tracking-tighter uppercase leading-none text-white">
                        One Tool. 83.4% Fewer Headaches. <span class="text-[#f58613]">Zero Corporate Nonsense.</span>
                    </h1>
                </div>

                <p class="text-slate-400 font-medium text-sm sm:text-base leading-relaxed">
                    Running a service business shouldn’t feel like juggling chainsaws while answering texts from customers who <span class="text-white italic font-bold">“just have one more question.”</span>
                </p>

                <p class="text-xs text-slate-500 font-bold">
                    So we built the tool that handles the admin chaos you hate: all in one clean place, all stupidly simple, all built for real contractors.
                </p>

                <div class="pt-2 flex flex-wrap justify-center lg:justify-start gap-3">
                    <a href="#terminal" class="bg-gradient-to-r from-amber-500 to-[#f58613] hover:from-amber-600 hover:to-orange-600 text-white font-black text-xs py-3.5 px-6 rounded-xl tracking-widest uppercase shadow-lg transition-transform active:scale-[0.99] text-decoration-none">
                        Get Started Free &rarr;
                    </a>
                    <a href="/quick-estimate" class="bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 font-bold text-xs py-3.5 px-6 rounded-xl transition-all text-decoration-none">
                        Test Drive Simulator
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- SECTION: THE STUFF YOU HATE Automated Grid Blocks -->
    <div class="bg-slate-900/60 border-t border-b border-slate-800/80 py-16 px-4">
        <div class="max-w-5xl mx-auto space-y-12">

            <div class="text-center space-y-2">
                <h2 class="text-3xl font-black italic uppercase text-white tracking-tight">
                    The Stuff You Hate Doing… <span class="text-[#f58613]">Automated</span>
                </h2>
                <p class="text-xs text-slate-400 font-bold max-w-md mx-auto leading-normal">
                    You didn’t start a service business because you love admin work. You started it because you’re good at the actual job. This is that part of your day unified.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $featuresList = [
                        ['📄', 'Estimates That Look Elite', 'Build sharp, professional proposals in 45 seconds. No spreadsheets. No guesswork. No “I’ll send that text tonight.”'],
                        ['💳', 'Invoices That Get Paid', 'Clear totals, clean structural item formatting, and direct mobile payment links customers actually click immediately.'],
                        ['📅', 'Zero Group Chat Chaos', 'Assign project scopes, update deployment coordinates, and keep crews synced without 14 phone alerts asking “what time tomorrow?”'],
                        ['📸', 'Photos Without the Mess', 'Every structural project log, every field image, every site document — automatically organized and bound directly to the account profile.'],
                        ['💬', 'Professional Client Messaging', 'Keep your customer updates clean, tracked, and isolated. No more frantic texts hitting your personal cell number at 9:47 PM.'],
                        ['👷', 'Soul-Saving Crew Flow', 'Everyone sees their assigned tracking routes on site layout sheets. No more morning log delays or missing field parameters.']
                    ];
                @endphp

                @foreach($featuresList as $item)
                    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-2.5">
                        <div class="text-xl select-none">{{ $item[0] }}</div>
                        <h4 class="font-black text-sm uppercase text-white tracking-tight">{{ $item[1] }}</h4>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed">{{ $item[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- SECTION: PRICING TIERS -->
    <div class="max-w-4xl mx-auto px-4 py-16 text-center space-y-10">
        <div class="space-y-2">
            <h2 class="text-3xl font-black italic uppercase text-white tracking-tight">Straightforward Monthly Pricing</h2>
            <p class="text-xs text-slate-400 font-bold max-w-sm mx-auto leading-normal">
                No games. No hidden system fees. <br>
                <span class="text-emerald-400">And yes — if you don’t use it that month, it’s free.</span> That’s our policy.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left max-w-2xl mx-auto">

            <!-- Solo Tier -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between space-y-6">
                <div class="space-y-3">
                    <div>
                        <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider">Solo Account</h4>
                        <div class="text-3xl font-black font-mono text-white mt-1">$24.95<span class="text-xs text-slate-500">/mo</span></div>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium leading-relaxed">For the one-man show who handles quoting, operations, scheduling, and occasionally questioning life choices.</p>
                    <ul class="space-y-1.5 text-xs font-bold text-slate-200 border-t border-slate-800/60 pt-4">
                        <li>• 1 Active User Entry</li>
                        <li>• Full Pricebook Manager</li>
                        <li>• Unlimited Estimates & Invoices</li>
                        <li>• Premium Profile Directory Node</li>
                    </ul>
                </div>
                <a href="#terminal" class="block w-full text-center bg-slate-800 hover:bg-slate-700 text-white font-black text-[11px] uppercase tracking-widest py-3 rounded-xl transition-all text-decoration-none">Select Solo Command</a>
            </div>

            <!-- Crew Tier -->
            <div class="bg-slate-900 border border-[#f58613] rounded-3xl p-6 flex flex-col justify-between space-y-6 relative shadow-xl shadow-orange-500/5">
                <span class="absolute -top-3 right-6 bg-[#f58613] text-white font-black uppercase tracking-widest text-[8px] px-2.5 py-1 rounded-full">Most Popular</span>
                <div class="space-y-3">
                    <div>
                        <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider">Crew Account</h4>
                        <div class="text-3xl font-black font-mono text-white mt-1">$39.95<span class="text-xs text-slate-500">/mo</span></div>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium leading-relaxed">For small teams who need everyone on the same page — completely bypassing the group chat dumpster fires.</p>
                    <ul class="space-y-1.5 text-xs font-bold text-slate-200 border-t border-slate-800/60 pt-4">
                        <li>• Up to 5 Active Users</li>
                        <li>• Shared Master Schedule Maps</li>
                        <li>• Real-Time Crew Coordination</li>
                        <li>• Field Photo Vault Storage</li>
                    </ul>
                </div>
                <a href="#terminal" class="block w-full text-center bg-gradient-to-r from-amber-500 to-[#f58613] text-white font-black text-[11px] uppercase tracking-widest py-3 rounded-xl transition-all shadow-md text-decoration-none">Select Crew Command</a>
            </div>

        </div>
    </div>

    <!-- UNIFIED INTAKE TERMINAL CONTAINER -->
    <div id="terminal" class="max-w-md mx-auto px-4 pb-20"
         x-data="{
            intent: 'register',
            get formAction() {
                if (this.intent === 'estimate') return '/quick-estimate';

                @if(Route::has('magic.auth.send')) return '{{ route('magic.auth.send') }}';
                @elseif(Route::has('magic.link.send')) return '{{ route('magic.link.send') }}';
                @elseif(Route::has('magic.send')) return '{{ route('magic.send') }}';
                @else return '/login'; @endif
            },
            get buttonText() {
                return this.intent === 'register'
                    ? 'Request Dashboard Token &rarr;'
                    : 'Open Live Estimate Simulator &rarr;';
            }
         }">

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-6">

            <div class="text-center space-y-1.5">
                <h3 class="text-xl font-black italic uppercase text-white tracking-tight">Instant Gateway Entrance</h3>
                <p class="text-[11px] text-slate-400 font-semibold leading-normal">Choose your target intent configuration to arm your data pipeline.</p>
            </div>

            <!-- Track Radio Split Buttons -->
            <div class="space-y-2">
                <div @click="intent = 'register'" :class="intent === 'register' ? 'border-[#f58613] bg-orange-500/5' : 'border-slate-800 bg-slate-950/40'" class="border rounded-xl p-3 flex items-center justify-between cursor-pointer transition-all select-none">
                    <div class="text-left">
                        <span class="block text-xs font-black uppercase text-white">Sign In / Activate Profile</span>
                        <span class="block text-[9px] text-slate-500 font-bold">Drops you straight into your dashboard — no passwords, no mess.</span>
                    </div>
                    <div class="w-3 h-3 rounded-full border border-slate-700 shrink-0 ml-2" :class="intent === 'register' && 'bg-[#f58613] border-orange-600'"></div>
                </div>

                <div @click="intent = 'estimate'" :class="intent === 'estimate' ? 'border-[#f58613] bg-orange-500/5' : 'border-slate-800 bg-slate-950/40'" class="border rounded-xl p-3 flex items-center justify-between cursor-pointer transition-all select-none">
                    <div class="text-left">
                        <span class="block text-xs font-black uppercase text-white">Build Estimate In 45 Seconds</span>
                        <span class="block text-[9px] text-slate-500 font-bold">See exactly how sharp your company looks before creating an account.</span>
                    </div>
                    <div class="w-3 h-3 rounded-full border border-slate-700 shrink-0 ml-2" :class="intent === 'estimate' && 'bg-[#f58613] border-orange-600'"></div>
                </div>
            </div>

            <!-- Unified Form Action Node -->
            <form :action="formAction" :method="intent === 'register' ? 'POST' : 'GET'" class="space-y-4">
                @csrf
                <input type="hidden" name="system_verification_token" value="">

                <div>
                    <label class="block text-[9px] font-black uppercase text-slate-500 tracking-wider mb-1" for="email">Business Email Address</label>
                    <input type="email" id="email" name="email" value="{{ request('email') }}" required placeholder="name@yourcompany.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl py-2.5 px-4 text-xs font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner">
                </div>

                <div class="pt-2">
                    <button type="submit" x-html="buttonText" class="w-full bg-gradient-to-r from-amber-500 to-[#f58613] text-white font-black text-xs py-3.5 rounded-xl tracking-widest uppercase shadow-lg border-0 cursor-pointer outline-none"></button>
                    <p class="text-center text-[8px] font-black tracking-widest text-slate-500 uppercase mt-3 leading-normal">
                        📲 Transactional alerts only. No spam. Reply STOP anytime.
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
