<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace Core Callibration Setup | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col justify-center min-h-full font-sans antialiased bg-slate-50 px-4 py-12 selection:bg-[#f58613] selection:text-white">

    <div class="w-full max-w-xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-xl p-6 md:p-8 space-y-6">

        <!-- Tactical Progress Track Module -->
        <div class="space-y-2">
            <div class="flex justify-between items-center text-[10px] font-black uppercase text-slate-400 tracking-wider">
                <span>System Configuration Blueprint</span>
                <span class="text-[#f58613]">Phase {{ $step }} of 5</span>
            </div>
            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden flex gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="h-full flex-1 transition-all duration-300 {{ $i <= $step ? 'bg-[#f58613]' : 'bg-slate-200' }}"></div>
                @endfor
            </div>
        </div>

        @if($errors->any())
            <div class="p-3 bg-red-50 text-red-700 border border-red-200 rounded-xl text-xs font-bold space-y-1 shadow-sm">
                @foreach($errors->all() as $error)
                    <div class="flex items-start gap-1.5"><span>⚠️</span><span>{{ $error }}</span></div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('onboarding.submit') }}" method="POST" enctype="multipart/form-with-data" class="space-y-6">
            @csrf
            <input type="hidden" name="current_step" value="{{ $step }}">

            <!-- PHASE 1: CORE OPERATIONS -->
            @if ($step === 1)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Phase 1: Core Field Parameters</h3>
                        <p class="text-xs text-slate-500 font-semibold">Calibrate your dispatch boundary and trade focus mapping variables.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_name">Company Logged Name</label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $company->name) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="owner_name">Principal Operator Name</label>
                            <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name', $company->owner_name) }}" required placeholder="First and Last Name" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="business_phone">Primary Dispatch Contact Line</label>
                            <input type="text" id="business_phone" name="business_phone" value="{{ old('business_phone', $company->business_phone ?? $user->phone_2fa) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="primary_specialty">Primary Structural Trade Specialty</label>
                            <select id="primary_specialty" name="primary_specialty" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                                <option value="">-- Choose Trade --</option>
                                @foreach(['Roofing & Siding', 'HVAC & Climate Control', 'Plumbing & Gas Mechanical', 'Electrical Systems', 'General Contracting', 'Lawn Care & Landscape Operations'] as $trade)
                                    <option value="{{ $trade }}" {{ old('primary_specialty', $company->primary_specialty) === $trade ? 'selected' : '' }}>{{ $trade }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="base_city">Central Operating Base (City, State)</label>
                            <input type="text" id="base_city" name="base_city" placeholder="e.g., Washington, NC" value="{{ old('base_city', $company->base_city) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="service_radius_miles">Dispatch Radius (Miles)</label>
                            <input type="number" id="service_radius_miles" name="service_radius_miles" value="{{ old('service_radius_miles', $company->service_radius_miles ?? 25) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                    </div>
                </div>
            @endif

            <!-- PHASE 2: PUBLIC BRANDING & COMPLIANCE -->
            @if ($step === 2)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Phase 2: Brand Identity & Compliance</h3>
                        <p class="text-xs text-slate-500 font-semibold">These operational data sets assemble your automated public search presence optimization layers.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="years_experience">Years Operational</label>
                            <input type="number" id="years_experience" name="years_experience" value="{{ old('years_experience', $company->years_experience ?? 0) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="license_number">Trade License Registration #</label>
                            <input type="text" id="license_number" name="license_number" placeholder="Optional" value="{{ old('license_number', $company->license_number) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="is_insured">General Liability Insured</label>
                            <select id="is_insured" name="is_insured" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                                <option value="1" {{ old('is_insured', $company->is_insured) == 1 ? 'selected' : '' }}>Yes, Active Coverage</option>
                                <option value="0" {{ old('is_insured', $company->is_insured) == 0 ? 'selected' : '' }}>No Active Coverage</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_bio_short">Company Profile Punchline (Short Meta String)</label>
                        <input type="text" id="company_bio_short" name="company_bio_short" placeholder="e.g., Premium residential metal roofing specialists in Eastern NC." value="{{ old('company_bio_short', $company->company_bio_short) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_bio_long">Full Company Operating Summary (SEO Content Layer)</label>
                        <textarea id="company_bio_long" name="company_bio_long" rows="4" placeholder="Describe your team history, workmanship guarantees, and core mission profiles..." required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-medium text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner font-sans">{{ old('company_bio_long', $company->company_bio_long) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="logo_file">Corporate Logo File Upload</label>
                        <input type="file" id="logo_file" name="logo_file" accept="image/*" class="w-full text-xs font-bold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-slate-950 file:text-[#f58613] file:cursor-pointer cursor-pointer">
                    </div>
                </div>
            @endif

            <!-- PHASE 3: SERVICE MAPPING & MEDIA SCHEMATICS -->
            @if ($step === 3)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Phase 3: Service Scope Configuration</h3>
                        <p class="text-xs text-slate-500 font-semibold">Select the baseline service categories used to compile client invoices and programmatic search trees.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-3">Check All Functional Field Capabilities Offered</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(['Full Tear-Off Replacement', 'Emergency Structural Patching', 'Storm Damage Remediation', 'Preventative Inspection Maintenance', 'Gutter System Integrations', 'Commercial Property Overhauls'] as $service)
                                <label class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-950 cursor-pointer hover:border-[#f58613] transition-all shadow-sm">
                                    <input type="checkbox" name="service_tags[]" value="{{ $service }}" {{ is_array(old('service_tags', $company->service_tags)) && in_array($service, old('service_tags', $company->service_tags)) ? 'checked' : '' }} class="accent-[#f58613] w-4 h-4 shrink-0">
                                    <span>{{ $service }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="emergency_availability">24/7 Rapid Response Dispatch</label>
                        <select id="emergency_availability" name="emergency_availability" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                            <option value="0" {{ old('emergency_availability', $company->emergency_availability) == 0 ? 'selected' : '' }}>No, Standard Working Shifts Only</option>
                            <option value="1" {{ old('emergency_availability', $company->emergency_availability) == 1 ? 'selected' : '' }}>Yes, Available for Emergency Overtime Callouts</option>
                        </select>
                    </div>
                </div>
            @endif

            <!-- PHASE 4: SOCIAL PROOF CONNECTIONS -->
            @if ($step === 4)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Phase 4: Trust Signal Synchronization</h3>
                        <p class="text-xs text-slate-500 font-semibold">Drop in external operating nodes to link reputation metadata fields.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="google_review_link">Google Business Profile Review URL</label>
                            <input type="url" id="google_review_link" name="google_review_link" placeholder="https://g.page/r/.../review" value="{{ old('google_review_link', $company->social_links['google'] ?? '') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="facebook_link">Facebook Corporate Page URL</label>
                            <input type="url" id="facebook_link" name="facebook_link" placeholder="https://facebook.com/yourbusiness" value="{{ old('facebook_link', $company->social_links['facebook'] ?? '') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="yelp_link">Yelp Business Profile URL</label>
                            <input type="url" id="yelp_link" name="yelp_link" placeholder="https://yelp.com/biz/your-slug" value="{{ old('yelp_link', $company->social_links['yelp'] ?? '') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                    </div>
                </div>
            @endif

            <!-- PHASE 5: WORKFLOWS & FIELD DEPLOYMENT -->
            @if ($step === 5)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Phase 5: SaaS Dispatch Workflows</h3>
                        <p class="text-xs text-slate-500 font-semibold">Lock in operational billing configurations to finalize project provisioning loops.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="crew_structure">Operational Crew Alignment</label>
                            <select id="crew_structure" name="crew_structure" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                                <option value="solo" {{ old('crew_structure', $company->crew_structure) === 'solo' ? 'selected' : '' }}>Solo Operator (Single Profile)</option>
                                <option value="multi-crew" {{ old('crew_structure', $company->crew_structure) === 'multi-crew' ? 'selected' : '' }}>Multi-Crew Deployment (Sub-users Required)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="invoice_preferences">Billing Output Configurations</label>
                            <select id="invoice_preferences" name="invoice_preferences" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                                <option value="digital_only" {{ old('invoice_preferences', $company->invoice_preferences) === 'digital_only' ? 'selected' : '' }}>Instant Link via Web Portal & SMS Only</option>
                                <option value="cod" {{ old('invoice_preferences', $company->invoice_preferences) === 'cod' ? 'selected' : '' }}>Collect On Delivery (Field Draft Approvals)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="deposit_rules">Contract Mobilization Deposit Rules</label>
                        <textarea id="deposit_rules" name="deposit_rules" rows="3" placeholder="e.g., A 50% material mobilization deposit is required on all projects exceeding $2,500 prior to crew alignment scheduling." class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-medium text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner font-sans">{{ old('deposit_rules', $company->deposit_rules) }}</textarea>
                    </div>
                </div>
            @endif

            <!-- NAVIGATION TRIGGER DECK -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-100 gap-4">
                @if ($step > 1)
                    <button type="submit" name="direction" value="back" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-xs py-3 px-6 rounded-xl tracking-widest uppercase shadow-sm transition-all active:scale-[0.99] cursor-pointer">
                        &larr; Previous Section
                    </button>
                @else
                    <div></div>
                @endif

                <button type="submit" name="direction" value="next" class="flex-1 md:flex-none bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 px-8 rounded-xl tracking-widest uppercase shadow-md transition-all active:scale-[0.99] cursor-pointer text-center">
                    {{ $step === 5 ? 'Deploy Field Workspace ⚡' : 'Lock in Progress & Continue &rarr;' }}
                </button>
            </div>
        </form>
    </div>

</body>
</html>
