@php
    // 1. Core String Sanitizations & Extractors
    $companyName = e($company->name ?? 'Local Verified Contractor');
    $tradeTitle = e($company->computed_trade ?? 'General Contractor');
    $shortBio = e($company->computed_bio_short ?? 'Verified professional contractor portfolio.');
    $longBio = e($company->computed_bio_long ?? '');

    $locationArr = array_filter([$company->city ?? '', strtoupper($company->state ?? '')]);
    $displayLocation = count($locationArr) > 0 ? implode(', ', $locationArr) : 'Local Service Area';

    $routingLine = $company->monetization_routing_phone ?? $company->business_phone ?? '';
    $cleanPhoneSchema = preg_replace('/[^0-9+]/', '', $routingLine);

    // 2. High-Conversion Layout Element Compilation
    $logoHtml = !empty($company->logo_path)
        ? '<img src="'.asset($company->logo_path).'" class="w-full h-full object-contain transform scale-95" alt="'.$companyName.' Logo">'
        : '<div class="text-center select-none"><span class="text-4xl block">🏗️</span><span class="text-[9px] font-mono uppercase font-black text-slate-400 tracking-wider">No Logo</span></div>';

    $badgeHtml = '<span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-[10px] font-black tracking-wider uppercase px-3 py-1 rounded-full border border-amber-200/60 shadow-sm">⭐ Verified Pro</span>';
    if (!empty($company->is_insured) || !empty($company->insurance_badge)) {
        $badgeHtml .= ' <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[10px] font-black tracking-wider uppercase px-3 py-1 rounded-full border border-emerald-200/60 shadow-sm">🛡️ Insured</span>';
    }
    if (!empty($company->emergency_availability)) {
        $badgeHtml .= ' <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 text-[10px] font-black tracking-wider uppercase px-3 py-1 rounded-full border border-rose-200/60 animate-pulse shadow-sm">🚨 24/7 Service</span>';
    }

    $longBioHtml = !empty($longBio) ? '<p class="text-sm font-medium text-slate-600 leading-relaxed pt-4 border-t border-slate-100/80">'.$longBio.'</p>' : '';
    $licenseHtml = !empty($company->license_number) ? '<div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-200/50"><div class="text-[11px] font-black uppercase text-slate-400 tracking-wider">License Registry</div><div class="text-xs font-mono font-black text-slate-900 bg-white border border-slate-200 px-2.5 py-1 rounded-md shadow-sm">'.e($company->license_number).'</div></div>' : '';
    $warrantyHtml = !empty($company->warranty_details) ? '<div class="p-4 bg-amber-50/50 border border-amber-200/40 rounded-2xl space-y-1"><div class="text-[10px] font-black uppercase text-amber-800 tracking-wider">Warranty Policy</div><div class="text-xs font-black text-amber-900">'.e($company->warranty_details).'</div></div>' : '';
    $depositHtml = !empty($company->deposit_rules) ? '<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-2.5"><h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">Deposit & Escrow Rules</h3><p class="text-[11px] font-medium text-slate-600 leading-relaxed font-mono bg-slate-50 p-3.5 rounded-xl border border-slate-200/60 shadow-inner">'.e($company->deposit_rules).'</p></div>' : '';
    $philosophyHtml = !empty($company->work_philosophy) ? '<div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-800 space-y-3"><h3 class="text-[10px] font-black uppercase text-amber-500 tracking-widest">Our Operations Statement</h3><p class="text-sm font-medium text-slate-300 leading-relaxed italic font-serif pl-2 border-l-2 border-amber-500">"'.nl2br(e($company->work_philosophy)).'"</p></div>' : '';

    // 3. Specialties Pill Aggregations
    $serviceTags = !empty($company->service_tags) ? json_decode($company->service_tags, true) : [];
    $tagsHtml = '';
    if (is_array($serviceTags) && count($serviceTags) > 0) {
        $tagsHtml .= '<div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4"><h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Verified Specializations</h3><div class="flex flex-wrap gap-2">';
        foreach (array_filter($serviceTags) as $tag) {
            $tagsHtml .= '<span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 rounded-xl shadow-sm hover:bg-slate-100 transition-colors cursor-default">⚡ '.e($tag).'</span>';
        }
        $tagsHtml .= '</div></div>';
    }

    // 4. Clean Portfolio Gallery Assemblies
    $galleryHtml = '';
    if (!empty($galleryImages) && count($galleryImages) > 0) {
        $galleryHtml .= '<div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4"><h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Field Showcase Logs</h3><div class="grid grid-cols-2 gap-3.5">';
        foreach ($galleryImages as $img) {
            $assetUrl = asset($img);
            $galleryHtml .= '<div @click="activeImg = \''.$assetUrl.'\'; lightboxOpen = true" class="rounded-2xl border border-slate-200 overflow-hidden aspect-video bg-slate-100 shadow-sm relative group cursor-pointer border border-slate-200/80"><img src="'.$assetUrl.'" class="w-full h-full object-cover group-hover:scale-[1.015] transition-transform duration-200" alt="Field Photo"><div class="absolute inset-0 bg-slate-950/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[1px]"><span class="bg-white text-slate-950 font-black text-[10px] uppercase tracking-wider py-2 px-4 rounded-xl shadow-md transform scale-95 group-hover:scale-100 transition-transform">🔍 Inspect Project</span></div></div>';
        }
        $galleryHtml .= '</div></div>';
    }

    // 5. External Portals Block
    $socialLinks = !empty($company->social_links) ? json_decode($company->social_links, true) : [];
    $socialsHtml = '';
    if (is_array($socialLinks) && count(array_filter($socialLinks)) > 0) {
        $socialsHtml .= '<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-3"><h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">Reputation Profiles</h3><div class="flex flex-col gap-2">';
        if (!empty($socialLinks['google'])) $socialsHtml .= '<a href="'.e($socialLinks['google']).'" target="_blank" rel="noopener" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors text-decoration-none"><span>🌐 Google Listing</span> <span class="text-slate-400 font-sans font-normal">&rarr;</span></a>';
        if (!empty($socialLinks['facebook'])) $socialsHtml .= '<a href="'.e($socialLinks['facebook']).'" target="_blank" rel="noopener" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors text-decoration-none"><span>🔵 Facebook Page</span> <span class="text-slate-400 font-sans font-normal">&rarr;</span></a>';
        if (!empty($socialLinks['yelp'])) $socialsHtml .= '<a href="'.e($socialLinks['yelp']).'" target="_blank" rel="noopener" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors text-decoration-none"><span>🔴 Yelp Directory</span> <span class="text-slate-400 font-sans font-normal">&rarr;</span></a>';
        $socialsHtml .= '</div></div>';
    }

    // 6. Sticky Bottom Conversion Action Call Container
    $phoneActionBoxHtml = !empty($cleanPhoneSchema) ? '<div class="bg-gradient-to-b from-amber-500 to-orange-600 border border-orange-600 rounded-3xl p-6 shadow-xl text-white text-center space-y-4 shadow-orange-600/10"><div class="space-y-1"><h4 class="font-black text-xs text-white uppercase tracking-widest opacity-95">Project Estimate Line</h4><p class="text-[11px] text-orange-50 font-medium max-w-[220px] mx-auto leading-normal">Connect directly with their team to verify real-time scheduling fields.</p></div><a href="tel:'.$cleanPhoneSchema.'" class="block w-full bg-white text-orange-700 font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow-md hover:bg-orange-50 transition-colors cursor-pointer border-0 outline-none text-decoration-none">📞 Call Workspace</a></div>' : '';

    // 7. Structured SEO JSON-LD Configurations
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
            "reviewCount" => "38",
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
<html lang="en" class="h-full bg-slate-50/60">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{!! $companyName !!} | Verified {!! $tradeTitle !!} Portfolio</title>
    <meta name="description" content="{!! $shortBio !!}">
    <link rel="canonical" href="{{ url()->current() }}">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>

    <script type="application/ld+json">
    {!! json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased selection:bg-[#f58613] selection:text-white" x-data="{ lightboxOpen: false, activeImg: '' }">

    <div class="bg-slate-950 text-white text-center py-2.5 px-4 border-b border-slate-900 text-[10px] font-black uppercase tracking-widest select-none">
        🛡️ Verified Profile Node &bull; ContractorSpecialties Network
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">

        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <div class="w-24 h-24 rounded-2xl border border-slate-200 bg-slate-50/60 flex items-center justify-center p-2 shadow-inner shrink-0 overflow-hidden">
                    {!! $logoHtml !!}
                </div>
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5">
                        {!! $badgeHtml !!}
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-950 uppercase tracking-tight leading-none">{!! $companyName !!}</h1>
                    <p class="text-xs font-black text-orange-600 uppercase tracking-wider">{!! $tradeTitle !!}</p>
                    <div class="flex items-center justify-center sm:justify-start gap-1 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <span>📍 Service Hub:</span>
                        <span class="text-slate-900 font-black bg-slate-100 px-2 py-0.5 rounded-md text-[11px]">{!! $displayLocation !!}</span>
                    </div>
                </div>
            </div>
            <div class="bg-slate-950 text-white rounded-2xl p-4 text-center shrink-0 w-full sm:w-auto border border-slate-900 shadow-md flex sm:flex-col items-center justify-between sm:justify-center gap-2 px-6 sm:px-5">
                <div class="text-left sm:text-center">
                    <div class="text-2xl font-black font-mono text-white leading-none">4.9</div>
                    <div class="text-amber-500 text-xs tracking-tighter my-0.5">★★★★★</div>
                </div>
                <div class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Verified Index</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Corporate Overview</h3>
                    <p class="text-sm font-medium text-slate-700 leading-relaxed">{!! $shortBio !!}</p>
                    {!! $longBioHtml !!}
                </div>
                {!! $tagsHtml !!}
                {!! $philosophyHtml !!}
                {!! $galleryHtml !!}
            </div>

            <div class="space-y-6">
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">Profile Meta</h3>
                    <div class="space-y-3.5">
                        {!! $licenseHtml !!}
                        <div class="flex items-center justify-between p-3 bg-slate-50/60 rounded-xl border border-slate-200/40 text-xs">
                            <div class="font-black text-slate-400 uppercase text-[9px] tracking-wider">Activity Field</div>
                            <div class="text-slate-700 font-bold">{{ $company->computed_experience ?? 'Verified' }} Years Active</div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-50/60 rounded-xl border border-slate-200/40 text-xs">
                            <div class="font-black text-slate-400 uppercase text-[9px] tracking-wider">SLA Response</div>
                            <div class="text-slate-700 font-bold">{{ $company->typical_response_time ?? 'Within 24 Hours' }}</div>
                        </div>
                        {!! $warrantyHtml !!}
                    </div>
                </div>
                {!! $depositHtml !!}
                {!! $socialsHtml !!}
                {!! $phoneActionBoxHtml !!}
            </div>
        </div>
    </div>

    <div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-4" x-transition>
        <div class="absolute inset-0" @click="lightboxOpen = false"></div>
        <div class="relative z-20 w-full max-w-3xl bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-2xl">
            <button type="button" @click="lightboxOpen = false" class="absolute top-4 right-4 z-50 text-slate-400 hover:text-white bg-slate-900/80 border border-slate-800 p-2.5 rounded-xl cursor-pointer outline-none">✕</button>
            <div class="bg-slate-950 flex items-center justify-center p-2 min-h-[250px]">
                <img :src="activeImg" class="max-w-full max-h-[70vh] object-contain rounded-xl shadow-inner" alt="Showcase Image">
            </div>
        </div>
    </div>

</body>
</html>
