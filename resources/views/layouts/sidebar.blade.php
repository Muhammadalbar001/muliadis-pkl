<aside class="fixed inset-y-0 left-0 z-50 transition-all duration-500 flex flex-col shadow-2xl border-r 
    bg-white border-slate-200 text-slate-600 
    dark:bg-[#0a0a0a] dark:border-white/5 dark:text-neutral-300" :class="{ 
        'translate-x-0': sidebarOpen, 
        '-translate-x-full': !sidebarOpen, 
        'lg:translate-x-0': true,
        'w-64': isSidebarExpanded,
        'w-20': !isSidebarExpanded
    }" x-cloak>

    {{-- HEADER SIDEBAR --}}
    <div class="h-24 flex-none flex items-center px-6 border-b transition-colors duration-300
        border-slate-100 bg-slate-50/50 
        dark:border-white/5 dark:bg-gradient-to-b dark:from-[#121212] dark:to-[#0a0a0a]"
        :class="isSidebarExpanded ? 'justify-between' : 'justify-center'">

        <div class="flex items-center gap-4 overflow-hidden whitespace-nowrap">
            {{-- Logo Icon --}}
            <div class="w-10 h-10 md:w-12 md:h-12 rounded-2xl flex-none flex items-center justify-center text-white shadow-lg 
                bg-gradient-to-br from-blue-500 to-indigo-600 ring-2 ring-blue-500/20 dark:ring-white/10">
                <i class="fas fa-truck-fast text-lg"></i>
            </div>

            {{-- Logo Text --}}
            <div x-show="isSidebarExpanded" x-transition.opacity.duration.400ms>
                <h1 class="font-black text-xs tracking-tighter uppercase leading-tight italic 
                    text-slate-800 dark:text-white">
                    PT MULIA <span class="text-blue-600 dark:text-blue-500">ANUGERAH</span>
                </h1>
                <p class="text-[7px] font-black tracking-[0.4em] uppercase mt-1 flex items-center gap-1
                    text-slate-400 dark:text-slate-500">
                    <span class="w-1 h-1 bg-blue-500 rounded-full animate-pulse"></span> Distribusindo
                </p>
            </div>
        </div>

        {{-- Mobile Close Button --}}
        <button @click="sidebarOpen = false"
            class="lg:hidden text-slate-400 hover:text-slate-800 dark:hover:text-white p-1">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    {{-- MENU ITEMS --}}
    <div class="flex-1 overflow-y-auto py-6 space-y-2 custom-scrollbar overflow-x-hidden"
        :class="isSidebarExpanded ? 'px-4' : 'px-2'">

        {{-- DASHBOARD --}}
        <a href="{{ route('dashboard') }}"
            class="flex items-center py-3.5 rounded-xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest mb-4
            {{ request()->routeIs('dashboard') 
                ? 'bg-blue-50 text-blue-600 dark:bg-gradient-to-r dark:from-blue-600 dark:to-indigo-600 dark:text-white shadow-sm ring-1 ring-blue-100 dark:ring-white/20' 
                : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600 dark:hover:bg-white/5 dark:hover:text-white' }}"
            :class="isSidebarExpanded ? 'px-5' : 'justify-center'">

            @if(request()->routeIs('dashboard'))
            <div class="absolute left-0 h-8 w-1 bg-blue-600 rounded-r-full hidden lg:block" x-show="isSidebarExpanded">
            </div>
            @endif

            <i
                class="fas fa-chart-pie w-5 text-center text-base {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-white' : 'group-hover:text-blue-500 dark:group-hover:text-blue-400' }}"></i>
            <span x-show="isSidebarExpanded" class="ml-4">Dashboard</span>

            {{-- Tooltip Collapsed --}}
            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-slate-800 text-white text-[10px] px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-50 whitespace-nowrap shadow-xl font-bold tracking-wider uppercase transform translate-x-2 group-hover:translate-x-0">
                Dashboard
            </div>
        </a>

        {{-- MASTER DATA --}}
        @if(in_array(auth()->user()->role, ['super_admin', 'pimpinan', 'supervisor']))
        <div x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }" class="w-full relative group">
            <button @click="isSidebarExpanded ? open = !open : isSidebarExpanded = true"
                class="flex items-center w-full py-3 rounded-xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
                {{ request()->routeIs('master.*') 
                    ? 'text-cyan-600 bg-cyan-50 dark:text-cyan-400 dark:bg-cyan-400/10' 
                    : 'text-slate-500 hover:bg-slate-50 hover:text-cyan-600 dark:hover:bg-white/5 dark:hover:text-white' }}"
                :class="isSidebarExpanded ? 'px-5 justify-between' : 'justify-center'">

                <div class="flex items-center">
                    <span class="w-5 flex justify-center">
                        <i
                            class="fas fa-database text-base {{ request()->routeIs('master.*') ? 'text-cyan-500 dark:text-cyan-400' : 'group-hover:text-cyan-500 dark:group-hover:text-cyan-400' }}"></i>
                    </span>
                    <span x-show="isSidebarExpanded" class="ml-4">Master Data</span>
                </div>
                <i x-show="isSidebarExpanded"
                    class="fas fa-chevron-right text-[9px] transition-transform duration-300 opacity-70"
                    :class="{'rotate-90': open}"></i>
            </button>

            {{-- Submenu Master --}}
            <div x-show="open && isSidebarExpanded" x-cloak x-transition.origin.top
                class="mt-1 space-y-0.5 ml-4 border-l-[1.5px] border-slate-200 pl-3 dark:border-white/10">

                <a href="{{ route('master.produk') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('master.produk') ? 'text-cyan-600 bg-cyan-50/50 dark:text-cyan-300 dark:bg-white/5' : 'text-slate-500 hover:text-cyan-600 dark:hover:text-cyan-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('master.produk') ? 'bg-cyan-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Produk
                </a>

                <a href="{{ route('master.supplier') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('master.supplier') ? 'text-cyan-600 bg-cyan-50/50 dark:text-cyan-300 dark:bg-white/5' : 'text-slate-500 hover:text-cyan-600 dark:hover:text-cyan-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('master.supplier') ? 'bg-cyan-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Supplier
                </a>

                <a href="{{ route('master.sales') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('master.sales') ? 'text-cyan-600 bg-cyan-50/50 dark:text-cyan-300 dark:bg-white/5' : 'text-slate-500 hover:text-cyan-600 dark:hover:text-cyan-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('master.sales') ? 'bg-cyan-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Salesman
                </a>

                @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('master.user') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('master.user') ? 'text-rose-500 bg-rose-50/50 dark:text-rose-300 dark:bg-white/5' : 'text-slate-500 hover:text-rose-500 dark:hover:text-rose-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('master.user') ? 'bg-rose-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Pengguna
                </a>
                @endif
            </div>

            {{-- Tooltip Collapsed --}}
            <div x-show="!isSidebarExpanded"
                class="absolute left-14 top-1 bg-slate-800 text-white text-[10px] px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-50 whitespace-nowrap shadow-xl font-bold tracking-wider uppercase transform translate-x-2 group-hover:translate-x-0">
                Master Data
            </div>
        </div>
        @endif

        {{-- OPERASIONAL --}}
        <div x-data="{ open: {{ request()->routeIs('transaksi.*') ? 'true' : 'false' }} }"
            class="w-full relative group">
            <button @click="isSidebarExpanded ? open = !open : isSidebarExpanded = true"
                class="flex items-center w-full py-3 rounded-xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
                {{ request()->routeIs('transaksi.*') 
                    ? 'text-emerald-600 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-400/10' 
                    : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600 dark:hover:bg-white/5 dark:hover:text-white' }}"
                :class="isSidebarExpanded ? 'px-5 justify-between' : 'justify-center'">

                <div class="flex items-center">
                    <span class="w-5 flex justify-center">
                        <i
                            class="fas fa-shopping-cart text-base {{ request()->routeIs('transaksi.*') ? 'text-emerald-500 dark:text-emerald-400' : 'group-hover:text-emerald-500 dark:group-hover:text-emerald-400' }}"></i>
                    </span>
                    <span x-show="isSidebarExpanded" class="ml-4">Operasional</span>
                </div>
                <i x-show="isSidebarExpanded"
                    class="fas fa-chevron-right text-[9px] transition-transform duration-300 opacity-70"
                    :class="{'rotate-90': open}"></i>
            </button>

            <div x-show="open && isSidebarExpanded" x-cloak x-transition.origin.top
                class="mt-1 space-y-0.5 ml-4 border-l-[1.5px] border-slate-200 pl-3 dark:border-white/10">

                <a href="{{ route('transaksi.penjualan') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('transaksi.penjualan') ? 'text-emerald-600 bg-emerald-50/50 dark:text-emerald-300 dark:bg-white/5' : 'text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('transaksi.penjualan') ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Penjualan
                </a>

                <a href="{{ route('transaksi.retur') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('transaksi.retur') ? 'text-emerald-600 bg-emerald-50/50 dark:text-emerald-300 dark:bg-white/5' : 'text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('transaksi.retur') ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Retur Barang
                </a>

                <a href="{{ route('transaksi.ar') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('transaksi.ar') ? 'text-emerald-600 bg-emerald-50/50 dark:text-emerald-300 dark:bg-white/5' : 'text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('transaksi.ar') ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Piutang (AR)
                </a>

                <a href="{{ route('transaksi.collection') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('transaksi.collection') ? 'text-emerald-600 bg-emerald-50/50 dark:text-emerald-300 dark:bg-white/5' : 'text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('transaksi.collection') ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Pelunasan
                </a>
            </div>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 top-1 bg-slate-800 text-white text-[10px] px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-50 whitespace-nowrap shadow-xl font-bold tracking-wider uppercase transform translate-x-2 group-hover:translate-x-0">
                Operasional
            </div>
        </div>

        {{-- ANALISA & LAPORAN --}}
        @if(in_array(auth()->user()->role, ['super_admin', 'pimpinan', 'supervisor']))
        <div x-data="{ open: {{ (request()->routeIs('pimpinan.*') || request()->routeIs('laporan.*')) ? 'true' : 'false' }} }"
            class="w-full relative group">
            <button @click="isSidebarExpanded ? open = !open : isSidebarExpanded = true"
                class="flex items-center w-full py-3 rounded-xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
                {{ (request()->routeIs('pimpinan.*') || request()->routeIs('laporan.*')) 
                    ? 'text-amber-600 bg-amber-50 dark:text-amber-400 dark:bg-amber-400/10' 
                    : 'text-slate-500 hover:bg-slate-50 hover:text-amber-600 dark:hover:bg-white/5 dark:hover:text-white' }}"
                :class="isSidebarExpanded ? 'px-5 justify-between' : 'justify-center'">

                <div class="flex items-center">
                    <span class="w-5 flex justify-center">
                        <i
                            class="fas fa-file-contract text-base {{ (request()->routeIs('pimpinan.*') || request()->routeIs('laporan.*')) ? 'text-amber-500 dark:text-amber-400' : 'group-hover:text-amber-500 dark:group-hover:text-amber-400' }}"></i>
                    </span>
                    <span x-show="isSidebarExpanded" class="ml-4">Analisa</span>
                </div>
                <i x-show="isSidebarExpanded"
                    class="fas fa-chevron-right text-[9px] transition-transform duration-300 opacity-70"
                    :class="{'rotate-90': open}"></i>
            </button>

            <div x-show="open && isSidebarExpanded" x-cloak x-transition.origin.top
                class="mt-1 space-y-0.5 ml-4 border-l-[1.5px] border-slate-200 pl-3 dark:border-white/10">

                {{-- [MENU BARU] PUSAT CETAK --}}
                <a href="{{ route('pusat-cetak') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors mb-2
                    {{ request()->routeIs('pusat-cetak') ? 'text-white bg-amber-500 shadow-lg shadow-amber-500/20' : 'text-slate-500 hover:text-amber-600 dark:hover:text-amber-300' }}">
                    <i class="fas fa-print w-4 text-center"></i>
                    Pusat Cetak
                </a>

                {{-- ANALISA STRATEGIS --}}
                <div
                    class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mt-3 mb-1 px-3">
                    Strategis</div>

                <a href="{{ route('pimpinan.profit-analysis') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('pimpinan.profit-analysis') ? 'text-amber-600 bg-amber-50/50 dark:text-amber-300 dark:bg-white/5' : 'text-slate-500 hover:text-amber-600 dark:hover:text-amber-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('pimpinan.profit-analysis') ? 'bg-amber-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Laba Rugi
                </a>

                <a href="{{ route('laporan.kinerja-sales') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('laporan.kinerja-sales') ? 'text-amber-600 bg-amber-50/50 dark:text-amber-300 dark:bg-white/5' : 'text-slate-500 hover:text-amber-600 dark:hover:text-amber-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.kinerja-sales') ? 'bg-amber-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Kinerja Sales
                </a>

                <a href="{{ route('pimpinan.stock-analysis') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('pimpinan.stock-analysis') ? 'text-amber-600 bg-amber-50/50 dark:text-amber-300 dark:bg-white/5' : 'text-slate-500 hover:text-amber-600 dark:hover:text-amber-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('pimpinan.stock-analysis') ? 'bg-amber-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Analisa Stok
                </a>

                {{-- REKAPITULASI --}}
                <div
                    class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mt-3 mb-1 px-3">
                    Rekapitulasi</div>

                <a href="{{ route('laporan.rekap-penjualan') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('laporan.rekap-penjualan') ? 'text-blue-600 bg-blue-50/50 dark:text-blue-300 dark:bg-white/5' : 'text-slate-500 hover:text-blue-600 dark:hover:text-blue-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.rekap-penjualan') ? 'bg-blue-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Rekap Jual
                </a>

                <a href="{{ route('laporan.rekap-ar') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('laporan.rekap-ar') ? 'text-blue-600 bg-blue-50/50 dark:text-blue-300 dark:bg-white/5' : 'text-slate-500 hover:text-blue-600 dark:hover:text-blue-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.rekap-ar') ? 'bg-blue-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Rekap Piutang
                </a>

                <a href="{{ route('laporan.rekap-retur') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('laporan.rekap-retur') ? 'text-blue-600 bg-blue-50/50 dark:text-blue-300 dark:bg-white/5' : 'text-slate-500 hover:text-blue-600 dark:hover:text-blue-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.rekap-retur') ? 'bg-blue-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Rekap Retur
                </a>

                <a href="{{ route('laporan.rekap-collection') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-colors
                    {{ request()->routeIs('laporan.rekap-collection') ? 'text-blue-600 bg-blue-50/50 dark:text-blue-300 dark:bg-white/5' : 'text-slate-500 hover:text-blue-600 dark:hover:text-blue-300' }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.rekap-collection') ? 'bg-blue-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                    Rekap Lunas
                </a>
            </div>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 top-1 bg-slate-800 text-white text-[10px] px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-50 whitespace-nowrap shadow-xl font-bold tracking-wider uppercase transform translate-x-2 group-hover:translate-x-0">
                Analytics
            </div>
        </div>
        @endif
    </div>

    {{-- FOOTER TOGGLE --}}
    <div
        class="p-4 border-t transition-colors bg-slate-50 border-slate-200 dark:bg-[#050505] dark:border-white/5 flex-none">
        <button @click="toggleSidebar()" class="flex w-full items-center justify-center py-3 rounded-xl transition-all shadow-sm border group
            bg-white border-slate-200 text-slate-500 hover:border-blue-300 hover:text-blue-600
            dark:bg-white/5 dark:border-white/5 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-white">
            <i class="fas fa-chevron-left transition-transform duration-500 group-hover:-translate-x-1"
                :class="!isSidebarExpanded ? 'rotate-180' : ''"></i>
            <span x-show="isSidebarExpanded"
                class="ml-3 text-[10px] font-black uppercase tracking-[0.2em]">Sembunyikan</span>
        </button>
    </div>
</aside>