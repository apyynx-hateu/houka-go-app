<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HoukaGO Operasional</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-400 items-center justify-center shadow-xl shadow-brand-500/25 mb-4 text-white font-extrabold text-2xl tracking-tight">
                HG
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Houka<span class="text-brand-400">GO</span></h1>
            <p class="text-sm text-slate-400 mt-1">Sistem Manajemen Operasional Bimbel</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-white mb-2">Masuk ke Portal</h2>
            <p class="text-xs text-slate-400 mb-6">Silakan masukkan username dan kata sandi Anda</p>

            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-2.5 px-4 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-semibold text-sm shadow-lg shadow-brand-600/30 transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Masuk ke Akun</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>

            <!-- Test Accounts Helper -->
            <div class="mt-6 pt-5 border-t border-slate-800 text-[11px] text-slate-400">
                <span class="font-semibold text-slate-300 block mb-1.5">Akun Demo Tersedia (Password: password):</span>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <button type="button" onclick="fillLogin('admin')" class="py-1 px-2 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700/80 text-brand-300 font-medium transition-colors">
                        admin
                        <span class="block text-[9px] text-slate-500">CO Founder</span>
                    </button>
                    <button type="button" onclick="fillLogin('operasional')" class="py-1 px-2 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700/80 text-brand-300 font-medium transition-colors">
                        operasional
                        <span class="block text-[9px] text-slate-500">Operasional</span>
                    </button>
                    <button type="button" onclick="fillLogin('hr')" class="py-1 px-2 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700/80 text-brand-300 font-medium transition-colors">
                        hr
                        <span class="block text-[9px] text-slate-500">HR</span>
                    </button>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">&copy; {{ date('Y') }} Houka GO. All rights reserved.</p>
    </div>

    <script>
        function fillLogin(username) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
