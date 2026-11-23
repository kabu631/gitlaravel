import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/**
 * Semantic colour tokens resolve to CSS custom properties (see resources/css/app.css),
 * so a single class such as `bg-surface` or `text-fg-muted` is correct in both themes
 * and component markup does not need a parallel set of `dark:` overrides.
 */
const token = (name) => `rgb(var(--${name}) / <alpha-value>)`;

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
                sans: ['"Mona Sans Variable"', ...defaultTheme.fontFamily.sans],
                // Kept as an alias so older markup using `font-heading` stays on the same family.
                heading: ['"Mona Sans Variable"', ...defaultTheme.fontFamily.sans],
                mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Consolas', 'monospace'],
            },
            fontSize: {
                // 13px is the floor for metadata; nothing in the UI goes below 12px.
                '2xs': ['0.75rem', { lineHeight: '1rem' }],
                xs: ['0.8125rem', { lineHeight: '1.125rem' }],
                sm: ['0.875rem', { lineHeight: '1.375rem' }],
                base: ['0.9375rem', { lineHeight: '1.5rem' }],
                lg: ['1.0625rem', { lineHeight: '1.625rem' }],
                xl: ['1.25rem', { lineHeight: '1.75rem', letterSpacing: '-0.01em' }],
                '2xl': ['1.5rem', { lineHeight: '2rem', letterSpacing: '-0.015em' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem', letterSpacing: '-0.02em' }],
                '4xl': ['2.25rem', { lineHeight: '2.625rem', letterSpacing: '-0.025em' }],
                '5xl': ['3rem', { lineHeight: '3.25rem', letterSpacing: '-0.03em' }],
            },
            colors: {
                canvas: token('canvas'),
                surface: {
                    DEFAULT: token('surface'),
                    2: token('surface-2'),
                    3: token('surface-3'),
                },
                line: {
                    DEFAULT: token('line'),
                    strong: token('line-strong'),
                },
                fg: {
                    DEFAULT: token('fg'),
                    muted: token('fg-muted'),
                    subtle: token('fg-subtle'),
                    faint: token('fg-faint'),
                },
                accent: {
                    DEFAULT: token('accent'),
                    text: token('accent-text'),
                    soft: token('accent-soft'),
                },
                positive: {
                    DEFAULT: token('positive'),
                    soft: token('positive-soft'),
                },
                negative: {
                    DEFAULT: token('negative'),
                    soft: token('negative-soft'),
                },
                // Git Infosys logo orange (#FF991B). Prefer the `accent` tokens in new markup.
                brand: {
                    50:  '#fff8ec',
                    100: '#ffeed3',
                    200: '#ffdca4',
                    300: '#ffc36b',
                    400: '#ffa631',
                    500: '#ff991b',
                    600: '#e57c09',
                    700: '#be5c06',
                    800: '#97460c',
                    900: '#7a390e',
                    950: '#421a03',
                },
                // Git Infosys logo navy (#232F3F).
                navy: {
                    50:  '#f3f6f9',
                    100: '#e3e8ef',
                    200: '#c8d3df',
                    300: '#9fb3c7',
                    400: '#6f8dab',
                    500: '#4f7091',
                    600: '#3c5673',
                    700: '#31445b',
                    800: '#232f3f',
                    900: '#19222e',
                    950: '#0e141c',
                },
            },
            borderRadius: {
                DEFAULT: '0.375rem',
            },
            boxShadow: {
                xs: '0 1px 2px 0 rgb(16 24 40 / 0.05)',
                sm: '0 1px 2px 0 rgb(16 24 40 / 0.06), 0 1px 3px 0 rgb(16 24 40 / 0.04)',
                md: '0 4px 8px -2px rgb(16 24 40 / 0.08), 0 2px 4px -2px rgb(16 24 40 / 0.04)',
                lg: '0 12px 24px -6px rgb(16 24 40 / 0.12), 0 4px 8px -4px rgb(16 24 40 / 0.05)',
                xl: '0 20px 40px -12px rgb(16 24 40 / 0.18)',
                popover: '0 10px 30px -8px rgb(16 24 40 / 0.18), 0 0 0 1px rgb(var(--line) / 1)',
            },
            maxWidth: {
                prose: '68ch',
            },
            transitionTimingFunction: {
                out: 'cubic-bezier(0.22, 1, 0.36, 1)',
            },
            typography: ({ theme }) => ({
                DEFAULT: {
                    css: {
                        '--tw-prose-body': 'rgb(var(--fg-muted))',
                        '--tw-prose-headings': 'rgb(var(--fg))',
                        '--tw-prose-lead': 'rgb(var(--fg-muted))',
                        '--tw-prose-links': 'rgb(var(--accent-text))',
                        '--tw-prose-bold': 'rgb(var(--fg))',
                        '--tw-prose-counters': 'rgb(var(--fg-subtle))',
                        '--tw-prose-bullets': 'rgb(var(--line-strong))',
                        '--tw-prose-hr': 'rgb(var(--line))',
                        '--tw-prose-quotes': 'rgb(var(--fg))',
                        '--tw-prose-quote-borders': 'rgb(var(--accent))',
                        '--tw-prose-captions': 'rgb(var(--fg-subtle))',
                        '--tw-prose-code': 'rgb(var(--fg))',
                        '--tw-prose-pre-code': 'rgb(var(--fg))',
                        '--tw-prose-pre-bg': 'rgb(var(--surface-2))',
                        '--tw-prose-th-borders': 'rgb(var(--line-strong))',
                        '--tw-prose-td-borders': 'rgb(var(--line))',
                        // `invert` is kept identical so existing `dark:prose-invert` usages stay correct.
                        '--tw-prose-invert-body': 'rgb(var(--fg-muted))',
                        '--tw-prose-invert-headings': 'rgb(var(--fg))',
                        '--tw-prose-invert-links': 'rgb(var(--accent-text))',
                        '--tw-prose-invert-bold': 'rgb(var(--fg))',
                        '--tw-prose-invert-quotes': 'rgb(var(--fg))',
                        '--tw-prose-invert-hr': 'rgb(var(--line))',
                        '--tw-prose-invert-bullets': 'rgb(var(--line-strong))',
                        '--tw-prose-invert-counters': 'rgb(var(--fg-subtle))',
                        '--tw-prose-invert-th-borders': 'rgb(var(--line-strong))',
                        '--tw-prose-invert-td-borders': 'rgb(var(--line))',
                        '--tw-prose-invert-code': 'rgb(var(--fg))',
                        '--tw-prose-invert-pre-bg': 'rgb(var(--surface-2))',
                        maxWidth: '68ch',
                        lineHeight: '1.75',
                        a: { textDecoration: 'underline', textUnderlineOffset: '3px', textDecorationThickness: '1px', fontWeight: '500' },
                        'h2, h3, h4': { letterSpacing: '-0.015em', fontWeight: '600' },
                        img: { borderRadius: theme('borderRadius.lg') },
                        'code::before': { content: 'none' },
                        'code::after': { content: 'none' },
                        code: { fontWeight: '500', backgroundColor: 'rgb(var(--surface-2))', padding: '0.15em 0.35em', borderRadius: '0.25rem' },
                    },
                },
            }),
        },
    },

    plugins: [forms, typography],
};
