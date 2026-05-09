<div class="min-h-screen space-y-8 pb-20 font-jakarta bg-slate-50 dark:bg-[#050505] transition-colors duration-300">

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

    {{-- STICKY HEADER --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-5 mb-8
        dark:bg-slate-950/95 dark:border-white/5 bg-white/98 border-slate-100 shadow-lg flex flex-col gap-4">

        <div class="flex flex-col xl:flex-row gap-6 items-start lg:items-center justify-between">
            {{-- Logo & Judul --}}
            <div class="flex items-center gap-4 w-full xl:w-auto shrink-0">
                <div class="p-3 rounded-2xl shadow-xl bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-500 text-white ring-4 ring-indigo-500/20 group-hover:shadow-2xl group-hover:shadow-indigo-500/30 transition-all duration-300">
                    <i class="fas fa-print text-2xl font-bold"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-tight uppercase leading-none dark:text-white text-slate-900">Pusat Cetak <span class="bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent dark:from-indigo-400 dark:to-blue-400">Laporan</span></h1>
                    <p class="text-[11px] font-bold uppercase tracking-[0.15em] mt-1.5 dark:text-slate-400 text-slate-500">Manajemen Cetak Terpusat & Strategis</p>
                </div>
            </div>

            {{-- Info Tambahan Kanan --}}
            <div class="flex items-center gap-2 w-full xl:w-auto justify-end">
                <span class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 text-[10px] font-bold text-indigo-700 dark:text-indigo-300 flex items-center gap-2 border border-indigo-200/50 dark:border-indigo-500/30 shadow-sm">
                    <i class="fas fa-info-circle text-indigo-600 dark:text-indigo-400"></i> Operasional, Keuangan & Analitik
                </span>
            </div>
        </div>
    </div>

    {{-- AREA KONTEN UTAMA --}}
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto animate-fade-in space-y-8">

        {{-- SECTION 1: KINERJA SALES --}}
        <div>
            <div class="flex items-center gap-4 mb-6">
                <div class="h-1 w-16 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-full"></div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 px-4 py-2 bg-slate-100/50 dark:bg-white/5 rounded-full border border-slate-200/50 dark:border-white/10">Laporan Operasional & Sales</span>
            </div>

            <div class="bg-white dark:bg-slate-900/50 rounded-3xl border border-slate-200/50 dark:border-white/10 shadow-lg hover:shadow-xl transition-all duration-300 backdrop-blur-sm">
                <div class="grid grid-cols-1 lg:grid-cols-12" x-data="{ dropdownActive: false }">

                    {{-- SIDEBAR FILTER SALES --}}
                    <div :class="dropdownActive ? 'z-[100] relative' : 'z-10 relative'"
                        class="lg:col-span-3 bg-gradient-to-b from-slate-50 to-white dark:from-slate-900/30 dark:to-slate-900/50 p-8 border-b lg:border-b-0 lg:border-r border-slate-200/50 dark:border-white/5 flex flex-col justify-start rounded-l-3xl lg:rounded-tr-none transition-all">
                        <div class="mb-8 pb-4 border-b border-slate-200/50 dark:border-white/5">
                            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Filter Laporan</h3>
                            <p class="text-[10px] font-medium text-slate-500 dark:text-slate-400 mt-2">Atur parameter untuk cetak laporan.</p>
                        </div>

                        <div class="space-y-5">
                            {{-- Periode --}}
                            <div class="space-y-1.5">
                                <label
                                    class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Periode</label>
                                <div class="flex gap-2">
                                    <select wire:model="bulan"
                                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-[11px] font-bold uppercase focus:ring-indigo-500 cursor-pointer dark:text-white transition-all shadow-inner">
                                        @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                            @endfor
                                    </select>
                                    <select wire:model="tahun"
                                        class="w-24 px-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-[11px] font-bold uppercase focus:ring-indigo-500 cursor-pointer dark:text-white transition-all shadow-inner">
                                        @for($y=date('Y')-1; $y<=date('Y')+1; $y++) <option value="{{ $y }}">{{ $y }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>
                            </div>

                            {{-- Cabang Sales --}}
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Cabang
                                    Area</label>
                                <select wire:model.live="selectedCabangSales"
                                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-[11px] font-bold uppercase focus:ring-indigo-500 cursor-pointer dark:text-white transition-all shadow-inner">
                                    <option value="Semua Cabang">Semua Cabang</option>
                                    @foreach($cabangOptions as $c) <option value="{{ $c }}">{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- MULTI SELECT SALESMAN (ALPINE) --}}
                            <div class="space-y-1.5 relative" x-data="{
                                     open: false, search: '',
                                     selected: @entangle('selectedSalesIds').live,
                                     options: @entangle('salesOptions'),
                                     get filteredOptions() {
                                         if(this.search === '') return this.options;
                                         return this.options.filter(i => i.sales_name.toLowerCase().includes(this.search.toLowerCase()))
                                     }
                                 }" x-init="$watch('open', value => dropdownActive = value)">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pilih
                                    Sales (Spesifik)</label>
                                <div class="relative">
                                    <button @click="open = !open" @click.outside="open = false" type="button"
                                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-left flex justify-between items-center bg-white dark:bg-transparent text-[11px] font-bold shadow-sm focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer">
                                        <span
                                            x-text="selected.length > 0 ? selected.length + ' Sales Dipilih' : 'Semua Sales...'"
                                            class="truncate text-slate-700 dark:text-slate-200 uppercase"></span>
                                        <i class="fas fa-chevron-down opacity-50 text-[10px]"></i>
                                    </button>

                                    <div x-show="open" x-transition.origin.top style="display: none;"
                                        class="absolute z-[100] w-full mt-1 bg-white dark:bg-[#1a1a1a] rounded-xl shadow-2xl border border-slate-200 dark:border-white/10 p-2 ring-1 ring-black/5">
                                        <input x-model="search" type="text" placeholder="Cari nama sales..."
                                            class="w-full mb-2 pl-3 py-2 rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#121212] text-xs font-bold uppercase focus:ring-indigo-500 dark:text-white">
                                        <div class="max-h-56 overflow-y-auto custom-scrollbar space-y-0.5">
                                            <template x-for="item in filteredOptions" :key="item.id">
                                                <label
                                                    class="flex items-center px-2 py-2 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg cursor-pointer transition-colors group">
                                                    <input type="checkbox" :value="item.id" x-model="selected"
                                                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5 mr-3 cursor-pointer dark:bg-black/50">
                                                    <div>
                                                        <div class="text-[10px] font-bold uppercase text-slate-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400"
                                                            x-text="item.sales_name"></div>
                                                    </div>
                                                </label>
                                            </template>
                                            <div x-show="filteredOptions.length === 0"
                                                class="text-center py-3 text-[10px] text-slate-400 uppercase font-bold tracking-widest">
                                                Tidak Ditemukan.</div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Tags --}}
                                @if(!empty($selectedSalesIds))
                                <div class="flex flex-wrap gap-1 mt-2 max-h-20 overflow-y-auto custom-scrollbar">
                                    <template x-for="id in selected" :key="id">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded bg-slate-100 border border-slate-200 dark:bg-white/5 dark:border-white/10 text-slate-600 dark:text-slate-300 text-[8px] font-black uppercase shadow-sm">
                                            <span
                                                x-text="options.find(o => o.id == id)?.sales_name.substring(0, 10) + '...'"></span>
                                            <button @click="selected = selected.filter(i => i != id)"
                                                class="hover:text-rose-500 ml-1 transition-colors"><i
                                                    class="fas fa-times"></i></button>
                                        </span>
                                    </template>
                                </div>
                                @endif
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Filter EC
                                    (Min. Nota)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-[10px] font-bold text-slate-400">Rp</span>
                                    <input type="number" wire:model="minNominal"
                                        class="w-full pl-8 pr-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-[11px] font-bold focus:ring-indigo-500 shadow-inner dark:text-white transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BUTTONS (Right) --}}
                    <div class="lg:col-span-9 p-10 bg-gradient-to-br from-white to-slate-50/50 dark:from-slate-900/50 dark:to-slate-950 rounded-r-3xl relative z-10">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 h-full content-center">

                            <button wire:click="cetakSales('penjualan')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-7 rounded-2xl border-2 transition-all duration-300 group text-left hover:-translate-y-2 hover:shadow-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 hover:from-emerald-100 hover:to-emerald-200/70 border-emerald-200/60 dark:border-emerald-500/30 text-emerald-900 dark:text-emerald-300 dark:bg-emerald-500/5 cursor-pointer flex flex-col justify-between h-full">
                                <div class="absolute right-0 bottom-0 p-6 opacity-5 group-hover:opacity-15 transition-opacity group-hover:scale-125 duration-300">
                                    <i class="fas fa-chart-line text-8xl"></i>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-emerald-200/60 dark:bg-emerald-600/40 flex items-center justify-center mb-5 border border-emerald-300/50 dark:border-emerald-500/50 shadow-lg group-hover:shadow-emerald-500/20">
                                    <i class="fas fa-chart-line text-lg text-emerald-700 dark:text-emerald-300"></i>
                                </div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1.5">Kinerja Penjualan</h4>
                                    <p class="text-[11px] font-medium opacity-75 leading-relaxed">Target vs Realisasi</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('penjualan')" class="fas fa-spinner fa-spin absolute top-5 right-5 text-emerald-600 dark:text-emerald-400 text-lg"></i>
                            </button>

                            <button wire:click="cetakSales('ar')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-7 rounded-2xl border-2 transition-all duration-300 group text-left hover:-translate-y-2 hover:shadow-2xl bg-gradient-to-br from-orange-50 to-orange-100/50 hover:from-orange-100 hover:to-orange-200/70 border-orange-200/60 dark:border-orange-500/30 text-orange-900 dark:text-orange-300 dark:bg-orange-500/5 cursor-pointer flex flex-col justify-between h-full">
                                <div class="absolute right-0 bottom-0 p-6 opacity-5 group-hover:opacity-15 transition-opacity group-hover:scale-125 duration-300">
                                    <i class="fas fa-file-invoice-dollar text-8xl"></i>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-orange-200/60 dark:bg-orange-600/40 flex items-center justify-center mb-5 border border-orange-300/50 dark:border-orange-500/50 shadow-lg group-hover:shadow-orange-500/20">
                                    <i class="fas fa-file-invoice-dollar text-lg text-orange-700 dark:text-orange-300"></i>
                                </div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1.5">Monitoring Kredit</h4>
                                    <p class="text-[11px] font-medium opacity-75 leading-relaxed">Risiko Piutang Macet</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('ar')" class="fas fa-spinner fa-spin absolute top-5 right-5 text-orange-600 dark:text-orange-400 text-lg"></i>
                            </button>

                            <button wire:click="cetakSales('supplier')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-7 rounded-2xl border-2 transition-all duration-300 group text-left hover:-translate-y-2 hover:shadow-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 hover:from-purple-100 hover:to-purple-200/70 border-purple-200/60 dark:border-purple-500/30 text-purple-900 dark:text-purple-300 dark:bg-purple-500/5 cursor-pointer flex flex-col justify-between h-full">
                                <div class="absolute right-0 bottom-0 p-6 opacity-5 group-hover:opacity-15 transition-opacity group-hover:scale-125 duration-300">
                                    <i class="fas fa-boxes-stacked text-8xl"></i>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-purple-200/60 dark:bg-purple-600/40 flex items-center justify-center mb-5 border border-purple-300/50 dark:border-purple-500/50 shadow-lg group-hover:shadow-purple-500/20">
                                    <i class="fas fa-boxes-stacked text-lg text-purple-700 dark:text-purple-300"></i>
                                </div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1.5">Sales by Supplier</h4>
                                    <p class="text-[11px] font-medium opacity-75 leading-relaxed">Kontribusi Penjualan</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('supplier')" class="fas fa-spinner fa-spin absolute top-5 right-5 text-purple-600 dark:text-purple-400 text-lg"></i>
                            </button>

                            <button wire:click="cetakSales('produktifitas')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-7 rounded-2xl border-2 transition-all duration-300 group text-left hover:-translate-y-2 hover:shadow-2xl bg-gradient-to-br from-cyan-50 to-cyan-100/50 hover:from-cyan-100 hover:to-cyan-200/70 border-cyan-200/60 dark:border-cyan-500/30 text-cyan-900 dark:text-cyan-300 dark:bg-cyan-500/5 cursor-pointer flex flex-col justify-between h-full">
                                <div class="absolute right-0 bottom-0 p-6 opacity-5 group-hover:opacity-15 transition-opacity group-hover:scale-125 duration-300">
                                    <i class="fas fa-users-viewfinder text-8xl"></i>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-cyan-200/60 dark:bg-cyan-600/40 flex items-center justify-center mb-5 border border-cyan-300/50 dark:border-cyan-500/50 shadow-lg group-hover:shadow-cyan-500/20">
                                    <i class="fas fa-users-viewfinder text-lg text-cyan-700 dark:text-cyan-300"></i>
                                </div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1.5">Produktivitas</h4>
                                    <p class="text-[11px] font-medium opacity-75 leading-relaxed">Efektivitas OA / EC</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('produktifitas')" class="fas fa-spinner fa-spin absolute top-5 right-5 text-cyan-600 dark:text-cyan-400 text-lg"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: AUDIT & KOMPARASI --}}
        <div class="flex items-center gap-4 mb-6 mt-10">
            <div class="h-1 w-16 bg-gradient-to-r from-slate-400 to-slate-500 rounded-full"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 px-4 py-2 bg-slate-100/50 dark:bg-white/5 rounded-full border border-slate-200/50 dark:border-white/10">Audit & Komparasi Kinerja</span>
        </div>

        <div class="bg-white dark:bg-slate-900/50 rounded-3xl border border-slate-200/50 dark:border-white/10 shadow-lg hover:shadow-xl transition-all duration-300 p-8 relative group mb-8 z-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <div class="lg:col-span-3 relative z-10 flex flex-col justify-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 text-white flex items-center justify-center text-2xl shadow-lg mb-6 border border-slate-600/50">
                        <i class="fas fa-scale-balanced"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase leading-tight tracking-tight">
                        Analisa <br>Perbandingan</h3>
                    <p class="text-[11px] font-medium text-slate-600 dark:text-slate-400 mt-3 leading-relaxed">Cetak laporan perbandingan tren operasional dengan filter rentang waktu presisi.</p>
                </div>

                <div class="lg:col-span-5 relative z-10 bg-gradient-to-br from-slate-50 to-slate-100/50 dark:from-slate-800/30 dark:to-slate-900/20 rounded-2xl p-7 border border-slate-200/50 dark:border-white/5 shadow-inner">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Rentang
                                Tanggal Khusus</label>
                            <div class="flex items-center gap-3">
                                <input type="date" wire:model="tglMulaiKomparasi"
                                    class="w-full px-4 py-3 rounded-xl border-2 border-slate-200/50 dark:border-slate-700/50 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/30 dark:to-slate-900/20 text-slate-700 dark:text-slate-300 text-xs font-bold uppercase focus:ring-slate-500 focus:border-slate-400 cursor-pointer shadow-sm transition-all">
                                <span class="text-slate-400 text-[10px] font-black uppercase px-2 shrink-0">s/d</span>
                                <input type="date" wire:model="tglSelesaiKomparasi"
                                    class="w-full px-4 py-3 rounded-xl border-2 border-slate-200/50 dark:border-slate-700/50 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/30 dark:to-slate-900/20 text-slate-700 dark:text-slate-300 text-xs font-bold uppercase focus:ring-slate-500 focus:border-slate-400 cursor-pointer shadow-sm transition-all">
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Pilih
                                Cabang (Opsional)</label>
                            <select wire:model="selectedCabangKomparasi"
                                class="w-full px-4 py-3 rounded-xl border-2 border-slate-200/50 dark:border-slate-700/50 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800/30 dark:to-slate-900/20 text-slate-700 dark:text-slate-300 text-xs font-bold uppercase focus:ring-slate-500 focus:border-slate-400 cursor-pointer shadow-sm transition-all">
                                <option value="">Semua Cabang</option>
                                @foreach($cabangOptions as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 grid grid-cols-1 sm:grid-cols-2 gap-4 relative z-10 content-center">
                    <button wire:click="cetakKomparasi('omzet')" wire:loading.attr="disabled"
                        class="group relative p-6 rounded-2xl border-2 border-red-200/60 dark:border-red-500/30 hover:border-red-300 bg-gradient-to-br from-red-50 to-red-100/50 hover:from-red-100 hover:to-red-200/70 dark:bg-red-500/5 dark:hover:bg-red-600/10 transition-all text-left shadow-md hover:shadow-lg hover:-translate-y-1 h-full flex flex-col justify-between cursor-pointer">
                        <div class="flex justify-between items-start mb-5">
                            <div class="w-12 h-12 rounded-xl bg-red-200/60 dark:bg-red-600/40 text-red-700 dark:text-red-300 flex items-center justify-center shadow-lg border border-red-300/50 dark:border-red-500/50">
                                <i class="fas fa-chart-area text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-black text-xs text-red-900 dark:text-red-300 uppercase tracking-widest mb-1">Jual vs Retur</h4>
                            <p class="text-[10px] text-red-800/70 dark:text-red-400/70 font-medium">Analisa Perbandingan</p>
                        </div>
                        <div wire:loading wire:target="cetakKomparasi('omzet')" class="absolute inset-0 bg-white/80 dark:bg-black/80 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-spinner fa-spin text-red-600 dark:text-red-400 text-xl"></i>
                        </div>
                    </button>

                    <button wire:click="cetakKomparasi('cashflow')" wire:loading.attr="disabled"
                        class="group relative p-6 rounded-2xl border-2 border-emerald-200/60 dark:border-emerald-500/30 hover:border-emerald-300 bg-gradient-to-br from-emerald-50 to-emerald-100/50 hover:from-emerald-100 hover:to-emerald-200/70 dark:bg-emerald-500/5 dark:hover:bg-emerald-600/10 transition-all text-left shadow-md hover:shadow-lg hover:-translate-y-1 h-full flex flex-col justify-between cursor-pointer">
                        <div class="flex justify-between items-start mb-5">
                            <div class="w-12 h-12 rounded-xl bg-emerald-200/60 dark:bg-emerald-600/40 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shadow-lg border border-emerald-300/50 dark:border-emerald-500/50">
                                <i class="fas fa-money-bill-transfer text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-black text-xs text-emerald-900 dark:text-emerald-300 uppercase tracking-widest mb-1">Piutang vs Lunas</h4>
                            <p class="text-[10px] text-emerald-800/70 dark:text-emerald-400/70 font-medium">Analisa Perbandingan</p>
                        </div>
                        <div wire:loading wire:target="cetakKomparasi('cashflow')" class="absolute inset-0 bg-white/80 dark:bg-black/80 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-spinner fa-spin text-emerald-600 dark:text-emerald-400 text-xl"></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        {{-- SECTION 3: ANALISA STRATEGIS --}}
        <div class="flex items-center gap-4 mb-6 mt-10">
            <div class="h-1 w-16 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-full"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 px-4 py-2 bg-slate-100/50 dark:bg-white/5 rounded-full border border-slate-200/50 dark:border-white/10">Analisa Strategis & Keuangan</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">

            {{-- CARD 1: VALUASI STOK --}}
            <div x-data="{ dropdownActive: false }" :class="dropdownActive ? 'z-[100] relative' : 'z-10 relative'"
                class="bg-white dark:bg-slate-900/50 rounded-3xl border border-slate-200/50 dark:border-white/10 p-2 shadow-lg hover:shadow-xl transition-all h-full group">
                <div class="p-8 bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-900/10 dark:to-slate-900/30 rounded-3xl h-full flex flex-col relative">
                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 text-white flex items-center justify-center text-2xl shadow-lg border border-blue-500/30">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Valuasi Stok</h3>
                    </div>

                    <div class="space-y-5 flex-grow relative z-20">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Cabang
                                Penyimpanan</label>
                            <select wire:model.live="selectedCabangStok"
                                class="w-full px-4 py-3 rounded-xl border-2 border-blue-200/50 dark:border-blue-500/30 bg-gradient-to-r from-blue-50 to-white dark:from-blue-900/10 dark:to-slate-900/20 text-slate-700 dark:text-slate-300 text-xs font-bold uppercase focus:ring-blue-500 focus:border-blue-500 cursor-pointer shadow-sm transition-all">
                                @foreach($cabangOptions as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                            </select>
                        </div>

                        {{-- Multi-Select Supplier Stok --}}
                        <div class="space-y-1.5 relative transition-all"
                            x-data="{ open: false, selected: @entangle('selectedSupplierStok').live, options: @entangle('supplierOptionsStok'), search: '', get filtered() { if(this.search === '') return this.options; return this.options.filter(i => i.toLowerCase().includes(this.search.toLowerCase())) } }"
                            x-init="$watch('open', value => dropdownActive = value)">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih
                                Supplier (Filter)</label>
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:bg-[#1a1a1a] dark:border-white/10 text-left flex justify-between items-center bg-white text-xs font-bold uppercase cursor-pointer shadow-inner">
                                    <span
                                        x-text="selected.length > 0 ? selected.length + ' Vendor Dipilih' : 'Semua Pemasok'"
                                        class="truncate text-slate-800 dark:text-white"></span>
                                    <i class="fas fa-chevron-down text-slate-400 text-[10px]"></i>
                                </button>
                                <div x-show="open" x-transition style="display: none;"
                                    class="absolute z-[100] mt-1 w-full bg-white dark:bg-[#27272a] rounded-2xl shadow-2xl border border-slate-100 dark:border-white/10 p-2 max-h-64 overflow-y-auto custom-scrollbar ring-1 ring-black/5">
                                    <input x-model="search" type="text" placeholder="Cari nama supplier..."
                                        class="w-full mb-2 pl-3 py-2 rounded-lg border-slate-200 dark:border-white/10 dark:bg-[#18181b] text-slate-800 dark:text-white text-xs font-bold uppercase focus:ring-blue-500">
                                    <template x-for="s in filtered" :key="s">
                                        <label
                                            class="flex items-center px-3 py-2 hover:bg-blue-50 dark:hover:bg-white/5 rounded-xl cursor-pointer transition-colors">
                                            <input type="checkbox" :value="s" x-model="selected"
                                                class="rounded border-slate-300 dark:border-slate-500 dark:bg-[#18181b] text-blue-600 focus:ring-blue-500 w-4 h-4 mr-3 cursor-pointer focus:ring-offset-0">
                                            <span
                                                class="text-[10px] font-bold uppercase text-slate-700 dark:text-slate-200 tracking-tight"
                                                x-text="s"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Multi-Select Produk Stok --}}
                        <div class="space-y-1.5 relative transition-all"
                            x-data="{ open: false, selected: @entangle('selectedProductStok').live, options: @entangle('productOptionsStok'), search: '', get filtered() { if(!this.options) return []; if(this.search === '') return this.options.slice(0, 50); return this.options.filter(i => i.name_item.toLowerCase().includes(this.search.toLowerCase())) } }"
                            x-init="$watch('open', value => dropdownActive = value)">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih
                                Produk Spesifik</label>
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:bg-[#1a1a1a] dark:border-white/10 text-left flex justify-between items-center bg-white text-xs font-bold uppercase cursor-pointer shadow-inner">
                                    <span
                                        x-text="selected.length > 0 ? selected.length + ' Produk Dipilih' : 'Semua Produk'"
                                        class="truncate text-slate-800 dark:text-white"></span>
                                    <i class="fas fa-chevron-down text-slate-400 text-[10px]"></i>
                                </button>
                                <div x-show="open" x-transition style="display: none;"
                                    class="absolute z-[100] mt-1 w-full bg-white dark:bg-[#27272a] rounded-2xl shadow-2xl border border-slate-100 dark:border-white/10 p-2 max-h-64 overflow-y-auto custom-scrollbar ring-1 ring-black/5">
                                    <input x-model="search" type="text" placeholder="Cari nama item..."
                                        class="w-full mb-2 pl-3 py-2 rounded-lg border-slate-200 dark:border-white/10 dark:bg-[#18181b] text-slate-800 dark:text-white text-xs font-bold uppercase focus:ring-blue-500">
                                    <template x-for="item in filtered" :key="item.sku">
                                        <label
                                            class="flex items-center px-3 py-2 hover:bg-blue-50 dark:hover:bg-white/5 rounded-xl cursor-pointer group transition-colors">
                                            <input type="checkbox" :value="item.sku" x-model="selected"
                                                class="rounded border-slate-300 dark:border-slate-500 dark:bg-[#18181b] text-blue-600 focus:ring-blue-500 w-4 h-4 mr-3 cursor-pointer focus:ring-offset-0">
                                            <div class="text-[10px] font-bold uppercase tracking-tight text-slate-700 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-blue-400"
                                                x-text="item.name_item"></div>
                                        </label>
                                    </template>
                                    <div x-show="filtered.length === 0"
                                        class="text-center py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                        Produk tidak ditemukan</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 relative z-10 pt-6 border-t border-blue-200/30 dark:border-blue-500/20">
                        <button wire:click="cetakStok" wire:loading.attr="disabled"
                            class="w-full p-4 group relative bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-lg shadow-blue-600/30 hover:shadow-xl active:scale-95 transition-all cursor-pointer flex justify-center items-center gap-3 border border-blue-500/50 hover:border-blue-400">
                            <span wire:loading.remove wire:target="cetakStok"><i class="fas fa-file-pdf mr-2"></i>Cetak Valuasi Stok</span>
                            <span wire:loading wire:target="cetakStok"><i class="fas fa-spinner fa-spin mr-2 animate-spin"></i>Menyusun Data...</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- CARD 2: ANALISA MARGIN --}}
            <div x-data="{ dropdownActive: false }" :class="dropdownActive ? 'z-[100] relative' : 'z-10 relative'"
                class="bg-white dark:bg-slate-900/50 rounded-3xl border border-slate-200/50 dark:border-white/10 p-2 shadow-lg hover:shadow-xl transition-all h-full group">
                <div
                    class="p-8 bg-gradient-to-br from-rose-50 to-rose-100/50 dark:from-rose-900/10 dark:to-slate-900/30 rounded-3xl h-full flex flex-col relative">
                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-600 to-rose-700 text-white flex items-center justify-center text-2xl shadow-lg border border-rose-500/30">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Analisa Laba (Profit)</h3>
                    </div>

                    <div class="space-y-5 flex-grow relative z-20">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Cabang
                                Penjualan</label>
                            <select wire:model.live="selectedCabangProfit"
                                class="w-full px-4 py-3 rounded-xl border-slate-200 dark:bg-[#1a1a1a] dark:border-white/10 text-slate-800 dark:text-white text-xs font-bold uppercase focus:ring-rose-500 cursor-pointer shadow-inner">
                                @foreach($cabangOptions as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5 relative transition-all"
                            x-data="{ open: false, selected: @entangle('selectedSupplierProfit').live, options: @entangle('supplierOptionsProfit'), search: '', get filtered() { if(this.search === '') return this.options; return this.options.filter(i => i.toLowerCase().includes(this.search.toLowerCase())) } }"
                            x-init="$watch('open', value => dropdownActive = value)">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih
                                Supplier</label>
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:bg-[#1a1a1a] dark:border-white/10 text-left flex justify-between items-center bg-white text-xs font-bold uppercase cursor-pointer shadow-inner">
                                    <span
                                        x-text="selected.length > 0 ? selected.length + ' Vendor Dipilih' : 'Semua Pemasok'"
                                        class="truncate text-slate-800 dark:text-white"></span>
                                    <i class="fas fa-chevron-down text-slate-400 text-[10px]"></i>
                                </button>
                                <div x-show="open" x-transition style="display: none;"
                                    class="absolute z-[100] mt-1 w-full bg-white dark:bg-[#27272a] rounded-2xl shadow-2xl border border-slate-100 dark:border-white/10 p-2 max-h-64 overflow-y-auto custom-scrollbar ring-1 ring-black/5">
                                    <input x-model="search" type="text" placeholder="Cari supplier..."
                                        class="w-full mb-2 pl-3 py-2 rounded-lg border-slate-200 dark:border-white/10 dark:bg-[#18181b] text-slate-800 dark:text-white text-xs font-bold uppercase focus:ring-rose-500">
                                    <template x-for="s in filtered" :key="s">
                                        <label
                                            class="flex items-center px-3 py-2 hover:bg-rose-50 dark:hover:bg-white/5 rounded-xl cursor-pointer transition-colors">
                                            <input type="checkbox" :value="s" x-model="selected"
                                                class="rounded border-slate-300 dark:border-slate-500 dark:bg-[#18181b] text-rose-600 focus:ring-rose-500 w-4 h-4 mr-3 cursor-pointer focus:ring-offset-0">
                                            <span
                                                class="text-[10px] font-bold uppercase text-slate-700 dark:text-slate-200 tracking-tight"
                                                x-text="s"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5 relative transition-all"
                            x-data="{ open: false, selected: @entangle('selectedProductProfit').live, options: @entangle('productOptionsProfit'), search: '', get filtered() { if(!this.options) return []; if(this.search === '') return this.options.slice(0, 50); return this.options.filter(i => i.name_item.toLowerCase().includes(this.search.toLowerCase())) } }"
                            x-init="$watch('open', value => dropdownActive = value)">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih
                                Produk</label>
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:bg-[#1a1a1a] dark:border-white/10 text-left flex justify-between items-center bg-white text-xs font-bold uppercase cursor-pointer shadow-inner">
                                    <span
                                        x-text="selected.length > 0 ? selected.length + ' Produk Dipilih' : 'Semua Produk'"
                                        class="truncate text-slate-800 dark:text-white"></span>
                                    <i class="fas fa-chevron-down text-slate-400 text-[10px]"></i>
                                </button>
                                <div x-show="open" x-transition style="display: none;"
                                    class="absolute z-[100] mt-1 w-full bg-white dark:bg-[#27272a] rounded-2xl shadow-2xl border border-slate-100 dark:border-white/10 p-2 max-h-64 overflow-y-auto custom-scrollbar ring-1 ring-black/5">
                                    <input x-model="search" type="text" placeholder="Cari nama produk..."
                                        class="w-full mb-2 pl-3 py-2 rounded-lg border-slate-200 dark:border-white/10 dark:bg-[#18181b] text-slate-800 dark:text-white text-xs font-bold uppercase focus:ring-rose-500">
                                    <template x-for="item in filtered" :key="item.sku">
                                        <label
                                            class="flex items-center px-3 py-2 hover:bg-rose-50 dark:hover:bg-white/5 rounded-xl cursor-pointer group transition-colors">
                                            <input type="checkbox" :value="item.sku" x-model="selected"
                                                class="rounded border-slate-300 dark:border-slate-500 dark:bg-[#18181b] text-rose-600 focus:ring-rose-500 w-4 h-4 mr-3 cursor-pointer focus:ring-offset-0">
                                            <div class="text-[10px] font-bold uppercase tracking-tight text-slate-700 dark:text-slate-200 group-hover:text-rose-600 dark:group-hover:text-rose-400"
                                                x-text="item.name_item"></div>
                                        </label>
                                    </template>
                                    <div x-show="filtered.length === 0"
                                        class="text-center py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                        Produk tidak ditemukan</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 relative z-10 pt-6 border-t border-rose-200/30 dark:border-rose-500/20">
                        <button wire:click="cetakProfit" wire:loading.attr="disabled"
                            class="w-full p-4 group relative bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-700 hover:to-rose-800 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-lg shadow-rose-600/30 hover:shadow-xl active:scale-95 transition-all cursor-pointer flex justify-center items-center gap-3 border border-rose-500/50 hover:border-rose-400">
                            <span wire:loading.remove wire:target="cetakProfit"><i class="fas fa-file-pdf mr-2"></i>Cetak Laporan Margin</span>
                            <span wire:loading wire:target="cetakProfit"><i class="fas fa-spinner fa-spin mr-2 animate-spin"></i>Menyusun Data...</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        {{-- SECTION 4: ANALITIK CERDAS (SPK & RFM) --}}
        <div class="flex items-center gap-4 mb-6 mt-10">
            <div class="h-1 w-16 bg-gradient-to-r from-indigo-600 to-fuchsia-600 rounded-full"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 px-4 py-2 bg-slate-100/50 dark:bg-white/5 rounded-full border border-slate-200/50 dark:border-white/10">Analitik Cerdas - Sistem Pendukung Keputusan</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10 mb-8">

            {{-- SPK Kinerja Sales --}}
            <div
                class="bg-white dark:bg-slate-900/50 rounded-3xl border border-slate-200/50 dark:border-white/10 p-8 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between border-l-[5px] border-l-indigo-500 group">
                <div>
                    <div class="flex items-center gap-5 mb-8">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-700 text-white flex items-center justify-center text-2xl shadow-lg border border-indigo-500/30">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">
                                Kinerja Sales</h3>
                            <p
                                class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mt-1">
                                Metode SPK SAW</p>
                        </div>
                    </div>
                    <div class="flex gap-3 mb-8">
                        <select wire:model="bulan"
                            class="w-full px-4 py-3 rounded-xl border-2 border-indigo-200/50 dark:border-indigo-500/30 bg-gradient-to-r from-indigo-50 to-white dark:from-indigo-900/10 dark:to-slate-900/20 text-slate-700 dark:text-slate-300 text-xs font-bold uppercase focus:ring-indigo-500 cursor-pointer shadow-sm transition-all">
                            @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option> @endfor
                        </select>
                        <select wire:model="tahun"
                            class="w-24 px-4 py-3 rounded-xl border-2 border-indigo-200/50 dark:border-indigo-500/30 bg-gradient-to-r from-indigo-50 to-white dark:from-indigo-900/10 dark:to-slate-900/20 text-slate-700 dark:text-slate-300 text-xs font-bold uppercase focus:ring-indigo-500 cursor-pointer shadow-sm transition-all">
                            @for($y=date('Y')-1; $y<=date('Y')+1; $y++) <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <button wire:click="cetakSpkSales" wire:loading.attr="disabled"
                    class="w-full p-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-lg shadow-indigo-600/30 hover:shadow-xl transition-all flex items-center justify-center gap-3 cursor-pointer active:scale-95 border border-indigo-500/50 hover:border-indigo-400">
                    <span wire:loading.remove wire:target="cetakSpkSales"><i class="fas fa-brain mr-2"></i>Eksekusi Algoritma</span>
                    <span wire:loading wire:target="cetakSpkSales"><i class="fas fa-spinner fa-spin mr-2 animate-spin"></i>Memproses...</span>
                </button>
            </div>

            {{-- Segmentasi RFM --}}
            <div
                class="bg-white dark:bg-slate-900/50 rounded-3xl border border-slate-200/50 dark:border-white/10 p-8 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between border-l-[5px] border-l-fuchsia-500 group">
                <div>
                    <div class="flex items-center gap-5 mb-8">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-fuchsia-600 to-fuchsia-700 text-white flex items-center justify-center text-2xl shadow-lg border border-fuchsia-500/30">
                            <i class="fas fa-users-viewfinder"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">
                                Segmentasi Loyalitas</h3>
                            <p
                                class="text-[10px] font-bold text-fuchsia-600 dark:text-fuchsia-400 uppercase tracking-widest mt-1">
                                Algoritma RFM</p>
                        </div>
                    </div>
                    <div class="flex gap-3 mb-8">
                        <select wire:model="bulan"
                            class="w-full px-4 py-3 rounded-xl border-2 border-fuchsia-200/50 dark:border-fuchsia-500/30 bg-gradient-to-r from-fuchsia-50 to-white dark:from-fuchsia-900/10 dark:to-slate-900/20 text-slate-700 dark:text-slate-300 text-xs font-bold uppercase focus:ring-fuchsia-500 cursor-pointer shadow-sm transition-all">
                            @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option> @endfor
                        </select>
                        <select wire:model="tahun"
                            class="w-24 px-4 py-3 rounded-xl border-2 border-fuchsia-200/50 dark:border-fuchsia-500/30 bg-gradient-to-r from-fuchsia-50 to-white dark:from-fuchsia-900/10 dark:to-slate-900/20 text-slate-700 dark:text-slate-300 text-xs font-bold uppercase focus:ring-fuchsia-500 cursor-pointer shadow-sm transition-all">
                            @for($y=date('Y')-1; $y<=date('Y')+1; $y++) <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <button wire:click="cetakRfmPelanggan" wire:loading.attr="disabled"
                    class="w-full p-4 bg-gradient-to-r from-fuchsia-600 to-fuchsia-700 hover:from-fuchsia-700 hover:to-fuchsia-800 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-lg shadow-fuchsia-600/30 hover:shadow-xl transition-all flex items-center justify-center gap-3 cursor-pointer active:scale-95 border border-fuchsia-500/50 hover:border-fuchsia-400">
                    <span wire:loading.remove wire:target="cetakRfmPelanggan"><i class="fas fa-users mr-2"></i>Analisa Konsumen</span>
                    <span wire:loading wire:target="cetakRfmPelanggan"><i class="fas fa-spinner fa-spin mr-2 animate-spin"></i>Memproses...</span>
                </button>
            </div>

        </div>

    </div>
</div>