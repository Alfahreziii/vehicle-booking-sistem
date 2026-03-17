{{-- resources/views/layouts/sidebar.blade.php --}}

<aside
    :class="[
        'fixed inset-y-0 left-0 z-30 flex flex-col bg-slate-900 transition-all duration-300 ease-in-out lg:relative lg:translate-x-0',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        sidebarCollapsed ? 'w-16' : 'w-64'
    ]">

    {{-- Logo --}}
    <div class="flex h-16 items-center justify-between px-4 border-b border-slate-800">
        <div x-show="!sidebarCollapsed" class="flex items-center gap-2 overflow-hidden">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-600">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-white">VBS Nikel</p>
                <p class="truncate text-xs text-slate-400">Vehicle Booking System</p>
            </div>
        </div>

        <div x-show="sidebarCollapsed" class="mx-auto">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
        </div>

        {{-- Collapse button (desktop only) --}}
        <button @click="sidebarCollapsed = !sidebarCollapsed"
            class="hidden lg:flex h-6 w-6 items-center justify-center rounded text-slate-400 hover:text-white transition-colors">
            <svg class="h-4 w-4 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    {{-- User Info --}}
    <div x-show="!sidebarCollapsed" class="mx-3 mt-4 rounded-xl bg-slate-800 px-3 py-3">
        <div class="flex items-center gap-3">
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-slate-400 capitalize">
                    {{ auth()->user()->getRoleNames()->first() ?? 'user' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1">

        {{-- Label section --}}
        <div x-show="!sidebarCollapsed" class="px-3 pb-1">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Menu Utama</p>
        </div>

        @php
            $navItems = [
                [
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' =>
                        'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                    'roles' => ['admin', 'approver', 'driver', 'viewer'],
                ],
                [
                    'label' => 'Pemesanan',
                    'route' => 'admin.bookings.index',
                    'icon' =>
                        'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                    'roles' => ['admin'],
                ],
                [
                    'label' => 'Persetujuan',
                    'route' => 'approvals.index',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'roles' => ['approver', 'admin'],
                    'badge' => true,
                ],
                [
                    'label' => 'Kendaraan',
                    'route' => 'admin.vehicles.index',
                    'icon' => 'M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 5h2l2 7h9l2-6H6',
                    'roles' => ['admin'],
                ],
                [
                    'label' => 'Driver',
                    'route' => 'admin.drivers.index',
                    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                    'roles' => ['admin'],
                ],
                [
                    'label' => 'Pengguna',
                    'route' => 'admin.users.index',
                    'icon' =>
                        'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                    'roles' => ['admin'],
                ],
            ];
        @endphp

        @foreach ($navItems as $item)
            @php
                $userRoles = auth()->user()->getRoleNames()->toArray();
                $hasAccess = count(array_intersect($item['roles'], $userRoles)) > 0;
                $isActive = request()->routeIs($item['route'] . '*');
            @endphp

            @if ($hasAccess)
                <a href="{{ route($item['route']) }}"
                    class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150
                      {{ $isActive ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                    :title="sidebarCollapsed ? '{{ $item['label'] }}' : ''">

                    <svg class="h-5 w-5 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                    </svg>

                    <span x-show="!sidebarCollapsed" class="flex-1 truncate">{{ $item['label'] }}</span>

                    {{-- Badge pending approvals --}}
                    @if (isset($item['badge']) && $item['badge'] && !$isActive)
                        @php
                            $pendingCount = \App\Models\BookingApproval::where('approver_id', auth()->id())
                                ->where('status', 'waiting')
                                ->count();
                        @endphp
                        @if ($pendingCount > 0)
                            <span x-show="!sidebarCollapsed"
                                class="ml-auto flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-xs font-semibold text-white">
                                {{ $pendingCount > 99 ? '99+' : $pendingCount }}
                            </span>
                        @endif
                    @endif
                </a>
            @endif
        @endforeach

        {{-- Divider --}}
        <div class="my-3 border-t border-slate-800"></div>

        <div x-show="!sidebarCollapsed" class="px-3 pb-1">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Laporan</p>
        </div>

        @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('viewer'))
            <a href="{{ route('admin.reports.index') }}"
                class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('admin.reports*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                :title="sidebarCollapsed ? 'Laporan' : ''">
                <svg class="h-5 w-5 shrink-0 text-slate-500 group-hover:text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="!sidebarCollapsed">Laporan</span>
            </a>
        @endif
    </nav>

    {{-- Logout --}}
    <div class="border-t border-slate-800 p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-400 transition-all hover:bg-red-600/10 hover:text-red-400"
                :title="sidebarCollapsed ? 'Logout' : ''">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span x-show="!sidebarCollapsed">Keluar</span>
            </button>
        </form>
    </div>
</aside>
