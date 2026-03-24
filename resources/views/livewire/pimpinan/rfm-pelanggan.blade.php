<div class="min-h-screen pb-10 transition-colors duration-300 font-jakarta dark:bg-[#050505] bg-slate-50 relative">

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
        background: rgba(217, 70, 239, 0.4);
        border-radius: 10px;
    }
    </style>

    {{-- STICKY HEADER --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-8
        dark:bg-[#0a0a0a]/80 dark:border-white/10 bg-white/95 border-slate-300 shadow-md">

        <div class="flex flex-col xl:flex-row gap-6 items-center justify-between">

            {{-- Logo & Judul --}}
            <div class="flex items-center gap-4 w-full xl:w-auto shrink-0">
                <div
                    class="p-3 rounded-2xl shadow-xl bg-gradient-to-br from-fuchsia-600 to-purple-700 text-white ring-4 ring-fuchsia-500/20">
                    <i class="fas fa-users-viewfinder text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                        Segmentasi <span class="text-fuchsia-600 dark:text-fuchsia-400">Pelanggan</span>
                    </h1>
                    <p
                        class="text-[10px] font-extrabold uppercase tracking-[0.2em] mt-1.5 dark:text-slate-400 text-slate-600">
                        Analisis Loyalitas Metode RFM
                    </p>
                </div>
            </div>

            {{-- Kolom Pencarian, Filter & Tombol Aksi --}}
            <div class="flex flex-wrap xl:flex-nowrap items-center gap-3 w-full xl:w-auto justify-end">

                {{-- SEARCH FILTER (FITUR BARU) --}}
                <div class="relative w-full sm:w-auto sm:min-w-[200px] group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 dark:text-slate-400 text-slate-500 group-focus-within:text-fuchsia-600 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 text-[11px] font-bold uppercase tracking-widest focus:ring-4 focus:ring-fuchsia-500/10 transition-all dark:bg-black/40 dark:border-white/10 dark:text-white bg-white border-slate-200 text-slate-900 placeholder-slate-400 shadow-sm"
                        placeholder="Cari Pelanggan...">
                </div>

                {{-- FILTER PERIODE (GROUPED) --}}
                <div
                    class="flex items-center bg-white dark:bg-[#121212] border-2 border-slate-200 dark:border-white/10 rounded-xl px-2 shadow-sm h-[42px] shrink-0">
                    <i class="far fa-calendar-alt text-slate-400 dark:text-slate-500 ml-2 text-xs"></i>
                    <select wire:model.live="bulan"
                        class="border-none bg-transparent text-[11px] font-bold uppercase tracking-widest focus:ring-0 cursor-pointer dark:text-slate-200 text-slate-700 w-full sm:w-32 py-1 pl-2 pr-6 appearance-none">
                        @for($i=1; $i<=12; $i++) <option value="{{ $i }}">
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                            @endfor
                    </select>
                    <span class="text-slate-300 dark:text-slate-700 font-black">|</span>
                    <select wire:model.live="tahun"
                        class="border-none bg-transparent text-[11px] font-bold uppercase tracking-widest focus:ring-0 cursor-pointer dark:text-slate-200 text-slate-700 w-full sm:w-20 py-1 pl-2 pr-6 appearance-none">
                        @for($y=date('Y')-1; $y<=date('Y'); $y++) <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                    </select>
                </div>

                {{-- TOMBOL CETAK PDF --}}
                @if(!empty($hasilRFM))
                <button wire:click="exportPdf" wire:loading.attr="disabled"
                    class="px-5 py-2.5 bg-fuchsia-600 hover:bg-fuchsia-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-fuchsia-600/30 transition-all active:scale-95 flex items-center justify-center gap-2 h-[42px] shrink-0 min-w-[150px]">
                    <i class="fas fa-file-pdf"></i>
                    <span wire:loading.remove wire:target="exportPdf">Cetak Laporan</span>
                    <span wire:loading wire:target="exportPdf"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in">

        @if(empty($hasilRFM))
        <div
            class="bg-white dark:bg-[#121212] p-16 rounded-[2rem] border-2 border-slate-200 dark:border-white/5 text-center shadow-xl">
            <i class="fas fa-users-slash text-7xl text-slate-300 dark:text-slate-700 mb-5"></i>
            <h3 class="text-xl font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">Belum Ada Data
                Pelanggan</h3>
            <p class="text-xs text-slate-500 mt-2 font-medium">Tidak ada transaksi yang tercatat pada bulan yang
                dipilih.</p>
        </div>
        @else

        {{-- KOTAK INFORMASI ACUAN RFM --}}
        <div
            class="bg-white dark:bg-[#121212] border-2 border-fuchsia-100 dark:border-fuchsia-900/30 p-6 rounded-[2rem] shadow-sm relative overflow-hidden">
            <div
                class="absolute right-0 top-0 w-24 h-full bg-fuchsia-50 dark:bg-fuchsia-500/5 -skew-x-12 translate-x-10 pointer-events-none">
            </div>
            <h4
                class="text-[10px] font-black text-fuchsia-600 dark:text-fuchsia-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2 relative z-10">
                <i class="fas fa-info-circle"></i> Standar Penilaian Sistem (Kuantil 1-5)
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                <div class="bg-slate-50 dark:bg-white/5 p-4 rounded-xl border border-slate-100 dark:border-white/5">
                    <span
                        class="block text-xs font-black text-slate-800 dark:text-white mb-1 uppercase tracking-wider">Keterbaruan
                        (R)</span>
                    <p class="text-[10px] text-slate-500 font-medium leading-relaxed">20% Pelanggan dengan transaksi
                        paling baru mendapat skor tertinggi (5).</p>
                </div>
                <div class="bg-slate-50 dark:bg-white/5 p-4 rounded-xl border border-slate-100 dark:border-white/5">
                    <span
                        class="block text-xs font-black text-slate-800 dark:text-white mb-1 uppercase tracking-wider">Frekuensi
                        (F)</span>
                    <p class="text-[10px] text-slate-500 font-medium leading-relaxed">20% Pelanggan dengan jumlah nota
                        terbanyak mendapat skor tertinggi (5).</p>
                </div>
                <div class="bg-slate-50 dark:bg-white/5 p-4 rounded-xl border border-slate-100 dark:border-white/5">
                    <span
                        class="block text-xs font-black text-slate-800 dark:text-white mb-1 uppercase tracking-wider">Moneter
                        (M)</span>
                    <p class="text-[10px] text-slate-500 font-medium leading-relaxed">20% Pelanggan dengan total belanja
                        terbesar mendapat skor tertinggi (5).</p>
                </div>
            </div>
        </div>

        {{-- KARTU SUMMARY --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
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
                class="bg-white dark:bg-[#121212] border-2 border-{{ $card['color'] }}-100 dark:border-{{ $card['color'] }}-900/30 p-4 rounded-2xl flex flex-col items-center justify-center text-center shadow-sm hover:-translate-y-1 transition-transform group">
                <i
                    class="fas fa-{{ $card['icon'] }} text-2xl text-{{ $card['color'] }}-500 mb-3 group-hover:scale-110 transition-transform"></i>
                <h4
                    class="text-3xl font-black text-{{ $card['color'] }}-700 dark:text-{{ $card['color'] }}-400 tracking-tighter">
                    {{ $summary[$card['key']] ?? 0 }}
                </h4>
                <p
                    class="text-[9px] font-bold uppercase tracking-widest text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-500 mt-1">
                    {{ $card['label'] }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- TABEL ANALISA LENGKAP --}}
        <div
            class="bg-white dark:bg-[#0f0f0f] rounded-[2rem] border-2 border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">
            <div
                class="p-6 border-b-2 border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-[#1a1a1a] flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <div>
                    <h4
                        class="font-black text-slate-800 dark:text-white uppercase tracking-widest text-sm flex items-center gap-2">
                        <i class="fas fa-table text-fuchsia-500"></i> Matriks Keputusan Pelanggan
                    </h4>
                    <p class="text-[11px] font-bold text-slate-500 mt-1 uppercase tracking-widest">Klik baris tabel
                        untuk melihat detail.</p>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead
                        class="bg-slate-100 dark:bg-black/40 text-slate-600 dark:text-slate-400 font-black uppercase tracking-[0.15em] text-[10px] border-b-2 dark:border-white/10 border-slate-200">
                        <tr>
                            <th class="px-6 py-5">Nama Pelanggan</th>
                            <th class="px-6 py-5 text-center border-l border-slate-200 dark:border-white/5">Keterbaruan
                                (R) <span class="block text-[8px] opacity-60 mt-0.5">Hari</span></th>
                            <th class="px-6 py-5 text-center border-l border-slate-200 dark:border-white/5">Frekuensi
                                (F) <span class="block text-[8px] opacity-60 mt-0.5">Nota</span></th>
                            <th class="px-6 py-5 text-right border-l border-slate-200 dark:border-white/5">Moneter (M)
                                <span class="block text-[8px] opacity-60 mt-0.5">Rupiah</span></th>
                            <th
                                class="px-6 py-5 text-center border-l border-slate-200 dark:border-white/5 bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-700 dark:text-fuchsia-400 text-[11px]">
                                Skor Gabungan</th>
                            <th class="px-6 py-5 text-center border-l border-slate-200 dark:border-white/5">Klasifikasi
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y-2 divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-300 font-medium">
                        @forelse($hasilRFM as $index => $row)
                        <tr wire:key="rfm-row-{{ $index }}-{{ $bulan }}-{{ $tahun }}"
                            wire:click="openDetail('{{ $row['nama'] }}')"
                            class="hover:bg-fuchsia-50 dark:hover:bg-fuchsia-500/10 transition-colors cursor-pointer group">

                            <td class="px-6 py-4">
                                <div
                                    class="font-black text-slate-800 dark:text-white uppercase tracking-tight group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors flex items-center gap-2">
                                    {{ Str::limit($row['nama'], 25) }}
                                    <i
                                        class="fas fa-arrow-right opacity-0 group-hover:opacity-100 transition-opacity text-[10px]"></i>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center border-l border-slate-100 dark:border-white/5">
                                <span
                                    class="block font-black text-slate-800 dark:text-slate-200">{{ number_format($row['r_raw'], 0, ',', '.') }}</span>
                                <span class="text-[9px] font-bold text-slate-400">Skor: {{ $row['r_score'] }}</span>
                            </td>

                            <td class="px-6 py-4 text-center border-l border-slate-100 dark:border-white/5">
                                <span
                                    class="block font-black text-slate-800 dark:text-slate-200">{{ number_format($row['f_raw'], 0, ',', '.') }}</span>
                                <span class="text-[9px] font-bold text-slate-400">Skor: {{ $row['f_score'] }}</span>
                            </td>

                            <td class="px-6 py-4 text-right border-l border-slate-100 dark:border-white/5">
                                <span class="block font-black text-emerald-600 dark:text-emerald-400">Rp
                                    {{ $row['m_fmt'] }}</span>
                                <span class="text-[9px] font-bold text-slate-400">Skor: {{ $row['m_score'] }}</span>
                            </td>

                            <td
                                class="px-6 py-4 text-center border-l border-slate-100 dark:border-white/5 bg-fuchsia-50/50 dark:bg-fuchsia-500/5">
                                <span
                                    class="font-mono font-black text-lg text-fuchsia-600 dark:text-fuchsia-400">{{ $row['rfm_concat'] }}</span>
                            </td>

                            <td class="px-6 py-4 text-center border-l border-slate-100 dark:border-white/5">
                                <span
                                    class="px-3 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border border-{{ $row['color'] }}-200 bg-{{ $row['color'] }}-50 dark:bg-{{ $row['color'] }}-500/10 dark:border-{{ $row['color'] }}-500/30 text-{{ $row['color'] }}-600 dark:text-{{ $row['color'] }}-400 shadow-sm">
                                    {{ $row['segment'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6"
                                class="px-6 py-12 text-center text-slate-500 text-xs font-bold uppercase tracking-widest">
                                Tidak ada data pelanggan yang sesuai dengan pencarian Anda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- MODAL DETAIL RFM --}}
        @if($isModalOpen && $selectedDetail)
        <div
            class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm p-4 animate-fade-in">
            <div x-data @click.outside="$wire.closeModal()"
                class="bg-white dark:bg-[#18181b] w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden relative border-2 border-slate-200 dark:border-white/10 my-8">
                <div class="bg-fuchsia-600 px-6 py-5 flex justify-between items-center text-white">
                    <h3 class="font-black tracking-widest uppercase text-sm flex items-center gap-2">
                        <i class="fas fa-microscope"></i> Analisis Perilaku Belanja
                    </h3>
                    <button wire:click="closeModal"
                        class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center transition-colors"><i
                            class="fas fa-times"></i></button>
                </div>

                <div class="p-6 md:p-8">
                    <div class="mb-6 text-center">
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-black tracking-widest">
                            Identitas Toko / Pelanggan</p>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white mt-1 uppercase">
                            {{ $selectedDetail['nama'] }}</h2>
                    </div>

                    <div class="space-y-4">
                        <div
                            class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/10">
                            <div class="flex justify-between items-start mb-2">
                                <span
                                    class="text-[10px] font-black text-fuchsia-600 dark:text-fuchsia-400 uppercase tracking-widest">Keterbaruan
                                    (R)</span>
                                <span
                                    class="bg-fuchsia-100 dark:bg-fuchsia-900/30 text-fuchsia-700 dark:text-fuchsia-400 px-2 py-1 rounded text-[10px] font-black border border-fuchsia-200 dark:border-fuchsia-800">Skor:
                                    {{ $selectedDetail['r_score'] }}</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                Terakhir belanja pada <strong>{{ number_format($selectedDetail['r_raw'], 0, ',', '.') }}
                                    Hari</strong> yang lalu. <br>
                                <span class="text-[10px] text-slate-500 mt-1 inline-block"><em>Keterangan:</em>
                                    Menduduki <strong>{{ $selectedDetail['r_rank_info'] }}</strong> dari seluruh
                                    populasi pelanggan.</span>
                            </p>
                        </div>

                        <div
                            class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/10">
                            <div class="flex justify-between items-start mb-2">
                                <span
                                    class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">Frekuensi
                                    (F)</span>
                                <span
                                    class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-2 py-1 rounded text-[10px] font-black border border-blue-200 dark:border-blue-800">Skor:
                                    {{ $selectedDetail['f_score'] }}</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300">
                                Telah melakukan transaksi sebanyak
                                <strong>{{ number_format($selectedDetail['f_raw'], 0, ',', '.') }} Nota</strong>.
                            </p>
                        </div>

                        <div
                            class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/10">
                            <div class="flex justify-between items-start mb-2">
                                <span
                                    class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Moneter
                                    (M)</span>
                                <span
                                    class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 px-2 py-1 rounded text-[10px] font-black border border-emerald-200 dark:border-emerald-800">Skor:
                                    {{ $selectedDetail['m_score'] }}</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300">
                                Total nilai belanja mencapai <strong>Rp {{ $selectedDetail['m_fmt'] }}</strong>.
                            </p>
                        </div>

                        <div
                            class="mt-6 p-5 bg-fuchsia-600 dark:bg-fuchsia-600/20 dark:border dark:border-fuchsia-500/30 rounded-2xl text-center text-white shadow-xl shadow-fuchsia-500/20 relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-16 h-full bg-white/10 -skew-x-12 translate-x-4"></div>
                            <p
                                class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-1 relative z-10 dark:text-fuchsia-300">
                                Klasifikasi Segmen Akhir</p>
                            <p
                                class="text-3xl font-black uppercase tracking-tighter relative z-10 dark:text-fuchsia-400">
                                {{ $selectedDetail['segment'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>