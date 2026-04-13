<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Wali Kelas Dashboard') — SIMAS</title>
    <meta name="description" content="Panel wali kelas SIMAS untuk mengelola absensi dan rekap kehadiran siswa.">

    {{-- ─── PWA Support ─────────────────────────────────────────────────────── --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3b82f6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SIMAS">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-manrope { font-family: 'Manrope', sans-serif; }
        /* Mobile menu transition */
        #mobile-menu-wk {
            transition: max-height 0.25s ease, opacity 0.25s ease;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }
        #mobile-menu-wk.open {
            max-height: 300px;
            opacity: 1;
        }
        /* Active nav underline */
        .nav-link-active {
            color: #00236f;
            font-weight: 700;
            border-bottom: 2px solid #00236f;
            padding-bottom: 2px;
        }
    </style>
</head>

<body class="bg-slate-100 antialiased">

{{-- ══════════════════════════════════════════════
     TOP NAVBAR — WALI KELAS
     ══════════════════════════════════════════════ --}}
<nav class="w-full bg-white/80 backdrop-blur-xl shadow-sm border-b border-gray-100 sticky top-0 z-50" role="navigation" aria-label="Top navigation wali kelas">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="flex items-center justify-between h-16">

            {{-- ── Kiri: Logo + Menu Desktop ──────────────── --}}
            <div class="flex items-center gap-8">
                {{-- Logo --}}
                <a href="{{ route('wali-kelas.dashboard') }}" class="flex items-center gap-2 font-manrope text-2xl font-extrabold tracking-tighter text-blue-900" aria-label="SIMAS Home">
                    <span class="material-symbols-outlined text-blue-900 text-3xl leading-none">school</span>
                    SIMAS
                </a>

                {{-- Menu Desktop --}}
                <div class="hidden md:flex items-center gap-6" role="menubar">
                    <a href="{{ route('wali-kelas.dashboard') }}"
                       id="nav-wk-dashboard"
                       class="text-sm font-medium transition-colors duration-200 pb-0.5 {{ request()->routeIs('wali-kelas.dashboard') ? 'nav-link-active' : 'text-slate-500 hover:text-[#00236f]' }}"
                       role="menuitem"
                       aria-current="{{ request()->routeIs('wali-kelas.dashboard') ? 'page' : 'false' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('wali-kelas.absen.create') }}"
                       id="nav-wk-absen"
                       class="text-sm font-medium transition-colors duration-200 pb-0.5 {{ request()->routeIs('wali-kelas.absen.*') ? 'nav-link-active' : 'text-slate-500 hover:text-[#00236f]' }}"
                       role="menuitem"
                       aria-current="{{ request()->routeIs('wali-kelas.absen.*') ? 'page' : 'false' }}">
                        Absensi
                    </a>
                    <a href="{{ route('wali-kelas.recap') }}"
                       id="nav-wk-recap"
                       class="text-sm font-medium transition-colors duration-200 pb-0.5 {{ request()->routeIs('wali-kelas.recap') ? 'nav-link-active' : 'text-slate-500 hover:text-[#00236f]' }}"
                       role="menuitem"
                       aria-current="{{ request()->routeIs('wali-kelas.recap') ? 'page' : 'false' }}">
                        Rekap
                    </a>
                </div>
            </div>

            {{-- ── Kanan: User Info + Logout Desktop ──────── --}}
            <div class="flex items-center gap-3 md:gap-4">
                {{-- User info --}}
                <div class="flex items-center gap-2">
                    <div class="flex flex-col items-end">
                        <span class="font-semibold text-gray-800 text-sm leading-tight">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="text-xs text-gray-500">
                            WALI KELAS
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="block md:hidden">
                    @csrf
                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" aria-label="Keluar" title="Keluar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                </form>

                {{-- Separator --}}
                <div class="hidden md:block w-px h-8 bg-slate-200"></div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="hidden md:inline">
                    @csrf
                    <button id="btn-wk-logout" type="submit"
                        class="flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-red-600 transition-colors duration-200"
                        title="Keluar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>

            {{-- ── Hamburger Mobile ────────────────────────── --}}
            <button id="btn-wk-mobile-toggle" type="button"
                class="hidden p-2 rounded-lg text-slate-500 hover:text-[#00236f] hover:bg-slate-100 transition-colors"
                aria-label="Buka/tutup menu" aria-expanded="false" aria-controls="mobile-menu-wk">
                <svg id="icon-wk-hamburger" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="icon-wk-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        </div>

        {{-- ── Mobile Menu ─────────────────────────────── --}}
        <div id="mobile-menu-wk" role="menu" aria-label="Menu mobile wali kelas" class="hidden">
            <div class="py-3 space-y-1 border-t border-slate-100">
                {{-- User info mobile --}}
                <div class="px-3 py-2 mb-2">
                    <p class="text-sm font-bold text-[#00236f] font-manrope">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">WALI KELAS</p>
                </div>

                <a href="{{ route('wali-kelas.dashboard') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('wali-kelas.dashboard') ? 'bg-[#00236f]/10 text-[#00236f] font-semibold' : 'text-slate-600 hover:bg-slate-100 hover:text-[#00236f]' }} transition-colors"
                   role="menuitem">
                    Dashboard
                </a>
                <a href="{{ route('wali-kelas.absen.create') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('wali-kelas.absen.*') ? 'bg-[#00236f]/10 text-[#00236f] font-semibold' : 'text-slate-600 hover:bg-slate-100 hover:text-[#00236f]' }} transition-colors"
                   role="menuitem">
                    Absensi
                </a>
                <a href="{{ route('wali-kelas.recap') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('wali-kelas.recap') ? 'bg-[#00236f]/10 text-[#00236f] font-semibold' : 'text-slate-600 hover:bg-slate-100 hover:text-[#00236f]' }} transition-colors"
                   role="menuitem">
                    Rekap
                </a>

                {{-- Logout mobile --}}
                <div class="pt-2 border-t border-slate-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 transition-colors"
                            role="menuitem">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</nav>

{{-- ══════════════════════════════════════════════
     MAIN CONTENT AREA
     ══════════════════════════════════════════════ --}}
<div class="min-h-screen bg-slate-100 flex flex-col">
    <main class="flex-1 w-full pb-24 md:pb-6" role="main" id="main-content-wk">
        <div class="w-full max-w-[1440px] mx-auto px-4 md:px-10 py-8">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="mb-6 flex items-start gap-3 px-5 py-4 rounded-xl bg-emerald-50 border border-emerald-100" role="alert" aria-live="polite">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 flex items-start gap-3 px-5 py-4 rounded-xl bg-red-50 border border-red-100" role="alert" aria-live="assertive">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-red-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
            @endif

            @yield('content')

        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white/60 border-t border-slate-200/50" role="contentinfo">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-10 py-4 flex items-center justify-between gap-4">
            <p class="text-xs text-slate-400">&copy; {{ date('Y') }} SIMAS — Sistem Manajemen Absensi Sekolah</p>
            <p class="text-xs text-slate-400">v1.0.0</p>
        </div>
    </footer>
</div>

{{-- Mobile menu toggle script --}}
<script>
(function () {
    const btn     = document.getElementById('btn-wk-mobile-toggle');
    const menu    = document.getElementById('mobile-menu-wk');
    const iconHam = document.getElementById('icon-wk-hamburger');
    const iconX   = document.getElementById('icon-wk-close');

    if (!btn || !menu) return;

    btn.addEventListener('click', function () {
        const isOpen = menu.classList.toggle('open');
        btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        iconHam.classList.toggle('hidden', isOpen);
        iconX.classList.toggle('hidden', !isOpen);
    });

    // Tutup saat klik di luar
    document.addEventListener('click', function (e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
            iconHam.classList.remove('hidden');
            iconX.classList.add('hidden');
        }
    });
})();
</script>

{{-- ── Bottom Navigation Mobile ────────────────── --}}
<nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-4 pb-6 pt-3 bg-white/90 backdrop-blur-2xl z-50 rounded-t-3xl border-t border-slate-200/20 shadow-[0_-12px_40px_rgba(0,35,111,0.08)]">
    <!-- Dashboard -->
    <a class="flex flex-col items-center justify-center {{ request()->routeIs('wali-kelas.dashboard') ? 'bg-gradient-to-br from-[#00236f] to-[#1e3a8a] text-white rounded-xl' : 'text-slate-400 hover:text-[#00236f]' }} py-2 px-4 transition-transform active:scale-95" href="{{ route('wali-kelas.dashboard') }}">
        <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
        </svg>
        <span class="uppercase tracking-wider text-[10px] font-semibold">Dashboard</span>
    </a>
    <!-- Absensi -->
    <a class="flex flex-col items-center justify-center {{ request()->routeIs('wali-kelas.absen.*') ? 'bg-gradient-to-br from-[#00236f] to-[#1e3a8a] text-white rounded-xl' : 'text-slate-400 hover:text-[#00236f]' }} py-2 px-4 transition-all active:scale-95" href="{{ route('wali-kelas.absen.create') }}">
        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
        </svg>
        <span class="uppercase tracking-wider text-[10px] font-semibold">Absensi</span>
    </a>
    <!-- Rekap -->
    <a class="flex flex-col items-center justify-center {{ request()->routeIs('wali-kelas.recap') ? 'bg-gradient-to-br from-[#00236f] to-[#1e3a8a] text-white rounded-xl' : 'text-slate-400 hover:text-[#00236f]' }} py-2 px-4 transition-all active:scale-95" href="{{ route('wali-kelas.recap') }}">
        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        <span class="uppercase tracking-wider text-[10px] font-semibold">Rekap</span>
    </a>
</nav>

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
