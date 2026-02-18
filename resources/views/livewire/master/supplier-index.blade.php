<div>
    <div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta"
        x-data="{ filterOpen: false }">

        <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/10 bg-white/95 border-slate-300 shadow-md">

            <div class="flex flex-col xl:flex-row gap-6 items-center justify-between">
                <div class="flex items-center gap-4 w-full xl:w-auto shrink-0">
                    <div
                        class="p-3 rounded-2xl shadow-xl bg-gradient-to-br from-cyan-600 to-blue-700 text-white ring-4 ring-cyan-500/20">
                        <i class="fas fa-truck text-xl"></i>
                    </div>
                    <div>
                        <h1
                            class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                            Master <span class="text-cyan-600 dark:text-cyan-400">Supplier</span>
                        </h1>
                        <p
                            class="text-[10px] font-extrabold uppercase tracking-[0.2em] mt-1.5 dark:text-slate-400 text-slate-600">
                            Database Vendor Utama
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap xl:flex-nowrap items-center gap-2 w-full xl:w-auto justify-end">

                    {{-- SEARCH --}}
                    <div class="relative w-full sm:w-auto sm:min-w-[200px] xl:w-64 group">
                        <i
                            class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 dark:text-slate-400 text-slate-500 group-focus-within:text-cyan-600 transition-colors text-xs"></i>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 text-[11px] font-bold uppercase tracking-widest focus:ring-4 focus:ring-cyan-500/10 transition-all
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-white border-slate-200 text-slate-900 placeholder-slate-400 shadow-sm"
                            placeholder="Cari PT / Narahubung...">
                    </div>

                    {{-- FILTER GROUP --}}
                    <div class="flex items-center gap-2 w-full sm:w-auto overflow-visible">

                        {{-- FILTER KATEGORI --}}
                        <div class="relative shrink-0"
                            x-data="{ open: false, selected: @entangle('filterKategori').live }">
                            <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-3 border-2 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                            dark:bg-black/40 dark:text-slate-200 bg-white border-slate-200 text-slate-800 hover:border-cyan-500 whitespace-nowrap">
                                <span x-text="selected.length > 0 ? selected.length + ' Kategori' : 'Kategori'"></span>
                                <i class="fas fa-filter opacity-60 text-[9px] transition-transform"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                class="absolute left-0 z-[60] mt-2 min-w-[200px] border-2 rounded-2xl shadow-2xl p-2 dark:bg-slate-900 bg-white dark:border-white/10 border-slate-200"
                                style="display: none;">
                                <div @click="selected = []"
                                    class="px-3 py-2 text-[10px] text-rose-600 dark:text-rose-400 font-black uppercase tracking-widest cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-xl mb-1 flex items-center gap-2 border-b dark:border-white/5 border-slate-100">
                                    <i class="fas fa-times-circle"></i> Reset
                                </div>
                                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                    @foreach($optKategori as $k)
                                    <label
                                        class="flex items-center px-3 py-2.5 hover:bg-cyan-50 dark:hover:bg-white/5 rounded-xl cursor-pointer transition-colors group">
                                        <input type="checkbox" value="{{ $k }}" x-model="selected"
                                            class="rounded border-slate-400 text-cyan-600 focus:ring-cyan-500 h-4 w-4">
                                        <span
                                            class="ml-3 text-[10px] font-bold uppercase dark:text-slate-300 text-slate-700 group-hover:text-cyan-600 whitespace-nowrap">{{ $k }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- FILTER CABANG --}}
                        <div class="relative shrink-0"
                            x-data="{ open: false, selected: @entangle('filterCabang').live }">
                            <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-3 border-2 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                            dark:bg-black/40 dark:text-slate-200 bg-white border-slate-200 text-slate-800 hover:border-cyan-500 whitespace-nowrap">
                                <span x-text="selected.length > 0 ? selected.length + ' Cabang' : 'Cabang'"></span>
                                <i class="fas fa-map-marker-alt opacity-60 text-[9px] transition-transform"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                class="absolute left-0 z-[60] mt-2 min-w-[200px] border-2 rounded-2xl shadow-2xl p-2 dark:bg-slate-900 bg-white dark:border-white/10 border-slate-200"
                                style="display: none;">
                                <div @click="selected = []"
                                    class="px-3 py-2 text-[10px] text-rose-600 dark:text-rose-400 font-black uppercase tracking-widest cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-xl mb-1 flex items-center gap-2 border-b dark:border-white/5 border-slate-100">
                                    <i class="fas fa-times-circle"></i> Reset
                                </div>
                                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                    @foreach($optCabang as $c)
                                    <label
                                        class="flex items-center px-3 py-2.5 hover:bg-cyan-50 dark:hover:bg-white/5 rounded-xl cursor-pointer transition-colors group">
                                        <input type="checkbox" value="{{ $c }}" x-model="selected"
                                            class="rounded border-slate-400 text-cyan-600 focus:ring-cyan-500 h-4 w-4">
                                        <span
                                            class="ml-3 text-[10px] font-bold uppercase dark:text-slate-300 text-slate-700 group-hover:text-cyan-600 whitespace-nowrap">{{ $c }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="w-32 shrink-0">
                            <div class="relative">
                                <select wire:model.live="filterStatus"
                                    class="w-full border-2 px-3 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm dark:bg-black/40 dark:text-slate-200 bg-white border-slate-200 text-slate-800 focus:border-cyan-500 outline-none appearance-none transition-all">
                                    <option value="">Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none opacity-40 text-[8px]"></i>
                            </div>
                        </div>
                    </div>

                    {{-- BUTTON GROUP --}}
                    <div class="flex items-center gap-2 shrink-0 ml-0 sm:ml-2 w-full sm:w-auto justify-end">
                        <button wire:click="resetDatabase"
                            wire:confirm="PERINGATAN FATAL:\n\nApakah Anda YAKIN ingin menghapus SEMUA DATA SUPPLIER?\n\nData yang dihapus tidak dapat dikembalikan."
                            class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 transition-all active:scale-95 flex items-center gap-2">
                            <i class="fas fa-trash-alt"></i>
                            <span class="hidden 2xl:inline">Hapus</span>
                        </button>

                        <button wire:click="syncFromProducts"
                            class="px-4 py-2.5 bg-slate-800 dark:bg-white dark:text-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg flex items-center gap-2">
                            <i class="fas fa-sync-alt" wire:loading.class="fa-spin" wire:target="syncFromProducts"></i>
                            <span class="hidden xl:inline">Sinkron</span>
                        </button>

                        <button wire:click="create"
                            class="px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-cyan-600/30 transition-all active:scale-95 flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            <span class="hidden xl:inline">Baru</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL --}}
        <div wire:loading.class="opacity-50 pointer-events-none"
            class="transition-opacity duration-300 max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="rounded-[2.5rem] border-2 overflow-hidden transition-all duration-300 dark:bg-[#0f0f0f] bg-white dark:border-white/10 border-slate-300 shadow-2xl">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr
                                class="dark:bg-white/5 bg-slate-100 text-slate-800 dark:text-slate-100 font-black text-[10px] uppercase tracking-[0.15em] border-b-2 dark:border-white/10 border-slate-200">
                                <th class="px-6 py-5 w-16 text-center">No</th>
                                <th class="px-6 py-5 text-center">Cabang</th>
                                <th class="px-6 py-5 text-center">Kategori</th>
                                <th class="px-6 py-5">Nama Supplier</th>
                                <th class="px-6 py-5">Alamat Kantor</th>
                                <th class="px-6 py-5">Narahubung</th>
                                <th class="px-6 py-5 text-center">WhatsApp / Email</th>
                                <th class="px-6 py-5 text-center">Status</th>
                                <th
                                    class="px-6 py-5 text-center bg-slate-200/50 dark:bg-white/5 border-l dark:border-white/10 border-slate-200 w-28">
                                    Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 dark:divide-white/5 divide-slate-200">
                            @forelse($suppliers as $index => $item)
                            <tr class="hover:bg-cyan-500/[0.04] transition-colors group">
                                <td
                                    class="px-6 py-4 text-center text-slate-600 dark:text-slate-400 font-mono font-bold text-[10px]">
                                    {{ $suppliers->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[9px] font-black tracking-widest border-2 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20 bg-indigo-50 text-indigo-700 border-indigo-200 uppercase">
                                        {{ $item->cabang }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-[10px] font-black text-cyan-700 dark:text-cyan-400 tracking-wider uppercase">
                                        {{ $item->kategori ?? 'UMUM' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="font-black dark:text-white text-slate-900 text-[11px] tracking-tight group-hover:text-cyan-600 transition-colors uppercase italic">
                                        {{ $item->nama_supplier }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-[10px] font-bold dark:text-slate-400 text-slate-700 normal-case italic truncate block max-w-[200px]">
                                        {{ $item->alamat ?? 'Alamat belum disetel' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-full dark:bg-slate-800 bg-slate-200 flex items-center justify-center text-slate-600 dark:text-slate-400 group-hover:bg-cyan-600 group-hover:text-white transition-all shadow-inner border border-slate-300 dark:border-white/5">
                                            <i class="fas fa-user-tie text-[10px]"></i>
                                        </div>
                                        <span
                                            class="text-[10px] font-black dark:text-slate-200 text-slate-800 uppercase tracking-tight">{{ $item->nama_kontak ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        @if($item->telepon)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->telepon) }}"
                                            target="_blank"
                                            class="flex items-center gap-2 text-[10px] font-black text-emerald-600 dark:text-emerald-400 hover:scale-105 transition-transform">
                                            <i class="fab fa-whatsapp text-xs"></i> {{ $item->telepon }}
                                        </a>
                                        @endif
                                        @if($item->email)
                                        <span
                                            class="text-[10px] font-extrabold text-slate-500 dark:text-slate-500 normal-case flex items-center gap-2">
                                            <i class="fas fa-envelope opacity-60"></i> {{ $item->email }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button wire:click="toggleStatus({{ $item->id }})"
                                        class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border-2 transition-all
                                    {{ $item->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </button>
                                </td>
                                <td
                                    class="px-6 py-4 text-center bg-slate-50/50 dark:bg-white/[0.02] border-l dark:border-white/10 border-slate-200">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="edit({{ $item->id }})"
                                            class="w-9 h-9 rounded-xl dark:bg-white/5 bg-white border-2 border-blue-100 dark:border-white/10 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm"><i
                                                class="fas fa-edit text-xs"></i></button>
                                        <button wire:click="delete({{ $item->id }})"
                                            class="w-9 h-9 rounded-xl dark:bg-white/5 bg-white border-2 border-rose-100 dark:border-white/10 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white transition-all shadow-sm"
                                            onclick="confirm('Hapus?') || event.stopImmediatePropagation()"><i
                                                class="fas fa-trash-alt text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-32 text-center opacity-30"><i
                                        class="fas fa-truck-loading text-7xl mb-4 text-slate-400"></i>
                                    <p class="text-sm font-black tracking-[0.3em] uppercase text-slate-500">Database
                                        Kosong
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div
                    class="px-6 py-6 border-t-2 dark:border-white/10 border-slate-200 dark:bg-black/20 bg-slate-50 uppercase font-black text-[11px] dark:text-slate-300 text-slate-700">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>

        @if($isOpen)
        <div class="fixed inset-0 z-[110] overflow-y-auto" role="dialog">
            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 bg-slate-900/95 backdrop-blur-md" wire:click="closeModal"></div>
                <div
                    class="relative dark:bg-[#0a0a0a] bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden border-2 dark:border-white/10 border-slate-300">
                    <div
                        class="bg-gradient-to-r from-cyan-600 to-blue-700 px-10 py-8 text-white flex justify-between items-center shadow-lg relative">
                        <div class="absolute -right-4 -bottom-4 opacity-10 rotate-12"><i
                                class="fas fa-truck text-8xl"></i>
                        </div>
                        <div class="relative z-10">
                            <h3 class="font-black uppercase tracking-widest text-lg"><i
                                    class="fas {{ $supplierId ? 'fa-edit' : 'fa-plus-circle' }} mr-2 text-cyan-300"></i>{{ $supplierId ? 'Edit Supplier' : 'Supplier Baru' }}
                            </h3>
                            <p class="text-[10px] font-bold opacity-80 uppercase tracking-[0.2em] mt-1 italic">Detail
                                Narahubung & Kategori Vendor</p>
                        </div>
                        <button wire:click="closeModal"
                            class="relative z-10 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 flex items-center justify-center transition-all text-white font-bold"><i
                                class="fas fa-times"></i></button>
                    </div>
                    <div class="p-10 space-y-6 font-jakarta">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Cabang</label>
                                <div class="relative">
                                    <select wire:model="cabang"
                                        class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black uppercase focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-600 dark:text-white text-slate-900 shadow-inner appearance-none">
                                        <option value="">PILIH CABANG</option>
                                        @foreach($formCabang as $c)
                                        <option value="{{ $c }}">{{ $c }}</option>
                                        @endforeach
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="group"><label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Kategori
                                    (Divisi)</label><input type="text" wire:model="kategori"
                                    placeholder="PBF / MIX / SNACK"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black uppercase focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-600 dark:text-white text-slate-900 shadow-inner">
                            </div>
                        </div>
                        <div class="group"><label
                                class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1 text-shadow-sm">Nama
                                Supplier</label><input type="text" wire:model="nama_supplier"
                                class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-[13px] font-black uppercase focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-600 dark:text-white text-slate-900 shadow-inner"
                                placeholder="NAMA PT / CV">@error('nama_supplier') <span
                                class="text-rose-600 text-[10px] font-black mt-2 ml-1 block uppercase">{{ $message }}</span>
                            @enderror</div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="group"><label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Narahubung
                                    (PIC)</label><input type="text" wire:model="nama_kontak"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black uppercase focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-600 dark:text-white text-slate-900 shadow-inner"
                                    placeholder="NAMA KONTAK"></div>
                            <div class="group"><label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Status</label><select
                                    wire:model="is_active"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-600 dark:text-white text-slate-900 shadow-inner">
                                    <option value="1">SUPPLIER AKTIF</option>
                                    <option value="0">NON-AKTIF</option>
                                </select></div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="group"><label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">No.
                                    HP / WA</label><input type="text" wire:model="telepon"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-600 dark:text-white text-slate-900 shadow-inner"
                                    placeholder="0812..."></div>
                            <div class="group"><label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Email</label><input
                                    type="email" wire:model="email"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-600 dark:text-white text-slate-900 shadow-inner"
                                    placeholder="EMAIL@VENDOR.COM"></div>
                        </div>
                        <div class="group"><label
                                class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Alamat
                                Kantor</label><textarea wire:model="alamat" rows="2"
                                class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-[12px] font-bold dark:text-white text-slate-800 focus:border-cyan-600 transition-all outline-none"
                                placeholder="ALAMAT LENGKAP..."></textarea></div>
                    </div>
                    <div
                        class="dark:bg-white/[0.02] bg-slate-100 px-10 py-8 flex justify-end gap-4 border-t-2 dark:border-white/10 border-slate-200">
                        <button wire:click="closeModal"
                            class="px-8 py-3 text-[11px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:text-rose-600 transition-colors">Batal</button>
                        <button wire:click="store"
                            class="px-10 py-3.5 bg-gradient-to-r from-cyan-600 to-blue-700 hover:from-cyan-700 hover:to-blue-800 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-cyan-600/30 transform active:scale-95 transition-all ring-2 ring-white/10">SIMPAN
                            DATABASE</button>
                    </div>
                </div>
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
        background: rgba(6, 182, 212, 0.4);
        border-radius: 10px;
    }
    </style>
</div>