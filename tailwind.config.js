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
                sans: ['"Plus Jakarta Sans"', 'Figtree', ...defaultTheme.fontFamily.sans],
                heading: ['Outfit', '"Plus Jakarta Sans"', 'sans-serif'],
            },
            colors: {
                // Official Git Infosys Logo Vibrant Orange Accent (#FF991B)
                brand: {
                    50:  '#fff8ec',
                    100: '#ffeed3',
                    200: '#ffdca4',
                    300: '#ffc36b',
                    400: '#ffa631',
                    500: '#ff991b', // Exact Git Infosys logo arrow accent
                    600: '#e57c09',
                    700: '#be5c06',
                    800: '#97460c',
                    900: '#7a390e',
                    950: '#421a03',
                },
                // Official Git Infosys Logo Deep Slate Navy (#232F3F)
                navy: {
                    50:  '#f3f6f9',
                    100: '#e3e8ef',
                    200: '#c8d3df',
                    300: '#9fb3c7',
                    400: '#6f8dab',
                    500: '#4f7091',
                    600: '#3c5673',
                    700: '#31445b',
                    800: '#232f3f', // Exact Git Infosys logo primary dark
                    900: '#19222e', // Logo deep charcoal navy
                    950: '#0e141c', // Logo deep dark surface
                },
            },
            boxShadow: {
                'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'glow-brand': '0 0 25px -5px rgba(255, 153, 27, 0.40)',
                'glow-navy': '0 0 25px -5px rgba(35, 47, 63, 0.50)',
                'glow-blue': '0 0 25px -5px rgba(59, 130, 246, 0.3)',
                'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.08)',
                'glass-dark': '0 8px 32px 0 rgba(0, 0, 0, 0.37)',
            },
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
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
