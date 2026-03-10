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
            fontFamily: {
                display: ['Instrument Serif', 'serif'],
                body: ['Geist', 'sans-serif'],
                mono: ['Geist Mono', 'monospace'],
            },
            colors: {
                // Neutral Minimal - Système de couleurs épuré
                black: '#0a0a0a',
                white: '#ffffff',
                'off-white': '#f7f7f5',
                gray: {
                    50: '#f7f7f5',
                    100: '#efefed',
                    200: '#e0e0dc',
                    400: '#a0a09a',
                    600: '#666660',
                    800: '#2a2a28',
                },
                // Status colors - Stock indicators only
                'stock-ok': '#22c55e',
                'stock-out': '#f87171',
                // Badge statuses
                'badge-warn-bg': '#fdf6ec',
                'badge-warn-text': '#b45309',
                'badge-ok-bg': '#f0fdf4',
                'badge-ok-text': '#15803d',
                'badge-err-bg': '#fef2f2',
                'badge-err-text': '#dc2626',
            },
            borderRadius: {
                'sm': '4px',
                'md': '6px',
                'lg': '8px',
                'xl': '12px',
            },
            spacing: {
                '1': '4px',
                '2': '8px',
                '3': '12px',
                '4': '16px',
                '5': '20px',
                '6': '24px',
                '8': '32px',
                '10': '40px',
                '12': '48px',
                '16': '64px',
                '20': '80px',
            },
            transition: {
                fast: 'all 0.15s ease',
                normal: 'all 0.25s ease',
            },
            zIndex: {
                navbar: '50',
                modal: '100',
                tooltip: '200',
            },
        },
    },

    plugins: [forms],
};
