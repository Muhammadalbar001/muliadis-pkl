<div class="min-h-screen space-y-6 pb-20 transition-colors duration-300 font-jakarta">

    {{-- HEADER & NAVIGASI --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/90 dark:border-white/10 bg-white/95 border-slate-200 shadow-sm">

        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2.5 rounded-xl shadow-lg dark:bg-cyan-500/20 bg-cyan-600 text-white dark:text-cyan-400">
                    <i class="fas fa-hand-holding-usd text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Rekapitulasi <span class="text-cyan-500">Pelunasan</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-80 dark:text-slate-400 text-slate-600 mt-1.5">
                        Audit Penyelesaian Piutang ({{ number_format($collections->total(), 0, ',', '.') }} Baris)</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">

                {{-- PENCARIAN --}}
                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-cyan-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-cyan-500/20 transition-all
                        dark:bg-black/40 dark:border-white/20 dark:text-white bg-slate-100 border-slate-300 shadow-inner" placeholder="No Bukti / Pelanggan...">
                </div>

                {{-- RENTANG TANGGAL --}}
                <div
                    class="flex items-center gap-1.5 p-1.5 dark:bg-black/40 bg-white border dark:border-white/20 border-slate-300 rounded-xl shadow-sm h-[38px]">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-[10px] font-black uppercase dark:bg-transparent bg-transparent text-slate-900 dark:text-white focus:ring-0 p-0 w-24 cursor-pointer">
                    <span class="text-slate-400 dark:text-slate-500 text-[10px] font-black">S/D</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-[10px] font-black uppercase dark:bg-transparent bg-transparent text-slate-900 dark:text-white focus:ring-0 p-0 w-24 cursor-pointer">
                </div>

                {{-- FILTER CABANG --}}
                <div class="relative w-full sm:w-32" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between border px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                        dark:bg-black/40 dark:border-white/20 dark:text-slate-300 bg-white border-slate-300 text-slate-700 hover:border-cyan-400">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Regional' : 'CABANG'"></span>
                        <i class="fas fa-chevron-down opacity-40 text-[10px] transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 mt-2 w-48 border rounded-2xl shadow-2xl p-2 max-h-60 overflow-y-auto custom-scrollbar
                        dark:bg-slate-900 border-slate-800 bg-white border-slate-200" style="display: none;">
                        <div @click="selected = []"
                            class="px-3 py-2 text-[10px] text-rose-500 font-black uppercase cursor-pointer hover:bg-rose-500/10 rounded-xl mb-1 flex items-center gap-2">
                            <i class="fas fa-times-circle"></i> Atur Ulang
                        </div>
                        @foreach($optCabang as $c)
                        <label
                            class="flex items-center px-3 py-2.5 hover:bg-cyan-500/10 rounded-xl cursor-pointer transition-colors group">
                            <input type="checkbox" value="{{ $c }}" x-model="selected"
                                class="rounded-full border-slate-500 text-cyan-600 focus:ring-cyan-500 h-3.5 w-3.5">
                            <span
                                class="ml-3 text-[10px] font-bold uppercase tracking-tight dark:text-slate-400 text-slate-600">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- FILTER SALES --}}
                <div class="relative w-full sm:w-32" x-data="{ open: false, selected: @entangle('filterSales').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between border px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                        dark:bg-black/40 dark:border-white/20 dark:text-slate-300 bg-white border-slate-300 text-slate-700 hover:border-cyan-400">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Sales' : 'SALES'"></span>
                        <i class="fas fa-chevron-down opacity-40 text-[10px] transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 mt-2 w-48 border rounded-2xl shadow-2xl p-2 max-h-60 overflow-y-auto custom-scrollbar
                        dark:bg-slate-900 border-slate-800 bg-white border-slate-200" style="display: none;">
                        <div @click="selected = []"
                            class="px-3 py-2 text-[10px] text-rose-500 font-black uppercase tracking-widest cursor-pointer hover:bg-rose-500/10 rounded-xl mb-1 flex items-center gap-2">
                            <i class="fas fa-times-circle"></i> Atur Ulang
                        </div>
                        @foreach($optSales as $s)
                        <label
                            class="flex items-center px-3 py-2.5 hover:bg-cyan-500/10 rounded-xl cursor-pointer transition-colors group">
                            <input type="checkbox" value="{{ $s }}" x-model="selected"
                                class="rounded-full border-slate-500 text-cyan-600 focus:ring-cyan-500 h-3.5 w-3.5">
                            <span
                                class="ml-3 text-[10px] font-bold uppercase tracking-tight dark:text-slate-400 text-slate-600">{{ $s }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button wire:click="resetFilter"
                        class="px-4 py-2.5 dark:bg-white/5 bg-white border dark:border-white/10 border-slate-200 dark:text-slate-300 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-undo"></i>
                    </button>
                    <button wire:click="export" wire:loading.attr="disabled" wire:target="export"
                        class="flex items-center gap-2 px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-cyan-600/20 transition-all transform active:scale-95">
                        <span wire:loading.remove wire:target="export"><i class="fas fa-file-excel"></i> Ekspor
                            Excel</span>
                        <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin"></i> Proses</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- AREA TABEL DATA --}}
    <div wire:loading.class="opacity-50 pointer-events-none"
        class="transition-opacity duration-300 px-4 sm:px-6 lg:px-8">

        {{-- Tinggi h-[150vh] agar tabel memanjang ke bawah --}}
        <div class="rounded-[2.5rem] border overflow-hidden transition-all duration-300 flex flex-col h-[150vh] mb-10
            dark:bg-[#0a0a0a] dark:border-white/10 bg-white border-slate-300 shadow-2xl dark:shadow-black/80">

            <div class="overflow-auto flex-1 w-full custom-scrollbar">
                <table
                    class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full uppercase font-jakarta">
                    <thead>
                        <tr
                            class="dark:bg-[#151515] bg-slate-200 text-slate-800 dark:text-white font-black tracking-[0.15em] border-b-2 dark:border-white/20 border-slate-400 sticky top-0 z-30">
                            <th
                                class="px-4 py-6 border-r dark:border-white/10 border-slate-300 bg-inherit sticky left-0 z-40 shadow-md">
                                Cabang</th>
                            <th
                                class="px-4 py-6 border-r dark:border-white/10 border-slate-300 bg-inherit sticky left-[60px] z-40 shadow-md text-cyan-700 dark:text-cyan-400">
                                No Bukti</th>

                            <th class="px-4 py-6 border-r dark:border-white/10 border-slate-300">Status</th>
                            <th class="px-4 py-6 border-r dark:border-white/10 border-slate-300">Tgl Pelunasan</th>
                            <th class="px-4 py-6 border-r dark:border-white/10 border-slate-300">Nama Penagih</th>
                            <th class="px-4 py-6 border-r dark:border-white/10 border-slate-300">No Invoice</th>
                            <th class="px-4 py-6 border-r dark:border-white/10 border-slate-300 text-center">Kode Pel.
                            </th>
                            <th class="px-4 py-6 border-r dark:border-white/10 border-slate-300 min-w-[200px]">Nama
                                Pelanggan</th>
                            <th class="px-4 py-6 border-r dark:border-white/10 border-slate-300">Nama Salesman</th>
                            <th
                                class="px-4 py-6 border-r border-cyan-500/20 dark:bg-cyan-600/10 bg-cyan-50 text-cyan-800 dark:text-cyan-400 text-right font-black">
                                Jumlah Diterima</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-white/10 divide-slate-300">
                        @forelse($collections as $item)
                        <tr
                            class="hover:bg-slate-100 dark:hover:bg-white/[0.05] transition-colors group dark:bg-transparent bg-white text-slate-900 dark:text-slate-200">
                            {{-- Kolom Sticky Kontras Tinggi --}}
                            <td
                                class="px-4 py-4 border-r dark:border-white/10 border-slate-200 font-black sticky left-0 bg-white dark:bg-[#0a0a0a] z-10 text-slate-900 dark:text-white">
                                {{ $item->cabang }}</td>
                            <td
                                class="px-4 py-4 border-r dark:border-white/10 border-slate-200 font-mono font-black text-cyan-700 dark:text-cyan-400 sticky left-[60px] bg-white dark:bg-[#0a0a0a] z-10 shadow-sm">
                                {{ $item->receive_no }}</td>

                            <td
                                class="px-4 py-4 border-r dark:border-white/10 border-slate-200 text-[9px] font-bold opacity-80">
                                {{ $item->status }}</td>
                            <td class="px-4 py-4 border-r dark:border-white/10 border-slate-200 font-medium">
                                {{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                            <td
                                class="px-4 py-4 border-r dark:border-white/10 border-slate-200 font-bold opacity-80 text-slate-700 dark:text-slate-300">
                                {{ $item->penagih }}</td>
                            <td class="px-4 py-4 border-r dark:border-white/10 border-slate-200 font-mono opacity-80">
                                {{ $item->invoice_no }}</td>
                            <td
                                class="px-4 py-4 border-r dark:border-white/10 border-slate-200 font-mono text-center opacity-60 text-slate-600 dark:text-slate-400">
                                {{ $item->code_customer }}</td>
                            <td class="px-4 py-4 border-r dark:border-white/10 border-slate-200 font-black dark:text-white truncate max-w-[200px]"
                                title="{{ $item->outlet_name }}">{{ $item->outlet_name }}</td>
                            <td
                                class="px-4 py-4 border-r dark:border-white/10 border-slate-200 font-bold opacity-80 italic">
                                {{ $item->sales_name }}</td>
                            <td
                                class="px-4 py-4 border-r dark:border-white/10 border-slate-200 text-right font-black dark:text-cyan-400 text-cyan-700 bg-cyan-500/[0.03]">
                                {{ number_format($item->receive_amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10"
                                class="px-6 py-32 text-center text-slate-400 dark:text-slate-600 font-black tracking-widest uppercase">
                                Belum Ada Data Pelunasan Ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER / PAGINASI --}}
            <div
                class="px-6 py-6 border-t dark:border-white/10 border-slate-300 dark:bg-[#111] bg-slate-50 uppercase font-black text-[10px]">
                {{ $collections->links() }}
            </div>
        </div>
    </div>

</div>

<style>
/* Memperjelas Scrollbar Kontras Tinggi */
.custom-scrollbar::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
}

.dark .custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #94a3b8;
    border-radius: 10px;
    border: 2px solid transparent;
    background-clip: padding-box;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #475569;
}

/* Zebra Stripe Kontras Tinggi */
tbody tr:nth-child(even) {
    background-color: rgba(0, 0, 0, 0.02);
}

.dark tbody tr:nth-child(even) {
    background-color: rgba(255, 255, 255, 0.02);
}

tbody tr {
    animation: fadeIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(8px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>