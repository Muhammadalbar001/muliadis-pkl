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

    {{-- 1. WELCOME HERO SECTION (High Contrast) --}}
    <div
        class="bg-white dark:bg-[#121212] rounded-2xl p-8 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden">
        <div
            class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-blue-50 to-transparent dark:from-blue-900/10 pointer-events-none">
        </div>
        <div class="absolute -right-10 -top-10 text-blue-50 dark:text-blue-900/20 rotate-12 pointer-events-none">
            <i class="fas fa-shield-alt text-[15rem]"></i>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <p class="text-blue-600 dark:text-blue-400 font-bold tracking-wider text-xs mb-2 uppercase">
                    Ringkasan Operasional
                </p>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                    Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm max-w-xl leading-relaxed">
                    Pantau status master data harian, selidiki anomali retur, dan pastikan piutang kritis segera
                    ditangani oleh tim lapangan.
                </p>

                <div
                    class="mt-4 flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 w-fit px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700">
                    <i class="fas fa-clock text-blue-500"></i> Terakhir Import Data:
                    <span class="text-blue-700 dark:text-blue-400">
                        {{ $lastSync ? \Carbon\Carbon::parse($lastSync)->translatedFormat('d M Y, H:i') : 'Belum ada data' }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('master.produk') }}"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm shadow-blue-600/30">
                    <i class="fas fa-box-open"></i> Kelola Produk
                </a>
                <a href="{{ route('master.sales') }}"
                    class="flex items-center gap-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm">
                    <i class="fas fa-users"></i> Tim Sales
                </a>
            </div>
        </div>
    </div>

    {{-- 2. METRIK MASTER DATA (4 CARDS) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

        <div
            class="bg-white dark:bg-[#121212] rounded-2xl p-6 border {{ $anomaliProdukCount > 0 ? 'border-rose-400 dark:border-rose-600 shadow-rose-500/10' : 'border-slate-200 dark:border-white/10' }} shadow-sm relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-rose-50 dark:from-rose-900/10 to-transparent pointer-events-none">
            </div>

            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Anomali Master</p>
                    <h3 class="text-3xl font-black text-slate-800 dark:text-white">
                        {{ number_format($anomaliProdukCount) }}</h3>
                </div>
                <div
                    class="w-12 h-12 rounded-xl {{ $anomaliProdukCount > 0 ? 'bg-rose-500 text-white' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400' }} flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i
                        class="fas fa-exclamation-triangle text-xl {{ $anomaliProdukCount > 0 ? 'animate-pulse' : '' }}"></i>
                </div>
            </div>

            <div class="mt-5 relative z-10">
                @if($anomaliProdukCount > 0)
                <div
                    class="flex items-center gap-2 text-[10px] font-bold text-rose-700 dark:text-rose-300 px-2 py-2 bg-rose-100 dark:bg-rose-900/40 rounded-lg border border-rose-200 dark:border-rose-700/50 leading-tight">
                    <i class="fas fa-info-circle text-base"></i> Terdapat SKU Penjualan yang tidak terdaftar di Master
                    Produk!
                </div>
                @else
                <div
                    class="flex items-center gap-2 text-xs font-medium text-emerald-600 dark:text-emerald-400 px-3 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-100 dark:border-emerald-800/30">
                    <i class="fas fa-check-double text-emerald-500"></i> Semua SKU sinkron.
                </div>
                @endif
            </div>
        </div>

        <div
            class="bg-white dark:bg-[#121212] rounded-2xl p-6 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-cyan-50 dark:from-cyan-900/10 to-transparent pointer-events-none">
            </div>

            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Produk</p>
                    <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($totalProduk) }}
                    </h3>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center text-cyan-600 dark:text-cyan-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-boxes text-xl"></i>
                </div>
            </div>

            <div class="mt-5 relative z-10">
                @if($produkKosong > 0)
                <div
                    class="flex items-center gap-2 text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 px-3 py-2 rounded-lg border border-rose-100 dark:border-rose-800/30">
                    <i class="fas fa-box-open"></i> {{ $produkKosong }} Stok Kosong
                </div>
                @else
                <div
                    class="flex items-center gap-2 text-xs font-medium text-emerald-600 dark:text-emerald-400 px-3 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-100 dark:border-emerald-800/30">
                    <i class="fas fa-check-circle text-emerald-500"></i> Stok master aman
                </div>
                @endif
            </div>
        </div>

        <div
            class="bg-white dark:bg-[#121212] rounded-2xl p-6 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-indigo-50 dark:from-indigo-900/10 to-transparent pointer-events-none">
            </div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Supplier</p>
                    <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($totalSupplier) }}
                    </h3>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-truck text-xl"></i>
                </div>
            </div>
            <div class="mt-5 relative z-10">
                <div
                    class="flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-400 px-3 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-100 dark:border-slate-700/50">
                    <i class="fas fa-handshake text-indigo-500"></i> Mitra penyuplai
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-[#121212] rounded-2xl p-6 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-emerald-50 dark:from-emerald-900/10 to-transparent pointer-events-none">
            </div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Salesman</p>
                    <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($totalSalesman) }}
                    </h3>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
            </div>
            <div class="mt-5 relative z-10">
                <div
                    class="flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-400 px-3 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-100 dark:border-slate-700/50">
                    <i class="fas fa-briefcase text-emerald-500"></i> Tim lapangan
                </div>
            </div>
        </div>

    </div>

    {{-- 3. TABEL ACTION NEEDED (3 KOLOM) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div
            class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div
                class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/20">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400">
                        <i class="fas fa-undo-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-base">Top Retur</h3>
                        <p class="text-[11px] text-slate-500">Kuantitas tertinggi bulan ini</p>
                    </div>
                </div>
            </div>
            <div class="p-0 flex-1 overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Item</th>
                            <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider text-right">Qty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($topRetur as $index => $rt)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-5 py-4 flex items-center gap-2">
                                <div class="text-[10px] font-bold text-slate-400 w-3">{{ $index + 1 }}.</div>
                                <div class="font-semibold text-slate-700 dark:text-slate-200 truncate max-w-[120px]">
                                    {{ $rt->nama_item }}</div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <span
                                    class="inline-flex items-center justify-center bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 px-2 py-0.5 rounded text-xs font-bold border border-rose-200 dark:border-rose-800/50">
                                    {{ number_format($rt->total_qty) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-5 py-10 text-center text-slate-500">
                                <p class="text-sm font-medium">Belum ada retur.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div
                class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/20">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-base">Piutang Kritis</h3>
                        <p class="text-[11px] text-slate-500">Hutang toko (> 30 Hari)</p>
                    </div>
                </div>
            </div>
            <div class="p-0 flex-1 overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Outlet</th>
                            <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider text-right">Tagihan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($piutangKritis as $ar)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-700 dark:text-slate-200 truncate max-w-[120px]">
                                    {{ $ar->pelanggan_name }}</div>
                                <div
                                    class="text-[10px] mt-1 font-bold text-rose-600 bg-rose-50 dark:bg-rose-900/30 w-fit px-1.5 rounded">
                                    {{ $ar->umur_piutang }} Hari
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="font-black text-slate-800 dark:text-white text-xs">
                                    Rp {{ number_format((float) $ar->nilai, 0, ',', '.') }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-5 py-10 text-center text-slate-500">
                                <p class="text-sm font-medium">Aman. Tidak ada piutang macet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div
                class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/20">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                        <i class="fas fa-level-down-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-base">Perlu Coaching</h3>
                        <p class="text-[11px] text-slate-500">Bottom 3 Kinerja (Bulan Ini)</p>
                    </div>
                </div>
            </div>
            <div class="p-0 flex-1 overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Nama Sales</th>
                            <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider text-right">Omzet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($bottomSales as $bs)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-700 dark:text-slate-200 truncate max-w-[120px]">
                                    {{ $bs->sales_name }}</div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="font-black text-slate-800 dark:text-white text-xs">
                                    Rp {{ number_format((float) $bs->total_omzet, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] mt-1 font-bold text-purple-600">
                                    <i class="fas fa-arrow-down"></i> Rendah
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-5 py-10 text-center text-slate-500">
                                <p class="text-sm font-medium">Belum ada data penjualan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>