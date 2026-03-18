<div class="p-6 max-w-7xl mx-auto space-y-5 animate-fade-in" x-data="{ activeTab: 'overview' }">
    {{-- CSS Animasi --}}
    <style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    [x-cloak] {
        display: none !important;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.3);
        border-radius: 10px;
    }
    </style>

    {{-- ALERT NOTIFIKASI --}}
    @if (session()->has('message'))
    <div
        class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-lg"></i> {{ session('message') }}</div>
        <button type="button" class="opacity-50 hover:opacity-100" onclick="this.parentElement.remove()"><i
                class="fas fa-times"></i></button>
    </div>
    @endif
    @if (session()->has('error'))
    <div
        class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-2"><i class="fas fa-info-circle text-lg"></i> {{ session('error') }}</div>
        <button type="button" class="opacity-50 hover:opacity-100" onclick="this.parentElement.remove()"><i
                class="fas fa-times"></i></button>
    </div>
    @endif

    {{-- 1. WELCOME HERO SECTION (KINI LEBIH RAMPING & COMPACT) --}}
    <div
        class="bg-white dark:bg-[#121212] rounded-2xl px-6 py-5 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden">
        <div
            class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-blue-50 to-transparent dark:from-blue-900/10 pointer-events-none">
        </div>
        <div
            class="absolute -right-4 -top-10 text-blue-50 dark:text-blue-900/20 rotate-12 pointer-events-none transition-transform hover:rotate-6">
            <i class="fas fa-shield-alt text-[10rem]"></i>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white items-center justify-center text-xl shadow-lg shadow-blue-500/30">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <p class="text-blue-600 dark:text-blue-400 font-black tracking-widest text-[10px] uppercase">
                            Pusat Kendali Pengawasan</p>
                        <span
                            class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-white/5 text-[9px] font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1 border border-slate-200 dark:border-white/5">
                            <i class="fas fa-clock text-blue-500"></i> Sync:
                            {{ $lastSync ? \Carbon\Carbon::parse($lastSync)->translatedFormat('d M H:i') : '-' }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                        Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                    </h1>
                </div>
            </div>

            <div class="flex shrink-0">
                <a href="{{ route('master.produk') }}"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-600/20 active:scale-95">
                    <i class="fas fa-box-open"></i> Master Produk
                </a>
            </div>
        </div>
    </div>

    {{-- 2. NAVIGASI TAB MENU --}}
    <div
        class="flex p-1.5 bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm w-full sm:w-fit overflow-x-auto custom-scrollbar">
        <button @click="activeTab = 'overview'"
            :class="activeTab === 'overview' ? 'bg-slate-100 dark:bg-white/10 text-slate-800 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'"
            class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap min-w-[140px]">
            <i class="fas fa-chart-pie"></i> Ringkasan Master
        </button>

        <button @click="activeTab = 'persetujuan'"
            :class="activeTab === 'persetujuan' ? 'bg-rose-50 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50/50 dark:hover:bg-rose-500/10'"
            class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap min-w-[140px] relative">
            <i class="fas fa-clipboard-check"></i> Otorisasi Data
            @if($pendingRequests->count() > 0)
            <span class="absolute top-1 right-1 flex h-3 w-3">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                <span
                    class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 text-[8px] items-center justify-center text-white">{{ $pendingRequests->count() }}</span>
            </span>
            @endif
        </button>

        <button @click="activeTab = 'monitoring'"
            :class="activeTab === 'monitoring' ? 'bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-500/10'"
            class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap min-w-[140px]">
            <i class="fas fa-search-dollar"></i> Evaluasi Kinerja
        </button>
    </div>

    {{-- ========================================== --}}
    {{-- ISI TAB 1: RINGKASAN MASTER (OVERVIEW)     --}}
    {{-- ========================================== --}}
    <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            <div
                class="bg-white dark:bg-[#121212] rounded-2xl p-5 border {{ $anomaliProdukCount > 0 ? 'border-rose-400 dark:border-rose-600 shadow-rose-500/10' : 'border-slate-200 dark:border-white/10' }} shadow-sm relative overflow-hidden group">
                <div
                    class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-rose-50 dark:from-rose-900/10 to-transparent pointer-events-none">
                </div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">Anomali Master
                        </p>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white">
                            {{ number_format($anomaliProdukCount) }}</h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl {{ $anomaliProdukCount > 0 ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/30' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400' }} flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i
                            class="fas fa-exclamation-triangle text-lg {{ $anomaliProdukCount > 0 ? 'animate-pulse' : '' }}"></i>
                    </div>
                </div>
                <div class="mt-4 relative z-10">
                    @if($anomaliProdukCount > 0)
                    <div
                        class="flex items-center gap-1.5 text-[9px] font-bold text-rose-700 dark:text-rose-300 px-2 py-1.5 bg-rose-100 dark:bg-rose-900/40 rounded-lg border border-rose-200 dark:border-rose-700/50 leading-tight">
                        <i class="fas fa-info-circle"></i> SKU Penjualan tak terdaftar!
                    </div>
                    @else
                    <div
                        class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 px-2 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-100 dark:border-emerald-800/30">
                        <i class="fas fa-check-double text-emerald-500"></i> Semua SKU sinkron.
                    </div>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-[#121212] rounded-2xl p-5 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden group">
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">Total Produk</p>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($totalProduk) }}
                        </h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center text-cyan-600 dark:text-cyan-400 group-hover:scale-110 transition-transform">
                        <i class="fas fa-boxes text-lg"></i></div>
                </div>
                <div class="mt-4 relative z-10">
                    @if($produkKosong > 0)
                    <div
                        class="flex items-center gap-1.5 text-[10px] font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 px-2 py-1.5 rounded-lg border border-rose-100 dark:border-rose-800/30">
                        <i class="fas fa-box-open"></i> {{ $produkKosong }} Stok Kosong</div>
                    @else
                    <div
                        class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 px-2 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-100 dark:border-emerald-800/30">
                        <i class="fas fa-check-circle text-emerald-500"></i> Stok master aman</div>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-[#121212] rounded-2xl p-5 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden group">
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">Total Supplier
                        </p>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white">
                            {{ number_format($totalSupplier) }}</h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                        <i class="fas fa-truck text-lg"></i></div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-[#121212] rounded-2xl p-5 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden group">
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">Total Salesman
                        </p>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white">
                            {{ number_format($totalSalesman) }}</h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-tie text-lg"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- ISI TAB 2: OTORISASI DATA (PERSETUJUAN)    --}}
    {{-- ========================================== --}}
    <div x-show="activeTab === 'persetujuan'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
        <div
            class="bg-white dark:bg-[#121212] rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden relative">
            <div
                class="px-6 py-5 border-b border-slate-200 dark:border-white/5 flex justify-between items-center bg-slate-50 dark:bg-[#1a1a1a]">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center text-rose-600 dark:text-rose-400 shadow-inner">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 dark:text-white text-sm uppercase tracking-widest">Antrean
                            Penghapusan Data</h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold mt-0.5">Membutuhkan otorisasi
                            Anda untuk mengeksekusi Hard Delete.</p>
                    </div>
                </div>
            </div>

            <div class="p-0 overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead
                        class="text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-transparent">
                        <tr class="text-[10px] uppercase tracking-widest font-black">
                            <th class="px-6 py-4">Waktu Pengajuan</th>
                            <th class="px-6 py-4">Pemohon</th>
                            <th class="px-6 py-4">Modul Target</th>
                            <th class="px-6 py-4 text-center">Rentang Tanggal (Dihapus)</th>
                            <th class="px-6 py-4">Alasan Penghapusan</th>
                            <th class="px-6 py-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($pendingRequests as $req)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-[11px] text-slate-500 dark:text-slate-400 font-bold">
                                {{ $req->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-800 dark:text-slate-200">
                                {{ $req->requester->name ?? 'Admin' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-md border text-[10px] font-black uppercase tracking-widest
                                    {{ $req->tipe_modul == 'penjualan' ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 
                                      ($req->tipe_modul == 'retur' ? 'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20' : 
                                      ($req->tipe_modul == 'ar' ? 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20' : 
                                      'bg-cyan-50 text-cyan-600 border-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-400 dark:border-cyan-500/20')) }}">
                                    {{ $req->tipe_modul }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 text-center font-bold text-rose-600 dark:text-rose-400 text-xs bg-rose-50/50 dark:bg-rose-500/5">
                                {{ \Carbon\Carbon::parse($req->tanggal_mulai)->format('d/m/Y') }} <span
                                    class="text-slate-400 font-normal mx-1">s/d</span>
                                {{ \Carbon\Carbon::parse($req->tanggal_selesai)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-[11px] text-slate-600 dark:text-slate-400 max-w-[200px] truncate"
                                title="{{ $req->alasan }}">
                                "{{ $req->alasan }}"
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="rejectDeletion({{ $req->id }})"
                                        onclick="confirm('Tolak pengajuan ini?') || event.stopImmediatePropagation()"
                                        class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-white/10 dark:hover:bg-white/20 text-slate-600 dark:text-slate-300 flex items-center justify-center gap-2 text-[10px] font-bold transition-colors shadow-sm">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                    <button wire:click="approveDeletion({{ $req->id }})"
                                        onclick="confirm('YAKIN SETUJUI? Data pada rentang tanggal tersebut akan dihapus PERMANEN dan tidak dapat dikembalikan.') || event.stopImmediatePropagation()"
                                        class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center gap-2 text-[10px] font-bold transition-colors shadow-sm shadow-emerald-500/20">
                                        <i class="fas fa-check"></i> Setujui
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div
                                    class="flex flex-col items-center justify-center text-slate-400 dark:text-slate-600">
                                    <i class="fas fa-clipboard-check text-5xl mb-4 opacity-30"></i>
                                    <h4 class="font-black text-sm uppercase tracking-widest">Tidak ada antrean
                                        persetujuan</h4>
                                    <p class="text-xs mt-1 font-medium">Semua data operasional saat ini aman.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- ISI TAB 3: EVALUASI KINERJA (MONITORING)   --}}
    {{-- ========================================== --}}
    <div x-show="activeTab === 'monitoring'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div
                class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                <div
                    class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/20">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400">
                            <i class="fas fa-undo-alt"></i></div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-widest">Top
                                Retur</h3>
                            <p class="text-[10px] text-slate-500 font-bold mt-0.5">Kuantitas tertinggi bulan ini</p>
                        </div>
                    </div>
                </div>
                <div class="p-0 flex-1 overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead
                            class="text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-transparent">
                            <tr>
                                <th class="px-5 py-3 font-black text-[10px] uppercase tracking-widest">Item</th>
                                <th class="px-5 py-3 font-black text-[10px] uppercase tracking-widest text-right">Qty
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($topRetur as $index => $rt)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-4 flex items-center gap-2">
                                    <div class="text-[10px] font-bold text-slate-400 w-3">{{ $index + 1 }}.</div>
                                    <div class="font-bold text-xs text-slate-700 dark:text-slate-200 truncate max-w-[120px]"
                                        title="{{ $rt->nama_item }}">{{ $rt->nama_item }}</div>
                                </td>
                                <td class="px-5 py-4 text-right"><span
                                        class="inline-flex items-center justify-center bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 px-2 py-0.5 rounded-md text-[10px] font-black border border-rose-200 dark:border-rose-800/50">{{ number_format($rt->total_qty) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-5 py-10 text-center text-slate-500">
                                    <p class="text-xs font-bold uppercase tracking-widest">Belum ada retur.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                <div
                    class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/20">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <i class="fas fa-exclamation-triangle"></i></div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-widest">
                                Piutang Kritis</h3>
                            <p class="text-[10px] text-slate-500 font-bold mt-0.5">Hutang toko (> 30 Hari)</p>
                        </div>
                    </div>
                </div>
                <div class="p-0 flex-1 overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead
                            class="text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-transparent">
                            <tr>
                                <th class="px-5 py-3 font-black text-[10px] uppercase tracking-widest">Outlet</th>
                                <th class="px-5 py-3 font-black text-[10px] uppercase tracking-widest text-right">
                                    Tagihan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($piutangKritis as $ar)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-xs text-slate-700 dark:text-slate-200 truncate max-w-[120px]"
                                        title="{{ $ar->pelanggan_name }}">{{ $ar->pelanggan_name }}</div>
                                    <div
                                        class="text-[9px] mt-1 font-black uppercase tracking-widest text-rose-600 bg-rose-50 dark:bg-rose-900/30 w-fit px-1.5 rounded">
                                        {{ $ar->umur_piutang }} Hari</div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="font-black text-slate-800 dark:text-white text-xs">Rp
                                        {{ number_format((float) $ar->nilai, 0, ',', '.') }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-5 py-10 text-center text-slate-500">
                                    <p class="text-xs font-bold uppercase tracking-widest">Aman. Tidak ada piutang
                                        macet.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                <div
                    class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/20">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-level-down-alt"></i></div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-widest">Perlu
                                Coaching</h3>
                            <p class="text-[10px] text-slate-500 font-bold mt-0.5">Bottom 3 Kinerja (Bulan Ini)</p>
                        </div>
                    </div>
                </div>
                <div class="p-0 flex-1 overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead
                            class="text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-transparent">
                            <tr>
                                <th class="px-5 py-3 font-black text-[10px] uppercase tracking-widest">Nama Sales</th>
                                <th class="px-5 py-3 font-black text-[10px] uppercase tracking-widest text-right">Omzet
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($bottomSales as $bs)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-4">
                                    <div
                                        class="font-bold text-xs text-slate-700 dark:text-slate-200 truncate max-w-[120px]">
                                        {{ $bs->sales_name }}</div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="font-black text-slate-800 dark:text-white text-xs">Rp
                                        {{ number_format((float) $bs->total_omzet, 0, ',', '.') }}</div>
                                    <div
                                        class="text-[9px] mt-1 font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">
                                        <i class="fas fa-arrow-down"></i> Rendah</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-5 py-10 text-center text-slate-500">
                                    <p class="text-xs font-bold uppercase tracking-widest">Belum ada data penjualan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>