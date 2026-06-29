<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-200">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Launch Your Company Workspace | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col justify-center min-h-full font-sans antialiased bg-slate-950 px-4 py-12 selection:bg-[#f58613] selection:text-white">

    <div class="w-full max-w-md mx-auto bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-8 space-y-6">

        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-950 text-[#f58613] text-xl font-bold mb-1 shadow-sm border border-slate-800">
                制造 🏗️
            </div>
            <h2 class="text-xl font-black text-white uppercase tracking-tight">Launch Your Workspace</h2>
            <p class="text-xs text-slate-400 font-semibold max-w-[280px] mx-auto leading-normal">
                No passwords or setup fees required. Enter your company details to deploy your dashboard.
            </p>
        </div>

        @if($errors->any())
            <div class="p-4 bg-red-950/40 border border-red-900/60 text-red-400 rounded-xl text-xs font-bold space-y-1 shadow-inner">
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

            {{-- 🛡️ INVISIBLE HONEYPOT SHIELD: Bots fill this out automatically, triggering a silent reject filter --}}
            <div class="hidden" aria-hidden="true">
                <input type="text" name="system_verification_token" autocomplete="off" tabindex="-1">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_name">Business / Company Name</label>
                <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required placeholder="e.g., Apex Roofing LLC" autocomplete="organization"
                       class="w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none placeholder:text-slate-600 focus:ring-1 focus:ring-[#f58613]">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="email">Your Professional Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="name@yourcompany.com" autocomplete="email"
                       class="w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none placeholder:text-slate-600 focus:ring-1 focus:ring-[#f58613]">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="phone_2fa">Mobile Phone Number (For Verification)</label>
                <input type="tel" id="phone_2fa" name="phone_2fa" value="{{ old('phone_2fa') }}" required placeholder="e.g., (555) 000-0000" autocomplete="tel"
                       class="w-full bg-slate-950 border border-slate-800 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold text-white shadow-inner focus:outline-none placeholder:text-slate-600 focus:ring-1 focus:ring-[#f58613]">
            </div>

            {{-- 🛡️ COMPLIANT SMS OPT-IN DISCLOSURE MODIFIER --}}
            <div class="flex items-start gap-3 p-1">
                <input type="checkbox" id="sms_consent" name="sms_consent" value="1" {{ old('sms_consent') ? 'checked' : '' }}
                       class="accent-[#f58613] w-4 h-4 rounded border-slate-800 bg-slate-950 text-[#f58613] focus:ring-[#f58613] mt-0.5 shrink-0 cursor-pointer">
                <label for="sms_consent" class="text-[10px] text-slate-400 font-medium leading-normal select-none cursor-pointer">
                    I authorize <span class="font-bold text-white">ContractorSpecialties</span> to send automated operational text messages (such as secure 6-digit login codes and account status notifications) to the mobile number provided above. Consent is optional and not a requirement of purchase. Message frequency varies. Msg & data rates may apply. Text STOP to halt alerts at any time.
                    <span class="block font-black text-slate-300 mt-1.5 uppercase tracking-wide text-[9px]">🔒 Absolute Privacy Guarantee:</span>
                    Your mobile information will not be sold or shared with third parties for promotional or marketing purposes. Review our active <a href="{{ route('legal.privacy') }}" class="text-[#f58613] font-black underline hover:text-orange-500 transition-colors">Privacy Policy</a>.
                </label>
            </div>

            <div class="p-3.5 bg-slate-950 border border-slate-800 rounded-xl flex items-start gap-3 text-[11px] text-slate-400 font-medium leading-normal shadow-inner">
                <span class="text-base select-none">📱</span>
                <div>
                    <span class="font-black text-white uppercase text-[9px] block tracking-wide mb-0.5">Secure 6-Digit Text Verification</span>
                    Zero passwords to remember, manage, or lose. Our system sends an instant single-use security code straight to your phone to confirm your identity and launch your terminal environment securely.
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-4 px-4 rounded-xl tracking-widest uppercase shadow-lg transition-all active:scale-[0.99] flex justify-center items-center gap-2 cursor-pointer border-0 outline-none">
                    Build My Company Workspace →
                </button>
            </div>
        </form>

        <div class="pt-4 border-t border-slate-800 text-[10px] text-slate-500 leading-normal text-center font-medium">
            Already registered? <a href="{{ route('welcome') }}" class="text-[#f58613] font-black underline hover:text-orange-500 transition-colors">Request a fresh login link</a>.
        </div>

    </div>

</body>
</html>
