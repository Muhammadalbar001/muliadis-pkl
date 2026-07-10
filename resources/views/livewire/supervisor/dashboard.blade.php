<div class="min-h-screen pb-20 font-jakarta bg-slate-50 dark:bg-[#050505] transition-colors duration-300">
    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.4);
        border-radius: 10px;
    }
    </style>

    {{-- HEADER --}}
    <div
        class="sticky top-0 z-40 backdrop-blur-xl border-b -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-6 mb-8 dark:bg-[#0a0a0a]/80 bg-white/80 border-slate-200">
        <div class="flex items-center gap-4">
            <div class="p-3 rounded-2xl shadow-lg bg-blue-600 text-white flex items-center justify-center">
                <i class="fas fa-shield-alt text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black uppercase leading-none dark:text-white text-slate-800">
                    Status Integritas <span class="text-blue-600">Sistem</span>
                </h1>
                <p
                    class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-60 mt-1 dark:text-slate-400 text-slate-500">
                    Pusat Kendali & Taktikal Supervisor
                </p>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- NAVIGATION TABS --}}
        <div class="flex flex-wrap gap-2 border-b border-slate-200 dark:border-white/10 pb-4">
            <button wire:click="setTab('master')"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $activeTab == 'master' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-white dark:bg-white/5 text-slate-500 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                <i class="fas fa-database mr-2"></i> Ringkasan Master
            </button>
            <button wire:click="setTab('otorisasi')"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all relative {{ $activeTab == 'otorisasi' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-white dark:bg-white/5 text-slate-500 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                <i class="fas fa-user-shield mr-2"></i> Otorisasi Data
                @if($antreanHapus->where('status', 'Pending')->count() > 0)
                <span
                    class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[8px] text-white">
                    {{ $antreanHapus->where('status', 'Pending')->count() }}
                </span>
                @endif
            </button>
            <button wire:click="setTab('evaluasi')"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $activeTab == 'evaluasi' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-white dark:bg-white/5 text-slate-500 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                <i class="fas fa-clipboard-list mr-2"></i> Evaluasi Kinerja
            </button>
        </div>

        {{-- ========================================== --}}
        {{-- TAB 1: RINGKASAN MASTER & DATA HEALTH      --}}
        {{-- ========================================== --}}
        @if($activeTab == 'master')
        <div class="space-y-6 animate-fade-in">
            {{-- Data Health Alerts --}}
            @if($health['produk_invalid'] > 0 || $health['sales_tanpa_target'] > 0)
            <div
                class="bg-orange-50 dark:bg-orange-900/10 border border-orange-200 dark:border-orange-500/20 p-5 rounded-2xl flex items-start gap-4">
                <i class="fas fa-exclamation-triangle text-orange-500 text-2xl mt-1"></i>
                <div>
                    <h4 class="font-black text-orange-700 dark:text-orange-400 uppercase tracking-widest text-sm mb-2">
                        Peringatan Kesehatan Data (Data Health)</h4>
                    <ul class="list-disc list-inside text-xs text-orange-600 dark:text-orange-300 space-y-1">
                        @if($health['produk_invalid'] > 0)
                        <li>Terdapat <strong>{{ $health['produk_invalid'] }} Produk</strong> yang belum memiliki relasi
                            Supplier. Segera lengkapi di Master Produk.</li>
                        @endif
                        @if($health['sales_tanpa_target'] > 0)
                        <li>Terdapat <strong>{{ $health['sales_tanpa_target'] }} Salesman Aktif</strong> yang belum
                            diatur Target Penjualannya bulan ini. Hal ini dapat mengganggu perhitungan algoritma SPK
                            Pimpinan.</li>
                        @endif
                    </ul>
                </div>
            </div>
            @endif

            {{-- 4 Kotak Ringkasan --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div
                    class="p-6 bg-white dark:bg-[#121212] rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm">
                    <i class="fas fa-box text-3xl text-indigo-500 mb-4"></i>
                    <h4 class="text-sm font-black text-slate-500 uppercase tracking-widest mb-1">Total Produk</h4>
                    <p class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ $totalProduk }}</p>
                </div>
                <div
                    class="p-6 bg-white dark:bg-[#121212] rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm">
                    <i class="fas fa-truck-loading text-3xl text-blue-500 mb-4"></i>
                    <h4 class="text-sm font-black text-slate-500 uppercase tracking-widest mb-1">Total Supplier</h4>
                    <p class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ $totalSupplier }}</p>
                </div>
                <div
                    class="p-6 bg-white dark:bg-[#121212] rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm">
                    <i class="fas fa-users-tie text-3xl text-orange-500 mb-4"></i>
                    <h4 class="text-sm font-black text-slate-500 uppercase tracking-widest mb-1">Total Salesman</h4>
                    <p class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ $totalSalesman }}</p>
                </div>
                <div
                    class="p-6 bg-white dark:bg-[#121212] rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm">
                    <i class="fas fa-user-shield text-3xl text-emerald-500 mb-4"></i>
                    <h4 class="text-sm font-black text-slate-500 uppercase tracking-widest mb-1">Total Pengguna</h4>
                    <p class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ $totalUser }}</p>
                </div>
            </div>
        </div>
        @endif

        {{-- ========================================== --}}
        {{-- TAB 2: OTORISASI DATA (MAKER-CHECKER)      --}}
        {{-- ========================================== --}}
        @if($activeTab == 'otorisasi')
        <div class="animate-fade-in">
            <div
                class="bg-white dark:bg-[#121212] rounded-3xl shadow-xl border border-slate-200 dark:border-white/5 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                    <h3
                        class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-rose-500"></i> Antrean Penghapusan Data (Dari Admin)
                    </h3>
                    <p class="text-[10px] text-slate-500 mt-1 font-medium">Tinjau permohonan penghapusan data
                        operasional sebelum dieksekusi permanen oleh sistem.</p>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left uppercase">
                        <thead>
                            <tr
                                class="bg-slate-50 dark:bg-[#1a1a1a] text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b dark:border-white/5">
                                <th class="px-6 py-4">Waktu Pengajuan</th>
                                <th class="px-6 py-4">Pemohon</th>
                                <th class="px-6 py-4">Modul Target</th>
                                <th class="px-6 py-4">Rentang Tanggal (Dihapus)</th>
                                <th class="px-6 py-4">Alasan</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($antreanHapus as $req)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300">
                                    {{ $req->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-xs font-black text-blue-600 dark:text-blue-400">
                                    <i class="fas fa-user-circle mr-1"></i> {{ $req->user->name ?? 'Admin' }}
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700 dark:text-slate-200">
                                    {{ $req->modul }}
                                </td>
                                <td
                                    class="px-6 py-4 text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-50/30 dark:bg-rose-500/10">
                                    {{ \Carbon\Carbon::parse($req->tanggal_awal)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($req->tanggal_akhir)->format('d/m/Y') }}
                                </td>
                                <td
                                    class="px-6 py-4 text-[10px] font-medium text-slate-500 dark:text-slate-400 normal-case">
                                    "{{ $req->alasan }}"
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($req->status == 'Pending')
                                    <span
                                        class="px-3 py-1 rounded bg-orange-100 text-orange-600 border border-orange-200 text-[10px] font-bold">Pending</span>
                                    @elseif($req->status == 'Disetujui')
                                    <span
                                        class="px-3 py-1 rounded bg-emerald-100 text-emerald-600 border border-emerald-200 text-[10px] font-bold">Disetujui</span>
                                    @else
                                    <span
                                        class="px-3 py-1 rounded bg-rose-100 text-rose-600 border border-rose-200 text-[10px] font-bold">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($req->status == 'Pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="confirmAction({{ $req->id }}, 'approve')"
                                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-colors border border-emerald-200"
                                            title="Setujui & Hapus">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button wire:click="confirmAction({{ $req->id }}, 'reject')"
                                            class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-colors border border-rose-200"
                                            title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    @else
                                    <span class="text-[10px] text-slate-400 font-bold"><i class="fas fa-lock"></i>
                                        Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-10 text-center font-bold text-slate-400 uppercase tracking-widest text-xs">
                                    <i class="fas fa-check-circle text-emerald-500 text-2xl mb-2 block"></i><br>
                                    Tidak ada antrean penghapusan data saat ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-slate-50 dark:bg-[#1a1a1a] border-t dark:border-white/5 border-slate-100">
                    {{ $antreanHapus->links() }}
                </div>
            </div>
        </div>
        @endif

        {{-- ========================================== --}}
        {{-- TAB 3: EVALUASI KINERJA (TARGET & AI PLAN) --}}
        {{-- ========================================== --}}
        @if($activeTab == 'evaluasi')
        <div class="space-y-6 animate-fade-in">
            {{-- Filter Bulan untuk Pacing --}}
            <div
                class="flex gap-3 items-center bg-white dark:bg-[#121212] p-4 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm w-max">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Bulan Evaluasi:</span>
                <select wire:model.live="evalBulan"
                    class="border px-3 py-1.5 rounded-lg text-xs font-bold uppercase dark:bg-[#1a1a1a] dark:text-white border-slate-200 dark:border-white/10">
                    @for($i=1; $i<=12; $i++) <option value="{{ sprintf('%02d', $i) }}">
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option> @endfor
                </select>
                <select wire:model.live="evalTahun"
                    class="border px-3 py-1.5 rounded-lg text-xs font-bold uppercase dark:bg-[#1a1a1a] dark:text-white border-slate-200 dark:border-white/10">
                    @for($y=date('Y')-1; $y<=date('Y'); $y++) <option value="{{ $y }}">{{ $y }}</option> @endfor
                </select>
                <div wire:loading wire:target="evalBulan, evalTahun"><i
                        class="fas fa-spinner fa-spin text-blue-500"></i></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Kiri: Target Pacing (Progress Bar) --}}
                <div
                    class="bg-white dark:bg-[#121212] rounded-3xl shadow-sm border border-slate-200 dark:border-white/10 p-6">
                    <h3
                        class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-bullseye text-blue-500"></i> Pencapaian Target Sales (Pacing)
                    </h3>
                    <div class="space-y-5 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
                        @foreach($eval['pacing'] as $p)
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <span
                                    class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase">{{ $p['nama'] }}</span>
                                <span
                                    class="text-[10px] font-black {{ $p['persen'] >= 80 ? 'text-emerald-500' : ($p['persen'] >= 50 ? 'text-orange-500' : 'text-rose-500') }}">{{ number_format($p['persen'], 1) }}%</span>
                            </div>
                            <div
                                class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5 mb-1 border border-slate-200 dark:border-white/5">
                                <div class="h-2.5 rounded-full {{ $p['persen'] >= 80 ? 'bg-emerald-500' : ($p['persen'] >= 50 ? 'bg-orange-500' : 'bg-rose-500') }}"
                                    style="width: {{ $p['persen'] > 100 ? 100 : $p['persen'] }}%"></div>
                            </div>
                            <div class="flex justify-between text-[9px] text-slate-400 font-bold tracking-widest">
                                <span>Real: Rp {{ number_format($p['omzet']/1000000, 1) }} Jt</span>
                                <span>Tgt: Rp {{ number_format($p['target']/1000000, 1) }} Jt</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kanan: AI Action Plan --}}
                <div class="space-y-6">
                    {{-- Action 1: Coaching --}}
                    <div
                        class="bg-rose-50 dark:bg-rose-900/10 rounded-3xl border border-rose-200 dark:border-rose-500/20 p-6 shadow-sm">
                        <h3
                            class="text-sm font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-user-md"></i> Jadwalkan Coaching (Kinerja Rendah)
                        </h3>
                        <div class="space-y-2">
                            @forelse($eval['coaching'] as $c)
                            <div
                                class="flex items-center justify-between bg-white dark:bg-[#1a1a1a] p-3 rounded-xl border border-rose-100 dark:border-white/5">
                                <div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white uppercase">
                                        {{ $c['nama'] }}</p>
                                    <p class="text-[10px] text-slate-500">Hanya capai
                                        {{ number_format($c['persen'], 1) }}% target.</p>
                                </div>
                                <button
                                    class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg text-[10px] font-bold uppercase transition-colors">Tindak
                                    Lanjuti</button>
                            </div>
                            @empty
                            <p class="text-xs text-emerald-600 font-bold">Semua sales on-track (>50% target).</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Action 2: Follow Up Churn --}}
                    <div
                        class="bg-orange-50 dark:bg-orange-900/10 rounded-3xl border border-orange-200 dark:border-orange-500/20 p-6 shadow-sm">
                        <h3
                            class="text-sm font-black text-orange-700 dark:text-orange-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-store-slash"></i> Follow-Up Toko Pasif (FCM AI)
                        </h3>
                        <div class="space-y-2">
                            @forelse($eval['churn'] as $ch)
                            <div
                                class="flex items-center justify-between bg-white dark:bg-[#1a1a1a] p-3 rounded-xl border border-orange-100 dark:border-white/5">
                                <div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white uppercase truncate max-w-[150px]"
                                        title="{{ $ch->nama_pelanggan }}">{{ $ch->nama_pelanggan }}</p>
                                    <p class="text-[9px] text-orange-600 font-bold">Sales: {{ $ch->sales_name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] text-slate-400">Order Terakhir:</p>
                                    <p class="text-[10px] font-black text-slate-600 dark:text-slate-300">
                                        {{ \Carbon\Carbon::parse($ch->last_order)->format('d M Y') }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-xs text-emerald-600 font-bold">Tidak ada toko pasif bulan ini.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- MODAL KONFIRMASI OTORISASI --}}
    @if($showConfirmModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-[#1a1a1a] rounded-3xl p-8 max-w-md w-full shadow-2xl text-center animate-fade-in">
            @if($actionType == 'approve')
            <div
                class="w-20 h-20 mx-auto bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mb-4 border-4 border-rose-50">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-wider mb-2">Peringatan
                Kritis!</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
                Anda akan <strong>MENYETUJUI</strong> penghapusan data ini. Data yang dihapus dari database tidak dapat
                dikembalikan. Lanjutkan?
            </p>
            @else
            <div
                class="w-20 h-20 mx-auto bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mb-4 border-4 border-orange-50">
                <i class="fas fa-times-circle text-4xl"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-wider mb-2">Tolak Pengajuan?
            </h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
                Pengajuan penghapusan dari Admin ini akan dibatalkan dan statusnya ditandai sebagai Ditolak.
            </p>
            @endif

            <div class="flex gap-3 justify-center">
                <button wire:click="closeModal"
                    class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold uppercase text-xs tracking-widest transition-colors">
                    Batal
                </button>
                <button wire:click="executeAction"
                    class="px-6 py-3 rounded-xl text-white font-bold uppercase text-xs tracking-widest transition-transform active:scale-95 {{ $actionType == 'approve' ? 'bg-rose-600 hover:bg-rose-700 shadow-lg shadow-rose-500/30' : 'bg-orange-500 hover:bg-orange-600 shadow-lg shadow-orange-500/30' }}">
                    Ya, Eksekusi Sekarang
                </button>
            </div>
        </div>
    </div>
    @endif
</div>