<div class="min-h-screen space-y-6 font-jakarta pb-10 transition-colors duration-300 dark:bg-[#0a0a0a]"
    x-data="{ activeTab: 'overview' }" x-init="$watch('activeTab', value => {
         if (value === 'ai') {
             setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 150);
         }
     })">

    {{-- HEADER & FILTER AREA --}}
    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-slate-50/90 dark:bg-[#0a0a0a]/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 dark:border-white/5 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex flex-col md:flex-row items-center gap-6 w-full xl:w-auto">
                <div class="flex items-center gap-4">
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

                <div
                    class="flex p-1 bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-lg shadow-sm overflow-x-auto">
                    <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-3 py-1.5 rounded-md text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                            class="fas fa-home"></i> Ringkasan Utama</button>
                    <button @click="activeTab = 'ai'"
                        :class="activeTab === 'ai' ? 'bg-purple-50 dark:bg-purple-500/20 text-purple-700 dark:text-purple-300 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-3 py-1.5 rounded-md text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                            class="fas fa-robot"></i> Keputusan AI</button>
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
    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">

        {{-- TAB OVERVIEW (RINGKASAN UTAMA) --}}
        <div x-show="activeTab === 'overview'" x-transition.opacity.duration.300ms class="space-y-6">

            {{-- SMART ALERTS: RADAR PERINGATAN DINI --}}
            @if(count($alerts) > 0)
            <div class="mb-6 space-y-3">
                <div class="flex items-center px-2 mb-2">
                    <h3
                        class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 flex items-center gap-2">
                        <i class="fas fa-satellite-dish animate-pulse"></i> Radar Peringatan Dini AI
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
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{!! $alert['message']
                                !!}</p>
                            <a href="{{ $alert['link'] }}"
                                class="inline-block mt-2 text-[10px] font-bold text-indigo-600 hover:underline">Tinjau
                                Analitik &rarr;</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 4 KARTU KPI STANDAR --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div
                    class="bg-gradient-to-br from-indigo-500 to-blue-600 p-4 rounded-2xl shadow-lg text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-indigo-100 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Penjualan
                        </p>
                        <h3 class="text-2xl font-extrabold tracking-tight">Rp {{ $this->formatCompact($salesSum) }}</h3>
                        <p class="text-[10px] mt-0.5 text-indigo-100 opacity-80">Realisasi Cabang</p>
                    </div>
                    <i
                        class="fas fa-chart-line absolute right-3 top-3 text-white/20 text-5xl group-hover:scale-110 transition-transform"></i>
                </div>

                <div
                    class="bg-white dark:bg-[#121212] p-4 rounded-2xl border border-rose-100 dark:border-white/5 shadow-sm relative overflow-hidden">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Retur</p>
                    <h3 class="text-2xl font-extrabold text-rose-500 tracking-tight">Rp
                        {{ $this->formatCompact($returSum) }}</h3>
                    <div class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded bg-rose-50 border border-rose-100">
                        <span class="text-[9px] font-bold text-rose-600">Rasio Kerugian:
                            {{ number_format($persenRetur, 2) }}%</span>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-[#121212] p-4 rounded-2xl border border-orange-100 dark:border-white/5 shadow-sm relative overflow-hidden">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Piutang Dagang (AR)
                    </p>
                    <h3 class="text-2xl font-extrabold text-orange-500 tracking-tight">Rp
                        {{ $this->formatCompact($arSum) }}</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Tagihan Terbentuk</p>
                </div>

                <div
                    class="bg-white dark:bg-[#121212] p-4 rounded-2xl border border-emerald-100 dark:border-white/5 shadow-sm relative overflow-hidden">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Uang Masuk (Coll)
                    </p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 tracking-tight">Rp
                        {{ $this->formatCompact($collSum) }}</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Pembayaran Diterima</p>
                </div>
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

        {{-- TAB KEPUTUSAN AI (AHP-SAW & FCM) --}}
        <div x-show="activeTab === 'ai'" x-transition.opacity.duration.300ms
            class="grid grid-cols-1 lg:grid-cols-2 gap-6" wire:ignore>

            {{-- Doughnut Chart Segmentasi FCM --}}
            <div
                class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-fuchsia-200 dark:border-fuchsia-900/30">
                <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100 dark:border-white/5">
                    <h4 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-project-diagram text-fuchsia-500"></i> Klasterisasi Pelanggan (FCM)
                    </h4>
                    <a href="{{ route('keputusan.rfm-pelanggan') }}"
                        class="text-[10px] font-bold text-fuchsia-600 bg-fuchsia-50 px-2 py-1 rounded">Lihat Matriks
                        &rarr;</a>
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
                        class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">Lihat Matriks
                        &rarr;</a>
                </div>
                <div id="chart-ahp-sales" style="min-height: 350px;"></div>
            </div>

        </div>

    </div>
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
                    background: 'transparent'
                },
                theme: {
                    mode: themeMode
                },
                series: data.fcm_series,
                labels: data.fcm_labels,
                colors: ['#10b981', '#3b82f6', '#f43f5e'], // Utama, Menengah, Pasif
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
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    fontSize: '12px'
                }
            });
            charts.fcm.render();
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