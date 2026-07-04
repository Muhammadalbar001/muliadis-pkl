<div class="min-h-screen pb-20 font-jakarta bg-slate-50 dark:bg-[#050505]">
    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.4);
        border-radius: 10px;
    }
    </style>

    <div
        class="sticky top-0 z-40 backdrop-blur-xl border-b -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6 dark:bg-[#0a0a0a]/80 bg-white/80 border-slate-200">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-2xl shadow-lg bg-indigo-600 text-white"><i
                        class="fas fa-project-diagram text-xl"></i></div>
                <div>
                    <h1 class="text-2xl font-black uppercase leading-none dark:text-white text-slate-800">Kinerja <span
                            class="text-indigo-600">Segmentasi</span></h1>
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-60 mt-1 dark:text-slate-400">
                        Analisa Toko VIP vs Pasif</p>
                </div>
            </div>
            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.500ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-indigo-500/20 transition-all h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner"
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

                {{-- TOMBOL CETAK --}}
                <button wire:click="exportPdf" wire:loading.attr="disabled"
                    class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 h-[38px] flex items-center gap-2 transition-transform active:scale-95 whitespace-nowrap">
                    <i class="fas fa-file-pdf"></i> Cetak Laporan
                    <i wire:loading wire:target="exportPdf" class="fas fa-spinner fa-spin ml-1"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="rounded-[2.5rem] border overflow-hidden bg-white shadow-xl dark:bg-[#121212]">
            <div class="p-6 border-b bg-indigo-50/50 dark:bg-indigo-500/10">
                <span class="text-[11px] font-black uppercase tracking-[0.2em] text-indigo-600"><i
                        class="fas fa-table mr-2"></i> Matriks Segmen Pelanggan per Sales</span>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-sm text-left uppercase">
                    <thead>
                        <tr class="bg-slate-50 font-black text-[10px] tracking-[0.15em] dark:bg-[#1a1a1a]">
                            <th class="px-6 py-5 border-r w-24">Kode</th>
                            <th class="px-6 py-5 border-r">Nama Sales</th>
                            <th class="px-6 py-5 text-center text-emerald-600 border-r">Toko VIP (Utama)</th>
                            <th class="px-6 py-5 text-center text-blue-600 border-r">Toko Menengah</th>
                            <th class="px-6 py-5 text-center text-rose-600 border-r">Toko Pasif (Churn)</th>
                            <th class="px-6 py-5 text-center bg-indigo-50/30">Total Toko</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-indigo-50/30">
                            <td class="px-6 py-4 font-mono font-bold text-xs border-r">{{ $row['kode'] }}</td>
                            <td class="px-6 py-4 font-black text-xs border-r">{{ $row['nama'] }}</td>
                            <td
                                class="px-6 py-4 text-center font-black text-emerald-600 text-lg border-r bg-emerald-50/10">
                                {{ $row['vip'] }}</td>
                            <td class="px-6 py-4 text-center font-black text-blue-600 text-lg border-r">
                                {{ $row['menengah'] }}</td>
                            <td class="px-6 py-4 text-center font-black text-rose-600 text-lg border-r bg-rose-50/10">
                                {{ $row['pasif'] }}</td>
                            <td class="px-6 py-4 text-center font-black text-indigo-700 bg-indigo-50/30">
                                {{ $row['total'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-[#1a1a1a]">{{ $laporan->links() }}</div>
        </div>
    </div>
</div>