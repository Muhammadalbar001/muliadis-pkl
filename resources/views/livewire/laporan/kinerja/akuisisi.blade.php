<div class="min-h-screen pb-20 font-jakarta bg-slate-50 dark:bg-[#050505]">
    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(245, 158, 11, 0.4);
        border-radius: 10px;
    }
    </style>

    <div
        class="sticky top-0 z-40 backdrop-blur-xl border-b -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6 dark:bg-[#0a0a0a]/80 bg-white/80 border-slate-200">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-2xl shadow-lg bg-amber-500 text-white"><i class="fas fa-seedling text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black uppercase leading-none dark:text-white text-slate-800">Akuisisi <span
                            class="text-amber-500">Toko Baru</span></h1>
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-60 mt-1 dark:text-slate-400">
                        Analisa Pertumbuhan Pelanggan Baru</p>
                </div>
            </div>
            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-amber-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.500ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-amber-500/20 transition-all h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner"
                        placeholder="Cari Sales...">
                </div>
                <input type="month" wire:model.live="bulan"
                    class="w-full sm:w-36 border px-4 py-2 rounded-xl text-[11px] font-black uppercase h-[38px] dark:bg-black/40 bg-white dark:border-white/10 border-slate-200 dark:text-white transition-all shadow-sm cursor-pointer">

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
            <div class="p-6 border-b bg-amber-50/50 dark:bg-amber-500/10">
                <span class="text-[11px] font-black uppercase tracking-[0.2em] text-amber-600"><i
                        class="fas fa-table mr-2"></i> Matriks Ekspansi & Pembukaan Toko</span>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-sm text-left uppercase">
                    <thead>
                        <tr class="bg-slate-50 font-black text-[10px] tracking-[0.15em] dark:bg-[#1a1a1a]">
                            <th class="px-6 py-5 border-r w-24">Kode</th>
                            <th class="px-6 py-5 border-r">Nama Sales</th>
                            <th class="px-6 py-5 text-center text-slate-500 border-r">Toko Aktif (Lama)</th>
                            <th class="px-6 py-5 text-center text-amber-600 border-r">Toko Aktif (Baru)</th>
                            <th class="px-6 py-5 text-center border-r">Total Toko Transaksi</th>
                            <th class="px-6 py-5 text-center border-r">Rasio Pertumbuhan</th>
                            <th class="px-6 py-5 text-right bg-amber-50/30 text-amber-700">Omzet Toko Baru (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-amber-50/30">
                            <td class="px-6 py-4 font-mono font-bold text-xs border-r">{{ $row['kode'] }}</td>
                            <td class="px-6 py-4 font-black text-xs border-r">{{ $row['nama'] }}</td>
                            <td class="px-6 py-4 text-center font-black text-slate-600 border-r">{{ $row['lama'] }}</td>
                            <td class="px-6 py-4 text-center font-black text-amber-600 text-lg border-r bg-amber-50/10">
                                {{ $row['baru'] }}</td>
                            <td class="px-6 py-4 text-center font-black border-r">{{ $row['total_toko'] }}</td>
                            <td class="px-6 py-4 text-center border-r">
                                <span
                                    class="px-3 py-1 rounded-md text-[10px] font-bold border {{ $row['rasio'] >= 10 ? 'bg-amber-100 text-amber-600 border-amber-200' : 'bg-slate-100 text-slate-500' }}">
                                    {{ number_format($row['rasio'], 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-amber-700 bg-amber-50/30">
                                {{ number_format($row['omzet_baru'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-[#1a1a1a]">{{ $laporan->links() }}</div>
        </div>
    </div>
</div>