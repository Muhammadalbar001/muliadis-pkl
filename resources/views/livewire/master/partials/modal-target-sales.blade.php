<div class="fixed inset-0 z-[170] overflow-y-auto px-4 py-6">
    <div class="flex items-center justify-center min-h-screen">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md transition-opacity" wire:click="closeModal"></div>

        <div
            class="relative bg-white dark:bg-[#0a0a0a] rounded-[2rem] w-full max-w-3xl overflow-hidden shadow-2xl border dark:border-white/10 border-slate-200 transition-all transform font-jakarta">

            <div
                class="bg-gradient-to-r from-purple-700 to-indigo-800 p-6 text-white flex justify-between items-center relative">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-xl border border-white/20 shadow-inner">
                        <i class="fas fa-crosshairs text-xl text-yellow-300"></i>
                    </div>
                    <div>
                        <h3 class="font-black uppercase tracking-wider text-base leading-none">Target Penjualan</h3>
                        <p class="text-[11px] font-medium opacity-80 mt-1.5 italic">Sales:
                            {{ $selectedSalesNameForTarget }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 relative z-10">
                    <select wire:model.live="targetYear"
                        class="bg-white/10 border border-white/20 rounded-lg text-[11px] font-black text-white px-2 py-1 focus:ring-0 cursor-pointer appearance-none text-center">
                        @for($y = date('Y')-1; $y <= date('Y')+2; $y++) <option value="{{ $y }}" class="text-slate-900">
                            {{ $y }}</option>
                            @endfor
                    </select>
                    <button wire:click="closeModal"
                        class="w-10 h-10 rounded-xl bg-white/10 hover:bg-rose-500/20 hover:text-rose-500 flex items-center justify-center transition-all text-white border border-white/20">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-white/[0.02] px-8 py-5 border-b dark:border-white/5 border-slate-200">
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="flex-1 w-full">
                        <label class="text-[9px] font-black uppercase text-slate-500 mb-2 block ml-1">Quick Fill (Set
                            Semua Bulan)</label>
                        <div
                            class="flex items-center shadow-sm rounded-xl overflow-hidden border dark:border-white/10 border-slate-300">
                            <input type="number" wire:model="bulkTarget" placeholder="Nominal..."
                                class="flex-1 px-4 py-2 dark:bg-black/60 bg-white dark:text-white text-xs font-black focus:ring-0 outline-none border-none">
                            <select wire:model="multiplier"
                                class="bg-slate-100 dark:bg-slate-800 border-x dark:border-white/10 border-slate-200 text-[9px] font-black uppercase px-3 py-2 focus:ring-0 outline-none dark:text-white cursor-pointer">
                                <option value="1000000">Jutaan</option>
                                <option value="100000">Ratusan</option>
                                <option value="1">Satuan</option>
                            </select>
                            <button wire:click="applyBulkTarget"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 text-[9px] font-black uppercase transition-all flex items-center gap-2 shrink-0">
                                <i class="fas fa-magic"></i> Apply
                            </button>
                        </div>
                    </div>
                    <div class="hidden lg:block w-px h-10 bg-slate-300 dark:bg-white/10 mx-2"></div>
                    <div class="sm:w-48 text-right">
                        <p class="text-[9px] font-bold text-blue-500 dark:text-blue-400 italic leading-tight">
                            Tips: Masukkan <span class="underline">1.5</span> + <span class="underline">Jutaan</span>
                            untuk hasil <span class="font-black">Rp 1.500.000</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-white dark:bg-transparent">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @php
                    $months = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli',
                    8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                    @endphp
                    @foreach($months as $num => $name)
                    <div
                        class="group space-y-1.5 p-3 rounded-2xl border dark:border-white/5 border-slate-100 hover:border-indigo-500/40 hover:bg-indigo-50/30 transition-all duration-200">
                        <div
                            class="flex justify-between items-center px-0.5 text-[9px] font-black text-slate-400 uppercase tracking-tighter">
                            <span>{{ $name }}</span>
                            <span
                                class="text-slate-300 italic font-mono">{{ str_pad($num, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-[9px] font-black text-slate-400">Rp</span>
                            <input type="text" wire:model.defer="monthlyTargets.{{ $num }}"
                                class="w-full pl-9 pr-3 py-2.5 rounded-xl border dark:bg-black/40 bg-white dark:border-white/10 border-slate-200 dark:text-white text-xs font-black focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-inner">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div
                class="dark:bg-white/[0.02] bg-slate-50 px-8 py-5 flex justify-end gap-3 border-t dark:border-white/5 border-slate-200">
                <button wire:click="closeModal"
                    class="px-6 py-2.5 text-[10px] font-black uppercase text-slate-500 hover:text-rose-500 transition-colors">Batal</button>
                <button wire:click="saveTargets"
                    class="px-8 py-2.5 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 text-white rounded-xl text-[10px] font-black uppercase shadow-lg shadow-indigo-500/30 transform active:scale-95 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle text-xs"></i> SIMPAN TARGET
                </button>
            </div>
        </div>
    </div>
</div>