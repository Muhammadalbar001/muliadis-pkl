<div>
    <div class="min-h-screen space-y-6 pb-20 transition-colors duration-300 font-jakarta bg-slate-50 dark:bg-[#050505]"
        x-data="{ activeTab: @entangle('activeTab').live }">

        {{-- CSS Tambahan & Scrollbar --}}
        <style>
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
            background: rgba(203, 213, 225, 0.5);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.8);
        }
        </style>

        {{-- HEADER & NAVIGASI --}}
        <div
            class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6 dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

            <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
                <div class="flex items-center gap-4 w-full xl:w-auto">
                    <div
                        class="p-3 rounded-2xl shadow-lg dark:bg-amber-500/20 bg-amber-500 text-white dark:text-amber-400 flex items-center justify-center">
                        <i class="fas fa-trophy text-xl"></i>
                    </div>
                    <div>
                        <h1
                            class="text-2xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                            Rapor <span class="text-amber-500 dark:text-amber-400">Kinerja Sales</span>
                        </h1>
                        <p
                            class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-60 mt-1 dark:text-slate-400 text-slate-500">
                            Monitoring KPI & Pencapaian Target
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">
                    {{-- PENCARIAN --}}
                    <div class="relative w-full sm:w-48 group">
                        <i
                            class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-amber-500 transition-colors text-xs"></i>
                        <input wire:model.live.debounce.500ms="search" type="text"
                            class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-amber-500/20 transition-all h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner dark:placeholder-slate-500"
                            placeholder="Cari Kode/Nama...">
                    </div>

                    {{-- FILTER MINIMAL NOTA --}}
                    <div x-show="activeTab === 'produktifitas'" x-cloak x-transition
                        class="flex items-center dark:bg-black/40 bg-white border dark:border-white/10 border-slate-200 rounded-xl px-3 shadow-inner h-[38px]">
                        <span
                            class="text-[9px] font-black text-blue-500 dark:text-blue-400 uppercase pr-2 border-r dark:border-white/10 border-slate-100 mr-2">Min.
                            Nota</span>
                        <input type="number" wire:model.live.debounce.500ms="minNominal"
                            class="w-20 border-none focus:ring-0 text-[10px] font-black text-slate-700 dark:text-white py-1 bg-transparent p-0"
                            placeholder="Rp">
                    </div>

                    {{-- FILTER CABANG --}}
                    <div class="relative w-full sm:w-40" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="w-full flex items-center justify-between border px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-slate-300 bg-white border-slate-200 text-slate-700 hover:border-amber-400 dark:hover:border-amber-500/50">
                            <span class="truncate">
                                @if(count($filterCabang) > 0) {{ count($filterCabang) }} Regional @else Pilih Cabang
                                @endif
                            </span>
                            <i class="fas fa-chevron-down opacity-40 text-[10px] transition-transform"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition
                            class="absolute z-[100] mt-2 w-56 border rounded-2xl shadow-2xl p-2 max-h-72 overflow-y-auto custom-scrollbar dark:bg-[#1a1a1a] dark:border-white/10 bg-white border-slate-200">
                            @foreach($optCabang as $c)
                            <label
                                class="flex items-center px-3 py-2.5 hover:bg-amber-500/10 dark:hover:bg-amber-500/20 rounded-xl cursor-pointer transition-colors group">
                                <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                                    class="rounded-full border-slate-300 dark:border-slate-600 text-amber-600 focus:ring-amber-500 h-3.5 w-3.5 dark:bg-white/5 cursor-pointer">
                                <span
                                    class="ml-3 text-[10px] font-bold uppercase tracking-tight dark:text-slate-300 text-slate-600 group-hover:text-amber-600 dark:group-hover:text-amber-400">
                                    {{ $c }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- FILTER BULAN --}}
                    <input type="month" wire:model.live="bulan"
                        class="w-full sm:w-36 border px-4 py-2 rounded-xl text-[11px] font-black uppercase h-[38px] dark:bg-black/40 bg-white dark:border-white/10 border-slate-200 dark:text-white transition-all shadow-sm cursor-pointer">

                    <div class="flex items-center gap-2">
                        <button wire:click="resetFilter"
                            class="px-4 py-2 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 rounded-xl text-[10px] hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:text-rose-500 transition-all shadow-sm h-[38px]"
                            title="Reset Filter"><i class="fas fa-undo"></i>
                        </button>

                        <button wire:click="export"
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-600/20 h-[38px] flex items-center gap-2 transition-transform active:scale-95">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>

                        {{-- TOMBOL PDF DINAMIS --}}
                        <button wire:click="exportPdf" wire:loading.attr="disabled"
                            class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 h-[38px] flex items-center gap-2 transition-transform active:scale-95">
                            <i class="fas fa-file-pdf"></i>
                            <span>
                                @if($activeTab == 'penjualan') Cetak Rapor
                                @elseif($activeTab == 'ar') Cetak Kredit
                                @elseif($activeTab == 'supplier') Cetak Supplier
                                @elseif($activeTab == 'produktifitas') Cetak Produktivitas
                                @else Cetak PDF @endif
                            </span>
                            <i wire:loading wire:target="exportPdf" class="fas fa-spinner fa-spin ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- KARTU RINGKASAN GLOBAL --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 px-4 sm:px-6 lg:px-8">
            <div
                class="p-6 rounded-[2rem] border transition-all dark:bg-[#121212] bg-white border-slate-200 dark:border-white/5 shadow-sm flex flex-col justify-center">
                <div
                    class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center mb-3">
                    <i class="fas fa-bullseye text-lg"></i></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Target Omzet</p>
                <h3 class="text-2xl font-black dark:text-white text-slate-800 tracking-tighter">Rp
                    {{ $this->formatCompact($globalSummary['total_target']) }}</h3>
            </div>

            <div
                class="relative p-6 rounded-[2rem] border transition-all overflow-hidden dark:bg-emerald-500/10 dark:border-emerald-500/20 bg-emerald-600 text-white shadow-xl shadow-emerald-600/20 group">
                <div
                    class="w-10 h-10 rounded-xl bg-white/20 dark:bg-emerald-500/20 text-white dark:text-emerald-400 flex items-center justify-center mb-3">
                    <i class="fas fa-chart-line text-lg"></i></div>
                <p
                    class="text-emerald-100 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest mb-1 opacity-80">
                    Realisasi Bersih</p>
                <h3 class="text-3xl font-black tracking-tighter">Rp
                    {{ $this->formatCompact($globalSummary['total_real']) }}</h3>
                <i
                    class="fas fa-arrow-trend-up absolute -right-2 -bottom-2 text-[6rem] opacity-10 rotate-12 group-hover:scale-110 transition-transform duration-500"></i>
            </div>

            <div
                class="p-6 rounded-[2rem] border transition-all dark:bg-[#121212] bg-white border-slate-200 dark:border-white/5 shadow-sm flex flex-col justify-center">
                <div
                    class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 text-orange-600 flex items-center justify-center mb-3">
                    <i class="fas fa-file-invoice-dollar text-lg"></i></div>
                <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-1">Total Piutang</p>
                <h3 class="text-2xl font-black dark:text-white text-slate-800 tracking-tighter">Rp
                    {{ $this->formatCompact($globalSummary['total_ar']) }}</h3>
            </div>

            <div
                class="p-6 rounded-[2rem] border transition-all dark:bg-rose-500/5 dark:border-rose-500/20 bg-white border-rose-100 shadow-sm flex flex-col justify-center">
                <div
                    class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/30 text-rose-600 flex items-center justify-center mb-3">
                    <i class="fas fa-exclamation-triangle text-lg"></i></div>
                <p class="text-[10px] font-black text-rose-500 dark:text-rose-400 uppercase tracking-widest mb-1">Risiko
                    Piutang Macet</p>
                <h3 class="text-2xl font-black dark:text-rose-400 text-rose-600 tracking-tighter">Rp
                    {{ $this->formatCompact($globalSummary['total_macet']) }}</h3>
            </div>
        </div>

        {{-- NAVIGASI TAB --}}
        <div class="px-4 sm:px-6 lg:px-8">
            <div
                class="flex space-x-2 dark:bg-[#121212] bg-white p-1.5 rounded-[1.25rem] w-fit overflow-x-auto border dark:border-white/5 border-slate-200 shadow-sm">
                <button wire:click="setTab('penjualan')"
                    :class="activeTab === 'penjualan' ? 'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-2">
                    <i class="fas fa-chart-line text-sm"></i> Kinerja Penjualan
                </button>
                <button wire:click="setTab('ar')"
                    :class="activeTab === 'ar' ? 'bg-orange-50 dark:bg-orange-500/20 text-orange-700 dark:text-orange-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-2">
                    <i class="fas fa-money-bill-wave text-sm"></i> Monitoring Kredit
                </button>
                <button wire:click="setTab('supplier')"
                    :class="activeTab === 'supplier' ? 'bg-purple-50 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-2">
                    <i class="fas fa-boxes-stacked text-sm"></i> Penjualan By Supplier
                </button>
                <button wire:click="setTab('produktifitas')"
                    :class="activeTab === 'produktifitas' ? 'bg-blue-50 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5'"
                    class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-2">
                    <i class="fas fa-users-viewfinder text-sm"></i> Analisa Produktivitas
                </button>
            </div>
        </div>

        {{-- KONTEN TAB --}}
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2.5rem] border overflow-hidden transition-all duration-300 flex flex-col min-h-[80vh] mb-10 dark:bg-[#121212] dark:border-white/5 bg-white border-slate-200 shadow-xl"
                wire:loading.class="opacity-50">

                {{-- 1. TAB KINERJA PENJUALAN --}}
                <div x-show="activeTab === 'penjualan'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div
                        class="p-6 border-b dark:border-white/5 border-slate-100 bg-emerald-50/50 dark:bg-emerald-500/10">
                        <span
                            class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400"><i
                                class="fas fa-table text-emerald-500 mr-2"></i> Matriks Pencapaian Sales</span>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-sm text-left border-collapse uppercase font-jakarta">
                            <thead>
                                <tr
                                    class="dark:bg-[#1a1a1a] bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b border-slate-100 dark:border-white/5">
                                    <th class="px-6 py-5 border-r border-slate-100 dark:border-white/5 w-24">Kode</th>
                                    <th class="px-6 py-5 border-r border-slate-100 dark:border-white/5">Nama Sales</th>
                                    <th class="px-6 py-5 text-right border-r border-slate-100 dark:border-white/5">
                                        Target (Rp)</th>
                                    <th
                                        class="px-6 py-5 text-right border-r border-slate-100 dark:border-white/5 text-emerald-600 dark:text-emerald-400">
                                        Realisasi (Rp)</th>
                                    <th class="px-6 py-5 text-center border-r border-slate-100 dark:border-white/5">
                                        Capaian %</th>
                                    <th class="px-6 py-5 text-right">Selisih (Gap)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                                @foreach($laporan as $row)
                                <tr class="hover:bg-emerald-50 dark:hover:bg-emerald-500/5 transition-colors group">
                                    <td
                                        class="px-6 py-4 font-mono font-bold text-indigo-500 dark:text-indigo-400 text-xs border-r border-slate-50 dark:border-white/5">
                                        {{ $row['kode'] }}</td>
                                    <td class="px-6 py-4 border-r border-slate-50 dark:border-white/5">
                                        <div class="font-black dark:text-white text-slate-800 text-xs">
                                            {{ $row['nama'] }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold tracking-widest mt-1">
                                            {{ $row['cabang'] ?: '-' }}</div>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right font-mono text-slate-500 dark:text-slate-400 border-r border-slate-50 dark:border-white/5">
                                        {{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                                    <td
                                        class="px-6 py-4 text-right font-black text-emerald-700 dark:text-emerald-400 border-r border-slate-50 dark:border-white/5 bg-emerald-500/[0.02] dark:bg-emerald-500/[0.05]">
                                        {{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center border-r border-slate-50 dark:border-white/5">
                                        <span
                                            class="px-3 py-1 rounded-md text-[10px] font-black tracking-widest border {{ $row['persen_ims'] >= 100 ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20' : ($row['persen_ims'] >= 80 ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20') }}">
                                            {{ number_format($row['persen_ims'], 1) }}%
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right font-mono text-xs font-bold {{ $row['gap'] >= 0 ? 'text-emerald-500 dark:text-emerald-400' : 'text-rose-500 dark:text-rose-400' }}">
                                        {{ number_format($row['gap'], 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 2. TAB MONITORING KREDIT --}}
                <div x-show="activeTab === 'ar'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div
                        class="p-6 border-b dark:border-white/5 border-slate-100 bg-orange-50/50 dark:bg-orange-500/10">
                        <span
                            class="text-[11px] font-black uppercase tracking-[0.2em] text-orange-600 dark:text-orange-400"><i
                                class="fas fa-table text-orange-500 mr-2"></i> Monitor Risiko Kredit Sales</span>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-sm text-left border-collapse uppercase font-jakarta">
                            <thead>
                                <tr
                                    class="dark:bg-[#1a1a1a] bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b border-slate-100 dark:border-white/5">
                                    <th class="px-6 py-5 border-r border-slate-100 dark:border-white/5 w-24">Kode</th>
                                    <th class="px-6 py-5 border-r border-slate-100 dark:border-white/5">Nama Sales</th>
                                    <th class="px-6 py-5 text-right border-r border-slate-100 dark:border-white/5">Total
                                        Tagihan</th>
                                    <th
                                        class="px-6 py-5 text-right border-r border-slate-100 dark:border-white/5 text-rose-600 dark:text-rose-400">
                                        Macet (>30 Hari)</th>
                                    <th class="px-6 py-5 text-center">Rasio Macet</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                                @foreach($laporan as $row)
                                <tr class="hover:bg-orange-50 dark:hover:bg-orange-500/5 transition-colors group">
                                    <td
                                        class="px-6 py-4 font-mono font-bold text-indigo-500 dark:text-indigo-400 text-xs border-r border-slate-50 dark:border-white/5">
                                        {{ $row['kode'] }}</td>
                                    <td
                                        class="px-6 py-4 font-black text-slate-800 dark:text-white text-xs border-r border-slate-50 dark:border-white/5">
                                        {{ $row['nama'] }}</td>
                                    <td
                                        class="px-6 py-4 text-right font-black text-slate-600 dark:text-slate-300 border-r border-slate-50 dark:border-white/5">
                                        {{ number_format($row['ar_total'], 0, ',', '.') }}</td>
                                    <td
                                        class="px-6 py-4 text-right font-black text-rose-600 dark:text-rose-400 border-r border-slate-50 dark:border-white/5 bg-rose-500/[0.02] dark:bg-rose-500/[0.05]">
                                        {{ number_format($row['ar_macet'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-3 py-1 rounded-md text-[10px] font-bold border {{ $row['ar_persen_macet'] > 10 ? 'bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-500/30' : 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/30' }}">
                                            {{ number_format($row['ar_persen_macet'], 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 3. TAB MIX SUPPLIER --}}
                <div x-show="activeTab === 'supplier'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div
                        class="p-6 border-b dark:border-white/5 border-slate-100 bg-purple-50/50 dark:bg-purple-500/10">
                        <span
                            class="text-[11px] font-black uppercase tracking-[0.2em] text-purple-600 dark:text-purple-400"><i
                                class="fas fa-table text-purple-500 mr-2"></i> Matriks Penetrasi Supplier</span>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table
                            class="w-full text-[10px] text-left whitespace-nowrap border-collapse uppercase font-jakarta relative">
                            <thead>
                                <tr
                                    class="dark:bg-[#1a1a1a] bg-slate-50 text-slate-500 dark:text-slate-400 font-black tracking-[0.1em] border-b border-slate-100 dark:border-white/5">
                                    <th
                                        class="px-6 py-5 sticky left-0 dark:bg-[#1a1a1a] bg-slate-50 border-r border-slate-100 dark:border-white/5 z-20">
                                        Personel Sales</th>
                                    @foreach($topSuppliers as $supp)
                                    <th class="px-6 py-5 text-right border-r border-slate-100 dark:border-white/5 w-32 truncate"
                                        title="{{ $supp }}">{{ Str::limit($supp, 15) }}</th>
                                    @endforeach
                                    <th
                                        class="px-6 py-5 text-center bg-purple-50 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400 border-l dark:border-white/5 sticky right-32 z-20 shadow-[-5px_0_10px_-5px_rgba(0,0,0,0.1)]">
                                        Jumlah Brand</th>
                                    <th
                                        class="px-6 py-5 text-right bg-purple-100 dark:bg-purple-800/60 text-purple-900 dark:text-purple-200 sticky right-0 z-20 border-l border-purple-200 dark:border-purple-700">
                                        Total Omzet</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                                @foreach($laporan as $row)
                                <tr class="hover:bg-purple-50 dark:hover:bg-purple-500/5 transition-colors group">
                                    <td
                                        class="px-6 py-4 font-black text-slate-700 dark:text-white sticky left-0 dark:bg-[#121212] group-hover:dark:bg-[#1a1a1a] bg-white group-hover:bg-purple-50 border-r border-slate-100 dark:border-white/5 z-10">
                                        {{ $row['nama'] }} <span
                                            class="text-[9px] font-mono font-bold text-slate-400 dark:text-slate-500 ml-1">({{ $row['kode'] }})</span>
                                    </td>
                                    @foreach($topSuppliers as $supp)
                                    <td
                                        class="px-6 py-4 text-right border-r border-slate-50 dark:border-white/5 font-mono text-slate-500 dark:text-slate-400">
                                        @php $val = $matrixSupplier[$row['nama']][$supp] ?? 0; @endphp
                                        <span
                                            class="{{ $val > 0 ? 'text-slate-800 dark:text-slate-200 font-bold' : 'opacity-20' }}">{{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}</span>
                                    </td>
                                    @endforeach
                                    <td
                                        class="px-6 py-4 text-center font-black bg-purple-50/80 dark:bg-purple-900/20 border-l dark:border-white/5 sticky right-32 z-10 text-purple-700 dark:text-purple-400 shadow-[-5px_0_10px_-5px_rgba(0,0,0,0.05)]">
                                        {{ $row['jml_supplier'] }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right font-black bg-purple-100/80 dark:bg-purple-800/40 text-purple-900 dark:text-purple-200 sticky right-0 z-10 border-l border-purple-200 dark:border-purple-700">
                                        {{ number_format($row['total_supplier_val'], 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 4. TAB ANALISA PRODUKTIVITAS --}}
                <div x-show="activeTab === 'produktifitas'" x-cloak
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">
                    <div
                        class="p-6 border-b dark:border-white/5 border-slate-100 bg-blue-50/50 dark:bg-blue-500/10 flex justify-between items-center">
                        <span
                            class="text-[11px] font-black uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400"><i
                                class="fas fa-table text-blue-500 mr-2"></i> Analisa Efektivitas Kunjungan</span>
                        <span
                            class="text-[9px] font-bold bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 px-3 py-1.5 rounded-md border border-blue-200 dark:border-blue-500/30 shadow-sm">
                            Min. Nota: Rp {{ number_format($minNominal,0,',','.') }}
                        </span>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-sm text-left border-collapse uppercase font-jakarta">
                            <thead>
                                <tr
                                    class="dark:bg-[#1a1a1a] bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b border-slate-100 dark:border-white/5">
                                    <th class="px-6 py-5 border-r border-slate-100 dark:border-white/5 w-24">Kode</th>
                                    <th class="px-6 py-5 border-r border-slate-100 dark:border-white/5">Nama Sales</th>
                                    <th
                                        class="px-6 py-5 text-center border-r border-slate-100 dark:border-white/5 text-blue-600 dark:text-blue-400">
                                        Outlet Aktif (OA)</th>
                                    <th class="px-6 py-5 text-center text-emerald-600 dark:text-emerald-400">Nota
                                        Efektif (EC)</th>
                                    <th class="px-6 py-5 text-center border-l border-slate-100 dark:border-white/5">
                                        Rasio EC/OA</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                                @foreach($laporan as $row)
                                <tr class="hover:bg-blue-50 dark:hover:bg-blue-500/5 transition-colors group">
                                    <td
                                        class="px-6 py-4 font-mono font-bold text-indigo-500 dark:text-indigo-400 text-xs border-r border-slate-50 dark:border-white/5">
                                        {{ $row['kode'] }}</td>
                                    <td
                                        class="px-6 py-4 font-black text-slate-800 dark:text-white text-xs border-r border-slate-50 dark:border-white/5">
                                        {{ $row['nama'] }}</td>
                                    <td
                                        class="px-6 py-4 text-center font-black text-blue-600 dark:text-blue-400 border-r border-slate-50 dark:border-white/5 text-lg bg-blue-500/[0.02] dark:bg-blue-500/[0.05]">
                                        {{ $row['real_oa'] }}</td>
                                    <td
                                        class="px-6 py-4 text-center font-black text-emerald-600 dark:text-emerald-400 text-lg bg-emerald-500/[0.02] dark:bg-emerald-500/[0.05]">
                                        {{ $row['ec'] }}</td>
                                    <td class="px-6 py-4 text-center border-l border-slate-50 dark:border-white/5">
                                        @php $ratio = $row['real_oa'] > 0 ? ($row['ec'] / $row['real_oa']) * 100 : 0;
                                        @endphp
                                        <span
                                            class="px-3 py-1 rounded-md text-[10px] font-black tracking-widest border {{ $ratio > 50 ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700/50 dark:border-slate-600 text-slate-500 dark:text-slate-300' }}">
                                            {{ number_format($ratio, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- PAGINASI --}}
                <div
                    class="mt-auto px-6 py-6 border-t dark:border-white/5 border-slate-100 dark:bg-[#1a1a1a] bg-slate-50/50 uppercase font-black text-[10px]">
                    {{ $laporan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>