<div class="p-6 max-w-7xl mx-auto font-jakarta animate-fade-in pb-20">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3 tracking-tighter">
                <i class="fas fa-brain text-blue-600"></i> SPK Kinerja Salesman
            </h2>
            <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-widest">Metode Penjumlahan Terbobot
                (Simple Additive Weighting)</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="bulan"
                class="rounded-xl border-slate-200 dark:border-white/10 dark:bg-[#121212] text-xs font-bold shadow-sm focus:ring-blue-500 cursor-pointer">
                @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option> @endfor
            </select>
            <select wire:model.live="tahun"
                class="rounded-xl border-slate-200 dark:border-white/10 dark:bg-[#121212] text-xs font-bold shadow-sm focus:ring-blue-500 cursor-pointer">
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

    {{-- ACUAN BOBOT --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-2xl">
            <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">Kriteria 1
                (40%)</span>
            <p class="text-sm font-black text-slate-800 dark:text-white mt-1">Total Omzet</p>
            <p class="text-[9px] text-slate-500 font-bold uppercase mt-1">Sifat: Keuntungan</p>
        </div>
        <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-2xl">
            <span class="text-[9px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Kriteria
                2 (20%)</span>
            <p class="text-sm font-black text-slate-800 dark:text-white mt-1">Jumlah Nota</p>
            <p class="text-[9px] text-slate-500 font-bold uppercase mt-1">Sifat: Keuntungan</p>
        </div>
        <div class="p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 rounded-2xl">
            <span class="text-[9px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest">Kriteria 3
                (20%)</span>
            <p class="text-sm font-black text-slate-800 dark:text-white mt-1">Total Retur</p>
            <p class="text-[9px] text-slate-500 font-bold uppercase mt-1">Sifat: Biaya</p>
        </div>
        <div class="p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-100 dark:border-orange-800 rounded-2xl">
            <span class="text-[9px] font-black text-orange-600 dark:text-orange-400 uppercase tracking-widest">Kriteria
                4 (20%)</span>
            <p class="text-sm font-black text-slate-800 dark:text-white mt-1">Piutang Macet</p>
            <p class="text-[9px] text-slate-500 font-bold uppercase mt-1">Sifat: Biaya</p>
        </div>
    </div>

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

    {{-- BAGIAN 2: TABEL PROSES --}}
    <div
        class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-200 dark:border-white/5 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-transparent">
            <h4 class="font-black text-slate-800 dark:text-white uppercase tracking-widest text-sm"><i
                    class="fas fa-calculator text-blue-500 mr-2"></i> Matriks Normalisasi & Skor Akhir</h4>
            <p class="text-xs text-slate-500 mt-1">Klik baris untuk melihat pembuktian rumus secara detail.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-slate-800 text-white font-bold uppercase tracking-widest text-[9px]">
                    <tr>
                        <th class="px-5 py-4 text-center">Rank</th>
                        <th class="px-5 py-4">Nama Salesman</th>
                        <th class="px-5 py-4 text-center">N1 (Omzet)</th>
                        <th class="px-5 py-4 text-center">N2 (Nota)</th>
                        <th class="px-5 py-4 text-center">N3 (Retur)</th>
                        <th class="px-5 py-4 text-center">N4 (Piutang)</th>
                        <th class="px-5 py-4 text-center bg-slate-900 text-amber-400 text-sm">Nilai Akhir (V)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-300">
                    @foreach($hasilSPK as $index => $row)
                    <tr wire:click="openDetail('{{ $row['nama'] }}')"
                        class="hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors cursor-pointer group">
                        <td class="px-5 py-4 text-center font-black text-slate-900 dark:text-white">{{ $index + 1 }}
                        </td>
                        <td class="px-5 py-4 font-bold group-hover:text-blue-600 dark:group-hover:text-blue-400">
                            {{ $row['nama'] }} <i
                                class="fas fa-search-plus ml-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </td>
                        <td class="px-5 py-4 text-center font-mono">{{ $row['n1'] }}</td>
                        <td class="px-5 py-4 text-center font-mono">{{ $row['n2'] }}</td>
                        <td class="px-5 py-4 text-center font-mono">{{ $row['n3'] }}</td>
                        <td class="px-5 py-4 text-center font-mono">{{ $row['n4'] }}</td>
                        <td
                            class="px-5 py-4 text-center font-black text-lg bg-amber-50 dark:bg-amber-900/10 text-amber-700 dark:text-amber-500">
                            {{ number_format($row['skor_akhir'], 3) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- MODAL PENJELASAN RUMUS DIKENDALIKAN OLEH LIVEWIRE --}}
    @if($isModalOpen && $selectedDetail)
    <div
        class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white dark:bg-[#18181b] w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden relative">
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="font-black uppercase text-xs tracking-widest">Rincian Perhitungan Matematis (SAW)</h3>
                <button wire:click="closeModal" class="hover:rotate-90 transition-transform"><i
                        class="fas fa-times text-lg"></i></button>
            </div>

            <div class="p-6 overflow-y-auto max-h-[80vh]">
                <div class="mb-6 border-b dark:border-white/10 pb-4 text-center">
                    <p class="text-[10px] text-slate-500 uppercase font-black">Dokumentasi Nilai Kinerja Salesman</p>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $selectedDetail['nama'] }}
                    </h2>
                </div>

                <div class="space-y-4">
                    {{-- N1 --}}
                    <div
                        class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/10">
                        <p class="font-black text-blue-600 dark:text-blue-400 text-xs mb-1 uppercase tracking-widest">
                            Kriteria 1: Total Omzet (Sifat: Keuntungan)</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-2 leading-relaxed">
                            <strong>Acuan:</strong> Karena ini adalah kriteria <em>Keuntungan</em> (semakin besar
                            semakin baik), nilai Omzet dari salesman ini (Rp {{ $selectedDetail['omzet_fmt'] }}) akan
                            dibandingkan dengan <strong>Omzet Tertinggi</strong> yang diraih oleh salesman lain pada
                            bulan ini (Rp {{ $selectedDetail['max_k1_fmt'] }}).
                        </p>
                        <div class="bg-white dark:bg-black p-2 rounded-lg border border-slate-200 dark:border-white/10">
                            <p class="font-mono text-[11px]">Rumus: Nilai Sales / Nilai Maksimal</p>
                            <p class="font-mono text-[11px] mt-1">= Rp {{ $selectedDetail['omzet_fmt'] }} / Rp
                                {{ $selectedDetail['max_k1_fmt'] }} = <strong
                                    class="text-blue-600 text-sm">{{ $selectedDetail['n1'] }}</strong></p>
                        </div>
                    </div>

                    {{-- N3 --}}
                    <div
                        class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/10">
                        <p class="font-black text-rose-600 dark:text-rose-400 text-xs mb-1 uppercase tracking-widest">
                            Kriteria 3: Total Retur (Sifat: Biaya)</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-2 leading-relaxed">
                            <strong>Acuan:</strong> Karena ini adalah kriteria <em>Biaya</em> (semakin kecil semakin
                            baik), nilai Retur dari salesman ini (Rp {{ $selectedDetail['retur_fmt'] }}) akan menjadi
                            angka pembagi untuk nilai <strong>Retur Terendah / Terbaik</strong> bulan ini (Rp
                            {{ $selectedDetail['min_k3_fmt'] }}).
                        </p>
                        <div class="bg-white dark:bg-black p-2 rounded-lg border border-slate-200 dark:border-white/10">
                            <p class="font-mono text-[11px]">Rumus: Nilai Minimal / Nilai Sales</p>
                            <p class="font-mono text-[11px] mt-1">= Rp {{ $selectedDetail['min_k3_fmt'] }} / Rp
                                {{ $selectedDetail['retur_fmt'] }} = <strong
                                    class="text-rose-600 text-sm">{{ $selectedDetail['n3'] }}</strong></p>
                        </div>
                    </div>

                    <div
                        class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-800">
                        <p class="font-black text-amber-700 text-xs mb-2 uppercase tracking-widest">Tahap Akhir:
                            Penjumlahan Terbobot (V)</p>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 mb-2">Seluruh nilai normalisasi (N)
                            akan dikalikan dengan persentase bobot masing-masing kriteria (40%, 20%, 20%, 20%).</p>
                        <p class="text-[11px] font-mono mb-2">V = ({{ $selectedDetail['n1'] }} * 0.4) +
                            ({{ $selectedDetail['n2'] }} * 0.2) + ({{ $selectedDetail['n3'] }} * 0.2) +
                            ({{ $selectedDetail['n4'] }} * 0.2)</p>
                        <div
                            class="font-mono text-xl font-black text-slate-900 dark:text-white border-t border-amber-200 pt-2 mt-2">
                            Skor Akhir (V): <span class="text-amber-600">{{ $selectedDetail['skor_akhir'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>