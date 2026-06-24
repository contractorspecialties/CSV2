<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Brand Trust Profile | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-[240px] flex items-center">
                    <img src="/images/header-logo.webp" alt="Logo" class="w-full h-auto max-h-[70px] object-contain object-left">
                </div>
                <a href="/dashboard" class="bg-slate-900 hover:bg-slate-800 text-slate-400 font-black text-[10px] py-2 px-3.5 rounded-lg uppercase tracking-wider transition-all border border-slate-800 cursor-pointer">
                    &larr; Operations Desk
                </a>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-emerald-400 font-mono font-black text-[10px] uppercase tracking-wider bg-emerald-950/40 px-3 py-1.5 rounded-xl border border-emerald-900/40">
                    🟢 Profile Identity Configurator Active
                </span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl w-full mx-auto px-4 py-10">

        @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-4 mb-6 flex items-center gap-3 shadow-sm">
                <span class="text-lg">⚡</span>
                <p class="text-xs font-black uppercase tracking-tight">{{ session('status') }}</p>
            </div>
        @endif

        <div x-data="{ currentTab: 'legitimacy', dynamicPreviews: [], logoPreview: '{{ !empty($company->logo_path) ? asset($company->logo_path) : '' }}' }" class="grid grid-cols-1 md:grid-cols-4 gap-8 items-start">

            <nav class="space-y-1.5">
                <button type="button" @click="currentTab = 'legitimacy'" :class="currentTab === 'legitimacy' ? 'bg-[#f58613] text-white shadow' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60'" class="w-full text-left font-black text-xs uppercase tracking-wider py-4 px-4 rounded-xl transition-all flex items-center justify-between cursor-pointer outline-none border-0">
                    <span>🛡️ Identity & Legitimacy</span>
                    <span class="text-xs opacity-60">&rarr;</span>
                </button>
                <button type="button" @click="currentTab = 'reliability'" :class="currentTab === 'reliability' ? 'bg-[#f58613] text-white shadow' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60'" class="w-full text-left font-black text-xs uppercase tracking-wider py-4 px-4 rounded-xl transition-all flex items-center justify-between cursor-pointer outline-none border-0">
                    <span>👤 Reliability & Philosophy</span>
                    <span class="text-xs opacity-60">&rarr;</span>
                </button>
                <button type="button" @click="currentTab = 'gallery'" :class="currentTab === 'gallery' ? 'bg-[#f58613] text-white shadow' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60'" class="w-full text-left font-black text-xs uppercase tracking-wider py-4 px-4 rounded-xl transition-all flex items-center justify-between cursor-pointer outline-none border-0">
                    <span>📸 Showcase Photo Reel</span>
                    <span class="text-xs opacity-60">&rarr;</span>
                </button>

                <div class="pt-4 border-t border-slate-200/80 mt-4">
                    <a href="{{ route('brand.show', ['slug' => !empty($company->slug) ? $company->slug : 'staged-profile']) }}" target="_blank" class="w-full block text-center bg-slate-950 hover:bg-black text-white font-black text-[10px] uppercase tracking-widest py-3.5 px-4 rounded-xl shadow cursor-pointer transition-colors">
                        🌐 Preview Live Trust Page
                    </a>
                </div>
            </nav>

            <div class="md:col-span-3 bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
                <form action="{{ route('workspace.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div x-show="currentTab === 'legitimacy'" class="space-y-6">
                        <div>
                            <h3 class="text-base font-black text-slate-950 uppercase tracking-tight border-b border-slate-100 pb-2">Business Identity Pillars</h3>
                            <p class="text-xs text-slate-400 font-medium mt-1">Verify core structural variables that homeowners check first.</p>
                        </div>

                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex flex-col sm:flex-row items-center gap-5 shadow-inner">
                            <div class="w-20 h-20 rounded-2xl bg-white border border-slate-300 shadow-sm flex items-center justify-center overflow-hidden shrink-0 relative bg-slate-50">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" class="w-full h-full object-contain p-1" alt="Logo Preview">
                                </template>
                                <template x-if="!logoPreview">
                                    <span class="text-2xl select-none text-slate-300">🏢</span>
                                </template>
                            </div>
                            <div class="space-y-1.5 w-full">
                                <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider">Company Brand Mark Logo</label>
                                <div class="relative bg-white border border-slate-300 hover:border-[#f58613] rounded-xl py-2.5 px-4 text-center cursor-pointer transition-colors shadow-sm max-w-xs">
                                    <input type="file" name="logo" accept="image/*"
                                           @change="if($event.target.files[0]) { logoPreview = URL.createObjectURL($event.target.files[0]); }"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <span class="text-xs font-black text-slate-700">📸 Choose Logo File</span>
                                </div>
                                <p class="text-[10px] text-slate-400 font-medium font-mono">Square or transparent widescreen PNG/JPG up to 2MB</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Business Name</label>
                            <input type="text" name="name" value="{{ old('name', $company->name ?? '') }}" required class="w-full bg-slate-50 border border-slate-300 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold focus:outline-none shadow-inner text-slate-900">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Years Active In Field</label>
                                <input type="number" name="years_in_business" value="{{ old('years_in_business', $company->years_in_business ?? '') }}" placeholder="e.g., 8" class="w-full bg-slate-50 border border-slate-300 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold focus:outline-none shadow-inner text-slate-900">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1.5">State License Identification Number</label>
                                <input type="text" name="license_number" value="{{ old('license_number', $company->license_number ?? '') }}" placeholder="e.g., ROC #382910" class="w-full bg-slate-50 border border-slate-300 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold focus:outline-none shadow-inner text-slate-900">
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                            <div class="space-y-0.5">
                                <label class="text-xs font-black uppercase text-slate-900 tracking-tight">Active General Liability Coverage</label>
                                <p class="text-[11px] text-slate-400 font-medium">Do you possess active bonding and commercial general insurance wrappers?</p>
                            </div>
                            <input type="checkbox" name="insurance_badge" value="1" {{ (!empty($company->insurance_badge) && $company->insurance_badge) ? 'checked' : '' }} class="w-5 h-5 accent-[#f58613] cursor-pointer rounded">
                        </div>
                    </div>

                    <div x-show="currentTab === 'reliability'" class="space-y-6" x-cloak>
                        <div>
                            <h3 class="text-base font-black text-slate-950 uppercase tracking-tight border-b border-slate-100 pb-2">Human Element Configuration</h3>
                            <p class="text-xs text-slate-400 font-medium mt-1">Speak directly to homeowners to eliminate their fear of being burned.</p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider">Company Bio / Founder's Pitch</label>
                                <button type="button" @click="alert('🤖 AI Assist Engine: Integration pending localized contractor prompt setup matrices.')" class="bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 font-black text-[9px] uppercase tracking-wider py-1 px-2 rounded-lg flex items-center gap-1 cursor-pointer transition-all active:scale-95 border-0">
                                    ✨ AI Assist
                                </button>
                            </div>
                            <textarea name="company_bio" rows="4" placeholder="Describe who you are, how you serve locally, and why your specialization matters..." class="w-full bg-slate-50 border border-slate-300 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-medium focus:outline-none shadow-inner leading-relaxed text-slate-900">{{ old('company_bio', $company->company_bio ?? '') }}</textarea>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider">Work Philosophy / Customer Promise</label>
                                <button type="button" @click="alert('🤖 AI Assist Engine: Integration pending localized contractor prompt setup matrices.')" class="bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 font-black text-[9px] uppercase tracking-wider py-1 px-2 rounded-lg flex items-center gap-1 cursor-pointer transition-all active:scale-95 border-0">
                                    ✨ AI Assist
                                </button>
                            </div>
                            <textarea name="work_philosophy" rows="3" placeholder="e.g., We treat your home exactly like ours. We clean up completely and don't exit the footprint until you sign off on our craftsmanship." class="w-full bg-slate-50 border border-slate-300 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-medium focus:outline-none shadow-inner leading-relaxed text-slate-900">{{ old('work_philosophy', $company->work_philosophy ?? '') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Communication Response Metric</label>
                                <input type="text" name="typical_response_time" value="{{ old('typical_response_time', $company->typical_response_time ?? 'within 1 hour') }}" required class="w-full bg-slate-50 border border-slate-300 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold focus:outline-none shadow-inner text-slate-900">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Workmanship Warranty Terms</label>
                                <input type="text" name="warranty_details" value="{{ old('warranty_details', $company->warranty_details ?? '') }}" placeholder="e.g., 2-Year Workmanship Warranty" class="w-full bg-slate-50 border border-slate-300 focus:border-[#f58613] rounded-xl py-3 px-4 text-sm font-semibold focus:outline-none shadow-inner text-slate-900">
                            </div>
                        </div>
                    </div>

                    <div x-show="currentTab === 'gallery'" class="space-y-6" x-cloak>
                        <div>
                            <h3 class="text-base font-black text-slate-950 uppercase tracking-tight border-b border-slate-100 pb-2">Visual Production Gallery</h3>
                            <p class="text-xs text-slate-400 font-medium mt-1">Upload high-resolution images showing proof of recent job site work (Max 6 photos total).</p>
                        </div>

                        <div class="space-y-2">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-slate-400">Currently Active Portfolio Assets</span>
                            @if(!empty($galleryImages) && count($galleryImages) > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    @foreach($galleryImages as $image)
                                        <div class="relative rounded-xl border border-slate-200 overflow-hidden aspect-video group bg-slate-100 shadow-sm">
                                            <img src="{{ asset($image) }}" class="w-full h-full object-cover" alt="Portfolio Image">
                                            <div class="absolute inset-0 bg-slate-950/70 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-center items-center p-3 text-center">
                                                <label class="bg-red-600 hover:bg-red-700 text-white font-black text-[9px] uppercase tracking-widest py-2 px-3 rounded-xl cursor-pointer flex items-center gap-1.5 shadow active:scale-[0.98] transition-all">
                                                    <input type="checkbox" name="remove_images[]" value="{{ $image }}" class="rounded accent-red-900 w-3.5 h-3.5">
                                                    <span>Flag For Purge</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 text-xs text-slate-400 font-bold italic border border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                                    No active assets cataloged inside this cloud pool registry yet.
                                </div>
                            @endif
                        </div>

                        <div x-show="dynamicPreviews.length > 0" class="space-y-2 pt-2 border-t border-slate-100" x-transition>
                            <div class="flex items-center gap-2">
                                <span class="block text-[10px] font-black uppercase tracking-wider text-amber-500">Selected for Upload (Review Deck)</span>
                                <span class="text-[9px] bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full font-mono font-black" x-text="dynamicPreviews.length + ' Photo(s)'"></span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <template x-for="(blobUrl, index) in dynamicPreviews" :key="index">
                                    <div class="relative rounded-xl border border-amber-200 overflow-hidden aspect-video bg-slate-100 shadow-inner group">
                                        <img :src="blobUrl" class="w-full h-full object-cover" alt="Local File Thumbnail Preview">
                                        <div class="absolute top-1.5 right-1.5 bg-amber-500 text-white font-mono font-black text-[9px] w-4 h-4 rounded-full flex items-center justify-center shadow">
                                            <span x-text="index + 1"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        @if(empty($galleryImages) || count($galleryImages) < 6)
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Select Fresh Media Assets</label>
                                <div class="border-2 border-dashed border-slate-300 hover:border-[#f58613] rounded-2xl p-6 bg-slate-50 text-center transition-colors relative">
                                    <input type="file" name="new_gallery_images[]" multiple accept="image/*"
                                           @change="
                                               dynamicPreviews = [];
                                               if ($event.target.files) {
                                                   Array.from($event.target.files).forEach(file => {
                                                       dynamicPreviews.push(URL.createObjectURL(file));
                                                   });
                                               }
                                           "
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                    <div class="text-xs font-bold text-slate-500 relative z-10 pointer-events-none">
                                        📸 <span class="text-[#f58613]">Click to queue local showcase photography</span> or drop media rows here
                                        <p class="text-[10px] text-slate-400 font-mono mt-1">JPG, PNG, WEBP up to 4MB per slot</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                        <button type="submit" class="bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 px-8 rounded-xl tracking-widest uppercase shadow transition-all active:scale-[0.99] cursor-pointer border-0 outline-none">
                            Lock In Trust Changes &rarr;
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

</body>
</html>
