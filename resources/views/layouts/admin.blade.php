<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>@yield('title', 'Admin Dashboard') — SIMAS</title>
    <meta name="description" content="Panel administrator SIMAS untuk mengelola data sekolah, kelas, guru, dan siswa.">

    {{-- ─── PWA Support ─────────────────────────────────────────────────────── --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3b82f6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SIMAS">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    {{-- ─── Google Fonts: Manrope (display) + Inter (body) ─────────────────── --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Glassmorphism: surface-container-lowest @ 80% + 24px blur */
        .glass-nav {
            background-color: rgba(255, 255, 255, 0.80);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        /* Primary → primary_container gradient for CTAs & brand marks */
        .btn-primary-gradient {
            background: linear-gradient(135deg, #00236f, #1e3a8a);
        }

        /* Sidebar scrollbar — invisible but functional */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(144, 168, 255, 0.25);
            border-radius: 4px;
        }

        /* Sidebar nav item micro-transition */
        .sidebar-link {
            transition: background-color 180ms ease, color 180ms ease;
        }
    </style>
</head>

{{-- Body: surface bg, on_surface text, Inter font — never pure black --}}

<body class="font-sans antialiased bg-surface text-on_surface">

    <div class="flex h-screen overflow-hidden">

        {{-- ═══════════════════════════════════════════════════════════════════════
        ACADEMIC SIDEBAR — ADMIN
        Solid primary (#00236f) command-center. No border separators.
        Depth via tonal contrast: primary vs surface (body bg).
        ═══════════════════════════════════════════════════════════════════════ --}}
        <aside id="sidebar" class="fixed md:static inset-y-0 left-0 w-72 bg-primary flex-shrink-0 flex flex-col overflow-hidden
                   transform transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0 z-50"
            aria-label="Sidebar navigasi admin">
            {{-- Brand Header --}}
            <div class="relative px-8 pt-8 pb-10">
                <button id="close-sidebar-btn"
                    class="md:hidden text-white/70 hover:text-white absolute top-8 right-6 transition-colors"
                    aria-label="Tutup menu">
                    <span class="material-symbols-outlined">close</span>
                </button>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-white text-4xl leading-none">school</span>
                    <p class="text-3xl font-bold text-white tracking-widest leading-none">SIMAS</p>
                </div>
                <p class="text-[10px] text-[#90a8ff] tracking-widest mt-2 opacity-70 uppercase leading-snug">
                    Sistem Manajemen Absensi Sekolah
                </p>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 space-y-1 overflow-y-auto sidebar-scroll" aria-label="Menu admin">
                <p
                    class="px-4 mb-3 text-label-sm font-semibold uppercase tracking-widest text-on_primary_container opacity-60">
                    Menu Utama
                </p>

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" id="nav-admin-dashboard" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
                      {{ request()->routeIs('admin.dashboard')
    ? 'bg-white/20 text-on_primary'
    : 'text-on_primary_container hover:bg-white/10 hover:text-on_primary' }}"
                    aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-label-md font-medium">Dashboard</span>
                </a>

                {{-- Kelola Kelas --}}
                <a href="{{ route('classrooms.index') }}" id="nav-admin-classrooms" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
                      {{ request()->routeIs('classrooms.*')
    ? 'bg-white/20 text-on_primary'
    : 'text-on_primary_container hover:bg-white/10 hover:text-on_primary' }}"
                    aria-current="{{ request()->routeIs('classrooms.*') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="text-label-md font-medium">Kelola Kelas</span>
                </a>

                {{-- Kelola Guru --}}
                <a href="{{ route('users.index') }}" id="nav-admin-users" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
                      {{ request()->routeIs('users.*')
    ? 'bg-white/20 text-on_primary'
    : 'text-on_primary_container hover:bg-white/10 hover:text-on_primary' }}"
                    aria-current="{{ request()->routeIs('users.*') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-label-md font-medium">Kelola Guru</span>
                </a>

                {{-- Kelola Siswa --}}
                <a href="{{ route('students.index') }}" id="nav-admin-students" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
                      {{ request()->routeIs('students.*')
    ? 'bg-white/20 text-on_primary'
    : 'text-on_primary_container hover:bg-white/10 hover:text-on_primary' }}"
                    aria-current="{{ request()->routeIs('students.*') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-label-md font-medium">Kelola Siswa</span>
                </a>

                {{-- Mutasi Kelas --}}
                <a href="{{ route('admin.mutasi.index') }}" id="nav-admin-mutasi" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
                      {{ request()->routeIs('admin.mutasi.*')
    ? 'bg-white/20 text-on_primary'
    : 'text-on_primary_container hover:bg-white/10 hover:text-on_primary' }}"
                    aria-current="{{ request()->routeIs('admin.mutasi.*') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span class="text-label-md font-medium">Mutasi Kelas</span>
                </a>

                {{-- Pengaturan Web --}}
                <a href="{{ route('admin.settings.index') }}" id="nav-admin-settings" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
                      {{ request()->routeIs('admin.settings.*')
    ? 'bg-white/20 text-on_primary'
    : 'text-on_primary_container hover:bg-white/10 hover:text-on_primary' }}"
                    aria-current="{{ request()->routeIs('admin.settings.*') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-label-md font-medium">Pengaturan Web</span>
                </a>
            </nav>

            {{-- Logout — tonal dark overlay, no border --}}
            <div class="px-4 py-6" style="background-color: rgba(0,0,0,0.15);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button id="btn-admin-logout" type="submit" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl w-full
                           text-on_primary_container hover:bg-red-600/30 hover:text-on_primary">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-label-md font-medium">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
        {{-- END ACADEMIC SIDEBAR --}}


        {{-- ─── MAIN CONTENT COLUMN ───────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            {{-- TOP NAV — Glassmorphism (white 80% + blur 24px). No border. --}}
            <header id="top-nav-admin" class="glass-nav sticky top-0 z-40 flex-shrink-0" role="banner">
                <div class="flex items-center justify-between px-8 py-4 gap-4">

                    {{-- Left: hamburger (mobile) + page title --}}
                    <div class="flex items-center gap-4 min-w-0">

                        {{-- Hamburger button — hanya muncul di mobile --}}
                        <button id="mobile-menu-btn" class="md:hidden text-primary focus:outline-none flex-shrink-0"
                            aria-label="Buka menu">
                            <span class="material-symbols-outlined text-2xl leading-none">menu</span>
                        </button>

                        <div class="min-w-0">
                            <h1 class="font-display text-headline-sm font-bold text-on_surface leading-tight truncate">
                                @yield('page-title', 'Dashboard')
                            </h1>
                            @hasSection('page-description')
                                <p class="text-label-sm text-on_surface_variant mt-0.5 truncate">
                                    @yield('page-description')
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Right: user identity --}}
                    <div class="flex items-center flex-shrink-0">
                        <div class="flex flex-col items-end">
                            <span class="font-display text-sm font-bold text-primary leading-tight">
                                {{ auth()->user()->name }}
                            </span>
                            <span class="text-[10px] text-on_surface_variant uppercase tracking-widest mt-0.5">
                                Super Administrator
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- MAIN CONTENT — generous gallery spacing --}}
            <main id="main-content-admin" class="flex-1 overflow-x-hidden overflow-y-auto bg-surface" role="main">
                <div class="px-8 py-10 lg:px-12 lg:py-12 xl:px-16 xl:py-16 max-w-screen-2xl mx-auto">

                    {{-- Flash: success → secondary_container (Hadir chip color) --}}
                    @if(session('success'))
                        <div class="mb-8 flex items-start gap-3 px-5 py-4 rounded-xl bg-secondary_container" role="alert"
                            aria-live="polite">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-on_secondary_container" fill="currentColor"
                                viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-body-md font-medium text-on_secondary_container">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Flash: error → tertiary (#4b1c00) tonal bg --}}
                    @if(session('error'))
                        <div class="mb-8 flex items-start gap-3 px-5 py-4 rounded-xl"
                            style="background-color: rgba(75,28,0,0.08);" role="alert" aria-live="assertive">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-tertiary" fill="currentColor" viewBox="0 0 20 20"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-body-md font-medium text-tertiary">{{ session('error') }}</p>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            {{-- FOOTER — surface-container-low tones off from surface. No border. --}}
            <footer class="flex-shrink-0 bg-surface-container-low" role="contentinfo">
                <div class="px-8 py-4 lg:px-12 flex items-center justify-between gap-4">
                    <p class="text-label-sm text-on_surface_variant">&copy; {{ date('Y') }} SIMAS — Sistem
                        Manajemen Absensi Sekolah</p>
                    <p class="text-label-sm text-on_surface_variant">v1.0.0</p>
                </div>
            </footer>
        </div>
        {{-- END MAIN CONTENT COLUMN --}}

    </div>

    {{-- Mobile backdrop overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm hidden" aria-hidden="true"></div>

    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const menuBtn = document.getElementById('mobile-menu-btn');
            const closeBtn = document.getElementById('close-sidebar-btn');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            if (menuBtn) menuBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
        })();
    </script>

    @stack('scripts')

    {{-- ─── PWA Service Worker Registration ─────────────────────────────────── --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js')
                    .then(function (reg) { console.log('[PWA] SW registered:', reg.scope); })
                    .catch(function (err) { console.warn('[PWA] SW registration failed:', err); });
            });
        }
    </script>
</body>

</html>