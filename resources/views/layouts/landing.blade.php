<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'SIMAS') }} – Sistem Manajemen Absensi</title>
    <meta name="description" content="@yield('meta-description', 'SIMAS – Platform terintegrasi untuk kelola presensi siswa SMA dengan mudah, cepat, dan akurat.')">

    {{-- ─── Google Fonts ─────────────────────────────────────────────────── --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    {{-- ─── Vite Assets (Tailwind + custom CSS) ────────────────────────── --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ─── Per-page extra styles ──────────────────────────────────────── --}}
    @stack('styles')
</head>
<body class="bg-surface text-on_surface font-sans antialiased selection:bg-primary_fixed selection:text-on_primary">

    @yield('content')

    {{-- ─── Per-page extra scripts ─────────────────────────────────────── --}}
    @stack('scripts')

</body>
</html>
