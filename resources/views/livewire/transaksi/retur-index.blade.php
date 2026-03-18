<div>
    <div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta"
        x-data="{ filterOpen: false }">

        {{-- HEADER & NAVIGASI --}}
        <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

            <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
                <div class="flex items-center gap-4 w-full xl:w-auto">
                    <div
                        class="p-2.5 rounded-xl shadow-lg dark:bg-rose-500/20 bg-rose-600 text-white dark:text-rose-400">
                        <i class="fas fa-undo-alt text-xl"></i>
                    </div>
                    <div>
                        <h1
                            class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                            Transaksi <span class="text-rose-500">Retur</span>
                        </h1>
                        <p
                            class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1.5 dark:text-slate-400 text-slate-500">
                            Pengembalian Barang & Koreksi</p>
                    </div>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">

                    {{-- PENCARIAN --}}
                    <div class="relative w-full sm:w-48 group">
                        <i
                            class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-rose-500 transition-colors text-xs"></i>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-rose-500/20 transition-all
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner" placeholder="Cari No. Retur...">
                    </div>

                    {{-- FILTER REGIONAL --}}
                    <div class="relative w-full sm:w-40"
                        x-data="{ open: false, selected: @entangle('filterCabang').live }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="w-full flex items-center justify-between border px-4 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                        dark:bg-black/40 dark:border-white/5 dark:text-slate-300 bg-white border-slate-200 text-slate-700 hover:border-rose-400">
                            <span class="truncate"
                                x-text="selected.length > 0 ? selected.length + ' Regional' : 'Pilih Cabang'"></span>
                            <i class="fas fa-chevron-down opacity-40 text-[10px] transition-transform"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition class="absolute z-50 mt-2 w-full border rounded-2xl shadow-2xl p-2 max-h-60 overflow-y-auto custom-scrollbar
                        dark:bg-slate-900 border-slate-800 bg-white border-slate-200" style="display: none;">
                            <div @click="selected = []"
                                class="px-3 py-2 text-[10px] text-rose-500 font-black uppercase tracking-widest cursor-pointer hover:bg-rose-500/10 rounded-xl mb-1 flex items-center gap-2">
                                <i class="fas fa-times-circle"></i> Bersihkan Filter
                            </div>
                            @foreach($optCabang as $c)
                            <label
                                class="flex items-center px-3 py-2.5 hover:bg-rose-500/10 rounded-xl cursor-pointer transition-colors group">
                                <input type="checkbox" value="{{ $c }}" x-model="selected"
                                    class="rounded-full border-slate-500 text-rose-600 focus:ring-rose-500 h-3.5 w-3.5 focus:ring-offset-0 bg-transparent">
                                <span
                                    class="ml-3 text-[10px] font-bold uppercase tracking-tight group-hover:text-rose-400 dark:text-slate-400 text-slate-600">{{ $c }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- TOMBOL PENGAJUAN HAPUS PERIODE --}}
                    <button wire:click="openDeletePeriodModal"
                        class="flex items-center justify-center gap-2 px-5 py-2.5 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 hover:bg-rose-100 dark:hover:bg-rose-500/20 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm transform active:scale-95">
                        <i class="fas fa-trash-alt"></i>
                        <span class="hidden sm:inline">Hapus Periode</span>
                    </button>

                    {{-- IMPORT --}}
                    <button wire:click="openImportModal"
                        class="flex items-center justify-center gap-2 px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 transition-all transform active:scale-95">
                        <i class="fas fa-file-excel"></i>
                        <span class="hidden sm:inline">Unggah Data</span>
                    </button>

                    <div wire:loading
                        class="w-10 h-10 rounded-xl flex items-center justify-center dark:bg-slate-800 bg-white border dark:border-white/5 border-slate-200 shadow-sm animate-pulse">
                        <i class="fas fa-circle-notch fa-spin text-rose-500"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- KONTEN UTAMA --}}
        <div wire:loading.class="opacity-50 pointer-events-none"
            class="transition-opacity duration-300 px-4 sm:px-6 lg:px-8">

            {{-- TABEL TRANSAKSI --}}
            <div class="rounded-[2.5rem] border overflow-hidden transition-all duration-300 flex flex-col h-[75vh]
            dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-2xl dark:shadow-black/60">

                <div class="overflow-auto flex-1 w-full custom-scrollbar">
                    <table class="w-full text-xs text-left border-collapse uppercase">
                        <thead>
                            <tr
                                class="dark:bg-white/5 bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b dark:border-white/5 border-slate-100">
                                <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100">Tgl Retur</th>
                                <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100">No. Retur</th>
                                <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100 min-w-[200px]">Nama
                                    Pelanggan</th>
                                <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100">Nama Sales</th>
                                <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100 text-center">Cabang
                                </th>
                                <th
                                    class="px-6 py-5 border-r dark:border-white/5 border-slate-100 text-right dark:bg-rose-600/10 bg-rose-50/50 text-rose-600">
                                    Total Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                            @forelse($returs as $item)
                            <tr class="hover:bg-rose-500/[0.02] transition-colors group">
                                <td
                                    class="px-6 py-4 dark:text-slate-400 text-slate-500 font-bold border-r dark:border-white/5 border-slate-50 italic">
                                    {{ date('d/m/Y', strtotime($item->tgl_retur)) }}
                                </td>
                                <td
                                    class="px-6 py-4 font-mono font-black text-rose-600 dark:text-rose-400 border-r dark:border-white/5 border-slate-50">
                                    {{ $item->no_retur }}
                                </td>
                                <td class="px-6 py-4 font-black dark:text-white text-slate-800 border-r dark:border-white/5 border-slate-50 truncate max-w-[250px]"
                                    title="{{ $item->nama_pelanggan }}">
                                    {{ $item->nama_pelanggan }}
                                </td>
                                <td
                                    class="px-6 py-4 text-[10px] font-bold dark:text-slate-400 text-slate-500 border-r dark:border-white/5 border-slate-50">
                                    {{ $item->sales_name }}
                                </td>
                                <td class="px-6 py-4 text-center border-r dark:border-white/5 border-slate-50">
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[9px] font-black tracking-widest border dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20 bg-indigo-50 text-indigo-600 border-indigo-100">
                                        {{ $item->cabang }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 text-right font-black dark:text-white text-slate-900 border-r dark:border-white/5 border-slate-50 bg-rose-500/[0.01]">
                                    {{ number_format($item->total_grand ?? $item->total_nilai, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center opacity-20">
                                    <i class="fas fa-undo text-6xl mb-4"></i>
                                    <p class="text-xs font-black tracking-[0.4em]">Belum Ada Data Retur</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINASI --}}
                <div
                    class="px-6 py-5 border-t dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-slate-50/50 uppercase font-black text-[10px]">
                    {{ $returs->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL UNGGAH --}}
        @if($isImportOpen)
        @include('livewire.partials.import-modal', ['title' => 'Sinkronisasi Data Retur', 'color' => 'rose'])
        @endif

        {{-- MODAL PENGAJUAN HAPUS PERIODE --}}
        @if($isDeletePeriodModalOpen)
        <div
            class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm p-4">
            <div
                class="relative w-full max-w-md bg-white dark:bg-[#18181b] rounded-3xl shadow-2xl ring-1 ring-slate-200 dark:ring-white/10 overflow-hidden animate-fade-in transform scale-100 border border-slate-200 dark:border-white/10">
                <div
                    class="bg-rose-50 dark:bg-rose-900/20 px-6 py-5 border-b border-rose-100 dark:border-rose-800/30 flex items-center justify-between">
                    <h3
                        class="text-sm font-black text-rose-700 dark:text-rose-400 flex items-center gap-2 uppercase tracking-widest">
                        <i class="fas fa-exclamation-triangle"></i> Pengajuan Hapus Retur
                    </h3>
                    <button wire:click="closeDeletePeriodModal"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <form wire:submit.prevent="submitDeletionRequest" class="p-6 space-y-5">
                    <div
                        class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-[#121212] p-4 rounded-2xl border border-slate-100 dark:border-white/5">
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Mulai
                                Tanggal <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model="deleteStartDate"
                                class="w-full rounded-xl border-slate-200 dark:border-white/10 bg-white dark:bg-[#1a1a1a] text-slate-800 dark:text-white text-xs font-bold focus:ring-rose-500 focus:border-rose-500 [color-scheme:light] dark:[color-scheme:dark] shadow-sm">
                            @error('deleteStartDate') <span
                                class="text-[9px] text-rose-600 dark:text-rose-400 font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Sampai
                                Tanggal <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model="deleteEndDate"
                                class="w-full rounded-xl border-slate-200 dark:border-white/10 bg-white dark:bg-[#1a1a1a] text-slate-800 dark:text-white text-xs font-bold focus:ring-rose-500 focus:border-rose-500 [color-scheme:light] dark:[color-scheme:dark] shadow-sm">
                            @error('deleteEndDate') <span
                                class="text-[9px] text-rose-600 dark:text-rose-400 font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Alasan
                            Penghapusan <span class="text-rose-500">*</span></label>
                        <textarea wire:model="deleteReason" rows="3"
                            placeholder="Jelaskan alasan menghapus data periode ini..."
                            class="w-full rounded-2xl border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#121212] text-slate-800 dark:text-white text-sm focus:ring-rose-500 focus:border-rose-500 placeholder-slate-400 dark:placeholder-slate-500 shadow-sm"></textarea>
                        @error('deleteReason') <span
                            class="text-[10px] text-rose-600 dark:text-rose-400 font-bold block mt-1"><i
                                class="fas fa-info-circle"></i> {{ $message }}</span> @enderror
                    </div>
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-white/5">
                        <button type="button" wire:click="closeDeletePeriodModal"
                            class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">Batal</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-white bg-rose-600 hover:bg-rose-700 active:scale-95 transition-all shadow-md shadow-rose-500/20 flex items-center gap-2">
                            <span wire:loading.remove wire:target="submitDeletionRequest"><i
                                    class="fas fa-paper-plane"></i> Ajukan Hapus</span>
                            <span wire:loading wire:target="submitDeletionRequest"><i
                                    class="fas fa-circle-notch fa-spin"></i> Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(244, 63, 94, 0.2);
        border-radius: 10px;
    }

    tbody tr {
        animation: fadeIn 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(4px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
</div>