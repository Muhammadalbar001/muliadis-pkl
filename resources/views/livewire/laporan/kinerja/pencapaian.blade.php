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
        background: rgba(16, 185, 129, 0.4);
        border-radius: 10px;
    }
    </style>

    {{-- HEADER & FILTER --}}
    <div
        class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6 dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-3 rounded-2xl shadow-lg bg-emerald-500 text-white flex items-center justify-center">
                    <i class="fas fa-bullseye text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-2xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Kinerja <span class="text-emerald-500">Sales</span>
                    </h1>
                    <p
                        class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-60 mt-1 dark:text-slate-400 text-slate-500">
                        Pencapaian Target Penjualan
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.500ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-emerald-500/20 transition-all h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner"
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
        {{-- KARTU RINGKASAN GLOBAL --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div
                class="p-6 rounded-[2rem] border transition-all dark:bg-[#121212] bg-white border-slate-200 dark:border-white/5 shadow-sm flex flex-col justify-center">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-3"><i
                        class="fas fa-bullseye text-lg"></i></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Target Omzet</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">Rp
                    {{ number_format($summary['target'], 0, ',', '.') }}</h3>
            </div>
            <div class="relative p-6 rounded-[2rem] border overflow-hidden bg-emerald-600 text-white shadow-xl group">
                <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center mb-3"><i
                        class="fas fa-chart-line text-lg"></i></div>
                <p class="text-emerald-100 text-[10px] font-black uppercase tracking-widest mb-1 opacity-80">Realisasi
                    Bersih</p>
                <h3 class="text-3xl font-black tracking-tighter">Rp {{ number_format($summary['real'], 0, ',', '.') }}
                </h3>
                <i
                    class="fas fa-arrow-trend-up absolute -right-2 -bottom-2 text-[6rem] opacity-10 rotate-12 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        {{-- TABEL PENCAPAIAN --}}
        <div class="rounded-[2.5rem] border overflow-hidden bg-white shadow-xl dark:bg-[#121212] dark:border-white/5">
            <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-emerald-50/50 dark:bg-emerald-500/10">
                <span
                    class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400"><i
                        class="fas fa-table text-emerald-500 mr-2"></i> Matriks Pencapaian Sales</span>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-sm text-left border-collapse uppercase">
                    <thead>
                        <tr
                            class="bg-slate-50 dark:bg-[#1a1a1a] text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b border-slate-100 dark:border-white/5">
                            <th class="px-6 py-5 border-r border-slate-100 dark:border-white/5 w-24">Kode</th>
                            <th class="px-6 py-5 border-r border-slate-100 dark:border-white/5">Nama Sales</th>
                            <th class="px-6 py-5 text-right border-r border-slate-100 dark:border-white/5">Target (Rp)
                            </th>
                            <th
                                class="px-6 py-5 text-right border-r border-slate-100 dark:border-white/5 text-emerald-600 dark:text-emerald-400">
                                Realisasi (Rp)</th>
                            <th class="px-6 py-5 text-center border-r border-slate-100 dark:border-white/5">Capaian %
                            </th>
                            <th class="px-6 py-5 text-right">Selisih (Gap)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-emerald-50 dark:hover:bg-emerald-500/5 group transition-colors">
                            <td
                                class="px-6 py-4 font-mono font-bold text-indigo-500 dark:text-indigo-400 text-xs border-r border-slate-50 dark:border-white/5">
                                {{ $row['kode'] }}</td>
                            <td class="px-6 py-4 border-r border-slate-50 dark:border-white/5">
                                <div class="font-black text-slate-800 dark:text-white text-xs">{{ $row['nama'] }}</div>
                                <div class="text-[9px] text-slate-400 font-bold tracking-widest mt-1">
                                    {{ $row['cabang'] ?: '-' }}</div>
                            </td>
                            <td
                                class="px-6 py-4 text-right font-mono text-slate-500 dark:text-slate-400 border-r border-slate-50 dark:border-white/5">
                                {{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                            <td
                                class="px-6 py-4 text-right font-black text-emerald-700 dark:text-emerald-400 bg-emerald-500/[0.02] dark:bg-emerald-500/[0.05] border-r border-slate-50 dark:border-white/5">
                                {{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center border-r border-slate-50 dark:border-white/5">
                                <span
                                    class="px-3 py-1 rounded-md text-[10px] font-black tracking-widest border {{ $row['persen_ims'] >= 100 ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : ($row['persen_ims'] >= 80 ? 'bg-amber-500/10 text-amber-600 border-amber-500/20' : 'bg-rose-500/10 text-rose-600 border-rose-500/20') }}">
                                    {{ number_format($row['persen_ims'], 1) }}%
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 text-right font-mono text-xs font-bold {{ $row['gap'] >= 0 ? 'text-emerald-500 dark:text-emerald-400' : 'text-rose-500 dark:text-rose-400' }}">
                                {{ number_format($row['gap'], 0, ',', '.') }}</td>
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