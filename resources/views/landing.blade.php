@extends('layouts.landing')

@php
    $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
@endphp

{{-- ─── Per-page styles ──────────────────────────────────────────────────────── --}}
@push('styles')
<style>
    /* ─── Material Symbols rendering ────────────────────────────────────── */
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    /* ─── CTA / Header gradient button ──────────────────────────────────── */
    .editorial-gradient {
        background: linear-gradient(135deg, #00236f 0%, #1e3a8a 100%);
    }

    /* ─── Global scrollbar — thin, themed, visible ───────────────────────
       Replaces the hidden scrollbar approach. Thin (6px) with surface
       tokens so it blends with the editorial aesthetic. */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #f3f4f6;   /* surface-container-low */
    }
    ::-webkit-scrollbar-thumb {
        background: #c3c7cf;   /* outline-variant */
        border-radius: 999px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #43474e;   /* on_surface_variant */
    }
    /* Firefox */
    * {
        scrollbar-width: thin;
        scrollbar-color: #c3c7cf #f3f4f6;
    }

    /* ─── Filter-chips strip (horizontal scroll on mobile) — keeps the
       thin global scrollbar but hides it for this specific element only ── */
    .chips-strip::-webkit-scrollbar { display: none; }
    .chips-strip { -ms-overflow-style: none; scrollbar-width: none; }

    /* ─── Misc ───────────────────────────────────────────────────────────── */
    body { -webkit-tap-highlight-color: transparent; }

    /* Nav active-state underline — toggled by JS Intersection Observer */
    .nav-link-active {
        color: #00236f !important;           /* primary */
        border-bottom: 2px solid #00236f;
        padding-bottom: 4px;
    }
</style>
@endpush

@section('content')

    {{-- ===== TOP APP BAR ===== --}}
    <header id="main-header" x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50"
            style="background-color: rgba(248,249,251,0.80); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);">
        <div class="flex justify-between items-center h-14 md:h-16 px-6 md:px-10">
            {{-- Brand: toga icon + SIMAS text --}}
            <a href="#beranda" class="flex items-center gap-x-2">
                <span class="material-symbols-outlined text-primary text-3xl leading-none" aria-hidden="true">school</span>
                <span class="font-display font-extrabold text-primary tracking-tighter text-lg md:text-xl">SIMAS</span>
            </a>

            {{-- Desktop navigation — active state managed by Intersection Observer --}}
            <nav class="hidden md:flex items-center space-x-8 font-sans text-sm font-medium" aria-label="Navigasi utama">
                <a id="nav-beranda" href="#beranda"
                   class="nav-link text-on_surface_variant hover:text-primary transition-colors">Beranda</a>
                <a id="nav-kelas" href="#kelas"
                   class="nav-link text-on_surface_variant hover:text-primary transition-colors">Kelas</a>
                <a id="nav-kontak" href="#kontak"
                   class="nav-link text-on_surface_variant hover:text-primary transition-colors">Kontak</a>
            </nav>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3">
                {{-- Login CTA --}}
                <a href="{{ route('login') }}"
                   id="btn-header-login"
                   class="editorial-gradient text-on_primary flex items-center gap-2 px-4 py-2 md:px-6 md:py-2.5 rounded-xl font-sans text-xs md:text-sm font-semibold active:scale-95 transition-all shadow-sm">
                    <span>Masuk</span>
                    <span class="material-symbols-outlined text-base md:text-lg" aria-hidden="true">login</span>
                </a>

                {{-- Hamburger Menu Button (Mobile Only) --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden flex items-center justify-center p-2 text-on_surface_variant hover:text-primary transition-colors rounded-lg bg-surface-container-low"
                        aria-label="Toggle mobile menu">
                    <span class="material-symbols-outlined" x-text="mobileMenuOpen ? 'close' : 'menu'" aria-hidden="true">menu</span>
                </button>
            </div>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div x-show="mobileMenuOpen" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden absolute top-full left-0 w-full bg-surface-container-lowest border-t border-outline_variant/30 shadow-lg"
             @click.away="mobileMenuOpen = false">
            <nav class="flex flex-col py-2 px-6" aria-label="Navigasi mobile">
                <a href="#beranda" @click="mobileMenuOpen = false" class="py-4 font-sans font-medium text-base text-on_surface_variant hover:text-primary transition-colors border-b border-outline_variant/20">Beranda</a>
                <a href="#kelas" @click="mobileMenuOpen = false" class="py-4 font-sans font-medium text-base text-on_surface_variant hover:text-primary transition-colors border-b border-outline_variant/20">Kelas</a>
                <a href="#kontak" @click="mobileMenuOpen = false" class="py-4 font-sans font-medium text-base text-on_surface_variant hover:text-primary transition-colors">Kontak</a>
            </nav>
        </div>
    </header>

    <main class="pt-14 md:pt-16">

        {{-- ===== HERO SECTION ===== --}}
        <section id="beranda"
                 class="relative min-h-[420px] md:min-h-[500px] flex items-center overflow-hidden px-6 md:px-20 lg:px-32 py-16 md:py-24">
            {{-- Radial glow --}}
            <div class="absolute inset-0 z-0"
                 style="background: radial-gradient(circle at center, rgba(220,228,255,0.35) 0%, transparent 70%);"
                 aria-hidden="true"></div>

            <div class="relative z-10 max-w-4xl mx-auto text-center flex flex-col items-center w-full gap-6 md:gap-8">

                {{-- Badge --}}
                <div class="inline-flex items-center px-3 md:px-4 py-1 md:py-1.5 rounded-full font-sans text-[10px] md:text-xs font-bold tracking-widest uppercase"
                     style="background-color: rgba(220,228,255,0.5); color: #00236f;">
                    {{ $settings['hero_kicker'] ?? 'SISTEM INFORMASI MANAJEMEN ABSENSI' }}
                </div>

                {{-- H1 --}}
                <h1 class="font-display text-4xl md:text-5xl lg:text-7xl font-extrabold text-on_surface tracking-tight leading-tight md:leading-[1.1]">
                    {{ $settings['hero_headline'] ?? 'Sistem Pengelolaan Presensi Siswa' }}
                </h1>

                {{-- Subtitle --}}
                <p class="text-on_surface_variant text-base md:text-xl lg:text-2xl font-sans leading-relaxed max-w-2xl mx-auto px-2">
                    {{ $settings['hero_subheadline'] ?? 'Kelola presensi siswa SMA dengan mudah, cepat, dan akurat dalam satu platform terintegrasi.' }}
                </p>

                {{-- CTA --}}
                <div class="pt-2 md:pt-4">
                    <a href="{{ route('login') }}"
                       id="btn-hero-cta"
                       class="editorial-gradient text-on_primary px-8 md:px-10 py-3.5 md:py-4 rounded-full md:rounded-xl font-display font-bold text-sm md:text-lg hover:opacity-90 transition-all shadow-ambient active:scale-95 inline-block">
                        Mulai Sekarang
                    </a>
                </div>
            </div>
        </section>

        {{-- ===== STATS ROW ===== --}}
        <section class="px-6 md:px-20 lg:px-32 py-8 md:py-12" aria-label="Statistik">
            <div class="grid grid-cols-3 gap-4 md:gap-0 md:p-10 md:bg-surface-container-low md:rounded-3xl">

                <div class="bg-surface-container-lowest md:bg-transparent p-4 md:p-0 rounded-2xl flex flex-col items-center text-center gap-1 md:gap-2">
                    <span class="font-display text-2xl md:text-5xl font-extrabold text-primary tracking-tighter">{{ $totalKelas }}</span>
                    <span class="font-sans text-[10px] md:text-sm uppercase tracking-widest text-on_surface_variant font-bold">Kelas</span>
                </div>

                <div class="bg-surface-container-lowest md:bg-transparent p-4 md:p-0 rounded-2xl flex flex-col items-center text-center gap-1 md:gap-2 md:border-x md:border-outline_variant/30">
                    <span class="font-display text-2xl md:text-5xl font-extrabold text-primary tracking-tighter">{{ $totalSiswa }}</span>
                    <span class="font-sans text-[10px] md:text-sm uppercase tracking-widest text-on_surface_variant font-bold">Siswa</span>
                </div>

                <div class="bg-secondary_container md:bg-transparent p-4 md:p-0 rounded-2xl flex flex-col items-center text-center gap-1 md:gap-2">
                    <span class="font-display text-2xl md:text-5xl font-extrabold text-on_secondary_container md:text-secondary tracking-tighter">{{ $totalGuru }}</span>
                    <span class="font-sans text-[10px] md:text-sm uppercase tracking-widest text-on_secondary_container/70 md:text-on_surface_variant font-bold">Guru Aktif</span>
                </div>
            </div>
        </section>

        {{-- ===== SEARCH & FILTER SECTION ===== --}}
        <section id="kelas"
                 class="px-6 md:px-20 lg:px-32 py-8 md:py-12 flex flex-col gap-6 md:gap-8"
                 aria-label="Cari dan filter kelas">

            <div class="flex items-center justify-between md:block">
                <h2 class="font-display text-xl md:text-3xl font-extrabold text-on_surface tracking-tight">
                    Cari &amp; Filter Kelas
                </h2>
                <span class="material-symbols-outlined text-primary md:hidden" aria-hidden="true">tune</span>
            </div>

            <div class="flex flex-col md:flex-row gap-4 md:gap-6 items-start md:items-center">
                {{-- Search Bar --}}
                <div class="relative w-full md:max-w-md">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on_surface_variant" aria-hidden="true">search</span>
                    <input
                        id="search-kelas"
                        type="search"
                        placeholder="Cari nama kelas..."
                        oninput="filterKelas()"
                        autocomplete="off"
                        class="w-full pl-12 pr-4 py-3.5 md:py-3 bg-surface-container-low outline-none rounded-xl font-sans text-sm focus:ring-2 focus:ring-primary/20 transition-all"
                        aria-label="Cari nama kelas"
                    >
                </div>

                {{-- Filter Chips --}}
                <div id="filter-chips"
                     class="chips-strip flex gap-2 overflow-x-auto pb-2 -mx-2 px-2 md:mx-0 md:px-0 md:flex-wrap md:gap-3"
                     role="group"
                     aria-label="Filter tingkat kelas">
                    <button data-filter="semua"
                            onclick="setFilter('semua', this)"
                            class="chip-btn bg-primary text-on_primary px-5 py-2 rounded-full font-sans text-xs md:text-sm font-bold whitespace-nowrap active:scale-95 transition-all"
                            aria-pressed="true">
                        Semua
                    </button>
                    <button data-filter="x"
                            onclick="setFilter('x', this)"
                            class="chip-btn bg-surface-container-high text-on_surface_variant px-5 py-2 rounded-full font-sans text-xs md:text-sm font-semibold whitespace-nowrap active:scale-95 transition-all"
                            aria-pressed="false">
                        Kelas X
                    </button>
                    <button data-filter="xi"
                            onclick="setFilter('xi', this)"
                            class="chip-btn bg-surface-container-high text-on_surface_variant px-5 py-2 rounded-full font-sans text-xs md:text-sm font-semibold whitespace-nowrap active:scale-95 transition-all"
                            aria-pressed="false">
                        Kelas XI
                    </button>
                    <button data-filter="xii"
                            onclick="setFilter('xii', this)"
                            class="chip-btn bg-surface-container-high text-on_surface_variant px-5 py-2 rounded-full font-sans text-xs md:text-sm font-semibold whitespace-nowrap active:scale-95 transition-all"
                            aria-pressed="false">
                        Kelas XII
                    </button>
                </div>
            </div>
        </section>

        {{-- ===== CLASS CARDS SECTION ===== --}}
        <section class="px-6 md:px-20 lg:px-32 py-4 md:py-12 flex flex-col gap-6 md:gap-12">

            @if ($classrooms->isEmpty())
                {{-- Empty State --}}
                <div id="empty-state" class="bg-surface-container-lowest p-10 rounded-3xl text-center shadow-ambient">
                    <span class="material-symbols-outlined text-on_surface_variant text-5xl mb-4 block" aria-hidden="true">school</span>
                    <p class="text-on_surface_variant font-sans">Belum ada data kelas untuk ditampilkan.</p>
                </div>
            @else

                {{-- No-result state --}}
                <div id="no-result" class="hidden bg-surface-container-lowest p-10 rounded-3xl text-center shadow-ambient" role="status" aria-live="polite">
                    <span class="material-symbols-outlined text-on_surface_variant text-5xl mb-4 block" aria-hidden="true">search_off</span>
                    <p class="text-on_surface_variant font-sans">Tidak ada kelas yang cocok dengan pencarian.</p>
                </div>

                {{-- ── DESKTOP GRID (hidden on mobile) ── --}}
                <div id="desktop-grid" class="hidden md:grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($classrooms as $classroom)
                        @php
                            // Default icon untuk Kelas X
                            $classIcon = 'school';
                            $className = \Illuminate\Support\Str::upper((string) ($classroom->name ?? ''));

                            // Cek urutan dari yang paling panjang (XII) agar tidak tertimpa
                            if (\Illuminate\Support\Str::contains($className, 'XII')) {
                                $classIcon = 'history_edu';
                            } elseif (\Illuminate\Support\Str::contains($className, 'XI')) {
                                $classIcon = 'meeting_room';
                            }
                        @endphp
                        <div class="classroom-card group bg-surface-container-lowest p-8 rounded-3xl transition-all hover:bg-primary hover:-translate-y-2 duration-300 shadow-sm"
                             data-name="{{ strtolower($classroom->name) }}"
                             data-tingkat="{{ strtolower($classroom->tingkat ?? '') }}">
                            <div class="flex flex-col gap-6">
                                <div class="w-14 h-14 rounded-2xl bg-surface-container-low flex items-center justify-center group-hover:bg-white/10 transition-colors">
                                    <span class="material-symbols-outlined text-primary group-hover:text-white text-3xl" aria-hidden="true">{{ $classIcon }}</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h3 class="font-display text-2xl font-bold text-on_surface group-hover:text-white transition-colors">
                                        {{ $classroom->name }}
                                    </h3>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-3 py-1 bg-secondary_container text-on_secondary_container text-xs font-bold rounded-full">
                                            {{ $classroom->students_count }} Siswa
                                        </span>
                                        @if(isset($classroom->tingkat))
                                            <span class="px-3 py-1 bg-surface-container text-on_surface_variant text-xs font-bold rounded-full">
                                                {{ $classroom->tingkat }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('login') }}"
                                   class="w-full py-4 rounded-xl bg-surface-container-high text-primary font-display font-extrabold text-sm group-hover:bg-white group-hover:text-primary transition-all flex items-center justify-center gap-2 active:scale-95">
                                    Detail
                                    <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ── MOBILE LIST (hidden on desktop) ── --}}
                <div id="mobile-list" class="flex flex-col gap-4 md:hidden">
                    @foreach ($classrooms as $classroom)
                        @php
                            // Default icon untuk Kelas X
                            $classIcon = 'school';
                            $className = \Illuminate\Support\Str::upper((string) ($classroom->name ?? ''));

                            // Cek urutan dari yang paling panjang (XII) agar tidak tertimpa
                            if (\Illuminate\Support\Str::contains($className, 'XII')) {
                                $classIcon = 'history_edu';
                            } elseif (\Illuminate\Support\Str::contains($className, 'XI')) {
                                $classIcon = 'meeting_room';
                            }
                        @endphp
                        <div class="classroom-card group bg-surface-container-lowest p-5 rounded-3xl transition-all hover:bg-primary duration-300 shadow-sm"
                             data-name="{{ strtolower($classroom->name) }}"
                             data-tingkat="{{ strtolower($classroom->tingkat ?? '') }}">
                            <div class="flex flex-col gap-4">
                                {{-- Ikon toga — sama persis dengan desktop --}}
                                <div class="w-12 h-12 rounded-2xl bg-surface-container-low flex items-center justify-center group-hover:bg-white/10 transition-colors">
                                    <span class="material-symbols-outlined text-primary group-hover:text-white text-2xl" aria-hidden="true">{{ $classIcon }}</span>
                                </div>
                                {{-- Nama & chip siswa --}}
                                <div class="flex flex-col gap-2">
                                    <h4 class="font-display font-bold text-lg text-on_surface group-hover:text-white transition-colors">
                                        {{ $classroom->name }}
                                    </h4>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-3 py-1 bg-secondary_container text-on_secondary_container text-xs font-bold rounded-full">
                                            {{ $classroom->students_count }} Siswa
                                        </span>
                                        @if(isset($classroom->tingkat))
                                            <span class="px-3 py-1 bg-surface-container text-on_surface_variant text-xs font-bold rounded-full group-hover:bg-white/10 group-hover:text-white transition-colors">
                                                {{ $classroom->tingkat }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- Tombol Detail --}}
                                <a href="{{ route('login') }}"
                                   class="w-full py-3 rounded-xl bg-surface-container-high text-primary font-display font-extrabold text-sm group-hover:bg-white group-hover:text-primary transition-all flex items-center justify-center gap-2 active:scale-95">
                                    Detail
                                    <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Load More --}}
            <div id="load-more-wrap" class="flex justify-center pt-4 md:pt-8">
                <button id="btn-load-more"
                        class="flex items-center gap-2 px-8 py-4 text-primary font-sans font-bold text-xs md:text-sm uppercase tracking-widest rounded-full md:rounded-xl hover:bg-primary_fixed/30 md:hover:editorial-gradient transition-all active:scale-95">
                    <span>Muat Lebih Banyak Kelas</span>
                    <span class="material-symbols-outlined text-sm" aria-hidden="true">expand_more</span>
                </button>
            </div>
        </section>

        {{-- ===== FOOTER ===== --}}
        <footer id="kontak"
                class="bg-surface-container-lowest px-6 md:px-20 lg:px-32 py-12 md:py-20 mt-8"
                role="contentinfo">

            {{-- Desktop: brand + kontak --}}
            <div class="hidden md:grid md:grid-cols-4 gap-16">
                <div class="md:col-span-2 flex flex-col gap-6">
                    <div class="font-display text-3xl font-extrabold text-primary tracking-tighter">SIMAS</div>
                    <p class="text-on_surface_variant font-sans leading-relaxed max-w-sm">
                        {{ $settings['footer_desc'] ?? 'Menghadirkan transparansi dan akurasi dalam pengelolaan kehadiran siswa melalui teknologi editorial yang mutakhir.' }}
                    </p>
                </div>
                <div class="flex flex-col gap-6">
                    <h4 class="font-display font-bold text-on_surface tracking-tight">Tautan Cepat</h4>
                    <ul class="flex flex-col gap-4 font-sans text-sm text-on_surface_variant">
                        <li><a href="#" class="hover:text-primary transition-colors">Beranda</a></li>
                        <li><a href="#kelas" class="hover:text-primary transition-colors">Cari &amp; Filter Kelas</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-primary transition-colors">Masuk / Login Admin</a></li>
                    </ul>
                </div>
                <div class="flex flex-col gap-6">
                    <h4 class="font-display font-bold text-on_surface tracking-tight">Kontak</h4>
                    <ul class="flex flex-col gap-4 font-sans text-sm text-on_surface_variant">
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl" aria-hidden="true">mail</span>
                            {{ $settings['footer_email'] ?? 'info@simas.sch.id' }}
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl" aria-hidden="true">location_on</span>
                            {{ $settings['footer_address'] ?? 'Jepara, Indonesia' }}
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Mobile: centered --}}
            <div class="md:hidden flex flex-col items-center gap-6 text-center">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-3xl" aria-hidden="true">school</span>
                    <span class="font-display font-extrabold text-primary tracking-tighter text-xl">SIMAS</span>
                </div>
                <div class="flex flex-col gap-2 text-on_surface_variant font-sans text-sm">
                    <p>Jl. Pendidikan No. 123, Jepara</p>
                    <p>Email: info@simas.sch.id</p>
                </div>
            </div>

            {{-- Copyright bar --}}
            <div class="mt-12 md:mt-20 pt-8 flex flex-col md:flex-row justify-between gap-4 items-center md:items-start"
                 style="border-top: 1px solid rgba(195,199,207,0.20);">
                <p class="font-sans text-xs text-on_surface_variant text-center md:text-left">
                    &copy; {{ date('Y') }} SIMAS. Seluruh hak cipta dilindungi.
                </p>
                <div class="flex items-center gap-6 md:gap-8">
                    <div class="flex items-center gap-4">
                        <a href="{{ $settings['social_instagram'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="text-on_surface_variant hover:text-primary transition-colors font-sans text-sm font-semibold">Instagram</a>
                        <a href="{{ $settings['social_youtube'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="text-on_surface_variant hover:text-primary transition-colors font-sans text-sm font-semibold">YouTube</a>
                    </div>
                    
                    <div class="hidden md:block w-px h-4 bg-[rgba(195,199,207,0.40)]"></div>
                    
                    <div class="flex items-center gap-4">
                        <a href="#" class="font-sans text-xs text-on_surface_variant hover:text-primary transition-colors">Syarat &amp; Ketentuan</a>
                        <a href="#" class="font-sans text-xs text-on_surface_variant hover:text-primary transition-colors">Keamanan Data</a>
                    </div>
                </div>
            </div>
        </footer>

    </main>

@endsection

{{-- ===== SCRIPTS ===== --}}
@push('scripts')
<script>
(function () {
    'use strict';

    // ─── 1. Filter & Search ─────────────────────────────────────────────────
    let activeFilter = 'semua';

    window.setFilter = function (value, btn) {
        activeFilter = value;
        document.querySelectorAll('.chip-btn').forEach(function (chip) {
            chip.classList.remove('bg-primary', 'text-on_primary', 'font-bold');
            chip.classList.add('bg-surface-container-high', 'text-on_surface_variant', 'font-semibold');
            chip.setAttribute('aria-pressed', 'false');
        });
        btn.classList.remove('bg-surface-container-high', 'text-on_surface_variant', 'font-semibold');
        btn.classList.add('bg-primary', 'text-on_primary', 'font-bold');
        btn.setAttribute('aria-pressed', 'true');
        filterKelas();
    };

    window.filterKelas = function () {
        var query = (document.getElementById('search-kelas').value || '').toLowerCase().trim();
        var cards = document.querySelectorAll('.classroom-card');
        var visibleCount = 0;

        cards.forEach(function (card) {
            var name    = card.dataset.name    || '';
            var tingkat = card.dataset.tingkat || '';
            var chipMatch = true;

            if (activeFilter === 'x') {
                chipMatch = /\bx\b/.test(tingkat) && !/xi|xii/.test(tingkat);
                if (!chipMatch) chipMatch = /\bx\b/.test(name) && !/xi|xii/.test(name);
            } else if (activeFilter === 'xi') {
                chipMatch = /\bxi\b/.test(tingkat) && !/xii/.test(tingkat);
                if (!chipMatch) chipMatch = /\bxi\b/.test(name) && !/xii/.test(name);
            } else if (activeFilter === 'xii') {
                chipMatch = /\bxii\b/.test(tingkat);
                if (!chipMatch) chipMatch = /\bxii\b/.test(name);
            }

            var searchMatch = query === '' || name.includes(query);

            if (chipMatch && searchMatch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        var noResult = document.getElementById('no-result');
        if (noResult) noResult.classList.toggle('hidden', visibleCount > 0);

        var loadMore = document.getElementById('load-more-wrap');
        if (loadMore) loadMore.style.display = (query !== '' || activeFilter !== 'semua') ? 'none' : '';
    };

    // ─── 2. Intersection Observer — Nav Active State ────────────────────────
    var navLinks = {
        'beranda': document.getElementById('nav-beranda'),
        'kelas':   document.getElementById('nav-kelas'),
        'kontak':  document.getElementById('nav-kontak'),
    };

    function setActiveNav(id) {
        Object.values(navLinks).forEach(function (link) {
            if (!link) return;
            link.classList.remove('nav-link-active');
            link.style.borderBottom = '';
            link.style.paddingBottom = '';
        });
        if (navLinks[id]) {
            navLinks[id].classList.add('nav-link-active');
        }
    }

    // Default: beranda active on load
    setActiveNav('beranda');

    var sections = [
        document.getElementById('beranda'),
        document.getElementById('kelas'),
        document.getElementById('kontak'),
    ].filter(Boolean);

    if ('IntersectionObserver' in window && sections.length) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    setActiveNav(entry.target.id);
                }
            });
        }, {
            // Fire when section occupies ≥20% of viewport, accounting for fixed header
            rootMargin: '-56px 0px -60% 0px',
            threshold: 0
        });

        sections.forEach(function (sec) { observer.observe(sec); });
    }

})();
</script>
@endpush
