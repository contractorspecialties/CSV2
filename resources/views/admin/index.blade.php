<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Control Board | Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex flex-col min-h-full font-sans antialiased bg-slate-50 text-slate-900 selection:bg-[#f58613] selection:text-white">

    <header class="bg-slate-950 border-b-2 border-[#f58613] sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto px-4 h-24 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-2xl">⚙️</span>
                <div>
                    <h1 class="text-white font-black text-xs uppercase tracking-widest leading-none">System Architecture</h1>
                    <span class="text-[#f58613] font-mono text-lg font-black uppercase tracking-tight">Master Control Board</span>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 font-black text-[10px] py-2.5 px-4 rounded-xl uppercase tracking-wider transition-colors">
                Return To Dashboard →
            </a>
        </div>
    </header>

    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-8 space-y-6">

        @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                <span class="text-lg">⚡</span>
                <p class="text-xs font-black uppercase tracking-tight">{{ session('status') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-900 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                <span class="text-lg">⚠️</span>
                <p class="text-xs font-black uppercase tracking-tight">{{ $errors->first() }}</p>
            </div>
        @endif

        <section class="bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-black text-xs text-slate-900 uppercase tracking-wider">Systemic Workspace Engine Manifest</h3>
                <span class="text-[10px] bg-slate-950 text-amber-400 font-mono font-black px-2 py-0.5 rounded uppercase tracking-wide">Live Infrastructure Overview</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-[10px] font-black uppercase text-slate-400 bg-slate-50/50">
                            <th class="py-3.5 px-6">Company / Enterprise Space</th>
                            <th class="py-3.5 px-6">Programmatic Slug</th>
                            <th class="py-3.5 px-6">Administrative Identity</th>
                            <th class="py-3.5 px-6">Secure 2FA Routing Channel</th>
                            <th class="py-3.5 px-6 text-center">System Authority</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-semibold">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-black text-slate-950 text-sm tracking-tight uppercase">
                                        {{ $user->company->name ?? 'Unassociated Tenant' }}
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-mono tracking-tight block mt-0.5">ID Space: CS-00{{ $user->company_id }}</span>
                                </td>

                                <td class="py-4 px-6 font-mono text-slate-500 text-xs tracking-tight">
                                    @if(isset($user->company->slug))
                                        <span class="bg-slate-100 px-2 py-1 border border-slate-200/50 rounded-md">/{{ $user->company->slug }}</span>
                                    @else
                                        <span class="text-red-400 italic">No Node Compilations Found</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6">
                                    <div class="text-slate-900 font-bold text-sm">{{ $user->last_name }}, {{ $user->first_name }}</div>
                                    <span class="text-xs text-slate-500 block tracking-tight font-medium">{{ $user->email }}</span>
                                </td>

                                <td class="py-4 px-6">
                                    @if($user->phone_2fa)
                                        <span class="font-mono text-slate-950 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-lg text-xs shadow-inner">
                                            📞 {{ $user->phone_2fa }}
                                        </span>
                                    @else
                                        <span class="text-amber-600 bg-amber-50 border border-amber-200/60 text-[10px] font-black uppercase px-2 py-0.5 rounded tracking-wider">
                                            ⚠️ Unsecured Fallback Active
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-center">
                                    @if($user->id === auth()->id())
                                        <span class="inline-block px-3 py-1 bg-emerald-600 text-white font-black text-[9px] uppercase tracking-widest rounded-lg shadow-sm border border-emerald-700">
                                            👑 Core Operator
                                        </span>
                                    @else
                                        <form action="{{ route('admin.toggle-rights', ['id' => $user->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-28 text-center py-1.5 px-3 rounded-lg text-[10px] font-black tracking-widest uppercase shadow-sm transition-all active:scale-95 cursor-pointer border
                                                {{ $user->is_admin
                                                    ? 'bg-[#f58613] text-white border-orange-600 hover:bg-orange-600'
                                                    : 'bg-white text-slate-500 border-slate-300 hover:text-slate-900 hover:border-slate-400'
                                                }}
                                            ">
                                                {{ $user->is_admin ? 'Admin: ON' : 'Admin: OFF' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>

</body>
</html>
