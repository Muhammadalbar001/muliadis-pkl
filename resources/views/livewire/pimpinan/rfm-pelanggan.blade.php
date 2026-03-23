<div class="p-6 max-w-7xl mx-auto font-jakarta animate-fade-in pb-20">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3 tracking-tighter">
                <i class="fas fa-users-viewfinder text-fuchsia-600"></i> Segmentasi Pelanggan (RFM)
            </h2>
            <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-widest">Analisis Keterbaruan, Frekuensi,
                dan Nilai Belanja</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <select wire:model.live="bulan"
                class="rounded-xl border-slate-200 dark:border-white/10 dark:bg-[#121212] text-xs font-bold focus:ring-fuchsia-500 cursor-pointer">
                @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option> @endfor
            </select>
            <select wire:model.live="tahun"
                class="rounded-xl border-slate-200 dark:border-white/10 dark:bg-[#121212] text-xs font-bold focus:ring-fuchsia-500 cursor-pointer">
                @for($y=date('Y')-1; $y<=date('Y'); $y++) <option value="{{ $y }}">{{ $y }}</option> @endfor
            </select>

            {{-- Tombol Cetak Laporan PDF --}}
            @if(!empty($hasilRFM))
            <button wire:click="exportPdf" wire:loading.attr="disabled"
                class="bg-fuchsia-600 hover:bg-fuchsia-700 disabled:bg-fuchsia-400 disabled:cursor-not-allowed text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm flex items-center gap-2 transition-all">
                <i class="fas fa-file-pdf"></i>
                <span wire:loading.remove wire:target="exportPdf">Cetak Laporan</span>
                <span wire:loading wire:target="exportPdf">Menyiapkan...</span>
            </button>
            @endif
        </div>
    </div>

    @if(empty($hasilRFM))
    <div
        class="bg-white dark:bg-[#121212] p-10 rounded-3xl border border-slate-200 dark:border-white/5 text-center shadow-sm">
        <i class="fas fa-users-slash text-6xl text-slate-300 dark:text-slate-700 mb-4"></i>
        <h3 class="text-lg font-black text-slate-700 dark:text-slate-300">Belum Ada Data Pelanggan</h3>
        <p class="text-xs text-slate-500 mt-2">Tidak ada transaksi tercatat pada bulan yang dipilih.</p>
    </div>
    @else

    {{-- KOTAK INFORMASI ACUAN RFM --}}
    <div
        class="bg-fuchsia-50 dark:bg-fuchsia-900/10 border border-fuchsia-100 dark:border-fuchsia-800 p-5 rounded-3xl mb-8">
        <h4 class="text-[10px] font-black text-fuchsia-600 uppercase tracking-[0.2em] mb-3 text-center">Standar
            Penilaian Sistem (Metode Kuantil 1-5)</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <span class="block text-xs font-black text-slate-700 dark:text-white">Keterbaruan (R)</span>
                <p class="text-[10px] text-slate-500 mt-1">20% Pelanggan dengan transaksi paling baru mendapat skor
                    tertinggi (5).</p>
            </div>
            <div class="text-center border-x border-fuchsia-200 dark:border-fuchsia-800">
                <span class="block text-xs font-black text-slate-700 dark:text-white">Frekuensi (F)</span>
                <p class="text-[10px] text-slate-500 mt-1">20% Pelanggan dengan jumlah nota terbanyak mendapat skor
                    tertinggi (5).</p>
            </div>
            <div class="text-center">
                <span class="block text-xs font-black text-slate-700 dark:text-white">Moneter (M)</span>
                <p class="text-[10px] text-slate-500 mt-1">20% Pelanggan dengan total nilai belanja terbesar mendapat
                    skor tertinggi (5).</p>
            </div>
        </div>
    </div>

    {{-- KARTU SUMMARY --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        @php
        $cards = [
        ['label' => 'Utama', 'key' => 'Pelanggan Utama', 'icon' => 'crown', 'color' => 'emerald'],
        ['label' => 'Setia', 'key' => 'Pelanggan Setia', 'icon' => 'handshake', 'color' => 'blue'],
        ['label' => 'Potensial', 'key' => 'Pelanggan Potensial', 'icon' => 'seedling', 'color' => 'cyan'],
        ['label' => 'Berisiko', 'key' => 'Berisiko Pindah', 'icon' => 'user-clock', 'color' => 'orange'],
        ['label' => 'Pasif', 'key' => 'Pelanggan Pasif', 'icon' => 'user-slash', 'color' => 'rose'],
        ];
        @endphp
        @foreach($cards as $card)
        <div
            class="bg-{{ $card['color'] }}-50 dark:bg-{{ $card['color'] }}-500/10 border border-{{ $card['color'] }}-200 dark:border-{{ $card['color'] }}-500/20 p-4 rounded-2xl flex flex-col items-center justify-center text-center shadow-sm">
            <i class="fas fa-{{ $card['icon'] }} text-2xl text-{{ $card['color'] }}-500 mb-2"></i>
            <h4 class="text-2xl font-black text-{{ $card['color'] }}-700 dark:text-{{ $card['color'] }}-400">
                {{ $summary[$card['key']] ?? 0 }}</h4>
            <p class="text-[9px] font-bold uppercase tracking-widest text-{{ $card['color'] }}-600 mt-1">
                {{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- TABEL ANALISA LENGKAP --}}
    <div
        class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-200 dark:border-white/5 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-transparent">
            <h4 class="font-black text-slate-800 dark:text-white uppercase tracking-widest text-sm"><i
                    class="fas fa-table text-fuchsia-500 mr-2"></i> Matriks Keputusan Pelanggan</h4>
            <p class="text-xs text-slate-500 mt-1">Klik baris tabel untuk melihat penjelasan detail dari sistem cerdas.
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-slate-800 text-white font-bold uppercase tracking-widest text-[9px]">
                    <tr>
                        <th class="px-5 py-4">Nama Pelanggan</th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">Keterbaruan (R)<br><span
                                class="font-normal text-fuchsia-400">Hari</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">Frekuensi (F)<br><span
                                class="font-normal text-fuchsia-400">Nota</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600">Moneter (M)<br><span
                                class="font-normal text-fuchsia-400">Rupiah</span></th>
                        <th class="px-5 py-4 text-center border-l border-slate-600 bg-slate-900">Skor Gabungan</th>
                        <th class="px-5 py-4 text-center border-l border-slate-600 bg-slate-900">Klasifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-300">
                    @foreach($hasilRFM as $index => $row)
                    <tr wire:key="rfm-row-{{ $index }}-{{ $bulan }}-{{ $tahun }}"
                        wire:click="openDetail('{{ $row['nama'] }}')"
                        class="hover:bg-fuchsia-50 dark:hover:bg-fuchsia-900/20 transition-colors cursor-pointer group">

                        <td class="px-5 py-4 font-black text-slate-800 dark:text-white group-hover:text-fuchsia-600">
                            {{ $row['nama'] }} <i
                                class="fas fa-search-plus ml-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </td>

                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span
                                class="block font-black text-slate-800 dark:text-slate-200">{{ number_format($row['r_raw'], 0, ',', '.') }}</span>
                            <span class="text-[9px] font-bold text-slate-400">Skor: {{ $row['r_score'] }}</span>
                        </td>

                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span
                                class="block font-black text-slate-800 dark:text-slate-200">{{ number_format($row['f_raw'], 0, ',', '.') }}</span>
                            <span class="text-[9px] font-bold text-slate-400">Skor: {{ $row['f_score'] }}</span>
                        </td>

                        <td class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5">
                            <span class="block font-black text-emerald-600 dark:text-emerald-400">Rp
                                {{ $row['m_fmt'] }}</span>
                            <span class="text-[9px] font-bold text-slate-400">Skor: {{ $row['m_score'] }}</span>
                        </td>

                        <td
                            class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                            <span class="font-mono font-black text-lg text-fuchsia-600">{{ $row['rfm_concat'] }}</span>
                        </td>

                        <td
                            class="px-5 py-4 text-center border-l border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                            <span
                                class="px-3 py-1 rounded-md text-[10px] font-black uppercase border border-{{ $row['color'] }}-200 bg-{{ $row['color'] }}-100 text-{{ $row['color'] }}-700">
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

    {{-- MODAL DETAIL RFM DIKENDALIKAN OLEH LIVEWIRE --}}
    @if($isModalOpen && $selectedDetail)
    <div
        class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm p-4 animate-fade-in">
        <div x-data @click.outside="$wire.closeModal()"
            class="bg-white dark:bg-[#18181b] w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden relative">
            <div class="bg-fuchsia-600 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="font-black tracking-widest uppercase text-[10px]">Analisis Perilaku Belanja Pelanggan</h3>
                <button wire:click="closeModal" class="hover:rotate-90 transition-transform"><i
                        class="fas fa-times text-lg"></i></button>
            </div>
            <div class="p-6">
                <div class="mb-6 text-center border-b dark:border-white/10 pb-4">
                    <p class="text-[10px] text-slate-500 uppercase font-black">Identitas Toko</p>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $selectedDetail['nama'] }}
                    </h2>
                </div>
                <div class="space-y-4">
                    <div
                        class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/10">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-black text-fuchsia-600 uppercase tracking-widest">Keterbaruan
                                (R)</span>
                            <span
                                class="bg-fuchsia-600 text-white px-3 py-1 rounded-lg font-black text-xs shadow-sm">Skor:
                                {{ $selectedDetail['r_score'] }}</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            Terakhir belanja pada <strong>{{ number_format($selectedDetail['r_raw'], 0, ',', '.') }}
                                Hari</strong> yang lalu. <br>
                            <em>Keterangan:</em> Menggunakan metode Kuantil, toko ini menduduki
                            <strong>{{ $selectedDetail['r_rank_info'] }}</strong> dari seluruh populasi pelanggan.
                        </p>
                    </div>

                    <div
                        class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/10">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Frekuensi
                                (F)</span>
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-lg font-black text-xs shadow-sm">Skor:
                                {{ $selectedDetail['f_score'] }}</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            Telah melakukan transaksi sebanyak
                            <strong>{{ number_format($selectedDetail['f_raw'], 0, ',', '.') }} Nota</strong>.
                        </p>
                    </div>

                    <div
                        class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/10">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Moneter
                                (M)</span>
                            <span
                                class="bg-emerald-600 text-white px-3 py-1 rounded-lg font-black text-xs shadow-sm">Skor:
                                {{ $selectedDetail['m_score'] }}</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            Total nilai belanja mencapai <strong>Rp {{ $selectedDetail['m_fmt'] }}</strong>.
                        </p>
                    </div>

                    <div
                        class="mt-6 p-4 bg-fuchsia-600 rounded-2xl text-center text-white shadow-xl shadow-fuchsia-500/20">
                        <p class="text-[10px] font-black uppercase opacity-80 mb-1">Klasifikasi Segmen Akhir Berdasarkan
                            Aturan Bisnis</p>
                        <p class="text-2xl font-black uppercase tracking-tighter">{{ $selectedDetail['segment'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>