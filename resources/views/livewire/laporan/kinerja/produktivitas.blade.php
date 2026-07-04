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
        background: rgba(59, 130, 246, 0.4);
        border-radius: 10px;
    }
    </style>

    {{-- HEADER & FILTER --}}
    <div
        class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6 dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-3 rounded-2xl shadow-lg bg-blue-600 text-white flex items-center justify-center">
                    <i class="fas fa-users-viewfinder text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-2xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Kinerja <span class="text-blue-600">Kunjungan</span>
                    </h1>
                    <p
                        class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-60 mt-1 dark:text-slate-400 text-slate-500">
                        Produktivitas Sales (OA & EC)
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-40 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.500ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-blue-500/20 transition-all h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner"
                        placeholder="Cari Sales...">
                </div>

                {{-- Filter Khusus Min Nominal --}}
                <div
                    class="flex items-center dark:bg-black/40 bg-white border dark:border-white/10 border-slate-200 rounded-xl px-3 shadow-inner h-[38px]">
                    <span
                        class="text-[9px] font-black text-blue-500 dark:text-blue-400 uppercase pr-2 border-r dark:border-white/10 border-slate-100 mr-2">Min.
                        Nota</span>
                    <input type="number" wire:model.live.debounce.500ms="minNominal"
                        class="w-20 border-none focus:ring-0 text-[10px] font-black text-slate-700 dark:text-white py-1 bg-transparent p-0"
                        placeholder="Rp">
                </div>

                <select wire:model.live="filterCabang"
                    class="border px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-slate-300 bg-white border-slate-200 text-slate-700 w-full sm:w-32 cursor-pointer">
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
                        class="fas fa-store text-lg"></i></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Outlet Aktif (OA)
                </p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">
                    {{ number_format($summary['total_oa'], 0, ',', '.') }} Toko</h3>
            </div>
            <div
                class="p-6 rounded-[2rem] border transition-all dark:bg-[#121212] bg-white border-slate-200 dark:border-white/5 shadow-sm flex flex-col justify-center">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">
                    <i class="fas fa-file-signature text-lg"></i></div>
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Total Nota Efektif
                    (EC)</p>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                    {{ number_format($summary['total_ec'], 0, ',', '.') }} Nota</h3>
            </div>
        </div>

        {{-- TABEL PRODUKTIVITAS --}}
        <div class="rounded-[2.5rem] border overflow-hidden bg-white shadow-xl dark:bg-[#121212] dark:border-white/5">
            <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-blue-50/50 dark:bg-blue-500/10">
                <span class="text-[11px] font-black uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400"><i
                        class="fas fa-table text-blue-500 mr-2"></i> Analisa Efektivitas Kunjungan</span>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-sm text-left border-collapse uppercase font-jakarta">
                    <thead>
                        <tr
                            class="bg-slate-50 dark:bg-[#1a1a1a] text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b border-slate-100 dark:border-white/5">
                            <th class="px-6 py-5 border-r border-slate-100 dark:border-white/5 w-24">Kode</th>
                            <th class="px-6 py-5 border-r border-slate-100 dark:border-white/5">Nama Sales</th>
                            <th
                                class="px-6 py-5 text-center border-r border-slate-100 dark:border-white/5 text-blue-600 dark:text-blue-400">
                                Outlet Aktif (OA)</th>
                            <th
                                class="px-6 py-5 text-center border-r border-slate-100 dark:border-white/5 text-emerald-600 dark:text-emerald-400">
                                Nota Efektif (EC)</th>
                            <th class="px-6 py-5 text-center">Rasio Kunjungan (EC/OA)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-blue-50 dark:hover:bg-blue-500/5 group transition-colors">
                            <td
                                class="px-6 py-4 font-mono font-bold text-indigo-500 dark:text-indigo-400 text-xs border-r border-slate-50 dark:border-white/5">
                                {{ $row['kode'] }}</td>
                            <td
                                class="px-6 py-4 font-black text-slate-800 dark:text-white text-xs border-r border-slate-50 dark:border-white/5">
                                {{ $row['nama'] }}</td>
                            <td
                                class="px-6 py-4 text-center font-black text-blue-600 dark:text-blue-400 text-lg bg-blue-500/[0.02] dark:bg-blue-500/[0.05] border-r border-slate-50 dark:border-white/5">
                                {{ $row['real_oa'] }}</td>
                            <td
                                class="px-6 py-4 text-center font-black text-emerald-600 dark:text-emerald-400 text-lg bg-emerald-500/[0.02] dark:bg-emerald-500/[0.05] border-r border-slate-50 dark:border-white/5">
                                {{ $row['ec'] }}</td>
                            <td class="px-6 py-4 text-center">
                                @php $ratio = $row['real_oa'] > 0 ? ($row['ec'] / $row['real_oa']) * 100 : 0; @endphp
                                <span
                                    class="px-3 py-1 rounded-md text-[10px] font-black tracking-widest border {{ $ratio > 50 ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 border-slate-200 text-slate-500 dark:bg-slate-800/50 dark:border-slate-600 dark:text-slate-400' }}">
                                    {{ number_format($ratio, 1) }}%
                                </span>
                            </td>
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