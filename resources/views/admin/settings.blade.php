@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('page-title', 'Pengaturan Website')
@section('page-description', 'Kelola konten yang tampil di halaman depan (Landing Page)')

@section('content')
    <div id="admin-settings">

        {{-- ═══ SECTION TITLE ═══════════════════════════════════════════════════════ --}}
        <div class="mb-10">
            <h2 class="font-display text-4xl font-extrabold text-primary tracking-tight mb-2">
                Pengaturan Landing Page
            </h2>
            <p class="text-on_surface_variant font-sans">
                Perubahan akan langsung tercermin di halaman depan website SIMAS.
            </p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" id="form-settings">
            @csrf

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

                {{-- ─── KOLOM KIRI: form utama ─────────────────────────────────── --}}
                <div class="xl:col-span-2 flex flex-col gap-8">

                    {{-- ── CARD: Hero Section ──────────────────────────────────── --}}
                    <div class="bg-surface-container-lowest rounded-3xl p-8 shadow-sm">

                        {{-- Card Header --}}
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-10 h-10 rounded-xl btn-primary-gradient flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-xl font-extrabold text-primary">Area Banner Utama (Hero)</h3>
                                <p class="text-xs text-on_surface_variant font-sans mt-0.5">Konten yang tampil di bagian
                                    paling atas halaman</p>
                            </div>
                        </div>

                        <div class="space-y-6">

                            {{-- Label Kicker --}}
                            <div>
                                <label for="hero_kicker"
                                    class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                    Label / Kicker
                                </label>
                                <p class="text-xs text-on_surface_variant font-sans mb-3">
                                    Teks kecil di atas judul utama (biasanya huruf kapital semua).
                                </p>
                                <input type="text" id="hero_kicker" name="hero_kicker"
                                    value="{{ $settings['hero_kicker'] ?? '' }}"
                                    class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                    placeholder="Contoh: SISTEM INFORMASI MANAJEMEN ABSENSI">
                            </div>

                            {{-- Headline --}}
                            <div>
                                <label for="hero_headline"
                                    class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                    Headline (Judul Utama)
                                </label>
                                <p class="text-xs text-on_surface_variant font-sans mb-3">
                                    Judul besar yang menonjol di hero section.
                                </p>
                                <input type="text" id="hero_headline" name="hero_headline"
                                    value="{{ $settings['hero_headline'] ?? '' }}"
                                    class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                    placeholder="Contoh: Sistem Pengelolaan Presensi Siswa">
                            </div>

                            {{-- Subheadline --}}
                            <div>
                                <label for="hero_subheadline"
                                    class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                    Subheadline (Deskripsi Judul)
                                </label>
                                <p class="text-xs text-on_surface_variant font-sans mb-3">
                                    Kalimat penjelas singkat di bawah judul utama.
                                </p>
                                <textarea id="hero_subheadline" name="hero_subheadline" rows="3"
                                    class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all resize-none"
                                    placeholder="Contoh: Kelola presensi siswa SMA dengan mudah, cepat, dan akurat...">{{ $settings['hero_subheadline'] ?? '' }}</textarea>
                            </div>

                        </div>
                    </div>

                    {{-- ── CARD: Footer & Kontak ───────────────────────────────── --}}
                    <div class="bg-surface-container-lowest rounded-3xl p-8 shadow-sm">

                        {{-- Card Header --}}
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-10 h-10 rounded-xl btn-primary-gradient flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-xl font-extrabold text-primary">Area Footer & Kontak</h3>
                                <p class="text-xs text-on_surface_variant font-sans mt-0.5">Informasi yang tampil di bagian
                                    bawah halaman</p>
                            </div>
                        </div>

                        <div class="space-y-6">

                            {{-- Deskripsi Brand --}}
                            <div>
                                <label for="footer_desc"
                                    class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                    Deskripsi Brand Footer
                                </label>
                                <p class="text-xs text-on_surface_variant font-sans mb-3">
                                    Paragraf singkat di bawah logo SIMAS pada footer desktop.
                                </p>
                                <textarea id="footer_desc" name="footer_desc" rows="3"
                                    class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all resize-none"
                                    placeholder="Contoh: Menghadirkan transparansi dan akurasi dalam pengelolaan kehadiran siswa...">{{ $settings['footer_desc'] ?? '' }}</textarea>
                            </div>

                            {{-- Email & Alamat --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="footer_email"
                                        class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                        Email Resmi
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-on_surface_variant">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </span>
                                        <input type="email" id="footer_email" name="footer_email"
                                            value="{{ $settings['footer_email'] ?? '' }}"
                                            class="w-full bg-surface-container-low rounded-xl pl-10 pr-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                            placeholder="info@simas.sch.id">
                                    </div>
                                </div>

                                <div>
                                    <label for="footer_address"
                                        class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                        Alamat Fisik
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-on_surface_variant">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </span>
                                        <input type="text" id="footer_address" name="footer_address"
                                            value="{{ $settings['footer_address'] ?? '' }}"
                                            class="w-full bg-surface-container-low rounded-xl pl-10 pr-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                            placeholder="Jepara, Indonesia">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ── CARD: Geofencing Absensi ───────────────────────────── --}}
                    <div class="bg-surface-container-lowest rounded-3xl p-8 shadow-sm">

                        {{-- Card Header --}}
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-10 h-10 rounded-xl btn-primary-gradient flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-xl font-extrabold text-primary">Pengaturan Lokasi (Geofencing)
                                </h3>
                                <p class="text-xs text-on_surface_variant font-sans mt-0.5">Tentukan titik pusat sekolah dan
                                    batas radius absensi</p>
                            </div>
                        </div>

                        <div class="space-y-6">

                            {{-- Latitude & Longitude --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="latitude_sekolah"
                                        class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                        Latitude Sekolah
                                    </label>
                                    <input type="text" id="latitude_sekolah" name="latitude_sekolah"
                                        value="{{ $settings['latitude_sekolah'] ?? '' }}"
                                        class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                        placeholder="Contoh: -6.538249">
                                </div>

                                <div>
                                    <label for="longitude_sekolah"
                                        class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                        Longitude Sekolah
                                    </label>
                                    <input type="text" id="longitude_sekolah" name="longitude_sekolah"
                                        value="{{ $settings['longitude_sekolah'] ?? '' }}"
                                        class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                        placeholder="Contoh: 110.752525">
                                </div>
                            </div>

                            {{-- Radius --}}
                            <div>
                                <label for="radius_meter"
                                    class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                    Radius Maksimal (Meter)
                                </label>
                                <p class="text-xs text-on_surface_variant font-sans mb-3">
                                    Jarak maksimal siswa dapat melakukan absensi dari titik sekolah.
                                </p>
                                <input type="number" id="radius_meter" name="radius_meter"
                                    value="{{ $settings['radius_meter'] ?? '' }}"
                                    class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                    placeholder="Contoh: 50">
                            </div>

                        </div>
                    </div>

                    {{-- ── CARD: Media Sosial ──────────────────────────────────── --}}
                    <div class="bg-surface-container-lowest rounded-3xl p-8 shadow-sm">

                        {{-- Card Header --}}
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-10 h-10 rounded-xl btn-primary-gradient flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-xl font-extrabold text-primary">Media Sosial</h3>
                                <p class="text-xs text-on_surface_variant font-sans mt-0.5">Tautan yang tampil di bagian
                                    copyright footer</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Instagram --}}
                            <div>
                                <label for="social_instagram"
                                    class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                    URL Instagram
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-on_surface_variant">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path
                                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                        </svg>
                                    </span>
                                    <input type="url" id="social_instagram" name="social_instagram"
                                        value="{{ $settings['social_instagram'] ?? '' }}"
                                        class="w-full bg-surface-container-low rounded-xl pl-10 pr-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                        placeholder="https://instagram.com/akun">
                                </div>
                            </div>

                            {{-- YouTube --}}
                            <div>
                                <label for="social_youtube"
                                    class="block text-sm font-semibold text-on_surface mb-2 font-display">
                                    URL YouTube
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-on_surface_variant">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path
                                                d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                        </svg>
                                    </span>
                                    <input type="url" id="social_youtube" name="social_youtube"
                                        value="{{ $settings['social_youtube'] ?? '' }}"
                                        class="w-full bg-surface-container-low rounded-xl pl-10 pr-4 py-3 font-sans text-sm text-on_surface outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                        placeholder="https://youtube.com/@channel">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                {{-- END KOLOM KIRI --}}

                {{-- ─── KOLOM KANAN: info + tombol simpan ─────────────────────── --}}
                <div class="flex flex-col gap-6">

                    {{-- Info Card --}}
                    <div class="bg-surface-container-low rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h4 class="font-display font-bold text-on_surface text-sm">Cara Kerja</h4>
                        </div>
                        <ul class="space-y-3 font-sans text-xs text-on_surface_variant leading-relaxed">
                            <li class="flex items-start gap-2">
                                <span
                                    class="w-4 h-4 rounded-full bg-primary/10 text-primary text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                                Ubah teks pada form di sebelah kiri
                            </li>
                            <li class="flex items-start gap-2">
                                <span
                                    class="w-4 h-4 rounded-full bg-primary/10 text-primary text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                                Klik tombol <strong class="text-on_surface">Simpan Perubahan</strong>
                            </li>
                            <li class="flex items-start gap-2">
                                <span
                                    class="w-4 h-4 rounded-full bg-primary/10 text-primary text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                                Perubahan langsung aktif di halaman depan tanpa perlu restart
                            </li>
                        </ul>
                    </div>

                    {{-- Preview Link --}}
                    <a href="{{ route('landing') }}" target="_blank" rel="noopener noreferrer"
                        class="flex items-center justify-between gap-3 bg-surface-container-lowest rounded-2xl px-5 py-4 group hover:bg-primary/5 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-on_surface font-display">Preview Landing Page</p>
                                <p class="text-xs text-on_surface_variant font-sans truncate">Buka di tab baru</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-on_surface_variant group-hover:text-primary transition-colors flex-shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>

                    {{-- Tombol Simpan --}}
                    <button type="submit" form="form-settings" id="btn-simpan-pengaturan"
                        class="w-full btn-primary-gradient text-white font-display font-bold text-sm py-4 rounded-2xl hover:opacity-90 active:scale-95 transition-all shadow-md flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Perubahan
                    </button>

                </div>
                {{-- END KOLOM KANAN --}}

            </div>
        </form>

    </div>
@endsection