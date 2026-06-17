<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col min-h-full font-sans antialiased selection:bg-amber-500 selection:text-slate-950">

    <header class="bg-slate-950 border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded bg-amber-500 flex items-center justify-center font-black text-slate-950 text-base">CS</div>
                <h2 class="font-black text-lg text-white tracking-tight">Add New <span class="text-amber-500">Customer</span></h2>
            </div>
            <a href="/customers" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider">Cancel</a>
        </div>
    </header>

    <main class="flex-grow max-w-xl w-full mx-auto px-4 py-12">
        <div class="bg-white rounded-xl border border-slate-200 shadow-xl overflow-hidden">
            <div class="p-4 bg-slate-950 text-white flex items-center gap-2">
                <span class="text-xl">➕</span>
                <h3 class="font-black text-sm uppercase tracking-wider text-white">New Customer Contact Form</h3>
            </div>

            <form action="/customers" method="POST" class="p-6 space-y-5">
                @csrf

                @if($errors->any())
                    <div class="p-3 bg-red-50 text-red-700 rounded-lg text-xs font-bold border border-red-200">
                        Please review form errors before proceeding.
                    </div>
                @endif

                <div>
                    <label for="name" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1">Full Name</label>
                    <input type="text" id="name" name="name" required autocomplete="off" placeholder="e.g., Marcus Vance"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2.5 px-3.5 text-sm font-bold focus:outline-none focus:border-amber-500 text-slate-950 shadow-sm">
                    <p class="text-[10px] text-slate-400 mt-1">Our system automatically splits the name into first and last names for your history records.</p>
                </div>

                <div>
                    <label for="email" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1">Email Address</label>
                    <input type="email" id="email" name="email" required autocomplete="off" placeholder="name@domain.com"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2.5 px-3.5 text-sm font-bold focus:outline-none focus:border-amber-500 text-slate-950 shadow-sm">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-black uppercase text-slate-500 tracking-wider mb-1">Mobile Phone Number</label>
                    <input type="text" id="phone" name="phone" required autocomplete="off" placeholder="(555) 000-0000"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2.5 px-3.5 text-sm font-bold focus:outline-none focus:border-amber-500 text-slate-950 shadow-sm">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs py-3.5 px-4 rounded-xl uppercase tracking-widest shadow transition-all active:scale-[0.99] cursor-pointer">
                        Save Customer to List →
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
