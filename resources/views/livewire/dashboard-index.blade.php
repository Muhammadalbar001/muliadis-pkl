<div class="min-h-screen space-y-6 font-jakarta pb-10 transition-colors duration-300 dark:bg-[#0a0a0a]">

    {{-- HEADER & FILTER AREA --}}
    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-slate-50/90 dark:bg-[#0a0a0a]/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 dark:border-white/5 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div
                    class="p-2 bg-indigo-600 rounded-lg text-white shadow-lg border border-indigo-500 relative overflow-hidden">
                    <i class="fas fa-brain text-xl relative z-10"></i>
                    <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">AI Executive
                        Dashboard</h1>
                    <p class="text-[10px] uppercase tracking-widest text-indigo-500 font-black mt-0.5">Powered by
                        AHP-SAW & Fuzzy C-Means</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">
                <div
                    class="flex items-center gap-1 bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-lg px-2 py-1 shadow-sm h-[38px]">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-xs font-bold text-slate-700 dark:text-slate-300 focus:ring-0 p-0 bg-transparent w-24 cursor-pointer">
                    <span class="text-slate-300 dark:text-slate-600 text-[10px]">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-xs font-bold text-slate-700 dark:text-slate-300 focus:ring-0 p-0 bg-transparent w-24 cursor-pointer">
                </div>
                <div wire:loading
                    class="px-3 py-2 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 text-indigo-600 rounded-lg shadow-sm flex items-center justify-center">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200 space-y-6">

        {{-- SMART ALERTS: RADAR PERINGATAN DINI --}}
        @if(count($alerts) > 0)
        <div class="space-y-3">
            <div class="flex items-center px-2 mb-2">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 flex items-center gap-2">
                    <i class="fas fa-satellite-dish animate-pulse"></i> Radar Peringatan Dini
                </h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($alerts as $alert)
                <div
                    class="flex items-start gap-4 p-4 rounded-2xl border {{ $alert['type'] == 'danger' ? 'bg-rose-50 border-rose-200 dark:bg-rose-900/10' : 'bg-orange-50 border-orange-200 dark:bg-orange-900/10' }} shadow-sm transition-transform hover:-translate-y-0.5">
                    <div
                        class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $alert['type'] == 'danger' ? 'bg-rose-100 text-rose-600' : 'bg-orange-100 text-orange-600' }}">
                        <i class="{{ $alert['icon'] }} text-lg"></i>
                    </div>
                    <div>
                        <h4
                            class="font-black text-sm uppercase tracking-widest mb-1 {{ $alert['type'] == 'danger' ? 'text-rose-700' : 'text-orange-700' }}">
                            {{ $alert['title'] }}</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{!! $alert['message'] !!}
                        </p>
                        <a href="{{ $alert['link'] }}"
                            class="inline-block mt-2 text-[10px] font-bold text-indigo-600 hover:underline">Tinjau
                            Analitik &rarr;</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- FITUR 2: KOTAK NARASI INSIGHT OTOMATIS (AI TEXT) --}}
        <div
            class="bg-gradient-to-r from-indigo-500 via-purple-500 to-fuchsia-500 p-[2px] rounded-[1.25rem] shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-white/20 animate-pulse" style="animation-duration: 3s;"></div>
            <div
                class="bg-white dark:bg-[#121212] rounded-xl p-5 md:p-6 flex flex-col md:flex-row items-start gap-4 md:gap-6 relative z-10">
                <div
                    class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 flex items-center justify-center shrink-0 border border-indigo-200 dark:border-indigo-500/30">
                    <i class="fas fa-robot text-2xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] mb-2 flex items-center gap-3">
                        <span
                            class="bg-gradient-to-r from-indigo-600 to-fuchsia-600 bg-clip-text text-transparent dark:from-indigo-400 dark:to-fuchsia-400">AI
                            Executive Summary</span>
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                    </h3>
                    <p
                        class="text-[13px] md:text-sm text-slate-700 dark:text-slate-300 leading-loose md:leading-relaxed font-medium">
                        {!! $this->autoInsight !!}
                    </p>
                </div>
            </div>
        </div>

        {{-- ==================================================== --}}
        {{-- FITUR 3: 4 KARTU KPI STANDAR (DENGAN TREN MoM)       --}}
        {{-- ==================================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">

            {{-- KPI 1: Penjualan --}}
            <div
                class="bg-gradient-to-br from-indigo-500 to-blue-600 p-4 rounded-2xl shadow-lg text-white relative overflow-hidden group flex flex-col justify-between">
                <div class="relative z-10">
                    <p class="text-indigo-100 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Penjualan</p>
                    <h3 class="text-2xl font-extrabold tracking-tight mb-2">Rp {{ $this->formatCompact($salesSum) }}
                    </h3>
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] text-indigo-100 opacity-80">Realisasi Cabang</p>
                        <span
                            class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-white/20 border border-white/10 text-[10px] font-bold {{ $trendSales >= 0 ? 'text-emerald-300' : 'text-rose-300' }}">
                            <i class="fas {{ $trendSales >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                            {{ number_format(abs($trendSales), 1) }}% MoM
                        </span>
                    </div>
                </div>
                <i
                    class="fas fa-chart-line absolute right-3 top-3 text-white/20 text-5xl group-hover:scale-110 transition-transform"></i>
            </div>

            {{-- KPI 2: Retur (Kenaikan = Buruk/Merah, Penurunan = Baik/Hijau) --}}
            <div
                class="bg-white dark:bg-[#121212] p-4 rounded-2xl border border-rose-100 dark:border-white/5 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Retur</p>
                    <h3 class="text-2xl font-extrabold text-rose-500 tracking-tight mb-2">Rp
                        {{ $this->formatCompact($returSum) }}</h3>
                </div>
                <div class="flex items-center justify-between">
                    <span
                        class="text-[9px] font-bold text-rose-600 bg-rose-50 border border-rose-100 px-1.5 py-0.5 rounded">Rasio:
                        {{ number_format($persenRetur, 2) }}%</span>
                    <span
                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-white/5 text-[10px] font-bold {{ $trendRetur > 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                        <i class="fas {{ $trendRetur > 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                        {{ number_format(abs($trendRetur), 1) }}% MoM
                    </span>
                </div>
            </div>

            {{-- KPI 3: Piutang AR --}}
            <div
                class="bg-white dark:bg-[#121212] p-4 rounded-2xl border border-orange-100 dark:border-white/5 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Piutang Dagang (AR)
                    </p>
                    <h3 class="text-2xl font-extrabold text-orange-500 tracking-tight mb-2">Rp
                        {{ $this->formatCompact($arSum) }}</h3>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-[10px] text-slate-400">Tagihan Terbentuk</p>
                    <span
                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-white/5 text-[10px] font-bold {{ $trendAr > 0 ? 'text-orange-500' : 'text-emerald-500' }}">
                        <i class="fas {{ $trendAr > 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                        {{ number_format(abs($trendAr), 1) }}% MoM
                    </span>
                </div>
            </div>

            {{-- KPI 4: Uang Masuk / Collection --}}
            <div
                class="bg-white dark:bg-[#121212] p-4 rounded-2xl border border-emerald-100 dark:border-white/5 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Uang Masuk (Coll)
                    </p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 tracking-tight mb-2">Rp
                        {{ $this->formatCompact($collSum) }}</h3>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-[10px] text-slate-400">Pembayaran Diterima</p>
                    <span
                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-white/5 text-[10px] font-bold {{ $trendColl >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                        <i class="fas {{ $trendColl >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                        {{ number_format(abs($trendColl), 1) }}% MoM
                    </span>
                </div>
            </div>
        </div>

        {{-- AREA KEPUTUSAN AI (AHP-SAW & FCM) --}}
        <div class="flex items-center px-2 mt-4 mb-2">
            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 flex items-center gap-2">
                <i class="fas fa-brain text-fuchsia-500"></i> Visualisasi Keputusan Analitik (DSS)
            </h3>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" wire:ignore>
            {{-- Doughnut Chart Segmentasi FCM --}}
            <div
                class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-fuchsia-200 dark:border-fuchsia-900/30">
                <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100 dark:border-white/5">
                    <h4 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-project-diagram text-fuchsia-500"></i> Klasterisasi Pelanggan (FCM)
                    </h4>
                    <span class="text-[9px] text-slate-400 italic">Klik grafik untuk detail</span>
                </div>
                <div id="chart-fcm" style="min-height: 350px;" class="flex justify-center items-center"></div>
            </div>

            {{-- Bar Chart Top Sales AHP-SAW --}}
            <div
                class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-blue-200 dark:border-blue-900/30">
                <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100 dark:border-white/5">
                    <h4 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-medal text-blue-500"></i> Papan Peringkat Sales (AHP-SAW)
                    </h4>
                    <a href="{{ route('keputusan.spk-sales') }}"
                        class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded hover:bg-blue-100 transition-colors">Buka
                        SPK &rarr;</a>
                </div>
                <div id="chart-ahp-sales" style="min-height: 350px;"></div>
            </div>
        </div>

        {{-- AREA TREN OPERASIONAL --}}
        <div class="flex items-center px-2 mt-4 mb-2">
            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 flex items-center gap-2">
                <i class="fas fa-chart-line text-emerald-500"></i> Tren Operasional Harian
            </h3>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" wire:ignore>
            <div
                class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10">
                <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-4"><i
                        class="fas fa-chart-area text-indigo-500"></i> Tren Penjualan vs Retur</h4>
                <div id="chart-sales-retur" style="min-height: 300px;"></div>
            </div>
            <div
                class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10">
                <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-4"><i
                        class="fas fa-balance-scale text-orange-500"></i> Tagihan vs Pembayaran</h4>
                <div id="chart-ar-coll" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>

    {{-- MODAL DRILL-DOWN (KLIK GRAFIK FCM) --}}
    @if($showSegmentModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        wire:transition.opacity>
        <div
            class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden animate-fade-in">

            {{-- Header Modal --}}
            <div
                class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-fuchsia-100 dark:bg-fuchsia-900/30 text-fuchsia-600 flex items-center justify-center">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 dark:text-white uppercase tracking-widest text-sm">
                            {{ $segmentModalTitle }}</h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase">
                            {{ count($segmentDetails) }} Toko Ditemukan</p>
                    </div>
                </div>
                <button wire:click="closeSegmentModal"
                    class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/10 flex items-center justify-center text-slate-500 hover:bg-rose-100 hover:text-rose-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Body Modal (Tabel) --}}
            <div class="p-0 overflow-y-auto custom-scrollbar flex-1">
                <table class="w-full text-sm text-left border-collapse uppercase font-jakarta">
                    <thead
                        class="sticky top-0 bg-slate-50 dark:bg-[#1a1a1a] text-[10px] tracking-[0.15em] text-slate-500 dark:text-slate-400 font-black border-b border-slate-200 dark:border-white/10 z-10">
                        <tr>
                            <th class="px-6 py-4 border-r dark:border-white/5 border-slate-100 w-16 text-center">No</th>
                            <th class="px-6 py-4 border-r dark:border-white/5 border-slate-100">Nama Toko / Pelanggan
                            </th>
                            <th class="px-6 py-4 border-r dark:border-white/5 border-slate-100 text-center">Transaksi
                                (Freq)</th>
                            <th class="px-6 py-4 border-r dark:border-white/5 border-slate-100 text-right">Total Belanja
                                (Monetary)</th>
                            <th class="px-6 py-4 text-center">Order Terakhir (Recency)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($segmentDetails as $index => $item)
                        <tr class="hover:bg-fuchsia-50 dark:hover:bg-fuchsia-500/5 transition-colors">
                            <td
                                class="px-6 py-4 text-center font-bold text-slate-500 border-r dark:border-white/5 border-slate-100">
                                {{ $index + 1 }}</td>
                            <td
                                class="px-6 py-4 font-black text-slate-800 dark:text-white border-r dark:border-white/5 border-slate-100">
                                {{ $item['nama'] }}</td>
                            <td class="px-6 py-4 text-center font-bold border-r dark:border-white/5 border-slate-100">
                                {{ $item['frekuensi'] }}x</td>
                            <td
                                class="px-6 py-4 text-right font-black text-fuchsia-600 dark:text-fuchsia-400 border-r dark:border-white/5 border-slate-100">
                                Rp {{ number_format($item['monetary'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center font-bold text-[10px] tracking-widest">
                                {{ $item['terakhir_belanja'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5"
                                class="px-6 py-10 text-center font-bold text-slate-400 uppercase tracking-widest text-[10px]">
                                Belum ada pelanggan di segmen ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {
    let charts = {};
    const initData = @json($chartData);

    const getChartTheme = () => document.documentElement.classList.contains('dark') ? 'dark' : 'light';

    const renderCharts = (data) => {
        const themeMode = getChartTheme();
        const isDark = themeMode === 'dark';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        // 1. Sales vs Retur (Area Chart)
        if (document.querySelector("#chart-sales-retur")) {
            if (charts.sr) charts.sr.destroy();
            charts.sr = new ApexCharts(document.querySelector("#chart-sales-retur"), {
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: {
                        show: false
                    },
                    background: 'transparent'
                },
                theme: {
                    mode: themeMode
                },
                series: [{
                    name: 'Penjualan',
                    data: data.trend_sales
                }, {
                    name: 'Retur',
                    data: data.trend_retur
                }],
                colors: ['#4f46e5', '#f43f5e'],
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: data.dates,
                    labels: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '10px',
                            colors: textColor
                        },
                        formatter: (v) => (v / 1000000).toFixed(1) + " Jt"
                    }
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.4,
                        opacityTo: 0.05
                    }
                }
            });
            charts.sr.render();
        }

        // 2. AR vs Collection (Bar Chart)
        if (document.querySelector("#chart-ar-coll")) {
            if (charts.ac) charts.ac.destroy();
            charts.ac = new ApexCharts(document.querySelector("#chart-ar-coll"), {
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: {
                        show: false
                    },
                    background: 'transparent'
                },
                theme: {
                    mode: themeMode
                },
                series: [{
                    name: 'Piutang',
                    data: data.trend_ar
                }, {
                    name: 'Collection',
                    data: data.trend_coll
                }],
                colors: ['#f97316', '#10b981'],
                plotOptions: {
                    bar: {
                        borderRadius: 2,
                        columnWidth: '60%'
                    }
                },
                xaxis: {
                    categories: data.dates,
                    labels: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '10px',
                            colors: textColor
                        },
                        formatter: (v) => (v / 1000000).toFixed(1) + " Jt"
                    }
                },
                dataLabels: {
                    enabled: false
                }
            });
            charts.ac.render();
        }

        // 3. FCM Segmentation (Doughnut Chart AI)
        if (document.querySelector("#chart-fcm")) {
            if (charts.fcm) charts.fcm.destroy();
            charts.fcm = new ApexCharts(document.querySelector("#chart-fcm"), {
                chart: {
                    type: 'donut',
                    height: 350,
                    background: 'transparent',
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            let seriesIndex = config.dataPointIndex;
                            @this.call('showSegmentDetails', seriesIndex);
                        }
                    }
                },
                theme: {
                    mode: themeMode
                },
                series: data.fcm_series,
                labels: data.fcm_labels,
                colors: ['#10b981', '#3b82f6', '#f43f5e'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    fontSize: '12px',
                                    color: textColor
                                },
                                value: {
                                    fontSize: '24px',
                                    fontWeight: 'bold',
                                    color: isDark ? '#fff' : '#1e293b'
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total Pelanggan',
                                    fontSize: '10px'
                                }
                            }
                        },
                        expandOnClick: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    fontSize: '12px'
                },
                states: {
                    hover: {
                        filter: {
                            type: 'darken',
                            value: 0.9
                        }
                    }
                },
                tooltip: {
                    theme: themeMode
                }
            });
            charts.fcm.render();
            document.querySelector("#chart-fcm").style.cursor = 'pointer';
        }

        // 4. AHP-SAW Sales Performance (Horizontal Bar AI)
        if (document.querySelector("#chart-ahp-sales")) {
            if (charts.ahp) charts.ahp.destroy();
            charts.ahp = new ApexCharts(document.querySelector("#chart-ahp-sales"), {
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    background: 'transparent'
                },
                theme: {
                    mode: themeMode
                },
                series: [{
                    name: 'Skor AI (V)',
                    data: data.ahp_sales_scores
                }],
                xaxis: {
                    categories: data.ahp_sales_names,
                    labels: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 'bold',
                            colors: textColor
                        }
                    }
                },
                colors: ['#3b82f6'],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 4,
                        barHeight: '50%'
                    }
                },
                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    style: {
                        fontSize: '10px',
                        colors: ['#fff']
                    },
                    offsetX: 10
                },
                grid: {
                    show: false
                }
            });
            charts.ahp.render();
        }
    };

    if (initData) renderCharts(initData);

    Livewire.on('update-charts', (event) => {
        renderCharts(event.data || event[0].data);
    });
});
</script>