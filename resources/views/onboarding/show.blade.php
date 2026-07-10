<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile Setup | ContractorSpecialties</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Premium Forced Aspect Ratio Cropping Engine Assets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css">
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="flex flex-col justify-center min-h-full font-sans antialiased bg-slate-50 px-4 py-12 selection:bg-[#f58613] selection:text-white">

    <div class="w-full max-w-2xl mx-auto bg-white border border-slate-200 rounded-3xl shadow-xl p-6 md:p-10 space-y-6"
         x-data="{
            aiLoading: false,
            bulletPoints: '',
            cropModalOpen: false,
            currentCropTarget: '',
            cropperInstance: null,

            triggerAICopy() {
                if(!this.bulletPoints) return alert('Please key in bullet parameters first.');
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
                    // Optimized tactical copywriting engine failover backup fallback strings
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

                    // Enforce square matching on logos vs widescreen banners on layout frames
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
                    // Generate data pipeline wrapper file to override typical field arrays
                    const croppedFile = new File([blob], 'cropped_' + this.currentCropTarget + '.jpg', { type: 'image/jpeg' });
                    const container = new DataTransfer();
                    container.items.add(croppedFile);

                    const inputElement = document.getElementById(this.currentCropTarget + '_file');
                    if(inputElement) inputElement.files = container.files;

                    this.cropModalOpen = false;
                    alert(this.currentCropTarget.toUpperCase() + ' image processing locked inside form stack.');
                }, 'image/jpeg', 0.9);
            }
         }">

        <!-- Progress Track Line Header -->
        <div class="space-y-2">
            <div class="flex justify-between items-center text-[10px] font-black uppercase text-slate-400 tracking-wider">
                <span>Setup Progress Map</span>
                <span class="text-[#f58613] font-mono">Step {{ $step }} of 5</span>
            </div>
            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden flex gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="h-full flex-1 transition-all duration-300 {{ $i <= $step ? 'bg-[#f58613]' : 'bg-slate-200' }}"></div>
                @endfor
            </div>
        </div>

        @if($errors->any())
            <div class="p-3.5 bg-red-50 text-red-700 border border-red-200 rounded-xl text-xs font-bold space-y-1 shadow-inner">
                @foreach($errors->all() as $error)
                    <div class="flex items-start gap-1.5"><span>⚠️</span><span>{{ $error }}</span></div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('onboarding.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="current_step" value="{{ $step }}">

            {{-- PAGE 1: ENTITY LOGISTICS MATRIX --}}
            @if ($step === 1)
                <div class="space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Company Attributes</h3>
                        <p class="text-xs text-slate-500 font-semibold">Verify corporate identities for live network profile generation loops.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_name">Company Name</label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $company->name) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="owner_name">Principal Contractor Name</label>
                            <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name', $company->owner_name) }}" required placeholder="e.g. John Doe" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="business_phone">Business Phone Number</label>
                            <input type="text" id="business_phone" name="business_phone" value="{{ old('business_phone', $company->business_phone ?? $user->phone_2fa) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="primary_specialty">Primary Trade Core</label>
                            <select id="primary_specialty" name="primary_specialty" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                <option value="">-- Choose Trade --</option>
                                @foreach(['Drywall & Framing', 'Roofing & Siding', 'Decks & Porches', 'HVAC', 'Plumbing', 'Electrical', 'Lawn Care & Landscaping', 'General Contracting'] as $trade)
                                    <option value="{{ $trade }}" {{ old('primary_specialty', $company->primary_specialty) === $trade ? 'selected' : '' }}>{{ $trade }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="base_city">Business Location (City, State, Zip)</label>
                            <input type="text" id="base_city" name="base_city" placeholder="e.g., Washington, NC 27889" value="{{ old('base_city', $company->base_city) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="service_radius_miles">Work Radius (Miles)</label>
                            <input type="number" id="service_radius_miles" name="service_radius_miles" value="{{ old('service_radius_miles', $company->service_radius_miles ?? 25) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                    </div>
                </div>
            @endif

            {{-- PAGE 2: PROFILE AESTHETICS COMPLIANCE & THEME ENGINE --}}
            @if ($step === 2)
                <div class="space-y-6">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Identity Compliance & Layout Themes</h3>
                        <p class="text-xs text-slate-500 font-semibold">Link operational statistics, aesthetic layout templates, and copy declarations.</p>
                    </div>

                    <!-- PROFILE LAYOUT VISUAL RADIO INTERFACE CARDS -->
                    <div x-data="{ localTheme: '{{ old('profile_theme', $company->profile_theme ?? 'light') }}' }">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Live Public Profile Theme Skin</label>
                        <input type="hidden" name="profile_theme" :value="localTheme">
                        <div class="grid grid-cols-2 gap-4">
                            <div @click="localTheme = 'light'" :class="localTheme === 'light' ? 'border-[#f58613] ring-2 ring-orange-500/10 bg-orange-50/5' : 'border-slate-200 bg-white'" class="border rounded-2xl p-4 cursor-pointer transition-all flex items-center justify-between">
                                <span class="text-xs font-black uppercase text-slate-950">Corporate Crisp Light</span>
                                <div class="w-3 h-3 rounded-full border border-slate-300" :class="localTheme === 'light' && 'bg-[#f58613] border-orange-600'"></div>
                            </div>
                            <div @click="localTheme = 'dark'" :class="localTheme === 'dark' ? 'border-[#f58613] ring-2 ring-orange-500/10 bg-slate-900 text-white' : 'border-slate-200 bg-white'" class="border rounded-2xl p-4 cursor-pointer transition-all flex items-center justify-between">
                                <span class="text-xs font-black uppercase">Midnight Elite Tactical</span>
                                <div class="w-3 h-3 rounded-full border border-slate-300" :class="localTheme === 'dark' && 'bg-[#f58613] border-orange-600'"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="years_experience">Years Active Status</label>
                            <input type="number" id="years_experience" name="years_experience" value="{{ old('years_experience', $company->years_experience ?? 0) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="license_number">License Registry #</label>
                            <input type="text" id="license_number" name="license_number" placeholder="Optional Registry Code" value="{{ old('license_number', $company->license_number) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="is_insured">Insurance Policy Matrix</label>
                            <select id="is_insured" name="is_insured" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                <option value="none" {{ old('is_insured', $company->is_insured) === 'none' ? 'selected' : '' }}>No Active Coverage</option>
                                <option value="liability" {{ old('is_insured', $company->is_insured) === 'liability' ? 'selected' : '' }}>Liability Coverage Lock</option>
                                <option value="liability_workers" {{ old('is_insured', $company->is_insured) === 'liability_workers' ? 'selected' : '' }}>Liability + Work Comp Grid</option>
                            </select>
                        </div>
                    </div>

                    <!-- AI COPY COMPILER ASSISTANT CARD -->
                    <div class="p-4 bg-slate-950 border border-slate-900 rounded-2xl space-y-3.5 shadow-xl">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-1"><span>🪄</span> AI Copywriting Dashboard Assistant</h4>
                            <button type="button" @click="triggerAICopy()" :disabled="aiLoading" class="bg-[#f58613] hover:bg-orange-600 disabled:bg-slate-800 text-white font-black text-[9px] uppercase tracking-widest px-3 py-2 rounded-xl transition-all cursor-pointer">
                                <span x-show="!aiLoading">Generate Copy</span>
                                <span x-show="aiLoading" x-cloak>Structuring Data...</span>
                            </button>
                        </div>
                        <input type="text" x-model="bulletPoints" placeholder="Type rough notes: family business, 15 years local roofing pro, free deck logs..." class="w-full bg-slate-900 border border-slate-800 text-slate-200 font-medium rounded-xl py-2 px-3 text-xs focus:outline-none focus:border-orange-500 shadow-inner">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_bio_short">Public Strategy Profile Slogan (Short Bio)</label>
                        <input type="text" id="company_bio_short" name="company_bio_short" value="{{ old('company_bio_short', $company->company_bio_short) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_bio_long">Full Corporate Operations Statement (Long Bio)</label>
                        <textarea id="company_bio_long" name="company_bio_long" rows="4" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-medium text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner font-sans">{{ old('company_bio_long', $company->company_bio_long) }}</textarea>
                    </div>

                    <!-- CROPPING INTEGRATED FILE DISPATCH SYSTEM inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                        <div class="p-5 border border-dashed border-slate-200 rounded-2xl bg-slate-50 text-center space-y-1.5">
                            <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Company Logo File</span>
                            <input type="file" id="logo_file" name="logo_file" accept="image/*" @change="initializeImageCrop($event, 'logo')" class="w-full text-xs font-bold text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-950 file:text-[#f58613] cursor-pointer">
                            <p class="text-[9px] font-medium text-slate-400">Forces crisp 1:1 asset compression mapping loops.</p>
                        </div>
                        <div class="p-5 border border-dashed border-slate-200 rounded-2xl bg-slate-50 text-center space-y-1.5">
                            <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Cover Hero Photo Background</span>
                            <input type="file" id="cover_file" name="cover_file" accept="image/*" @change="initializeImageCrop($event, 'cover')" class="w-full text-xs font-bold text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-950 file:text-[#f58613] cursor-pointer">
                            <p class="text-[9px] font-medium text-slate-400">Forces wide-aspect 16:9 asymmetrical frame fit checks.</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- PAGE 3: SPECIALIZATION SELECTION CORES --}}
            @if ($step === 3)
                <div class="space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">List Job Specialties</h3>
                        <p class="text-xs text-slate-500 font-semibold">Choose dispatch parameters your teams handle inside active service metrics maps.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-3">Check All Specialties Offered</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(['Roof Replacements', 'Drywall & Framing', 'Decks & Porches', 'Service Calls & Repairs', 'Lawn & Landscape', 'Commercial Work'] as $service)
                                <label class="flex items-center gap-3 p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-950 cursor-pointer hover:border-[#f58613] transition-all shadow-sm">
                                    <input type="checkbox" name="service_tags[]" value="{{ $service }}" {{ is_array(old('service_tags', $company->service_tags)) && in_array($service, old('service_tags', $company->service_tags)) ? 'checked' : '' }} class="accent-[#f58613] w-4 h-4 shrink-0">
                                    <span>{{ $service }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="emergency_availability">Emergency Availability Status</label>
                        <select id="emergency_availability" name="emergency_availability" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                            <option value="0" {{ old('emergency_availability', $company->emergency_availability) == 0 ? 'selected' : '' }}>No, Standard Working Hours Matrix Only</option>
                            <option value="1" {{ old('emergency_availability', $company->emergency_availability) == 1 ? 'selected' : '' }}>Yes, Active Overtime System Armed (24/7 Dispatch)</option>
                        </select>
                    </div>
                </div>
            @endif

            {{-- PAGE 4: LINK REVIEW MATRICES --}}
            @if ($step === 4)
                <div class="space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Review Matrices Links</h3>
                        <p class="text-xs text-slate-500 font-semibold">Bind external validation paths to populate high-end local search engines.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="google_review_link">Google Business Profile Review URL</label>
                            <input type="url" id="google_review_link" name="google_review_link" placeholder="https://g.page/r/.../review" value="{{ old('google_review_link', $company->social_links['google'] ?? '') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="facebook_link">Facebook Page URL</label>
                            <input type="url" id="facebook_link" name="facebook_link" placeholder="https://facebook.com/yourbusiness" value="{{ old('facebook_link', $company->social_links['facebook'] ?? '') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                    </div>
                </div>
            @endif

            {{-- PAGE 5: CREW OPERATING FINANCIALS --}}
            @if ($step === 5)
                <div class="space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Crew and Billing Configurations</h3>
                        <p class="text-xs text-slate-500 font-semibold">Calibrate tracking keys, starting sequences, and escrow preference pools.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="crew_structure">Operational Scale</label>
                            <select id="crew_structure" name="crew_structure" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                <option value="solo" {{ old('crew_structure', $company->crew_structure) === 'solo' ? 'selected' : '' }}>Solo Specialist Operator</option>
                                <option value="small" {{ old('crew_structure', $company->crew_structure) === 'small' ? 'selected' : '' }}>Small Crew (Principal + 1-3 Operators)</option>
                                <option value="large" {{ old('crew_structure', $company->crew_structure) === 'large' ? 'selected' : '' }}>Mass Grid (Principal + 4-8 Operators)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="invoice_preferences">Bill Payment link Logic</label>
                            <select id="invoice_preferences" name="invoice_preferences" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                <option value="digital_only" {{ old('invoice_preferences', $company->invoice_preferences) === 'digital_only' ? 'selected' : '' }}>Link digital merchant app pipeline tracks</option>
                                <option value="cod" {{ old('invoice_preferences', $company->invoice_preferences) === 'cod' ? 'selected' : '' }}>Cash/Check Upon Work Delivery (COD)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="state_tax_rate">Local Jurisdiction Tax Rate (%)</label>
                            <input type="number" step="0.001" id="state_tax_rate" name="state_tax_rate" value="{{ old('state_tax_rate', $company->state_tax_rate ?? '') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="start_number">Estimate Starting Key #</label>
                            <input type="number" id="start_number" name="start_number" value="{{ old('start_number', $company->start_number ?? '1001') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="deposit_rules">Escrow Protection terms</label>
                        <textarea id="deposit_rules" name="deposit_rules" rows="3" placeholder="e.g. We require a 33% upfront material retainer allocation." class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-medium text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner font-sans">{{ old('deposit_rules', $company->deposit_rules) }}</textarea>
                    </div>
                </div>
            @endif

            <!-- BACK/NEXT STEP NAVIGATION ACTIONS -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-100 gap-4">
                @if ($step > 1)
                    <button type="submit" name="direction" value="back" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-xs py-3 px-6 rounded-xl tracking-widest uppercase cursor-pointer border-0">
                        &larr; Save & Go Back
                    </button>
                @else
                    <div></div>
                @endif

                <button type="submit" name="direction" value="next" class="flex-1 md:flex-none bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 px-8 rounded-xl tracking-widest uppercase shadow-md transition-all active:scale-[0.99] cursor-pointer text-center border-0">
                    {{ $step === 5 ? 'Launch Premium Corporate Profile' : 'Save & Advance &rarr;' }}
                </button>
            </div>
        </form>
    </div>

    <!-- PERSISTENT FRONTEND CROPPING CALIBRATION OVERLAY FRAME MODAL -->
    <div x-show="cropModalOpen" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-4" x-transition>
        <div class="bg-white border border-slate-200 w-full max-w-xl rounded-3xl p-6 shadow-2xl flex flex-col justify-between gap-5">
            <div>
                <h4 class="text-base font-black uppercase tracking-tight text-slate-950">Calibrate Image Aspect Fit Alignment</h4>
                <p class="text-xs font-semibold text-slate-400 mt-0.5">Please scale or drag the bounds box frame array matrix below to enforce a perfect profile asset deployment rendering layer.</p>
            </div>
            <div class="bg-slate-950 max-h-[40vh] overflow-hidden rounded-2xl flex items-center justify-center">
                <img id="cropper-target-image" src="" class="max-w-full max-h-[350px] object-contain">
            </div>
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" @click="cropModalOpen = false; if(cropperInstance) cropperInstance.destroy()" class="text-slate-500 font-bold text-xs uppercase px-4 py-2 hover:bg-slate-100 rounded-xl cursor-pointer">Drop Asset</button>
                <button type="button" @click="commitCroppedOutput()" class="bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs tracking-widest uppercase px-6 py-3 rounded-xl shadow-md cursor-pointer">Lock Profile Aspect Fit</button>
            </div>
        </div>
    </div>

</body>
</html>
