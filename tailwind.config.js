import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // ─── Color Architecture: "The Scholastic Editorial" ───────────────
            colors: {
                // Primary Brand
                primary:            '#00236f',
                primary_container:  '#1e3a8a',
                on_primary:         '#ffffff',
                on_primary_container: '#90a8ff',
                primary_fixed:      '#dce4ff',

                // Secondary / Attendance Chips
                secondary:              '#006c4f',
                secondary_container:    '#6cf8bb',
                on_secondary:           '#ffffff',
                on_secondary_container: '#00714d',

                // Tertiary / Alert
                tertiary:   '#4b1c00',

                // Surface Hierarchy (No-Line Rule)
                surface:                    '#f8f9fb',
                'surface-container-lowest': '#ffffff',
                'surface-container-low':    '#f3f4f6',
                'surface-container':        '#edeef0',
                'surface-container-high':   '#e7e8ea',
                'surface-container-highest':'#e1e2e4',
                surface_bright:             '#f9fafb',

                // On-Surface (Never pure black)
                on_surface:         '#191c1e',
                on_surface_variant: '#43474e',
                outline_variant:    '#c3c7cf',

                // Outline (Ghost Border fallback at 20% opacity)
                outline: '#73777f',
            },

            // ─── Typography: Dual-Typeface System ─────────────────────────────
            fontFamily: {
                display: ['Manrope', ...defaultTheme.fontFamily.sans],
                sans:    ['Inter', ...defaultTheme.fontFamily.sans],
            },

            // ─── Font Size Scale: Editorial Voice ─────────────────────────────
            fontSize: {
                'display-lg':   ['3.5rem',  { lineHeight: '1.15', fontWeight: '700' }],
                'display-md':   ['2.75rem', { lineHeight: '1.2',  fontWeight: '700' }],
                'headline-lg':  ['2rem',    { lineHeight: '1.25', fontWeight: '700' }],
                'headline-md':  ['1.75rem', { lineHeight: '1.3',  fontWeight: '600' }],
                'headline-sm':  ['1.5rem',  { lineHeight: '1.35', fontWeight: '600' }],
                'label-lg':     ['0.875rem',{ lineHeight: '1.4',  fontWeight: '600' }],
                'label-md':     ['0.8125rem',{ lineHeight: '1.4', fontWeight: '600' }],
                'label-sm':     ['0.75rem', { lineHeight: '1.4',  fontWeight: '500' }],
                'body-lg':      ['1rem',    { lineHeight: '1.6',  fontWeight: '400' }],
                'body-md':      ['0.875rem',{ lineHeight: '1.6',  fontWeight: '400' }],
                'body-sm':      ['0.75rem', { lineHeight: '1.5',  fontWeight: '400' }],
            },

            // ─── Border Radius ─────────────────────────────────────────────────
            borderRadius: {
                xl: '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
            },

            // ─── Elevation & Ambient Shadows ──────────────────────────────────
            boxShadow: {
                // Primary-tinted ambient shadow — for floating elements only (modals, popovers)
                ambient: '0 12px 40px rgba(0, 35, 111, 0.08)',
                // Slightly stronger for modals
                'ambient-lg': '0 20px 60px rgba(0, 35, 111, 0.12)',
            },

            // ─── Backdrop Blur ─────────────────────────────────────────────────
            backdropBlur: {
                glass: '24px',
            },

            // ─── Spacing additions ─────────────────────────────────────────────
            spacing: {
                18: '4.5rem',
                22: '5.5rem',
            },
        },
    },

    plugins: [forms({ strategy: 'class' })],
};
