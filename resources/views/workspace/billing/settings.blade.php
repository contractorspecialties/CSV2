<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Payment Setup | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-4xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[400px] max-w-[60%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>
            <a href="/dashboard" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl transition-all shadow-inner text-decoration-none">
                &larr; Back to Dashboard
            </a>
        </div>
    </header>

    <main class="flex-grow max-w-4xl w-full mx-auto px-4 py-8">

        @if(session('status'))
            <div class="bg-emerald-600 border border-emerald-700 text-white rounded-2xl p-4 flex items-center gap-3 shadow-md mb-6">
                <span class="text-lg">👍</span>
                <p class="text-xs font-black uppercase tracking-tight">Saved. Everything looks good.</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-600 border border-red-700 text-white rounded-2xl p-4 flex items-center gap-3 shadow-md mb-6">
                <span class="text-lg">🛑</span>
                <p class="text-xs font-black uppercase tracking-tight">One of your links isn’t valid. Make sure it’s a real URL and try again.</p>
            </div>
        @endif

        <div class="border-b border-slate-200 pb-4 mb-6">
            <h1 class="text-3xl font-black text-slate-950 uppercase tracking-tight">Direct Payment Setup</h1>
            <p class="text-base text-slate-500 font-bold mt-1">Set your payment links one time. The cockpit will show customers the right options automatically.</p>
        </div>

        <form action="/workspace/billing" method="POST" class="space-y-6">
            @csrf

            <!-- 💰 HOW YOU GET PAID CARD -->
            <div class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-6">
                <h2 class="text-xs font-black uppercase text-slate-400 tracking-wider font-mono">💵 How You Get Paid</h2>

                <div>
                    <label class="block text-sm font-black uppercase text-slate-900 tracking-wide mb-1">Stripe Payment Link</label>
                    <p class="text-xs text-slate-400 font-bold mb-2">Paste your Stripe checkout link here. The exact link Stripe gives you. No special formatting — just copy and paste.</p>
                    <input type="url" name="stripe_link" value="{{ old('stripe_link', $company->stripe_link ?? '') }}" placeholder="https://buy.stripe.com/..."
                           class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-mono font-bold focus:outline-none focus:border-slate-900 text-slate-900">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-black uppercase text-slate-900 tracking-wide mb-1">PayPal.Me Link</label>
                        <p class="text-xs text-slate-400 font-bold mb-2">Drop your PayPal.Me link here. Customers click it and pay you instantly.</p>
                        <input type="url" name="paypal_link" value="{{ old('paypal_link', $company->paypal_link ?? '') }}" placeholder="https://paypal.me/yourbusiness"
                               class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-bold focus:outline-none focus:border-slate-900 text-slate-900">
                    </div>

                    <div>
                        <label class="block text-sm font-black uppercase text-slate-900 tracking-wide mb-1">Zelle Email or Phone</label>
                        <p class="text-xs text-slate-400 font-bold mb-2">Enter the email or phone number your Zelle account uses. That’s all it needs.</p>
                        <input type="text" name="zelle_handle" value="{{ old('zelle_handle', $company->zelle_handle ?? '') }}" placeholder="e.g., payments@yourdomain.com"
                               class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-bold focus:outline-none focus:border-slate-900 text-slate-900">
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 24px 0;">

                <div>
                    <label class="block text-sm font-black uppercase text-slate-900 tracking-wide mb-1">Check, Wire, or Cash Instructions</label>
                    <p class="text-xs text-slate-400 font-bold mb-2">Add any extra details your customers need to pay you by check, bank transfer, or cash on delivery.</p>
                    <textarea name="billing_instructions" rows="3" placeholder="e.g., Please make checks payable to Your Company LLC and mail to our office address..."
                              class="w-full bg-slate-50 border-2 border-slate-300 rounded-2xl p-4 text-base font-medium focus:outline-none focus:border-slate-900 text-slate-900">{{ old('billing_instructions', $company->billing_instructions ?? '') }}</textarea>
                </div>
            </div>

            <!-- ⭐ GOOGLE REVIEWS CARD -->
            <div class="bg-white border-2 border-slate-300 rounded-3xl p-6 shadow-sm space-y-4">
                <h2 class="text-xs font-black uppercase text-slate-400 tracking-wider font-mono">📡 Get Google Reviews</h2>

                <div>
                    <label class="block text-sm font-black uppercase text-slate-900 tracking-wide mb-1">Google Review Link</label>
                    <p class="text-xs text-slate-400 font-bold mb-2">Paste your direct Google review link here. When you finish a job, the system will text this link to your customer to grab a 5-star review on autopilot.</p>
                    <input type="url" name="google_review_link" value="{{ old('google_review_link', $company->google_review_link ?? '') }}" placeholder="https://g.page/r/your-profile-id/review"
                           class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3.5 px-4 text-base font-mono font-bold focus:outline-none focus:border-slate-900 text-slate-900">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="w-full sm:w-auto bg-[#f58613] hover:bg-orange-600 text-white font-black text-base py-4 px-8 rounded-xl uppercase tracking-widest shadow-md transition-all active:scale-[0.99] cursor-pointer border-0 outline-none">
                    Save Payment Settings ⚡
                </button>
            </div>

        </form>
    </main>

    <footer class="border-t border-slate-200 bg-white py-6 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">
        &copy; 2026 ContractorSpecialties. Secure Payment Setup.
    </footer>

</body>
</html>
