<div class="min-h-screen space-y-6 font-jakarta pb-10 transition-colors duration-300 dark:bg-[#0a0a0a]"
    x-data="{ activeTab: 'overview' }">

    {{-- HEADER & FILTER AREA --}}
    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-slate-50/90 dark:bg-[#0a0a0a]/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 dark:border-white/5 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex flex-col md:flex-row items-center gap-6 w-full xl:w-auto">
                <div class="flex items-center gap-4">
                    <div
                        class="p-2 bg-slate-200 dark:bg-white/10 rounded-lg text-slate-600 dark:text-slate-300 shadow-sm border border-transparent dark:border-white/10">
                        <i class="fas fa-chart-pie text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">Executive
                            Dashboard</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Monitoring kinerja
                            bisnis real-time.</p>
                    </div>
                </div>

                <div
                    class="flex p-1 bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-lg shadow-sm overflow-x-auto">
                    <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'bg-slate-100 dark:bg-white/10 text-slate-800 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5'"
                        class="px-3 py-1.5 rounded-md text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                            class="fas fa-home"></i> Overview</button>
                    <button @click="activeTab = 'ranking'"
                        :class="activeTab === 'ranking' ? 'bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5'"
                        class="px-3 py-1.5 rounded-md text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                            class="fas fa-trophy"></i> Ranking</button>
                    <button @click="activeTab = 'salesman'"
                        :class="activeTab === 'salesman' ? 'bg-purple-50 dark:bg-purple-500/20 text-purple-700 dark:text-purple-300 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5'"
                        class="px-3 py-1.5 rounded-md text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                            class="fas fa-user-tie"></i> Sales</button>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">
                <div
                    class="flex items-center gap-1 bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-lg px-2 py-1 shadow-sm h-[38px]">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-xs font-bold text-slate-700 dark:text-slate-300 focus:ring-0 p-0 bg-transparent w-24 cursor-pointer [color-scheme:light] dark:[color-scheme:dark]">
                    <span class="text-slate-300 dark:text-slate-600 text-[10px]">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-xs font-bold text-slate-700 dark:text-slate-300 focus:ring-0 p-0 bg-transparent w-24 cursor-pointer [color-scheme:light] dark:[color-scheme:dark]">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:border-slate-300 dark:hover:border-white/20 transition-all h-[38px]">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang).' Dipilih' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 dark:text-slate-500 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-lg shadow-xl dark:shadow-2xl p-2 max-h-48 overflow-y-auto right-0"
                        style="display: none;">
                        @foreach($optCabang as $cab)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-slate-50 dark:hover:bg-white/5 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-[#18181b] text-slate-600 dark:text-slate-400 mr-2 h-3 w-3 focus:ring-slate-500 dark:focus:ring-slate-400 focus:ring-offset-0">
                            <span class="text-xs text-slate-600 dark:text-slate-300">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div wire:loading
                    class="px-3 py-2 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-400 rounded-lg shadow-sm flex items-center justify-center">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">

        {{-- TAB OVERVIEW --}}
        <div x-show="activeTab === 'overview'" x-transition.opacity.duration.300ms class="space-y-6">
            {{-- SMART ALERTS: RADAR PERINGATAN DINI --}}
            @if(count($alerts) > 0)
            <div class="mb-6 space-y-3" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
                <div class="flex items-center justify-between px-2 mb-2">
                    <h3
                        class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400 flex items-center gap-2">
                        <i class="fas fa-satellite-dish text-rose-500 animate-pulse"></i> Radar Peringatan Dini
                    </h3>
                    <button @click="show = false"
                        class="text-[10px] font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Tutup
                        Semua</button>
                </div>

                @foreach($alerts as $alert)
                @php
                $bgClass = $alert['type'] == 'danger' ? 'bg-rose-50 dark:bg-rose-500/10 border-rose-200
                dark:border-rose-500/30' : ($alert['type'] == 'warning' ? 'bg-orange-50 dark:bg-orange-500/10
                border-orange-200 dark:border-orange-500/30' : 'bg-blue-50 dark:bg-blue-500/10 border-blue-200
                dark:border-blue-500/30');
                $textClass = $alert['type'] == 'danger' ? 'text-rose-700 dark:text-rose-400' : ($alert['type'] ==
                'warning' ? 'text-orange-700 dark:text-orange-400' : 'text-blue-700 dark:text-blue-400');
                $iconClass = $alert['type'] == 'danger' ? 'bg-rose-100 text-rose-600 dark:bg-rose-900/40
                dark:text-rose-400' : ($alert['type'] == 'warning' ? 'bg-orange-100 text-orange-600
                dark:bg-orange-900/40 dark:text-orange-400' : 'bg-blue-100 text-blue-600 dark:bg-blue-900/40
                dark:text-blue-400');
                @endphp

                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-2xl border {{ $bgClass }} shadow-sm gap-4 transition-transform hover:-translate-y-0.5">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $iconClass }} shadow-inner">
                            <i
                                class="{{ $alert['icon'] }} text-lg {{ $alert['type'] == 'danger' ? 'animate-pulse' : '' }}"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-sm uppercase tracking-widest mb-1 {{ $textClass }}">
                                {{ $alert['title'] }}</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{!! $alert['message']
                                !!}</p>
                        </div>
                    </div>
                    @if(isset($alert['link']))
                    <a href="{{ $alert['link'] }}"
                        class="shrink-0 px-4 py-2 bg-white dark:bg-[#18181b] border border-slate-200 dark:border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $textClass }} hover:bg-slate-50 dark:hover:bg-white/5 transition-colors shadow-sm self-start sm:self-center text-center">
                        Tinjau Data <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            {{-- 4 KARTU KPI --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div
                    class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 rounded-2xl shadow-lg shadow-emerald-500/20 text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total
                            Penjualan</p>
                        <h3 class="text-xl font-extrabold tracking-tight">Rp {{ $this->formatCompact($salesSum) }}</h3>
                        <p class="text-[10px] mt-0.5 text-emerald-100 opacity-80">Real:
                            {{ number_format($salesSum, 0, ',', '.') }}</p>
                    </div>
                    <i
                        class="fas fa-chart-line absolute right-3 top-3 text-white/20 text-5xl group-hover:scale-110 transition-transform"></i>
                </div>

                <div
                    class="bg-white dark:bg-[#121212] p-4 rounded-2xl border border-rose-100 dark:border-rose-900/50 shadow-sm relative overflow-hidden group hover:border-rose-300 dark:hover:border-rose-700/50 transition-colors">
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">
                        Total Retur</p>
                    <h3 class="text-xl font-extrabold text-rose-500 tracking-tight">Rp
                        {{ $this->formatCompact($returSum) }}</h3>
                    <div
                        class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20">
                        <span class="text-[9px] font-bold text-rose-600 dark:text-rose-400">Rasio:
                            {{ number_format($persenRetur, 2) }}%</span>
                    </div>
                    <i
                        class="fas fa-undo absolute right-3 top-3 text-rose-100 dark:text-rose-900/30 text-5xl group-hover:rotate-[-12deg] transition-transform"></i>
                </div>

                <div
                    class="bg-white dark:bg-[#121212] p-4 rounded-2xl border border-orange-100 dark:border-orange-900/50 shadow-sm relative overflow-hidden hover:border-orange-300 dark:hover:border-orange-700/50 transition-colors">
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">
                        Piutang Baru</p>
                    <h3 class="text-xl font-extrabold text-orange-500 tracking-tight">Rp
                        {{ $this->formatCompact($arSum) }}</h3>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Tagihan Terbentuk</p>
                    <i
                        class="fas fa-file-invoice-dollar absolute right-3 top-3 text-orange-100 dark:text-orange-900/30 text-5xl"></i>
                </div>

                <div
                    class="bg-white dark:bg-[#121212] p-4 rounded-2xl border border-cyan-100 dark:border-cyan-900/50 shadow-sm relative overflow-hidden hover:border-cyan-300 dark:hover:border-cyan-700/50 transition-colors">
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">
                        Uang Masuk (Coll)</p>
                    <h3 class="text-xl font-extrabold text-cyan-600 dark:text-cyan-500 tracking-tight">Rp
                        {{ $this->formatCompact($collSum) }}</h3>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Pembayaran Diterima</p>
                    <i class="fas fa-wallet absolute right-3 top-3 text-cyan-100 dark:text-cyan-900/30 text-5xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6" wire:ignore>
                <div
                    class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 transition-colors">
                    <div class="flex items-center gap-3 mb-4 border-b border-slate-100 dark:border-white/5 pb-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20">
                            <i class="fas fa-chart-area"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm">Tren Penjualan vs Retur (Harian)
                        </h4>
                    </div>
                    <div id="chart-sales-retur" style="min-height: 350px;"></div>
                </div>

                <div
                    class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 transition-colors">
                    <div class="flex items-center gap-3 mb-4 border-b border-slate-100 dark:border-white/5 pb-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center border border-orange-100 dark:border-orange-500/20">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm">Tagihan vs Pembayaran (Harian)</h4>
                    </div>
                    <div id="chart-ar-coll" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        {{-- TAB RANKING (DENGAN TAMBAHAN FILTER) --}}
        <div x-show="activeTab === 'ranking'" x-transition.opacity.duration.300ms class="grid grid-cols-1 gap-6">

            {{-- 1. Top Produk (Filter by Supplier) --}}
            <div
                class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 transition-colors">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-2 border-b border-slate-100 dark:border-white/5">
                    <h4 class="font-bold text-slate-700 dark:text-white flex items-center gap-2">
                        <i class="fas fa-box text-blue-500"></i> Top 10 Produk (Qty)
                    </h4>
                    <select wire:model.live="rankingFilterSupplier"
                        class="text-[10px] font-bold rounded-lg border-slate-200 dark:border-white/10 dark:bg-[#18181b] dark:text-white focus:ring-blue-500 w-full sm:w-48 py-2 px-3 uppercase tracking-wider cursor-pointer">
                        <option value="">-- Semua Pemasok --</option>
                        @foreach($optSupplier as $supp)
                        <option value="{{ $supp }}">{{ Str::limit($supp, 20) }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="chart-top-produk" wire:ignore style="min-height: 400px;"></div>
            </div>

            {{-- 2. Top Pelanggan (Filter by Salesman) --}}
            <div
                class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 transition-colors">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-2 border-b border-slate-100 dark:border-white/5">
                    <h4 class="font-bold text-slate-700 dark:text-white flex items-center gap-2">
                        <i class="fas fa-users text-purple-500 dark:text-purple-400"></i> Top 10 Pelanggan (Omzet)
                    </h4>
                    <select wire:model.live="rankingFilterSalesCustomer"
                        class="text-[10px] font-bold rounded-lg border-slate-200 dark:border-white/10 dark:bg-[#18181b] dark:text-white focus:ring-purple-500 w-full sm:w-48 py-2 px-3 uppercase tracking-wider cursor-pointer">
                        <option value="">-- Semua Salesman --</option>
                        @foreach($optSales as $s)
                        <option value="{{ $s }}">{{ Str::limit($s, 20) }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="chart-top-customer" wire:ignore style="min-height: 400px;"></div>
            </div>

            {{-- 3. Top Supplier (Filter by Salesman) --}}
            <div
                class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 transition-colors">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-2 border-b border-slate-100 dark:border-white/5">
                    <h4 class="font-bold text-slate-700 dark:text-white flex items-center gap-2">
                        <i class="fas fa-truck text-pink-500 dark:text-pink-400"></i> Top 10 Supplier (Omzet)
                    </h4>
                    <select wire:model.live="rankingFilterSalesSupplier"
                        class="text-[10px] font-bold rounded-lg border-slate-200 dark:border-white/10 dark:bg-[#18181b] dark:text-white focus:ring-pink-500 w-full sm:w-48 py-2 px-3 uppercase tracking-wider cursor-pointer">
                        <option value="">-- Semua Salesman --</option>
                        @foreach($optSales as $s)
                        <option value="{{ $s }}">{{ Str::limit($s, 20) }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="chart-top-supplier" wire:ignore style="min-height: 400px;"></div>
            </div>

        </div>

        {{-- TAB SALESMAN --}}
        <div x-show="activeTab === 'salesman'" x-transition.opacity.duration.300ms class="grid grid-cols-1 gap-6"
            wire:ignore>
            <div
                class="bg-white dark:bg-[#121212] p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 transition-colors">
                <h4
                    class="font-bold text-lg text-indigo-900 dark:text-white mb-4 pb-2 border-b border-slate-50 dark:border-white/5 flex items-center gap-2">
                    <i class="fas fa-bullseye text-indigo-500"></i> Top 10 Sales Performance (Target vs Realisasi)
                </h4>
                <div id="chart-sales-perf" style="min-height: 500px;"></div>
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
        const font = 'Plus Jakarta Sans, sans-serif';

        // FUNGSI FORMATTER (Diperbaiki agar tidak memunculkan NaN)
        const fmtRp = (v) => {
            if (v == null || isNaN(v)) return "Rp 0";
            return "Rp " + new Intl.NumberFormat('id-ID').format(v);
        };
        const fmtJt = (v) => {
            if (v == null || isNaN(v)) return "0 Jt";
            return (Number(v) / 1000000).toFixed(1) + " Jt";
        };
        const fmtUnit = (v) => {
            if (v == null || isNaN(v)) return "0 Unit";
            return new Intl.NumberFormat('id-ID').format(v) + " Unit";
        };

        const textColor = isDark ? '#94a3b8' : '#64748b';
        const gridColor = isDark ? 'rgba(255,255,255,0.05)' : '#f1f5f9';

        const baseOptions = {
            chart: {
                fontFamily: font,
                toolbar: {
                    show: false
                },
                background: 'transparent'
            },
            theme: {
                mode: themeMode
            },
            grid: {
                borderColor: gridColor
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                labels: {
                    style: {
                        colors: textColor
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '10px',
                        colors: textColor
                    }
                    // Dihapus formatter global dari sini agar nama (huruf) tidak diubah menjadi "NaN Jt"
                }
            }
        };

        // 1. Sales vs Retur (Area)
        if (document.querySelector("#chart-sales-retur")) {
            if (charts.sr) charts.sr.destroy();
            charts.sr = new ApexCharts(document.querySelector("#chart-sales-retur"), {
                ...baseOptions,
                series: [{
                    name: 'Penjualan',
                    data: data.trend_sales
                }, {
                    name: 'Retur',
                    data: data.trend_retur
                }],
                chart: {
                    ...baseOptions.chart,
                    type: 'area',
                    height: 350
                },
                colors: ['#10b981', '#f43f5e'],
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    ...baseOptions.xaxis,
                    categories: data.dates,
                    labels: {
                        show: false
                    }
                },
                yaxis: {
                    ...baseOptions.yaxis,
                    labels: {
                        ...baseOptions.yaxis.labels,
                        formatter: fmtJt
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.4,
                        opacityTo: 0.05
                    }
                },
                tooltip: {
                    theme: themeMode,
                    y: {
                        formatter: fmtRp
                    }
                }
            });
            charts.sr.render();
        }

        // 2. AR vs Collection (Bar)
        if (document.querySelector("#chart-ar-coll")) {
            if (charts.ac) charts.ac.destroy();
            charts.ac = new ApexCharts(document.querySelector("#chart-ar-coll"), {
                ...baseOptions,
                series: [{
                    name: 'Piutang Baru',
                    data: data.trend_ar
                }, {
                    name: 'Pelunasan',
                    data: data.trend_coll
                }],
                chart: {
                    ...baseOptions.chart,
                    type: 'bar',
                    height: 350
                },
                colors: ['#f97316', '#06b6d4'],
                plotOptions: {
                    bar: {
                        borderRadius: 3,
                        columnWidth: '60%'
                    }
                },
                xaxis: {
                    ...baseOptions.xaxis,
                    categories: data.dates,
                    labels: {
                        show: false
                    }
                },
                yaxis: {
                    ...baseOptions.yaxis,
                    labels: {
                        ...baseOptions.yaxis.labels,
                        formatter: fmtJt
                    }
                },
                tooltip: {
                    theme: themeMode,
                    y: {
                        formatter: fmtRp
                    }
                }
            });
            charts.ac.render();
        }

        // Config Ranking Charts (Horizontal)
        const rankingOpts = {
            ...baseOptions,
            chart: {
                ...baseOptions.chart,
                type: 'bar',
                height: 400
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '65%',
                    borderRadius: 2
                }
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 0,
                style: {
                    fontSize: '10px',
                    colors: ['#fff']
                }
            },
            grid: {
                show: false
            },
            xaxis: {
                ...baseOptions.xaxis,
                labels: {
                    show: false
                }
            },
            yaxis: {
                ...baseOptions.yaxis,
                labels: {
                    ...baseOptions.yaxis.labels,
                    formatter: function(val) {
                        return val;
                    } // Pastikan label nama tidak diubah
                }
            }
        };

        // 3. Top Produk
        if (document.querySelector("#chart-top-produk")) {
            if (charts.tp) charts.tp.destroy();
            charts.tp = new ApexCharts(document.querySelector("#chart-top-produk"), {
                ...rankingOpts,
                series: [{
                    name: 'Qty',
                    data: data.top_produk_val
                }],
                xaxis: {
                    ...rankingOpts.xaxis,
                    categories: data.top_produk_lbl
                },
                colors: ['#3b82f6'],
                dataLabels: {
                    ...rankingOpts.dataLabels,
                    formatter: fmtUnit
                },
                tooltip: {
                    theme: themeMode,
                    y: {
                        formatter: fmtUnit
                    }
                }
            });
            charts.tp.render();
        }

        // 4. Top Customer
        if (document.querySelector("#chart-top-customer")) {
            if (charts.tc) charts.tc.destroy();
            charts.tc = new ApexCharts(document.querySelector("#chart-top-customer"), {
                ...rankingOpts,
                series: [{
                    name: 'Omzet',
                    data: data.top_cust_val
                }],
                xaxis: {
                    ...rankingOpts.xaxis,
                    categories: data.top_cust_lbl
                },
                colors: ['#8b5cf6'],
                dataLabels: {
                    ...rankingOpts.dataLabels,
                    formatter: fmtJt
                },
                tooltip: {
                    theme: themeMode,
                    y: {
                        formatter: fmtRp
                    }
                }
            });
            charts.tc.render();
        }

        // 5. Top Supplier
        if (document.querySelector("#chart-top-supplier")) {
            if (charts.ts) charts.ts.destroy();
            charts.ts = new ApexCharts(document.querySelector("#chart-top-supplier"), {
                ...rankingOpts,
                series: [{
                    name: 'Omzet',
                    data: data.top_supp_val
                }],
                xaxis: {
                    ...rankingOpts.xaxis,
                    categories: data.top_supp_lbl
                },
                colors: ['#ec4899'],
                dataLabels: {
                    ...rankingOpts.dataLabels,
                    formatter: fmtJt
                },
                tooltip: {
                    theme: themeMode,
                    y: {
                        formatter: fmtRp
                    }
                }
            });
            charts.ts.render();
        }

        // 6. Sales Perf
        if (document.querySelector("#chart-sales-perf")) {
            if (charts.sp) charts.sp.destroy();
            charts.sp = new ApexCharts(document.querySelector("#chart-sales-perf"), {
                ...baseOptions,
                series: [{
                    name: 'Realisasi',
                    data: data.sales_real
                }, {
                    name: 'Target',
                    data: data.sales_target
                }],
                chart: {
                    ...baseOptions.chart,
                    type: 'bar',
                    height: 500
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 3
                    }
                },
                xaxis: {
                    ...baseOptions.xaxis,
                    categories: data.sales_names
                },
                yaxis: {
                    ...baseOptions.yaxis,
                    labels: {
                        ...baseOptions.yaxis.labels,
                        formatter: fmtJt
                    }
                },
                colors: ['#4f46e5', isDark ? '#334155' : '#cbd5e1'],
                tooltip: {
                    theme: themeMode,
                    y: {
                        formatter: fmtRp
                    }
                }
            });
            charts.sp.render();
        }
    };

    if (initData) renderCharts(initData);

    Livewire.on('update-charts', (event) => {
        const newData = event.data || (event[0] && event[0].data) || event;
        if (newData) renderCharts(newData);
    });

    const observer = new MutationObserver(() => {
        if (initData) renderCharts(initData);
    });
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});
</script>