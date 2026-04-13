<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ─── PWA Support ─────────────────────────────────────────────────────── --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3b82f6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SIMAS">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <title>{{ config('app.name', 'SIMAS') }} — {{ isset($title) ? $title : 'Sistem Manajemen Absensi Sekolah' }}</title>
    <meta name="description"
        content="SIMAS adalah platform administrasi sekolah premium untuk mengelola kehadiran, kelas, dan data akademik secara efisien.">

    {{-- ─── Google Fonts: Dual-Typeface System ─────────────────────────────── --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- ─── Vite Assets ─────────────────────────────────────────────────────── --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /*
         * SCHOLASTIC EDITORIAL — Foundation Styles
         * These inline styles handle tokens that require CSS variables or
         * features that are not easily purged by Tailwind (e.g., glass effect,
         * initial layout paint). All component-level styles should use
         * Tailwind utility classes.
         */

        /* Glassmorphism: surface-container-lowest @ 80% opacity + 24px blur */
        .glass-nav {
            background-color: rgba(255, 255, 255, 0.80);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        /* Academic Sidebar scrollbar — invisible but functional */
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

        /* Gradient CTA — primary to primary_container @ 135deg */
        .btn-primary-gradient {
            background: linear-gradient(135deg, #00236f, #1e3a8a);
        }

        /* Subtle sidebar item transition */
        .sidebar-link {
            transition: background-color 180ms ease, color 180ms ease;
        }

        /* Ghost Border fallback at 20% opacity for accessibility */
        .ghost-border {
            outline: 1px solid rgba(195, 199, 207, 0.20);
        }
    </style>
</head>

{{--
BODY:
• Background: surface (#f8f9fb) — Base Layer of the tonal hierarchy
• Text: on_surface (#191c1e) — Never pure black
• Font: Inter (body workhorse)
• Antialiased for premium rendering
--}}

<body class="font-sans antialiased bg-surface text-on_surface">

    {{-- ════════════════════════════════════════════════════════════════════════════
    MASTER LAYOUT — "The Academic Curator"
    Responsive two-column layout:
    Left → Academic Sidebar (static, solid primary)
    Right → Top Nav (glass) + Content (generous whitespace)
    ════════════════════════════════════════════════════════════════════════════ --}}
    <div class="flex h-screen overflow-hidden">

        {{-- ─── ACADEMIC SIDEBAR ──────────────────────────────────────────────── --}}
        {{--
        Solid primary (#00236f) command-center sidebar.
        Inactive icons use on_primary_container (#90a8ff) for legibility.
        NO border separators — depth via solid color contrast against surface.
        --}}
        <aside id="academic-sidebar"
            class="w-72 bg-primary flex-shrink-0 flex flex-col overflow-hidden transition-all duration-300 ease-in-out hidden lg:flex"
            aria-label="Sidebar navigasi utama">
            {{-- ── Sidebar Header / Brand ───────────────────────────────────────── --}}
            <div class="px-8 py-8 flex items-center gap-4">
                {{-- Logo mark — gradient circle --}}
                <div
                    class="w-11 h-11 rounded-xl btn-primary-gradient flex items-center justify-center shadow-ambient flex-shrink-0">
                    <svg class="w-6 h-6 text-on_primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    {{-- Manrope for brand headline --}}
                    <p class="font-display text-xl font-bold text-on_primary tracking-tight leading-none">SIMAS</p>
                    <p class="text-label-sm text-on_primary_container mt-0.5">Sistem Informasi Sekolah</p>
                </div>
            </div>

            {{-- ── User Profile Card ────────────────────────────────────────────── --}}
            {{-- Uses surface-container-lowest at low opacity to create tonal lift without a border --}}
            <div class="mx-4 mb-6 px-4 py-4 rounded-xl" style="background-color: rgba(255,255,255,0.08);">
                <div class="flex items-center gap-3">
                    {{-- Avatar — initials --}}
                    <div class="w-10 h-10 rounded-xl btn-primary-gradient flex items-center justify-center flex-shrink-0"
                        aria-hidden="true">
                        @auth
                            <span class="text-label-lgfont-semibold text-on_primary uppercase">
                                {{ substr(auth()->user()->name, 0, 2) }}
                            </span>
                        @endauth
                    </div>
                    <div class="min-w-0 flex-1">
                        @auth
                            <p class="text-label-md text-on_primary font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-label-sm text-on_primary_container">
                                {{ ucfirst(auth()->user()->role ?? 'Pengguna') }}
                            </p>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- ── Navigation Menu ─────────────────────────────────────────────── --}}
            <nav class="flex-1 px-4 space-y-1 overflow-y-auto sidebar-scroll" aria-label="Menu navigasi">

                {{-- Section Label --}}
                <p
                    class="px-4 mb-3 text-label-sm font-semibold uppercase tracking-widest text-on_primary_container opacity-60">
                    Menu Utama
                </p>

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" id="nav-dashboard" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
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
                <a href="{{ route('classrooms.index') }}" id="nav-classrooms" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
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
                <a href="{{ route('users.index') }}" id="nav-users" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
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
                <a href="{{ route('students.index') }}" id="nav-students" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
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

            </nav>

            {{-- ── Sidebar Footer / Logout ─────────────────────────────────────── --}}
            {{-- Tonal separation: subtle white overlay replaces a border --}}
            <div class="px-4 py-6" style="background-color: rgba(0,0,0,0.15);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button id="btn-logout" type="submit" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl w-full
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
        {{-- ─── END ACADEMIC SIDEBAR ──────────────────────────────────────────── --}}


        {{-- ─── MAIN CONTENT COLUMN ───────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            {{-- ── TOP NAVIGATION BAR (Glassmorphism) ──────────────────────────── --}}
            {{--
            Glass Rule: surface-container-lowest @ 80% opacity + 24px backdrop-blur.
            NO border — the tonal shift from sidebar (primary) to glass (white 80%)
            defines the separation. This is the "No-Line Rule" in action.
            --}}
            <header id="top-nav" class="glass-nav sticky top-0 z-40 flex-shrink-0" role="banner">
                <div class="flex items-center justify-between px-8 py-4 gap-4">

                    {{-- Left: Mobile menu toggle + Page breadcrumb --}}
                    <div class="flex items-center gap-4 min-w-0">
                        {{-- Mobile hamburger — visible only on smaller screens --}}
                        <button id="btn-sidebar-toggle" type="button" class="lg:hidden p-2 rounded-xl bg-surface-container-low text-on_surface_variant
                               hover:bg-surface-container transition-colors" aria-label="Buka/tutup sidebar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        {{-- Page title (injected per-view via @yield) --}}
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

                    {{-- Right: Utility actions --}}
                    <div class="flex items-center gap-3 flex-shrink-0">

                        {{-- Date & Time — hidden on mobile --}}
                        <div class="hidden md:flex flex-col items-end">
                            <span class="text-label-md text-on_surface font-semibold">{{ date('d F Y') }}</span>
                            <span class="text-label-sm text-on_surface_variant">{{ date('H:i') }} WIB</span>
                        </div>

                        {{-- Notification Bell --}}
                        <button id="btn-notifications" type="button" class="relative p-2.5 rounded-xl bg-surface-container-low text-on_surface_variant
                               hover:bg-surface-container hover:text-on_surface transition-colors"
                            aria-label="Notifikasi">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            {{-- Accent dot — uses tertiary for "urgent" sophistication --}}
                            <span class="absolute top-2 right-2 w-2 h-2 rounded-full" style="background-color: #4b1c00;"
                                aria-hidden="true"></span>
                        </button>

                        {{-- User Avatar (Top Nav) --}}
                        @auth
                            <div class="w-9 h-9 rounded-xl btn-primary-gradient flex items-center justify-center flex-shrink-0 cursor-pointer"
                                title="{{ auth()->user()->name }}" aria-label="Profil pengguna: {{ auth()->user()->name }}">
                                <span class="text-label-sm font-bold text-on_primary uppercase">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </span>
                            </div>
                        @endauth
                    </div>

                </div>
            </header>
            {{-- ── END TOP NAVIGATION BAR ───────────────────────────────────────── --}}


            {{-- ── MAIN CONTENT AREA ───────────────────────────────────────────── --}}
            {{--
            Background: surface (#f8f9fb) — matches body, the "Base Layer."
            Content blocks (cards) should use surface-container-lowest (#ffffff)
            to create a natural "lift" via Tonal Layering, without shadows.

            Generous top padding (p-20/p-24) for the "gallery" feel per the docs.
            --}}
            <main id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto bg-surface" role="main">
                <div class="w-full max-w-[1440px] mx-auto px-4 md:px-10 py-8">

                    {{-- ─── Flash Messages ───────────────────────────────────────── --}}
                    {{-- Success: secondary_container for "Hadir" / positive feedback --}}
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

                    {{-- Error: tertiary (#4b1c00) — sophisticated alternative to bright red --}}
                    @if(session('error'))
                        <div class="mb-8 flex items-start gap-3 px-5 py-4 rounded-xl"
                            style="background-color: rgba(75, 28, 0, 0.08);" role="alert" aria-live="assertive">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-tertiary" fill="currentColor" viewBox="0 0 20 20"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-body-md font-medium text-tertiary">{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- ─── Page Content Slot ────────────────────────────────────── --}}
                    @yield('content')

                </div>
            </main>
            {{-- ── END MAIN CONTENT AREA ────────────────────────────────────────── --}}


            {{-- ── FOOTER ──────────────────────────────────────────────────────── --}}
            {{--
            No border-top. The footer uses surface-container-low (#f3f4f6)
            against the surface (#f8f9fb) body — tonal separation, no line.
            --}}
            <footer class="flex-shrink-0 bg-surface-container-low" role="contentinfo">
                <div class="px-8 py-4 lg:px-12 flex items-center justify-between gap-4">
                    <p class="text-label-sm text-on_surface_variant">
                        &copy; {{ date('Y') }} SIMAS — Sistem Manajemen Absensi Sekolah
                    </p>
                    <p class="text-label-sm text-on_surface_variant">v1.0.0</p>
                </div>
            </footer>

        </div>
        {{-- ─── END MAIN CONTENT COLUMN ───────────────────────────────────────── --}}

    </div>

    {{-- ─── Mobile Sidebar Overlay ─────────────────────────────────────────────── --}}
    {{-- Glassmorphism overlay for mobile sidebar toggle --}}
    <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-primary/40 backdrop-blur-sm hidden lg:hidden"
        aria-hidden="true"></div>

    {{-- ─── Scripts ──────────────────────────────────────────────────────────────── --}}
    <script>
        // Mobile sidebar toggle — pure vanilla, no dependencies
        (function () {
            const sidebar = document.getElementById('academic-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleBtn = document.getElementById('btn-sidebar-toggle');

            function openSidebar() {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
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