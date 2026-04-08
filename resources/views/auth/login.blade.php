@extends('layouts.landing')

@section('meta-description', 'Login ke SIMAS – Sistem Manajemen Absensi Sekolah untuk administrator, guru, dan wali kelas.')

@section('content')

{{-- ─── Login Page ─────────────────────────────────────────────────────────── --}}
<div class="min-h-screen bg-surface flex flex-col">


    {{-- ── Main Content (centered) ─────────────────────────────────────────── --}}
    <main class="flex-grow flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm md:max-w-md space-y-8">

            {{-- ── Branding ───────────────────────────────────────────────── --}}
            <div class="text-center space-y-3">
                {{-- Branding icon: terang di semua ukuran layar (No-Line design system) --}}
                <div class="inline-flex items-center justify-center w-16 h-16
                            rounded-full
                            bg-surface-container-low
                            mb-2">
                    <span class="material-symbols-outlined text-primary text-4xl">school</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold md:font-black tracking-tight text-primary font-headline">
                    SIMAS
                </h1>
                <p class="text-sm font-medium text-on-surface-variant tracking-wide px-4 leading-relaxed">
                    Sistem Manajemen Absensi Sekolah
                </p>
            </div>

            {{-- ── Login Card ─────────────────────────────────────────────── --}}
            <div class="bg-surface-container-lowest p-8 rounded-xl
                        shadow-[0_12px_40px_rgba(0,35,111,0.08)]
                        space-y-6">

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="p-3 bg-surface-container-low rounded-xl text-sm text-on-surface-variant">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- ── EMAIL / NIS ─────────────────────────────────────── --}}
                    <div class="space-y-1.5">
                        <label for="login"
                               class="block text-[10px] font-bold tracking-widest text-on-surface-variant uppercase ml-1">
                            EMAIL
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-lg">alternate_email</span>
                            </div>
                            <input
                                id="login"
                                type="text"
                                name="login"
                                value="{{ old('login') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="name@school.edu"
                                class="block w-full pl-11 pr-4 py-3.5
                                       bg-surface-container-low
                                       rounded-xl
                                       focus:outline-none focus:ring-2 focus:ring-primary/20
                                       text-on-surface placeholder-outline/50
                                       text-sm font-body
                                       transition-all duration-300
                                       @error('login') ring-2 ring-error/40 @enderror"
                            >
                        </div>
                        @error('login')
                            <p class="text-[11px] text-red-500 text-sm ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── PASSWORD ────────────────────────────────────────── --}}
                    <div class="space-y-1.5">
                        <label for="password"
                               class="block text-[10px] font-bold tracking-widest text-on-surface-variant uppercase ml-1">
                            PASSWORD
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-lg">lock</span>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="block w-full pl-11 pr-12 py-3.5
                                       bg-surface-container-low
                                       rounded-xl
                                       focus:outline-none focus:ring-2 focus:ring-primary/20
                                       text-on-surface placeholder-outline/50
                                       text-sm font-body
                                       transition-all duration-300
                                       @error('password') ring-2 ring-error/40 @enderror"
                            >
                            {{-- Toggle Visibility --}}
                            <button type="button" id="toggle-password"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center"
                                    aria-label="Toggle password visibility">
                                <span id="eye-icon"
                                      class="material-symbols-outlined text-outline hover:text-primary transition-colors text-lg">
                                    visibility
                                </span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-red-500 text-sm ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── Remember this device ────────────────────────────── --}}
                    <div class="flex items-center py-1">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="h-5 w-5 rounded
                                       bg-surface-container-low text-primary
                                       focus:ring-primary/20
                                       transition-all"
                            >
                            <span class="text-xs font-medium text-on-surface-variant group-hover:text-primary transition-colors">
                                Remember this device
                            </span>
                        </label>
                    </div>

                    {{-- ── Submit ───────────────────────────────────────────── --}}
                    <button
                        type="submit"
                        class="w-full py-4 px-6 rounded-xl
                               font-label font-bold text-sm tracking-wide
                               text-white
                               flex items-center justify-center gap-2
                               shadow-[0_4px_14px_rgba(0,35,111,0.25)]
                               active:scale-[0.98]
                               hover:brightness-110
                               transition-all duration-300 group"
                        style="background: linear-gradient(135deg, #00236f 0%, #1e3a8a 100%);"
                    >
                        LOGIN TO DASHBOARD
                        <span class="material-symbols-outlined text-lg transition-transform group-hover:translate-x-1">
                            arrow_forward
                        </span>
                    </button>

                </form>

                {{-- ── Footer Link ──────────────────────────────────────────── --}}
                <div class="pt-2 text-center">
                    <p class="text-on-surface-variant font-body text-xs font-normal">
                        New student or faculty?
                        <a href="mailto:admin@school.edu"
                           class="text-primary font-semibold hover:underline underline-offset-4 transition-colors">
                            Contact Administration
                        </a>
                    </p>
                </div>

            </div>{{-- /card --}}

            {{-- ── Ornamental divider ──────────────────────────────────────── --}}
            <div class="flex justify-center opacity-10">
                <div class="w-24 h-1 bg-primary rounded-full"></div>
            </div>

        </div>
    </main>

    {{-- ── Global Footer ───────────────────────────────────────────────────── --}}
    <footer class="w-full flex flex-col items-center gap-2 px-8 mt-auto py-6">
        <div class="flex flex-col items-center gap-1">
            <p class="font-body text-[10px] uppercase tracking-widest text-slate-400 font-medium">
                SYSTEM ONLINE &bull; VERSION 4.2.0 &bull; PRIVACY
            </p>
            <div class="flex gap-4 mt-2">
                <a href="#"
                   class="font-body text-[10px] uppercase tracking-widest text-slate-400 hover:text-primary transition-colors">
                    Privacy Policy
                </a>
                <span class="text-slate-300 text-[10px]">&bull;</span>
                <a href="#"
                   class="font-body text-[10px] uppercase tracking-widest text-slate-400 hover:text-primary transition-colors">
                    Help Center
                </a>
            </div>
        </div>
    </footer>

</div>

@endsection

@push('scripts')
<script>
    // ── Password visibility toggle ───────────────────────────────────────────
    const toggleBtn  = document.getElementById('toggle-password');
    const pwdInput   = document.getElementById('password');
    const eyeIcon    = document.getElementById('eye-icon');

    if (toggleBtn && pwdInput && eyeIcon) {
        toggleBtn.addEventListener('click', () => {
            const isHidden = pwdInput.type === 'password';
            pwdInput.type  = isHidden ? 'text' : 'password';
            eyeIcon.textContent = isHidden ? 'visibility_off' : 'visibility';
        });
    }

    // ── SweetAlert2 – validation errors ─────────────────────────────────────
    @if ($errors->has('login') || $errors->has('password'))
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{{ $errors->first("login") ?: $errors->first("password") }}',
            confirmButtonText: 'Coba Lagi',
            confirmButtonColor: '#00236f',
            buttonsStyling: false,
            customClass: {
                popup: 'bg-white rounded-2xl shadow-2xl border border-gray-100',
                title: 'text-2xl font-bold text-gray-800',
                htmlContainer: 'text-base text-gray-500 mt-2',
                confirmButton: 'mt-4 bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95',
                cancelButton: 'mt-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95 ml-2'
            }
        });
    }
    @endif
</script>
@endpush
