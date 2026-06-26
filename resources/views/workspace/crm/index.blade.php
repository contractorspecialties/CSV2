<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Roster | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="w-[400px] max-w-[45%] h-[100px] flex items-center">
                <img src="/images/header-logo.webp" alt="ContractorSpecialties Logo" class="w-full h-auto max-h-[90px] object-contain object-left">
            </div>

            <div class="flex items-center gap-4">
                <span class="text-slate-400 font-black text-xs uppercase tracking-widest hidden lg:inline-block">
                    {{ now()->format('l, F jS') }}
                </span>
                <a href="/dashboard" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-200 hover:text-white font-black text-[10px] py-2.5 px-4 rounded-xl uppercase tracking-wider transition-all text-decoration-none shadow-sm cursor-pointer">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-8 space-y-8">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 border-b border-slate-200 pb-5">
            <h2 class="font-black text-3xl text-slate-900 tracking-tight leading-tight">
                Client Roster
            </h2>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('workspace.crm.export') }}" class="w-full sm:w-auto text-center bg-white hover:bg-slate-50 border-2 border-slate-300 text-slate-600 hover:text-slate-900 font-black py-4 px-6 rounded-2xl shadow-sm transition transform active:scale-95 text-sm uppercase tracking-widest flex justify-center items-center text-decoration-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export CSV
                </a>
                <a href="/workspace/crm/create" class="w-full sm:w-auto text-center bg-slate-900 hover:bg-black text-white font-black py-4 px-8 rounded-2xl shadow-xl transition transform active:scale-95 text-lg flex justify-center items-center text-decoration-none">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Add Client
                </a>
            </div>
        </div>

        @if(session('status'))
            <div class="bg-emerald-600 border border-emerald-700 text-white font-black text-xs uppercase tracking-tight rounded-2xl p-4 shadow-md flex items-center gap-2">
                <span>👍</span>
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <form action="{{ route('workspace.crm.index') }}" method="GET" class="mb-10">
            <div class="relative flex items-center">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by name, company, email, or phone..." class="block w-full pl-16 pr-24 py-5 rounded-3xl border-4 border-slate-300 focus:border-slate-900 focus:ring-0 shadow-md text-xl font-bold text-slate-900 placeholder:text-slate-500 transition bg-white">

                @if(!empty($search))
                    <a href="{{ route('workspace.crm.index') }}" class="absolute right-4 bg-slate-200 hover:bg-slate-300 text-slate-700 font-black py-2 px-4 rounded-xl transition shadow-sm border-2 border-slate-300 hover:border-slate-400 text-sm uppercase tracking-wider text-decoration-none">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @forelse($clients as $client)
                <div class="bg-white rounded-3xl shadow-lg border-2 border-slate-300 overflow-hidden flex flex-col hover:border-slate-900 hover:shadow-2xl transition-all group">

                    <div class="px-6 py-6 bg-slate-900 border-b-4 border-slate-800 flex justify-between items-center gap-4">
                        <div class="flex items-center gap-4 overflow-hidden flex-1">
                            <div class="bg-slate-700 h-16 w-16 rounded-2xl flex items-center justify-center text-white font-black text-2xl uppercase border-2 border-slate-600 shadow-inner shrink-0 group-hover:bg-[#f58613] transition-colors">
                                {{ substr($client->name, 0, 2) }}
                            </div>
                            <div class="overflow-hidden">
                                <h3 class="text-2xl font-black text-white leading-tight transition truncate">{{ $client->name }}</h3>
                                @if($client->company)
                                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-1 truncate">{{ $client->company }}</p>
                                @endif
                            </div>
                        </div>
                        <a href="/workspace/crm/edit/{{ $client->id }}" class="text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 p-3 rounded-xl transition border-2 border-slate-700 hover:border-slate-500 shadow-sm shrink-0 active:scale-95 text-decoration-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                    </div>

                    <div class="p-6 md:p-8 flex-grow bg-slate-50">
                        <div class="bg-white rounded-2xl p-6 border-2 border-slate-200 relative overflow-hidden shadow-sm">
                            <h4 class="text-sm font-black text-slate-500 uppercase tracking-widest mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                Gate Codes / Notes
                            </h4>
                            <p class="text-lg text-slate-800 font-bold line-clamp-3">
                                {{ $client->notes ?? 'No specific job site notes saved yet.' }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 border-t-4 border-slate-200 bg-white">
                        @if($client->phone)
                            <a href="tel:{{ $client->phone }}" class="flex flex-col items-center justify-center py-6 border-r-2 border-b-2 border-slate-200 hover:bg-emerald-50 transition action-group text-decoration-none">
                                <div class="p-3 bg-emerald-100 text-emerald-700 rounded-xl mb-2 action-group-hover:bg-emerald-600 action-group-hover:text-white transition-colors shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Call</span>
                            </a>
                        @else
                            <div class="flex flex-col items-center justify-center py-6 border-r-2 border-b-2 border-slate-200 opacity-40 cursor-not-allowed">
                                <div class="p-3 bg-slate-200 text-slate-500 rounded-xl mb-2">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Call</span>
                            </div>
                        @endif

                        @if($client->email)
                            <a href="mailto:{{ $client->email }}" class="flex flex-col items-center justify-center py-6 border-b-2 border-slate-200 hover:bg-orange-50 transition action-group text-decoration-none">
                                <div class="p-3 bg-orange-100 text-[#f58613] rounded-xl mb-2 action-group-hover:bg-orange-600 action-group-hover:text-white transition-colors shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Email</span>
                            </a>
                        @else
                            <div class="flex flex-col items-center justify-center py-6 border-b-2 border-slate-200 opacity-40 cursor-not-allowed">
                                <div class="p-3 bg-slate-200 text-slate-500 rounded-xl mb-2">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Email</span>
                            </div>
                        @endif

                        @if($client->address)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($client->address) }}" target="_blank" class="flex flex-col items-center justify-center py-6 border-r-2 border-slate-200 hover:bg-indigo-50 transition action-group text-decoration-none">
                                <div class="p-3 bg-indigo-100 text-indigo-700 rounded-xl mb-2 action-group-hover:bg-indigo-600 action-group-hover:text-white transition-colors shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Map</span>
                            </a>
                        @else
                            <div class="flex flex-col items-center justify-center py-6 border-r-2 border-slate-200 opacity-40 cursor-not-allowed">
                                <div class="p-3 bg-slate-200 text-slate-500 rounded-xl mb-2">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Map</span>
                            </div>
                        @endif

                        @if($client->address)
                            <button onclick="shareDirections('{{ addslashes($client->name) }}', '{{ addslashes($client->address) }}')" class="flex flex-col items-center justify-center py-6 hover:bg-violet-50 transition action-group bg-transparent border-0 cursor-pointer outline-none">
                                <div class="p-3 bg-violet-100 text-violet-700 rounded-xl mb-2 action-group-hover:bg-violet-600 action-group-hover:text-white transition-colors shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                </div>
                                <span class="text-xs font-black text-slate-600 uppercase tracking-widest">To Team</span>
                            </button>
                        @else
                            <div class="flex flex-col items-center justify-center py-6 opacity-40 cursor-not-allowed">
                                <div class="p-3 bg-slate-200 text-slate-500 rounded-xl mb-2">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                </div>
                                <span class="text-xs font-black text-slate-600 uppercase tracking-widest">To Team</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 bg-white rounded-3xl border-4 border-dashed border-slate-300 text-center px-4 shadow-sm">
                    <svg class="w-20 h-20 text-slate-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>

                    @if(!empty($search))
                        <h3 class="text-3xl font-black text-slate-900 mb-3">No Results Found</h3>
                        <p class="text-slate-600 text-lg mb-10 font-bold">We couldn't find any clients matching "{{ $search }}".</p>
                        <a href="{{ route('workspace.crm.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-black py-4 px-8 rounded-2xl shadow-sm transition transform active:scale-95 text-lg inline-block border-2 border-slate-300 text-decoration-none">Clear Search</a>
                    @else
                        <h3 class="text-3xl font-black text-slate-900 mb-3">No Clients Yet</h3>
                        <p class="text-slate-600 text-lg mb-10 font-bold">Add your first client to start building quotes.</p>
                        <a href="/workspace/crm/create" class="bg-slate-900 hover:bg-black text-white font-black py-5 px-10 rounded-2xl shadow-xl transition transform active:scale-95 text-xl inline-block text-decoration-none">Add Your First Client</a>
                    @endif
                </div>
            @endforelse
        </div>
    </main>

    <footer class="border-t border-slate-900 bg-black text-slate-400 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs font-medium text-slate-500">
            © 2026 ContractorSpecialties. All company databases and communication networks secure.
        </div>
    </footer>

    <script>
        function shareDirections(name, address) {
            const encodedAddress = encodeURIComponent(address);
            const text = `Job Site Info for ${name}:\n\nAddress: ${address}\n\nGoogle Maps Link: https://www.google.com/maps/search/?api=1&query=${encodedAddress}`;

            if (navigator.share) {
                navigator.share({
                    title: 'Job Site Directions',
                    text: text,
                }).catch((error) => console.log('Error sharing', error));
            } else {
                navigator.clipboard.writeText(text);
                alert('Job site details copied to clipboard!');
            }
        }
    </script>

    <style>
        .action-group:hover .action-group-hover\:bg-emerald-600 { background-color: #059669; }
        .action-group:hover .action-group-hover\:text-white { color: #ffffff; }
        .action-group:hover .action-group-hover\:bg-indigo-600 { background-color: #4f46e5; }
        .action-group:hover .action-group-hover\:bg-violet-600 { background-color: #7c3aed; }
        .action-group:hover .action-group-hover\:bg-orange-600 { background-color: #ea580c; }
    </style>
</body>
</html>
