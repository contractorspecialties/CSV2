<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile Setup | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col justify-center min-h-full font-sans antialiased bg-slate-50 px-4 py-12 selection:bg-[#f58613] selection:text-white">

    <div class="w-full max-w-xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-xl p-6 md:p-8 space-y-6">

        <!-- Progress Track Module -->
        <div class="space-y-2">
            <div class="flex justify-between items-center text-[10px] font-black uppercase text-slate-400 tracking-wider">
                <span>Setup Progress</span>
                <span class="text-[#f58613]">Step {{ $step }} of 5</span>
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

        <!-- Fixed enctype configuration here -->
        <form action="{{ route('onboarding.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="current_step" value="{{ $step }}">

            <!-- PAGE 1: COMPANY DETAILS -->
            @if ($step === 1)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Company Details</h3>
                        <p class="text-xs text-slate-500 font-semibold">Authenticated. Please complete your company profile below.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_name">Company Name</label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $company->name) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="owner_name">Contractor's First and Last Name</label>
                            <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name', $company->owner_name) }}" required placeholder="e.g. John Doe" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="business_phone">Business Phone Number</label>
                            <input type="text" id="business_phone" name="business_phone" value="{{ old('business_phone', $company->business_phone ?? $user->phone_2fa) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="primary_specialty">Primary Trade</label>
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

            <!-- PAGE 2: IDENTITY & COMPLIANCE -->
            @if ($step === 2)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Identity & Compliance</h3>
                        <p class="text-xs text-slate-500 font-semibold">Provide your company outline, licensing data, and insurance details.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="years_experience">Years in Business</label>
                            <input type="number" id="years_experience" name="years_experience" value="{{ old('years_experience', $company->years_experience ?? 0) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="license_number">License #</label>
                            <input type="text" id="license_number" name="license_number" placeholder="Optional" value="{{ old('license_number', $company->license_number) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="is_insured">Insurance Coverage</label>
                            <select id="is_insured" name="is_insured" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                <option value="none" {{ old('is_insured', $company->is_insured) === 'none' ? 'selected' : '' }}>No Coverage</option>
                                <option value="liability" {{ old('is_insured', $company->is_insured) === 'liability' ? 'selected' : '' }}>Liability Only</option>
                                <option value="liability_workers" {{ old('is_insured', $company->is_insured) === 'liability_workers' ? 'selected' : '' }}>Liability and Workers Comp</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_bio_short">Company Slogan</label>
                        <input type="text" id="company_bio_short" name="company_bio_short" placeholder="e.g., Quality roofing and siding across eastern North Carolina." value="{{ old('company_bio_short', $company->company_bio_short) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="company_bio_long">Company Description, Experience, and Operations</label>
                        <textarea id="company_bio_long" name="company_bio_long" rows="4" placeholder="Tell customers about your services, specialized experience, and standard crew operations..." required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-medium text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner font-sans">{{ old('company_bio_long', $company->company_bio_long) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="logo_file">Company Logo</label>
                        <input type="file" id="logo_file" name="logo_file" accept="image/*" class="w-full text-xs font-bold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-slate-950 file:text-[#f58613] file:cursor-pointer cursor-pointer">
                    </div>
                </div>
            @endif

            <!-- PAGE 3: LIST JOB SPECIALTIES -->
            @if ($step === 3)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">List Job Specialties</h3>
                        <p class="text-xs text-slate-500 font-semibold">Select all fields of work your team covers on a regular basis.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-3">Check All Specialties Offered</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(['Roof Replacements', 'Drywall & Framing', 'Decks & Porches', 'Service Calls & Repairs', 'Lawn & Landscape', 'Commercial Work'] as $service)
                                <label class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-950 cursor-pointer hover:border-[#f58613] transition-all shadow-sm">
                                    <input type="checkbox" name="service_tags[]" value="{{ $service }}" {{ is_array(old('service_tags', $company->service_tags)) && in_array($service, old('service_tags', $company->service_tags)) ? 'checked' : '' }} class="accent-[#f58613] w-4 h-4 shrink-0">
                                    <span>{{ $service }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="emergency_availability">Emergency Availability</label>
                        <select id="emergency_availability" name="emergency_availability" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                            <option value="0" {{ old('emergency_availability', $company->emergency_availability) == 0 ? 'selected' : '' }}>No, Standard Business Hours Only</option>
                            <option value="1" {{ old('emergency_availability', $company->emergency_availability) == 1 ? 'selected' : '' }}>Yes, Available for Emergency Overtime Calls</option>
                        </select>
                    </div>
                </div>
            @endif

            <!-- PAGE 4: TRUST CONNECTIONS -->
            @if ($step === 4)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Review Profiles</h3>
                        <p class="text-xs text-slate-500 font-semibold">Link your public review links to build instant customer trust.</p>
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
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="yelp_link">Yelp Business Profile URL</label>
                            <input type="url" id="yelp_link" name="yelp_link" placeholder="https://yelp.com/biz/your-slug" value="{{ old('yelp_link', $company->social_links['yelp'] ?? '') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                    </div>
                </div>
            @endif

            <!-- PAGE 5: CREW AND BILLING -->
            @if ($step === 5)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Crew and Billing</h3>
                        <p class="text-xs text-slate-500 font-semibold">Set your project numbers, tax variables, and payment options to finalize setup.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="crew_structure">Crew Size</label>
                            <select id="crew_structure" name="crew_structure" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                <option value="solo" {{ old('crew_structure', $company->crew_structure) === 'solo' ? 'selected' : '' }}>Owner/Operator</option>
                                <option value="small" {{ old('crew_structure', $company->crew_structure) === 'small' ? 'selected' : '' }}>Owner plus 1-3</option>
                                <option value="large" {{ old('crew_structure', $company->crew_structure) === 'large' ? 'selected' : '' }}>Owner plus 4-8</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="invoice_preferences">Bill Pay Link</label>
                            <select id="invoice_preferences" name="invoice_preferences" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner cursor-pointer">
                                <option value="digital_only" {{ old('invoice_preferences', $company->invoice_preferences) === 'digital_only' ? 'selected' : '' }}>Link your favorite payment app here</option>
                                <option value="cod" {{ old('invoice_preferences', $company->invoice_preferences) === 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="state_tax_rate">State Tax Rate (%)</label>
                            <input type="number" step="0.001" id="state_tax_rate" name="state_tax_rate" placeholder="e.g. 4.75" value="{{ old('state_tax_rate', $company->state_tax_rate ?? '') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="start_number">Estimate/Invoice Start #</label>
                            <input type="number" id="start_number" name="start_number" placeholder="e.g. 1001" value="{{ old('start_number', $company->start_number ?? '1001') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-1.5" for="deposit_rules">Deposit Preference</label>
                        <textarea id="deposit_rules" name="deposit_rules" rows="3" placeholder="e.g., We require a 50% deposit upfront for materials before work is officially booked onto our schedule." class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-medium text-slate-950 focus:outline-none focus:border-[#f58613] shadow-inner font-sans">{{ old('deposit_rules', $company->deposit_rules) }}</textarea>
                    </div>
                </div>
            @endif

            <!-- NAVIGATION TRIGGER DECK -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-100 gap-4">
                @if ($step > 1)
                    <button type="submit" name="direction" value="back" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-xs py-3 px-6 rounded-xl tracking-widest uppercase shadow-sm transition-all active:scale-[0.99] cursor-pointer border-0">
                        &larr; Save & Go Back
                    </button>
                @else
                    <div></div>
                @endif

                <button type="submit" name="direction" value="next" class="flex-1 md:flex-none bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3.5 px-8 rounded-xl tracking-widest uppercase shadow-md transition-all active:scale-[0.99] cursor-pointer text-center border-0">
                    {{ $step === 5 ? 'Preview Company Profile Page' : 'Save & Continue &rarr;' }}
                </button>
            </div>
        </form>
    </div>

</body>
</html>
