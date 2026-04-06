<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AssetHub</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assethub-icon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-25px); }
        }
        .floating { animation: float 4s ease-in-out infinite; }
        .login-gradient { background: linear-gradient(135deg, #4680ff 0%, #6f42c1 100%); }
    </style>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: { primary: '#4680ff' } } }
        }
    </script>
</head>
<body class="bg-[#f4f7fa] dark:bg-slate-900 min-h-screen flex items-center justify-center p-4 transition-colors duration-300">

    <div class="absolute top-5 right-5 z-50">
        <button @click="darkMode = !darkMode; localStorage.setItem('dark', darkMode)" 
            class="p-3 rounded-full bg-white dark:bg-slate-800 shadow-lg text-gray-500 dark:text-yellow-400 hover:scale-110 transition">
            <template x-if="!darkMode">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </template>
            <template x-if="darkMode">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z"></path></svg>
            </template>
        </button>
    </div>

    <div class="max-w-5xl w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px]">
        
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center">
            <div class="mb-10 text-center md:text-left">
                <img src="{{ asset('images/assethub-horizontal.png') }}" alt="Logo" class="h-10 mx-auto md:mx-0 dark:brightness-200">
            </div>

            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">Selamat Datang!</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base">Silakan masuk dengan NIP dan Password Anda.</p>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                @if($errors->any())
                    <div class="bg-red-50 text-red-500 p-3 rounded-lg text-sm border border-red-100">{{ $errors->first() }}</div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">NIP</label>
                    <input type="text" name="nip_nik" placeholder="Masukkan NIP" required
                        class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" placeholder="Masukkan Password" required
                        class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary outline-none transition">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary mr-2"> Ingat Saya
                    </label>
                    <a href="#" class="text-sm text-primary font-bold hover:underline">Lupa Password?</a>
                </div>

                <button type="submit" class="w-full py-3 bg-primary hover:bg-blue-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-200 dark:shadow-none">
                    Login Sekarang
                </button>
            </form>
        </div>

        <div class="hidden md:flex w-1/2 login-gradient p-12 flex-col items-center justify-center text-center text-white relative">
            <div class="floating">
                <img src="{{ asset('images/asset-hero.png') }}" alt="Asset Illustration" class="w-72 h-72 object-contain">
            </div>
            
            <div class="mt-12">
                <h3 class="text-2xl font-bold mb-4">Manajemen Aset Jadi Lebih Mudah</h3>
                <p class="text-blue-100 text-sm opacity-90 leading-relaxed px-10">
                    Pantau lokasi, kondisi, dan riwayat peminjaman dalam satu genggaman.
                </p>
            </div>
        </div>
    </div>

</body>
</html>