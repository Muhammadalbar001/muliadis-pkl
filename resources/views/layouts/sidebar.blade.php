<aside
    class="fixed inset-y-0 left-0 z-50 bg-[#0a0a0a] border-r border-white/5 text-neutral-300 transition-all duration-500 flex flex-col shadow-2xl"
    :class="{ 
        'translate-x-0': sidebarOpen, 
        '-translate-x-full': !sidebarOpen, 
        'lg:translate-x-0': true,
        'w-64': isSidebarExpanded,
        'w-20': !isSidebarExpanded
    }" x-cloak>

    <div class="h-24 flex-none flex items-center px-6 border-b border-white/5 bg-gradient-to-b from-[#121212] to-[#0a0a0a]"
        :class="isSidebarExpanded ? 'justify-between' : 'justify-center'">
        <div class="flex items-center gap-4 overflow-hidden whitespace-nowrap">
            <div
                class="w-12 h-12 rounded-2xl flex-none flex items-center justify-center text-white shadow-lg shadow-blue-600/40 bg-gradient-to-br from-blue-500 to-indigo-700 ring-2 ring-white/10">
                <i class="fas fa-truck-fast text-lg"></i>
            </div>
            <div x-show="isSidebarExpanded" x-transition.opacity.duration.400ms>
                <h1 class="font-black text-xs tracking-tighter text-white uppercase leading-tight italic">PT MULIA <span
                        class="text-blue-500">ANUGERAH</span></h1>
                <p class="text-[7px] font-black text-slate-500 tracking-[0.4em] uppercase mt-1 flex items-center gap-1">
                    <span class="w-1 h-1 bg-blue-500 rounded-full animate-pulse"></span> Distribusindo
                </p>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden text-neutral-500 hover:text-white p-1">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto py-8 space-y-4 custom-scrollbar overflow-x-hidden"
        :class="isSidebarExpanded ? 'px-4' : 'px-2'">

        <a href="{{ route('dashboard') }}"
            class="flex items-center py-3.5 rounded-2xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
            {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-xl shadow-blue-600/20 ring-1 ring-white/20' : 'text-slate-500 hover:bg-white/5 hover:text-white' }}"
            :class="isSidebarExpanded ? 'px-5' : 'justify-center'">
            <i
                class="fas fa-chart-pie w-5 text-center text-base {{ request()->routeIs('dashboard') ? 'text-white' : 'group-hover:text-blue-400' }}"></i>
            <span x-show="isSidebarExpanded" class="ml-4">Dashboard</span>
            <div x-show="!isSidebarExpanded"
                class="absolute left-16 bg-blue-600 text-white text-[10px] px-3 py-2 rounded-xl opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-50 whitespace-nowrap shadow-2xl font-black tracking-widest uppercase">
                Dashboard</div>
        </a>

        @if(in_array(auth()->user()->role, ['super_admin', 'pimpinan', 'supervisor']))
        <div x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }" class="w-full relative group">
            <button @click="isSidebarExpanded ? open = !open : isSidebarExpanded = true"
                class="flex items-center w-full py-3.5 rounded-2xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
                {{ request()->routeIs('master.*') ? 'text-cyan-400 bg-cyan-400/5 ring-1 ring-cyan-400/20' : 'text-slate-500 hover:bg-white/5 hover:text-white' }}"
                :class="isSidebarExpanded ? 'px-5 justify-between' : 'justify-center'">
                <div class="flex items-center">
                    <span class="w-5 flex justify-center"><i
                            class="fas fa-database text-base {{ request()->routeIs('master.*') ? 'text-cyan-400' : 'group-hover:text-cyan-400' }}"></i></span>
                    <span x-show="isSidebarExpanded" class="ml-4">Master Data</span>
                </div>
                <i x-show="isSidebarExpanded" class="fas fa-chevron-right text-[8px] transition-transform duration-300"
                    :class="{'rotate-90 text-cyan-400': open}"></i>
            </button>
            <div x-show="open && isSidebarExpanded" x-cloak x-transition
                class="mt-2 space-y-1 ml-6 border-l-2 border-cyan-400/20 pl-4">
                <a href="{{ route('master.produk') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('master.produk') ? 'text-cyan-400' : 'text-slate-500 hover:text-cyan-200' }}">📦
                    Produk</a>
                <a href="{{ route('master.supplier') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('master.supplier') ? 'text-cyan-400' : 'text-slate-500 hover:text-cyan-200' }}">🤝
                    Supplier</a>
                <a href="{{ route('master.sales') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('master.sales') ? 'text-cyan-400' : 'text-slate-500 hover:text-cyan-200' }}">💼
                    Salesman</a>
                @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('master.user') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('master.user') ? 'text-rose-400' : 'text-slate-500 hover:text-rose-300' }}">👤
                    Pengguna</a>
                @endif
            </div>
            <div x-show="!isSidebarExpanded"
                class="absolute left-16 top-1 bg-cyan-500 text-white text-[10px] px-3 py-2 rounded-xl opacity-0 group-hover:opacity-100 transition-all z-50 whitespace-nowrap font-black uppercase tracking-widest">
                Master Data</div>
        </div>
        @endif

        <div x-data="{ open: {{ request()->routeIs('transaksi.*') ? 'true' : 'false' }} }"
            class="w-full relative group">
            <button @click="isSidebarExpanded ? open = !open : isSidebarExpanded = true"
                class="flex items-center w-full py-3.5 rounded-2xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
                {{ request()->routeIs('transaksi.*') ? 'text-emerald-400 bg-emerald-400/5 ring-1 ring-emerald-400/20' : 'text-slate-500 hover:bg-white/5 hover:text-white' }}"
                :class="isSidebarExpanded ? 'px-5 justify-between' : 'justify-center'">
                <div class="flex items-center">
                    <span class="w-5 flex justify-center"><i
                            class="fas fa-shopping-cart text-base {{ request()->routeIs('transaksi.*') ? 'text-emerald-400' : 'group-hover:text-emerald-400' }}"></i></span>
                    <span x-show="isSidebarExpanded" class="ml-4">Operasional</span>
                </div>
                <i x-show="isSidebarExpanded" class="fas fa-chevron-right text-[8px] transition-transform duration-300"
                    :class="{'rotate-90 text-emerald-400': open}"></i>
            </button>
            <div x-show="open && isSidebarExpanded" x-cloak x-transition
                class="mt-2 space-y-1 ml-6 border-l-2 border-emerald-400/20 pl-4">
                <a href="{{ route('transaksi.penjualan') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('transaksi.penjualan') ? 'text-emerald-400' : 'text-slate-500 hover:text-emerald-200' }}">🛒
                    Penjualan</a>
                <a href="{{ route('transaksi.retur') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('transaksi.retur') ? 'text-emerald-400' : 'text-emerald-200' }}">🔄
                    Retur Barang</a>
                <a href="{{ route('transaksi.ar') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('transaksi.ar') ? 'text-emerald-400' : 'text-emerald-200' }}">📑
                    Piutang (AR)</a>
                <a href="{{ route('transaksi.collection') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('transaksi.collection') ? 'text-emerald-400' : 'text-emerald-200' }}">💰
                    Pelunasan</a>
            </div>
            <div x-show="!isSidebarExpanded"
                class="absolute left-16 top-1 bg-emerald-500 text-white text-[10px] px-3 py-2 rounded-xl opacity-0 group-hover:opacity-100 transition-all z-50 whitespace-nowrap font-black uppercase tracking-widest">
                Transaksi</div>
        </div>

        @if(in_array(auth()->user()->role, ['super_admin', 'pimpinan', 'supervisor']))
        <div x-data="{ open: {{ (request()->routeIs('pimpinan.*') || request()->routeIs('laporan.*')) ? 'true' : 'false' }} }"
            class="w-full relative group">
            <button @click="isSidebarExpanded ? open = !open : isSidebarExpanded = true"
                class="flex items-center w-full py-3.5 rounded-2xl transition-all duration-300 group text-[11px] font-black relative uppercase tracking-widest
                {{ (request()->routeIs('pimpinan.*') || request()->routeIs('laporan.*')) ? 'text-amber-400 bg-amber-400/5 ring-1 ring-amber-400/20' : 'text-slate-500 hover:bg-white/5 hover:text-white' }}"
                :class="isSidebarExpanded ? 'px-5 justify-between' : 'justify-center'">
                <div class="flex items-center">
                    <span class="w-5 flex justify-center"><i
                            class="fas fa-file-contract text-base {{ (request()->routeIs('pimpinan.*') || request()->routeIs('laporan.*')) ? 'text-amber-400' : 'group-hover:text-amber-400' }}"></i></span>
                    <span x-show="isSidebarExpanded" class="ml-4">ANALISA</span>
                </div>
                <i x-show="isSidebarExpanded" class="fas fa-chevron-right text-[8px] transition-transform duration-300"
                    :class="{'rotate-90 text-amber-400': open}"></i>
            </button>
            <div x-show="open && isSidebarExpanded" x-cloak x-transition
                class="mt-2 space-y-1 ml-6 border-l-2 border-amber-400/20 pl-4">
                <a href="{{ route('pimpinan.profit-analysis') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('pimpinan.profit-analysis') ? 'text-amber-400' : 'text-slate-500 hover:text-amber-200' }}">📈
                    Laba Rugi</a>
                <a href="{{ route('laporan.kinerja-sales') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('laporan.kinerja-sales') ? 'text-amber-400' : 'text-slate-500 hover:text-amber-200' }}">🏆
                    Kinerja Sales</a>
                <a href="{{ route('pimpinan.stock-analysis') }}"
                    class="block py-2 text-[10px] font-black uppercase {{ request()->routeIs('pimpinan.stock-analysis') ? 'text-amber-400' : 'text-slate-500 hover:text-amber-200' }}">📦
                    Analisa Stok</a>

                <div class="h-px bg-white/5 my-3 mr-4"></div>

                <a href="{{ route('laporan.rekap-penjualan') }}"
                    class="block py-2 text-[10px] font-bold uppercase {{ request()->routeIs('laporan.rekap-penjualan') ? 'text-blue-400' : 'text-slate-500 hover:text-white' }}">📋
                    Rekap Jual</a>
                <a href="{{ route('laporan.rekap-ar') }}"
                    class="block py-2 text-[10px] font-bold uppercase {{ request()->routeIs('laporan.rekap-ar') ? 'text-blue-400' : 'text-slate-500 hover:text-white' }}">📑
                    Rekap Piutang</a>
                <a href="{{ route('laporan.rekap-retur') }}"
                    class="block py-2 text-[10px] font-bold uppercase {{ request()->routeIs('laporan.rekap-retur') ? 'text-blue-400' : 'text-slate-500 hover:text-white' }}">🔄
                    Rekap Retur</a>
                <a href="{{ route('laporan.rekap-collection') }}"
                    class="block py-2 text-[10px] font-bold uppercase {{ request()->routeIs('laporan.rekap-collection') ? 'text-blue-400' : 'text-slate-500 hover:text-white' }}">💰
                    Rekap Lunas</a>
            </div>
            <div x-show="!isSidebarExpanded"
                class="absolute left-16 top-1 bg-amber-500 text-white text-[10px] px-3 py-2 rounded-xl opacity-0 group-hover:opacity-100 transition-all z-50 whitespace-nowrap font-black uppercase tracking-widest">
                Analytics</div>
        </div>
        @endif
    </div>

    <div class="p-6 border-t border-white/5 bg-[#050505] flex-none">
        <button @click="toggleSidebar()"
            class="flex w-full items-center justify-center py-3 rounded-2xl bg-white/5 text-slate-500 hover:text-blue-400 hover:bg-white/10 transition-all border border-white/5 shadow-inner group">
            <i class="fas fa-chevron-left transition-transform duration-500 group-hover:-translate-x-1"
                :class="!isSidebarExpanded ? 'rotate-180' : ''"></i>
            <span x-show="isSidebarExpanded"
                class="ml-3 text-[10px] font-black uppercase tracking-[0.2em]">Sembunyikan</span>
        </button>
    </div>
</aside>