<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Launch Your Company Workspace | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col justify-center min-h-full font-sans antialiased bg-slate-50 px-4 py-12 selection:bg-[#f58613] selection:text-white">

    <div class="w-full max-w-md mx-auto bg-white border border-slate-200 rounded-2xl shadow-xl p-8 space-y-6">

        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-950 text-[#f58613] text-xl font-bold mb-1 shadow-sm">
                🏗️
            </div>
            <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Launch Your Workspace</h2>
            <p class="text-xs text-slate-500 font-semibold max-w-[280px] mx-auto leading-normal">
                No passwords, credit cards, or setup fees required. Enter your company details to deploy your dashboard.
            </p>
        </div>

        @if($errors->any())
            <div class="p-3 bg-red-50 text-red-700 border border-red-200 rounded-xl text-xs font-bold space-y-1 shadow-sm">
                @foreach($errors->all() as $error)
                    <div class="flex items-start gap-1.5">
                        <span class="shrink-0">⚠️</span>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="company_name" class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Business / Company Name</label>
                <input type="text" id=\"company_name\" name="company_name" value="{{ old('company_name') }}" required placeholder="e.g., Apex Roofing LLC" autocomplete="organization"
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3 px-4 text-sm font-bold text-slate-950 placeholder:text-slate-400 focus:outline-none focus:border-[#f58613] focus:ring-1 focus:ring-[#f58613] shadow-inner">
            </div>

            <div>
                <label for="email" class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5">Your Professional Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="name@yourcompany.com" autocomplete="email"
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3 px-4 text-sm font-bold text-slate-950 placeholder:text-slate-400 focus:outline-none focus:border-[#f58613] focus:ring-1 focus:ring-[#f58613] shadow-inner">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow-md transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer">
                    Build My Company Workspace →
                </button>
            </div>
        </form>

        <div class="pt-4 border-t border-slate-100 text-[10px] text-slate-400 leading-normal text-center font-medium">
            Already registered? <a href="{{ route('welcome') }}" class="text-[#f58613] font-black underline hover:text-orange-600 transition-colors">Request a fresh login link</a>.
        </div>

    </div>

</body>
</html>
