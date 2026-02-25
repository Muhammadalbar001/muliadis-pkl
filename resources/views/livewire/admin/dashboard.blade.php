<div class="p-6 max-w-7xl mx-auto space-y-6 animate-fade-in">
    {{-- CSS Animasi --}}
    <style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
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

    {{-- 1. HEADER & QUICK UPLOAD ACTIONS --}}
    <div
        class="bg-white dark:bg-[#121212] rounded-2xl p-8 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden">
        <div
            class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-blue-50 to-transparent dark:from-blue-900/10 pointer-events-none">
        </div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <div class="flex-1">
                <p class="text-blue-600 dark:text-blue-400 font-bold tracking-wider text-xs mb-2 uppercase">
                    Pusat Operasional Data
                </p>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                    Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm max-w-xl leading-relaxed">
                    Hari ini adalah <strong>{{ $hariIni }}</strong>. Pastikan Anda telah mengunggah (import) semua file
                    Excel operasional terbaru agar data sistem tetap terbarui.
                </p>
            </div>

            {{-- TOMBOL UPLOAD LENGKAP & RAPI --}}
            <div class="grid grid-cols-2 sm:flex sm:flex-wrap sm:justify-end gap-2 shrink-0">
                <a href="{{ route('transaksi.penjualan') }}"
                    class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm shadow-blue-600/20 hover:-translate-y-0.5">
                    <i class="fas fa-shopping-cart w-4 text-center"></i> Penjualan
                </a>

                <a href="{{ route('transaksi.retur') }}"
                    class="flex items-center justify-center gap-2 bg-white dark:bg-[#1e1e1e] text-rose-600 dark:text-rose-400 border border-slate-200 dark:border-white/10 hover:border-rose-300 dark:hover:border-rose-500/50 hover:bg-rose-50 dark:hover:bg-rose-500/10 px-4 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm hover:-translate-y-0.5">
                    <i class="fas fa-undo-alt w-4 text-center"></i> Retur
                </a>

                <a href="{{ route('transaksi.ar') }}"
                    class="flex items-center justify-center gap-2 bg-white dark:bg-[#1e1e1e] text-amber-600 dark:text-amber-400 border border-slate-200 dark:border-white/10 hover:border-amber-300 dark:hover:border-amber-500/50 hover:bg-amber-50 dark:hover:bg-amber-500/10 px-4 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm hover:-translate-y-0.5">
                    <i class="fas fa-file-invoice-dollar w-4 text-center"></i> Piutang
                </a>

                <a href="{{ route('transaksi.collection') }}"
                    class="flex items-center justify-center gap-2 bg-white dark:bg-[#1e1e1e] text-emerald-600 dark:text-emerald-400 border border-slate-200 dark:border-white/10 hover:border-emerald-300 dark:hover:border-emerald-500/50 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 px-4 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm hover:-translate-y-0.5">
                    <i class="fas fa-money-bill-wave w-4 text-center"></i> Pelunasan
                </a>
            </div>
        </div>
    </div>

    {{-- [BARU] CHECKLIST UPLOAD CABANG HARI INI --}}
    <div class="bg-white dark:bg-[#121212] rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-tasks text-blue-500"></i> Status Import Penjualan Hari Ini
            </h3>
            <span class="text-xs font-medium text-slate-500">Berdasarkan tanggal transaksi</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            @foreach($statusUploadCabang as $cabang => $isUploaded)
            <div
                class="flex items-center justify-between p-3 rounded-xl border {{ $isUploaded ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-900/50 dark:bg-emerald-900/10' : 'border-slate-200 bg-slate-50 dark:border-white/5 dark:bg-white/5' }} transition-colors">
                <span
                    class="text-sm font-semibold {{ $isUploaded ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400' }}">
                    {{ $cabang }}
                </span>
                @if($isUploaded)
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                @else
                <i class="far fa-clock text-slate-400 text-lg"></i>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- 2. METRIK DATA MASUK HARI INI --}}
    <div>
        <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Ringkasan Data Masuk (Transaksi Hari Ini)</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div
                class="bg-white dark:bg-[#121212] rounded-xl p-5 border border-slate-200 dark:border-white/10 shadow-sm flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase">Faktur Jual</p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($jualHariIni) }}
                        <span class="text-[10px] text-slate-400 font-normal">Baris</span></h3>
                </div>
            </div>

            <div
                class="bg-white dark:bg-[#121212] rounded-xl p-5 border border-slate-200 dark:border-white/10 shadow-sm flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-lg bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <i class="fas fa-undo-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase">Faktur Retur</p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($returHariIni) }}
                        <span class="text-[10px] text-slate-400 font-normal">Baris</span></h3>
                </div>
            </div>

            <div
                class="bg-white dark:bg-[#121212] rounded-xl p-5 border border-slate-200 dark:border-white/10 shadow-sm flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <i class="fas fa-hand-holding-usd text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase">Piutang Baru</p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($piutangHariIni) }}
                        <span class="text-[10px] text-slate-400 font-normal">Toko</span></h3>
                </div>
            </div>

            <div
                class="bg-white dark:bg-[#121212] rounded-xl p-5 border border-slate-200 dark:border-white/10 shadow-sm flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase">Lunas (BKM)</p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">
                        {{ number_format($pelunasanHariIni) }} <span
                            class="text-[10px] text-slate-400 font-normal">Slip</span></h3>
                </div>
            </div>

        </div>
    </div>

    {{-- 3. RECENT ACTIVITY (DATA TERBARU DI DATABASE) --}}
    <div
        class="bg-white dark:bg-[#121212] rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
        <div
            class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/20 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-history text-slate-400"></i> Riwayat Input Terakhir di Sistem
            </h3>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">Jenis Data</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">No. Dokumen / Invoice</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider">Cabang</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase tracking-wider text-right">Waktu Import
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                    {{-- Loop Penjualan --}}
                    @foreach($recentPenjualan as $jual)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 bg-blue-50 border border-blue-200 px-2 py-1 rounded dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-400">
                                <i class="fas fa-shopping-cart text-[10px]"></i> Penjualan
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-200">{{ $jual->no_penjualan }}
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $jual->cabang }}</td>
                        <td class="px-6 py-4 text-right text-xs text-slate-400">{{ $jual->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach

                    {{-- Loop Retur --}}
                    @foreach($recentRetur as $ret)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 px-2 py-1 rounded dark:bg-rose-900/30 dark:border-rose-800 dark:text-rose-400">
                                <i class="fas fa-undo text-[10px]"></i> Retur
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-200">{{ $ret->no_retur }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $ret->cabang }}</td>
                        <td class="px-6 py-4 text-right text-xs text-slate-400">{{ $ret->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach

                    @if($recentPenjualan->isEmpty() && $recentRetur->isEmpty())
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                            Belum ada data yang diunggah baru-baru ini.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>