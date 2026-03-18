<div class="p-6 max-w-7xl mx-auto space-y-6 animate-fade-in relative">
    {{-- CSS Animasi --}}
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
    </style>

    {{-- ALERT SUKSES PENGAJUAN --}}
    @if (session()->has('message'))
    <div
        class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm animate-fade-in mb-4">
        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-lg"></i> {{ session('message') }}</div>
        <button type="button" class="opacity-50 hover:opacity-100" onclick="this.parentElement.remove()"><i
                class="fas fa-times"></i></button>
    </div>
    @endif

    {{-- 1. HEADER & QUICK ACTIONS --}}
    <div
        class="bg-white dark:bg-[#121212] rounded-2xl p-8 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden">
        <div
            class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-blue-50 to-transparent dark:from-blue-900/10 pointer-events-none">
        </div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <div class="flex-1">
                <p class="text-blue-600 dark:text-blue-400 font-bold tracking-wider text-xs mb-2 uppercase">Pusat
                    Operasional Data</p>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                    Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm max-w-xl leading-relaxed">
                    Hari ini adalah <strong>{{ $hariIni }}</strong>. Pastikan Anda mengunggah file Excel operasional
                    terbaru. Gunakan fitur pengajuan hapus jika terjadi salah *import*.
                </p>
            </div>

            {{-- TOMBOL UPLOAD LENGKAP & AJUKAN HAPUS --}}
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('transaksi.penjualan') }}"
                    class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm shadow-blue-600/20 hover:-translate-y-0.5">
                    <i class="fas fa-shopping-cart w-4 text-center"></i> Penjualan
                </a>
                <a href="{{ route('transaksi.retur') }}"
                    class="flex items-center justify-center gap-2 bg-white dark:bg-[#1e1e1e] text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm hover:-translate-y-0.5">
                    <i class="fas fa-undo-alt w-4 text-center"></i> Retur
                </a>
                <a href="{{ route('transaksi.ar') }}"
                    class="flex items-center justify-center gap-2 bg-white dark:bg-[#1e1e1e] text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm hover:-translate-y-0.5">
                    <i class="fas fa-file-invoice-dollar w-4 text-center"></i> Piutang
                </a>
                <a href="{{ route('transaksi.collection') }}"
                    class="flex items-center justify-center gap-2 bg-white dark:bg-[#1e1e1e] text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm hover:-translate-y-0.5">
                    <i class="fas fa-money-bill-wave w-4 text-center"></i> Pelunasan
                </a>

                <button wire:click="openModal"
                    class="flex items-center justify-center gap-2 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 hover:bg-rose-100 dark:hover:bg-rose-500/20 px-4 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm hover:-translate-y-0.5 ml-2">
                    <i class="fas fa-trash-alt text-center"></i> Ajukan Hapus Data
                </button>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- SMART ALERTS: PENGINGAT TUGAS & NOTIFIKASI KHUSUS ADMIN  --}}
    {{-- ======================================================== --}}
    @if(isset($alerts) && count($alerts) > 0)
    <div class="space-y-3" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
        <div class="flex items-center justify-between px-2 mb-2 mt-4">
            <h3
                class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400 flex items-center gap-2">
                <i class="fas fa-bell text-blue-500 animate-pulse"></i> Pengingat Tugas Harian
            </h3>
            <button @click="show = false"
                class="text-[10px] font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Tutup
                Semua</button>
        </div>

        @foreach($alerts as $alert)
        @php
        $bgClass = $alert['type'] == 'danger' ? 'bg-rose-50 dark:bg-rose-500/10 border-rose-200 dark:border-rose-500/30'
        :
        ($alert['type'] == 'warning' ? 'bg-orange-50 dark:bg-orange-500/10 border-orange-200 dark:border-orange-500/30'
        :
        ($alert['type'] == 'success' ? 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200
        dark:border-emerald-500/30' :
        'bg-blue-50 dark:bg-blue-500/10 border-blue-200 dark:border-blue-500/30'));

        $textClass = $alert['type'] == 'danger' ? 'text-rose-700 dark:text-rose-400' :
        ($alert['type'] == 'warning' ? 'text-orange-700 dark:text-orange-400' :
        ($alert['type'] == 'success' ? 'text-emerald-700 dark:text-emerald-400' :
        'text-blue-700 dark:text-blue-400'));

        $iconClass = $alert['type'] == 'danger' ? 'bg-rose-100 text-rose-600 dark:bg-rose-900/40 dark:text-rose-400' :
        ($alert['type'] == 'warning' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/40 dark:text-orange-400' :
        ($alert['type'] == 'success' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400' :
        'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400'));
        @endphp

        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-2xl border {{ $bgClass }} shadow-sm gap-4 transition-transform hover:-translate-y-0.5">
            <div class="flex items-start gap-4">
                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $iconClass }} shadow-inner">
                    <i
                        class="{{ $alert['icon'] }} text-lg {{ $alert['type'] == 'danger' || $alert['type'] == 'warning' ? 'animate-pulse' : '' }}"></i>
                </div>
                <div>
                    <h4 class="font-black text-sm uppercase tracking-widest mb-1 {{ $textClass }}">{{ $alert['title'] }}
                    </h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{!! $alert['message'] !!}</p>
                </div>
            </div>
            @if(isset($alert['link']))
            <a href="{{ $alert['link'] }}"
                class="shrink-0 px-4 py-2 bg-white dark:bg-[#18181b] border border-slate-200 dark:border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $textClass }} hover:bg-slate-50 dark:hover:bg-white/5 transition-colors shadow-sm self-start sm:self-center text-center">
                Lakukan Import <i class="fas fa-arrow-right ml-1"></i>
            </a>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- 3. SPLIT PANEL: RECENT ACTIVITY & RIWAYAT PENGAJUAN --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div
            class="xl:col-span-2 bg-white dark:bg-[#121212] rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden flex flex-col">
            <div
                class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/20 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2 text-sm">
                    <i class="fas fa-history text-slate-400"></i> Log Import Terakhir
                </h3>
            </div>
            <div class="p-0 overflow-x-auto flex-1">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead
                        class="text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-transparent">
                        <tr>
                            <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">Modul</th>
                            <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">No. Dokumen</th>
                            <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">Cabang</th>
                            <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider text-right">Waktu Import
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($recentPenjualan as $jual)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4"><span
                                    class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded dark:bg-blue-900/30 dark:text-blue-400">Penjualan</span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-200">
                                {{ $jual->no_penjualan }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $jual->cabang }}</td>
                            <td class="px-6 py-4 text-right text-xs text-slate-400">
                                {{ $jual->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                        @foreach($recentRetur as $ret)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4"><span
                                    class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded dark:bg-rose-900/30 dark:text-rose-400">Retur</span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-200">{{ $ret->no_retur }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $ret->cabang }}</td>
                            <td class="px-6 py-4 text-right text-xs text-slate-400">
                                {{ $ret->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                        @if($recentPenjualan->isEmpty() && $recentRetur->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada data.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="xl:col-span-1 bg-white dark:bg-[#121212] rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden flex flex-col">
            <div
                class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/20 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2 text-sm">
                    <i class="fas fa-file-signature text-rose-500"></i> Status Pengajuan Anda
                </h3>
            </div>
            <div class="p-0 overflow-y-auto flex-1">
                <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($riwayatPengajuan as $pengajuan)
                    <li class="p-4 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-black uppercase text-slate-700 dark:text-slate-300">
                                {{ strtoupper($pengajuan->tipe_modul) }}
                            </span>
                            @if($pengajuan->status == 'pending')
                            <span
                                class="bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 text-[9px] font-bold px-2 py-0.5 rounded-full border border-amber-200 dark:border-amber-500/30 animate-pulse">Menunggu
                                ACC</span>
                            @elseif($pengajuan->status == 'approved')
                            <span
                                class="bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 text-[9px] font-bold px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-500/30">Disetujui</span>
                            @else
                            <span
                                class="bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400 text-[9px] font-bold px-2 py-0.5 rounded-full border border-rose-200 dark:border-rose-500/30">Ditolak</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-500 mb-1">
                            <i class="far fa-calendar-alt w-3"></i>
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d/m/Y') }}
                        </p>
                        <p class="text-[10px] text-slate-400 italic truncate" title="{{ $pengajuan->alasan }}">
                            "{{ $pengajuan->alasan }}"</p>
                    </li>
                    @empty
                    <div class="p-8 text-center text-slate-500">
                        <i class="fas fa-check-circle text-3xl mb-2 text-emerald-500 opacity-50"></i>
                        <p class="text-xs font-medium">Belum ada pengajuan hapus.</p>
                    </div>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

    {{-- MODAL POP-UP PENGAJUAN HAPUS --}}
    @if($showModal)
    <div
        class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm p-4">
        <div
            class="relative w-full max-w-md bg-white dark:bg-[#18181b] rounded-2xl shadow-2xl ring-1 ring-slate-200 dark:ring-white/10 overflow-hidden animate-fade-in transform scale-100">
            <div
                class="bg-rose-50 dark:bg-rose-900/20 px-6 py-4 border-b border-rose-100 dark:border-rose-800/30 flex items-center justify-between">
                <h3 class="text-lg font-black text-rose-700 dark:text-rose-400 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> Form Pengajuan Hapus
                </h3>
                <button wire:click="$set('showModal', false)"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form wire:submit.prevent="submitDeletionRequest" class="p-6 space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Pilih Modul Data
                        <span class="text-rose-500">*</span></label>
                    <select wire:model="tipe_modul"
                        class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-[#121212] text-slate-800 dark:text-white text-sm focus:ring-rose-500 focus:border-rose-500 cursor-pointer">
                        <option value="">-- Klik untuk Pilih Modul --</option>
                        <option value="penjualan">Tabel Penjualan</option>
                        <option value="retur">Tabel Retur</option>
                        <option value="ar">Tabel Piutang (AR)</option>
                        <option value="collection">Tabel Pelunasan (Collection)</option>
                    </select>
                    @error('tipe_modul') <span
                        class="text-[10px] text-rose-600 dark:text-rose-400 font-bold block mt-1"><i
                            class="fas fa-info-circle"></i> {{ $message }}</span> @enderror
                </div>

                <div
                    class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-white/5 p-3 rounded-xl border border-slate-100 dark:border-transparent">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Dari
                            Tanggal Transaksi <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="tanggal_mulai"
                            class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-[#121212] text-slate-800 dark:text-white text-xs font-bold focus:ring-rose-500 focus:border-rose-500 [color-scheme:light] dark:[color-scheme:dark]">
                        @error('tanggal_mulai') <span
                            class="text-[10px] text-rose-600 dark:text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Sampai
                            Tanggal Transaksi <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="tanggal_selesai"
                            class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-[#121212] text-slate-800 dark:text-white text-xs font-bold focus:ring-rose-500 focus:border-rose-500 [color-scheme:light] dark:[color-scheme:dark]">
                        @error('tanggal_selesai') <span
                            class="text-[10px] text-rose-600 dark:text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alasan Penghapusan
                        <span class="text-rose-500">*</span></label>
                    <textarea wire:model="alasan" rows="3"
                        placeholder="Contoh: Terjadi kesalahan format file Excel pada baris tanggal sehingga data yang masuk bulan lalu..."
                        class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-[#121212] text-slate-800 dark:text-white text-sm focus:ring-rose-500 focus:border-rose-500 placeholder-slate-400 dark:placeholder-slate-600"></textarea>
                    @error('alasan') <span class="text-[10px] text-rose-600 dark:text-rose-400 font-bold block mt-1"><i
                            class="fas fa-info-circle"></i> {{ $message }}</span> @enderror
                    <p class="text-[10px] text-slate-500 mt-1.5 leading-tight">Jelaskan secara logis agar Supervisor
                        dapat memahami dan menyetujui pengajuan ini. Data yang dihapus tidak dapat dikembalikan.</p>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                        Batal
                    </button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-95 transition-all shadow-md shadow-rose-500/20 flex items-center gap-2">
                        <span wire:loading.remove wire:target="submitDeletionRequest"><i class="fas fa-paper-plane"></i>
                            Kirim Pengajuan</span>
                        <span wire:loading wire:target="submitDeletionRequest"><i
                                class="fas fa-circle-notch fa-spin"></i> Mengirim...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>