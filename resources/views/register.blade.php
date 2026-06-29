<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Launch Your Company Workspace | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col justify-center min-h-full font-sans antialiased bg-slate-50 px-4 py-12 selection:bg-[#f58613] selection:text-white">

    <div class="w-full max-w-lg mx-auto bg-white border-2 border-slate-200 rounded-2xl shadow-xl p-6 md:p-8 space-y-6">

        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-12 rounded-xl bg-slate-950 text-[#f58613] text-xl font-bold mb-1 shadow-sm">
                制造 🏗️
            </div>
            <h2 class="text-2xl font-black text-slate-950 uppercase tracking-tight">Launch Your Workspace</h2>
            <p class="text-sm text-slate-500 font-bold max-w-[340px] mx-auto leading-normal">
                Deploy your dedicated commercial operational panel. It's a lot cheaper than you think.
            </p>
        </div>

        <!-- 📑 WHAT YOU ARE REGISTERING FOR & WHY (Value Reinforcement Context Box) -->
        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2.5 shadow-inner">
            <h3 class="text-xs font-black text-slate-950 uppercase tracking-wider flex items-center gap-1.5">
                📋 Workspace Allocation Checklist:
            </h3>
            <div class="text-xs text-slate-600 font-semibold space-y-1.5 leading-normal">
                <div class="flex items-start gap-1.5">
                    <span class="text-[#f58613] font-bold">✓</span>
                    <p><span class="font-black text-slate-950">Isolated Tenant Cockpit:</span> Provisions your secure database partition mapped directly to your trade specialization.</p>
                </div>
                <div class="flex items-start gap-1.5">
                    <span class="text-[#f58613] font-bold">✓</span>
                    <p><span class="font-black text-slate-950">A2P 10DLC Security Clearance:</span> Hooks your system account into our secure verification pipeline to authorize transactional billing and text links.</p>
                </div>
                <div class="flex items-start gap-1.5">
                    <span class="text-[#f58613] font-bold">✓</span>
                    <p><span class="font-black text-slate-950">Lead-Gen Profile Node:</span> Auto-compiles your search-optimized public portal map block to broadcast your credentials and target zip radius.</p>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-bold space-y-1 shadow-inner">
                @foreach($errors->all() as $error)
                    <div class="flex items-start gap-1.5">
                        <span class="shrink-0">⚠️</span>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-5">
            @csrf

            {{-- 🛡️ INVISIBLE HONEYPOT SHIELD --}}
            <div class="hidden" aria-hidden="true">
                <input type="text" name="system_verification_token" autocomplete="off" tabindex="-1">
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2" for="company_name">Business / Company Name</label>
                <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required placeholder="e.g., Apex Roofing LLC" autocomplete="organization"
                       class="w-full bg-slate-50 border-2 border-slate-300 focus:border-slate-950 rounded-xl py-3.5 px-4 text-base font-bold text-slate-950 shadow-inner focus:outline-none placeholder:text-slate-400 transition-all">
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2" for="email">Your Professional Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="name@yourcompany.com" autocomplete="email"
                       class="w-full bg-slate-50 border-2 border-slate-300 focus:border-slate-950 rounded-xl py-3.5 px-4 text-base font-bold text-slate-950 shadow-inner focus:outline-none placeholder:text-slate-400 transition-all">
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2" for="phone_2fa">Mobile Phone Number (For Verification)</label>
                <input type="tel" id="phone_2fa" name="phone_2fa" value="{{ old('phone_2fa') }}" required placeholder="e.g., (555) 000-0000" autocomplete="tel"
                       class="w-full bg-slate-50 border-2 border-slate-300 focus:border-slate-950 rounded-xl py-3.5 px-4 text-base font-bold text-slate-950 shadow-inner focus:outline-none placeholder:text-slate-400 transition-all">
            </div>

            {{-- 🛡️ HIGH-READABILITY COMPLIANT SMS OPT-IN DISCLOSURE MODIFIER --}}
            <div class="flex items-start gap-3 bg-slate-50 border border-slate-200 p-4 rounded-xl shadow-inner">
                <input type="checkbox" id="sms_consent" name="sms_consent" value="1" {{ old('sms_consent') ? 'checked' : '' }}
                       class="accent-[#f58613] w-5 h-5 rounded border-slate-300 bg-white text-[#f58613] focus:ring-[#f58613] mt-0.5 shrink-0 cursor-pointer">
                <label for="sms_consent" class="text-xs text-slate-600 font-bold leading-relaxed select-none cursor-pointer space-y-2">
                    <span>I authorize <span class="font-black text-slate-900">ContractorSpecialties</span> to send automated operational text messages (such as secure 6-digit login codes and account status notifications) to the mobile number provided above. Consent is optional and not a requirement of purchase. Message frequency varies. Msg & data rates may apply. Text STOP to halt alerts at any time.</span>
                    <span class="block font-black text-slate-950 uppercase tracking-wide text-[10px] pt-1">🔒 Absolute Privacy Guarantee:</span>
                    <span class="block text-slate-500 font-medium">Your mobile information will not be sold or shared with third parties for promotional or marketing purposes. Review our active <a href="{{ route('legal.privacy') }}" class="text-[#f58613] font-black underline hover:text-orange-600 transition-colors">Privacy Policy</a>.</span>
                </label>
            </div>

            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3 text-xs text-slate-500 font-bold leading-normal shadow-inner">
                <span class="text-lg select-none">📱</span>
                <div>
                    <span class="font-black text-slate-950 uppercase text-[10px] block tracking-wide mb-0.5">Secure 6-Digit Text Verification</span>
                    Zero passwords to remember or lose. Our system sends an instant single-use security code straight to your phone to confirm your identity and launch your terminal environment securely.
                </div>
            </div>

            <div class="pt-1">
                <button type="submit" class="w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-sm py-4.5 px-4 rounded-xl tracking-widest uppercase shadow-lg transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer border-0 outline-none">
                    Build My Company Workspace →
                </button>
            </div>
        </form>

        <div class="pt-4 border-t border-slate-200 text-xs text-slate-400 leading-normal text-center font-bold">
            Already registered? <a href="{{ route('welcome') }}" class="text-[#f58613] font-black underline hover:text-orange-600 transition-colors">Request a fresh login link</a>.
        </div>

    </div>

</body>
</html>
