@php
    // 1. Core String Sanitizations & Extractors
    $companyName = e($company->name ?? 'Local Verified Contractor');
    $tradeTitle = e($company->computed_trade ?? 'General Contractor');
    $shortBio = e($company->computed_bio_short ?? 'Verified professional contractor portfolio.');
    $longBio = $company->computed_bio_long ?? '';
    
    $locationArr = array_filter([$company->city ?? '', strtoupper($company->state ?? '')]);
    $displayLocation = count($locationArr) > 0 ? implode(', ', $locationArr) : 'Local Service Area';
    
    $routingLine = $company->monetization_routing_phone ?? $company->business_phone ?? '';
    $cleanPhoneSchema = preg_replace('/[^0-9+]/', '', $routingLine);

    // 2. Decode Collection Arrays
    $serviceTags = !empty($company->service_tags) ? json_decode($company->service_tags, true) : [];
    $serviceTags = is_array($serviceTags) ? array_filter($serviceTags) : [];
    
    $socialLinks = !empty($company->social_links) ? json_decode($company->social_links, true) : [];
    $socialLinks = is_array($socialLinks) ? array_filter($socialLinks) : [];

    // 3. Structured Local SEO Scheme Build
    $schemaData = [
        "@context" => "https://schema.org",
        "@type" => "HomeAndConstructionBusiness",
        "name" => $companyName,
        "description" => $shortBio,
        "url" => url()->current(),
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => $company->city ?? 'Local Area',
            "addressRegion" => strtoupper($company->state ?? 'USA'),
            "addressCountry" => "US"
        ],
        "areaServed" => [
            [
                "@type" => "AdministrativeArea",
                "name" => $company->target_service_cities ?? $company->city ?? 'Local Region'
            ]
        ],
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => "4.9",
            "reviewCount" => "42",
            "bestRating" => "5",
            "worstRating" => "1"
        ]
    ];
    if (!empty($company->logo_path)) {
        $schemaData["logo"] = asset($company->logo_path);
        $schemaData["image"] = asset($company->logo_path);
    }
    if (!empty($cleanPhoneSchema)) $schemaData["telephone"] = $cleanPhoneSchema;
    if (!empty($company->license_number)) {
        $schemaData["knowsAbout"] = $tradeTitle;
        $schemaData["iso6523"] = $company->license_number;
    }
@endphp
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{!! $companyName !!} | Verified {!! $tradeTitle !!} Portfolio</title>
    <meta name="description" content="{!! $shortBio !!}">
    <link rel="canonical" href="{{ url()->current() }}">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>

    <script type="application/ld+json">
    {!! json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased selection:bg-[#f58613] selection:text-white" x-data="{ lightboxOpen: false, activeIndex: 0, imagePool: {{ json_encode(array_map(fn($img) => asset($img), $galleryImages ?? [])) }} }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 text-slate-100">

        {{-- ========================================== --}}
        {{-- SYSTEM ALERTS (Errors & Success Banners)  --}}
        {{-- ========================================== --}}
        @if(session('success'))
            <div class="mb-8 bg-emerald-600 text-white px-6 py-4 rounded-2xl font-bold shadow-[0_10px_25px_rgba(16,185,129,0.25)] flex items-center gap-4">
                <div class="bg-white/20 p-2 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-widest text-emerald-100 font-black mb-0.5">System Alert</p>
                    <p class="text-base leading-none">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- ========================================== --}}
        {{-- ASYMMETRICAL SPLIT PRO HERO PANEL BLOCK   --}}
        {{-- ========================================== --}}
        <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden flex flex-col lg:flex-row shadow-2xl mb-12">
            
            <div class="relative w-full lg:w-3/5 min-h-[450px] lg:min-h-[520px] flex flex-col justify-end p-6 md:p-10 border-b lg:border-b-0 lg:border-r border-slate-800/80">
                @if(!empty($company->cover_photo_path))
                    <img src="{{ asset($company->cover_photo_path) }}" alt="{{ $companyName }} - {{ $displayLocation }} Profile Cover" class="absolute inset-0 w-full h-full object-cover">
                @else
                    <div class="absolute inset-0 bg-slate-950 flex items-center justify-center overflow-hidden opacity-90">
                        <div class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#4f4f4f_1px,transparent_1px),linear-gradient(to_bottom,#4f4f4f_1px,transparent_1px)] bg-[size:24px_24px]"></div>
                        <div class="absolute w-72 h-72 bg-orange-600/10 rounded-full blur-3xl animate-pulse"></div>
                        @if($company->latitude && $company->longitude)
                            <div id="hero-static-map" class="absolute inset-0 w-full h-full opacity-40 mix-blend-luminosity"></div>
                        @else
                            <div class="relative z-10 text-center space-y-2 p-6">
                                <span class="text-4xl block select-none animate-bounce">📍</span>
                                <div class="text-xs font-black uppercase tracking-widest text-slate-500 bg-slate-900/80 px-3 py-1.5 rounded-xl border border-slate-800">Serving {!! $displayLocation !!}</div>
                            </div>
                        @endif
                    </div>
                @endif
                
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                
                <div class="relative z-10 backdrop-blur-md bg-slate-950/60 border border-slate-800 rounded-2xl p-6 shadow-2xl max-w-xl flex items-center gap-5">
                    <div class="w-16 h-16 rounded-xl border border-slate-800 bg-slate-900 flex items-center justify-center p-1.5 shrink-0 overflow-hidden shadow-inner">
                        @if(!empty($company->logo_path))
                            <img src="{{ asset($company->logo_path) }}" class="w-full h-full object-contain" alt="{{ $companyName }} Logo">
                        @else
                            <span class="text-2xl select-none">🏗️</span>
                        @endif
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-1.5 mb-2">
                            <span class="bg-[#f58613] text-white text-[9px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-full shadow-[0_0_15px_rgba(245,134,19,0.4)]">Network Verified</span>
                            @if(!empty($company->is_insured) || !empty($company->insurance_badge))
                                <span class="bg-emerald-600 text-white text-[9px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.3)]">Insured</span>
                            @endif
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black italic tracking-tighter text-white uppercase leading-none drop-shadow-md mb-1">
                            {!! $companyName !!}
                        </h1>
                        <p class="text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                            {!! $tradeTitle !!} &bull; <span class="text-orange-500">{{ $company->computed_experience ?? 'Verified' }} Years Active</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-2/5 p-6 md:p-10 flex flex-col bg-slate-900 justify-between gap-8">
                <div class="space-y-6">
                    <div>
                        <h4 class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mb-1.5">Primary Logistics Hub</h4>
                        <p class="text-xl font-black tracking-tight text-white uppercase flex items-center gap-2">
                            <span>🏢</span> {!! $displayLocation !!}
                        </p>
                    </div>
                    
                    @if(count($serviceTags) > 0)
                        <div>
                            <h4 class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mb-2.5">Focus Disciplines</h4>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(array_slice($serviceTags, 0, 5) as $tag)
                                    <span class="bg-slate-950 border border-slate-800 text-slate-300 px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wide">
                                        {{ e($tag) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="space-y-2">
                        @if(!empty($company->license_number))
                            <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-3.5 flex items-center justify-between">
                                <div class="flex items-center gap-2.5 text-xs font-black uppercase tracking-wider text-slate-400">
                                    <span>📋</span> License Registry
                                </div>
                                <div class="text-xs font-mono font-black text-white bg-slate-900 border border-slate-800 px-2 py-0.5 rounded shadow-inner">{!! e($company->license_number) !!}</div>
                            </div>
                        @endif
                        @if(!empty($company->warranty_details))
                            <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-3.5 flex items-center justify-between">
                                <div class="flex items-center gap-2.5 text-xs font-black uppercase tracking-wider text-slate-400">
                                    <span>🛡️ Warranty Policy</span>
                                </div>
                                <div class="text-[11px] font-black text-orange-500 uppercase tracking-tight">{!! e($company->warranty_details) !!}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-800/60 flex flex-col sm:flex-row items-center gap-4">
                    @if(!empty($cleanPhoneSchema))
                        <a href="tel:{{ $cleanPhoneSchema }}" class="w-full flex items-center justify-center bg-orange-600 hover:bg-orange-500 text-white font-black uppercase tracking-widest py-4 rounded-xl shadow-[0_10px_20px_rgba(245,134,19,0.15)] transition-all transform hover:-translate-y-0.5 text-xs border-0 outline-none text-decoration-none">
                            📞 Call Workspace Line
                        </a>
                    @else
                        <div class="w-full bg-slate-950 rounded-xl p-3.5 text-center text-xs font-bold text-slate-500 border border-slate-800">
                            Scheduling Matrix Open
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-10">
            
            <div class="lg:col-span-3 space-y-12">
                
                <div class="space-y-6">
                    <h2 class="text-2xl md:text-3xl font-black italic text-white uppercase tracking-tight flex items-center gap-3.5">
                        <span class="w-6 h-1.5 bg-orange-600 rounded-full"></span> About Our Workspace
                    </h2>
                    <div class="bg-white border border-slate-200 shadow-xl rounded-3xl p-6 md:p-10 text-slate-800">
                        <div class="prose prose-slate max-w-none leading-relaxed text-slate-600 font-medium">
                            <p class="text-base text-slate-900 font-bold mb-4">{!! $shortBio !!}</p>
                            @if(!empty($longBio))
                                <p class="pt-4 border-t border-slate-100">{!! nl2br(e($longBio)) !!}</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if(count($serviceTags) > 0)
                    <div class="space-y-6">
                        <h2 class="text-2xl md:text-3xl font-black italic text-white uppercase tracking-tight flex items-center gap-3.5">
                            <span class="w-6 h-1.5 bg-orange-600 rounded-full"></span> Service Deliverables
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($serviceTags as $tag)
                                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex items-center gap-3.5 transition-colors hover:border-slate-700/80">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-orange-500/10 text-orange-500 border border-orange-500/20 shadow-inner text-xs font-black">✓</div>
                                    <span class="text-sm font-bold text-slate-200 uppercase tracking-tight">{{ e($tag) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($galleryImages) && count($galleryImages) > 0)
                    <div class="space-y-6">
                        <h2 class="text-2xl md:text-3xl font-black italic text-white uppercase tracking-tight flex items-center gap-3.5">
                            <span class="w-6 h-1.5 bg-orange-600 rounded-full"></span> Project Showcase Gallery
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($galleryImages as $index => $image)
                                <div @click="activeIndex = {{ $index }}; lightboxOpen = true" class="cursor-pointer aspect-video rounded-2xl overflow-hidden border border-slate-800 bg-slate-900 group relative shadow-lg">
                                    <img src="{{ asset($image) }}" alt="Showcase Image {{ $index + 1 }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-orange-500/0 group-hover:bg-orange-500/10 transition-colors duration-200 flex items-center justify-center backdrop-blur-[0.5px]">
                                        <span class="bg-white text-slate-950 text-[9px] font-black uppercase tracking-widest py-1.5 px-3 rounded-lg shadow-md opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-200">Inspect</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="space-y-6">
                    <h2 class="text-2xl md:text-3xl font-black italic text-white uppercase tracking-tight flex items-center gap-3.5">
                        <span class="w-6 h-1.5 bg-orange-600 rounded-full"></span> Profile FAQ
                    </h2>
                    <div class="space-y-3">
                        
                        <details class="bg-slate-900 border border-slate-800 rounded-2xl shadow-sm group overflow-hidden">
                            <summary class="flex items-center justify-between font-bold text-sm text-white p-5 cursor-pointer list-none select-none uppercase tracking-tight">
                                <span>Do you service areas surrounding {!! $company->city ?? 'my location' !!}?</span>
                                <span class="transition group-open:rotate-180 text-orange-500">
                                    <svg fill="none" height="18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" viewBox="0 0 24 24" width="18"><path d="M6 9l6 6 6-6"></path></svg>
                                </span>
                            </summary>
                            <div class="text-slate-400 px-5 pb-5 pt-1 text-xs leading-relaxed border-t border-slate-800/40 mt-1">
                                Yes! Our core routing field is anchored right out of {!! $displayLocation !!}. We actively dispatch crews across the entire local service grid framework.
                            </div>
                        </details>

                        @if(!empty($company->deposit_rules))
                            <details class="bg-slate-900 border border-slate-800 rounded-2xl shadow-sm group overflow-hidden">
                                <summary class="flex items-center justify-between font-bold text-sm text-white p-5 cursor-pointer list-none select-none uppercase tracking-tight">
                                    <span>What are your project deposit rules?</span>
                                    <span class="transition group-open:rotate-180 text-orange-500">
                                        <svg fill="none" height="18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" viewBox="0 0 24 24" width="18"><path d="M6 9l6 6 6-6"></path></svg>
                                    </span>
                                </summary>
                                <div class="text-slate-400 px-5 pb-5 pt-1 text-xs leading-relaxed border-t border-slate-800/40 mt-1 font-mono">
                                    {!! e($company->deposit_rules) !!}
                                </div>
                            </details>
                        @endif

                    </div>
                </div>

            </div>

            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-slate-900 rounded-3xl overflow-hidden shadow-xl border border-slate-800">
                    @if($company->latitude && $company->longitude)
                        <div id="map" style="width: 100%; height: 230px; background-color: #0f172a;" class="mix-blend-luminosity border-b border-slate-800"></div>
                    @else
                        <div class="w-full h-48 bg-slate-950 flex flex-col items-center justify-center text-slate-500 text-[10px] font-black uppercase tracking-widest text-center p-6 border-b border-slate-800 leading-normal">
                            <span>📍 Geographic Hub Configured</span>
                            <span class="text-slate-600 font-normal mt-1 font-mono">{!! $displayLocation !!}</span>
                        </div>
                    @endif
                    <div class="p-6 bg-slate-900">
                        <p class="font-black text-white text-base uppercase tracking-tight italic leading-none mb-1">{{ $company->address ?? 'Local Footprint' }}</p>
                        <p class="text-slate-400 text-[10px] font-black tracking-wider uppercase">{!! $displayLocation !!}</p>
                    </div>
                </div>

                @if(!empty($company->work_philosophy))
                    <div class="bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-2">
                        <h4 class="text-[9px] font-black uppercase tracking-widest text-orange-500">Operation Framework</h4>
                        <p class="text-xs text-slate-300 italic font-serif leading-relaxed">
                            "{!! nl2br(e($company->work_philosophy)) !!}"
                        </p>
                    </div>
                @endif

                @if(is_array($socialLinks) && count(array_filter($socialLinks)) > 0)
                    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-sm space-y-3">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-800 pb-2">Reputation Matrices</h3>
                        <div class="flex flex-col gap-2">
                            @if(!empty($socialLinks['google']))
                                <a href="{{ $socialLinks['google'] }}" target="_blank" rel="noopener" class="flex items-center justify-between p-3 rounded-xl bg-slate-950 border border-slate-800 text-[11px] font-bold text-slate-300 hover:bg-slate-800 transition-colors text-decoration-none">
                                    <span>🌐 Google Profile</span> <span class="text-slate-500">&rarr;</span>
                                </a>
                            @endif
                            @if(!empty($socialLinks['facebook']))
                                <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener" class="flex items-center justify-between p-3 rounded-xl bg-slate-950 border border-slate-800 text-[11px] font-bold text-slate-300 hover:bg-slate-800 transition-colors text-decoration-none">
                                    <span>🔵 Facebook Page</span> <span class="text-slate-500">&rarr;</span>
                                </a>
                            @endif
                            @if(!empty($socialLinks['yelp']))
                                <a href="{{ $socialLinks['yelp'] }}" target="_blank" rel="noopener" class="flex items-center justify-between p-3 rounded-xl bg-slate-950 border border-slate-800 text-[11px] font-bold text-slate-300 hover:bg-slate-800 transition-colors text-decoration-none">
                                    <span>🔴 Yelp Reviews</span> <span class="text-slate-500">&rarr;</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- PERSISTENT ALPINE LIGHTBOX MODAL CONTAINER --}}
    {{-- ========================================== --}}
    <div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-slate-950/95 backdrop-blur-md p-4 select-none" x-transition>
        <div class="absolute inset-0 z-10" @click="lightboxOpen = false"></div>
        
        <button type="button" @click="lightboxOpen = false" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors z-50 bg-slate-900 border border-slate-800 p-2 rounded-xl cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="relative z-20 w-full max-w-5xl h-[70vh] flex items-center justify-center p-2">
            <button type="button" @click="activeIndex = (activeIndex === 0) ? imagePool.length - 1 : activeIndex - 1" class="absolute left-2 md:left-6 text-white/60 bg-slate-900/60 border border-slate-800/80 p-3 rounded-full hover:bg-black transition-all z-30 cursor-pointer">
                &larr;
            </button>

            <img :src="imagePool[activeIndex]" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl transition-all duration-200">

            <button type="button" @click="activeIndex = (activeIndex === imagePool.length - 1) ? 0 : activeIndex + 1" class="absolute right-2 md:right-6 text-white/60 bg-slate-900/60 border border-slate-800/80 p-3 rounded-full hover:bg-black transition-all z-30 cursor-pointer">
                &rarr;
            </button>
        </div>
        
        <div class="mt-4 text-slate-400 font-mono text-[10px] font-black uppercase tracking-widest bg-slate-900 border border-slate-800 px-3 py-1 rounded-md">
            Image <span x-text="activeIndex + 1" class="text-white"></span> / <span x-text="imagePool.length"></span>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- ASYNC GOOGLE MAPS API EXECUTION SCRIPTS   --}}
    {{-- ========================================== --}}
    @if($company->latitude && $company->longitude)
        <script>
            function initProfileMap() {
                var lat = {{ $company->latitude }};
                var lng = {{ $company->longitude }};
                var coords = { lat: lat, lng: lng };
                
                // Map Element Layout Loader
                var primaryMapEl = document.getElementById("map");
                if (primaryMapEl) {
                    var map = new google.maps.Map(primaryMapEl, {
                        zoom: 13,
                        center: coords,
                        disableDefaultUI: true,
                        zoomControl: true,
                        styles: [
                            {elementType: 'geometry', stylers: [{color: '#1e293b'}]},
                            {elementType: 'labels.text.stroke', stylers: [{color: '#0f172a'}]},
                            {elementType: 'labels.text.fill', stylers: [{color: '#475569'}]},
                            {featuretype: 'water', elementType: 'geometry', stylers: [{color: '#020617'}]}
                        ]
                    });
                    new google.maps.Marker({ position: coords, map: map });
                }

                // Hero Background Static Fallback Element Loader
                var heroMapEl = document.getElementById("hero-static-map");
                if (heroMapEl) {
                    var heroMap = new google.maps.Map(heroMapEl, {
                        zoom: 12,
                        center: coords,
                        disableDefaultUI: true,
                        gestureHandling: 'none'
                    });
                    new google.maps.Marker({ position: coords, map: heroMap });
                }
            }
        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initProfileMap"></script>
    @endif

</body>
</html>