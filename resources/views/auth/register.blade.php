<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - NEXORA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased">

    <div class="min-h-screen flex flex-col md:flex-row">

        <div class="hidden md:flex md:w-1/2 bg-gradient-to-tr from-blue-700 via-indigo-700 to-violet-800 p-12 flex-col justify-between text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 800 800">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div class="relative z-10">
                <div class="flex items-center space-x-2 text-2xl font-black tracking-wider">
                    <span class="bg-white text-blue-700 px-3 py-1 rounded-lg shadow-md">N</span>
                    <span>NEXORA</span>
                </div>
            </div>

            <div class="relative z-10 max-w-md my-auto space-y-4">
                <h1 class="text-4xl font-extrabold leading-tight tracking-tight">Bergabunglah sebagai Surveyor</h1>
                <p class="text-indigo-100 text-lg leading-relaxed">
                    Daftarkan akun Anda untuk mulai membuat proyek, mengelola form survei, dan menganalisis data responden dengan lebih cepat dan terstruktur.
                </p>
            </div>

            <div class="relative z-10 text-sm text-indigo-200">
                &copy; {{ date('Y') }} NEXORA Platform. All rights reserved.
            </div>
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 sm:p-12 md:p-16 bg-white">
            <div class="w-full max-w-md space-y-8">

                <div class="text-center md:text-left">
                    <div class="md:hidden inline-flex items-center space-x-2 text-2xl font-black tracking-wider text-blue-700 mb-6">
                        <span class="bg-blue-700 text-white px-3 py-1 rounded-lg">N</span>
                        <span>NEXORA</span>
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Buat Akun Baru</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Lengkapi data di bawah ini untuk mendapatkan akses ke Dashboard.
                    </p>
                </div>

                <form class="mt-8 space-y-5" method="POST" action="{{ route('register') }}">
                    @csrf <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}" autofocus
                            class="block w-full px-4 py-3 bg-gray-50 border @error('name') border-red-400 focus:ring-red-500 focus:border-red-500 @else border-gray-200 focus:ring-blue-500 focus:border-blue-500 @enderror rounded-xl shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 transition duration-200 text-sm"
                            placeholder="John Doe">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full px-4 py-3 bg-gray-50 border @error('email') border-red-400 focus:ring-red-500 focus:border-red-500 @else border-gray-200 focus:ring-blue-500 focus:border-blue-500 @enderror rounded-xl shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 transition duration-200 text-sm"
                            placeholder="nama@lembaga.com">
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Kata Sandi</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                            class="block w-full px-4 py-3 bg-gray-50 border @error('password') border-red-400 focus:ring-red-500 focus:border-red-500 @else border-gray-200 focus:ring-blue-500 focus:border-blue-500 @enderror rounded-xl shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 transition duration-200 text-sm"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                            class="block w-full px-4 py-3 bg-gray-50 border @error('password_confirmation') border-red-400 focus:ring-red-500 focus:border-red-500 @else border-gray-200 focus:ring-blue-500 focus:border-blue-500 @enderror rounded-xl shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 transition duration-200 text-sm"
                            placeholder="Ulangi kata sandi Anda">
                        @error('password_confirmation')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md hover:shadow-lg transition duration-200 transform active:scale-[0.98]">
                            Daftar Akun Sekarang
                        </button>
                    </div>
                </form>

                <p class="text-center text-sm text-gray-600 mt-8">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-500 transition duration-150">
                        Masuk di sini
                    </a>
                </p>

            </div>
        </div>

    </div>

</body>
</html>
