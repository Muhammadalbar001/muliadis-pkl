<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta dark:bg-[#050505] bg-slate-50">

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

    {{-- STICKY HEADER --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/10 bg-white/95 border-slate-300 shadow-md flex flex-col gap-4">

        {{-- Baris Atas: Judul & Info --}}
        <div class="flex flex-col xl:flex-row gap-6 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto shrink-0">
                <div
                    class="p-3 rounded-2xl shadow-xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white ring-4 ring-blue-500/20">
                    <i class="fas fa-keyboard text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                        Pusat Operasional <span class="text-blue-600 dark:text-blue-400">Data</span>
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                        <p
                            class="text-[10px] font-extrabold uppercase tracking-[0.2em] dark:text-slate-400 text-slate-600">
                            Admin Workspace
                        </p>
                        <span
                            class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-white/5 text-[9px] font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1 border border-slate-200 dark:border-white/5">
                            <i class="far fa-calendar-check text-blue-500"></i> Hari ini: {{ $hariIni }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi Kanan --}}
            <div class="flex items-center gap-2 w-full xl:w-auto justify-end">
                <button wire:click="openModal"
                    class="px-5 py-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-500/10 dark:hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i>
                    <span>Ajukan Hapus Data</span>
                </button>
            </div>
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

        {{-- 1. SMART ALERTS: PENGINGAT TUGAS --}}
        @if(isset($alerts) && count($alerts) > 0)
        <div class="space-y-3 mb-8" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
            <div class="flex items-center justify-between px-2 mb-2">
                <h3
                    class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    <i class="fas fa-bell text-blue-500 animate-pulse"></i> Pengingat Tugas Harian
                </h3>
                <button @click="show = false"
                    class="text-[10px] font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">
                    Tutup Semua
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($alerts as $alert)
                @php
                $bgClass = $alert['type'] == 'danger' ? 'bg-rose-50 dark:bg-rose-500/10 border-rose-200
                dark:border-rose-500/30' :
                ($alert['type'] == 'warning' ? 'bg-orange-50 dark:bg-orange-500/10 border-orange-200
                dark:border-orange-500/30' :
                ($alert['type'] == 'success' ? 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200
                dark:border-emerald-500/30' :
                'bg-blue-50 dark:bg-blue-500/10 border-blue-200 dark:border-blue-500/30'));

                $textClass = $alert['type'] == 'danger' ? 'text-rose-700 dark:text-rose-400' :
                ($alert['type'] == 'warning' ? 'text-orange-700 dark:text-orange-400' :
                ($alert['type'] == 'success' ? 'text-emerald-700 dark:text-emerald-400' : 'text-blue-700
                dark:text-blue-400'));

                $iconClass = $alert['type'] == 'danger' ? 'bg-rose-100 text-rose-600 dark:bg-rose-900/40
                dark:text-rose-400' :
                ($alert['type'] == 'warning' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/40
                dark:text-orange-400' :
                ($alert['type'] == 'success' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40
                dark:text-emerald-400' :
                'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400'));
                @endphp

                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-2xl border {{ $bgClass }} shadow-sm gap-4 transition-transform hover:-translate-y-0.5">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $iconClass }} shadow-inner">
                            <i
                                class="{{ $alert['icon'] }} text-lg {{ $alert['type'] == 'danger' || $alert['type'] == 'warning' ? 'animate-pulse' : '' }}"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-[11px] uppercase tracking-widest mb-1 {{ $textClass }}">
                                {{ $alert['title'] }}</h4>
                            <p class="text-[11px] font-medium text-slate-600 dark:text-slate-300 leading-relaxed">{!!
                                $alert['message'] !!}</p>
                        </div>
                    </div>
                    @if(isset($alert['link']))
                    <a href="{{ $alert['link'] }}"
                        class="shrink-0 px-4 py-2.5 bg-white dark:bg-[#18181b] border border-slate-200 dark:border-white/10 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $textClass }} hover:bg-slate-50 dark:hover:bg-white/5 transition-colors shadow-sm self-start sm:self-center text-center mt-2 sm:mt-0">
                        Impor Data <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- 2. GRID KARTU MODUL IMPOR (MENU UTAMA ADMIN) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            {{-- Modul Penjualan --}}
            <a href="{{ route('transaksi.penjualan') }}"
                class="bg-white dark:bg-[#121212] rounded-[2rem] p-6 border-2 border-slate-200 dark:border-white/10 hover:border-blue-300 dark:hover:border-blue-500/30 shadow-sm hover:shadow-xl transition-all group flex flex-col justify-between min-h-[160px] relative overflow-hidden">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity"><i
                        class="fas fa-shopping-cart text-[5rem] text-blue-600"></i></div>
                <div
                    class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl mb-4 border border-blue-100 dark:border-blue-800/30 group-hover:scale-110 transition-transform">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="relative z-10">
                    <h3 class="font-black text-sm text-slate-800 dark:text-white uppercase tracking-wide">Data Penjualan
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 font-medium">Impor data transaksi
                        harian.</p>
                </div>
                <div
                    class="mt-5 flex items-center text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest group-hover:gap-2 transition-all">
                    Buka Modul <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </a>

            {{-- Modul Retur --}}
            <a href="{{ route('transaksi.retur') }}"
                class="bg-white dark:bg-[#121212] rounded-[2rem] p-6 border-2 border-slate-200 dark:border-white/10 hover:border-rose-300 dark:hover:border-rose-500/30 shadow-sm hover:shadow-xl transition-all group flex flex-col justify-between min-h-[160px] relative overflow-hidden">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity"><i
                        class="fas fa-undo-alt text-[5rem] text-rose-600"></i></div>
                <div
                    class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl mb-4 border border-rose-100 dark:border-rose-800/30 group-hover:scale-110 transition-transform">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <div class="relative z-10">
                    <h3 class="font-black text-sm text-slate-800 dark:text-white uppercase tracking-wide">Data Retur
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 font-medium">Impor pengembalian
                        barang.</p>
                </div>
                <div
                    class="mt-5 flex items-center text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-widest group-hover:gap-2 transition-all">
                    Buka Modul <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </a>

            {{-- Modul Piutang (AR) --}}
            <a href="{{ route('transaksi.ar') }}"
                class="bg-white dark:bg-[#121212] rounded-[2rem] p-6 border-2 border-slate-200 dark:border-white/10 hover:border-amber-300 dark:hover:border-amber-500/30 shadow-sm hover:shadow-xl transition-all group flex flex-col justify-between min-h-[160px] relative overflow-hidden">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity"><i
                        class="fas fa-file-invoice-dollar text-[5rem] text-amber-600"></i></div>
                <div
                    class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl mb-4 border border-amber-100 dark:border-amber-800/30 group-hover:scale-110 transition-transform">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="relative z-10">
                    <h3 class="font-black text-sm text-slate-800 dark:text-white uppercase tracking-wide">Data Piutang
                        (AR)</h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 font-medium">Impor data hutang
                        pelanggan.</p>
                </div>
                <div
                    class="mt-5 flex items-center text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest group-hover:gap-2 transition-all">
                    Buka Modul <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </a>

            {{-- Modul Pelunasan (Collection) --}}
            <a href="{{ route('transaksi.collection') }}"
                class="bg-white dark:bg-[#121212] rounded-[2rem] p-6 border-2 border-slate-200 dark:border-white/10 hover:border-emerald-300 dark:hover:border-emerald-500/30 shadow-sm hover:shadow-xl transition-all group flex flex-col justify-between min-h-[160px] relative overflow-hidden">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity"><i
                        class="fas fa-money-bill-wave text-[5rem] text-emerald-600"></i></div>
                <div
                    class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl mb-4 border border-emerald-100 dark:border-emerald-800/30 group-hover:scale-110 transition-transform">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="relative z-10">
                    <h3 class="font-black text-sm text-slate-800 dark:text-white uppercase tracking-wide">Data Pelunasan
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 font-medium">Impor pembayaran/koleksi.
                    </p>
                </div>
                <div
                    class="mt-5 flex items-center text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest group-hover:gap-2 transition-all">
                    Buka Modul <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </a>
        </div>

        {{-- 3. SPLIT PANEL: RECENT ACTIVITY & RIWAYAT PENGAJUAN --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Log Import Terakhir --}}
            <div
                class="xl:col-span-2 dark:bg-[#0f0f0f] bg-white rounded-[2.5rem] border-2 border-slate-300 dark:border-white/10 shadow-2xl overflow-hidden flex flex-col">
                <div
                    class="px-8 py-6 border-b-2 border-slate-200 dark:border-white/5 bg-slate-100 dark:bg-white/5 flex justify-between items-center">
                    <h3
                        class="font-black uppercase tracking-widest text-slate-800 dark:text-white flex items-center gap-3 text-xs">
                        <i class="fas fa-history text-blue-500 text-lg"></i> Log Import Terakhir
                    </h3>
                </div>
                <div class="p-0 overflow-x-auto flex-1 custom-scrollbar">
                    <table class="w-full text-left text-sm whitespace-nowrap border-collapse">
                        <thead
                            class="text-slate-500 dark:text-slate-400 border-b-2 border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-transparent">
                            <tr>
                                <th class="px-8 py-5 font-black text-[10px] uppercase tracking-widest">Modul</th>
                                <th class="px-8 py-5 font-black text-[10px] uppercase tracking-widest">No. Dokumen</th>
                                <th class="px-8 py-5 font-black text-[10px] uppercase tracking-widest">Cabang</th>
                                <th class="px-8 py-5 font-black text-[10px] uppercase tracking-widest text-right">Waktu
                                    Import</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-100 dark:divide-white/5">
                            @foreach($recentPenjualan as $jual)
                            <tr class="hover:bg-blue-500/[0.04] transition-colors">
                                <td class="px-8 py-5"><span
                                        class="inline-flex items-center border border-blue-200 dark:border-blue-800/30 gap-1 text-[9px] font-black uppercase tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md dark:bg-blue-900/30 dark:text-blue-400">Penjualan</span>
                                </td>
                                <td class="px-8 py-5 font-bold text-xs text-slate-700 dark:text-slate-200">
                                    {{ $jual->no_penjualan }}</td>
                                <td class="px-8 py-5 text-xs font-bold text-slate-500">{{ $jual->cabang }}</td>
                                <td class="px-8 py-5 text-right text-[11px] font-medium text-slate-400">
                                    {{ $jual->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                            @foreach($recentRetur as $ret)
                            <tr class="hover:bg-rose-500/[0.04] transition-colors">
                                <td class="px-8 py-5"><span
                                        class="inline-flex items-center border border-rose-200 dark:border-rose-800/30 gap-1 text-[9px] font-black uppercase tracking-widest text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md dark:bg-rose-900/30 dark:text-rose-400">Retur</span>
                                </td>
                                <td class="px-8 py-5 font-bold text-xs text-slate-700 dark:text-slate-200">
                                    {{ $ret->no_retur }}</td>
                                <td class="px-8 py-5 text-xs font-bold text-slate-500">{{ $ret->cabang }}</td>
                                <td class="px-8 py-5 text-right text-[11px] font-medium text-slate-400">
                                    {{ $ret->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                            @if($recentPenjualan->isEmpty() && $recentRetur->isEmpty())
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center text-slate-500">
                                    <i class="fas fa-folder-open text-5xl mb-4 opacity-20"></i>
                                    <p class="text-[10px] font-black uppercase tracking-widest">Belum ada data
                                        operasional.</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Status Pengajuan Anda --}}
            <div
                class="xl:col-span-1 dark:bg-[#0f0f0f] bg-white rounded-[2.5rem] border-2 border-slate-300 dark:border-white/10 shadow-2xl overflow-hidden flex flex-col">
                <div
                    class="px-6 py-6 border-b-2 border-slate-200 dark:border-white/5 bg-slate-100 dark:bg-white/5 flex justify-between items-center">
                    <h3
                        class="font-black uppercase tracking-widest text-slate-800 dark:text-white flex items-center gap-3 text-xs">
                        <i class="fas fa-file-signature text-rose-500 text-lg"></i> Status Pengajuan
                    </h3>
                </div>
                <div class="p-0 overflow-y-auto flex-1 custom-scrollbar">
                    <ul class="divide-y-2 divide-slate-100 dark:divide-white/5">
                        @forelse($riwayatPengajuan as $pengajuan)
                        <li class="p-6 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <div class="flex justify-between items-start mb-3">
                                <span
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-slate-300">
                                    {{ strtoupper($pengajuan->tipe_modul) }}
                                </span>
                                @if($pengajuan->status == 'pending')
                                <span
                                    class="bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 text-[9px] font-black px-2 py-1 rounded-md border border-amber-200 dark:border-amber-500/30 animate-pulse tracking-widest uppercase">Menunggu</span>
                                @elseif($pengajuan->status == 'approved')
                                <span
                                    class="bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 text-[9px] font-black px-2 py-1 rounded-md border border-emerald-200 dark:border-emerald-500/30 tracking-widest uppercase">Disetujui</span>
                                @else
                                <span
                                    class="bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400 text-[9px] font-black px-2 py-1 rounded-md border border-rose-200 dark:border-rose-500/30 tracking-widest uppercase">Ditolak</span>
                                @endif
                            </div>
                            <p class="text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-2">
                                <i class="far fa-calendar-alt w-4 text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d/m/Y') }}
                            </p>
                            <p class="text-[10px] font-medium text-slate-500 italic truncate"
                                title="{{ $pengajuan->alasan }}">
                                "{{ $pengajuan->alasan }}"
                            </p>
                        </li>
                        @empty
                        <div class="p-12 text-center text-slate-500 flex flex-col items-center">
                            <i class="fas fa-check-circle text-5xl mb-4 text-emerald-500 opacity-30"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest">Belum ada pengajuan hapus.</p>
                        </div>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>

        {{-- MODAL POP-UP PENGAJUAN HAPUS --}}
        @if($showModal)
        <div
            class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/80 dark:bg-black/80 backdrop-blur-sm p-4">
            <div
                class="relative w-full max-w-md bg-white dark:bg-[#18181b] rounded-3xl shadow-2xl ring-2 ring-slate-200 dark:ring-white/10 overflow-hidden animate-fade-in transform scale-100">
                <div
                    class="bg-rose-50 dark:bg-rose-900/20 px-6 py-5 border-b-2 border-rose-100 dark:border-rose-800/30 flex items-center justify-between">
                    <h3
                        class="text-sm font-black uppercase tracking-widest text-rose-700 dark:text-rose-400 flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-lg"></i> Form Pengajuan Hapus
                    </h3>
                    <button wire:click="$set('showModal', false)"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors bg-white dark:bg-white/5 rounded-full w-8 h-8 flex items-center justify-center shadow-sm">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="submitDeletionRequest" class="p-6 space-y-6">
                    <div>
                        <label
                            class="block text-[10px] uppercase tracking-widest font-black text-slate-500 dark:text-slate-400 mb-2">Pilih
                            Modul Data <span class="text-rose-500">*</span></label>
                        <select wire:model="tipe_modul"
                            class="w-full rounded-xl border-2 border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-[#121212] text-slate-800 dark:text-white text-xs font-bold focus:ring-rose-500 focus:border-rose-500 cursor-pointer py-3.5">
                            <option value="">-- Klik untuk Pilih Modul --</option>
                            <option value="penjualan">Tabel Penjualan</option>
                            <option value="retur">Tabel Retur</option>
                            <option value="ar">Tabel Piutang (AR)</option>
                            <option value="collection">Tabel Pelunasan (Collection)</option>
                        </select>
                        @error('tipe_modul') <span
                            class="text-[10px] text-rose-600 dark:text-rose-400 font-bold block mt-1.5"><i
                                class="fas fa-info-circle"></i> {{ $message }}</span> @enderror
                    </div>

                    <div
                        class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-white/5 p-5 rounded-2xl border border-slate-100 dark:border-transparent">
                        <div>
                            <label
                                class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Dari
                                Tanggal <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model="tanggal_mulai"
                                class="w-full rounded-lg border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-[#121212] text-slate-800 dark:text-white text-xs font-bold focus:ring-rose-500 focus:border-rose-500 cursor-pointer py-2">
                            @error('tanggal_mulai') <span
                                class="text-[10px] text-rose-600 dark:text-rose-400 font-bold block mt-1.5">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Sampai
                                Tanggal <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model="tanggal_selesai"
                                class="w-full rounded-lg border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-[#121212] text-slate-800 dark:text-white text-xs font-bold focus:ring-rose-500 focus:border-rose-500 cursor-pointer py-2">
                            @error('tanggal_selesai') <span
                                class="text-[10px] text-rose-600 dark:text-rose-400 font-bold block mt-1.5">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] uppercase tracking-widest font-black text-slate-500 dark:text-slate-400 mb-2">Alasan
                            Penghapusan <span class="text-rose-500">*</span></label>
                        <textarea wire:model="alasan" rows="3"
                            placeholder="Contoh: Terjadi kesalahan format file Excel pada baris tanggal..."
                            class="w-full rounded-xl border-2 border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-[#121212] text-slate-800 dark:text-white text-xs font-bold focus:ring-rose-500 focus:border-rose-500 p-4 placeholder-slate-400 dark:placeholder-slate-600"></textarea>
                        @error('alasan') <span
                            class="text-[10px] text-rose-600 dark:text-rose-400 font-bold block mt-1.5"><i
                                class="fas fa-info-circle"></i> {{ $message }}</span> @enderror
                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mt-2 leading-relaxed">
                            Jelaskan secara logis agar Supervisor dapat menyetujui pengajuan ini. Data yang dihapus
                            tidak dapat dikembalikan.</p>
                    </div>

                    <div
                        class="pt-5 flex items-center justify-end gap-3 border-t-2 border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-white bg-rose-600 hover:bg-rose-700 active:scale-95 transition-all shadow-lg shadow-rose-500/20 flex items-center gap-2">
                            <span wire:loading.remove wire:target="submitDeletionRequest"><i
                                    class="fas fa-paper-plane"></i> Kirim Pengajuan</span>
                            <span wire:loading wire:target="submitDeletionRequest"><i
                                    class="fas fa-circle-notch fa-spin"></i> Mengirim...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>