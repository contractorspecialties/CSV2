<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-3xl text-slate-900 tracking-tight leading-tight">
                {{ __('Add New Client') }}
            </h2>
            <a href="{{ route('workspace.crm.index') }}" class="text-base font-black text-white hover:text-slate-200 transition bg-slate-900 hover:bg-black px-6 py-3 rounded-2xl shadow-md transform active:scale-95 flex items-center text-decoration-none">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to CRM
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-3xl shadow-md border-2 border-slate-300 overflow-hidden mb-10">

                <div class="px-6 md:px-10 py-6 bg-slate-900 border-b-4 border-slate-800 flex items-center">
                    <div class="p-2.5 bg-slate-800 text-white rounded-xl mr-4 shadow-sm border border-slate-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-2xl text-white tracking-wide">Client Details</h3>
                        <p class="text-base font-bold text-slate-400 mt-1">Add the contact information for your new lead or customer.</p>
                    </div>
                </div>

                <form id="client-form" action="{{ route('workspace.crm.store') }}" method="POST" class="p-6 md:p-10 space-y-8 bg-slate-50">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="name" class="block text-base font-black text-slate-900 uppercase tracking-wider mb-2">Full Name <span class="text-red-600">*</span></label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}" class="block w-full rounded-xl border-0 py-4 px-5 text-slate-900 font-bold text-lg shadow-sm ring-2 ring-inset ring-slate-400 placeholder:text-slate-400 focus:ring-4 focus:ring-slate-900 bg-white transition" placeholder="e.g. John Smith">
                            @error('name') <p class="mt-2 text-sm text-red-600 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="company" class="block text-base font-black text-slate-900 uppercase tracking-wider mb-2">Company Name</label>
                            <input type="text" name="company" id="company" value="{{ old('company') }}" class="block w-full rounded-xl border-0 py-4 px-5 text-slate-900 font-bold text-lg shadow-sm ring-2 ring-inset ring-slate-400 placeholder:text-slate-400 focus:ring-4 focus:ring-slate-900 bg-white transition" placeholder="e.g. ABC Plumbing">
                            @error('company') <p class="mt-2 text-sm text-red-600 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="phone" class="block text-base font-black text-slate-900 uppercase tracking-wider mb-2">Phone Number</label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5">
                                    <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="block w-full rounded-xl border-0 py-4 pl-14 text-slate-900 font-bold text-lg ring-2 ring-inset ring-slate-400 placeholder:text-slate-400 focus:ring-4 focus:ring-slate-900 bg-white transition" placeholder="(555) 555-5555">
                            </div>
                            @error('phone') <p class="mt-2 text-sm text-red-600 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-base font-black text-slate-900 uppercase tracking-wider mb-2">Email Address</label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5">
                                    <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="block w-full rounded-xl border-0 py-4 pl-14 text-slate-900 font-bold text-lg ring-2 ring-inset ring-slate-400 placeholder:text-slate-400 focus:ring-4 focus:ring-slate-900 bg-white transition" placeholder="john@example.com">
                            </div>
                            @error('email') <p class="mt-2 text-sm text-red-600 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-base font-black text-slate-900 uppercase tracking-wider mb-2">Job Site / Address</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5">
                                <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" class="block w-full rounded-xl border-0 py-4 pl-14 text-slate-900 font-bold text-lg ring-2 ring-inset ring-slate-400 placeholder:text-slate-400 focus:ring-4 focus:ring-slate-900 bg-white transition" placeholder="123 Main St, City, State" autocomplete="off">
                        </div>
                        @error('address') <p class="mt-2 text-sm text-red-600 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 border-t-2 border-slate-200">
                        <label for="notes" class="block text-base font-black text-slate-900 uppercase tracking-wider mb-1">Internal Notes</label>
                        <p class="text-sm font-bold text-slate-500 mb-3">Gate codes, dog names, or specific client requests. The client will never see this.</p>
                        <textarea id="notes" name="notes" rows="4" class="block w-full rounded-xl border-0 py-4 px-5 text-slate-900 font-bold text-lg shadow-sm ring-2 ring-inset ring-slate-400 placeholder:text-slate-400 focus:ring-4 focus:ring-slate-900 bg-white transition" placeholder="Beware of the dog in the backyard...">{{ old('notes') }}</textarea>
                        @error('notes') <p class="mt-2 text-sm text-red-600 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-8 border-t-4 border-slate-200 flex flex-col md:flex-row justify-end items-center">
                        <button type="submit" class="w-full md:w-auto bg-slate-900 hover:bg-black text-white px-10 py-5 rounded-2xl text-xl font-black shadow-xl transition transform active:scale-95 flex justify-center items-center border-0 cursor-pointer">
                            Save Client Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(config('services.google.maps_api_key'))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>
        <script>
            function initAutocomplete() {
                const addressInput = document.getElementById('address');
                if (!addressInput) return;

                const autocomplete = new google.maps.places.Autocomplete(addressInput, {
                    types: ['address'],
                    componentRestrictions: { country: 'us' },
                    fields: ['formatted_address']
                });

                addressInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                    }
                });

                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    if (place.formatted_address) {
                        addressInput.value = place.formatted_address;
                        addressInput.blur();
                    }
                });
            }
        </script>

        <style>
            .pac-container {
                border-radius: 0.75rem;
                border: 2px solid #cbd5e1;
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
                font-family: inherit;
                padding: 0.5rem 0;
                z-index: 9999;
            }
            .pac-item {
                padding: 0.75rem 1rem;
                font-size: 1rem;
                color: #0f172a;
                cursor: pointer;
            }
            .pac-item:hover {
                background-color: #f8fafc;
            }
            .pac-item-query {
                font-weight: 900;
                font-size: 1rem;
                color: #0f172a;
            }
            .pac-icon {
                display: none;
            }
        </style>
    @endif
</x-app-layout>
