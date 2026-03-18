<div class="p-6 max-w-7xl mx-auto font-jakarta animate-fade-in pb-20" x-data="{ modalOpen: false, selected: null }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3 tracking-tighter">
                <i class="fas fa-users-viewfinder text-fuchsia-600"></i> Segmentasi Pelanggan (RFM)
            </h2>
            <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-widest">Keterbaruan, Frekuensi, dan Nilai
                Belanja</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="bulan"
                class="rounded-xl border-slate-200 dark:border-white/10 dark:bg-[#121212] text-xs font-bold shadow-sm focus:ring-fuchsia-500 cursor-pointer">
                @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option> @endfor
            </select>
            <select wire:model.live="tahun"
                class="rounded-xl border-slate-200 dark:border-white/10 dark:bg-[#121212] text-xs font-bold shadow-sm focus:ring-fuchsia-500 cursor-pointer">
                @for($y=date('Y')-1; $y<=date('Y'); $y++) <option value="{{ $y }}">{{ $y }}</option> @endfor
            </select>
        </div>
    </div>

    @if(empty($hasilRFM))
    <div
        class="bg-white dark:bg-[#121212] p-10 rounded-3xl border border-slate-200 dark:border-white/5 text-center shadow-sm">
        <i class="fas fa-users-slash text-6xl text-slate-300 dark:text-slate-700 mb-4"></i>
        <h3 class="text-lg font-black text-slate-700 dark:text-slate-300">Belum Ada Data Pelanggan</h3>
        <p class="text-xs text-slate-500 mt-2">Tidak ada transaksi tercatat hingga bulan yang dipilih.</p>
    </div>
    @else

    {{-- BAGIAN 1: SUMMARY KELOMPOK PELANGGAN --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div
            class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 p-4 rounded-2xl flex flex-col items-center justify-center text-center shadow-sm">
            <i class="fas fa-crown text-2xl text-emerald-500 mb-2"></i>
            <h4 class="text-2xl font-black text-emerald-700 dark:text-emerald-400">
                {{ $summary['Pelanggan Utama'] ?? 0 }}</h4>
            <p class="text-[9px] font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-500 mt-1">Utama
            </p>
        </div>
        <div
            class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 p-4 rounded-2xl flex flex-col items-center justify-center text-center shadow-sm">
            <i class="fas fa-handshake text-2xl text-blue-500 mb-2"></i>
            <h4 class="text-2xl font-black text-blue-700 dark:text-blue-400">{{ $summary['Pelanggan Setia'] ?? 0 }}</h4>
            <p class="text-[9px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-500 mt-1">Setia</p>
        </div>
        <div
            class="bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 p-4 rounded-2xl flex flex-col items-center justify-center text-center shadow-sm">
            <i class="fas fa-seedling text-2xl text-cyan-500 mb-2"></i>
            <h4 class="text-2xl font-black text-cyan-700 dark:text-cyan-400">{{ $summary['Pelanggan Potensial'] ?? 0 }}
            </h4>
            <p class="text-[9px] font-bold uppercase tracking-widest text-cyan-600 dark:text-cyan-500 mt-1">Potensial
            </p>
        </div>
        <div
            class="bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/20 p-4 rounded-2xl flex flex-col items-center justify-center text-center shadow-sm">
            <i class="fas fa-user-clock text-2xl text-orange-500 mb-2"></i>
            <h4 class="text-2xl font-black text-orange-700 dark:text-orange-400">{{ $summary['Berisiko Pindah'] ?? 0 }}
            </h4>
            <p class="text-[9px] font-bold uppercase tracking-widest text-orange-600 dark:text-orange-500 mt-1">Berisiko
            </p>
        </div>
        <div
            class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 p-4 rounded-2xl flex flex-col items-center justify-center text-center shadow-sm">
            <i class="fas fa-user-slash text-2xl text-rose-500 mb-2"></i>
            <h4 class="text-2xl font-black text-rose-700 dark:text-rose-400">{{ $summary['Pelanggan Pasif'] ?? 0 }}</h4>
            <p class="text-[9px] font-bold uppercase tracking-widest text-rose-600 dark:text-rose-500 mt-1">Pasif</p>
        </div>
    </div>

    {{-- BAGIAN 2: TABEL ANALISA (KLIK UNTUK DETAIL) --}}
    <div
        class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-200 dark:border-white/5 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-transparent">
            <h4 class="font-black text-slate-800 dark:text-white uppercase tracking-widest text-sm"><i
                    class="fas fa-table text-fuchsia-500 mr-2"></i> Matriks RFM Pelanggan</h4>
            <p class="text-xs text-slate-500 mt-1">Klik pada nama pelanggan untuk melihat rincian penentuan kelompok.
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-slate-800 text-white font-bold uppercase tracking-widest text-[9px]">
                    <tr>
                        <th class="px-5 py-4">Nama Toko/Pelanggan</th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">Keterbaruan (R) <br><span
                                class="text-fuchsia-400 font-normal">Hari Sejak Beli</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">Frekuensi (F) <br><span
                                class="text-fuchsia-400 font-normal">Total Nota</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">Moneter (M) <br><span
                                class="text-fuchsia-400 font-normal">Total Belanja</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">Skor Evaluasi <br><span
                                class="text-fuchsia-400 font-normal">(1-5)</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600 bg-slate-900">Kelompok Pelanggan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-300">
                    @foreach($hasilRFM as $row)
                    <tr @click="selected = {{ json_encode($row) }}; modalOpen = true"
                        class="hover:bg-fuchsia-50 dark:hover:bg-fuchsia-900/20 transition-colors cursor-pointer group">
                        <td
                            class="px-5 py-4 font-black text-sm text-slate-800 dark:text-white group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400">
                            {{ $row['nama'] }} <i
                                class="fas fa-hand-pointer ml-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </td>

                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span class="block font-black">{{ $row['r_raw'] }} Hari</span>
                            <span class="text-[9px] font-bold text-slate-400">Skor: {{ $row['r_score'] }}</span>
                        </td>

                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span class="block font-black">{{ $row['f_raw'] }} Nota</span>
                            <span class="text-[9px] font-bold text-slate-400">Skor: {{ $row['f_score'] }}</span>
                        </td>

                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span class="block font-black text-emerald-600 dark:text-emerald-400">Rp
                                {{ number_format($row['m_raw']/1000000, 1) }} Jt</span>
                            <span class="text-[9px] font-bold text-slate-400">Skor: {{ $row['m_score'] }}</span>
                        </td>

                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span
                                class="font-mono font-black text-lg tracking-widest text-fuchsia-600 dark:text-fuchsia-400">{{ $row['rfm_concat'] }}</span>
                        </td>

                        <td
                            class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                            <span
                                class="px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-widest border border-{{ $row['color'] }}-200 dark:border-{{ $row['color'] }}-500/30 bg-{{ $row['color'] }}-100 dark:bg-{{ $row['color'] }}-500/10 text-{{ $row['color'] }}-700 dark:text-{{ $row['color'] }}-400">
                                {{ $row['segment'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- MODAL PENJELASAN RFM --}}
    <div x-show="modalOpen" x-transition x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm p-4">
        <div @click.outside="modalOpen = false"
            class="bg-white dark:bg-[#18181b] w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">
            <div class="bg-fuchsia-600 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="font-black tracking-widest uppercase text-sm"><i class="fas fa-search-plus"></i> Detail
                    Penilaian Sistem</h3>
                <button @click="modalOpen = false" class="hover:text-fuchsia-200"><i
                        class="fas fa-times text-lg"></i></button>
            </div>

            <div class="p-6" x-if="selected">
                <div class="mb-5 text-center">
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Analisis Pola Belanja Toko
                    </p>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white mt-1" x-text="selected.nama"></h2>
                </div>

                <div class="space-y-3 text-xs text-slate-700 dark:text-slate-300">
                    <div
                        class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/10">
                        <div>
                            <p class="font-bold text-slate-800 dark:text-white">Keterbaruan Belanja (R)</p>
                            <p class="text-[10px] text-slate-500">Terakhir beli: <span class="font-bold text-rose-500"
                                    x-text="selected.r_raw + ' Hari yang lalu'"></span></p>
                        </div>
                        <div
                            class="bg-white dark:bg-black px-3 py-1 rounded-lg font-black text-fuchsia-600 border border-slate-200 dark:border-white/20">
                            Skor: <span x-text="selected.r_score"></span></div>
                    </div>

                    <div
                        class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/10">
                        <div>
                            <p class="font-bold text-slate-800 dark:text-white">Frekuensi Transaksi (F)</p>
                            <p class="text-[10px] text-slate-500">Telah membuat <span class="font-bold text-blue-500"
                                    x-text="selected.f_raw + ' Nota'"></span> tahun ini</p>
                        </div>
                        <div
                            class="bg-white dark:bg-black px-3 py-1 rounded-lg font-black text-fuchsia-600 border border-slate-200 dark:border-white/20">
                            Skor: <span x-text="selected.f_score"></span></div>
                    </div>

                    <div
                        class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/10">
                        <div>
                            <p class="font-bold text-slate-800 dark:text-white">Total Moneter (M)</p>
                            <p class="text-[10px] text-slate-500">Total belanja: Rp <span
                                    class="font-bold text-emerald-500" x-text="selected.m_fmt"></span></p>
                        </div>
                        <div
                            class="bg-white dark:bg-black px-3 py-1 rounded-lg font-black text-fuchsia-600 border border-slate-200 dark:border-white/20">
                            Skor: <span x-text="selected.m_score"></span></div>
                    </div>

                    <div
                        class="mt-5 p-4 bg-fuchsia-50 dark:bg-fuchsia-900/20 rounded-xl border border-fuchsia-200 dark:border-fuchsia-700/50 text-center">
                        <p class="text-[10px] uppercase font-bold text-fuchsia-700 dark:text-fuchsia-400 mb-1">
                            Kesimpulan Sistem Cerdas</p>
                        <p>Kombinasi skor (<strong x-text="selected.rfm_concat"></strong>) menunjukkan bahwa toko ini
                            termasuk ke dalam kelompok:</p>
                        <p class="text-xl font-black mt-2 text-slate-900 dark:text-white uppercase tracking-widest"
                            x-text="selected.segment"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>