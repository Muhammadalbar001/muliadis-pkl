<div>
    <div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta bg-slate-50 dark:bg-[#050505]">

        {{-- HEADER & NAVIGASI --}}
        <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/10 bg-white/95 border-slate-300 shadow-md">
            <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
                <div class="flex items-center gap-4 w-full xl:w-auto shrink-0">
                    <div
                        class="p-3 rounded-2xl shadow-xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white ring-4 ring-blue-500/20">
                        <i class="fas fa-user-tie text-xl"></i>
                    </div>
                    <div>
                        <h1
                            class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                            Master <span class="text-blue-600 dark:text-blue-400">Salesman</span>
                        </h1>
                        <p
                            class="text-[9px] font-extrabold uppercase tracking-[0.3em] dark:text-slate-400 text-slate-600 mt-1.5 opacity-90">
                            Manajemen Personel & Performa
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap lg:flex-nowrap items-center gap-3 w-full xl:w-auto justify-end">
                    <div class="relative group min-w-[200px] lg:w-64">
                        <i
                            class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors text-xs"></i>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="pl-10 pr-4 py-2.5 w-full rounded-2xl border-2 text-[11px] font-bold uppercase transition-all
                        dark:bg-white/5 dark:border-white/10 dark:text-white bg-white border-slate-200 text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none"
                            placeholder="Cari Sales / HP / NIK...">
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <button wire:click="autoDiscover"
                            class="px-5 py-2.5 bg-slate-800 dark:bg-white dark:text-slate-900 text-white rounded-2xl text-[10px] font-black uppercase hover:scale-105 transition-all shadow-lg flex items-center gap-2">
                            <i class="fas fa-sync-alt" wire:loading.class="fa-spin" wire:target="autoDiscover"></i>
                            <span class="hidden sm:inline">Sinkron Data</span>
                        </button>
                        <button wire:click="create"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl shadow-blue-600/20 hover:bg-blue-700 hover:scale-105 transition-all active:scale-95 flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Baru</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL DATA --}}
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="rounded-[2.5rem] border-2 overflow-hidden dark:bg-[#0f0f0f] dark:border-white/10 bg-white border-slate-200 shadow-2xl">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr
                                class="dark:bg-white/5 bg-slate-100 text-slate-800 dark:text-slate-100 font-black text-[9px] uppercase tracking-[0.2em] border-b-2 dark:border-white/10 border-slate-200">
                                <th class="px-6 py-5 w-16 text-center">No</th>
                                <th class="px-6 py-5">Kode Salesman</th>
                                <th class="px-6 py-5">Nama Salesman</th>
                                <th class="px-6 py-5">Alamat</th>
                                <th class="px-6 py-5">Tempat Tgl Lahir</th>
                                <th class="px-6 py-5">No. HP</th>
                                <th class="px-6 py-5 text-center">NIK</th>
                                <th class="px-6 py-5 text-center">Status</th>
                                <th class="px-6 py-5 text-center">Cabang</th>
                                <th
                                    class="px-6 py-5 text-right bg-slate-200/50 dark:bg-white/5 border-l dark:border-white/10 border-slate-200">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 dark:divide-white/5 divide-slate-100 uppercase">
                            @forelse($sales as $index => $item)
                            <tr class="hover:bg-blue-600/[0.04] dark:hover:bg-blue-400/[0.02] group transition-all">
                                <td class="px-6 py-4 text-center text-slate-500 font-mono font-bold text-[10px]">
                                    {{ $sales->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 font-mono font-black text-[10px] border border-blue-200">
                                        {{ $item->sales_code ?: 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-black dark:text-white text-slate-900 text-xs tracking-tight">
                                        {{ $item->sales_name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p
                                        class="text-[10px] text-slate-600 dark:text-slate-400 font-bold normal-case italic truncate max-w-[180px]">
                                        {{ $item->alamat ?: '-' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-[10px] font-black text-slate-700 dark:text-slate-300">
                                    {{ $item->tempat_lahir ?: '-' }}<br>
                                    <span
                                        class="opacity-50 font-bold">{{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d/m/Y') : '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->phone) }}"
                                        target="_blank"
                                        class="flex items-center gap-2 text-[10px] font-black text-emerald-600 dark:text-emerald-400 hover:scale-105 transition-transform">
                                        <i class="fab fa-whatsapp text-xs"></i> {{ $item->phone }}
                                    </a>
                                    @else
                                    <span class="text-[9px] font-bold text-slate-400 italic">No Contact</span>
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-4 text-center font-mono text-[10px] text-slate-700 dark:text-slate-300 font-bold">
                                    {{ $item->nik ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-3 py-1.5 rounded-full text-[9px] font-black {{ $item->status == 'Active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }} border-2 transition-all">
                                        {{ $item->status == 'Active' ? 'AKTIF' : 'RESIGN' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200">
                                        {{ $item->city ?: 'N/A' }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 text-right bg-slate-50/50 dark:bg-white/[0.01] border-l dark:border-white/10 border-slate-200">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="manageTargets({{ $item->id }})"
                                            class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all shadow-sm"><i
                                                class="fas fa-crosshairs text-xs"></i></button>
                                        <button wire:click="edit({{ $item->id }})"
                                            class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm"><i
                                                class="fas fa-edit text-xs"></i></button>
                                        <button onclick="confirm('Hapus?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $item->id }})"
                                            class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm"><i
                                                class="fas fa-trash-alt text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10"
                                    class="px-6 py-24 text-center opacity-30 font-black tracking-widest text-xs uppercase">
                                    Database Salesman Kosong</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div
                    class="px-6 py-6 border-t-2 dark:border-white/10 border-slate-200 dark:bg-black/20 bg-slate-50 uppercase font-black text-[11px] dark:text-slate-300 text-slate-700">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL EDIT/TAMBAH --}}
        @if($isOpen)
        <div class="fixed inset-0 z-[160] overflow-y-auto px-4 py-10">
            <div class="flex items-center justify-center min-h-screen">
                <div class="fixed inset-0 bg-slate-900/95 backdrop-blur-md" wire:click="closeModal"></div>
                <div
                    class="relative bg-white dark:bg-[#0d0d0d] rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl border-2 dark:border-white/10 border-slate-300">
                    <div
                        class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-white flex justify-between items-center shadow-lg relative">
                        <div class="absolute -right-4 -bottom-4 opacity-10 rotate-12"><i
                                class="fas fa-user-tie text-8xl"></i></div>
                        <div class="relative z-10">
                            <h3 class="font-black uppercase tracking-widest text-base">Profil Lengkap Salesman</h3>
                            <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest mt-1 italic">
                                {{ $salesId ? 'Perbarui Data Personal' : 'Registrasi Salesman Baru' }}
                            </p>
                        </div>
                        <button wire:click="closeModal"
                            class="relative z-10 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 flex items-center justify-center transition-all text-white font-bold"><i
                                class="fas fa-times"></i></button>
                    </div>

                    <div class="p-10 space-y-6 font-jakarta">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Kode
                                    Salesman</label>
                                <input type="text" wire:model="sales_code"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black uppercase focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 dark:text-white text-slate-900 shadow-inner"
                                    placeholder="CONTOH: SL001">
                                @error('sales_code') <span
                                    class="text-rose-600 text-[10px] font-bold mt-1 ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Nama
                                    Lengkap</label>
                                <input type="text" wire:model="sales_name"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-sm font-black uppercase focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 dark:text-white text-slate-900 shadow-inner">
                                @error('sales_name') <span
                                    class="text-rose-600 text-[10px] font-bold mt-1 ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="group">
                            <label
                                class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Alamat
                                Domisili Lengkap</label>
                            <textarea wire:model="alamat" rows="2"
                                class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-bold dark:text-white text-slate-800 focus:border-blue-600 transition-all outline-none"
                                placeholder="ALAMAT LENGKAP SAAT INI..."></textarea>
                            @error('alamat') <span
                                class="text-rose-600 text-[10px] font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Tempat
                                    Lahir</label>
                                <input type="text" wire:model="tempat_lahir"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black uppercase focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 dark:text-white text-slate-900 shadow-inner">
                                @error('tempat_lahir') <span
                                    class="text-rose-600 text-[10px] font-bold mt-1 ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Tanggal
                                    Lahir</label>
                                <input type="date" wire:model="tanggal_lahir"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 dark:text-white text-slate-900 shadow-inner">
                                @error('tanggal_lahir') <span
                                    class="text-rose-600 text-[10px] font-bold mt-1 ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">No.
                                    HP / WhatsApp</label>
                                <input type="text" wire:model="phone"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 dark:text-white text-slate-900 shadow-inner"
                                    placeholder="0812...">
                                @error('phone') <span
                                    class="text-rose-600 text-[10px] font-bold mt-1 ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Nomor
                                    NIK (KTP)</label>
                                <input type="text" wire:model="nik" maxlength="16"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 dark:text-white text-slate-900 shadow-inner"
                                    placeholder="16 DIGIT">
                                @error('nik') <span
                                    class="text-rose-600 text-[10px] font-bold mt-1 ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1 text-shadow-sm">Status
                                    Karyawan</label>
                                <select wire:model="status"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 dark:text-white text-slate-900">
                                    <option value="Active">AKTIF / BEKERJA</option>
                                    <option value="Inactive">NON-AKTIF / RESIGN</option>
                                </select>
                                @error('status') <span
                                    class="text-rose-600 text-[10px] font-bold mt-1 ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Cabang
                                    Cabang</label>
                                <input type="text" wire:model="city"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black uppercase focus:ring-4 focus:ring-cyan-500/10 focus:border-cyan-600 dark:text-white text-slate-900 shadow-inner"
                                    placeholder="BANJARMASIN">
                                @error('city') <span
                                    class="text-rose-600 text-[10px] font-bold mt-1 ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div
                        class="dark:bg-white/[0.02] bg-slate-100 px-10 py-8 flex justify-end gap-4 border-t-2 dark:border-white/10 border-slate-200">
                        <button wire:click="closeModal"
                            class="px-8 py-3 text-[11px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:text-rose-600 transition-colors">Batal</button>
                        <button wire:click="store"
                            class="px-10 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-800 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-blue-600/30 transform active:scale-95 transition-all ring-2 ring-white/10">
                            SIMPAN DATABASE
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- MODAL TARGET --}}
        @if($isTargetOpen)
        @include('livewire.master.partials.modal-target-sales')
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
        background: rgba(37, 99, 235, 0.3);
        border-radius: 10px;
    }
    </style>
</div>