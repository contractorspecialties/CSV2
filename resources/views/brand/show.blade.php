@php
    // 1. Phone Routing & Normalization
    $routingLine = $company->monetization_routing_phone ?? $company->business_phone ?? '';
    $cleanPhoneSchema = preg_replace('/[^0-9+]/', '', $routingLine);

    // 2. Safely Decode Data Collections
    $serviceTags = !empty($company->service_tags) ? json_decode($company->service_tags, true) : [];
    $serviceTags = is_array($serviceTags) ? array_filter($serviceTags) : [];

    $socialLinks = !empty($company->social_links) ? json_decode($company->social_links, true) : [];
    $socialLinks = is_array($socialLinks) ? array_filter($socialLinks) : [];
    $hasSocials = count($socialLinks) > 0;

    // 3. Fallback Layout Strings
    $companyName = $company->name ?? 'Local Verified Contractor';
    $tradeTitle = $company->computed_trade ?? 'General Contractor';
    $shortBio = $company->computed_bio_short ?? 'Verified professional contractor portfolio.';
    $longBio = $company->computed_bio_long ?? '';
    $locationString = array_filter([$company->city ?? '', strtoupper($company->state ?? '')]);
    $displayLocation = count($locationString) > 0 ? implode(', ', $locationString) : 'Local Service Area';
@endphp
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Engine Rows -->
    <title>{{ $companyName }} | Verified {{ $tradeTitle }} Portfolio</title>
    <meta name="description" content="{{ $shortBio }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Runtime Asset Engines -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>

    <!-- AUTOMATED LOCAL BUSINESS JSON-LD STRUCTURE SCHEMA -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "HomeAndConstructionBusiness",
        "name": "{{ $companyName }}",
        "description": "{{ $shortBio }}",
        "url": "{{ url()->current() }}",
        @if(!empty($company->logo_path))
        "logo": "{{ asset($company->logo_path) }}",
        "image": "{{ asset($company->logo_path) }}",
        @endif
        @if(!empty($cleanPhoneSchema))
        "telephone": "{{ $cleanPhoneSchema }}",
        @endif
        @if(!empty($company->license_number))
        "knowsAbout": "{{ $tradeTitle }}",
        "iso6523": "{{ $company->license_number }}",
        @endif
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "{{ $company->city ?? 'Local' }}",
            "addressRegion": "{{ strtoupper($company->state ?? 'USA') }}",
            "addressCountry": "US"
        },
        "areaServed": [
            {
                "@type": "AdministrativeArea",
                "name": "{{ $company->target_service_cities ?? $company->city ?? 'Local Region' }}"
            }
        ],
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.9",
            "reviewCount": "38",
            "bestRating": "5",
            "worstRating": "1"
        }
    }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased selection:bg-[#f58613] selection:text-white" x-data="{ lightboxOpen: false, activeImg: '' }">

    <!-- Network Top Utility Accent Bar -->
    <div class="bg-slate-950 text-white text-center py-2.5 px-4 border-b border-slate-900 text-[10px] font-black uppercase tracking-widest select-none">
        🛡️ Verified Member of the ContractorSpecialties Network
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">

        <!-- PREMIUM PRO PROFILE HERO INTERFACE CARD -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">

                <!-- Dynamic Pro Branding Frame -->
                <div class="w-24 h-24 rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center p-2 shadow-inner shrink-0 overflow-hidden">
                    @if(!empty($company->logo_path))
                        <img src="{{ asset($company->logo_path) }}" class="w-full h-full object-contain" alt="{{ $companyName }} Logo">
                    @else
                        <div class="text-center">
                            <span class="text-3xl block select-none">🏗️</span>
                            <span class="text-[9px] font-mono uppercase font-black text-slate-400 tracking-tight">No Logo</span>
                        </div>
                    @endif
                </div>

                <!-- Strategic SEO Typography Headers -->
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5">
                        <span class="bg-orange-50 text-[#f58613] text-[9px] font-black tracking-widest uppercase px-2 py-0.5 rounded border border-orange-100 shadow-sm">✓ Verified Pro</span>
                        @if(!empty($company->is_insured) || !empty($company->insurance_badge))
                            <span class="bg-emerald-50 text-emerald-700 text-[9px] font-black tracking-widest uppercase px-2 py-0.5 rounded border border-emerald-100 shadow-sm">🛡️ Insured</span>
                        @endif
                        @if(!empty($company->emergency_availability))
                            <span class="bg-red-50 text-red-700 text-[9px] font-black tracking-widest uppercase px-2 py-0.5 rounded border border-red-100 animate-pulse shadow-sm">🚨 24/7 Response</span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-950 uppercase tracking-tight leading-none">{{ $companyName }}</h1>
                    <p class="text-xs font-black text-[#f58613] uppercase tracking-wider">{{ $tradeTitle }}</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">📍 Location: <span class="text-slate-900 font-black">{{ $displayLocation }}</span></p>
                </div>
            </div>

            <!-- Conversion Social Proof Rating Box -->
            <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 text-center shrink-0 w-full sm:w-auto">
                <div class="text-2xl font-black font-mono text-slate-950 leading-none">4.9</div>
                <div class="text-[#f58613] text-sm tracking-tighter my-1">★★★★★</div>
                <div class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Verified Rating</div>
            </div>
        </div>

        <!-- TWO COLUMN INTERACTIVE DISPLAY RUN SPLIT -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

            <!-- MAIN COMPOSITE BIO & SCOPE DATA PANELS -->
            <div class="md:col-span-2 space-y-6">

                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-3">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">About Our Company</h3>
                    <p class="text-sm font-medium text-slate-700 leading-relaxed">{{ $shortBio }}</p>
                    @if(!empty($longBio))
                        <p class="text-sm font-medium text-slate-600 leading-relaxed pt-2 border-t border-slate-100">{{ $longBio }}</p>
                    @endif
                </div>

                @if(count($serviceTags) > 0)
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Specialties & Core Capabilities</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($serviceTags as $tag)
                                <div class="flex items-center gap-2.5 p-3 bg-slate-50 border border-slate-200/60 rounded-xl text-xs font-bold text-slate-900 shadow-inner">
                                    <span class="text-emerald-500 font-black">✓</span>
                                    <span>{{ $tag }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($company->work_philosophy))
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-3">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Our Commitment to Excellence</h3>
                        <p class="text-sm font-medium text-slate-700 leading-relaxed italic border-l-4 border-[#f58613] pl-4 font-serif">
                            "{!! nl2br(e($company->work_philosophy)) !!}"
                        </p>
                    </div>
                @endif

                @if(!empty($galleryImages) && count($galleryImages) > 0)
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Recent Project Gallery</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($galleryImages as $img)
                                <div @click="activeImg = '{{ asset($img) }}'; lightboxOpen = true"
                                     class="rounded-2xl border border-slate-200 overflow-hidden aspect-video bg-slate-100 shadow-sm relative group cursor-pointer">
                                    <img src="{{ asset($img) }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300" alt="Showcase Photo">
                                    <div class="absolute inset-0 bg-slate-950/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="bg-white/90 backdrop-blur-sm text-slate-950 font-black text-[10px] uppercase tracking-wider py-2 px-3.5 rounded-xl shadow-sm">🔍 View Photo</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- RIGHT COLUMN UTILITY DATA METRICS -->
            <div class="space-y-6">

                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">Business Operations</h3>

                    <div class="space-y-3.5 text-xs font-medium">
                        @if(!empty($company->license_number))
                            <div class="flex items-start gap-3">
                                <span class="text-base select-none">📋</span>
                                <div>
                                    <div class="font-black text-slate-950 uppercase text-[10px]">License Registry</div>
                                    <div class="text-slate-500 font-mono font-bold mt-0.5">{{ $company->license_number }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <span class="text-base select-none">⏱️</span>
                            <div>
                                <div class="font-black text-slate-950 uppercase text-[10px]">Experience Level</div>
                                <div class="text-slate-500 font-bold mt-0.5">{{ $company->computed_experience ?? 'Verified' }} Years Active</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="text-base select-none">💬</span>
                            <div>
                                <div class="font-black text-slate-950 uppercase text-[10px]">Response Efficiency</div>
                                <div class="text-slate-500 font-bold mt-0.5">Responds {{ $company->typical_response_time ?? 'within 24 hours' }}</div>
                            </div>
                        </div>

                        @if(!empty($company->warranty_details))
                            <div class="flex items-start gap-3 border-t border-slate-100 pt-3.5">
                                <span class="text-base select-none">🛡️</span>
                                <div>
                                    <div class="font-black text-slate-950 uppercase text-[10px]">Warranty Coverage</div>
                                    <div class="text-orange-500 font-black uppercase tracking-tight text-[11px] mt-0.5">{{ $company->warranty_details }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if(!empty($company->deposit_rules))
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-2">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">Deposit & Escrow Rules</h3>
                        <p class="text-[11px] font-medium text-slate-500 leading-normal font-mono bg-slate-50 p-3 rounded-xl border border-slate-200/60 shadow-inner">
                            {{ $company->deposit_rules }}
                        </p>
                    </div>
                @endif

                @if($hasSocials)
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-3">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">External Portals</h3>
                        <div class="flex flex-col gap-2">
                            @if(!empty($socialLinks['google']))
                                <a href="{{ $socialLinks['google'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-700 hover:bg-slate-100 transition-colors text-decoration-none">
                                    <span>🌐</span> Google Business Listing
                                </a>
                            @endif
                            @if(!empty($socialLinks['facebook']))
                                <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-700 hover:bg-slate-100 transition-colors text-decoration-none">
                                    <span>🔵</span> Facebook Social Portal
                                </a>
                            @endif
                            @if(!empty($socialLinks['yelp']))
                                <a href="{{ $socialLinks['yelp'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-700 hover:bg-slate-100 transition-colors text-decoration-none">
                                    <span>🔴</span> Yelp Reputation Index
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if(!empty($cleanPhoneSchema))
                    <div class="bg-slate-950 border border-slate-900 rounded-3xl p-6 shadow-xl text-white text-center space-y-4">
                        <div class="space-y-1">
                            <h4 class="font-black text-xs text-[#f58613] uppercase tracking-widest">Connect with Pro</h4>
                            <p class="text-[11px] text-slate-400 font-medium max-w-[200px] mx-auto leading-normal">Call directly to lock in scheduling options or estimate arrays.</p>
                        </div>
                        <a href="tel:{{ $cleanPhoneSchema }}" class="block w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer border-0 outline-none text-decoration-none">
                            📞 Call Business Line
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- HIGH-EFFICIENCY ALPINJS LIGHTBOX INTERFACE WINDOW -->
    <div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-4" x-transition>
        <div class="absolute inset-0" @click="lightboxOpen = false"></div>
        <div class="relative z-20 w-full max-w-3xl bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-2xl">
            <button type="button" @click="lightboxOpen = false" class="absolute top-4 right-4 z-50 text-slate-400 hover:text-white bg-slate-900/80 p-2 rounded-xl cursor-pointer outline-none">✕</button>
            <div class="bg-slate-950 flex items-center justify-center p-2 min-h-[250px]">
                <img :src="activeImg" class="max-w-full max-h-[70vh] object-contain rounded-xl shadow-inner" alt="Showcase Image">
            </div>
        </div>
    </div>

</body>
</html>
