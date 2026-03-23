<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta dark:bg-[#050505] bg-slate-50"
    x-data="{ activeTab: 'overview' }">

    {{-- CSS Animasi & Scrollbar --}}
    <style>
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
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
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.4);
        border-radius: 10px;
    }
    </style>

    {{-- STICKY HEADER (Serasi dengan Master & User) --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/10 bg-white/95 border-slate-300 shadow-md flex flex-col gap-4">

        {{-- Baris Atas: Judul & Info --}}
        <div class="flex flex-col xl:flex-row gap-6 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto shrink-0">
                <div
                    class="p-3 rounded-2xl shadow-xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white ring-4 ring-blue-500/20">
                    <i class="fas fa-server text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                        Status <span class="text-blue-600 dark:text-blue-400">Integritas</span> Sistem
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                        <p
                            class="text-[10px] font-extrabold uppercase tracking-[0.2em] dark:text-slate-400 text-slate-600">
                            Pusat Kendali Pengawasan
                        </p>
                        <span
                            class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-white/5 text-[9px] font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1 border border-slate-200 dark:border-white/5">
                            <i class="fas fa-clock text-blue-500"></i> Sync:
                            {{ $lastSync ? \Carbon\Carbon::parse($lastSync)->translatedFormat('d M H:i') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-2 w-full xl:w-auto justify-end">
                <a href="{{ route('master.produk') }}"
                    class="px-5 py-2.5 bg-gradient-to-r from-slate-800 to-slate-900 dark:from-white dark:to-slate-200 dark:text-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-database"></i>
                    <span>Kelola Master Data</span>
                </a>
            </div>
        </div>

        {{-- Baris Bawah: Navigasi Tab Menyatu dengan Header --}}
        <div
            class="flex gap-4 sm:gap-8 overflow-x-auto custom-scrollbar pt-2 border-t dark:border-white/5 border-slate-100">
            <button @click="activeTab = 'overview'"
                :class="activeTab === 'overview' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-500' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600'"
                class="py-3 text-[11px] font-black uppercase tracking-widest border-b-[3px] transition-all flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-chart-pie text-sm"></i> Ringkasan Master
            </button>

            <button @click="activeTab = 'persetujuan'"
                :class="activeTab === 'persetujuan' ? 'border-rose-600 text-rose-600 dark:text-rose-400 dark:border-rose-500' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:border-rose-300 dark:hover:border-rose-600'"
                class="py-3 text-[11px] font-black uppercase tracking-widest border-b-[3px] transition-all flex items-center gap-2 whitespace-nowrap relative">
                <i class="fas fa-clipboard-check text-sm"></i> Otorisasi Data
                @if($pendingRequests->count() > 0)
                <span class="ml-1 bg-rose-500 text-white px-1.5 py-0.5 rounded-md text-[9px] shadow-sm animate-pulse">
                    {{ $pendingRequests->count() }} Perlu Aksi
                </span>
                @endif
            </button>

            <button @click="activeTab = 'monitoring'"
                :class="activeTab === 'monitoring' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-500' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-300 dark:hover:border-indigo-600'"
                class="py-3 text-[11px] font-black uppercase tracking-widest border-b-[3px] transition-all flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-search-dollar text-sm"></i> Evaluasi Kinerja
            </button>
        </div>
    </div>

    {{-- AREA KONTEN UTAMA --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ALERT NOTIFIKASI SESSION --}}
        @if (session()->has('message'))
        <div
            class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm animate-fade-in mb-4">
            <div class="flex items-center gap-2"><i class="fas fa-check-circle text-lg"></i> {{ session('message') }}
            </div>
            <button type="button" class="opacity-50 hover:opacity-100 transition-opacity"
                onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
        @endif
        @if (session()->has('error'))
        <div
            class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm animate-fade-in mb-4">
            <div class="flex items-center gap-2"><i class="fas fa-info-circle text-lg"></i> {{ session('error') }}</div>
            <button type="button" class="opacity-50 hover:opacity-100 transition-opacity"
                onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
        @endif

        {{-- ========================================== --}}
        {{-- ISI TAB 1: RINGKASAN MASTER (OVERVIEW)     --}}
        {{-- ========================================== --}}
        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-cloak class="space-y-6">

            {{-- SMART ALERTS: RADAR KEAMANAN DATA --}}
            @if(count($alerts) > 0)
            <div class="space-y-3" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
                <div class="flex items-center justify-between px-2 mb-2 mt-2">
                    <h3
                        class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400 flex items-center gap-2">
                        <i class="fas fa-satellite-dish text-rose-500 animate-pulse"></i> Radar Keamanan Data Master
                    </h3>
                    <button @click="show = false"
                        class="text-[10px] font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">
                        Tutup Semat
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($alerts as $alert)
                    @php
                    $bgClass = $alert['type'] == 'danger' ? 'bg-rose-50 dark:bg-rose-500/10 border-rose-200
                    dark:border-rose-500/30' :
                    ($alert['type'] == 'warning' ? 'bg-orange-50 dark:bg-orange-500/10 border-orange-200
                    dark:border-orange-500/30' :
                    'bg-blue-50 dark:bg-blue-500/10 border-blue-200 dark:border-blue-500/30');

                    $textClass = $alert['type'] == 'danger' ? 'text-rose-700 dark:text-rose-400' :
                    ($alert['type'] == 'warning' ? 'text-orange-700 dark:text-orange-400' : 'text-blue-700
                    dark:text-blue-400');

                    $iconClass = $alert['type'] == 'danger' ? 'bg-rose-100 text-rose-600 dark:bg-rose-900/40
                    dark:text-rose-400' :
                    ($alert['type'] == 'warning' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/40
                    dark:text-orange-400' :
                    'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400');
                    @endphp

                    <div
                        class="flex flex-col sm:flex-row justify-between p-4 rounded-2xl border {{ $bgClass }} shadow-sm gap-4 transition-transform hover:-translate-y-0.5">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $iconClass }} shadow-inner">
                                <i
                                    class="{{ $alert['icon'] }} text-lg {{ $alert['type'] == 'danger' ? 'animate-pulse' : '' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-[11px] uppercase tracking-widest mb-1 {{ $textClass }}">
                                    {{ $alert['title'] }}</h4>
                                <p class="text-[11px] text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                                    {!! $alert['message'] !!}</p>
                            </div>
                        </div>

                        @if(isset($alert['link']))
                        <a href="{{ $alert['link'] }}"
                            class="shrink-0 px-4 py-2 bg-white dark:bg-[#18181b] border border-slate-200 dark:border-white/10 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $textClass }} hover:bg-slate-50 dark:hover:bg-white/5 transition-colors shadow-sm self-start sm:self-center text-center mt-2 sm:mt-0">
                            Tinjau <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        @elseif(isset($alert['action_tab']))
                        <button @click="activeTab = '{{ $alert['action_tab'] }}'"
                            class="shrink-0 px-4 py-2 bg-white dark:bg-[#18181b] border border-slate-200 dark:border-white/10 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $textClass }} hover:bg-slate-50 dark:hover:bg-white/5 transition-colors shadow-sm self-start sm:self-center text-center mt-2 sm:mt-0">
                            Otorisasi <i class="fas fa-unlock-alt ml-1"></i>
                        </button>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 4 KARTU OVERVIEW --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
                {{-- Kartu Anomali --}}
                <div
                    class="dark:bg-[#121212] bg-white rounded-[2rem] p-6 border-2 {{ $anomaliProdukCount > 0 ? 'border-rose-400 dark:border-rose-600 shadow-rose-500/10' : 'border-slate-200 dark:border-white/5' }} shadow-sm relative overflow-hidden group">
                    <div
                        class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-rose-50 dark:from-rose-900/10 to-transparent pointer-events-none">
                    </div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">Anomali
                                Master</p>
                            <h3 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter">
                                {{ number_format($anomaliProdukCount) }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl {{ $anomaliProdukCount > 0 ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/30' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400' }} flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i
                                class="fas fa-exclamation-triangle text-xl {{ $anomaliProdukCount > 0 ? 'animate-pulse' : '' }}"></i>
                        </div>
                    </div>
                    <div class="mt-6 relative z-10">
                        @if($anomaliProdukCount > 0)
                        <div
                            class="flex items-center gap-1.5 text-[9px] font-bold text-rose-700 dark:text-rose-300 px-3 py-2 bg-rose-100 dark:bg-rose-900/40 rounded-xl border border-rose-200 dark:border-rose-700/50 leading-tight">
                            <i class="fas fa-info-circle"></i> SKU Penjualan tak terdaftar!
                        </div>
                        @else
                        <div
                            class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 px-3 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                            <i class="fas fa-check-double text-emerald-500"></i> Semua SKU sinkron.
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Kartu Produk --}}
                <div
                    class="dark:bg-[#121212] bg-white rounded-[2rem] p-6 border-2 border-slate-200 dark:border-white/5 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">Total Produk
                            </p>
                            <h3 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter">
                                {{ number_format($totalProduk) }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center text-cyan-600 dark:text-cyan-400 group-hover:scale-110 transition-transform">
                            <i class="fas fa-boxes text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-6 relative z-10">
                        @if($produkKosong > 0)
                        <div
                            class="flex items-center gap-1.5 text-[10px] font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 px-3 py-2 rounded-xl border border-rose-100 dark:border-rose-800/30">
                            <i class="fas fa-box-open"></i> {{ $produkKosong }} Stok Kosong
                        </div>
                        @else
                        <div
                            class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 px-3 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                            <i class="fas fa-check-circle text-emerald-500"></i> Stok master aman
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Kartu Supplier --}}
                <div
                    class="dark:bg-[#121212] bg-white rounded-[2rem] p-6 border-2 border-slate-200 dark:border-white/5 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">Total
                                Supplier</p>
                            <h3 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter">
                                {{ number_format($totalSupplier) }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                            <i class="fas fa-truck text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Kartu Salesman --}}
                <div
                    class="dark:bg-[#121212] bg-white rounded-[2rem] p-6 border-2 border-slate-200 dark:border-white/5 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">Total
                                Salesman</p>
                            <h3 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter">
                                {{ number_format($totalSalesman) }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-tie text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- ISI TAB 2: OTORISASI DATA (PERSETUJUAN)    --}}
        {{-- ========================================== --}}
        <div x-show="activeTab === 'persetujuan'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-cloak>

            <div
                class="dark:bg-[#0f0f0f] bg-white rounded-[2.5rem] border-2 border-slate-300 dark:border-white/10 shadow-2xl overflow-hidden relative">
                <div
                    class="px-8 py-6 border-b-2 border-slate-200 dark:border-white/5 flex justify-between items-center bg-slate-100 dark:bg-white/5">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center text-rose-600 dark:text-rose-400 shadow-inner text-xl border border-rose-200 dark:border-rose-800/30">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 dark:text-white text-base uppercase tracking-widest">
                                Antrean Penghapusan Data</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold mt-1">Membutuhkan otorisasi
                                Anda untuk mengeksekusi Hard Delete.</p>
                        </div>
                    </div>
                </div>

                <div class="p-0 overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead
                            class="text-slate-500 dark:text-slate-400 border-b-2 border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-transparent">
                            <tr class="text-[10px] uppercase tracking-widest font-black">
                                <th class="px-8 py-5">Waktu Pengajuan</th>
                                <th class="px-8 py-5">Pemohon</th>
                                <th class="px-8 py-5 text-center">Modul Target</th>
                                <th class="px-8 py-5 text-center">Rentang Tanggal (Dihapus)</th>
                                <th class="px-8 py-5">Alasan Penghapusan</th>
                                <th class="px-8 py-5 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-100 dark:divide-white/5">
                            @forelse($pendingRequests as $req)
                            <tr class="hover:bg-rose-500/[0.04] transition-colors">
                                <td class="px-8 py-5 text-[11px] text-slate-500 dark:text-slate-400 font-bold">
                                    {{ $req->created_at->diffForHumans() }}
                                </td>
                                <td class="px-8 py-5 text-xs font-black text-slate-800 dark:text-slate-200">
                                    {{ $req->requester->name ?? 'Admin' }}
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span
                                        class="px-3 py-1.5 rounded-lg border-2 text-[10px] font-black uppercase tracking-widest
                                        {{ $req->tipe_modul == 'penjualan' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 
                                          ($req->tipe_modul == 'retur' ? 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20' : 
                                          ($req->tipe_modul == 'ar' ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20' : 
                                          'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-400 dark:border-cyan-500/20')) }}">
                                        {{ $req->tipe_modul }}
                                    </span>
                                </td>
                                <td
                                    class="px-8 py-5 text-center font-bold text-rose-600 dark:text-rose-400 text-xs bg-rose-50/50 dark:bg-rose-500/5">
                                    {{ \Carbon\Carbon::parse($req->tanggal_mulai)->format('d/m/Y') }}
                                    <span class="text-slate-400 font-normal mx-2">s/d</span>
                                    {{ \Carbon\Carbon::parse($req->tanggal_selesai)->format('d/m/Y') }}
                                </td>
                                <td class="px-8 py-5 text-xs text-slate-600 dark:text-slate-400 max-w-[250px] truncate"
                                    title="{{ $req->alasan }}">
                                    "{{ $req->alasan }}"
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="rejectDeletion({{ $req->id }})"
                                            onclick="confirm('Tolak pengajuan ini?') || event.stopImmediatePropagation()"
                                            class="w-10 h-10 rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-white/5 dark:hover:bg-white/10 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors shadow-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button wire:click="approveDeletion({{ $req->id }})"
                                            onclick="confirm('YAKIN SETUJUI? Data pada rentang tanggal tersebut akan dihapus PERMANEN dan tidak dapat dikembalikan.') || event.stopImmediatePropagation()"
                                            class="px-4 py-2 h-10 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest transition-colors shadow-lg shadow-emerald-500/20">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <div
                                        class="flex flex-col items-center justify-center text-slate-400 dark:text-slate-600">
                                        <i class="fas fa-shield-check text-6xl mb-5 opacity-30"></i>
                                        <h4 class="font-black text-base uppercase tracking-widest text-slate-500">Sistem
                                            Aman Terkendali</h4>
                                        <p class="text-xs mt-2 font-medium">Tidak ada antrean persetujuan penghapusan
                                            data saat ini.</p>
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
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Tabel Retur --}}
                <div
                    class="dark:bg-[#0f0f0f] bg-white border-2 border-slate-300 dark:border-white/10 rounded-[2rem] shadow-xl overflow-hidden flex flex-col">
                    <div
                        class="px-6 py-5 border-b-2 border-slate-200 dark:border-white/5 flex justify-between items-center bg-slate-100 dark:bg-white/5">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/30">
                                <i class="fas fa-undo-alt"></i>
                            </div>
                            <div>
                                <h3
                                    class="font-black text-slate-800 dark:text-white text-[11px] uppercase tracking-widest">
                                    Top Retur</h3>
                                <p class="text-[10px] text-slate-500 font-bold mt-0.5">Kuantitas tertinggi bulan ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-0 flex-1 overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead
                                class="text-slate-500 dark:text-slate-400 border-b-2 border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-transparent">
                                <tr>
                                    <th class="px-5 py-4 font-black text-[10px] uppercase tracking-widest">Item</th>
                                    <th class="px-5 py-4 font-black text-[10px] uppercase tracking-widest text-right">
                                        Qty</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-slate-100 dark:divide-white/5">
                                @forelse($topRetur as $index => $rt)
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-5 py-4 flex items-center gap-2">
                                        <div class="text-[10px] font-black text-slate-400 w-3">{{ $index + 1 }}.</div>
                                        <div class="font-bold text-xs text-slate-700 dark:text-slate-200 truncate max-w-[120px]"
                                            title="{{ $rt->nama_item }}">{{ $rt->nama_item }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <span
                                            class="inline-flex items-center justify-center bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 px-2 py-1 rounded-md text-[10px] font-black border border-rose-200 dark:border-rose-800/50">
                                            {{ number_format($rt->total_qty) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-5 py-10 text-center text-slate-500">
                                        <p class="text-[10px] font-black uppercase tracking-widest">Belum ada retur.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tabel Piutang --}}
                <div
                    class="dark:bg-[#0f0f0f] bg-white border-2 border-slate-300 dark:border-white/10 rounded-[2rem] shadow-xl overflow-hidden flex flex-col">
                    <div
                        class="px-6 py-5 border-b-2 border-slate-200 dark:border-white/5 flex justify-between items-center bg-slate-100 dark:bg-white/5">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/30">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h3
                                    class="font-black text-slate-800 dark:text-white text-[11px] uppercase tracking-widest">
                                    Piutang Kritis</h3>
                                <p class="text-[10px] text-slate-500 font-bold mt-0.5">Hutang toko (> 30 Hari)</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-0 flex-1 overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead
                                class="text-slate-500 dark:text-slate-400 border-b-2 border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-transparent">
                                <tr>
                                    <th class="px-5 py-4 font-black text-[10px] uppercase tracking-widest">Outlet</th>
                                    <th class="px-5 py-4 font-black text-[10px] uppercase tracking-widest text-right">
                                        Tagihan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-slate-100 dark:divide-white/5">
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
                                        <p class="text-[10px] font-black uppercase tracking-widest">Aman. Tidak ada
                                            piutang macet.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tabel Sales --}}
                <div
                    class="dark:bg-[#0f0f0f] bg-white border-2 border-slate-300 dark:border-white/10 rounded-[2rem] shadow-xl overflow-hidden flex flex-col">
                    <div
                        class="px-6 py-5 border-b-2 border-slate-200 dark:border-white/5 flex justify-between items-center bg-slate-100 dark:bg-white/5">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800/30">
                                <i class="fas fa-level-down-alt"></i>
                            </div>
                            <div>
                                <h3
                                    class="font-black text-slate-800 dark:text-white text-[11px] uppercase tracking-widest">
                                    Perlu Coaching</h3>
                                <p class="text-[10px] text-slate-500 font-bold mt-0.5">Bottom 3 Kinerja (Bulan Ini)</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-0 flex-1 overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead
                                class="text-slate-500 dark:text-slate-400 border-b-2 border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-transparent">
                                <tr>
                                    <th class="px-5 py-4 font-black text-[10px] uppercase tracking-widest">Nama Sales
                                    </th>
                                    <th class="px-5 py-4 font-black text-[10px] uppercase tracking-widest text-right">
                                        Omzet</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-slate-100 dark:divide-white/5">
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
                                        <p class="text-[10px] font-black uppercase tracking-widest">Belum ada data
                                            penjualan.</p>
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
</div>