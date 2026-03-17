{{-- resources/views/layouts/navbar.blade.php --}}

<header
    class="flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-6 shadow-sm">

    {{-- Hamburger (mobile) --}}
    <button @click="sidebarOpen = !sidebarOpen"
        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    {{-- Page Title --}}
    <div class="hidden lg:block">
        <h1 class="text-base font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
    </div>

    {{-- Right section --}}
    <div class="flex items-center gap-2">

        {{-- Notifikasi --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.outside="open = false"
                class="relative flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                @if ($unreadCount > 0)
                    <span
                        class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>

            {{-- Dropdown notifikasi --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 top-11 z-50 w-80 rounded-xl border border-slate-200 bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                    <p class="text-sm font-semibold text-slate-800">Notifikasi</p>
                    @if ($unreadCount > 0)
                        <form method="POST" action="{{ route('notifications.read-all') }}">
                            @csrf
                            <button type="submit" class="text-xs text-blue-600 hover:underline">Tandai semua
                                dibaca</button>
                        </form>
                    @endif
                </div>
                <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                    @forelse(auth()->user()->notifications()->take(8)->get() as $notif)
                        <a href="{{ $notif->data['url'] ?? '#' }}"
                            class="flex gap-3 px-4 py-3 hover:bg-slate-50 transition-colors {{ $notif->read_at ? 'opacity-60' : '' }}">
                            <div
                                class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full
                                    {{ $notif->read_at ? 'bg-slate-100' : 'bg-blue-100' }}">
                                <svg class="h-4 w-4 {{ $notif->read_at ? 'text-slate-400' : 'text-blue-600' }}"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-700 leading-relaxed">{{ $notif->data['message'] ?? '' }}
                                </p>
                                <p class="mt-0.5 text-xs text-slate-400">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            @if (!$notif->read_at)
                                <div class="mt-2 h-2 w-2 shrink-0 rounded-full bg-blue-500"></div>
                            @endif
                        </a>
                    @empty
                        <div class="px-4 py-8 text-center text-sm text-slate-400">
                            Tidak ada notifikasi
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- User dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.outside="open = false"
                class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-slate-100 transition-colors">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="hidden text-left md:block">
                    <p class="text-sm font-medium text-slate-700">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->getRoleNames()->first() }}</p>
                </div>
                <svg class="h-4 w-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 top-11 z-50 w-48 rounded-xl border border-slate-200 bg-white py-1 shadow-xl">
                <div class="border-b border-slate-100 px-4 py-2">
                    <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
                </div>
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil Saya
                </a>
                <div class="border-t border-slate-100 mt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
