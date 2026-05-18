import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Logo orange/amber accent (#F5A623)
                brand: {
                    50:  '#fff8ed',
                    100: '#ffefd4',
                    200: '#fddaa8',
                    300: '#fcc16e',
                    400: '#f9a12b',
                    500: '#f5a623',
                    600: '#e0861a',
                    700: '#b96610',
                    800: '#934f12',
                    900: '#784213',
                    950: '#451f05',
                },
                // Logo dark navy (#263248)
                navy: {
                    50:  '#eef1f6',
                    100: '#d9dfea',
                    200: '#b7c1d8',
                    300: '#8a9bbf',
                    400: '#6678a6',
                    500: '#4d5f8d',
                    600: '#3d4e74',
                    700: '#32405f',
                    800: '#263248',
                    900: '#1a2234',
                    950: '#111520',
                },
            },
            typography: {
                invert: {
                    css: {
                        '--tw-prose-body': '#d1d5db',
                        '--tw-prose-headings': '#f9fafb',
                        '--tw-prose-links': '#f9a12b',
                    },
                },
            },
        },
    },

    plugins: [forms, typography],
};
