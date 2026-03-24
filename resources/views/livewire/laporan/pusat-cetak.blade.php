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
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/10 bg-white/95 border-slate-300 shadow-md flex flex-col gap-4">

        <div class="flex flex-col xl:flex-row gap-6 items-center justify-between">
            {{-- Logo & Judul --}}
            <div class="flex items-center gap-4 w-full xl:w-auto shrink-0">
                <div
                    class="p-3 rounded-2xl shadow-xl bg-gradient-to-br from-indigo-600 to-blue-700 text-white ring-4 ring-indigo-500/20">
                    <i class="fas fa-print text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                        Pusat Cetak <span class="text-indigo-600 dark:text-indigo-400">Laporan</span>
                    </h1>
                    <p
                        class="text-[10px] font-extrabold uppercase tracking-[0.2em] mt-1.5 dark:text-slate-400 text-slate-600">
                        Manajemen Cetak Terpusat
                    </p>
                </div>
            </div>

            {{-- Info Tambahan Kanan --}}
            <div class="flex items-center gap-2 w-full xl:w-auto justify-end">
                <span
                    class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-white/5 text-[10px] font-bold text-slate-500 dark:text-slate-400 flex items-center gap-2 border border-slate-200 dark:border-white/5">
                    <i class="fas fa-info-circle text-indigo-500"></i> Operasional, Keuangan & Analitik AI
                </span>
            </div>
        </div>
    </div>

    {{-- AREA KONTEN UTAMA --}}
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto animate-fade-in">

        {{-- SECTION 1: KINERJA SALES --}}
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Laporan Operasional &
                    Sales</span>
                <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
            </div>

            <div
                class="bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-xl shadow-slate-200/50 dark:shadow-none relative z-30">
                <div class="grid grid-cols-1 lg:grid-cols-12" x-data="{ dropdownActive: false }">

                    {{-- SIDEBAR FILTER SALES --}}
                    <div :class="dropdownActive ? 'z-[100] relative' : 'z-10 relative'"
                        class="lg:col-span-3 bg-slate-50 dark:bg-white/5 p-8 border-b lg:border-b-0 lg:border-r border-slate-200 dark:border-white/5 flex flex-col justify-start rounded-t-[2rem] lg:rounded-tr-none lg:rounded-l-[2rem] transition-all">
                        <div class="mb-6">
                            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">
                                Filter Laporan</h3>
                            <p class="text-[10px] font-medium text-slate-400 mt-1">Gunakan parameter di bawah.</p>
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
                    <div
                        class="lg:col-span-9 p-8 bg-white dark:bg-[#121212] rounded-b-[2rem] lg:rounded-bl-none lg:rounded-r-[2rem] relative z-10">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 h-full content-center">

                            <button wire:click="cetakSales('penjualan')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-emerald-50 border-emerald-100 text-emerald-800 dark:bg-emerald-500/5 dark:border-emerald-500/20 dark:text-emerald-400 cursor-pointer flex flex-col justify-between">
                                <div
                                    class="absolute right-0 bottom-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity group-hover:scale-110">
                                    <i class="fas fa-chart-line text-6xl"></i>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-4 border border-emerald-200 dark:border-emerald-800/30">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-[11px] uppercase tracking-widest mb-1">Kinerja Penjualan
                                    </h4>
                                    <p class="text-[10px] font-medium opacity-70">Target vs Realisasi</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('penjualan')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-emerald-600"></i>
                            </button>

                            <button wire:click="cetakSales('ar')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-orange-50 border-orange-100 text-orange-800 dark:bg-orange-500/5 dark:border-orange-500/20 dark:text-orange-400 cursor-pointer flex flex-col justify-between">
                                <div
                                    class="absolute right-0 bottom-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity group-hover:scale-110">
                                    <i class="fas fa-file-invoice-dollar text-6xl"></i>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center mb-4 border border-orange-200 dark:border-orange-800/30">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-[11px] uppercase tracking-widest mb-1">Monitoring Kredit
                                    </h4>
                                    <p class="text-[10px] font-medium opacity-70">Risiko Piutang Macet</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('ar')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-orange-600"></i>
                            </button>

                            <button wire:click="cetakSales('supplier')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-purple-50 border-purple-100 text-purple-800 dark:bg-purple-500/5 dark:border-purple-500/20 dark:text-purple-400 cursor-pointer flex flex-col justify-between">
                                <div
                                    class="absolute right-0 bottom-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity group-hover:scale-110">
                                    <i class="fas fa-boxes-stacked text-6xl"></i>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-4 border border-purple-200 dark:border-purple-800/30">
                                    <i class="fas fa-boxes-stacked"></i>
                                </div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-[11px] uppercase tracking-widest mb-1">Sales by Supplier
                                    </h4>
                                    <p class="text-[10px] font-medium opacity-70">Kontribusi Penjualan</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('supplier')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-purple-600"></i>
                            </button>

                            <button wire:click="cetakSales('produktifitas')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-cyan-50 border-cyan-100 text-cyan-800 dark:bg-cyan-500/5 dark:border-cyan-500/20 dark:text-cyan-400 cursor-pointer flex flex-col justify-between">
                                <div
                                    class="absolute right-0 bottom-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity group-hover:scale-110">
                                    <i class="fas fa-users-viewfinder text-6xl"></i>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center mb-4 border border-cyan-200 dark:border-cyan-800/30">
                                    <i class="fas fa-users-viewfinder"></i>
                                </div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-[11px] uppercase tracking-widest mb-1">Produktivitas</h4>
                                    <p class="text-[10px] font-medium opacity-70">Efektivitas OA / EC</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('produktifitas')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-cyan-600"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: AUDIT & KOMPARASI --}}
        <div class="flex items-center gap-4 mb-4 mt-8">
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Audit & Komparasi
                Kinerja</span>
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
        </div>

        <div
            class="bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-xl p-6 relative group mb-8 z-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <div class="lg:col-span-3 relative z-10 flex flex-col justify-center">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 text-white flex items-center justify-center text-xl shadow-lg mb-4 border border-slate-600">
                        <i class="fas fa-scale-balanced"></i>
                    </div>
                    <h3
                        class="text-xl font-black text-slate-800 dark:text-white uppercase leading-tight tracking-tighter">
                        Analisa <br>Perbandingan</h3>
                    <p class="text-[10px] font-medium text-slate-500 mt-2 leading-relaxed">Cetak laporan perbandingan
                        tren operasional dengan filter rentang waktu yang presisi.</p>
                </div>

                <div
                    class="lg:col-span-5 relative z-10 bg-slate-50 dark:bg-white/5 rounded-[1.5rem] p-6 border border-slate-100 dark:border-white/5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Rentang
                                Tanggal Khusus</label>
                            <div class="flex items-center gap-3">
                                <input type="date" wire:model="tglMulaiKomparasi"
                                    class="w-full px-4 py-2.5 rounded-xl border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-indigo-500 cursor-pointer shadow-inner dark:text-white transition-all">
                                <span class="text-slate-400 text-[10px] font-black uppercase">s/d</span>
                                <input type="date" wire:model="tglSelesaiKomparasi"
                                    class="w-full px-4 py-2.5 rounded-xl border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-indigo-500 cursor-pointer shadow-inner dark:text-white transition-all">
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Pilih
                                Cabang (Opsional)</label>
                            <select wire:model="selectedCabangKomparasi"
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-indigo-500 cursor-pointer shadow-inner dark:text-white transition-all">
                                <option value="">Semua Cabang</option>
                                @foreach($cabangOptions as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 grid grid-cols-1 sm:grid-cols-2 gap-4 relative z-10 content-center">
                    <button wire:click="cetakKomparasi('omzet')" wire:loading.attr="disabled"
                        class="group relative p-5 rounded-[1.5rem] border border-slate-200 hover:border-red-300 bg-white hover:bg-red-50 dark:bg-[#1a1a1a] dark:hover:bg-red-900/20 dark:border-white/5 transition-all text-left shadow-sm hover:shadow-lg h-full flex flex-col justify-between cursor-pointer">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400 flex items-center justify-center shadow-inner">
                                <i class="fas fa-chart-area"></i>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="font-black text-[11px] text-slate-700 dark:text-slate-200 uppercase tracking-widest">
                                Jual vs Retur</h4>
                        </div>
                        <div wire:loading wire:target="cetakKomparasi('omzet')"
                            class="absolute inset-0 bg-white/80 dark:bg-black/80 rounded-[1.5rem] flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-spinner fa-spin text-red-600"></i></div>
                    </button>

                    <button wire:click="cetakKomparasi('cashflow')" wire:loading.attr="disabled"
                        class="group relative p-5 rounded-[1.5rem] border border-slate-200 hover:border-emerald-300 bg-white hover:bg-emerald-50 dark:bg-[#1a1a1a] dark:hover:bg-emerald-900/20 dark:border-white/5 transition-all text-left shadow-sm hover:shadow-lg h-full flex flex-col justify-between cursor-pointer">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 flex items-center justify-center shadow-inner">
                                <i class="fas fa-money-bill-transfer"></i>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="font-black text-[11px] text-slate-700 dark:text-slate-200 uppercase tracking-widest">
                                Piutang vs Lunas</h4>
                        </div>
                        <div wire:loading wire:target="cetakKomparasi('cashflow')"
                            class="absolute inset-0 bg-white/80 dark:bg-black/80 rounded-[1.5rem] flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-spinner fa-spin text-emerald-600"></i></div>
                    </button>
                </div>
            </div>
        </div>

        {{-- SECTION 3: ANALISA STRATEGIS --}}
        <div class="flex items-center gap-4 mb-4 mt-8">
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Analisa Strategis &
                Keuangan</span>
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">

            {{-- CARD 1: VALUASI STOK --}}
            <div x-data="{ dropdownActive: false }" :class="dropdownActive ? 'z-[100] relative' : 'z-10 relative'"
                class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/5 p-1 shadow-xl group transition-all h-full">
                <div
                    class="p-8 bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/10 dark:to-[#121212] rounded-[2.3rem] h-full flex flex-col relative">
                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl shadow-lg border border-transparent dark:border-blue-500/30">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">Valuasi
                            Stok</h3>
                    </div>

                    <div class="space-y-5 flex-grow relative z-20">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Cabang
                                Penyimpanan</label>
                            <select wire:model.live="selectedCabangStok"
                                class="w-full px-4 py-3 rounded-xl border-slate-200 dark:bg-[#1a1a1a] dark:border-white/10 text-slate-800 dark:text-white text-xs font-bold uppercase focus:ring-blue-500 cursor-pointer shadow-inner">
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

                    <div class="mt-8 relative z-10 pt-4 border-t border-slate-200 dark:border-white/5">
                        <button wire:click="cetakStok" wire:loading.attr="disabled"
                            class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/20 active:scale-95 transition-all cursor-pointer flex justify-center items-center gap-2">
                            <span wire:loading.remove wire:target="cetakStok"><i class="fas fa-print mr-1"></i> Cetak
                                Laporan Valuasi</span>
                            <span wire:loading wire:target="cetakStok"><i class="fas fa-spinner fa-spin mr-1"></i>
                                Menyusun Data...</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- CARD 2: ANALISA MARGIN --}}
            <div x-data="{ dropdownActive: false }" :class="dropdownActive ? 'z-[100] relative' : 'z-10 relative'"
                class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/5 p-1 shadow-xl group transition-all h-full">
                <div
                    class="p-8 bg-gradient-to-br from-rose-50 to-white dark:from-rose-900/10 dark:to-[#121212] rounded-[2.3rem] h-full flex flex-col relative">
                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center text-xl shadow-lg border border-transparent dark:border-rose-500/30">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">Analisa
                            Laba (Profit)</h3>
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

                    <div class="mt-8 relative z-10 pt-4 border-t border-slate-200 dark:border-white/5">
                        <button wire:click="cetakProfit" wire:loading.attr="disabled"
                            class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 active:scale-95 transition-all cursor-pointer flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="cetakProfit"><i class="fas fa-print mr-1"></i> Cetak
                                Laporan Margin</span>
                            <span wire:loading wire:target="cetakProfit"><i class="fas fa-spinner fa-spin mr-1"></i>
                                Menyusun Data...</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        {{-- SECTION 4: ANALITIK CERDAS (SPK & RFM) --}}
        <div class="flex items-center gap-4 mb-4 mt-8">
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Sistem Pendukung Keputusan
                (AI)</span>
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10 mb-8">

            {{-- SPK Kinerja Sales --}}
            <div
                class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/5 p-8 shadow-xl flex flex-col justify-between hover:shadow-2xl transition-all border-l-[6px] border-l-indigo-500">
                <div>
                    <div class="flex items-center gap-5 mb-8">
                        <div
                            class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 flex items-center justify-center text-2xl shadow-inner border border-indigo-200 dark:border-indigo-500/30">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">
                                Kinerja Sales</h3>
                            <p
                                class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mt-1">
                                Metode SPK SAW</p>
                        </div>
                    </div>
                    <div class="flex gap-3 mb-8">
                        <select wire:model="bulan"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 dark:bg-[#1a1a1a] dark:border-white/10 text-xs font-bold uppercase focus:ring-indigo-500 cursor-pointer shadow-inner">
                            @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option> @endfor
                        </select>
                        <select wire:model="tahun"
                            class="w-24 px-4 py-3 rounded-xl border-slate-200 dark:bg-[#1a1a1a] dark:border-white/10 text-xs font-bold uppercase focus:ring-indigo-500 cursor-pointer shadow-inner">
                            @for($y=date('Y')-1; $y<=date('Y')+1; $y++) <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <button wire:click="cetakSpkSales" wire:loading.attr="disabled"
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                    <span wire:loading.remove wire:target="cetakSpkSales"><i class="fas fa-print mr-1"></i> Eksekusi
                        Algoritma (SPK)</span>
                    <span wire:loading wire:target="cetakSpkSales"><i class="fas fa-spinner fa-spin mr-1"></i> Memproses
                        Data...</span>
                </button>
            </div>

            {{-- Segmentasi RFM --}}
            <div
                class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/5 p-8 shadow-xl flex flex-col justify-between hover:shadow-2xl transition-all border-l-[6px] border-l-fuchsia-500">
                <div>
                    <div class="flex items-center gap-5 mb-8">
                        <div
                            class="w-14 h-14 rounded-2xl bg-fuchsia-100 text-fuchsia-600 dark:bg-fuchsia-500/20 dark:text-fuchsia-400 flex items-center justify-center text-2xl shadow-inner border border-fuchsia-200 dark:border-fuchsia-500/30">
                            <i class="fas fa-users-viewfinder"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">
                                Segmentasi Loyalitas</h3>
                            <p
                                class="text-[10px] font-black text-fuchsia-600 dark:text-fuchsia-400 uppercase tracking-widest mt-1">
                                Algoritma RFM</p>
                        </div>
                    </div>
                    <div class="flex gap-3 mb-8">
                        <select wire:model="bulan"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 dark:bg-[#1a1a1a] dark:border-white/10 text-xs font-bold uppercase focus:ring-fuchsia-500 cursor-pointer shadow-inner">
                            @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option> @endfor
                        </select>
                        <select wire:model="tahun"
                            class="w-24 px-4 py-3 rounded-xl border-slate-200 dark:bg-[#1a1a1a] dark:border-white/10 text-xs font-bold uppercase focus:ring-fuchsia-500 cursor-pointer shadow-inner">
                            @for($y=date('Y')-1; $y<=date('Y')+1; $y++) <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <button wire:click="cetakRfmPelanggan" wire:loading.attr="disabled"
                    class="w-full py-4 bg-fuchsia-600 hover:bg-fuchsia-700 text-white rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-fuchsia-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                    <span wire:loading.remove wire:target="cetakRfmPelanggan"><i class="fas fa-print mr-1"></i> Analisa
                        Perilaku Konsumen</span>
                    <span wire:loading wire:target="cetakRfmPelanggan"><i class="fas fa-spinner fa-spin mr-1"></i>
                        Memproses Data...</span>
                </button>
            </div>

        </div>

    </div>
</div>