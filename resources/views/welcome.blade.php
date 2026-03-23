<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Eksekutif - PT Mulia Anugerah Distribusindo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #050505;
        color: #cbd5e1;
    }

    .hero-bg {
        background: radial-gradient(circle at 50% 0%, rgba(15, 23, 42, 0.8) 0%, rgba(5, 5, 5, 1) 70%),
            url('/images/bg-welcome.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    .glass-nav {
        background: rgba(5, 5, 5, 0.6);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .glass-card {
        background: rgba(20, 20, 20, 0.4);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 1.5rem;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    .glass-card:hover {
        background: rgba(30, 30, 30, 0.6);
        border-color: rgba(59, 130, 246, 0.4);
        transform: translateY(-5px);
        box-shadow: 0 10px 40px rgba(59, 130, 246, 0.1);
    }

    /* Animasi saat Scroll Terdeteksi */
    .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    .text-blue-glow {
        color: #3b82f6;
        text-shadow: 0 0 25px rgba(59, 130, 246, 0.5);
    }

    .btn-portal {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        transition: all 0.3s ease;
    }

    .btn-portal:hover {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        transform: scale(1.05);
    }

    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #050505;
    }

    ::-webkit-scrollbar-thumb {
        background: #1e293b;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #334155;
    }
    </style>
</head>

<body class="antialiased hero-bg font-sans selection:bg-blue-500/30 selection:text-blue-200">

    <nav class="fixed top-0 w-full z-[100] glass-nav h-20 transition-all duration-300" id="navbar">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12 h-full flex justify-between items-center">
            <div class="flex items-center gap-4 group cursor-pointer">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-blue-500/30 transition-all duration-300">
                    <i class="fas fa-chart-pie text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-sm font-black text-white tracking-tight uppercase leading-none">PT MULIA ANUGERAH
                    </h1>
                    <p class="text-[9px] font-bold text-blue-400 tracking-[0.25em] uppercase mt-1">Distribusindo</p>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-10 text-xs font-bold uppercase tracking-[0.15em]">
                <a href="#solusi" class="text-slate-400 hover:text-white transition-colors">Solusi EIS</a>
                <a href="#fitur" class="text-slate-400 hover:text-white transition-colors">Fitur Cerdas</a>
                @auth
                <a href="{{ url('/dashboard') }}"
                    class="btn-portal px-7 py-2.5 text-white rounded-xl shadow-lg border border-blue-400/20">Akses
                    Dashboard</a>
                @else
                <a href="{{ route('login') }}"
                    class="btn-portal px-7 py-2.5 text-white rounded-xl shadow-lg border border-blue-400/20">Portal
                    Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-24 px-6 md:px-12 max-w-[1440px] mx-auto space-y-32">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 reveal">
            <div
                class="lg:col-span-8 glass-card p-10 md:p-14 flex flex-col justify-center min-h-[450px] relative overflow-hidden group">
                <div
                    class="absolute -right-10 -bottom-10 opacity-[0.04] pointer-events-none transition-transform group-hover:scale-110 duration-1000">
                    <i class="fas fa-network-wired text-[28rem]"></i>
                </div>
                <div class="relative z-10 space-y-8">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-500/10 text-blue-400 rounded-full text-[10px] font-bold uppercase tracking-[0.25em] border border-blue-500/20">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        Executive Information System
                    </div>
                    <h2
                        class="text-5xl md:text-7xl lg:text-[5rem] font-black text-white leading-[1.05] tracking-tight uppercase">
                        Keputusan Cerdas <br><span class="text-blue-glow">Berbasis Data.</span>
                    </h2>
                    <p class="text-slate-400 font-medium max-w-xl text-base md:text-lg leading-relaxed">
                        Ubah kompleksitas jutaan baris data operasional menjadi wawasan strategis. Dilengkapi dengan
                        teknologi <span class="text-white font-bold">SPK SAW</span> dan <span
                            class="text-white font-bold">Segmentasi RFM</span> untuk dominasi pasar distribusi.
                    </p>
                </div>
            </div>

            <div id="solusi"
                class="lg:col-span-4 glass-card p-10 flex flex-col justify-center border-l-4 border-l-blue-500 scroll-mt-32">
                <h3
                    class="text-blue-400 text-[11px] font-black uppercase tracking-[0.3em] mb-5 flex items-center gap-2">
                    <i class="fas fa-bullseye"></i> Tujuan Sistem
                </h3>
                <p class="text-xl font-medium text-white leading-relaxed italic mb-8">
                    "Mengeliminasi information bottleneck dan data silo, mempercepat evaluasi kinerja perusahaan secara
                    presisi."
                </p>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 text-xs font-bold text-slate-300 uppercase tracking-widest">
                        <div class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400">
                            <i class="fas fa-check"></i></div>
                        Otomatisasi Rekapitulasi
                    </div>
                    <div class="flex items-center gap-4 text-xs font-bold text-slate-300 uppercase tracking-widest">
                        <div class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400">
                            <i class="fas fa-check"></i></div>
                        Analisa Cerdas Tepat Sasaran
                    </div>
                    <div class="flex items-center gap-4 text-xs font-bold text-slate-300 uppercase tracking-widest">
                        <div class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400">
                            <i class="fas fa-check"></i></div>
                        Laporan Real-time & Terpadu
                    </div>
                </div>
            </div>
        </div>

        <div id="fitur" class="space-y-16 reveal scroll-mt-32">
            <div class="flex flex-col items-center text-center space-y-4">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-500/10 text-blue-400 mb-2">
                    <i class="fas fa-microchip text-xl"></i>
                </div>
                <h2 class="text-3xl font-black text-white uppercase tracking-tighter">Modul Utama Eksekutif</h2>
                <div class="h-1.5 w-24 bg-gradient-to-r from-blue-600 to-blue-400 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="glass-card p-8 group hover:bg-blue-600/5 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-bl-[100px] -z-10 transition-transform group-hover:scale-110">
                    </div>
                    <div
                        class="w-14 h-14 bg-blue-500/10 border border-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 text-2xl mb-6 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300 shadow-lg">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h4 class="text-base font-black text-white uppercase tracking-widest mb-3">Dashboard Interaktif</h4>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Pemantauan visual metrik operasional
                        harian secara terpusat untuk berbagai tingkatan level manajemen.</p>
                </div>

                <div class="glass-card p-8 group hover:bg-emerald-600/5 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-bl-[100px] -z-10 transition-transform group-hover:scale-110">
                    </div>
                    <div
                        class="w-14 h-14 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-400 text-2xl mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 shadow-lg">
                        <i class="fas fa-ranking-star"></i>
                    </div>
                    <h4 class="text-base font-black text-white uppercase tracking-widest mb-3">Analisa Cerdas (SAW)</h4>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Sistem Pendukung Keputusan menggunakan
                        metode Simple Additive Weighting untuk pemeringkatan kinerja Sales.</p>
                </div>

                <div class="glass-card p-8 group hover:bg-orange-600/5 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-orange-500/5 rounded-bl-[100px] -z-10 transition-transform group-hover:scale-110">
                    </div>
                    <div
                        class="w-14 h-14 bg-orange-500/10 border border-orange-500/20 rounded-2xl flex items-center justify-center text-orange-400 text-2xl mb-6 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300 shadow-lg">
                        <i class="fas fa-users-rays"></i>
                    </div>
                    <h4 class="text-base font-black text-white uppercase tracking-widest mb-3">Segmentasi RFM</h4>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Pemetaan perilaku dan loyalitas
                        pelanggan secara otomatis berdasarkan Recency, Frequency, dan Monetary.</p>
                </div>

                <div class="glass-card p-8 group hover:bg-purple-600/5 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-bl-[100px] -z-10 transition-transform group-hover:scale-110">
                    </div>
                    <div
                        class="w-14 h-14 bg-purple-500/10 border border-purple-500/20 rounded-2xl flex items-center justify-center text-purple-400 text-2xl mb-6 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300 shadow-lg">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h4 class="text-base font-black text-white uppercase tracking-widest mb-3">Pusat Cetak Laporan</h4>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Akses instan untuk mencetak 8 metrik
                        laporan kinerja strategis perusahaan dalam format PDF dan Excel.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal">
            <div class="md:col-span-1 glass-card p-10 flex flex-col justify-center items-center text-center space-y-4">
                <span
                    class="text-xs font-black text-blue-500 uppercase tracking-[0.3em] bg-blue-500/10 px-3 py-1 rounded-full">Keamanan
                    Sistem</span>
                <div class="text-5xl font-black text-white drop-shadow-lg">100%</div>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">Otorisasi Berbasis Peran</p>
            </div>

            <div class="md:col-span-2 glass-card p-2 group">
                <a href="{{ route('login') }}"
                    class="w-full h-full bg-blue-600/5 rounded-[1.3rem] flex flex-col md:flex-row items-center justify-between p-10 md:p-12 transition-all group-hover:bg-blue-600/10 border border-transparent group-hover:border-blue-500/20">
                    <div class="space-y-3 text-center md:text-left mb-6 md:mb-0">
                        <h4 class="text-3xl font-black text-white uppercase tracking-tight">Mulai Eksplorasi Data</h4>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-widest">Masuk ke sistem
                            menggunakan kredensial Anda.</p>
                    </div>
                    <div
                        class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-blue-500/40 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-300">
                        <i class="fas fa-door-open"></i>
                    </div>
                </a>
            </div>
        </div>

    </main>

    <footer class="py-8 border-t border-white/5 bg-black/40 mt-10">
        <div
            class="max-w-[1440px] mx-auto px-6 md:px-12 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-500">
            <p class="text-[10px] font-black uppercase tracking-[0.3em]">© 2026 PT MULIA ANUGERAH DISTRIBUSINDO</p>
            <div class="flex gap-6">
                <span class="text-[10px] font-bold tracking-widest uppercase">Internal Use Only</span>
            </div>
        </div>
    </footer>

    <script>
    // Animasi Reveal saat scroll
    function reveal() {
        var reveals = document.querySelectorAll(".reveal");
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            var elementVisible = 100;
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add("active");
            }
        }
    }
    window.addEventListener("scroll", reveal);
    reveal(); // Panggil sekali saat load

    // Efek blur/gelap pada navbar saat di-scroll
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('bg-black/80', 'shadow-lg');
            navbar.classList.remove('h-20');
            navbar.classList.add('h-16');
        } else {
            navbar.classList.remove('bg-black/80', 'shadow-lg');
            navbar.classList.add('h-20');
            navbar.classList.remove('h-16');
        }
    });
    </script>

</body>

</html>