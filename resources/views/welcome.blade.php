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
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #0a0e27;
        color: #cbd5e1;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .hero-bg {
        background: radial-gradient(circle at 50% 0%, rgba(15, 23, 42, 0.85) 0%, rgba(10, 14, 39, 1) 65%),
            url('/images/bg-welcome.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    .glass-nav {
        background: rgba(10, 14, 39, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(59, 130, 246, 0.1);
    }

    .glass-nav.scrolled {
        background: rgba(10, 14, 39, 0.95);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .glass-card {
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(59, 130, 246, 0.15);
        border-radius: 1.75rem;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }

    .glass-card:hover {
        background: rgba(20, 30, 60, 0.7);
        border-color: rgba(59, 130, 246, 0.5);
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(59, 130, 246, 0.15);
    }

    /* Animasi saat Scroll Terdeteksi */
    .reveal {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.9s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    .text-blue-glow {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        filter: drop-shadow(0 0 15px rgba(59, 130, 246, 0.3));
    }

    .btn-portal {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }

    .btn-portal::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        transition: left 0.4s ease;
        z-index: -1;
    }

    .btn-portal:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
    }

    .btn-portal:hover::before {
        left: 0;
    }

    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(10, 14, 39, 0.3);
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.5);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: rgba(59, 130, 246, 0.7);
    }

    /* Improved Typography */
    h1, h2, h3, h4, h5, h6 {
        letter-spacing: -0.02em;
    }

    p {
        color: #a0aec0;
    }

    /* Badge Styling */
    .badge-glow {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 1rem;
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        color: #60a5fa;
        text-transform: uppercase;
    }

    .pulse-dot {
        width: 0.5rem;
        height: 0.5rem;
        background: #3b82f6;
        border-radius: 50%;
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    /* Section Separator */
    .section-divider {
        width: 6rem;
        height: 0.375rem;
        background: linear-gradient(90deg, #3b82f6 0%, #60a5fa 50%, transparent 100%);
        border-radius: 10px;
    }

    /* Footer Enhancement */
    footer {
        border-top: 1px solid rgba(59, 130, 246, 0.1);
        background: linear-gradient(180deg, rgba(10, 14, 39, 0.3) 0%, rgba(10, 14, 39, 0.7) 100%);
    }
    </style>
</head>

<body class="antialiased hero-bg font-sans selection:bg-blue-500/30 selection:text-blue-200">

    <nav class="fixed top-0 w-full z-[100] glass-nav h-20 transition-all duration-500" id="navbar">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12 h-full flex justify-between items-center">
            <div class="flex items-center gap-3 cursor-pointer group">
                <div
                    class="w-11 h-11 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 transition-all duration-300">
                    <i class="fas fa-chart-pie text-white text-lg font-bold"></i>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-sm font-black text-white tracking-tight leading-tight">PT MULIA</h1>
                    <p class="text-[9px] font-bold text-blue-400 tracking-[0.08em]">DISTRIBUSINDO</p>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-12 text-xs font-bold uppercase tracking-[0.12em]">
                <a href="#solusi" class="text-slate-400 hover:text-white transition-colors duration-300">Tentang Solusi</a>
                <a href="#fitur" class="text-slate-400 hover:text-white transition-colors duration-300">Fitur Utama</a>
                @auth
                <a href="{{ url('/dashboard') }}"
                    class="btn-portal px-6 py-2.5 text-white text-xs font-bold rounded-xl border border-blue-400/20 shadow-lg">
                    <i class="fas fa-arrow-right mr-2"></i>Dashboard
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="btn-portal px-6 py-2.5 text-white text-xs font-bold rounded-xl border border-blue-400/20 shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </a>
                @endauth
            </div>

            <div class="md:hidden">
                @auth
                <a href="{{ url('/dashboard') }}" class="btn-portal px-4 py-2 text-white text-xs font-bold rounded-lg">
                    <i class="fas fa-arrow-right"></i>
                </a>
                @else
                <a href="{{ route('login') }}" class="btn-portal px-4 py-2 text-white text-xs font-bold rounded-lg">
                    <i class="fas fa-sign-in-alt"></i>
                </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-24 px-6 md:px-12 max-w-[1440px] mx-auto space-y-36">

        <!-- Hero Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 reveal">
            <div
                class="lg:col-span-8 glass-card p-12 md:p-16 flex flex-col justify-center min-h-[480px] relative overflow-hidden group">
                <div
                    class="absolute -right-20 -bottom-20 opacity-[0.02] pointer-events-none transition-transform group-hover:scale-125 duration-1000">
                    <i class="fas fa-network-wired text-[32rem]"></i>
                </div>
                <div class="relative z-10 space-y-10">
                    <div class="badge-glow">
                        <span class="pulse-dot"></span>
                        Executive Information System
                    </div>
                    <div class="space-y-4">
                        <h2
                            class="text-5xl md:text-6xl lg:text-7xl font-black text-white leading-[1.1] tracking-[-0.02em]">
                            Keputusan Cerdas
                        </h2>
                        <h2 class="text-5xl md:text-6xl lg:text-7xl font-black text-blue-glow leading-[1.1] tracking-[-0.02em]">
                            Berbasis Data Akurat.
                        </h2>
                    </div>
                    <p class="text-slate-300 font-medium max-w-2xl text-base md:text-lg leading-relaxed pt-2">
                        Transformasikan kompleksitas jutaan baris data operasional menjadi insights strategis real-time. 
                        Dilengkapi dengan teknologi <span class="text-white font-bold">SPK SAW</span> dan <span
                            class="text-white font-bold">Segmentasi RFM</span> untuk optimalisasi kinerja distribusi.
                    </p>
                </div>
            </div>

            <div id="solusi"
                class="lg:col-span-4 glass-card p-10 md:p-12 flex flex-col justify-between border-l-4 border-l-blue-500 scroll-mt-32">
                <div>
                    <h3
                        class="text-blue-400 text-[10px] font-black uppercase tracking-[0.25em] mb-6 flex items-center gap-2">
                        <i class="fas fa-bullseye w-4 h-4"></i> Visi Sistem
                    </h3>
                    <p class="text-lg font-semibold text-white leading-relaxed italic mb-10">
                        "Mengeliminasi informasi bottleneck dan mengakselerasi pengambilan keputusan berbasis data presisi."
                    </p>
                </div>
                <div class="space-y-5">
                    <div class="flex items-start gap-3 text-sm font-semibold text-slate-200">
                        <div class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-xs"></i></div>
                        <span>Otomatisasi Rekapitulasi Data Operasional</span>
                    </div>
                    <div class="flex items-start gap-3 text-sm font-semibold text-slate-200">
                        <div class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-xs"></i></div>
                        <span>Analisa Cerdas Dengan Akurasi Tinggi</span>
                    </div>
                    <div class="flex items-start gap-3 text-sm font-semibold text-slate-200">
                        <div class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-xs"></i></div>
                        <span>Laporan Terpadu Real-time Setiap Waktu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fitur Section -->
        <div id="fitur" class="space-y-20 reveal scroll-mt-32">
            <div class="flex flex-col items-center text-center space-y-6">
                <div
                    class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-500/10 text-blue-400 border border-blue-500/20">
                    <i class="fas fa-microchip text-2xl"></i>
                </div>
                <div class="space-y-4">
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-tight tracking-[-0.02em]">
                        Modul Unggulan Eksekutif
                    </h2>
                    <p class="text-slate-400 text-base max-w-2xl mx-auto">
                        Fitur-fitur canggih dirancang untuk mendukung pengambilan keputusan strategis yang lebih baik
                    </p>
                </div>
                <div class="section-divider mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7">
                <div class="glass-card p-10 group hover:bg-blue-600/5 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-40 h-40 bg-blue-500/3 rounded-bl-3xl -z-10 transition-transform group-hover:scale-125 duration-500">
                    </div>
                    <div
                        class="w-16 h-16 bg-blue-500/15 border border-blue-500/30 rounded-2xl flex items-center justify-center text-blue-400 text-3xl mb-6 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-blue-500/30 transition-all duration-300">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h4 class="text-sm font-black text-white uppercase tracking-wider mb-3">Dashboard Interaktif</h4>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Visualisasi metrik operasional real-time dengan interface intuitif untuk berbagai level manajemen.</p>
                </div>

                <div class="glass-card p-10 group hover:bg-emerald-600/5 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-40 h-40 bg-emerald-500/3 rounded-bl-3xl -z-10 transition-transform group-hover:scale-125 duration-500">
                    </div>
                    <div
                        class="w-16 h-16 bg-emerald-500/15 border border-emerald-500/30 rounded-2xl flex items-center justify-center text-emerald-400 text-3xl mb-6 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-emerald-500/30 transition-all duration-300">
                        <i class="fas fa-ranking-star"></i>
                    </div>
                    <h4 class="text-sm font-black text-white uppercase tracking-wider mb-3">Analisa Cerdas (SAW)</h4>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Sistem Pendukung Keputusan berbasis metode Simple Additive Weighting untuk evaluasi kinerja optimal.</p>
                </div>

                <div class="glass-card p-10 group hover:bg-orange-600/5 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-40 h-40 bg-orange-500/3 rounded-bl-3xl -z-10 transition-transform group-hover:scale-125 duration-500">
                    </div>
                    <div
                        class="w-16 h-16 bg-orange-500/15 border border-orange-500/30 rounded-2xl flex items-center justify-center text-orange-400 text-3xl mb-6 group-hover:bg-orange-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-orange-500/30 transition-all duration-300">
                        <i class="fas fa-users-rays"></i>
                    </div>
                    <h4 class="text-sm font-black text-white uppercase tracking-wider mb-3">Segmentasi RFM</h4>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Pemetaan perilaku pelanggan berdasarkan Recency, Frequency, dan Monetary untuk strategi targeted.</p>
                </div>

                <div class="glass-card p-10 group hover:bg-purple-600/5 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-40 h-40 bg-purple-500/3 rounded-bl-3xl -z-10 transition-transform group-hover:scale-125 duration-500">
                    </div>
                    <div
                        class="w-16 h-16 bg-purple-500/15 border border-purple-500/30 rounded-2xl flex items-center justify-center text-purple-400 text-3xl mb-6 group-hover:bg-purple-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-purple-500/30 transition-all duration-300">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h4 class="text-sm font-black text-white uppercase tracking-wider mb-3">Pusat Cetak Laporan</h4>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">Akses instan untuk mencetak 8 laporan strategis dalam format PDF dan Excel dengan formatting professional.</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal">
            <div class="md:col-span-1 glass-card p-12 flex flex-col justify-center items-center text-center space-y-4 border-t-2 border-t-blue-500">
                <span
                    class="text-xs font-black text-blue-400 uppercase tracking-[0.25em] bg-blue-500/10 px-4 py-1.5 rounded-full border border-blue-500/20">Keamanan</span>
                <div class="text-6xl font-black bg-gradient-to-br from-blue-400 to-cyan-400 bg-clip-text text-transparent">100%</div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Otorisasi Role-based</p>
            </div>

            <div class="md:col-span-2 glass-card p-2 group">
                <a href="{{ route('login') }}"
                    class="w-full h-full bg-gradient-to-r from-blue-600/5 to-cyan-600/5 rounded-[1.5rem] flex flex-col md:flex-row items-center justify-between p-12 md:p-14 transition-all group-hover:bg-gradient-to-r group-hover:from-blue-600/10 group-hover:to-cyan-600/10 border border-transparent group-hover:border-blue-500/30">
                    <div class="space-y-3 text-center md:text-left mb-8 md:mb-0">
                        <h4 class="text-3xl font-black text-white uppercase tracking-tight leading-tight">Mulai Sekarang</h4>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-widest">Akses sistem dengan kredensial perusahaan Anda</p>
                    </div>
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-blue-600 to-cyan-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-300 flex-shrink-0">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
            </div>
        </div>

    </main>

    <footer class="py-10 border-t border-blue-500/10 bg-gradient-to-r from-blue-950/30 via-slate-900/20 to-blue-950/30 mt-12">
        <div
            class="max-w-[1440px] mx-auto px-6 md:px-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs font-bold"></i>
                </div>
                <p class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">© 2026 PT MULIA ANUGERAH DISTRIBUSINDO</p>
            </div>
            <div class="flex gap-8">
                <span class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">Confidential Internal Use</span>
            </div>
        </div>
    </footer>

    <script>
    // Smooth Reveal Animation on Scroll
    function reveal() {
        var reveals = document.querySelectorAll(".reveal");
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            var elementVisible = 120;
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add("active");
            }
        }
    }

    window.addEventListener("scroll", reveal, { passive: true });
    reveal(); // Panggil saat load

    // Enhanced Navbar Scroll Effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 80) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }, { passive: true });

    // Smooth scroll untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                const target = document.querySelector(href);
                const offset = 100;
                const targetPosition = target.offsetTop - offset;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    </script>

</body>

</html>