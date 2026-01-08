<div class="min-h-screen space-y-8 pb-20 font-jakarta bg-slate-50 dark:bg-[#050505] transition-colors duration-300">

    {{-- HERO HEADER --}}
    <div
        class="relative bg-white dark:bg-[#121212] border-b border-slate-200 dark:border-white/5 pt-10 pb-8 px-6 shadow-sm overflow-hidden">
        <div class="absolute top-0 right-0 p-10 opacity-5 dark:opacity-[0.02]">
            <i class="fas fa-print text-9xl transform rotate-12"></i>
        </div>
        <div class="max-w-7xl mx-auto relative z-10">
            <h1 class="text-3xl font-black uppercase text-slate-800 dark:text-white tracking-tighter mb-2">
                Pusat Cetak <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Laporan</span>
            </h1>
            <p
                class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] max-w-2xl leading-relaxed">
                Manajemen Cetak Terpusat: Sales, Stok, dan Profitabilitas dengan Filter Presisi.
            </p>
        </div>
    </div>

    <div class="px-6 max-w-7xl mx-auto">

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
                <div class="grid grid-cols-1 lg:grid-cols-12">

                    {{-- SIDEBAR FILTER SALES --}}
                    <div
                        class="lg:col-span-3 bg-slate-50 dark:bg-white/5 p-8 border-b lg:border-b-0 lg:border-r border-slate-200 dark:border-white/5 flex flex-col justify-start rounded-t-[2rem] lg:rounded-tr-none lg:rounded-l-[2rem]">
                        <div class="mb-6">
                            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase">Filter Sales</h3>
                            <p class="text-[10px] text-slate-400">Gunakan pencarian untuk memilih sales.</p>
                        </div>

                        <div class="space-y-5">
                            {{-- Periode --}}
                            <div class="space-y-1">
                                <label
                                    class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Periode</label>
                                <div class="flex gap-2">
                                    <select wire:model="bulan"
                                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-emerald-500">
                                        @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                            @endfor
                                    </select>
                                    <select wire:model="tahun"
                                        class="w-24 px-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-emerald-500">
                                        @for($y=date('Y')-1; $y<=date('Y')+1; $y++) <option value="{{ $y }}">{{ $y }}
                                            </option> @endfor
                                    </select>
                                </div>
                            </div>

                            {{-- Cabang Sales --}}
                            <div class="space-y-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Cabang
                                    Sales</label>
                                <select wire:model.live="selectedCabangSales"
                                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-emerald-500">
                                    <option value="Semua Cabang">Semua Cabang</option>
                                    @foreach($cabangOptions as $c) <option value="{{ $c }}">{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- MULTI SELECT SALESMAN (ALPINE) --}}
                            <div class="space-y-1 relative z-50" x-data="{
                                open: false,
                                search: '',
                                selected: @entangle('selectedSalesIds').live,
                                options: @entangle('salesOptions'),
                                get filteredOptions() {
                                    if(this.search === '') return this.options;
                                    return this.options.filter(i => i.sales_name.toLowerCase().includes(this.search.toLowerCase()))
                                }
                            }">
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Pilih Sales
                                    (Cari & Centang)</label>
                                <div class="relative">
                                    <button @click="open = !open" @click.outside="open = false" type="button"
                                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-left flex justify-between items-center bg-white dark:bg-transparent text-xs font-bold shadow-sm focus:ring-2 focus:ring-emerald-500 transition-all">
                                        <span
                                            x-text="selected.length > 0 ? selected.length + ' Sales Dipilih' : 'Pilih Sales...'"
                                            class="truncate text-slate-700 dark:text-slate-200"></span>
                                        <i class="fas fa-chevron-down opacity-50 text-[10px]"></i>
                                    </button>

                                    <div x-show="open" x-transition.origin.top
                                        class="absolute z-[100] w-[120%] -left-1 mt-2 bg-white dark:bg-[#1a1a1a] rounded-xl shadow-2xl border border-slate-200 dark:border-white/10 p-3 ring-1 ring-black/5">
                                        <input x-model="search" type="text" placeholder="Cari sales..."
                                            class="w-full mb-2 pl-3 pr-3 py-2 rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 text-xs font-bold uppercase">
                                        <div class="max-h-60 overflow-y-auto custom-scrollbar space-y-1">
                                            <template x-for="item in filteredOptions" :key="item.id">
                                                <label
                                                    class="flex items-center px-2 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 rounded-lg cursor-pointer transition-colors group">
                                                    <input type="checkbox" :value="item.id" x-model="selected"
                                                        class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4 mr-3 cursor-pointer">
                                                    <div>
                                                        <div class="text-[10px] font-bold uppercase text-slate-700 dark:text-slate-200"
                                                            x-text="item.sales_name"></div>
                                                        <div class="text-[9px] text-slate-400" x-text="item.sales_code">
                                                        </div>
                                                    </div>
                                                </label>
                                            </template>
                                            <div x-show="filteredOptions.length === 0"
                                                class="text-center py-2 text-[10px] text-slate-400">Tidak ditemukan.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Tags --}}
                                @if(!empty($selectedSalesIds))
                                <div class="flex flex-wrap gap-1.5 mt-2 max-h-20 overflow-y-auto custom-scrollbar">
                                    <template x-for="id in selected" :key="id">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-white border border-slate-200 dark:bg-white/5 dark:border-white/10 text-slate-600 dark:text-slate-300 text-[9px] font-bold uppercase shadow-sm">
                                            <span
                                                x-text="options.find(o => o.id == id)?.sales_name.substring(0, 10) + '...'"></span>
                                            <button @click="selected = selected.filter(i => i != id)"
                                                class="hover:text-red-500 ml-1"><i class="fas fa-times"></i></button>
                                        </span>
                                    </template>
                                </div>
                                @endif
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Min.
                                    Nota</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-[10px] font-bold text-slate-400">Rp</span>
                                    <input type="number" wire:model="minNominal"
                                        class="w-full pl-8 pr-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold focus:ring-emerald-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BUTTONS (Right) --}}
                    <div
                        class="lg:col-span-9 p-8 bg-white dark:bg-[#121212] rounded-b-[2rem] lg:rounded-bl-none lg:rounded-r-[2rem]">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 h-full content-center">
                            <button wire:click="cetakSales('penjualan')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-emerald-50 border-emerald-100 text-emerald-800 dark:bg-emerald-500/5 dark:border-emerald-500/20 dark:text-emerald-400">
                                <div
                                    class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fas fa-chart-line text-6xl"></i></div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-sm uppercase mb-1">Kinerja Penjualan</h4>
                                    <p class="text-[10px] opacity-70">Target vs Realisasi.</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('penjualan')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-emerald-600"></i>
                            </button>
                            <button wire:click="cetakSales('ar')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-orange-50 border-orange-100 text-orange-800 dark:bg-orange-500/5 dark:border-orange-500/20 dark:text-orange-400">
                                <div
                                    class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fas fa-file-invoice-dollar text-6xl"></i></div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-sm uppercase mb-1">Monitoring Kredit</h4>
                                    <p class="text-[10px] opacity-70">Piutang Macet.</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('ar')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-orange-600"></i>
                            </button>
                            <button wire:click="cetakSales('supplier')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-purple-50 border-purple-100 text-purple-800 dark:bg-purple-500/5 dark:border-purple-500/20 dark:text-purple-400">
                                <div
                                    class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fas fa-boxes-packing text-6xl"></i></div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-sm uppercase mb-1">Sales by Supplier</h4>
                                    <p class="text-[10px] opacity-70">Kontribusi Supplier.</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('supplier')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-purple-600"></i>
                            </button>
                            <button wire:click="cetakSales('produktifitas')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-cyan-50 border-cyan-100 text-cyan-800 dark:bg-cyan-500/5 dark:border-cyan-500/20 dark:text-cyan-400">
                                <div
                                    class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fas fa-stopwatch text-6xl"></i></div>
                                <div class="relative z-10">
                                    <h4 class="font-black text-sm uppercase mb-1">Produktivitas</h4>
                                    <p class="text-[10px] opacity-70">Efektivitas OA/EC.</p>
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
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Audit & Komparasi</span>
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
        </div>

        <div
            class="bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-lg p-6 relative overflow-hidden group mb-8 z-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-3 relative z-10 flex flex-col justify-center">
                    <div
                        class="w-12 h-12 rounded-2xl bg-slate-800 text-white flex items-center justify-center text-xl shadow-lg mb-4">
                        <i class="fas fa-scale-balanced"></i></div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase leading-tight">Analisa
                        <br>Perbandingan</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">Cetak laporan perbandingan tren harian dengan
                        filter periode presisi.</p>
                </div>
                <div
                    class="lg:col-span-5 relative z-10 bg-slate-50 dark:bg-white/5 rounded-xl p-5 border border-slate-100 dark:border-white/5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Rentang Tanggal</label>
                            <div class="flex items-center gap-2">
                                <input type="date" wire:model="tglMulaiKomparasi"
                                    class="w-full px-3 py-2 rounded-lg border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-slate-500">
                                <span class="text-slate-400 text-xs font-bold">s/d</span>
                                <input type="date" wire:model="tglSelesaiKomparasi"
                                    class="w-full px-3 py-2 rounded-lg border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-slate-500">
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Pilih Cabang
                                (Opsional)</label>
                            <select wire:model="selectedCabangKomparasi"
                                class="w-full px-3 py-2 rounded-lg border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-slate-500">
                                <option value="">Semua Cabang</option>
                                @foreach($cabangOptions as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-4 grid grid-cols-1 sm:grid-cols-2 gap-4 relative z-10 content-center">
                    <button wire:click="cetakKomparasi('omzet')" wire:loading.attr="disabled"
                        class="group relative p-4 rounded-xl border border-slate-200 hover:border-red-200 bg-white hover:bg-red-50 dark:bg-white/5 dark:hover:bg-red-900/20 dark:border-white/10 transition-all text-left shadow-sm hover:shadow-md h-full flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-2">
                            <div
                                class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                <i class="fas fa-chart-area"></i></div>
                        </div>
                        <div>
                            <h4 class="font-black text-xs text-slate-700 dark:text-slate-200 uppercase">Jual vs Retur
                            </h4>
                        </div>
                        <div wire:loading wire:target="cetakKomparasi('omzet')"
                            class="absolute inset-0 bg-white/80 dark:bg-black/80 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-spinner fa-spin text-red-600"></i></div>
                    </button>
                    <button wire:click="cetakKomparasi('cashflow')" wire:loading.attr="disabled"
                        class="group relative p-4 rounded-xl border border-slate-200 hover:border-emerald-200 bg-white hover:bg-emerald-50 dark:bg-white/5 dark:hover:bg-emerald-900/20 dark:border-white/10 transition-all text-left shadow-sm hover:shadow-md h-full flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-2">
                            <div
                                class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <i class="fas fa-money-bill-transfer"></i></div>
                        </div>
                        <div>
                            <h4 class="font-black text-xs text-slate-700 dark:text-slate-200 uppercase">Piutang vs Lunas
                            </h4>
                        </div>
                        <div wire:loading wire:target="cetakKomparasi('cashflow')"
                            class="absolute inset-0 bg-white/80 dark:bg-black/80 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-spinner fa-spin text-emerald-600"></i></div>
                    </button>
                </div>
            </div>
        </div>

        {{-- SECTION 3: ANALISA STRATEGIS --}}
        <div class="flex items-center gap-4 mb-4">
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Analisa Strategis &
                Keuangan</span>
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">

            {{-- CARD: VALUASI STOK --}}
            <div
                class="bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/5 p-1 shadow-lg group hover:shadow-xl transition-all h-full relative z-10">
                <div
                    class="p-6 bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/10 dark:to-[#121212] rounded-[1.8rem] h-full flex flex-col relative">
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl shadow-lg">
                            <i class="fas fa-cubes"></i></div>
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase">Valuasi Stok</h3>
                    </div>

                    <div class="space-y-4 flex-grow relative z-20">
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Cabang</label>
                            <select wire:model.live="selectedCabangStok"
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 dark:bg-black/20 dark:border-white/10 text-xs font-bold uppercase focus:ring-blue-500">
                                @foreach($cabangOptions as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                            </select>
                        </div>

                        {{-- Multi-Select Supplier --}}
                        <div class="space-y-1 relative z-30"
                            x-data="{ open: false, selected: @entangle('selectedSupplierStok').live, options: @entangle('supplierOptionsStok'), search: '', get filtered() { if(this.search === '') return this.options; return this.options.filter(i => i.toLowerCase().includes(this.search.toLowerCase())) } }">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Pilih Supplier (Muncul
                                Produk)</label>
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:bg-black/20 dark:border-white/10 text-left flex justify-between items-center bg-white dark:bg-transparent text-xs font-bold uppercase">
                                    <span
                                        x-text="selected.length > 0 ? selected.length + ' Dipilih' : 'Semua Pemasok'"></span>
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </button>
                                <div x-show="open" x-transition
                                    class="absolute z-50 mt-1 w-full bg-white dark:bg-[#1a1a1a] rounded-xl shadow-2xl border border-slate-100 dark:border-white/10 p-2 max-h-60 overflow-y-auto custom-scrollbar">
                                    <input x-model="search" type="text" placeholder="Cari supplier..."
                                        class="w-full mb-2 pl-3 py-1.5 rounded-lg border-slate-200 dark:bg-white/5 text-xs font-bold uppercase">
                                    <template x-for="s in filtered" :key="s">
                                        <label
                                            class="flex items-center px-2 py-1.5 hover:bg-blue-50 dark:hover:bg-white/5 rounded-lg cursor-pointer">
                                            <input type="checkbox" :value="s" x-model="selected"
                                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4 mr-2">
                                            <span
                                                class="text-[10px] font-bold uppercase text-slate-700 dark:text-slate-300"
                                                x-text="s"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Multi-Select Produk (Dependent) --}}
                        <div class="space-y-1 relative z-20"
                            x-data="{ open: false, selected: @entangle('selectedProductStok').live, options: @entangle('productOptionsStok'), search: '', get filtered() { if(!this.options) return []; if(this.search === '') return this.options.slice(0, 50); return this.options.filter(i => i.name_item.toLowerCase().includes(this.search.toLowerCase())) } }">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Pilih Produk
                                (Multi)</label>
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:bg-black/20 dark:border-white/10 text-left flex justify-between items-center bg-white dark:bg-transparent text-xs font-bold uppercase">
                                    <span
                                        x-text="selected.length > 0 ? selected.length + ' Produk Dipilih' : 'Semua Produk'"></span>
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </button>
                                <div x-show="open" x-transition
                                    class="absolute z-50 mt-1 w-[120%] -left-2 bg-white dark:bg-[#1a1a1a] rounded-xl shadow-2xl border border-slate-100 dark:border-white/10 p-3 max-h-64 overflow-y-auto custom-scrollbar">
                                    <input x-model="search" type="text" placeholder="Cari produk..."
                                        class="w-full mb-2 pl-3 py-1.5 rounded-lg border-slate-200 dark:bg-white/5 text-xs font-bold uppercase">
                                    <template x-for="item in filtered" :key="item.sku">
                                        <label
                                            class="flex items-center px-2 py-1.5 hover:bg-blue-50 dark:hover:bg-white/5 rounded-lg cursor-pointer">
                                            <input type="checkbox" :value="item.sku" x-model="selected"
                                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4 mr-2">
                                            <span
                                                class="text-[10px] font-bold uppercase text-slate-700 dark:text-slate-300"
                                                x-text="item.name_item"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 relative z-10">
                        <button wire:click="cetakStok" wire:loading.attr="disabled"
                            class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg active:scale-95 transition-all">
                            <span wire:loading.remove wire:target="cetakStok"><i class="fas fa-print mr-1"></i> Cetak
                                Valuasi</span>
                            <span wire:loading wire:target="cetakStok"><i class="fas fa-spinner fa-spin mr-1"></i>
                                Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- CARD: ANALISA MARGIN --}}
            <div
                class="bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/5 p-1 shadow-lg group hover:shadow-xl transition-all h-full relative z-10">
                <div
                    class="p-6 bg-gradient-to-br from-rose-50 to-white dark:from-rose-900/10 dark:to-[#121212] rounded-[1.8rem] h-full flex flex-col relative">
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center text-xl shadow-lg">
                            <i class="fas fa-coins"></i></div>
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase">Analisa Margin</h3>
                    </div>

                    <div class="space-y-4 flex-grow relative z-20">
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Cabang</label>
                            <select wire:model.live="selectedCabangProfit"
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 dark:bg-black/20 dark:border-white/10 text-xs font-bold uppercase focus:ring-rose-500">
                                @foreach($cabangOptions as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                            </select>
                        </div>

                        <div class="space-y-1 relative z-30"
                            x-data="{ open: false, selected: @entangle('selectedSupplierProfit').live, options: @entangle('supplierOptionsProfit'), search: '', get filtered() { if(this.search === '') return this.options; return this.options.filter(i => i.toLowerCase().includes(this.search.toLowerCase())) } }">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Supplier</label>
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:bg-black/20 dark:border-white/10 text-left flex justify-between items-center bg-white dark:bg-transparent text-xs font-bold uppercase">
                                    <span
                                        x-text="selected.length > 0 ? selected.length + ' Dipilih' : 'Semua Pemasok'"></span>
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </button>
                                <div x-show="open" x-transition
                                    class="absolute z-50 mt-1 w-full bg-white dark:bg-[#1a1a1a] rounded-xl shadow-2xl border border-slate-100 dark:border-white/10 p-2 max-h-60 overflow-y-auto custom-scrollbar">
                                    <input x-model="search" type="text" placeholder="Cari supplier..."
                                        class="w-full mb-2 pl-3 py-1.5 rounded-lg border-slate-200 dark:bg-white/5 text-xs font-bold uppercase">
                                    <template x-for="s in filtered" :key="s">
                                        <label
                                            class="flex items-center px-3 py-2 hover:bg-rose-50 dark:hover:bg-white/5 rounded-lg cursor-pointer transition-colors">
                                            <input type="checkbox" :value="s" x-model="selected"
                                                class="rounded border-slate-300 text-rose-600 focus:ring-rose-500 w-4 h-4 mr-3">
                                            <span
                                                class="text-[10px] font-bold uppercase text-slate-700 dark:text-slate-300"
                                                x-text="s"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1 relative z-20"
                            x-data="{ open: false, selected: @entangle('selectedProductProfit').live, options: @entangle('productOptionsProfit'), search: '', get filtered() { if(!this.options) return []; if(this.search === '') return this.options.slice(0, 50); return this.options.filter(i => i.name_item.toLowerCase().includes(this.search.toLowerCase())) } }">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Pilih Produk</label>
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:bg-black/20 dark:border-white/10 text-left flex justify-between items-center bg-white dark:bg-transparent text-xs font-bold uppercase">
                                    <span
                                        x-text="selected.length > 0 ? selected.length + ' Produk Dipilih' : 'Semua Produk'"></span>
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </button>
                                <div x-show="open" x-transition
                                    class="absolute z-50 mt-1 w-[120%] -left-2 bg-white dark:bg-[#1a1a1a] rounded-xl shadow-2xl border border-slate-100 dark:border-white/10 p-3 max-h-64 overflow-y-auto custom-scrollbar">
                                    <input x-model="search" type="text" placeholder="Cari nama produk..."
                                        class="w-full mb-2 pl-3 py-1.5 rounded-lg border-slate-200 dark:bg-white/5 text-xs font-bold uppercase">
                                    <template x-for="item in filtered" :key="item.sku">
                                        <label
                                            class="flex items-center px-2 py-2 hover:bg-rose-50 dark:hover:bg-white/5 rounded-lg cursor-pointer group">
                                            <input type="checkbox" :value="item.sku" x-model="selected"
                                                class="rounded border-slate-300 text-rose-600 focus:ring-rose-500 w-4 h-4 mr-3">
                                            <div class="text-[10px] font-bold uppercase text-slate-700 dark:text-slate-300 group-hover:text-rose-600 dark:group-hover:text-rose-400"
                                                x-text="item.name_item"></div>
                                        </label>
                                    </template>
                                    <div x-show="filteredOptions.length === 0"
                                        class="text-center py-2 text-[10px] text-slate-400">Produk tidak ditemukan</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 relative z-10">
                        <button wire:click="cetakProfit" wire:loading.attr="disabled"
                            class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg active:scale-95 transition-all">
                            <span wire:loading.remove wire:target="cetakProfit"><i
                                    class="fas fa-file-invoice-dollar mr-1"></i> Cetak Profit</span>
                            <span wire:loading wire:target="cetakProfit"><i class="fas fa-spinner fa-spin mr-1"></i>
                                Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>