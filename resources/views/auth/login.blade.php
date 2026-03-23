<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure Login | Sistem Informasi Eksekutif</title>

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
        /* Latar belakang selaras dengan halaman Welcome */
        background: radial-gradient(circle at 50% 0%, rgba(15, 23, 42, 0.9) 0%, rgba(5, 5, 5, 1) 80%),
            url('/images/bg-login.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .glass-card {
        background: rgba(20, 20, 20, 0.4);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        animation: slideUp 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
    }

    .input-glass {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: white;
        transition: all 0.3s ease;
    }

    .input-glass:focus {
        border-color: #3b82f6;
        background: rgba(0, 0, 0, 0.6);
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.15);
    }

    .btn-portal {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        transition: all 0.3s ease;
    }

    .btn-portal:hover {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        transform: translateY(-2px);
    }

    .btn-portal:active {
        transform: scale(0.98);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Styling kustom untuk Checkbox */
    .custom-checkbox {
        appearance: none;
        background-color: rgba(255, 255, 255, 0.05);
        margin: 0;
        font: inherit;
        color: currentColor;
        width: 1.15em;
        height: 1.15em;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 0.25em;
        display: grid;
        place-content: center;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .custom-checkbox::before {
        content: "";
        width: 0.65em;
        height: 0.65em;
        transform: scale(0);
        transition: 120ms transform ease-in-out;
        box-shadow: inset 1em 1em white;
        background-color: transform-origin;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    }

    .custom-checkbox:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .custom-checkbox:checked::before {
        transform: scale(1);
    }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6 selection:bg-blue-500/30 selection:text-blue-200">

    <div class="w-full max-w-[420px] relative z-10">

        <div class="text-center mb-8">
            <div
                class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 mx-auto mb-5 border border-blue-400/20">
                <i class="fas fa-shield-halved text-white text-2xl"></i>
            </div>
            <h1 class="text-xl font-black text-white tracking-tight uppercase leading-none">PT MULIA ANUGERAH</h1>
            <p class="text-[9px] font-bold text-blue-400 tracking-[0.3em] uppercase mt-2">Executive Information System
            </p>
        </div>

        <div class="glass-card p-10 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-600/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-center gap-3 mb-8">
                    <div class="h-[1px] w-8 bg-white/10"></div>
                    <h2 class="text-xs font-black text-slate-300 uppercase tracking-[0.2em] text-center">Secure Access
                    </h2>
                    <div class="h-[1px] w-8 bg-white/10"></div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Kredensial
                            Pengguna</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-slate-500 text-sm"></i>
                            </div>
                            <input type="text" name="username" value="{{ old('username') }}" required autofocus
                                autocomplete="username"
                                class="input-glass w-full pl-11 pr-5 py-3.5 rounded-xl text-sm outline-none placeholder:text-slate-600 focus:placeholder:text-slate-500"
                                placeholder="Masukkan Username Anda">
                        </div>
                        @error('username')
                        <p class="text-red-400 text-[10px] font-bold mt-2 ml-1 flex items-center gap-1.5">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex justify-between items-center mb-2 ml-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kata
                                Sandi</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-500 text-sm"></i>
                            </div>
                            <input type="password" name="password" required autocomplete="current-password"
                                class="input-glass w-full pl-11 pr-5 py-3.5 rounded-xl text-sm outline-none placeholder:text-slate-600 focus:placeholder:text-slate-500"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                        <p class="text-red-400 text-[10px] font-bold mt-2 ml-1 flex items-center gap-1.5">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="flex items-center ml-1">
                        <label for="remember_me" class="flex items-center cursor-pointer gap-2.5">
                            <input id="remember_me" type="checkbox" name="remember" class="custom-checkbox">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Ingat
                                Sesi Saya</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="btn-portal w-full py-3.5 text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-lg flex justify-center items-center gap-2 mt-2">
                        <span>Otorisasi Masuk</span>
                        <i class="fas fa-arrow-right-to-bracket text-sm"></i>
                    </button>
                </form>

            </div>
        </div>

        <p class="text-center text-[9px] font-bold text-slate-600 uppercase tracking-widest mt-8">
            <i class="fas fa-lock mr-1"></i> Terenkripsi & Terlindungi
        </p>

    </div>
</body>

</html>