<div class="min-h-screen pb-20 font-jakarta bg-slate-50 dark:bg-[#050505] transition-colors duration-300">
    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(168, 85, 247, 0.4);
        border-radius: 10px;
    }
    </style>

    {{-- HEADER & FILTER --}}
    <div
        class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6 dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-3 rounded-2xl shadow-lg bg-purple-600 text-white flex items-center justify-center">
                    <i class="fas fa-boxes-stacked text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-2xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Mix <span class="text-purple-600">Supplier</span>
                    </h1>
                    <p
                        class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-60 mt-1 dark:text-slate-400 text-slate-500">
                        Distribusi Penjualan per Brand
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-purple-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.500ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-purple-500/20 transition-all h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner"
                        placeholder="Cari Sales...">
                </div>

                <select wire:model.live="filterCabang"
                    class="border px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-slate-300 bg-white border-slate-200 text-slate-700 w-full sm:w-40 cursor-pointer">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>

                <input type="month" wire:model.live="bulan"
                    class="w-full sm:w-36 border px-4 py-2 rounded-xl text-[11px] font-black uppercase h-[38px] dark:bg-black/40 bg-white dark:border-white/10 border-slate-200 dark:text-white transition-all shadow-sm cursor-pointer">

                <button wire:click="resetFilter"
                    class="px-4 py-2 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 rounded-xl text-[10px] hover:bg-rose-50 hover:text-rose-500 transition-all shadow-sm h-[38px] flex items-center justify-center"
                    title="Reset Filter"><i class="fas fa-undo"></i></button>

                <button wire:click="exportPdf" wire:loading.attr="disabled"
                    class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 h-[38px] flex items-center gap-2 transition-transform active:scale-95 whitespace-nowrap">
                    <i class="fas fa-file-pdf"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 space-y-6 relative" wire:loading.class="opacity-50">
        {{-- TABEL MATRIKS --}}
        <div class="rounded-[2.5rem] border overflow-hidden bg-white shadow-xl dark:bg-[#121212] dark:border-white/5">
            <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-purple-50/50 dark:bg-purple-500/10">
                <span class="text-[11px] font-black uppercase tracking-[0.2em] text-purple-600 dark:text-purple-400"><i
                        class="fas fa-table text-purple-500 mr-2"></i> Matriks Sebaran Brand/Supplier Utama</span>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-[10px] text-left whitespace-nowrap border-collapse uppercase relative">
                    <thead>
                        <tr
                            class="bg-slate-50 dark:bg-[#1a1a1a] text-slate-500 dark:text-slate-400 font-black tracking-[0.1em] border-b border-slate-100 dark:border-white/5">
                            <th
                                class="px-6 py-5 sticky left-0 bg-slate-50 dark:bg-[#1a1a1a] border-r border-slate-100 dark:border-white/5 z-20">
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
                                Total Omzet (10 Brand)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-purple-50 dark:hover:bg-purple-500/5 group transition-colors">
                            <td
                                class="px-6 py-4 font-black text-slate-700 dark:text-white sticky left-0 bg-white group-hover:bg-purple-50 dark:bg-[#121212] group-hover:dark:bg-[#1a1a1a] border-r border-slate-100 dark:border-white/5 z-10">
                                {{ $row['nama'] }} <span
                                    class="text-[9px] font-mono font-bold text-slate-400 ml-1">({{ $row['kode'] }})</span>
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
                                {{ $row['jml_supplier'] }}</td>
                            <td
                                class="px-6 py-4 text-right font-black bg-purple-100/80 dark:bg-purple-800/40 text-purple-900 dark:text-purple-200 sticky right-0 z-10 border-l border-purple-200 dark:border-purple-700">
                                {{ number_format($row['total_supplier_val'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-[#1a1a1a] border-t dark:border-white/5 border-slate-100">
                {{ $laporan->links() }}
            </div>
        </div>
    </div>
</div>