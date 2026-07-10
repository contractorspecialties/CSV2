<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->name ?? 'Company Profile' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-100 text-slate-900 p-6 font-sans antialiased">

    <div class="max-w-xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm mt-12 space-y-4">
        <div>
            <span class="bg-orange-50 text-[#f58613] text-[10px] font-black tracking-widest uppercase px-2 py-1 rounded-md border border-orange-100 shadow-sm">Verified Pro</span>
            <h1 class="text-2xl font-black uppercase text-slate-950 tracking-tight mt-2">{{ $company->name ?? 'No Name Provided' }}</h1>
            <p class="text-xs font-black text-[#f58613] uppercase tracking-wider mt-0.5">{{ $company->computed_trade ?? 'General Contractor' }}</p>
        </div>

        <hr class="border-slate-100">

        <p class="text-sm font-medium text-slate-600 leading-relaxed">
            {{ $company->computed_bio_short ?? 'Profile description setup is pending.' }}
        </p>

        <div class="pt-2">
            <a href="tel:{{ $company->business_phone ?? '' }}" class="inline-block bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3 px-5 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] text-decoration-none">
                📞 Call Business
            </a>
        </div>
    </div>

</body>
</html>