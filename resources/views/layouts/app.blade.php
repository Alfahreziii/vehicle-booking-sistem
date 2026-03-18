<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — VBS Nikel Mining</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="h-full font-sans antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    <div class="flex h-screen overflow-hidden">

        {{-- ── Sidebar Overlay (mobile) ── --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black/50 lg:hidden">
        </div>

        {{-- ── Sidebar ── --}}
        @include('layouts.sidebar')

        {{-- ── Main Content ── --}}
        <div class="flex flex-1 flex-col min-w-0 overflow-hidden">

            {{-- Navbar --}}
            @include('layouts.navbar')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 lg:p-6">

                {{-- Breadcrumb --}}
                @hasSection('breadcrumb')
                    <div class="mb-4">
                        @yield('breadcrumb')
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
