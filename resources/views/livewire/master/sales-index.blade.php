<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta bg-slate-50 dark:bg-[#050505]">

    {{-- HEADER & NAVIGASI --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/95 border-slate-200 shadow-sm">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div
                    class="p-3 rounded-2xl shadow-xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white ring-4 ring-blue-500/10">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                        Master <span class="text-blue-600">Salesman</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] dark:text-slate-400 text-slate-600 mt-1.5 opacity-80">
                        Manajemen Personel & Target Performa
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 items-center justify-end w-full xl:w-auto">
                <div class="relative group">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 pr-4 py-2.5 w-64 rounded-2xl border text-[11px] font-bold uppercase transition-all
                        dark:bg-white/5 dark:border-white/10 dark:text-white dark:focus:border-blue-500/50
                        bg-white border-slate-300 text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none" placeholder="Cari Sales / Kode...">
                </div>

                <div class="flex gap-2">
                    <button wire:click="autoDiscover"
                        class="px-5 py-2.5 bg-slate-800 dark:bg-white dark:text-slate-900 text-white rounded-2xl text-[10px] font-black uppercase hover:scale-105 transition-all shadow-lg flex items-center gap-2">
                        <i class="fas fa-sync-alt" wire:loading.class="fa-spin" wire:target="autoDiscover"></i> Sinkron
                        Data
                    </button>
                    <button wire:click="create"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl shadow-blue-600/20 hover:bg-blue-700 hover:scale-105 transition-all active:scale-95 flex items-center gap-2">
                        <i class="fas fa-plus"></i> Tambah Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="rounded-[2.5rem] border overflow-hidden dark:bg-[#0f0f0f] dark:border-white/5 bg-white border-slate-200 shadow-2xl shadow-slate-200/60 dark:shadow-none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr
                            class="dark:bg-white/[0.02] bg-blue-600 text-white font-black text-[9px] uppercase tracking-[0.2em] border-b dark:border-white/5 border-blue-700">
                            <th class="px-6 py-5">Kode</th>
                            <th class="px-6 py-5">Nama Salesman</th>
                            <th class="px-6 py-5">Alamat</th>
                            <th class="px-6 py-5">Tempat, Tgl Lahir</th>
                            <th class="px-6 py-5">NIK</th>
                            <th class="px-6 py-5 text-center">Status</th>
                            <th class="px-6 py-5">Cabang</th>
                            <th class="px-6 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                        @forelse($sales as $item)
                        <tr class="hover:bg-blue-600/[0.04] dark:hover:bg-blue-400/[0.02] group transition-all">
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 font-mono font-black text-[10px] border border-blue-100 dark:border-blue-500/20">
                                    {{ $item->sales_code ?: 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-black dark:text-white text-slate-900 text-xs uppercase">
                                    {{ $item->sales_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium truncate max-w-xs">
                                    {{ $item->alamat ?: '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase">
                                {{ $item->tempat_lahir ?: '-' }},
                                {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-mono text-[10px] text-slate-500">{{ $item->nik ?: '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $item->status == 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $item->status == 'Active' ? 'AKTIF' : 'NON-AKTIF' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-blue-600 font-black text-[10px] uppercase">
                                    <i class="fas fa-map-marker-alt opacity-50"></i> {{ $item->city ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="manageTargets({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all"><i
                                            class="fas fa-crosshairs text-xs"></i></button>
                                    <button wire:click="edit({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all"><i
                                            class="fas fa-edit text-xs"></i></button>
                                    <button onclick="confirm('Hapus Salesman ini?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all"><i
                                            class="fas fa-trash-alt text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-20 text-center opacity-30">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT/TAMBAH --}}
    @if($isOpen)
    <div class="fixed inset-0 z-[160] overflow-y-auto px-4">
        <div class="flex items-center justify-center min-h-screen">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm" wire:click="closeModal"></div>
            <div
                class="relative bg-white dark:bg-[#0d0d0d] rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl border dark:border-white/10">
                <div class="bg-blue-600 p-8 text-white flex justify-between items-center">
                    <div>
                        <h3 class="font-black uppercase tracking-widest text-sm">Profil Lengkap Salesman</h3>
                        <p class="text-[10px] opacity-70 uppercase">
                            {{ $salesId ? 'Edit Data Manual' : 'Input Sales Baru' }}</p>
                    </div>
                    <button wire:click="closeModal"><i class="fas fa-times"></i></button>
                </div>

                <div class="p-10 space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Kode Salesman</label>
                            <input type="text" wire:model="sales_code"
                                class="w-full rounded-2xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold uppercase p-3"
                                placeholder="Contoh: SL001">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Nama Lengkap</label>
                            <input type="text" wire:model="sales_name"
                                class="w-full rounded-2xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold uppercase p-3">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Alamat Domisili</label>
                        <textarea wire:model="alamat" rows="2"
                            class="w-full rounded-2xl border-slate-200 dark:bg-white/5 dark:text-white text-xs p-3"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Tempat Lahir</label>
                            <input type="text" wire:model="tempat_lahir"
                                class="w-full rounded-2xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold uppercase p-3">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Tanggal Lahir</label>
                            <input type="date" wire:model="tanggal_lahir"
                                class="w-full rounded-2xl border-slate-200 dark:bg-white/5 dark:text-white text-xs p-3">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Nomor NIK (KTP)</label>
                            <input type="text" wire:model="nik" maxlength="16"
                                class="w-full rounded-2xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold p-3">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Cabang /
                                Regional</label>
                            <input type="text" wire:model="city"
                                class="w-full rounded-2xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold uppercase p-3">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Status Karyawan</label>
                        <select wire:model="status"
                            class="w-full rounded-2xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold p-3 appearance-none">
                            <option value="Active">AKTIF / BEKERJA</option>
                            <option value="Inactive">NON-AKTIF / RESIGN</option>
                        </select>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-white/5 p-8 flex justify-end gap-3">
                    <button wire:click="closeModal"
                        class="px-6 py-2 text-[10px] font-black uppercase text-slate-400">Batal</button>
                    <button wire:click="store"
                        class="px-8 py-3 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase shadow-lg shadow-blue-600/30">Simpan
                        Perubahan</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL TARGET TETAP SAMA SEPERTI SEBELUMNYA --}}
    @if($isTargetOpen)
    @include('livewire.master.partials.modal-target-sales')
    @endif
</div>