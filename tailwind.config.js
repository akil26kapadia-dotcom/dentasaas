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
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                'title-sm': ['30px', '38px'],
                'title-md': ['36px', '44px'],
            },
            // TailAdmin's design tokens (https://tailadmin.com) — 'indigo' and 'gray'
            // are overridden so every existing bg-indigo-*/text-gray-* class across
            // the app picks these up automatically, no per-view changes needed.
            colors: {
                indigo: {
                    25: '#f2f7ff',
                    50: '#ecf3ff',
                    100: '#dde9ff',
                    200: '#c2d6ff',
                    300: '#9cb9ff',
                    400: '#7592ff',
                    500: '#465fff',
                    600: '#3641f5',
                    700: '#2a31d8',
                    800: '#252dae',
                    900: '#262e89',
                    950: '#161950',
                },
                gray: {
                    25: '#fcfcfd',
                    50: '#f9fafb',
                    100: '#f2f4f7',
                    200: '#e4e7ec',
                    300: '#d0d5dd',
                    400: '#98a2b3',
                    500: '#667085',
                    600: '#475467',
                    700: '#344054',
                    800: '#1d2939',
                    900: '#101828',
                    950: '#0c111d',
                },
                'blue-light': {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    500: '#0ba5ec',
                    600: '#0086c9',
                },
                success: {
                    50: '#ecfdf3',
                    100: '#d1fadf',
                    500: '#12b76a',
                    600: '#039855',
                },
                error: {
                    50: '#fef3f2',
                    100: '#fee4e2',
                    500: '#f04438',
                    600: '#d92d20',
                },
                warning: {
                    50: '#fffaeb',
                    100: '#fef0c7',
                    500: '#f79009',
                    600: '#dc6803',
                },
            },
            boxShadow: {
                'theme-xs': '0px 1px 2px 0px rgba(16, 24, 40, 0.05)',
                'theme-sm': '0px 1px 3px 0px rgba(16, 24, 40, 0.1), 0px 1px 2px 0px rgba(16, 24, 40, 0.06)',
                'theme-md': '0px 4px 8px -2px rgba(16, 24, 40, 0.1), 0px 2px 4px -2px rgba(16, 24, 40, 0.06)',
                'theme-lg': '0px 12px 16px -4px rgba(16, 24, 40, 0.08), 0px 4px 6px -2px rgba(16, 24, 40, 0.03)',
                'theme-xl': '0px 20px 24px -4px rgba(16, 24, 40, 0.08), 0px 8px 8px -4px rgba(16, 24, 40, 0.03)',
            },
        },
    },

    plugins: [forms],
};
