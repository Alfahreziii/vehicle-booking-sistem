<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — VBS Nikel Mining</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-slate-50 font-sans antialiased">

    <div class="min-h-screen flex">

        {{-- ── Kiri — Ilustrasi / Branding ── --}}
        <div class="hidden lg:flex lg:w-1/2 bg-slate-900 flex-col justify-between p-12 relative overflow-hidden">

            {{-- Background pattern --}}
            <div class="absolute inset-0 opacity-5">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            {{-- Decorative circles --}}
            <div class="absolute top-20 right-20 w-64 h-64 rounded-full bg-blue-600 opacity-10 blur-3xl"></div>
            <div class="absolute bottom-32 left-10 w-48 h-48 rounded-full bg-blue-400 opacity-10 blur-3xl"></div>

            {{-- Logo --}}
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-lg leading-none">VBS Nikel</p>
                        <p class="text-slate-400 text-xs mt-0.5">Vehicle Booking System</p>
                    </div>
                </div>
            </div>

            {{-- Main content --}}
            <div class="relative z-10">
                <h1 class="text-4xl font-semibold text-white leading-tight">
                    Kelola armada<br>
                    kendaraan<br>
                    <span class="text-blue-400">lebih efisien.</span>
                </h1>
                <p class="mt-4 text-slate-400 text-sm leading-relaxed max-w-xs">
                    Sistem pemesanan kendaraan terpadu untuk operasional tambang nikel
                    di seluruh wilayah Indonesia.
                </p>

                {{-- Feature pills --}}
                <div class="mt-8 flex flex-wrap gap-2">
                    @foreach (['Pemesanan otomatis', 'Approval berjenjang', 'Monitoring BBM', 'Laporan Excel'] as $feature)
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-xs text-slate-300">
                            <svg class="h-3 w-3 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $feature }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <div class="relative z-10">
                <p class="text-slate-600 text-xs">© {{ now()->year }} PT Nikel Mining Indonesia</p>
            </div>
        </div>

        {{-- ── Kanan — Form Login ── --}}
        <div class="flex flex-1 flex-col items-center justify-center px-6 py-12 lg:px-12">

            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-800">VBS Nikel Mining</p>
                    <p class="text-xs text-slate-400">Vehicle Booking System</p>
                </div>
            </div>

            <div class="w-full max-w-sm">

                {{-- Heading --}}
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-slate-800">Masuk ke akun Anda</h2>
                    <p class="text-sm text-slate-500 mt-1">Gunakan email dan password yang terdaftar</p>
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div
                        class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Alamat Email
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autofocus autocomplete="username" placeholder="nama@nikelmining.co.id"
                            class="w-full rounded-xl border px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                  {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:border-red-400' : 'border-slate-200 bg-white focus:border-blue-400' }}">
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-600">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-xs text-blue-600 hover:text-blue-800 transition-colors">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 pr-10 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                      {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:border-red-400' : 'border-slate-200 bg-white focus:border-blue-400' }}">
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg x-show="!show" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center gap-2">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="remember_me" class="text-sm text-slate-600 cursor-pointer">
                            Ingat saya selama 30 hari
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 active:bg-blue-800 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/40 mt-2">
                        Masuk ke Sistem
                    </button>
                </form>

                {{-- Divider + info --}}
                <div class="mt-8 border-t border-slate-100 pt-6">
                    <p class="text-xs text-slate-400 text-center">
                        Butuh akun? Hubungi administrator sistem Anda.
                    </p>
                </div>

                {{-- Quick login hint (development only) --}}
                @if (app()->isLocal())
                    <div class="mt-4 rounded-xl border border-dashed border-slate-200 bg-slate-50 p-4"
                        x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex w-full items-center justify-between text-xs font-medium text-slate-500 hover:text-slate-700">
                            <span>Akun demo (dev only)</span>
                            <svg class="h-3.5 w-3.5 transition-transform" :class="open ? 'rotate-180' : ''"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="mt-3 space-y-2">
                            @foreach ([['label' => 'Admin', 'email' => 'admin.pool@nikelmining.co.id', 'color' => 'blue'], ['label' => 'Approver', 'email' => 'kabag.ops@nikelmining.co.id', 'color' => 'amber'], ['label' => 'Driver', 'email' => 'driver1@nikelmining.co.id', 'color' => 'emerald']] as $demo)
                                <button type="button"
                                    onclick="document.getElementById('email').value='{{ $demo['email'] }}'; document.getElementById('password').value='password';"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-left text-xs hover:bg-slate-50 transition-colors flex items-center justify-between">
                                    <span class="font-medium text-slate-700">{{ $demo['label'] }}</span>
                                    <span class="text-slate-400 font-mono">{{ $demo['email'] }}</span>
                                </button>
                            @endforeach
                            <p class="text-center text-xs text-slate-400">password: <span
                                    class="font-mono">password</span></p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

</body>

</html>
