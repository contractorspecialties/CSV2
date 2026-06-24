<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->name }} | Verified Contractor Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased selection:bg-[#f58613] selection:text-white">

    <!-- Trust Guard Bar -->
    <div class="bg-slate-950 text-white text-center py-2.5 px-4 border-b border-slate-900 text-[10px] font-black uppercase tracking-widest">
        🛡️ Secure Identity Profile • Verified Corporate Member of ContractorSpecialties Network
    </div>

    <!-- Main Viewport Layout -->
    <div class="max-w-4xl mx-auto px-4 py-10 space-y-8">

        <!-- Header: Legitimacy & Identity Anchor Card -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">

                <!-- Brand Mark Logo Container with Dynamic Fallback -->
                <div class="w-24 h-24 rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center p-2 shadow-inner shrink-0 overflow-hidden">
                    @if(!empty($company->logo_path))
                        <img src="/{{ $company->logo_path }}" class="w-full h-full object-contain" alt="{{ $company->name }} Logo">
                    @else
                        <div class="text-center">
                            <span class="text-3xl block select-none">🏢</span>
                            <span class="text-[9px] font-mono uppercase font-black text-slate-400 tracking-tight">No Logo</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-2">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <span class="bg-orange-50 text-[#f58613] text-[9px] font-black tracking-widest uppercase px-2.5 py-1 rounded-md border border-orange-100 shadow-sm">✓ Verified Contractor</span>
                        @if(!empty($company->insurance_badge))
                            <span class="bg-emerald-50 text-emerald-700 text-[9px] font-black tracking-widest uppercase px-2.5 py-1 rounded-md border border-emerald-100 shadow-sm">🛡️ Licensed & Insured</span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-950 uppercase tracking-tight leading-none">{{ $company->name }}</h1>

                    @if(!empty($company->city))
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            📍 Primary Market Area: <span class="text-slate-900 font-black">{{ $company->city }}{{ !empty($company->state) ? ', ' . strtoupper($company->state) : '' }}</span>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Social Proof Star Anchor Row -->
            <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 text-center shrink-0 w-full sm:w-auto">
                <div class="text-2xl font-black font-mono text-slate-950 leading-none">4.9</div>
                <div class="text-[#f58613] text-sm tracking-tighter my-1">★★★★★</div>
                <div class="text-[9px] font-black uppercase text-slate-400 tracking-wider">42 Verified Reviews</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

            <!-- Left Side Core Narrative Column Matrix -->
            <div class="md:col-span-2 space-y-6">

                <!-- Personal Reliability Deck -->
                @if(!empty($company->company_bio))
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-3">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">About Our Operation</h3>
                        <p class="text-sm font-medium text-slate-700 leading-relaxed">{{ $company->company_bio }}</p>
                    </div>
                @endif

                @if(!empty($company->work_philosophy))
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-3">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Our Commitment To You</h3>
                        <p class="text-sm font-medium text-slate-700 leading-relaxed italic border-l-4 border-[#f58613] pl-4 font-serif">
                            "{!! nl2br(e($company->work_philosophy)) !!}"
                        </p>
                    </div>
                @endif

                <!-- Visual Proof Photo Grid -->
                @if(!empty($galleryImages) && count($galleryImages) > 0)
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Showcase Proof of Recent Work</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($galleryImages as $img)
                                <div class="rounded-2xl border border-slate-100 overflow-hidden aspect-video bg-slate-50 shadow-sm">
                                    <img src="/{{ $img }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" alt="Job site showcase photography">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Side Sidebar Checklist Array -->
            <div class="space-y-6">

                <!-- Guarantees & Credentials Quick Matrix -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">Compliance & Safety</h3>

                    <div class="space-y-3.5 text-xs font-medium">
                        @if(!empty($company->license_number))
                            <div class="flex items-start gap-3">
                                <span class="text-base">📋</span>
                                <div>
                                    <div class="font-black text-slate-950 uppercase text-[10px]">Verified License</div>
                                    <div class="text-slate-500 font-mono font-bold mt-0.5">{{ $company->license_number }}</div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($company->years_in_business))
                            <div class="flex items-start gap-3">
                                <span class="text-base">⏱️</span>
                                <div>
                                    <div class="font-black text-slate-950 uppercase text-[10px]">Time In Service</div>
                                    <div class="text-slate-500 font-bold mt-0.5">{{ $company->years_in_business }} Years Active</div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <span class="text-base">💬</span>
                            <div>
                                <div class="font-black text-slate-950 uppercase text-[10px]">Response Efficiency</div>
                                <div class="text-slate-500 font-bold mt-0.5">Typically responds {{ $company->typical_response_time }}</div>
                            </div>
                        </div>

                        @if(!empty($company->warranty_details))
                            <div class="flex items-start gap-3 border-t border-slate-100 pt-3.5">
                                <span class="text-base">🛡️</span>
                                <div>
                                    <div class="font-black text-slate-950 uppercase text-[10px]">Warranty Protection Block</div>
                                    <div class="text-orange-500 font-black uppercase tracking-tight text-[11px] mt-0.5">{{ $company->warranty_details }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Instant Action Button Card -->
                <div class="bg-slate-950 border border-slate-900 rounded-3xl p-6 shadow-xl text-white text-center space-y-4">
                    <div class="space-y-1">
                        <h4 class="font-black text-xs text-[#f58613] uppercase tracking-widest">Need Immediate Attention?</h4>
                        <p class="text-[11px] text-slate-400 font-medium max-w-[200px] mx-auto leading-normal">Our system pipes text messages instantly straight to the foreman on duty.</p>
                    </div>
                    <a href="tel:{{ $company->sms_phone_number ?? '' }}" class="block w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer">
                        📞 Call Dispatch Line
                    </a>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
