<div class="min-h-screen pb-10 transition-colors duration-300 font-jakarta dark:bg-[#050505] bg-slate-50 relative">

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
        background: rgba(99, 102, 241, 0.4);
        border-radius: 10px;
    }
    </style>

    {{-- STICKY HEADER --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-8
        dark:bg-[#0a0a0a]/80 dark:border-white/10 bg-white/95 border-slate-300 shadow-md">

        <div class="flex flex-col xl:flex-row gap-6 items-center justify-between">

            {{-- Logo & Judul --}}
            <div class="flex items-center gap-4 w-full xl:w-auto shrink-0">
                <div
                    class="p-3 rounded-2xl shadow-xl bg-gradient-to-br from-indigo-600 to-blue-700 text-white ring-4 ring-indigo-500/20">
                    <i class="fas fa-brain text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                        Analisa <span class="text-indigo-600 dark:text-indigo-400">SPK SAW</span>
                    </h1>
                    <p
                        class="text-[10px] font-extrabold uppercase tracking-[0.2em] mt-1.5 dark:text-slate-400 text-slate-600">
                        Pemeringkatan Kinerja Salesman
                    </p>
                </div>
            </div>

            {{-- Kolom Pencarian, Filter & Tombol Aksi --}}
            <div class="flex flex-wrap xl:flex-nowrap items-center gap-3 w-full xl:w-auto justify-end">

                {{-- SEARCH FILTER (FITUR BARU) --}}
                <div class="relative w-full sm:w-auto sm:min-w-[200px] group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 dark:text-slate-400 text-slate-500 group-focus-within:text-indigo-600 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 text-[11px] font-bold uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/10 transition-all dark:bg-black/40 dark:border-white/10 dark:text-white bg-white border-slate-200 text-slate-900 placeholder-slate-400 shadow-sm"
                        placeholder="Cari Nama Sales...">
                </div>

                {{-- FILTER PERIODE (GROUPED) --}}
                <div
                    class="flex items-center bg-white dark:bg-[#121212] border-2 border-slate-200 dark:border-white/10 rounded-xl px-2 shadow-sm h-[42px] shrink-0">
                    <i class="far fa-calendar-alt text-slate-400 dark:text-slate-500 ml-2 text-xs"></i>
                    <select wire:model.live="bulan"
                        class="border-none bg-transparent text-[11px] font-bold uppercase tracking-widest focus:ring-0 cursor-pointer dark:text-slate-200 text-slate-700 w-full sm:w-32 py-1 pl-2 pr-6 appearance-none">
                        @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                            @endfor
                    </select>
                    <span class="text-slate-300 dark:text-slate-700 font-black">|</span>
                    <select wire:model.live="tahun"
                        class="border-none bg-transparent text-[11px] font-bold uppercase tracking-widest focus:ring-0 cursor-pointer dark:text-slate-200 text-slate-700 w-full sm:w-20 py-1 pl-2 pr-6 appearance-none">
                        @for($y=date('Y')-1; $y<=date('Y'); $y++) <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                    </select>
                </div>

                {{-- TOMBOL CETAK PDF --}}
                @if(!empty($hasilSPK))
                <button wire:click="exportPdf" wire:loading.attr="disabled"
                    class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/30 transition-all active:scale-95 flex items-center justify-center gap-2 h-[42px] shrink-0 min-w-[150px]">
                    <i class="fas fa-file-pdf"></i>
                    <span wire:loading.remove wire:target="exportPdf">Cetak Laporan</span>
                    <span wire:loading wire:target="exportPdf"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
                </button>
                @endif
            </div>

        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in">

        @if(empty($hasilSPK))
        <div
            class="bg-white dark:bg-[#121212] p-16 rounded-[2rem] border-2 border-slate-200 dark:border-white/5 text-center shadow-xl">
            <i class="fas fa-folder-open text-7xl text-slate-300 dark:text-slate-700 mb-5"></i>
            <h3 class="text-xl font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">Belum Ada Data
                Transaksi</h3>
            <p class="text-xs text-slate-500 mt-2 font-medium">Pilih bulan dan tahun yang memiliki data penjualan untuk
                melakukan kalkulasi SPK.</p>
        </div>
        @else

        {{-- ACUAN BOBOT KRITERIA --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="p-5 bg-white dark:bg-[#121212] border-2 border-blue-100 dark:border-blue-900/30 rounded-2xl shadow-sm relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-16 h-full bg-blue-50 dark:bg-blue-500/5 -skew-x-12 translate-x-4">
                </div>
                <div class="relative z-10">
                    <span
                        class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest bg-blue-50 dark:bg-blue-500/10 px-2 py-1 rounded-md">Kriteria
                        1 (40%)</span>
                    <p class="text-base font-black text-slate-800 dark:text-white mt-3">Total Omzet</p>
                    <p class="text-[10px] text-slate-500 font-bold uppercase mt-1 flex items-center gap-1"><i
                            class="fas fa-arrow-trend-up text-blue-500"></i> Sifat: Keuntungan</p>
                </div>
            </div>
            <div
                class="p-5 bg-white dark:bg-[#121212] border-2 border-emerald-100 dark:border-emerald-900/30 rounded-2xl shadow-sm relative overflow-hidden group">
                <div
                    class="absolute right-0 top-0 w-16 h-full bg-emerald-50 dark:bg-emerald-500/5 -skew-x-12 translate-x-4">
                </div>
                <div class="relative z-10">
                    <span
                        class="text-[9px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-md">Kriteria
                        2 (20%)</span>
                    <p class="text-base font-black text-slate-800 dark:text-white mt-3">Jumlah Nota</p>
                    <p class="text-[10px] text-slate-500 font-bold uppercase mt-1 flex items-center gap-1"><i
                            class="fas fa-arrow-trend-up text-emerald-500"></i> Sifat: Keuntungan</p>
                </div>
            </div>
            <div
                class="p-5 bg-white dark:bg-[#121212] border-2 border-rose-100 dark:border-rose-900/30 rounded-2xl shadow-sm relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-16 h-full bg-rose-50 dark:bg-rose-500/5 -skew-x-12 translate-x-4">
                </div>
                <div class="relative z-10">
                    <span
                        class="text-[9px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest bg-rose-50 dark:bg-rose-500/10 px-2 py-1 rounded-md">Kriteria
                        3 (20%)</span>
                    <p class="text-base font-black text-slate-800 dark:text-white mt-3">Total Retur</p>
                    <p class="text-[10px] text-slate-500 font-bold uppercase mt-1 flex items-center gap-1"><i
                            class="fas fa-arrow-trend-down text-rose-500"></i> Sifat: Biaya</p>
                </div>
            </div>
            <div
                class="p-5 bg-white dark:bg-[#121212] border-2 border-orange-100 dark:border-orange-900/30 rounded-2xl shadow-sm relative overflow-hidden group">
                <div
                    class="absolute right-0 top-0 w-16 h-full bg-orange-50 dark:bg-orange-500/5 -skew-x-12 translate-x-4">
                </div>
                <div class="relative z-10">
                    <span
                        class="text-[9px] font-black text-orange-600 dark:text-orange-400 uppercase tracking-widest bg-orange-50 dark:bg-orange-500/10 px-2 py-1 rounded-md">Kriteria
                        4 (20%)</span>
                    <p class="text-base font-black text-slate-800 dark:text-white mt-3">Piutang Macet</p>
                    <p class="text-[10px] text-slate-500 font-bold uppercase mt-1 flex items-center gap-1"><i
                            class="fas fa-arrow-trend-down text-orange-500"></i> Sifat: Biaya</p>
                </div>
            </div>
        </div>

        {{-- PODIUM: HASIL KEPUTUSAN TERBAIK (TOP 3) --}}
        @if(count($hasilSPK) > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach(array_slice($hasilSPK, 0, 3) as $index => $juara)
            <div
                class="bg-gradient-to-br {{ $index == 0 ? 'from-amber-400 to-orange-500 shadow-orange-500/30 ring-4 ring-amber-500/30 scale-100 md:scale-105 z-10' : ($index == 1 ? 'from-slate-300 to-slate-500 shadow-slate-500/20' : 'from-amber-700 to-amber-900 shadow-amber-900/20') }} p-6 rounded-[2rem] text-white shadow-xl relative overflow-hidden group">
                <i
                    class="fas {{ $index == 0 ? 'fa-trophy text-9xl' : 'fa-medal text-8xl' }} absolute -right-4 -bottom-4 opacity-20 transform group-hover:scale-110 transition-transform duration-500"></i>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <span
                            class="bg-white/20 px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-widest backdrop-blur-sm shadow-sm">
                            Peringkat {{ $index + 1 }}
                        </span>
                        @if($index == 0)
                        <i class="fas fa-crown text-amber-200 text-2xl animate-bounce"></i>
                        @endif
                    </div>
                    <h3 class="text-2xl font-black truncate">{{ $juara['nama'] }}</h3>
                    <p
                        class="text-[10px] font-bold opacity-90 uppercase tracking-widest mt-1 mb-6 flex items-center gap-1">
                        <i class="fas fa-map-marker-alt"></i> {{ $juara['cabang'] ?: 'Semua Cabang' }}
                    </p>
                    <div
                        class="bg-black/20 rounded-xl p-4 backdrop-blur-md border border-white/10 flex justify-between items-end">
                        <div>
                            <p class="text-[9px] uppercase font-black opacity-80 mb-1 tracking-widest">Skor Preferensi
                                (V)</p>
                            <p class="text-3xl font-black">{{ number_format($juara['skor_akhir'], 3) }}</p>
                        </div>
                        <button wire:click="openDetail('{{ $juara['nama'] }}')"
                            class="w-8 h-8 rounded-full bg-white/20 hover:bg-white flex items-center justify-center text-white hover:text-slate-900 transition-colors">
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- TABEL PROSES (MATRIKS) --}}
        <div
            class="bg-white dark:bg-[#0f0f0f] rounded-[2rem] border-2 border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">
            <div
                class="p-6 border-b-2 border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-[#1a1a1a] flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <div>
                    <h4
                        class="font-black text-slate-800 dark:text-white uppercase tracking-widest text-sm flex items-center gap-2">
                        <i class="fas fa-calculator text-indigo-500"></i> Matriks Keputusan & Skor Akhir
                    </h4>
                    <p class="text-[11px] font-bold text-slate-500 mt-1 uppercase tracking-widest">Klik baris tabel
                        untuk melihat pembuktian rumus.</p>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-slate-100 dark:bg-black/40 text-slate-600 dark:text-slate-400 font-black uppercase tracking-[0.15em] text-[10px] border-b-2 dark:border-white/10 border-slate-200">
                        <tr>
                            <th class="px-6 py-5 text-center w-16">Rank</th>
                            <th class="px-6 py-5">Nama Salesman</th>
                            <th class="px-6 py-5 text-center border-l border-slate-200 dark:border-white/5">N1 <span
                                    class="block text-[8px] opacity-60">Omzet</span></th>
                            <th class="px-6 py-5 text-center border-l border-slate-200 dark:border-white/5">N2 <span
                                    class="block text-[8px] opacity-60">Nota</span></th>
                            <th class="px-6 py-5 text-center border-l border-slate-200 dark:border-white/5">N3 <span
                                    class="block text-[8px] opacity-60">Retur</span></th>
                            <th class="px-6 py-5 text-center border-l border-slate-200 dark:border-white/5">N4 <span
                                    class="block text-[8px] opacity-60">Piutang</span></th>
                            <th
                                class="px-6 py-5 text-center border-l border-slate-200 dark:border-white/5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 text-[11px]">
                                Nilai (V)</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y-2 divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-300 font-medium">
                        @forelse($hasilSPK as $index => $row)
                        <tr wire:click="openDetail('{{ $row['nama'] }}')"
                            class="hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors cursor-pointer group">
                            <td class="px-6 py-4 text-center font-black text-slate-900 dark:text-white text-sm">
                                @if($index == 0) <i class="fas fa-trophy text-amber-500"></i> @else {{ $index + 1 }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="font-black text-slate-800 dark:text-white uppercase tracking-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $row['nama'] }}
                                </div>
                                <div class="text-[9px] text-slate-400 uppercase font-bold mt-1 tracking-widest">
                                    {{ $row['cabang'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-mono border-l border-slate-100 dark:border-white/5">
                                {{ $row['n1'] }}</td>
                            <td class="px-6 py-4 text-center font-mono border-l border-slate-100 dark:border-white/5">
                                {{ $row['n2'] }}</td>
                            <td class="px-6 py-4 text-center font-mono border-l border-slate-100 dark:border-white/5">
                                {{ $row['n3'] }}</td>
                            <td class="px-6 py-4 text-center font-mono border-l border-slate-100 dark:border-white/5">
                                {{ $row['n4'] }}</td>
                            <td
                                class="px-6 py-4 text-center font-black text-base border-l border-slate-100 dark:border-white/5 bg-indigo-50/50 dark:bg-indigo-500/5 text-indigo-700 dark:text-indigo-400">
                                {{ number_format($row['skor_akhir'], 3) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7"
                                class="px-6 py-12 text-center text-slate-500 text-xs font-bold uppercase tracking-widest">
                                Tidak ada data yang sesuai dengan pencarian Anda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- MODAL PENJELASAN RUMUS --}}
        @if($isModalOpen && $selectedDetail)
        <div
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm p-4 animate-fade-in overflow-y-auto">
            <div
                class="bg-white dark:bg-[#18181b] w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden relative border-2 border-slate-200 dark:border-white/10 my-8">
                <div class="bg-indigo-600 px-6 py-5 flex justify-between items-center text-white">
                    <h3 class="font-black uppercase tracking-widest text-sm flex items-center gap-2">
                        <i class="fas fa-square-root-variable"></i> Pembuktian Matematis (SAW)
                    </h3>
                    <button wire:click="closeModal"
                        class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center transition-colors"><i
                            class="fas fa-times"></i></button>
                </div>

                <div class="p-6 md:p-8">
                    <div class="mb-6 text-center">
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-black tracking-widest">
                            Dokumentasi Nilai Kinerja Salesman</p>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white mt-1 uppercase">
                            {{ $selectedDetail['nama'] }}</h2>
                    </div>

                    <div class="space-y-4">
                        {{-- K1 --}}
                        <div
                            class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/10">
                            <p
                                class="font-black text-blue-600 dark:text-blue-400 text-[11px] mb-1.5 uppercase tracking-widest flex items-center justify-between">
                                <span>Kriteria 1: Total Omzet</span>
                                <span
                                    class="bg-blue-100 dark:bg-blue-900/30 px-2 py-0.5 rounded text-[9px]">Keuntungan</span>
                            </p>
                            <p class="text-[11px] text-slate-600 dark:text-slate-300 mb-2 leading-relaxed">
                                Dibandingkan dengan <strong>Omzet Tertinggi</strong> bulan ini (Rp
                                {{ $selectedDetail['max_k1_fmt'] }}).
                            </p>
                            <div
                                class="bg-white dark:bg-black p-3 rounded-xl border border-slate-200 dark:border-white/10 font-mono text-[11px]">
                                <p class="text-slate-500">Nilai / Maksimal</p>
                                <p class="mt-1 font-bold dark:text-white">= Rp {{ $selectedDetail['omzet_fmt'] }} / Rp
                                    {{ $selectedDetail['max_k1_fmt'] }} = <strong
                                        class="text-blue-600 dark:text-blue-400 text-sm ml-2">{{ $selectedDetail['n1'] }}</strong>
                                </p>
                            </div>
                        </div>

                        {{-- K3 --}}
                        <div
                            class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/10">
                            <p
                                class="font-black text-rose-600 dark:text-rose-400 text-[11px] mb-1.5 uppercase tracking-widest flex items-center justify-between">
                                <span>Kriteria 3: Total Retur</span>
                                <span
                                    class="bg-rose-100 dark:bg-rose-900/30 px-2 py-0.5 rounded text-[9px]">Biaya</span>
                            </p>
                            <p class="text-[11px] text-slate-600 dark:text-slate-300 mb-2 leading-relaxed">
                                Menjadi pembagi untuk <strong>Retur Terendah</strong> bulan ini (Rp
                                {{ $selectedDetail['min_k3_fmt'] }}).
                            </p>
                            <div
                                class="bg-white dark:bg-black p-3 rounded-xl border border-slate-200 dark:border-white/10 font-mono text-[11px]">
                                <p class="text-slate-500">Minimal / Nilai</p>
                                <p class="mt-1 font-bold dark:text-white">= Rp {{ $selectedDetail['min_k3_fmt'] }} / Rp
                                    {{ $selectedDetail['retur_fmt'] }} = <strong
                                        class="text-rose-600 dark:text-rose-400 text-sm ml-2">{{ $selectedDetail['n3'] }}</strong>
                                </p>
                            </div>
                        </div>

                        {{-- Final --}}
                        <div
                            class="p-5 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border border-indigo-200 dark:border-indigo-800">
                            <p
                                class="font-black text-indigo-700 dark:text-indigo-400 text-xs mb-2 uppercase tracking-widest">
                                Penjumlahan Terbobot Akhir (V)</p>
                            <p class="text-[11px] font-mono text-slate-600 dark:text-slate-400 mb-3 leading-relaxed">
                                V = (N1 * 0.4) + (N2 * 0.2) + (N3 * 0.2) + (N4 * 0.2) <br>
                                V = ({{ $selectedDetail['n1'] }} * 0.4) + ({{ $selectedDetail['n2'] }} * 0.2) +
                                ({{ $selectedDetail['n3'] }} * 0.2) + ({{ $selectedDetail['n4'] }} * 0.2)
                            </p>
                            <div
                                class="flex items-center justify-between border-t border-indigo-200 dark:border-indigo-800 pt-3">
                                <span
                                    class="font-black uppercase tracking-widest text-[10px] text-indigo-800 dark:text-indigo-300">Skor
                                    Akhir Keputusan:</span>
                                <span
                                    class="font-black text-2xl text-indigo-600 dark:text-indigo-400">{{ $selectedDetail['skor_akhir'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>