<div class="p-6 max-w-7xl mx-auto font-jakarta animate-fade-in pb-20" x-data="{ modalOpen: false, selected: null }">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3 tracking-tighter">
                <i class="fas fa-brain text-blue-600"></i> SPK Kinerja Salesman
            </h2>
            <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-widest">Metode Penjumlahan Terbobot (SAW)
            </p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="bulan"
                class="rounded-xl border-slate-200 dark:border-white/10 dark:bg-[#121212] text-xs font-bold shadow-sm focus:ring-blue-500">
                @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option> @endfor
            </select>
            <select wire:model.live="tahun"
                class="rounded-xl border-slate-200 dark:border-white/10 dark:bg-[#121212] text-xs font-bold shadow-sm focus:ring-blue-500">
                @for($y=date('Y')-1; $y<=date('Y'); $y++) <option value="{{ $y }}">{{ $y }}</option> @endfor
            </select>
        </div>
    </div>

    @if(empty($hasilSPK))
    <div
        class="bg-white dark:bg-[#121212] p-10 rounded-3xl border border-slate-200 dark:border-white/5 text-center shadow-sm">
        <i class="fas fa-folder-open text-6xl text-slate-300 dark:text-slate-700 mb-4"></i>
        <h3 class="text-lg font-black text-slate-700 dark:text-slate-300">Belum Ada Data Transaksi</h3>
        <p class="text-xs text-slate-500 mt-2">Pilih bulan dan tahun yang memiliki data penjualan.</p>
    </div>
    @else

    {{-- BAGIAN 1: HASIL KEPUTUSAN TERBAIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @foreach(array_slice($hasilSPK, 0, 3) as $index => $juara)
        <div
            class="bg-gradient-to-br {{ $index == 0 ? 'from-amber-400 to-orange-500' : ($index == 1 ? 'from-slate-300 to-slate-500' : 'from-amber-600 to-amber-800') }} p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group">
            <i
                class="fas fa-trophy absolute -right-6 -bottom-6 text-9xl opacity-20 transform group-hover:scale-110 transition-transform"></i>
            <div class="relative z-10">
                <span
                    class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest backdrop-blur-sm shadow-sm">Peringkat
                    {{ $index + 1 }}</span>
                <h3 class="text-xl font-black mt-4 truncate">{{ $juara['nama'] }}</h3>
                <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest mt-1 mb-4">{{ $juara['cabang'] }}
                </p>
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-md border border-white/20">
                    <p class="text-[10px] uppercase font-bold opacity-80 mb-1">Nilai Preferensi (V)</p>
                    <p class="text-4xl font-black">{{ number_format($juara['skor_akhir'], 3) }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- BAGIAN 2: TABEL PROSES (KLIK UNTUK DETAIL) --}}
    <div
        class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-200 dark:border-white/5 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-transparent">
            <h4 class="font-black text-slate-800 dark:text-white uppercase tracking-widest text-sm"><i
                    class="fas fa-calculator text-blue-500 mr-2"></i> Matriks Keputusan & Normalisasi</h4>
            <p class="text-xs text-slate-500 mt-1">Klik pada nama Salesman untuk melihat rincian perhitungan matematis.
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-slate-800 text-white font-bold uppercase tracking-widest text-[9px]">
                    <tr>
                        <th class="px-5 py-4 text-center">Peringkat</th>
                        <th class="px-5 py-4">Nama Salesman</th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">K1 (Omzet) <br><span
                                class="text-blue-400">Keuntungan 40%</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">K2 (Nota) <br><span
                                class="text-blue-400">Keuntungan 20%</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">K3 (Retur) <br><span
                                class="text-rose-400">Biaya 20%</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">K4 (Piutang) <br><span
                                class="text-rose-400">Biaya 20%</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600 bg-slate-900 text-amber-400 text-sm">
                            Nilai (V)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-300">
                    @foreach($hasilSPK as $index => $row)
                    <tr @click="selected = {{ json_encode($row) }}; modalOpen = true"
                        class="hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors cursor-pointer group">
                        <td class="px-5 py-4 text-center font-black text-slate-900 dark:text-white">{{ $index + 1 }}
                        </td>
                        <td class="px-5 py-4 font-bold group-hover:text-blue-600 dark:group-hover:text-blue-400">
                            {{ $row['nama'] }} <i
                                class="fas fa-hand-pointer ml-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </td>
                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span class="block text-[10px] text-slate-400 mb-1">Rp
                                {{ number_format($row['omzet']/1000000, 1) }} Jt</span>
                            <span class="font-black text-blue-600 dark:text-blue-400">{{ $row['n1'] }}</span>
                        </td>
                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span class="font-black text-blue-600 dark:text-blue-400">{{ $row['n2'] }}</span>
                        </td>
                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span class="block text-[10px] text-slate-400 mb-1">Rp
                                {{ number_format($row['retur']/1000000, 1) }} Jt</span>
                            <span class="font-black text-rose-600 dark:text-rose-400">{{ $row['n3'] }}</span>
                        </td>
                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span class="font-black text-rose-600 dark:text-rose-400">{{ $row['n4'] }}</span>
                        </td>
                        <td
                            class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5 font-black text-lg bg-amber-50 dark:bg-amber-900/10 text-amber-700 dark:text-amber-500">
                            {{ number_format($row['skor_akhir'], 3) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- MODAL PENJELASAN RUMUS --}}
    <div x-show="modalOpen" x-transition x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm p-4">
        <div @click.outside="modalOpen = false"
            class="bg-white dark:bg-[#18181b] w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden">
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="font-black tracking-widest uppercase text-sm"><i class="fas fa-search-plus"></i> Detail
                    Perhitungan SAW</h3>
                <button @click="modalOpen = false" class="hover:text-blue-200"><i
                        class="fas fa-times text-lg"></i></button>
            </div>

            <div class="p-6" x-if="selected">
                <div class="mb-4">
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Nama Alternatif (Salesman)
                    </p>
                    <h2 class="text-xl font-black text-slate-800 dark:text-white" x-text="selected.nama"></h2>
                </div>

                <div class="space-y-4 text-xs text-slate-700 dark:text-slate-300">
                    <div
                        class="bg-slate-50 dark:bg-white/5 p-3 rounded-xl border border-slate-200 dark:border-white/10">
                        <p class="font-bold text-blue-600 dark:text-blue-400 mb-1">Kriteria 1: Omzet (Atribut
                            Keuntungan)</p>
                        <p>Rumus Normalisasi: <span class="font-mono bg-white dark:bg-black px-1 rounded">Nilai / Nilai
                                Maksimal</span></p>
                        <p class="font-mono mt-1">= Rp <span x-text="selected.omzet_fmt"></span> / Rp <span
                                x-text="selected.max_c1_fmt"></span> = <strong x-text="selected.n1"></strong></p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-white/5 p-3 rounded-xl border border-slate-200 dark:border-white/10">
                        <p class="font-bold text-blue-600 dark:text-blue-400 mb-1">Kriteria 2: Frekuensi Transaksi
                            (Atribut Keuntungan)</p>
                        <p>Rumus Normalisasi: <span class="font-mono bg-white dark:bg-black px-1 rounded">Nilai / Nilai
                                Maksimal</span></p>
                        <p class="font-mono mt-1">= <span x-text="selected.trans_fmt"></span> / <span
                                x-text="selected.max_c2_fmt"></span> = <strong x-text="selected.n2"></strong></p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-white/5 p-3 rounded-xl border border-slate-200 dark:border-white/10">
                        <p class="font-bold text-rose-600 dark:text-rose-400 mb-1">Kriteria 3: Total Retur (Atribut
                            Biaya)</p>
                        <p>Rumus Normalisasi: <span class="font-mono bg-white dark:bg-black px-1 rounded">Nilai Minimal
                                / Nilai</span></p>
                        <p class="font-mono mt-1">= Rp <span x-text="selected.min_c3_fmt"></span> / Rp <span
                                x-text="selected.retur_fmt"></span> = <strong x-text="selected.n3"></strong></p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-white/5 p-3 rounded-xl border border-slate-200 dark:border-white/10">
                        <p class="font-bold text-rose-600 dark:text-rose-400 mb-1">Kriteria 4: Piutang Macet (Atribut
                            Biaya)</p>
                        <p>Rumus Normalisasi: <span class="font-mono bg-white dark:bg-black px-1 rounded">Nilai Minimal
                                / Nilai</span></p>
                        <p class="font-mono mt-1">= Rp <span x-text="selected.min_c4_fmt"></span> / Rp <span
                                x-text="selected.piutang_fmt"></span> = <strong x-text="selected.n4"></strong></p>
                    </div>

                    <div
                        class="bg-amber-100 dark:bg-amber-900/20 p-4 rounded-xl border border-amber-200 dark:border-amber-700/50 mt-4">
                        <p
                            class="font-black text-amber-800 dark:text-amber-500 uppercase tracking-widest text-[10px] mb-2">
                            Perhitungan Nilai Akhir (Preferensi / V)</p>
                        <p class="font-mono">V = (N1 × 40%) + (N2 × 20%) + (N3 × 20%) + (N4 × 20%)</p>
                        <p class="font-mono mt-1">V = (<span x-text="selected.n1"></span> × 0.4) + (<span
                                x-text="selected.n2"></span> × 0.2) + (<span x-text="selected.n3"></span> × 0.2) +
                            (<span x-text="selected.n4"></span> × 0.2)</p>
                        <p class="font-mono font-black text-lg mt-2 text-slate-900 dark:text-white">Total Nilai: <span
                                x-text="selected.skor_akhir"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>