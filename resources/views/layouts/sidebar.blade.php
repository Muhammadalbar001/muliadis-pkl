<aside class="fixed inset-y-0 left-0 z-50 transition-all duration-500 flex flex-col shadow-2xl border-r 
    bg-white border-slate-100 text-slate-600 
    dark:bg-gradient-to-b dark:from-slate-950 dark:to-[#0a0a0a] dark:border-white/5 dark:text-neutral-300" :class="{ 
        'translate-x-0': sidebarOpen, 
        '-translate-x-full': !sidebarOpen, 
        'lg:translate-x-0': true,
        'w-64': isSidebarExpanded,
        'w-20': !isSidebarExpanded
    }" x-cloak>

    {{-- HEADER SIDEBAR --}}
    <div class="h-24 flex-none flex items-center px-6 border-b transition-all duration-300
        border-slate-100 bg-gradient-to-b from-slate-50 to-white/50 shadow-sm
        dark:border-white/10 dark:bg-gradient-to-br dark:from-slate-900 dark:via-slate-950 dark:to-[#0a0a0a]"
        :class="isSidebarExpanded ? 'justify-between' : 'justify-center'">

        <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
            {{-- Logo Icon --}}
            <div class="w-11 h-11 md:w-12 md:h-12 rounded-2xl flex-none flex items-center justify-center text-white shadow-lg 
                bg-gradient-to-br from-blue-600 via-blue-500 to-cyan-500 ring-2 ring-white/10 dark:ring-white/20 group-hover:shadow-blue-500/40 transition-all duration-300">
                <i class="fas fa-truck-fast text-xl font-bold"></i>
            </div>

            {{-- Logo Text --}}
            <div x-show="isSidebarExpanded" x-transition.opacity.duration.400ms class="flex-1">
                <h1 class="font-black text-xs tracking-tighter uppercase leading-tight 
                    text-slate-900 dark:text-white">
                    PT MULIA <span class="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent dark:from-blue-400 dark:to-cyan-400">ANUGERAH</span>
                </h1>
                <p class="text-[7px] font-bold tracking-[0.3em] uppercase mt-1 flex items-center gap-1.5
                    text-slate-500 dark:text-slate-400">
                    <span class="w-1.5 h-1.5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full animate-pulse"></span> Distribusindo
                </p>
            </div>
        </div>

        {{-- Mobile Close Button --}}
        <button @click="sidebarOpen = false"
            class="lg:hidden text-slate-400 hover:text-red-500 dark:hover:text-red-400 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 transition-all duration-300">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    {{-- MENU ITEMS --}}
    <div class="flex-1 overflow-y-auto py-6 space-y-1 custom-scrollbar overflow-x-hidden"
        :class="isSidebarExpanded ? 'px-4' : 'px-2.5'">

        {{-- DASHBOARD --}}
        <a href="{{ route('dashboard') }}"
            class="flex items-center py-3 rounded-xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest mb-6
            {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard')
                ? 'bg-gradient-to-r from-blue-50 to-blue-100/50 text-blue-700 dark:from-blue-600/20 dark:to-indigo-600/20 dark:text-blue-300 shadow-sm ring-1 ring-blue-200 dark:ring-blue-500/30' 
                : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600 dark:text-slate-400 dark:hover:bg-blue-500/10 dark:hover:text-blue-300' }}"
            :class="isSidebarExpanded ? 'px-5' : 'justify-center'">

            @if(request()->routeIs('*.dashboard') || request()->routeIs('dashboard'))
            <div class="absolute left-0 h-7 w-1.5 bg-gradient-to-b from-blue-600 to-blue-500 rounded-r-xl shadow-lg shadow-blue-500/20 hidden lg:block" x-show="isSidebarExpanded">
            </div>
            @endif

            <i
                class="fas fa-chart-pie w-5 text-center text-base transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-300' : 'group-hover:text-blue-500 dark:group-hover:text-blue-400' }}"></i>
            <span x-show="isSidebarExpanded" class="ml-4 font-bold">Dashboard</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-gradient-to-b from-slate-800 to-slate-900 text-white text-[10px] px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-50 whitespace-nowrap shadow-xl font-bold tracking-wider uppercase transform translate-x-2 group-hover:translate-x-0">
                Dashboard
            </div>
        </a>

        {{-- ================================================== --}}
        {{-- MENU (SPK & DATA MINING)                           --}}
        {{-- ================================================== --}}
        @if(in_array(auth()->user()->role, ['super_admin', 'superadmin', 'pimpinan']))
        <div x-data="{ open: {{ request()->routeIs('keputusan.*') ? 'true' : 'false' }} }"
            class="w-full relative group">
            <button @click="isSidebarExpanded ? open = !open : isSidebarExpanded = true"
                class="flex items-center w-full py-3 rounded-xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
                {{ request()->routeIs('keputusan.*') 
                    ? 'text-fuchsia-600 bg-gradient-to-r from-fuchsia-50 to-pink-50/50 dark:from-fuchsia-600/20 dark:to-pink-600/20 dark:text-fuchsia-300 border border-fuchsia-200 dark:border-fuchsia-500/30 shadow-sm ring-1 ring-fuchsia-100 dark:ring-fuchsia-500/20' 
                    : 'text-slate-600 hover:bg-fuchsia-50 hover:text-fuchsia-600 dark:text-slate-400 dark:hover:bg-fuchsia-500/10 dark:hover:text-fuchsia-400 border border-transparent' }}"
                :class="isSidebarExpanded ? 'px-5 justify-between' : 'justify-center'">

                <div class="flex items-center">
                    <span class="w-5 flex justify-center">
                        <i
                            class="fas fa-brain text-base transition-all duration-300 group-hover:scale-110 {{ request()->routeIs('keputusan.*') ? 'text-fuchsia-600 dark:text-fuchsia-400 animate-pulse' : 'group-hover:text-fuchsia-500 dark:group-hover:text-fuchsia-400' }}"></i>
                    </span>
                    <span x-show="isSidebarExpanded" class="ml-4 font-bold">Analisa Cerdas</span>
                </div>
                <i x-show="isSidebarExpanded"
                    class="fas fa-chevron-right text-[9px] transition-all duration-300 opacity-70 group-hover:opacity-100"
                    :class="{'rotate-90': open}"></i>
            </button>

            <div x-show="open && isSidebarExpanded" x-cloak x-transition.origin.top
                class="mt-2 space-y-1 ml-4 border-l-[2px] border-fuchsia-300 dark:border-fuchsia-500/30 pl-4">

                <a href="{{ route('keputusan.spk-sales') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('keputusan.spk-sales') ? 'text-fuchsia-700 bg-fuchsia-100/60 dark:text-fuchsia-300 dark:bg-fuchsia-500/20 shadow-sm' : 'text-slate-600 hover:text-fuchsia-600 dark:text-slate-400 dark:hover:text-fuchsia-300 hover:bg-fuchsia-50/50 dark:hover:bg-fuchsia-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('keputusan.spk-sales') ? 'bg-fuchsia-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    SPK Sales (SAW)
                </a>

                <a href="{{ route('keputusan.rfm-pelanggan') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('keputusan.rfm-pelanggan') ? 'text-fuchsia-700 bg-fuchsia-100/60 dark:text-fuchsia-300 dark:bg-fuchsia-500/20 shadow-sm' : 'text-slate-600 hover:text-fuchsia-600 dark:text-slate-400 dark:hover:text-fuchsia-300 hover:bg-fuchsia-50/50 dark:hover:bg-fuchsia-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('keputusan.rfm-pelanggan') ? 'bg-fuchsia-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Segmentasi (RFM)
                </a>
            </div>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-gradient-to-b from-slate-800 to-slate-900 text-white text-[10px] px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-50 whitespace-nowrap shadow-xl font-bold tracking-wider uppercase transform translate-x-2 group-hover:translate-x-0">
                Analisa Cerdas
            </div>
        </div>
        @endif

        {{-- ================================================== --}}
        {{-- MASTER DATA (Superadmin & Supervisor)              --}}
        {{-- ================================================== --}}
        @if(in_array(auth()->user()->role, ['super_admin', 'superadmin', 'supervisor']))
        <div x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }" class="w-full relative group">
            <button @click="isSidebarExpanded ? open = !open : isSidebarExpanded = true"
                class="flex items-center w-full py-3 rounded-xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
                {{ request()->routeIs('master.*') 
                    ? 'text-cyan-600 bg-gradient-to-r from-cyan-50 to-blue-50/50 dark:from-cyan-600/20 dark:to-blue-600/20 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-500/30 shadow-sm ring-1 ring-cyan-100 dark:ring-cyan-500/20' 
                    : 'text-slate-600 hover:bg-cyan-50 hover:text-cyan-600 dark:text-slate-400 dark:hover:bg-cyan-500/10 dark:hover:text-cyan-300 border border-transparent' }}"
                :class="isSidebarExpanded ? 'px-5 justify-between' : 'justify-center'">

                <div class="flex items-center">
                    <span class="w-5 flex justify-center">
                        <i
                            class="fas fa-database text-base transition-all duration-300 group-hover:scale-110 {{ request()->routeIs('master.*') ? 'text-cyan-600 dark:text-cyan-300' : 'group-hover:text-cyan-500 dark:group-hover:text-cyan-400' }}"></i>
                    </span>
                    <span x-show="isSidebarExpanded" class="ml-4 font-bold">Master Data</span>
                </div>
                <i x-show="isSidebarExpanded"
                    class="fas fa-chevron-right text-[9px] transition-all duration-300 opacity-70 group-hover:opacity-100"
                    :class="{'rotate-90': open}"></i>
            </button>

            {{-- Submenu Master --}}
            <div x-show="open && isSidebarExpanded" x-cloak x-transition.origin.top
                class="mt-2 space-y-1 ml-4 border-l-[2px] border-cyan-300 dark:border-cyan-500/30 pl-4">

                <a href="{{ route('master.produk') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('master.produk') ? 'text-cyan-700 bg-cyan-100/60 dark:text-cyan-300 dark:bg-cyan-500/20 shadow-sm' : 'text-slate-600 hover:text-cyan-600 dark:text-slate-400 dark:hover:text-cyan-300 hover:bg-cyan-50/50 dark:hover:bg-cyan-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('master.produk') ? 'bg-cyan-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Produk
                </a>

                <a href="{{ route('master.supplier') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('master.supplier') ? 'text-cyan-700 bg-cyan-100/60 dark:text-cyan-300 dark:bg-cyan-500/20 shadow-sm' : 'text-slate-600 hover:text-cyan-600 dark:text-slate-400 dark:hover:text-cyan-300 hover:bg-cyan-50/50 dark:hover:bg-cyan-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('master.supplier') ? 'bg-cyan-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Supplier
                </a>

                <a href="{{ route('master.sales') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('master.sales') ? 'text-cyan-700 bg-cyan-100/60 dark:text-cyan-300 dark:bg-cyan-500/20 shadow-sm' : 'text-slate-600 hover:text-cyan-600 dark:text-slate-400 dark:hover:text-cyan-300 hover:bg-cyan-50/50 dark:hover:bg-cyan-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('master.sales') ? 'bg-cyan-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Salesman
                </a>

                {{-- PERUBAHAN: AKSES MENU PENGGUNA DIIZINKAN UNTUK SUPERVISOR --}}
                @if(in_array(auth()->user()->role, ['super_admin', 'superadmin', 'supervisor']))
                <a href="{{ route('master.user') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('master.user') ? 'text-rose-700 bg-rose-100/60 dark:text-rose-300 dark:bg-rose-500/20 shadow-sm' : 'text-slate-600 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-300 hover:bg-rose-50/50 dark:hover:bg-rose-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('master.user') ? 'bg-rose-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Pengguna
                </a>
                @endif
            </div>

            {{-- Tooltip Collapsed --}}
            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-gradient-to-b from-slate-800 to-slate-900 text-white text-[10px] px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-50 whitespace-nowrap shadow-xl font-bold tracking-wider uppercase transform translate-x-2 group-hover:translate-x-0">
                Master Data
            </div>
        </div>
        @endif

        {{-- ================================================== --}}
        {{-- OPERASIONAL (Superadmin & Admin)                   --}}
        {{-- ================================================== --}}
        @if(in_array(auth()->user()->role, ['super_admin', 'superadmin', 'admin']))
        <div x-data="{ open: {{ request()->routeIs('transaksi.*') ? 'true' : 'false' }} }"
            class="w-full relative group">
            <button @click="isSidebarExpanded ? open = !open : isSidebarExpanded = true"
                class="flex items-center w-full py-3 rounded-xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
                {{ request()->routeIs('transaksi.*') 
                    ? 'text-emerald-600 bg-gradient-to-r from-emerald-50 to-green-50/50 dark:from-emerald-600/20 dark:to-green-600/20 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 shadow-sm ring-1 ring-emerald-100 dark:ring-emerald-500/20' 
                    : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 dark:text-slate-400 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300 border border-transparent' }}"
                :class="isSidebarExpanded ? 'px-5 justify-between' : 'justify-center'">

                <div class="flex items-center">
                    <span class="w-5 flex justify-center">
                        <i
                            class="fas fa-shopping-cart text-base transition-all duration-300 group-hover:scale-110 {{ request()->routeIs('transaksi.*') ? 'text-emerald-600 dark:text-emerald-300' : 'group-hover:text-emerald-500 dark:group-hover:text-emerald-400' }}"></i>
                    </span>
                    <span x-show="isSidebarExpanded" class="ml-4 font-bold">Operasional</span>
                </div>
                <i x-show="isSidebarExpanded"
                    class="fas fa-chevron-right text-[9px] transition-all duration-300 opacity-70 group-hover:opacity-100"
                    :class="{'rotate-90': open}"></i>
            </button>

            <div x-show="open && isSidebarExpanded" x-cloak x-transition.origin.top
                class="mt-2 space-y-1 ml-4 border-l-[2px] border-emerald-300 dark:border-emerald-500/30 pl-4">

                <a href="{{ route('transaksi.penjualan') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('transaksi.penjualan') ? 'text-emerald-700 bg-emerald-100/60 dark:text-emerald-300 dark:bg-emerald-500/20 shadow-sm' : 'text-slate-600 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-300 hover:bg-emerald-50/50 dark:hover:bg-emerald-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('transaksi.penjualan') ? 'bg-emerald-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Penjualan
                </a>

                <a href="{{ route('transaksi.retur') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('transaksi.retur') ? 'text-emerald-700 bg-emerald-100/60 dark:text-emerald-300 dark:bg-emerald-500/20 shadow-sm' : 'text-slate-600 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-300 hover:bg-emerald-50/50 dark:hover:bg-emerald-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('transaksi.retur') ? 'bg-emerald-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Retur Barang
                </a>

                <a href="{{ route('transaksi.ar') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('transaksi.ar') ? 'text-emerald-700 bg-emerald-100/60 dark:text-emerald-300 dark:bg-emerald-500/20 shadow-sm' : 'text-slate-600 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-300 hover:bg-emerald-50/50 dark:hover:bg-emerald-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('transaksi.ar') ? 'bg-emerald-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Piutang (AR)
                </a>

                <a href="{{ route('transaksi.collection') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('transaksi.collection') ? 'text-emerald-700 bg-emerald-100/60 dark:text-emerald-300 dark:bg-emerald-500/20 shadow-sm' : 'text-slate-600 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-300 hover:bg-emerald-50/50 dark:hover:bg-emerald-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('transaksi.collection') ? 'bg-emerald-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Pelunasan
                </a>
            </div>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-gradient-to-b from-slate-800 to-slate-900 text-white text-[10px] px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-50 whitespace-nowrap shadow-xl font-bold tracking-wider uppercase transform translate-x-2 group-hover:translate-x-0">
                Operasional
            </div>
        </div>
        @endif

        {{-- ================================================== --}}
        {{-- ANALISA & LAPORAN (Superadmin & Pimpinan)          --}}
        {{-- ================================================== --}}
        @if(in_array(auth()->user()->role, ['super_admin', 'superadmin', 'pimpinan']))
        <div x-data="{ open: {{ (request()->routeIs('pimpinan.*') || request()->routeIs('laporan.*') || request()->routeIs('pusat-cetak')) ? 'true' : 'false' }} }"
            class="w-full relative group">
            <button @click="isSidebarExpanded ? open = !open : isSidebarExpanded = true"
                class="flex items-center w-full py-3 rounded-xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
                {{ (request()->routeIs('pimpinan.*') || request()->routeIs('laporan.*') || request()->routeIs('pusat-cetak')) 
                    ? 'text-amber-600 bg-gradient-to-r from-amber-50 to-orange-50/50 dark:from-amber-600/20 dark:to-orange-600/20 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 shadow-sm ring-1 ring-amber-100 dark:ring-amber-500/20' 
                    : 'text-slate-600 hover:bg-amber-50 hover:text-amber-600 dark:text-slate-400 dark:hover:bg-amber-500/10 dark:hover:text-amber-300 border border-transparent' }}"
                :class="isSidebarExpanded ? 'px-5 justify-between' : 'justify-center'">

                <div class="flex items-center">
                    <span class="w-5 flex justify-center">
                        <i
                            class="fas fa-file-chart-line text-base transition-all duration-300 group-hover:scale-110 {{ (request()->routeIs('pimpinan.*') || request()->routeIs('laporan.*') || request()->routeIs('pusat-cetak')) ? 'text-amber-600 dark:text-amber-300' : 'group-hover:text-amber-500 dark:group-hover:text-amber-400' }}"></i>
                    </span>
                    <span x-show="isSidebarExpanded" class="ml-4 font-bold">Laporan & Analisa</span>
                </div>
                <i x-show="isSidebarExpanded"
                    class="fas fa-chevron-right text-[9px] transition-all duration-300 opacity-70 group-hover:opacity-100"
                    :class="{'rotate-90': open}"></i>
            </button>

            <div x-show="open && isSidebarExpanded" x-cloak x-transition.origin.top
                class="mt-2 space-y-1 ml-4 border-l-[2px] border-amber-300 dark:border-amber-500/30 pl-4">

                {{-- PUSAT CETAK --}}
                <a href="{{ route('pusat-cetak') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group mb-3
                    {{ request()->routeIs('pusat-cetak') ? 'text-white bg-gradient-to-r from-amber-500 to-orange-500 shadow-lg shadow-amber-500/20' : 'text-slate-600 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-300 hover:bg-amber-50/50 dark:hover:bg-amber-500/10' }}">
                    <i class="fas fa-print w-4 text-center"></i>
                    Pusat Cetak
                </a>

                {{-- ANALISA STRATEGIS --}}
                <div
                    class="text-[8.5px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-wider mt-3 mb-2 px-3 pl-0">
                    Strategis
                </div>

                <a href="{{ route('pimpinan.profit-analysis') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('pimpinan.profit-analysis') ? 'text-amber-700 bg-amber-100/60 dark:text-amber-300 dark:bg-amber-500/20 shadow-sm' : 'text-slate-600 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-300 hover:bg-amber-50/50 dark:hover:bg-amber-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('pimpinan.profit-analysis') ? 'bg-amber-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Laba Rugi
                </a>

                <a href="{{ route('laporan.kinerja-sales') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('laporan.kinerja-sales') ? 'text-amber-700 bg-amber-100/60 dark:text-amber-300 dark:bg-amber-500/20 shadow-sm' : 'text-slate-600 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-300 hover:bg-amber-50/50 dark:hover:bg-amber-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('laporan.kinerja-sales') ? 'bg-amber-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Kinerja Sales
                </a>

                <a href="{{ route('pimpinan.stock-analysis') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('pimpinan.stock-analysis') ? 'text-amber-700 bg-amber-100/60 dark:text-amber-300 dark:bg-amber-500/20 shadow-sm' : 'text-slate-600 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-300 hover:bg-amber-50/50 dark:hover:bg-amber-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('pimpinan.stock-analysis') ? 'bg-amber-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Analisa Stok
                </a>

                {{-- REKAPITULASI --}}
                <div
                    class="text-[8.5px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-wider mt-4 mb-2 px-3 pl-0">
                    Rekapitulasi
                </div>

                <a href="{{ route('laporan.rekap-penjualan') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('laporan.rekap-penjualan') ? 'text-blue-700 bg-blue-100/60 dark:text-blue-300 dark:bg-blue-500/20 shadow-sm' : 'text-slate-600 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-300 hover:bg-blue-50/50 dark:hover:bg-blue-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('laporan.rekap-penjualan') ? 'bg-blue-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Rekap Jual
                </a>

                <a href="{{ route('laporan.rekap-ar') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('laporan.rekap-ar') ? 'text-blue-700 bg-blue-100/60 dark:text-blue-300 dark:bg-blue-500/20 shadow-sm' : 'text-slate-600 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-300 hover:bg-blue-50/50 dark:hover:bg-blue-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('laporan.rekap-ar') ? 'bg-blue-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Rekap Piutang
                </a>

                <a href="{{ route('laporan.rekap-retur') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('laporan.rekap-retur') ? 'text-blue-700 bg-blue-100/60 dark:text-blue-300 dark:bg-blue-500/20 shadow-sm' : 'text-slate-600 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-300 hover:bg-blue-50/50 dark:hover:bg-blue-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('laporan.rekap-retur') ? 'bg-blue-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Rekap Retur
                </a>

                <a href="{{ route('laporan.rekap-collection') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded-lg text-[10px] font-bold uppercase transition-all duration-300 group
                    {{ request()->routeIs('laporan.rekap-collection') ? 'text-blue-700 bg-blue-100/60 dark:text-blue-300 dark:bg-blue-500/20 shadow-sm' : 'text-slate-600 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-300 hover:bg-blue-50/50 dark:hover:bg-blue-500/10' }}">
                    <span
                        class="w-2 h-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->routeIs('laporan.rekap-collection') ? 'bg-blue-600' : 'bg-slate-300 dark:bg-slate-500' }}"></span>
                    Rekap Lunas
                </a>
            </div>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-gradient-to-b from-slate-800 to-slate-900 text-white text-[10px] px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-50 whitespace-nowrap shadow-xl font-bold tracking-wider uppercase transform translate-x-2 group-hover:translate-x-0">
                Laporan
            </div>
        </div>
        @endif
    </div>

    {{-- FOOTER TOGGLE --}}
    <div
        class="p-4 border-t transition-all duration-300 bg-slate-50 border-slate-100 dark:bg-slate-950/50 dark:border-white/5 flex-none">
        <button @click="toggleSidebar()" class="flex w-full items-center justify-center py-2.5 rounded-xl transition-all shadow-sm border group
            bg-white border-slate-200 text-slate-600 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 hover:shadow-md
            dark:bg-slate-900 dark:border-white/10 dark:text-slate-400 dark:hover:bg-blue-600/10 dark:hover:border-blue-500/30 dark:hover:text-blue-400">
            <i class="fas fa-chevron-left transition-all duration-500 group-hover:-translate-x-1"
                :class="!isSidebarExpanded ? 'rotate-180' : ''"></i>
            <span x-show="isSidebarExpanded"
                class="ml-3 text-[10px] font-black uppercase tracking-[0.15em]">Sembunyikan</span>
        </button>
    </div>
</aside>