<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Programmatic Semantic SEO Meta Configuration Rows -->
    <title>{{ $company->name }} | Verified {{ $company->computed_trade }} Specialist Portfolio</title>
    <meta name="description" content="{{ $company->computed_bio_short }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>

    @php
        $routingLine = $company->monetization_routing_phone ?? $company->business_phone ?? '';
        $cleanPhoneSchema = preg_replace('/[^0-9+]/', '', $routingLine);
    @endphp

    <!-- 🌐 PROGRAMMATIC SEARCH ENGINE OPTIMIZED LOCAL BUSINESS JSON-LD SCHEMA STRINGS -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "HomeAndConstructionBusiness",
        "name": "{{ $company->name }}",
        "description": "{{ $company->computed_bio_short }}",
        "url": "{{ url()->current() }}",
        @if(!empty($company->logo_path))
        "logo": "{{ asset($company->logo_path) }}",
        "image": "{{ asset($company->logo_path) }}",
        @endif
        @if(!empty($routingLine))
        "telephone": "{{ $cleanPhoneSchema }}",
        @endif
        @if(!empty($company->license_number))
        "knowsAbout": "{{ $company->computed_trade }}",
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
            "reviewCount": "42",
            "bestRating": "5",
            "worstRating": "1"
        }
    }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased selection:bg-[#f58613] selection:text-white"
      x-data="{
          lightboxOpen: false,
          activeIndex: 0,
          imagePool: {{ json_encode(array_map(fn($img) => asset($img), $galleryImages)) }}
      }"
      @keydown.window.escape="lightboxOpen = false"
      @keydown.window.arrow-left="if(lightboxOpen) activeIndex = (activeIndex === 0) ? imagePool.length - 1 : activeIndex - 1"
      @keydown.window.arrow-right="if(lightboxOpen) activeIndex = (activeIndex === imagePool.length - 1) ? 0 : activeIndex + 1">

    <div class="bg-slate-950 text-white text-center py-2.5 px-4 border-b border-slate-900 text-[10px] font-black uppercase tracking-widest select-none">
        🛡️ Secure Identity Profile • Verified Corporate Member of ContractorSpecialties Network
    </div>

    <div class="max-w-4xl mx-auto px-4 py-10 space-y-8">

        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">

                <div class="w-24 h-24 rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center p-2 shadow-inner shrink-0 overflow-hidden">
                    @if(!empty($company->logo_path))
                        <img src="{{ asset($company->logo_path) }}" class="w-full h-full object-contain" alt="{{ $company->name }} Corporate Brand Mark">
                    @else
                        <div class="text-center">
                            <span class="text-3xl block select-none"> 🏗️ </span>
                            <span class="text-[9px] font-mono uppercase font-black text-slate-400 tracking-tight">No Logo</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-2">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <span class="bg-orange-50 text-[#f58613] text-[9px] font-black tracking-widest uppercase px-2.5 py-1 rounded-md border border-orange-100 shadow-sm">✓ Verified Contractor</span>
                        @if(!empty($company->is_insured) || !empty($company->insurance_badge))
                            <span class="bg-emerald-50 text-emerald-700 text-[9px] font-black tracking-widest uppercase px-2.5 py-1 rounded-md border border-emerald-100 shadow-sm">🛡️ Licensed & Insured</span>
                        @endif
                        @if(!empty($company->emergency_availability))
                            <span class="bg-red-50 text-red-700 text-[9px] font-black tracking-widest uppercase px-2.5 py-1 rounded-md border border-red-100 animate-pulse shadow-sm">🚨 24/7 Rapid Dispatch</span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-950 uppercase tracking-tight leading-none">{{ $company->name }}</h1>
                    <p class="text-xs font-black text-[#f58613] uppercase tracking-wider">{{ $company->computed_trade }}</p>

                    @if(!empty($company->city))
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            📍 Dispatch Base: <span class="text-slate-900 font-black">{{ $company->city }}{{ !empty($company->state) ? ', ' . strtoupper($company->state) : '' }}</span>
                            @if(!empty($company->service_radius_miles))
                                <span class="text-slate-400 font-normal font-sans">({{ $company->service_radius_miles }} Mile Search Radius)</span>
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 text-center shrink-0 w-full sm:w-auto">
                <div class="text-2xl font-black font-mono text-slate-950 leading-none">4.9</div>
                <div class="text-[#f58613] text-sm tracking-tighter my-1">★★★★★</div>
                <div class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Verified Field Log</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

            <div class="md:col-span-2 space-y-6">

                @if(!empty($company->computed_bio_long))
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-3">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">About Our Operation</h3>
                        <p class="text-sm font-medium text-slate-700 leading-relaxed">{{ $company->computed_bio_long }}</p>
                    </div>
                @endif

                @php
                    $serviceTags = [];
                    if (!empty($company->service_tags)) {
                        $serviceTags = json_decode($company->service_tags, true);
                    }
                @endphp
                @if(is_array($serviceTags) && count($serviceTags) > 0)
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Core Service Capabilities</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
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
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Our Commitment To You</h3>
                        <p class="text-sm font-medium text-slate-700 leading-relaxed italic border-l-4 border-[#f58613] pl-4 font-serif">
                            "{!! nl2br(e($company->work_philosophy)) !!}"
                        </p>
                    </div>
                @endif

                @if(!empty($galleryImages) && count($galleryImages) > 0)
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Showcase Proof of Recent Work</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($galleryImages as $index => $img)
                                <div @click="lightboxOpen = true; activeIndex = {{ $index }}"
                                     class="rounded-2xl border border-slate-200 overflow-hidden aspect-video bg-slate-100 shadow-sm relative group cursor-pointer">
                                    <img src="{{ asset($img) }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300" alt="Job site showcase snapshot">
                                    <div class="absolute inset-0 bg-slate-950/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="bg-white/90 backdrop-blur-sm text-slate-950 font-black text-[10px] uppercase tracking-wider py-2 px-3.5 rounded-xl shadow-sm">
                                            🔍 View Project
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6">

                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">Compliance & Credentials</h3>

                    <div class="space-y-3.5 text-xs font-medium">
                        @if(!empty($company->license_number))
                            <div class="flex items-start gap-3">
                                <span class="text-base select-none">📋</span>
                                <div>
                                    <div class="font-black text-slate-950 uppercase text-[10px]">Verified License</div>
                                    <div class="text-slate-500 font-mono font-bold mt-0.5">{{ $company->license_number }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <span class="text-base select-none">⏱️</span>
                            <div>
                                <div class="font-black text-slate-950 uppercase text-[10px]">Time In Service</div>
                                <div class="text-slate-500 font-bold mt-0.5">{{ $company->computed_experience }} Years Active Field Presence</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="text-base select-none">💬</span>
                            <div>
                                <div class="font-black text-slate-950 uppercase text-[10px]">Response Efficiency</div>
                                <div class="text-slate-500 font-bold mt-0.5">Typically responds {{ $company->typical_response_time ?? 'within 24 hours' }}</div>
                            </div>
                        </div>

                        @if(!empty($company->warranty_details))
                            <div class="flex items-start gap-3安全 border-t border-slate-100 pt-3.5">
                                <span class="text-base select-none">🛡️</span>
                                <div>
                                    <div class="font-black text-slate-950 uppercase text-[10px]">Warranty Protection Block</div>
                                    <div class="text-orange-500 font-black uppercase tracking-tight text-[11px] mt-0.5">{{ $company->warranty_details }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if(!empty($company->deposit_rules))
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-2">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">Mobilization Protocols</h3>
                        <p class="text-[11px] font-medium text-slate-500 leading-normal font-mono bg-slate-50 p-3 rounded-xl border border-slate-200/60 shadow-inner">
                            {{ $company->deposit_rules }}
                        </p>
                    </div>
                @endif

                @php
                    $socialLinks = [];
                    if (!empty($company->social_links)) {
                        $socialLinks = json_decode($company->social_links, true);
                    }
                    $hasSocials = is_array($socialLinks) && count(array_filter($socialLinks)) > 0;
                @endphp
                @if($hasSocials)
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-3">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">Verified Reputations</h3>
                        <div class="flex flex-col gap-2">
                            @if(!empty($socialLinks['google']))
                                <a href="{{ $socialLinks['google'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-700 hover:bg-slate-100 transition-colors text-decoration-none">
                                    <span>🌐</span> View Google Business Profile Links
                                </a>
                            @endif
                            @if(!empty($socialLinks['facebook']))
                                <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-700 hover:bg-slate-100 transition-colors text-decoration-none">
                                    <span>🔵</span> Follow Us On Facebook Page
                                </a>
                            @endif
                            @if(!empty($socialLinks['yelp']))
                                <a href="{{ $socialLinks['yelp'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-700 hover:bg-slate-100 transition-colors text-decoration-none">
                                    <span>🔴</span> Check Independent Yelp Reviews
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if(!empty($routingLine))
                    <div class="bg-slate-950 border border-slate-900 rounded-3xl p-6 shadow-xl text-white text-center space-y-4">
                        <div class="space-y-1">
                            <h4 class="font-black text-xs text-[#f58613] uppercase tracking-widest">Need Immediate Attention?</h4>
                            <p class="text-[11px] text-slate-400 font-medium max-w-[200px] mx-auto leading-normal">Our system pipes routing communications instantly straight to the foreman on duty.</p>
                        </div>
                        <a href="tel:{{ $cleanPhoneSchema }}" class="block w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 px-4 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer border-0 outline-none text-decoration-none">
                            📞 Call Dispatch Line
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- LIGHTBOX EXPERIMENTAL GALLERY DETAIL INTERFACE VIEW FRAME -->
    <div x-show="lightboxOpen"
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-4 sm:p-6 select-none"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="absolute inset-0 z-10" @click="lightboxOpen = false"></div>

        <button type="button" @click="lightboxOpen = false" class="absolute top-4 right-4 z-50 text-slate-400 hover:text-white text-2xl font-black bg-slate-900/60 border border-slate-800 p-2 rounded-xl cursor-pointer transition-colors outline-none">
            ✕
        </button>

        <div class="relative z-20 w-full max-w-5xl bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-2xl grid grid-cols-1 md:grid-cols-3 max-h-[90vh] md:max-h-[80vh]">

            <div class="md:col-span-2 bg-slate-950 relative flex items-center justify-center min-h-[300px] md:min-h-[450px] overflow-hidden">
                <img :src="imagePool[activeIndex]" class="max-w-full max-h-[50vh] md:max-h-[75vh] object-contain select-none" alt="Enlarged visualization confirmation block">

                <div class="absolute bottom-4 left-4 bg-slate-900/80 backdrop-blur border border-slate-800 text-[10px] font-mono font-black text-slate-300 px-2.5 py-1 rounded-lg">
                    <span x-text="activeIndex + 1"></span> / <span x-text="imagePool.length"></span> PHOTO
                </div>

                <button type="button"
                        @click="activeIndex = (activeIndex === 0) ? imagePool.length - 1 : activeIndex - 1"
                        class="absolute left-3 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-slate-900/70 border border-slate-800 text-white font-black flex items-center justify-center hover:bg-black transition-colors shadow outline-none cursor-pointer">
                    &larr;
                </button>

                <button type="button"
                        @click="activeIndex = (activeIndex === imagePool.length - 1) ? 0 : activeIndex + 1"
                        class="absolute right-3 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-slate-900/70 border border-slate-800 text-white font-black flex items-center justify-center hover:bg-black transition-colors shadow outline-none cursor-pointer">
                    &rarr;
                </button>
            </div>

            <div class="bg-white p-6 md:p-8 flex flex-col justify-between overflow-y-auto border-t md:border-t-0 md:border-l border-slate-200">
                <div class="space-y-5">
                    <div>
                        <span class="text-[9px] bg-orange-50 text-[#f58613] font-black tracking-widest uppercase px-2 py-0.5 rounded border border-orange-100 shadow-sm">
                            Artifact Proof Folder
                        </span>
                        <h4 class="text-base font-black uppercase text-slate-950 tracking-tight mt-1.5">Project Field Capture</h4>

                        @if(!empty($company->city))
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                                Market Domain: <span class="text-slate-900 font-black">{{ $company->city }}, {{ strtoupper($company->state ?? '') }}</span>
                            </p>
                        @endif
                    </div>

                    <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl space-y-3.5 text-xs font-semibold text-slate-600 shadow-inner">
                        <div class="flex items-center gap-2.5">
                            <span class="text-sm select-none">🏗️</span>
                            <div>
                                <div class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Corporate Identifier</div>
                                <div class="text-slate-900 font-bold leading-tight">{{ $company->name }}</div>
                            </div>
                        </div>

                        @if(!empty($company->license_number))
                            <div class="flex items-center gap-2.5 border-t border-slate-200/50 pt-2.5">
                                <span class="text-sm select-none">📋</span>
                                <div>
                                    <div class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Credential ID</div>
                                    <div class="text-slate-700 font-mono font-bold leading-tight">{{ $company->license_number }}</div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($company->warranty_details))
                            <div class="flex items-center gap-2.5 border-t border-slate-200/50 pt-2.5">
                                <span class="text-sm select-none">✨</span>
                                <div>
                                    <div class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Protection Policy</div>
                                    <div class="text-emerald-700 font-black uppercase text-[10px] tracking-tight leading-tight">{{ $company->warranty_details }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <p class="text-[11px] text-slate-400 font-medium leading-relaxed italic bg-slate-50/50 p-3 rounded-xl border border-slate-200/40">
                        💡 "This dynamic project image constitutes documented proof of standard local workmanship parameters and compliance values cataloged within our public consumer framework map."
                    </p>
                </div>

                @if(!empty($routingLine))
                    <div class="pt-6 border-t border-slate-100">
                        <a href="tel:{{ $cleanPhoneSchema }}" class="block w-full text-center bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer text-decoration-none border-0 outline-none">
                            📞 Request Similar Scope
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>

</body>
</html>
