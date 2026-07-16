<div class="min-h-screen pb-20 font-jakarta bg-slate-50 dark:bg-[#050505] transition-colors duration-300">
    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(245, 158, 11, 0.4);
        border-radius: 10px;
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>

    <div
        class="sticky top-0 z-40 backdrop-blur-xl border-b -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-6 mb-8 dark:bg-[#0a0a0a]/80 bg-white/95 border-slate-200 dark:border-white/10 shadow-sm">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="p-3 rounded-2xl shadow-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center ring-4 ring-amber-500/20">
                    <i class="fas fa-file-invoice-dollar text-xl"></i></div>
                <div>
                    <h1 class="text-2xl font-black uppercase leading-none dark:text-white text-slate-800">Manajemen
                        <span class="text-amber-500 dark:text-amber-400">Piutang (AR)</span></h1>
                    <p
                        class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-60 mt-1 dark:text-slate-400 text-slate-500">
                        Integrasi Account Receivable</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button wire:click="openModalImport"
                    class="px-5 py-2.5 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-xl flex items-center gap-2 hover:bg-slate-50 dark:hover:bg-white/5 transition-all font-black text-[10px] uppercase tracking-widest text-slate-600 dark:text-slate-300 shadow-sm"><i
                        class="fas fa-file-excel text-emerald-500 text-sm"></i> Impor Excel</button>
                <button wire:click="create"
                    class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl flex items-center gap-2 shadow-lg shadow-amber-500/30 transition-transform active:scale-95 font-black text-[10px] uppercase tracking-widest"><i
                        class="fas fa-plus-circle text-sm"></i> Input Manual</button>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 space-y-6">
        @if (session()->has('message')) <div
            class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-2"><i class="fas fa-check-circle text-lg"></i> {{ session('message') }}
            </div><button onclick="this.parentElement.remove()"
                class="opacity-50 hover:opacity-100 transition-opacity"><i class="fas fa-times"></i></button>
        </div> @endif
        @if (session()->has('error')) <div
            class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-2"><i class="fas fa-exclamation-triangle text-lg"></i>
                {{ session('error') }}</div><button onclick="this.parentElement.remove()"
                class="opacity-50 hover:opacity-100 transition-opacity"><i class="fas fa-times"></i></button>
        </div> @endif

        <div
            class="bg-white dark:bg-[#121212] rounded-[2rem] shadow-sm border border-slate-200 dark:border-white/5 overflow-hidden animate-fade-in">
            <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-4"><label
                            class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Pencarian
                            Cerdas</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i
                                    class="fas fa-search text-slate-400"></i></div><input
                                wire:model.live.debounce.500ms="search" type="text"
                                class="block w-full pl-11 pr-4 py-3 border border-slate-200 dark:border-white/10 rounded-2xl leading-5 bg-white dark:bg-[#1a1a1a] text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 text-xs font-bold uppercase tracking-wider shadow-sm transition-all"
                                placeholder="Cari Dokumen...">
                        </div>
                    </div>
                    <div class="md:col-span-2"><label
                            class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Cabang</label><select
                            wire:model.live="filter_cabang"
                            class="block w-full px-4 py-3 border border-slate-200 dark:border-white/10 rounded-2xl bg-white dark:bg-[#1a1a1a] text-slate-700 dark:text-white focus:ring-2 focus:ring-amber-500 text-xs font-bold uppercase shadow-sm transition-all">
                            <option value="">Semua</option>
                            <option value="Banjarmasin">Banjarmasin</option>
                            <option value="Palangkaraya">Palangkaraya</option>
                            <option value="Barabai">Barabai</option>
                            <option value="Batulicin">Batulicin</option>
                            <option value="Pharma">Pharma</option>
                        </select></div>
                    <div class="md:col-span-2"><label
                            class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Dari
                            Tgl</label><input wire:model.live="start_date" type="date"
                            class="block w-full px-4 py-3 border border-slate-200 dark:border-white/10 rounded-2xl bg-white dark:bg-[#1a1a1a] text-slate-700 dark:text-white focus:ring-2 focus:ring-amber-500 text-xs font-bold uppercase shadow-sm transition-all">
                    </div>
                    <div class="md:col-span-2"><label
                            class="block text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Sampai
                            Tgl</label><input wire:model.live="end_date" type="date"
                            class="block w-full px-4 py-3 border border-slate-200 dark:border-white/10 rounded-2xl bg-white dark:bg-[#1a1a1a] text-slate-700 dark:text-white focus:ring-2 focus:ring-amber-500 text-xs font-bold uppercase shadow-sm transition-all">
                    </div>
                    <div class="md:col-span-2"><button wire:click="resetFilters"
                            class="w-full px-4 py-3 border border-amber-200 dark:border-amber-500/30 text-amber-500 bg-amber-50 dark:bg-amber-500/10 hover:bg-amber-100 dark:hover:bg-amber-500/20 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-sm"><i
                                class="fas fa-sync-alt"></i> Reset</button></div>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-sm text-left uppercase whitespace-nowrap">
                    <thead>
                        <tr
                            class="bg-slate-50 dark:bg-[#1a1a1a] text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b border-slate-200 dark:border-white/5">
                            <th class="px-6 py-5">Tgl Piutang</th>
                            <th class="px-6 py-5">No Dokumen</th>
                            <th class="px-6 py-5">Cabang</th>
                            <th class="px-6 py-5">Salesman</th>
                            <th class="px-6 py-5">Toko / Pelanggan</th>
                            <th class="px-6 py-5 text-right">Nilai (Rp)</th>
                            <th class="px-6 py-5 text-center">Status</th>
                            <th class="px-6 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($dataTabel as $item)
                        <tr class="hover:bg-amber-50 dark:hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300">
                                <div class="flex items-center gap-2"><i class="far fa-calendar-alt text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($item->tgl_penjualan)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs font-black text-amber-600 dark:text-amber-400">
                                {{ $item->no_penjualan }}</td>
                            <td class="px-6 py-4"><span
                                    class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-slate-100 text-slate-600 dark:bg-white/5 dark:text-slate-300 border border-slate-200 dark:border-white/10">{{ $item->cabang }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-700 dark:text-slate-200">
                                {{ $item->sales_name }}</td>
                            <td
                                class="px-6 py-4 text-[11px] font-black text-slate-800 dark:text-white truncate max-w-xs">
                                {{ $item->pelanggan_name }}</td>
                            <td class="px-6 py-4 text-xs font-black text-right text-rose-600 dark:text-rose-400">Rp
                                {{ number_format($item->nilai, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'Lunas') <span
                                    class="px-2 py-1 rounded text-[9px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">LUNAS</span>
                                @else <span
                                    class="px-2 py-1 rounded text-[9px] font-black bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400">BELUM
                                    LUNAS</span> @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'pimpinan')
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="edit({{ $item->id }})"
                                        class="w-8 h-8 rounded-xl bg-white dark:bg-[#1a1a1a] text-blue-600 hover:bg-blue-50 dark:hover:bg-white/5 transition-colors border border-slate-200 dark:border-white/10 shadow-sm flex items-center justify-center"><i
                                            class="fas fa-pen text-[10px]"></i></button>
                                    <button wire:click="delete({{ $item->id }})" wire:confirm="HAPUS PERMANEN?"
                                        class="w-8 h-8 rounded-xl bg-white dark:bg-[#1a1a1a] text-rose-600 hover:bg-rose-50 dark:hover:bg-white/5 transition-colors border border-slate-200 dark:border-white/10 shadow-sm flex items-center justify-center"><i
                                            class="fas fa-trash text-[10px]"></i></button>
                                </div>
                                @else
                                <span
                                    class="text-[9px] font-black text-slate-400 border border-slate-200 dark:border-white/10 px-2 py-1 rounded-md bg-slate-50 dark:bg-white/5 cursor-not-allowed"><i
                                        class="fas fa-lock text-rose-400"></i></span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <p
                                    class="font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest text-xs">
                                    Tidak ada data yang sesuai filter.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div
                class="px-6 py-4 bg-slate-50 dark:bg-[#1a1a1a] border-t border-slate-200 dark:border-white/5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="w-full sm:w-auto overflow-hidden">{{ $dataTabel->links() }}</div>
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total:
                    {{ $dataTabel->total() }} Baris</div>
            </div>
        </div>
    </div>

    @if($showModal)
    <div
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm animate-fade-in">
        <div
            class="relative w-full max-w-3xl bg-white dark:bg-[#18181b] rounded-[2rem] shadow-2xl ring-1 ring-slate-200 dark:ring-white/10 overflow-hidden transform scale-100 flex flex-col max-h-[90vh]">
            <div
                class="bg-slate-50 dark:bg-white/[0.02] px-8 py-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between shrink-0">
                <div>
                    <h3
                        class="text-base font-black uppercase tracking-widest text-slate-800 dark:text-white flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                            <i class="fas {{ $isEdit ? 'fa-pen' : 'fa-plus' }} text-sm"></i></div>
                        {{ $isEdit ? 'Edit Data' : 'Input Data Manual' }}
                    </h3>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-2">Formulir Rangkuman
                        (Tipe A)</p>
                </div>
                <button wire:click="$set('showModal', false)"
                    class="text-slate-400 hover:text-rose-500 transition-colors bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-full w-10 h-10 flex items-center justify-center shadow-sm"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="p-8 overflow-y-auto custom-scrollbar">
                <form wire:submit.prevent="store" class="space-y-6 font-jakarta text-xs uppercase">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div><label
                                class="block text-[10px] font-black text-slate-500 dark:text-slate-400 mb-2 tracking-widest">No.
                                Dokumen <span class="text-rose-500">*</span></label>
                            <div class="relative"><i
                                    class="fas fa-file-invoice absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input
                                    type="text" wire:model="no_dokumen"
                                    class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-black/40 pl-10 pr-4 py-3.5 font-bold dark:text-white focus:ring-2 focus:ring-amber-500 transition-all shadow-sm"
                                    required></div>
                        </div>
                        <div><label
                                class="block text-[10px] font-black text-slate-500 dark:text-slate-400 mb-2 tracking-widest">Tanggal
                                <span class="text-rose-500">*</span></label><input type="date" wire:model="tanggal"
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-black/40 px-4 py-3.5 font-bold dark:text-white cursor-pointer focus:ring-2 focus:ring-amber-500 transition-all shadow-sm"
                                required></div>
                    </div>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-50 dark:bg-white/[0.02] p-6 rounded-2xl border border-slate-100 dark:border-white/5">
                        <div><label
                                class="block text-[10px] font-black text-slate-500 dark:text-slate-400 mb-2 tracking-widest">Cabang
                                <span class="text-rose-500">*</span></label><select wire:model="cabang"
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-[#121212] px-4 py-3.5 font-bold dark:text-white cursor-pointer focus:ring-2 focus:ring-amber-500 transition-all shadow-sm"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="Banjarmasin">Banjarmasin</option>
                                <option value="Palangkaraya">Palangkaraya</option>
                                <option value="Barabai">Barabai</option>
                                <option value="Batulicin">Batulicin</option>
                                <option value="Pharma">Pharma</option>
                            </select></div>
                        <div><label
                                class="block text-[10px] font-black text-slate-500 dark:text-slate-400 mb-2 tracking-widest">Salesman
                                <span class="text-rose-500">*</span></label>
                            <div class="relative"><i
                                    class="fas fa-user-tie absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input
                                    type="text" wire:model="sales_name"
                                    class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-[#121212] pl-10 pr-4 py-3.5 font-bold dark:text-white focus:ring-2 focus:ring-amber-500 transition-all shadow-sm"
                                    required></div>
                        </div>
                    </div>
                    <div><label
                            class="block text-[10px] font-black text-slate-500 dark:text-slate-400 mb-2 tracking-widest">Nama
                            Toko / Pelanggan <span class="text-rose-500">*</span></label>
                        <div class="relative"><i
                                class="fas fa-store absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input
                                type="text" wire:model="nama_pelanggan"
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-black/40 pl-10 pr-4 py-3.5 font-bold dark:text-white focus:ring-2 focus:ring-amber-500 transition-all shadow-sm"
                                required></div>
                    </div>
                    <div
                        class="bg-amber-50 dark:bg-amber-500/10 p-6 rounded-2xl border border-amber-100 dark:border-amber-500/20">
                        <label
                            class="block text-[10px] font-black text-amber-700 dark:text-amber-400 mb-2 tracking-widest">Nominal
                            (Rp) <span class="text-rose-500">*</span></label>
                        <div class="relative flex items-center"><span
                                class="absolute left-4 font-black text-amber-600 dark:text-amber-400 text-sm">Rp</span><input
                                type="number" wire:model="nominal"
                                class="w-full rounded-xl border border-amber-200 dark:border-amber-500/30 bg-white dark:bg-black/40 pl-12 pr-4 py-4 font-black text-amber-700 dark:text-amber-300 focus:ring-2 focus:ring-amber-500 text-lg shadow-sm transition-all"
                                required></div>
                    </div>
                    <div class="pt-8 flex items-center justify-end gap-3"><button type="submit"
                            class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-white bg-amber-500 hover:bg-amber-600 shadow-xl shadow-amber-500/30">Simpan
                            Record</button></div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @if($showModalImport)
    <div
        class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm animate-fade-in">
        <div
            class="relative w-full max-w-lg bg-white dark:bg-[#18181b] rounded-[2rem] shadow-2xl ring-1 ring-slate-200 dark:ring-white/10 overflow-hidden transform scale-100 flex flex-col">
            <div
                class="bg-amber-50 dark:bg-amber-900/20 px-8 py-6 border-b border-amber-100 dark:border-amber-800/30 flex items-center justify-between shrink-0">
                <div>
                    <h3
                        class="text-base font-black uppercase tracking-widest text-amber-800 dark:text-amber-400 flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-amber-200 dark:bg-amber-500/30 text-amber-700 dark:text-amber-300 flex items-center justify-center">
                            <i class="fas fa-file-excel text-sm"></i></div>Impor Data Massal
                    </h3>
                </div>
                <button wire:click="$set('showModalImport', false)"
                    class="text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-white transition-colors bg-white dark:bg-white/5 border border-amber-200 dark:border-white/10 rounded-full w-10 h-10 flex items-center justify-center shadow-sm"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="p-8">
                <form wire:submit.prevent="importData" class="space-y-6 font-jakarta text-xs uppercase">
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file"
                            class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-300 dark:border-white/10 border-dashed rounded-2xl cursor-pointer bg-slate-50 dark:bg-black/40 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6"><i
                                    class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-3"></i>
                                <p class="mb-2 text-xs font-black text-slate-600 dark:text-slate-300 tracking-widest">
                                    Klik untuk unggah Excel</p>
                                <p class="text-[9px] font-bold text-slate-500 dark:text-slate-400 tracking-widest">XLSX,
                                    CSV (Maks: 500MB)</p>
                            </div>
                            <input id="dropzone-file" type="file" wire:model="file_excel" class="hidden"
                                accept=".xlsx, .xls, .csv" />
                        </label>
                    </div>
                    <div wire:loading wire:target="file_excel"
                        class="w-full p-4 rounded-xl bg-blue-50 text-blue-600 font-bold text-[10px] tracking-widest flex items-center gap-2">
                        <i class="fas fa-circle-notch fa-spin"></i> Membaca file...</div>
                    @if($file_excel) <div
                        class="w-full p-4 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 font-bold text-[10px] tracking-widest flex items-center justify-between">
                        <div class="flex items-center gap-2"><i
                                class="fas fa-file-excel text-lg"></i><span>{{ $file_excel->getClientOriginalName() }}
                                siap.</span></div><i class="fas fa-check-circle text-lg"></i>
                    </div> @endif
                    @error('file_excel') <span
                        class="text-[10px] text-rose-500 font-bold block mt-2">{{ $message }}</span> @enderror
                    <div class="pt-6 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-white/5">
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-white bg-amber-500 hover:bg-amber-600 active:scale-95 transition-all shadow-xl shadow-amber-500/30 flex items-center gap-2 disabled:opacity-50"><span
                                wire:loading.remove wire:target="importData"><i class="fas fa-upload mr-1"></i> Mulai
                                Impor</span><span wire:loading wire:target="importData"><i
                                    class="fas fa-circle-notch fa-spin mr-1"></i> Mengimpor...</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>