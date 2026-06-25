<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field CRM Client Roster | ContractorSpecialties</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="min-h-full font-sans antialiased bg-slate-100 text-slate-900 selection:bg-[#f58613] selection:text-white"
      x-data="{
          showAddModal: false,
          activeClient: null,
          shareStatus: false,
          triggerShareNotify() {
              this.shareStatus = true;
              setTimeout(() => { this.shareStatus = false; }, 2500);
          }
      }">

    <header class="bg-black border-b border-slate-900 sticky top-0 z-40 shadow-lg">
        <div class="max-w-xl mx-auto px-4 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/dashboard" class="bg-slate-900 border border-slate-800 text-slate-200 text-xs font-black p-3 rounded-xl active:scale-95 transition-all outline-none">
                    &larr; Desk
                </a>
                <h1 class="text-sm font-black uppercase tracking-tight text-white font-mono">Field CRM Roster</h1>
            </div>
            <button type="button"
                    @click="showAddModal = true"
                    class="bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs py-3 px-4 rounded-xl uppercase tracking-wider shadow active:scale-95 transition-all border-0 outline-none cursor-pointer">
                + New Client
            </button>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-4 py-6 pb-32">

        @if(session('status'))
            <div class="bg-emerald-600 border border-emerald-700 text-white font-black text-xs uppercase tracking-tight rounded-xl p-4 mb-4 shadow-md flex items-center gap-2">
                <span>👍</span>
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <form action="{{ route('workspace.crm.index') }}" method="GET" class="mb-6">
            <div class="flex gap-2 bg-white border-2 border-slate-900 p-1.5 rounded-2xl shadow-sm">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="🔎 Lookup client name or city..."
                       class="w-full bg-transparent border-0 text-sm font-bold text-slate-900 focus:outline-none px-2 h-11 placeholder-slate-400">
                @if(request('search'))
                    <a href="{{ route('workspace.crm.index') }}" class="flex items-center justify-center px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs uppercase text-decoration-none">Clear</a>
                @endif
                <button type="submit" class="bg-slate-950 hover:bg-black text-white font-black text-xs uppercase tracking-wider px-5 rounded-xl transition-colors active:scale-95 border-0 cursor-pointer">
                    Go
                </button>
            </div>
        </form>

        <div class="space-y-4">
            @forelse($clients as $client)
                <div class="bg-white border-2 border-slate-300 rounded-2xl p-5 shadow-sm hover:border-slate-900 transition-all cursor-pointer relative"
                     @click="activeClient = {{ json_encode($client) }}">

                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <h2 class="text-base font-black text-slate-900 tracking-tight leading-tight">{{ $client->client_name }}</h2>
                            <p class="text-xs font-bold text-[#f58613] uppercase tracking-wide mt-0.5">{{ $client->project_type ?? 'General Trade Job' }}</p>
                        </div>

                        @if($client->job_status === 'lead')
                            <span class="text-[9px] font-mono font-black uppercase tracking-wider bg-indigo-100 border border-indigo-300 text-indigo-800 px-2.5 py-1 rounded-md">New Lead</span>
                        @elseif($client->job_status === 'active')
                            <span class="text-[9px] font-mono font-black uppercase tracking-wider bg-emerald-100 border border-emerald-300 text-emerald-800 px-2.5 py-1 rounded-md animate-pulse">Active Job</span>
                        @elseif($client->job_status === 'paused')
                            <span class="text-[9px] font-mono font-black uppercase tracking-wider bg-amber-100 border border-amber-300 text-amber-800 px-2.5 py-1 rounded-md">Paused</span>
                        @else
                            <span class="text-[9px] font-mono font-black uppercase tracking-wider bg-slate-100 border border-slate-300 text-slate-600 px-2.5 py-1 rounded-md">Finished Job</span>
                        @endif
                    </div>

                    <div class="text-xs font-semibold text-slate-500 flex items-center gap-1">
                        <span>📍</span>
                        <span>{{ $client->city ?? 'Local Town' }}, {{ $client->zip_code }}</span>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-black text-slate-400 uppercase tracking-wider font-mono">
                        <span>Open File Options</span>
                        <span>&rarr;</span>
                    </div>
                </div>
            @empty
                <div class="bg-white border-2 border-dashed border-slate-300 rounded-2xl p-10 text-center">
                    <span class="text-3xl select-none">🗂️</span>
                    <h3 class="text-sm font-black text-slate-900 uppercase mt-2">Client Roster Blank</h3>
                    <p class="text-xs text-slate-400 font-medium mt-1">Punch in your first customer account profile using the button above.</p>
                </div>
            @endforelse
        </div>
    </main>

    <div x-show="activeClient"
         class="fixed inset-0 z-50 overflow-hidden"
         x-cloak>
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" @click="activeClient = null"></div>

        <div class="absolute inset-x-0 bottom-0 max-w-xl mx-auto bg-white border-t-4 border-slate-950 rounded-t-3xl shadow-2xl max-h-[92vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300 transform translate-y-full"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform translate-y-0"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full">

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-start justify-between sticky top-0 z-10">
                <div>
                    <span class="text-[9px] font-mono font-black bg-slate-950 text-white px-2 py-0.5 rounded uppercase tracking-widest" x-text="'File ID #' + activeClient?.id"></span>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight mt-1" x-text="activeClient?.client_name"></h3>
                    <p class="text-xs font-bold text-[#f58613] uppercase tracking-wide" x-text="activeClient?.project_type"></p>
                </div>
                <button type="button"
                        @click="activeClient = null"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-black text-xs py-2 px-3.5 rounded-xl border-0 outline-none cursor-pointer">
                    Dismiss
                </button>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <a :href="'tel:' + activeClient?.phone_number"
                       class="bg-emerald-600 hover:bg-emerald-700 text-white font-black text-center text-sm py-4 px-4 rounded-xl shadow-md active:scale-98 transition-all flex flex-col items-center justify-center gap-1.5 border-0 text-decoration-none">
                        <span class="text-xl">📞</span>
                        <span class="uppercase tracking-wider text-xs">Call Customer</span>
                    </a>

                    <a :href="activeClient?.google_maps_url"
                       target="_blank"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white font-black text-center text-sm py-4 px-4 rounded-xl shadow-md active:scale-98 transition-all flex flex-col items-center justify-center gap-1.5 border-0 text-decoration-none">
                        <span class="text-xl">🗺️</span>
                        <span class="uppercase tracking-wider text-xs">Open Maps Link</span>
                    </a>
                </div>

                <div class="bg-slate-900 text-white p-4 rounded-xl border border-slate-950 space-y-3 shadow-inner">
                    <div class="flex items-center justify-between">
                        <h4 class="text-[10px] font-mono font-black text-slate-400 uppercase tracking-widest">Subcontractor Job Forwarder</h4>
                        <span class="text-[9px] bg-emerald-950 text-emerald-400 font-bold px-2 py-0.5 rounded border border-emerald-900/40">Privacy Shield Safe</span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-semibold leading-normal">Copies job parameters to your clipboard so you can text details to workers without leaking private phone numbers or customer addresses.</p>

                    <button type="button"
                            @click="navigator.clipboard.writeText(activeClient?.subcontractor_share_text); triggerShareNotify();"
                            class="w-full bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs uppercase py-3 px-4 rounded-xl tracking-wider transition-colors active:scale-95 flex items-center justify-center gap-2 border-0 outline-none cursor-pointer">
                        <span x-text="shareStatus ? '✅ Copied to Clipboard!' : '📋 Copy Clean Job Scope Info'"></span>
                    </button>
                </div>

                <form :action="'/workspace/crm/update/' + activeClient?.id" method="POST" class="space-y-4 pt-4 border-t border-slate-100">
                    @csrf

                    <input type="hidden" name="client_name" :value="activeClient?.client_name">
                    <input type="hidden" name="phone_number" :value="activeClient?.phone_number">
                    <input type="hidden" name="email_address" :value="activeClient?.email_address">
                    <input type="hidden" name="street_address" :value="activeClient?.street_address">
                    <input type="hidden" name="city" :value="activeClient?.city">
                    <input type="hidden" name="state" :value="activeClient?.state">
                    <input type="hidden" name="zip_code" :value="activeClient?.zip_code">
                    <input type="hidden" name="project_type" :value="activeClient?.project_type">
                    <input type="hidden" name="project_description" :value="activeClient?.project_description">

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Update Job Stage Status</label>
                        <select name="job_status" :value="activeClient?.job_status" class="w-full bg-slate-100 border-2 border-slate-300 rounded-xl py-3 px-4 text-sm font-bold text-slate-900 focus:outline-none cursor-pointer">
                            <option value="lead">New Lead (Follow Up Phase)</option>
                            <option value="active">Active Job (Field Crew Dispatched)</option>
                            <option value="paused">Paused (Waiting on Mats/Inspections)</option>
                            <option value="completed">Finished Job (Archived Record)</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Append New Timestamped Site Note</label>
                        <textarea name="new_site_note" rows="2" placeholder="Punch in quick site updates, material issues, or approvals here..." class="w-full bg-slate-50 border-2 border-slate-300 rounded-xl py-3 px-4 text-sm font-medium focus:outline-none text-slate-900"></textarea>
                    </div>

                    <div x-show="activeClient?.customer_notes" class="space-y-1.5" x-cloak>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Past Historical Site Notes Journal</label>
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-mono text-slate-700 whitespace-pre-wrap max-h-40 overflow-y-auto leading-relaxed" x-text="activeClient?.customer_notes"></div>
                    </div>

                    <button type="submit" class="w-full bg-slate-950 hover:bg-black text-white font-black text-xs uppercase tracking-widest py-4 px-4 rounded-xl shadow-md transition-all border-0 cursor-pointer">
                        Commit Progress Updates &rarr;
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div x-show="showAddModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
         x-cloak>
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" @click="showAddModal = false"></div>

        <div class="bg-white border-2 border-slate-950 rounded-2xl max-w-md w-full p-6 shadow-2xl relative z-10 max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200 transform scale-95"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <h3 class="text-sm font-black uppercase text-slate-950 tracking-tight font-mono">Create New Client Account</h3>
                <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-900 font-bold text-xs bg-transparent border-0 cursor-pointer outline-none">✕</button>
            </div>

            <form action="{{ route('workspace.crm.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="job_status" value="lead">

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1">Customer / Project Name *</label>
                    <input type="text" name="client_name" required placeholder="e.g., John Smith or Wake Custom Decks" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-sm font-semibold focus:outline-none text-slate-900">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1">Phone Number *</label>
                        <input type="tel" name="phone_number" required placeholder="(919) 555-0199" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-sm font-semibold focus:outline-none text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email_address" placeholder="name@domain.com" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-sm font-semibold focus:outline-none text-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1">Street Address</label>
                    <input type="text" name="street_address" placeholder="102 Main Street" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-sm font-semibold focus:outline-none text-slate-900">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1">City</label>
                        <input type="text" name="city" placeholder="Raleigh" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-sm font-semibold focus:outline-none text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1">Zip Code</label>
                        <input type="text" name="zip_code" placeholder="27601" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-sm font-semibold focus:outline-none text-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1">Primary Job Specialty Type *</label>
                    <input type="text" name="project_type" required placeholder="e.g., Metal Roofing Repair, Deck Build" class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-sm font-semibold focus:outline-none text-slate-900">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 tracking-wider mb-1">Scope of Work Description</label>
                    <textarea name="project_description" rows="3" placeholder="Describe the materials, structural limits, or timeline requirements..." class="w-full bg-slate-50 border border-slate-300 rounded-xl py-2.5 px-3 text-sm font-medium focus:outline-none text-slate-900"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase py-3 px-4 rounded-xl border-0 cursor-pointer">Cancel</button>
                    <button type="submit" class="bg-[#f58613] hover:bg-orange-600 text-white font-black text-xs uppercase tracking-wider py-3 px-6 rounded-xl shadow-md border-0 cursor-pointer">Log To List</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
