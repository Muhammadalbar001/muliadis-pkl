<div class="min-h-screen space-y-8 pb-20 font-jakarta bg-slate-50 dark:bg-[#050505] transition-colors duration-300">

    {{-- HEADER --}}
    <div
        class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-5 mb-8 dark:bg-slate-950/95 dark:border-white/5 bg-white/98 border-slate-100 shadow-lg flex flex-col gap-4">
        <div class="flex flex-col xl:flex-row gap-6 items-start lg:items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto shrink-0">
                <div
                    class="p-3 rounded-2xl shadow-xl bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-500 text-white ring-4 ring-indigo-500/20">
                    <i class="fas fa-print text-2xl font-bold"></i>
                </div>
                <div>
                    <h1
                        class="text-2xl font-black tracking-tight uppercase leading-none dark:text-white text-slate-900">
                        Pusat Cetak <span
                            class="bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">Laporan</span>
                    </h1>
                    <p
                        class="text-[11px] font-bold uppercase tracking-[0.15em] mt-1.5 dark:text-slate-400 text-slate-500">
                        Manajemen Cetak Terpusat & Strategis</p>
                </div>
            </div>
            <div class="flex items-center gap-2 w-full xl:w-auto justify-end">
                <span
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 text-[10px] font-bold text-indigo-700 dark:text-indigo-300 border border-indigo-200/50 shadow-sm">
                    <i class="fas fa-info-circle text-indigo-600"></i> SPK, Data Mining & Laporan Operasional
                </span>
            </div>
        </div>
    </div>

    {{-- AREA KONTEN UTAMA --}}
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto animate-fade-in space-y-8">

        {{-- SECTION 1: RAPOR KINERJA SALES --}}
        <div>
            <div class="flex items-center gap-4 mb-6">
                <div class="h-1 w-16 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-full"></div>
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 px-4 py-2 bg-slate-100/50 dark:bg-white/5 rounded-full border border-slate-200/50">8
                    Laporam Utama Kinerja Salesman</span>
            </div>

            <div
                class="bg-white dark:bg-slate-900/50 rounded-3xl border border-slate-200/50 dark:border-white/10 shadow-lg p-2">
                <div class="grid grid-cols-1 lg:grid-cols-12" x-data="{ dropdownActive: false }">

                    {{-- SIDEBAR FILTER SALES --}}
                    <div :class="dropdownActive ? 'z-[100] relative' : 'z-10 relative'"
                        class="lg:col-span-3 bg-gradient-to-b from-slate-50 to-white dark:from-slate-900/30 dark:to-slate-900/50 p-8 border-b lg:border-b-0 lg:border-r border-slate-200/50 dark:border-white/5 flex flex-col justify-start rounded-l-3xl">
                        <div class="mb-6 pb-4 border-b border-slate-200/50 dark:border-white/5">
                            <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight">
                                Parameter Filter</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Periode
                                    Bulan</label>
                                <div class="flex gap-2">
                                    <select wire:model="bulan"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-[11px] font-bold uppercase dark:text-white shadow-inner cursor-pointer">
                                        @for($i=1; $i<=12; $i++) <option value="{{ sprintf('%02d', $i) }}">
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                            @endfor
                                    </select>
                                    <select wire:model="tahun"
                                        class="w-24 px-3 py-2 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-[11px] font-bold uppercase dark:text-white shadow-inner cursor-pointer">
                                        @for($y=date('Y')-1; $y<=date('Y'); $y++) <option value="{{ $y }}">{{ $y }}
                                            </option> @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Filter
                                    Area</label>
                                <select wire:model.live="selectedCabangSales"
                                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-[11px] font-bold uppercase dark:text-white shadow-inner cursor-pointer">
                                    <option value="Semua Cabang">Semua Cabang</option>
                                    @foreach($cabangOptions as $c) <option value="{{ $c }}">{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Syarat
                                    Nota (EC)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-[10px] font-bold text-slate-400">Rp</span>
                                    <input type="number" wire:model="minNominal"
                                        class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-[11px] font-bold dark:text-white shadow-inner">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- GRID TOMBOL CETAK (8 LAPORAN) --}}
                    <div
                        class="lg:col-span-9 p-8 bg-gradient-to-br from-white to-slate-50/30 dark:from-slate-900/40 dark:to-slate-950 rounded-r-3xl">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                            {{-- 1. Kinerja Penjualan --}}
                            <button wire:click="cetakSales('penjualan')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-gradient-to-br from-emerald-50/50 to-emerald-100/30 hover:from-emerald-100 border-emerald-200/60 dark:border-emerald-500/20 text-emerald-900 dark:text-emerald-400 dark:bg-emerald-500/5 cursor-pointer flex flex-col justify-between h-40">
                                <div
                                    class="w-10 h-10 rounded-xl bg-emerald-200/50 dark:bg-emerald-600/40 flex items-center justify-center mb-4 border border-emerald-300/30">
                                    <i class="fas fa-chart-line text-emerald-700 dark:text-emerald-300"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1">Pencapaian Target</h4>
                                    <p class="text-[10px] font-medium opacity-70">Target vs Realisasi Bersih</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('penjualan')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-emerald-600"></i>
                            </button>

                            {{-- 2. Monitoring Kredit --}}
                            <button wire:click="cetakSales('ar')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-gradient-to-br from-orange-50/50 to-orange-100/30 hover:from-orange-100 border-orange-200/60 dark:border-orange-500/20 text-orange-900 dark:text-orange-400 dark:bg-orange-500/5 cursor-pointer flex flex-col justify-between h-40">
                                <div
                                    class="w-10 h-10 rounded-xl bg-orange-200/50 dark:bg-orange-600/40 flex items-center justify-center mb-4 border border-orange-300/30">
                                    <i class="fas fa-file-invoice-dollar text-orange-700 dark:text-orange-300"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1">Monitoring Kredit</h4>
                                    <p class="text-[10px] font-medium opacity-70">Rasio Manajemen Piutang Macet</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('ar')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-orange-600"></i>
                            </button>

                            {{-- 3. Produktivitas --}}
                            <button wire:click="cetakSales('produktifitas')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-gradient-to-br from-blue-50/50 to-blue-100/30 hover:from-blue-100 border-blue-200/60 dark:border-blue-500/20 text-blue-900 dark:text-blue-400 dark:bg-blue-500/5 cursor-pointer flex flex-col justify-between h-40">
                                <div
                                    class="w-10 h-10 rounded-xl bg-blue-200/50 dark:bg-blue-600/40 flex items-center justify-center mb-4 border border-blue-300/30">
                                    <i class="fas fa-users-viewfinder text-blue-700 dark:text-blue-300"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1">Produktivitas
                                        Kunjungan</h4>
                                    <p class="text-[10px] font-medium opacity-70">Analisa Efektivitas OA / EC</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('produktifitas')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-blue-600"></i>
                            </button>

                            {{-- 4. Supplier --}}
                            <button wire:click="cetakSales('supplier')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-gradient-to-br from-purple-50/50 to-purple-100/30 hover:from-purple-100 border-purple-200/60 dark:border-purple-500/20 text-purple-900 dark:text-purple-400 dark:bg-purple-500/5 cursor-pointer flex flex-col justify-between h-40">
                                <div
                                    class="w-10 h-10 rounded-xl bg-purple-200/50 dark:bg-purple-600/40 flex items-center justify-center mb-4 border border-purple-300/30">
                                    <i class="fas fa-boxes-stacked text-purple-700 dark:text-purple-300"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1">Kinerja per Supplier
                                    </h4>
                                    <p class="text-[10px] font-medium opacity-70">Matriks Sebaran Penjualan Brand</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('supplier')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-purple-600"></i>
                            </button>

                            {{-- ==================================================== --}}
                            {{-- 4 INTEGRASI LAPORAN BARU BERSKALA SKRIPSI UNTUK SIDANG --}}
                            {{-- ==================================================== --}}

                            {{-- 5. Kinerja Segmentasi --}}
                            <button wire:click="cetakSales('segmentasi')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-gradient-to-br from-indigo-50/50 to-fuchsia-100/30 hover:from-fuchsia-100 border-fuchsia-200/60 dark:border-fuchsia-500/20 text-indigo-900 dark:text-fuchsia-400 dark:bg-fuchsia-500/5 cursor-pointer flex flex-col justify-between h-40">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-200/50 dark:bg-fuchsia-600/40 flex items-center justify-center mb-4 border border-fuchsia-300/30">
                                    <i class="fas fa-project-diagram text-indigo-700 dark:text-fuchsia-300"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1">Kinerja Segmentasi
                                    </h4>
                                    <p class="text-[10px] font-medium opacity-70">Analisa Toko VIP vs Toko Pasif</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('segmentasi')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-indigo-600"></i>
                            </button>

                            {{-- 6. Kualitas Penjualan --}}
                            <button wire:click="cetakSales('kualitas')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-gradient-to-br from-rose-50/50 to-red-100/30 hover:from-red-100 border-red-200/60 dark:border-red-500/20 text-rose-900 dark:text-red-400 dark:bg-red-500/5 cursor-pointer flex flex-col justify-between h-40">
                                <div
                                    class="w-10 h-10 rounded-xl bg-red-200/50 dark:bg-red-600/40 flex items-center justify-center mb-4 border border-red-300/30">
                                    <i class="fas fa-undo-alt text-red-700 dark:text-red-300"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1">Kualitas Penjualan
                                    </h4>
                                    <p class="text-[10px] font-medium opacity-70">Analisa Kebijakan & Rasio Retur</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('kualitas')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-rose-600"></i>
                            </button>

                            {{-- 7. Efisiensi Penagihan --}}
                            <button wire:click="cetakSales('efisiensi')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-gradient-to-br from-cyan-50/50 to-teal-100/30 hover:from-teal-100 border-teal-200/60 dark:border-teal-500/20 text-cyan-900 dark:text-teal-400 dark:bg-teal-500/5 cursor-pointer flex flex-col justify-between h-40">
                                <div
                                    class="w-10 h-10 rounded-xl bg-cyan-200/50 dark:bg-teal-600/40 flex items-center justify-center mb-4 border border-teal-300/30">
                                    <i class="fas fa-wallet text-cyan-700 dark:text-teal-300"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1">Efisiensi Penagihan
                                    </h4>
                                    <p class="text-[10px] font-medium opacity-70">Rasio Likuiditas & Uang Masuk</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('efisiensi')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-cyan-600"></i>
                            </button>

                            {{-- 8. Akuisisi Toko Baru --}}
                            <button wire:click="cetakSales('akuisisi')" wire:loading.attr="disabled"
                                class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl bg-gradient-to-br from-amber-50/50 to-orange-100/30 hover:from-orange-100 border-orange-200/60 dark:border-orange-500/20 text-amber-900 dark:text-orange-400 dark:bg-orange-500/5 cursor-pointer flex flex-col justify-between h-40">
                                <div
                                    class="w-10 h-10 rounded-xl bg-amber-200/50 dark:bg-orange-600/40 flex items-center justify-center mb-4 border border-orange-300/30">
                                    <i class="fas fa-seedling text-amber-700 dark:text-orange-300"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1">Akuisisi Toko Baru
                                    </h4>
                                    <p class="text-[10px] font-medium opacity-70">Rasio Pertumbuhan Pasar Baru</p>
                                </div>
                                <i wire:loading wire:target="cetakSales('akuisisi')"
                                    class="fas fa-spinner fa-spin absolute top-4 right-4 text-amber-600"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: AUDIT & KOMPARASI HARIAN --}}
        {{-- ... Kode Komparasi Harian di bawahnya tetap berfungsi normal ... --}}
    </div>
</div>