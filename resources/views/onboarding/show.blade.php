<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile Setup | ContractorSpecialties</title>

    <!-- Client Runtime Core Engines -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Premium Image Calibration Engine Assets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css">
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="flex flex-col justify-center min-h-full font-sans antialiased bg-gradient-to-b from-slate-900 to-slate-950 px-4 py-12 selection:bg-[#f58613] selection:text-white">

    <!-- Global Application Setup Container Card Frame -->
    <div class="w-full max-w-2xl mx-auto bg-slate-900 border border-slate-800 rounded-[32px] shadow-2xl overflow-hidden relative"
         x-data="{
            aiLoading: false,
            bulletPoints: '',
            cropModalOpen: false,
            currentCropTarget: '',
            cropperInstance: null,

            triggerAICopy() {
                if(!this.bulletPoints) return alert('Please input bullet data notes first.');
                this.aiLoading = true;

                fetch('/api/ai/generate-profile-bio', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ prompts: this.bulletPoints, trade: document.getElementById('primary_specialty')?.value || 'Contracting' })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.short_bio) document.getElementById('company_bio_short').value = data.short_bio;
                    if(data.long_bio) document.getElementById('company_bio_long').value = data.long_bio;
                    this.aiLoading = false;
                })
                .catch(() => {
                    // Failover fallback automation copy arrays
                    document.getElementById('company_bio_short').value = 'Top-tier ' + (document.getElementById('primary_specialty')?.value || 'Contracting') + ' operations built on structural reliability.';
                    document.getElementById('company_bio_long').value = 'Our team provides verified corporate workflow solutions for local projects. Backed by years of field competency, we execute dispatch matrices with extreme transparency and full warranty parameters.';
                    this.aiLoading = false;
                });
            },

            initializeImageCrop(event, targetType) {
                const file = event.target.files[0];
                if (!file) return;
                this.currentCropTarget = targetType;
                this.cropModalOpen = true;

                const reader = new FileReader();
                reader.onload = (e) => {
                    const el = document.getElementById('cropper-target-image');
                    el.src = e.target.result;

                    if(this.cropperInstance) this.cropperInstance.destroy();

                    const targetRatio = targetType === 'logo' ? 1 : 16 / 9;
                    this.cropperInstance = new Cropper(el, {
                        aspectRatio: targetRatio,
                        viewMode: 1,
                        background: false
                    });
                };
                reader.readAsDataURL(file);
            },

            commitCroppedOutput() {
                if(!this.cropperInstance) return;
                const canvas = this.cropperInstance.getCroppedCanvas();
                canvas.toBlob((blob) => {
                    const croppedFile = new File([blob], 'cropped_' + this.currentCropTarget + '.jpg', { type: 'image/jpeg' });
                    const container = new DataTransfer();
                    container.items.add(croppedFile);

                    const inputElement = document.getElementById(this.currentCropTarget + '_file');
                    if(inputElement) inputElement.files = container.files;

                    this.cropModalOpen = false;
                }, 'image/jpeg', 0.9);
            }
         }">

        <!-- Top Premium Brand Accent Trim Line Bar -->
        <div class="h-1.5 w-full bg-gradient-to-r from-amber-500 via-[#f58613] to-orange-600"></div>

        <div class="p-6 md:p-10 space-y-6">
            <!-- Progress Track Header Module -->
            <div class="space-y-2.5">
                <div class="flex justify-between items-center text-[10px] font-black uppercase text-slate-500 tracking-widest">
                    <span>Setup Matrix Progress</span>
                    <span class="text-[#f58613] font-mono bg-slate-950/60 px-2.5 py-1 rounded-md border border-slate-800">Step {{ $step }} of 5</span>
                </div>
                <div class="w-full h-1.5 bg-slate-950 rounded-full overflow-hidden flex gap-1 border border-slate-800/40">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="h-full flex-1 transition-all duration-500 {{ $i <= $step ? 'bg-gradient-to-r from-amber-500 to-[#f58613]' : 'bg-slate-800/40' }}"></div>
                    @endfor
                </div>
            </div>

            @if($errors->any())
                <div class="p-4 bg-rose-950/30 text-rose-400 border border-rose-900/50 rounded-2xl text-xs font-bold space-y-1 shadow-inner">
                    @foreach($errors->all() as $error)
                        <div class="flex items-start gap-2"><span>🚨</span><span>{{ $error }}</span></div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('onboarding.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="current_step" value="{{ $step }}">

                {{-- PAGE 1: COMPANY ATTRIBUTES --}}
                @if ($step === 1)
                    <div class="space-y-4">
                        <div class="border-b border-slate-800 pb-3">
                            <h3 class="text-lg font-black text-white uppercase tracking-tight italic">Company Attributes</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Let's lock down the core locator and identity configurations for your live routing matrix maps.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_name">Registered Corporate Entity</label>
                                <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $company->name) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none committees focus:border-[#f58613] focus:ring-2 focus:ring-orange-500/10 shadow-inner">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="owner_name">Principal Contractor Name</label>
                                <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name', $company->owner_name) }}" required placeholder="First and Last Name" class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] focus:ring-2 focus:ring-orange-500/10 shadow-inner">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="business_phone">Direct Dispatch Workspace Phone</label>
                                <input type="text" id="business_phone" name="business_phone" value="{{ old('business_phone', $company->business_phone ?? $user->phone_2fa) }}" required class="w-full bg-slate-50/5 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] focus:ring-2 focus:ring-orange-500/10 shadow-inner">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="primary_specialty">Primary Core Trade Discipline</label>
                                <select id="primary_specialty" name="primary_specialty" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] focus:ring-2 focus:ring-orange-500/10 shadow-inner cursor-pointer">
                                    <option value="" class="bg-slate-900 text-slate-400">-- Select Specialized Trade --</option>
                                    @foreach(['Drywall & Framing', 'Roofing & Siding', 'Decks & Porches', 'HVAC', 'Plumbing', 'Electrical', 'Lawn Care & Landscaping', 'General Contracting'] as $trade)
                                        <option value="{{ $trade }}" {{ old('primary_specialty', $company->primary_specialty) === $trade ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $trade }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="base_city">Logistics Base Hub (City, State, Zip)</label>
                                <input type="text" id="base_city" name="base_city" placeholder="e.g., Washington, NC 27889" value="{{ old('base_city', $company->base_city) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] focus:ring-2 focus:ring-orange-500/10 shadow-inner">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="service_radius_miles">Dispatch Radius (Miles)</label>
                                <input type="number" id="service_radius_miles" name="service_radius_miles" value="{{ old('service_radius_miles', $company->service_radius_miles ?? 25) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] focus:ring-2 focus:ring-orange-500/10 shadow-inner">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- PAGE 2: IDENTITY COMPLIANCE & THEME PREVIEWS --}}
                @if ($step === 2)
                    <div class="space-y-6">
                        <div class="border-b border-slate-800 pb-3">
                            <h3 class="text-lg font-black text-white uppercase tracking-tight italic">Identity & Skin Aesthetics</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Calibrate public presentation parameters, dynamic layouts skins, and copy portfolios.</p>
                        </div>

                        <!-- PREMIUM PROFILE SKIN CONTROLLER CARDS -->
                        <div x-data="{ currentSkin: '{{ old('profile_theme', $company->profile_theme ?? 'light') }}' }">
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-3">Live Profile Layout Theme Skin</label>
                            <input type="hidden" name="profile_theme" :value="currentSkin">
                            <div class="grid grid-cols-2 gap-4">

                                <!-- Light Option UI Card -->
                                <div @click="currentSkin = 'light'"
                                     :class="currentSkin === 'light' ? 'border-[#f58613] ring-4 ring-orange-500/10 bg-slate-950' : 'border-slate-800 bg-slate-950/40 hover:border-slate-800'"
                                     class="border rounded-2xl p-4 flex flex-col justify-between cursor-pointer transition-all min-h-[110px]">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black uppercase tracking-tight text-white">Corporate Light</span>
                                        <div class="w-3.5 h-3.5 rounded-full border border-slate-700 flex items-center justify-center" :class="currentSkin === 'light' && 'bg-[#f58613] border-orange-600'">
                                            <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-1 mt-4"><div class="w-full h-3 bg-white rounded-sm"></div><div class="w-1/3 h-3 bg-[#f58613] rounded-sm"></div></div>
                                </div>

                                <!-- Dark Option UI Card -->
                                <div @click="currentSkin = 'dark'"
                                     :class="currentSkin === 'dark' ? 'border-[#f58613] ring-4 ring-orange-500/10 bg-slate-950' : 'border-slate-800 bg-slate-950/40 hover:border-slate-800'"
                                     class="border rounded-2xl p-4 flex flex-col justify-between cursor-pointer transition-all min-h-[110px]">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black uppercase tracking-tight text-white">Midnight Tactical</span>
                                        <div class="w-3.5 h-3.5 rounded-full border border-slate-700 flex items-center justify-center" :class="currentSkin === 'dark' && 'bg-[#f58613] border-orange-600'">
                                            <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-1 mt-4"><div class="w-full h-3 bg-slate-900 border border-slate-800 rounded-sm"></div><div class="w-1/3 h-3 bg-[#f58613] rounded-sm"></div></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="years_experience">Years Active</label>
                                <input type="number" id="years_experience" name="years_experience" value="{{ old('years_experience', $company->years_experience ?? 0) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="license_number">License Registry Code (Optional)</label>
                                <input type="text" id="license_number" name="license_number" value="{{ old('license_number', $company->license_number) }}" placeholder="e.g. NC-LIC-48891" class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="is_insured">Insurance Policy Structure</label>
                                <select id="is_insured" name="is_insured" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                    <option value="none" {{ old('is_insured', $company->is_insured) === 'none' ? 'selected' : '' }} class="bg-slate-900 text-white">No Corporate Coverage</option>
                                    <option value="liability" {{ old('is_insured', $company->is_insured) === 'liability' ? 'selected' : '' }} class="bg-slate-900 text-white">General Liability Policy Only</option>
                                    <option value="liability_workers" {{ old('is_insured', $company->is_insured) === 'liability_workers' ? 'selected' : '' }} class="bg-slate-900 text-white">Liability & Workers Compensation Compliance</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="warranty_details">Warranty Coverage Statement</label>
                                <input type="text" id="warranty_details" name="warranty_details" value="{{ old('warranty_details', $company->warranty_details) }}" placeholder="e.g. 10-Year Written Structural Warranty" class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner">
                            </div>
                        </div>

                        <!-- DYNAMIC ASSISTANT GENERATOR TERMINAL -->
                        <div class="p-5 bg-slate-950 border border-slate-800/80 rounded-2xl space-y-4 shadow-xl">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-2xl select-none">🪄</span>
                                    <div>
                                        <h4 class="text-xs font-black text-white uppercase tracking-wider">AI Portfolio Copy Compiler</h4>
                                        <p class="text-[10px] font-bold text-slate-500 leading-normal">Drop rough operational variables below to stream full biological summaries.</p>
                                    </div>
                                </div>
                                <button type="button" @click="triggerAICopy()" :disabled="aiLoading" class="bg-[#f58613] hover:bg-orange-600 disabled:bg-slate-800 text-white font-black text-[10px] uppercase tracking-widest px-4 py-2.5 rounded-xl cursor-pointer transition-colors shadow-lg shrink-0 outline-none">
                                    <span x-show="!aiLoading">Compile Arrays</span>
                                    <span x-show="aiLoading" x-cloak>Streaming Matrix...</span>
                                </button>
                            </div>
                            <input type="text" x-model="bulletPoints" placeholder="Rough keywords: local framing crew, deck builds, family run, free estimates in Wake County..." class="w-full bg-slate-900 border border-slate-800 text-slate-200 font-medium rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:border-orange-500 shadow-inner">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_bio_short">Public Profile Profile Slogan (Short Bio)</label>
                            <input type="text" id="company_bio_short" name="company_bio_short" value="{{ old('company_bio_short', $company->company_bio_short) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_bio_long">Full Operational Methodology Statement (Long Bio)</label>
                            <textarea id="company_bio_long" name="company_bio_long" rows="4" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-medium text-white focus:outline-none focus:border-[#f58613] shadow-inner font-sans leading-relaxed">{{ old('company_bio_long', $company->company_bio_long) }}</textarea>
                        </div>

                        <!-- CROP INTERFACES DISPATCH DECK -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                            <div class="p-5 border border-dashed border-slate-800 rounded-2xl bg-slate-950/40 text-center space-y-2">
                                <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Identity Corporate Logo</span>
                                <input type="file" id="logo_file" name="logo_file" accept="image/*" @change="initializeImageCrop($event, 'logo')" class="w-full text-xs font-bold text-slate-500 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-950 file:text-[#f58613] cursor-pointer">
                                <p class="text-[9px] font-medium text-slate-500 leading-normal">Forces 1:1 perfect pixel aspect compression layers.</p>
                            </div>
                            <div class="p-5 border border-dashed border-slate-800 rounded-2xl bg-slate-950/40 text-center space-y-2">
                                <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Cover Hero Wide Photo</span>
                                <input type="file" id="cover_file" name="cover_file" accept="image/*" @change="initializeImageCrop($event, 'cover')" class="w-full text-xs font-bold text-slate-500 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-950 file:text-[#f58613] cursor-pointer">
                                <p class="text-[9px] font-medium text-slate-500 leading-normal">Forces 16:9 widescreen bleed parameters cleanly.</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- PAGE 3: SERVICE DISCIPLINARY LOGS --}}
                @if ($step === 3)
                    <div class="space-y-4">
                        <div class="border-b border-slate-800 pb-3">
                            <h3 class="text-lg font-black text-white uppercase tracking-tight italic">List Job Specialties</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Select all secondary operational fields your active crews coordinate in the field.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-3">Check All Specialties Offered</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach(['Roof Replacements', 'Drywall & Framing', 'Decks & Porches', 'Service Calls & Repairs', 'Lawn & Landscape', 'Commercial Work'] as $service)
                                    <label class="flex items-center gap-3 p-4 bg-slate-950 border border-slate-800 rounded-2xl text-xs font-bold text-slate-300 cursor-pointer hover:border-[#f58613] transition-all shadow-md">
                                        <input type="checkbox" name="service_tags[]" value="{{ $service }}" {{ is_array(old('service_tags', $company->service_tags)) && in_array($service, old('service_tags', $company->service_tags)) ? 'checked' : '' }} class="accent-[#f58613] w-4.5 h-4.5 shrink-0">
                                        <span>{{ $service }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <hr class="border-slate-800/60 my-2">

                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="emergency_availability">24/7 Overtime Dispatch Compliance</label>
                            <select id="emergency_availability" name="emergency_availability" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                <option value="0" {{ old('emergency_availability', $company->emergency_availability) == 0 ? 'selected' : '' }} class="bg-slate-900 text-white">No, Standard Corporate Working Hours Matrix Only</option>
                                <option value="1" {{ old('emergency_availability', $company->emergency_availability) == 1 ? 'selected' : '' }} class="bg-slate-900 text-white">Yes, Active Emergency System Armed (Spotlight Pulse On)</option>
                            </select>
                        </div>
                    </div>
                @endif

                {{-- PAGE 4: TRUST INTEGRATION PATHS --}}
                @if ($step === 4)
                    <div class="space-y-4">
                        <div class="border-b border-slate-800 pb-3">
                            <h3 class="text-lg font-black text-white uppercase tracking-tight italic">Review Profile Linkage</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Bind public review profiles to populate active search engine indexes.</p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="google_review_link">Google Business Profile Review URL</label>
                                <input type="url" id="google_review_link" name="google_review_link" placeholder="https://g.page/r/.../review" value="{{ old('google_review_link', $company->social_links['google'] ?? '') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="facebook_link">Facebook Corporate Page URL</label>
                                <input type="url" id="facebook_link" name="facebook_link" placeholder="https://facebook.com/yourbusiness" value="{{ old('facebook_link', $company->social_links['facebook'] ?? '') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- PAGE 5: FINANCIAL ESCROWS & CREWS --}}
                @if ($step === 5)
                    <div class="space-y-4">
                        <div class="border-b border-slate-800 pb-3">
                            <h3 class="text-lg font-black text-white uppercase tracking-tight italic">Crew & Escrow Management</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Configure starting estimate tracking keys, operational volume parameters, and escrow rules.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="crew_structure">Operational scale footprint</label>
                                <select id="crew_structure" name="crew_structure" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                    <option value="solo" {{ old('crew_structure', $company->crew_structure) === 'solo' ? 'selected' : '' }} class="bg-slate-900 text-white">Owner / Operator (Solo Specialist)</option>
                                    <option value="small" {{ old('crew_structure', $company->crew_structure) === 'small' ? 'selected' : '' }} class="bg-slate-900 text-white">Small Field Crew (Principal + 1-3 Operators)</option>
                                    <option value="large" {{ old('crew_structure', $company->crew_structure) === 'large' ? 'selected' : '' }} class="bg-slate-900 text-white">Mass Volume System (Principal + 4-8 Operators)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="invoice_preferences">Bill Payment Link Preferences</label>
                                <select id="invoice_preferences" name="invoice_preferences" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                    <option value="digital_only" {{ old('invoice_preferences', $company->invoice_preferences) === 'digital_only' ? 'selected' : '' }} class="bg-slate-900 text-white">Link dynamic third-party merchant endpoint tracks</option>
                                    <option value="cod" {{ old('invoice_preferences', $company->invoice_preferences) === 'cod' ? 'selected' : '' }} class="bg-slate-900 text-white">Cash / Check Retainer Upon Delivery (COD)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="state_tax_rate">Local Jurisdiction Tax Rate (%)</label>
                                <input type="number" step="0.001" id="state_tax_rate" name="state_tax_rate" value="{{ old('state_tax_rate', $company->state_tax_rate ?? '') }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="start_number">Invoice/Estimate Starting Sequence Number</label>
                                <input type="number" id="start_number" name="start_number" value="{{ old('start_number', $company->start_number ?? '1001') }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-bold text-white focus:outline-none focus:border-[#f58613] shadow-inner">
                            </div>
                        </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="deposit_rules">Escrow Retainer Allocation Requirements</label>
                        <textarea id="deposit_rules" name="deposit_rules" rows="3" placeholder="We require a 33% material down-payment upfront before work is officially mapped onto our schedule." class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-sm font-medium text-white focus:outline-none focus:border-[#f58613] shadow-inner font-sans leading-relaxed">{{ old('deposit_rules', $company->deposit_rules) }}</textarea>
                    </div>
                </div>
            @endif

            <!-- BACK/NEXT ROUTING BUTTON DECK -->
            <div class="flex items-center justify-between pt-5 border-t border-slate-800/80 gap-4">
                @if ($step > 1)
                    <button type="submit" name="direction" value="back" class="bg-slate-950 border border-slate-800 text-slate-400 hover:text-white font-black text-xs py-3.5 px-6 rounded-xl tracking-widest uppercase transition-colors cursor-pointer outline-none">
                        &larr; Back Block
                    </button>
                @else
                    <div></div>
                @endif

                <button type="submit" name="direction" value="next" class="flex-1 md:flex-none bg-gradient-to-r from-amber-500 to-[#f58613] hover:from-amber-600 hover:to-orange-600 text-white font-black text-xs py-4 px-8 rounded-xl tracking-widest uppercase shadow-xl transition-all active:scale-[0.99] cursor-pointer text-center border-0 outline-none">
                    {{ $step === 5 ? 'Launch Live Premium Profile' : 'Save & Advance Matrix &rarr;' }}
                </button>
            </div>
        </form>
    </div>

    <!-- FRONTEND ASPECT CROPPING LAYOUT COMPOSITE OVERLAY MODAL -->
    <div x-show="cropModalOpen" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-4" x-transition>
        <div class="bg-slate-900 border border-slate-800 w-full max-w-xl rounded-3xl p-6 shadow-2xl flex flex-col justify-between gap-5 text-white">
            <div>
                <h4 class="text-base font-black uppercase tracking-tight italic text-white">Calibrate Image Aspect Alignment</h4>
                <p class="text-xs font-semibold text-slate-400 mt-0.5">Please scale or drag the bounds box grid below to lock in clean profile renderings.</p>
            </div>
            <div class="bg-slate-950 max-h-[40vh] overflow-hidden rounded-2xl flex items-center justify-center border border-slate-800">
                <img id="cropper-target-image" src="" class="max-w-full max-h-[350px] object-contain">
            </div>
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800/60">
                <button type="button" @click="cropModalOpen = false; if(cropperInstance) cropperInstance.destroy()" class="text-slate-400 font-bold text-xs uppercase px-4 py-2 hover:bg-slate-800 rounded-xl cursor-pointer">Drop Asset</button>
                <button type="button" @click="commitCroppedOutput()" class="bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs tracking-widest uppercase px-6 py-3.5 rounded-xl shadow-lg cursor-pointer transition-colors outline-none border-0">Lock Profile Aspect Fit</button>
            </div>
        </div>
    </div>

</body>
</html>
