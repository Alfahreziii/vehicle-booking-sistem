{{-- resources/views/auth/forgot-password.blade.php --}}

<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — VBS Nikel Mining</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-slate-50 font-sans antialiased flex items-center justify-center p-6">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="flex items-center justify-center gap-2 mb-8">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <span class="font-semibold text-slate-800">VBS Nikel Mining</span>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-800 mb-1">Lupa Password</h2>
            <p class="text-sm text-slate-500 mb-6">
                Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
            </p>

            @if (session('status'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-600 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="nama@nikelmining.co.id"
                        class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20
                              {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                    Kirim Link Reset Password
                </button>
            </form>

            <a href="{{ route('login') }}"
                class="mt-4 flex items-center justify-center gap-1.5 text-xs text-slate-500 hover:text-slate-700 transition-colors">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke login
            </a>
        </div>
    </div>

</body>

</html>
