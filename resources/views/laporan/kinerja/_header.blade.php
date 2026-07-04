{{-- CSS Tambahan & Scrollbar --}}
<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(203, 213, 225, 0.5);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(148, 163, 184, 0.8);
}
</style>

{{-- HEADER & NAVIGASI PENCARIAN --}}
<div
    class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6 dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">
    <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

        {{-- Judul Halaman Dinamis --}}
        <div class="flex items-center gap-4 w-full xl:w-auto">
            <div
                class="p-3 rounded-2xl shadow-lg {{ $iconBg ?? 'bg-indigo-600' }} text-white flex items-center justify-center">
                <i class="fas {{ $icon ?? 'fa-file-alt' }} text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                    {{ $title ?? 'Kinerja Sales' }}
                </h1>
                <p
                    class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-60 mt-1 dark:text-slate-400 text-slate-500">
                    {{ $subTitle ?? 'Monitoring Data' }}
                </p>
            </div>
        </div>

        {{-- FORM FILTER PENCARIAN --}}
        <form method="GET" action="{{ url()->current() }}"
            class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">

            <div class="relative w-full sm:w-48 group">
                <i
                    class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors text-xs"></i>
                <input name="search" value="{{ request('search') }}" type="text" onchange="this.form.submit()"
                    class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-blue-500/20 transition-all h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner"
                    placeholder="Cari Sales...">
            </div>

            @if(request()->routeIs('laporan.kinerja.produktivitas'))
            <div
                class="flex items-center bg-white border border-slate-200 rounded-xl px-3 shadow-inner h-[38px] dark:bg-black/40 dark:border-white/10">
                <span class="text-[9px] font-black text-blue-500 uppercase pr-2 border-r border-slate-100 mr-2">Min.
                    Rp</span>
                <input type="number" name="minNominal" value="{{ request('minNominal', 50000) }}"
                    onchange="this.form.submit()"
                    class="w-20 border-none focus:ring-0 text-[10px] font-black text-slate-700 py-1 bg-transparent p-0 dark:text-white">
            </div>
            @endif

            <select name="cabang" onchange="this.form.submit()"
                class="border px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm h-[38px] dark:bg-black/40 dark:border-white/10 dark:text-slate-300 bg-white border-slate-200 text-slate-700 w-full sm:w-40 cursor-pointer">
                <option value="">Semua Cabang</option>
                @foreach($optCabang as $c)
                <option value="{{ $c }}" {{ request('cabang') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <input type="month" name="bulan" value="{{ request('bulan', date('Y-m')) }}" onchange="this.form.submit()"
                class="w-full sm:w-36 border px-4 py-2 rounded-xl text-[11px] font-black uppercase h-[38px] dark:bg-black/40 bg-white dark:border-white/10 border-slate-200 dark:text-white transition-all shadow-sm cursor-pointer">

            <a href="{{ url()->current() }}"
                class="px-4 py-2 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 rounded-xl text-[10px] hover:bg-rose-50 hover:text-rose-500 transition-all shadow-sm h-[38px] flex items-center justify-center"
                title="Reset Filter"><i class="fas fa-undo"></i></a>

            {{-- TOMBOL EXPORT EXCEL --}}
            <a href="{{ route('laporan.kinerja.export.excel', request()->query()) }}"
                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-600/20 h-[38px] flex items-center gap-2 transition-transform active:scale-95 whitespace-nowrap">
                <i class="fas fa-file-excel"></i> Excel
            </a>

            {{-- TOMBOL CETAK PDF --}}
            @php
            $pdfType = 'penjualan';
            if(request()->routeIs('laporan.kinerja.ranking')) $pdfType = 'ar';
            if(request()->routeIs('laporan.kinerja.produktivitas')) $pdfType = 'produktifitas';
            if(request()->routeIs('laporan.kinerja.supplier')) $pdfType = 'supplier';
            @endphp
            <a href="{{ route('laporan.kinerja.export.pdf', ['type' => $pdfType] + request()->query()) }}"
                target="_blank"
                class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 h-[38px] flex items-center gap-2 transition-transform active:scale-95 whitespace-nowrap">
                <i class="fas fa-file-pdf"></i> Cetak Laporan
            </a>
        </form>
    </div>
</div>