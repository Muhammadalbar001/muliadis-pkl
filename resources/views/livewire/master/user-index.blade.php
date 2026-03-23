<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta dark:bg-[#050505] bg-slate-50"
    x-data="{ filterOpen: false }">

    {{-- CSS Animasi & Scrollbar --}}
    <style>
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
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

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, 0.4);
        border-radius: 10px;
    }
    </style>

    {{-- STICKY HEADER (Serasi dengan Master Supplier) --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/10 bg-white/95 border-slate-300 shadow-md">

        <div class="flex flex-col xl:flex-row gap-6 items-center justify-between">

            {{-- Logo & Judul --}}
            <div class="flex items-center gap-4 w-full xl:w-auto shrink-0">
                <div
                    class="p-3 rounded-2xl shadow-xl bg-gradient-to-br from-indigo-600 to-blue-700 text-white ring-4 ring-indigo-500/20">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                        Manajemen <span class="text-indigo-600 dark:text-indigo-400">Akun</span>
                    </h1>
                    <p
                        class="text-[10px] font-extrabold uppercase tracking-[0.2em] mt-1.5 dark:text-slate-400 text-slate-600">
                        Kelola Hak Akses & Otentikasi
                    </p>
                </div>
            </div>

            {{-- Kolom Pencarian & Tombol Aksi --}}
            <div class="flex flex-wrap xl:flex-nowrap items-center gap-3 w-full xl:w-auto justify-end">

                {{-- SEARCH --}}
                <div class="relative w-full sm:w-auto sm:min-w-[200px] xl:w-64 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 dark:text-slate-400 text-slate-500 group-focus-within:text-indigo-600 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 text-[11px] font-bold uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/10 transition-all dark:bg-black/40 dark:border-white/10 dark:text-white bg-white border-slate-200 text-slate-900 placeholder-slate-400 shadow-sm"
                        placeholder="Cari Username/Nama...">
                </div>

                {{-- BUTTON GROUP --}}
                <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end">
                    <button wire:click="openModal"
                        class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-700 hover:from-indigo-700 hover:to-blue-800 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-600/30 transition-all active:scale-95 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Akun</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session()->has('message'))
        <div
            class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm animate-fade-in mb-4">
            <div class="flex items-center gap-2"><i class="fas fa-check-circle text-lg"></i> {{ session('message') }}
            </div>
            <button type="button" class="opacity-50 hover:opacity-100" onclick="this.parentElement.remove()"><i
                    class="fas fa-times"></i></button>
        </div>
        @endif
        @if (session()->has('error'))
        <div
            class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm animate-fade-in mb-4">
            <div class="flex items-center gap-2"><i class="fas fa-exclamation-circle text-lg"></i>
                {{ session('error') }}</div>
            <button type="button" class="opacity-50 hover:opacity-100" onclick="this.parentElement.remove()"><i
                    class="fas fa-times"></i></button>
        </div>
        @endif

        {{-- TABEL DATA PENGGUNA --}}
        <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-300">
            <div
                class="rounded-[2.5rem] border-2 overflow-hidden transition-all duration-300 dark:bg-[#0f0f0f] bg-white dark:border-white/10 border-slate-300 shadow-2xl">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr
                                class="dark:bg-white/5 bg-slate-100 text-slate-800 dark:text-slate-100 font-black text-[10px] uppercase tracking-[0.15em] border-b-2 dark:border-white/10 border-slate-200">
                                <th class="px-6 py-5">Nama Lengkap</th>
                                <th class="px-6 py-5 text-center">Username / Kontak</th>
                                <th class="px-6 py-5 text-center">Role / Hak Akses</th>
                                <th class="px-6 py-5 text-center">Tgl Terdaftar</th>
                                <th
                                    class="px-6 py-5 text-center bg-slate-200/50 dark:bg-white/5 border-l dark:border-white/10 border-slate-200 w-28">
                                    Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 dark:divide-white/5 divide-slate-200">
                            @forelse($users as $user)
                            <tr class="hover:bg-indigo-500/[0.04] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-slate-200 dark:bg-white/10 flex items-center justify-center text-slate-500 dark:text-slate-300 font-black text-xs shrink-0 border border-slate-300 dark:border-white/5 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-inner">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div
                                            class="font-black dark:text-white text-slate-900 text-[11px] tracking-tight uppercase group-hover:text-indigo-600 transition-colors">
                                            {{ $user->name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div
                                        class="text-[11px] font-bold text-slate-600 dark:text-slate-300 tracking-wider">
                                        {{ $user->username }}</div>
                                    <div
                                        class="text-[10px] text-slate-400 font-medium mt-1 flex items-center justify-center gap-1">
                                        <i class="fas fa-envelope opacity-60"></i> {{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($user->role == 'pimpinan')
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border-2 border-purple-200 bg-purple-50 text-purple-700 dark:border-purple-500/20 dark:bg-purple-500/10 dark:text-purple-400"><i
                                            class="fas fa-crown mr-1"></i> Pimpinan</span>
                                    @elseif($user->role == 'supervisor')
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border-2 border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-400"><i
                                            class="fas fa-shield-alt mr-1"></i> Supervisor</span>
                                    @elseif($user->role == 'admin')
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border-2 border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400"><i
                                            class="fas fa-keyboard mr-1"></i> Admin</span>
                                    @else
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border-2 border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-500/20 dark:bg-slate-500/10 dark:text-slate-400">{{ $user->role }}</span>
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-4 text-center text-[10px] font-bold tracking-widest text-slate-500 dark:text-slate-400 uppercase">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td
                                    class="px-6 py-4 text-center bg-slate-50/50 dark:bg-white/[0.02] border-l dark:border-white/10 border-slate-200">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="edit({{ $user->id }})"
                                            class="w-9 h-9 rounded-xl dark:bg-white/5 bg-white border-2 border-blue-100 dark:border-white/10 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-user-edit text-xs"></i>
                                        </button>
                                        <button wire:click="delete({{ $user->id }})"
                                            class="w-9 h-9 rounded-xl dark:bg-white/5 bg-white border-2 border-rose-100 dark:border-white/10 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white transition-all shadow-sm"
                                            onclick="confirm('Hapus permanen akun ini?') || event.stopImmediatePropagation()">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-32 text-center opacity-30">
                                    <i class="fas fa-users-slash text-7xl mb-4 text-slate-400"></i>
                                    <p class="text-sm font-black tracking-[0.3em] uppercase text-slate-500">Database
                                        Kosong</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div
                    class="px-6 py-6 border-t-2 dark:border-white/10 border-slate-200 dark:bg-black/20 bg-slate-50 uppercase font-black text-[11px] dark:text-slate-300 text-slate-700">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH / EDIT --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-[110] overflow-y-auto" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4 py-10">
            <div class="fixed inset-0 bg-slate-900/95 backdrop-blur-md" wire:click="closeModal"></div>
            <div
                class="relative dark:bg-[#0a0a0a] bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden border-2 dark:border-white/10 border-slate-300 animate-fade-in transform scale-100">
                <div
                    class="bg-gradient-to-r from-indigo-600 to-blue-700 px-10 py-8 text-white flex justify-between items-center shadow-lg relative">
                    <div class="absolute -right-4 -bottom-4 opacity-10 rotate-12"><i
                            class="fas fa-users-cog text-8xl"></i></div>
                    <div class="relative z-10">
                        <h3 class="font-black uppercase tracking-widest text-lg">
                            <i class="fas {{ $isEditMode ? 'fa-user-edit' : 'fa-user-plus' }} mr-2 text-indigo-300"></i>
                            {{ $isEditMode ? 'Edit Profil Pengguna' : 'Registrasi Akun Baru' }}
                        </h3>
                        <p class="text-[10px] font-bold opacity-80 uppercase tracking-[0.2em] mt-1 italic">Kredensial &
                            Otentikasi Sistem</p>
                    </div>
                    <button wire:click="closeModal"
                        class="relative z-10 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 flex items-center justify-center transition-all text-white font-bold">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                    <div class="p-10 space-y-6 font-jakarta">
                        <div>
                            <label
                                class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Nama
                                Lengkap</label>
                            <input type="text" wire:model="name"
                                class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black uppercase focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 dark:text-white text-slate-900 shadow-inner"
                                placeholder="Masukkan nama pengguna">
                            @error('name') <span
                                class="text-rose-600 text-[10px] font-black mt-2 ml-1 block uppercase">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Username</label>
                                <input type="text" wire:model="username"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black lowercase focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 dark:text-white text-slate-900 shadow-inner"
                                    placeholder="tanpa_spasi">
                                @error('username') <span
                                    class="text-rose-600 text-[10px] font-black mt-2 ml-1 block uppercase">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Email
                                    Aktif</label>
                                <input type="email" wire:model="email"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 dark:text-white text-slate-900 shadow-inner"
                                    placeholder="email@domain.com">
                                @error('email') <span
                                    class="text-rose-600 text-[10px] font-black mt-2 ml-1 block uppercase">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Peran
                                    (Role)</label>
                                <div class="relative">
                                    <select wire:model="role"
                                        class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black uppercase focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 dark:text-white text-slate-900 shadow-inner appearance-none cursor-pointer">
                                        <option value="admin">Admin Operasional</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="pimpinan">Pimpinan</option>
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                </div>
                                @error('role') <span
                                    class="text-rose-600 text-[10px] font-black mt-2 ml-1 block uppercase">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Kata
                                    Sandi</label>
                                <input type="password" wire:model="password"
                                    class="w-full px-5 py-4 rounded-2xl border-2 dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-xs font-black focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 dark:text-white text-slate-900 shadow-inner"
                                    placeholder="{{ $isEditMode ? 'Abaikan jika tidak diubah' : 'Minimal 6 karakter' }}">
                                @error('password') <span
                                    class="text-rose-600 text-[10px] font-black mt-2 ml-1 block uppercase">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div
                        class="dark:bg-white/[0.02] bg-slate-100 px-10 py-8 flex justify-end gap-4 border-t-2 dark:border-white/10 border-slate-200">
                        <button type="button" wire:click="closeModal"
                            class="px-8 py-3 text-[11px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:text-rose-600 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-10 py-3.5 bg-gradient-to-r from-indigo-600 to-blue-700 hover:from-indigo-700 hover:to-blue-800 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-indigo-600/30 transform active:scale-95 transition-all ring-2 ring-white/10">
                            {{ $isEditMode ? 'SIMPAN PERUBAHAN' : 'BUAT AKUN BARU' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>