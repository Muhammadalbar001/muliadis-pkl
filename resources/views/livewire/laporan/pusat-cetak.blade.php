<div class="min-h-screen space-y-8 pb-20 font-jakarta bg-slate-50 dark:bg-[#050505] transition-colors duration-300">

    {{-- 1. HERO HEADER --}}
    <div
        class="relative bg-white dark:bg-[#121212] border-b border-slate-200 dark:border-white/5 pt-10 pb-8 px-6 shadow-sm overflow-hidden">
        {{-- Background Pattern Decoration --}}
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
                Pilih kategori laporan, atur filter periode atau cabang, dan unduh dokumen PDF resmi perusahaan dalam
                satu klik.
            </p>
        </div>
    </div>

    <div class="px-6 max-w-7xl mx-auto">

        {{-- SECTION 1: KINERJA SALES (MAIN REPORT) --}}
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Laporan Operasional &
                    Sales</span>
                <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
            </div>

            <div
                class="bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12">

                    {{-- Sidebar Filter (Left) --}}
                    <div
                        class="lg:col-span-3 bg-slate-50 dark:bg-white/5 p-8 border-b lg:border-b-0 lg:border-r border-slate-200 dark:border-white/5 flex flex-col justify-center">
                        <div class="mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 text-xl shadow-sm">
                                <i class="fas fa-users-viewfinder"></i>
                            </div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase leading-tight">Filter
                                <br>Kinerja Sales</h3>
                            <p class="text-[10px] text-slate-500 mt-2">Atur parameter sebelum mencetak laporan sales.
                            </p>
                        </div>

                        <div class="space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Periode
                                    Laporan</label>
                                <div class="flex gap-2">
                                    <select wire:model="bulan"
                                        class="w-full px-4 py-3 rounded-xl border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                                        @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                            @endfor
                                    </select>
                                    <select wire:model="tahun"
                                        class="w-28 px-4 py-3 rounded-xl border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                                        @for($y=date('Y')-1; $y<=date('Y')+1; $y++) <option value="{{ $y }}">{{ $y }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Min. Nota
                                    (Produktifitas)</label>
                                <div class="relative group">
                                    <span
                                        class="absolute left-4 top-3 text-xs font-bold text-slate-400 group-focus-within:text-emerald-500">Rp</span>
                                    <input type="number" wire:model="minNominal"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10 text-xs font-bold focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Grid (Right) --}}
                    <div class="lg:col-span-9 p-8 bg-white dark:bg-[#121212]">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4 block">Pilih
                            Jenis Laporan</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                            {{-- Button 1: Penjualan --}}
                            <button wire:click="cetakSales('penjualan')" wire:loading.attr="disabled" class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl
                                bg-emerald-50 border-emerald-100 hover:border-emerald-300
                                dark:bg-emerald-500/5 dark:border-emerald-500/20 dark:hover:border-emerald-500/50">
                                <div
                                    class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fas fa-chart-line text-6xl text-emerald-600"></i>
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-sm mb-4 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <h4
                                        class="font-black text-sm text-emerald-900 dark:text-emerald-100 uppercase tracking-tight">
                                        Kinerja Penjualan</h4>
                                    <p
                                        class="text-[10px] text-emerald-600/80 dark:text-emerald-400/70 mt-1 font-medium leading-relaxed">
                                        Rapor target vs realisasi per salesman.
                                    </p>
                                </div>
                                {{-- Loading Indicator --}}
                                <div wire:loading wire:target="cetakSales('penjualan')"
                                    class="absolute inset-0 bg-white/80 dark:bg-black/80 flex items-center justify-center backdrop-blur-sm z-20">
                                    <i class="fas fa-circle-notch fa-spin text-emerald-600 text-xl"></i>
                                </div>
                            </button>

                            {{-- Button 2: Kredit --}}
                            <button wire:click="cetakSales('ar')" wire:loading.attr="disabled" class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl
                                bg-orange-50 border-orange-100 hover:border-orange-300
                                dark:bg-orange-500/5 dark:border-orange-500/20 dark:hover:border-orange-500/50">
                                <div
                                    class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fas fa-file-invoice-dollar text-6xl text-orange-600"></i>
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white dark:bg-orange-500/20 flex items-center justify-center text-orange-600 dark:text-orange-400 shadow-sm mb-4 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                    <h4
                                        class="font-black text-sm text-orange-900 dark:text-orange-100 uppercase tracking-tight">
                                        Monitoring Kredit</h4>
                                    <p
                                        class="text-[10px] text-orange-600/80 dark:text-orange-400/70 mt-1 font-medium leading-relaxed">
                                        Analisa piutang macet & lancar.
                                    </p>
                                </div>
                                <div wire:loading wire:target="cetakSales('ar')"
                                    class="absolute inset-0 bg-white/80 dark:bg-black/80 flex items-center justify-center backdrop-blur-sm z-20">
                                    <i class="fas fa-circle-notch fa-spin text-orange-600 text-xl"></i>
                                </div>
                            </button>

                            {{-- Button 3: Supplier --}}
                            <button wire:click="cetakSales('supplier')" wire:loading.attr="disabled" class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl
                                bg-purple-50 border-purple-100 hover:border-purple-300
                                dark:bg-purple-500/5 dark:border-purple-500/20 dark:hover:border-purple-500/50">
                                <div
                                    class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fas fa-boxes-packing text-6xl text-purple-600"></i>
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white dark:bg-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400 shadow-sm mb-4 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-truck-loading"></i>
                                    </div>
                                    <h4
                                        class="font-black text-sm text-purple-900 dark:text-purple-100 uppercase tracking-tight">
                                        Sales by Supplier</h4>
                                    <p
                                        class="text-[10px] text-purple-600/80 dark:text-purple-400/70 mt-1 font-medium leading-relaxed">
                                        Matriks kontribusi supplier per sales.
                                    </p>
                                </div>
                                <div wire:loading wire:target="cetakSales('supplier')"
                                    class="absolute inset-0 bg-white/80 dark:bg-black/80 flex items-center justify-center backdrop-blur-sm z-20">
                                    <i class="fas fa-circle-notch fa-spin text-purple-600 text-xl"></i>
                                </div>
                            </button>

                            {{-- Button 4: Produktivitas --}}
                            <button wire:click="cetakSales('produktifitas')" wire:loading.attr="disabled" class="relative overflow-hidden p-6 rounded-2xl border transition-all duration-300 group text-left hover:-translate-y-1 hover:shadow-xl
                                bg-cyan-50 border-cyan-100 hover:border-cyan-300
                                dark:bg-cyan-500/5 dark:border-cyan-500/20 dark:hover:border-cyan-500/50">
                                <div
                                    class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fas fa-stopwatch text-6xl text-cyan-600"></i>
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white dark:bg-cyan-500/20 flex items-center justify-center text-cyan-600 dark:text-cyan-400 shadow-sm mb-4 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-user-clock"></i>
                                    </div>
                                    <h4
                                        class="font-black text-sm text-cyan-900 dark:text-cyan-100 uppercase tracking-tight">
                                        Produktivitas</h4>
                                    <p
                                        class="text-[10px] text-cyan-600/80 dark:text-cyan-400/70 mt-1 font-medium leading-relaxed">
                                        Analisa OA & Efektivitas kunjungan.
                                    </p>
                                </div>
                                <div wire:loading wire:target="cetakSales('produktifitas')"
                                    class="absolute inset-0 bg-white/80 dark:bg-black/80 flex items-center justify-center backdrop-blur-sm z-20">
                                    <i class="fas fa-circle-notch fa-spin text-cyan-600 text-xl"></i>
                                </div>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: ANALISA STRATEGIS (Stock & Profit) --}}
        <div class="flex items-center gap-4 mb-4">
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Analisa Strategis &
                Keuangan</span>
            <div class="h-px bg-slate-200 dark:bg-white/10 flex-1"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- CARD: ANALISA STOK --}}
            <div
                class="bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/5 p-1 shadow-lg shadow-slate-200/50 dark:shadow-none hover:shadow-xl transition-shadow group">
                <div
                    class="p-6 bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/10 dark:to-[#121212] rounded-[1.8rem] h-full flex flex-col relative overflow-hidden">
                    {{-- Decor --}}
                    <div
                        class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-colors">
                    </div>

                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl shadow-lg shadow-blue-600/30">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase">Valuasi Stok</h3>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Analisa nilai aset
                                gudang.</p>
                        </div>
                    </div>

                    <div class="space-y-4 flex-grow relative z-10">
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Cabang</label>
                            <select wire:model.live="selectedCabangStok"
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 dark:bg-black/20 dark:border-white/10 text-xs font-bold uppercase focus:ring-blue-500">
                                <option value="">-- Pilih Cabang --</option>
                                @foreach($cabangOptions as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Supplier
                                (Opsional)</label>
                            <select wire:model="selectedSupplierStok"
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 dark:bg-black/20 dark:border-white/10 text-xs font-bold uppercase focus:ring-blue-500">
                                <option value="">Semua Pemasok</option>
                                @foreach($supplierOptionsStok as $s)
                                <option value="{{ $s }}">{{ Str::limit($s, 30) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 relative z-10">
                        <button wire:click="cetakStok" wire:loading.attr="disabled"
                            class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="cetakStok">
                                <i class="fas fa-print mr-1"></i> Cetak Valuasi
                            </span>
                            <span wire:loading wire:target="cetakStok">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- CARD: LABA RUGI --}}
            <div
                class="bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/5 p-1 shadow-lg shadow-slate-200/50 dark:shadow-none hover:shadow-xl transition-shadow group">
                <div
                    class="p-6 bg-gradient-to-br from-rose-50 to-white dark:from-rose-900/10 dark:to-[#121212] rounded-[1.8rem] h-full flex flex-col relative overflow-hidden">
                    {{-- Decor --}}
                    <div
                        class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl group-hover:bg-rose-500/20 transition-colors">
                    </div>

                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center text-xl shadow-lg shadow-rose-600/30">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase">Analisa Margin</h3>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Laporan laba rugi &
                                profitabilitas.</p>
                        </div>
                    </div>

                    <div class="space-y-4 flex-grow relative z-10">
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Cabang</label>
                            <select wire:model.live="selectedCabangProfit"
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 dark:bg-black/20 dark:border-white/10 text-xs font-bold uppercase focus:ring-rose-500">
                                @foreach($cabangOptions as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase ml-1">Supplier
                                (Opsional)</label>
                            <select wire:model="selectedSupplierProfit"
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 dark:bg-black/20 dark:border-white/10 text-xs font-bold uppercase focus:ring-rose-500">
                                <option value="">Semua Pemasok</option>
                                @foreach($supplierOptionsProfit as $s)
                                <option value="{{ $s }}">{{ Str::limit($s, 30) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 relative z-10">
                        <button wire:click="cetakProfit" wire:loading.attr="disabled"
                            class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-rose-600/30 hover:shadow-rose-600/50 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="cetakProfit">
                                <i class="fas fa-file-invoice-dollar mr-1"></i> Cetak Profit
                            </span>
                            <span wire:loading wire:target="cetakProfit">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>