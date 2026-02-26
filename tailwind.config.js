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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Palette Modern Corporate & Clean UI
                primary: {
                    25: '#f8fbff',
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6', // Bleu professionnel
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
                accent: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e', // Vert tendre
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#145231',
                },
                secondary: {
                    50: '#f5f5f5',
                    100: '#e7e7e7',
                    200: '#d1d1d1',
                    300: '#b0b0b0',
                    400: '#888888',
                    500: '#6b7280', // Gris professionnel
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                },
                success: {
                    500: '#22c55e',
                    600: '#16a34a',
                },
                warning: {
                    500: '#eab308',
                    600: '#ca8a04',
                },
                danger: {
                    500: '#ef4444',
                    600: '#dc2626',
                },
            },
        },
    },

    plugins: [forms],
};
