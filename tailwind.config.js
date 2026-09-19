import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import plugin from 'tailwindcss/plugin';

/*
 * Dark theme (toggled with the `dark` class on <html>, see partials/theme-init).
 *
 * The views are written with light-only utilities (bg-white, text-gray-*, bg-green-50 ...).
 * Rather than adding dark: variants to every class, the neutral and tint utilities are
 * remapped here under `.dark`. The remaps carry higher specificity than the utilities they
 * replace, and Breeze's own dark: variants (same specificity, later in the file) still win.
 */
const darkTheme = plugin(({ addBase }) => {
    const c = { page: '#0c111d', surface: '#101828', raised: '#1d2939', strong: '#344054', text: '#f2f4f7' };
    const rules = {
        '.dark': { backgroundColor: c.page },
        '.dark *, .dark ::before, .dark ::after': { borderColor: c.raised },
    };
    const on = (selector, declarations) => {
        rules[`.dark ${selector}`] = declarations;
    };

    // Neutral backgrounds
    const bg = { white: c.surface, 'gray-25': c.surface, 'gray-50': c.page, 'gray-100': c.raised, 'gray-200': c.strong };
    const bgHover = { white: c.raised, 'gray-50': c.raised, 'gray-100': c.raised, 'gray-200': c.strong };
    for (const [name, value] of Object.entries(bg)) on(`.bg-${name}`, { backgroundColor: value });
    for (const [name, value] of Object.entries(bgHover)) on(`.hover\\:bg-${name}:hover`, { backgroundColor: value });
    on('.bg-white\\/95', { backgroundColor: 'rgb(16 24 40 / 0.92)' });
    on('.disabled\\:bg-gray-100:disabled', { backgroundColor: c.raised });

    // Neutral text
    const text = { 'gray-900': c.text, 'gray-800': '#e4e7ec', 'gray-700': '#d0d5dd', 'gray-600': '#b4bcc9', 'gray-500': '#98a2b3', 'gray-400': '#7b879c', 'gray-300': '#475467' };
    const textHover = { 'gray-900': '#ffffff', 'gray-800': '#ffffff', 'gray-700': c.text, 'gray-600': '#e4e7ec', 'gray-500': '#d0d5dd' };
    for (const [name, value] of Object.entries(text)) on(`.text-${name}`, { color: value });
    for (const [name, value] of Object.entries(textHover)) on(`.hover\\:text-${name}:hover`, { color: value });

    // Neutral borders and dividers
    const border = { 'gray-50': c.raised, 'gray-100': '#182233', 'gray-200': c.raised, 'gray-300': c.strong };
    for (const [name, value] of Object.entries(border)) {
        on(`.border-${name}`, { borderColor: value });
        on(`.divide-${name} > :not([hidden]) ~ :not([hidden])`, { borderColor: value });
    }
    on('.hover\\:border-gray-300:hover', { borderColor: '#475467' });

    // Tinted backgrounds / text / borders. [rgb, lighter text, lightest text]
    const tints = {
        indigo: ['70 95 255', '#8aa4ff', '#b4c8ff'],
        green: ['34 197 94', '#4ade80', '#86efac'],
        emerald: ['16 185 129', '#34d399', '#6ee7b7'],
        red: ['239 68 68', '#f87171', '#fca5a5'],
        amber: ['245 158 11', '#fbbf24', '#fcd34d'],
        orange: ['249 115 22', '#fb923c', '#fdba74'],
        blue: ['59 130 246', '#60a5fa', '#93c5fd'],
        yellow: ['234 179 8', '#facc15', '#fde047'],
        purple: ['168 85 247', '#c084fc', '#d8b4fe'],
        success: ['18 183 106', '#32d583', '#6ce9a6'],
        error: ['240 68 56', '#f97066', '#fda29b'],
        warning: ['247 144 9', '#fdb022', '#fec84b'],
        'blue-light': ['11 165 236', '#36bffa', '#7cd4fd'],
    };
    for (const [name, [rgb, light, lighter]] of Object.entries(tints)) {
        on(`.bg-${name}-50`, { backgroundColor: `rgb(${rgb} / 0.12)` });
        on(`.bg-${name}-100`, { backgroundColor: `rgb(${rgb} / 0.18)` });
        on(`.bg-${name}-200`, { backgroundColor: `rgb(${rgb} / 0.24)` });
        for (const shade of [50, 100]) on(`.hover\\:bg-${name}-${shade}:hover`, { backgroundColor: `rgb(${rgb} / 0.2)` });
        for (const shade of [100, 200, 300]) on(`.border-${name}-${shade}`, { borderColor: `rgb(${rgb} / 0.3)` });
        for (const shade of [600, 700]) on(`.text-${name}-${shade}`, { color: light });
        for (const shade of [800, 900]) on(`.text-${name}-${shade}`, { color: lighter });
        for (const shade of [700, 800, 900]) on(`.hover\\:text-${name}-${shade}:hover`, { color: lighter });
    }
    on('.text-indigo-500', { color: '#8aa4ff' });
    on('.bg-indigo-50\\/60', { backgroundColor: 'rgb(70 95 255 / 0.1)' });
    on('.bg-indigo-50\\/50', { backgroundColor: 'rgb(70 95 255 / 0.1)' });

    // Sidebar component classes (defined with @apply in app.css)
    on('.menu-item-active', { backgroundColor: 'rgb(70 95 255 / 0.16)', color: '#9cb9ff' });
    on('.menu-item-inactive', { color: '#d0d5dd' });
    on('.menu-item-inactive:hover', { backgroundColor: c.raised, color: c.text });
    on('.menu-item-icon-active', { color: '#9cb9ff' });
    on('.menu-item-icon-inactive', { color: '#98a2b3' });
    on('.group:hover .menu-item-icon-inactive', { color: c.text });
    on('.custom-scrollbar::-webkit-scrollbar-thumb', { backgroundColor: c.strong });

    // Form controls (the forms plugin hardcodes a white background)
    rules['html.dark :where(input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([type="range"]):not([type="color"]):not([type="file"]):not([type="image"]), select, textarea)'] = {
        backgroundColor: c.page,
        color: c.text,
    };
    rules['html.dark :where(input[type="checkbox"], input[type="radio"]):not(:checked)'] = {
        backgroundColor: c.page,
        borderColor: '#475467',
    };
    rules['.dark input:-webkit-autofill, .dark textarea:-webkit-autofill, .dark select:-webkit-autofill'] = {
        WebkitBoxShadow: `0 0 0 1000px ${c.page} inset`,
        WebkitTextFillColor: c.text,
        caretColor: c.text,
    };

    // Select2
    on('.select2-container--default .select2-selection--single, .dark .select2-container--default .select2-selection--multiple', { backgroundColor: c.page, borderColor: c.strong });
    on('.select2-container--default .select2-selection--single .select2-selection__rendered', { color: c.text });
    on('.select2-container--default .select2-selection__placeholder', { color: '#7b879c' });
    on('.select2-dropdown', { backgroundColor: c.surface, borderColor: c.strong, color: c.text });
    on('.select2-container--default .select2-search--dropdown .select2-search__field', { backgroundColor: c.page, color: c.text, borderColor: c.strong });
    on('.select2-container--default .select2-results__option--selected, .dark .select2-container--default .select2-results__option[aria-selected="true"]', { backgroundColor: c.raised });
    on('.select2-container--default .select2-results__option--highlighted, .dark .select2-container--default .select2-results__option--highlighted[aria-selected]', { backgroundColor: '#465fff', color: '#ffffff' });

    addBase(rules);
});

/** @type {import('tailwindcss').Config} */
export default {
    // The app is light-only; without this Tailwind follows the OS setting and the
    // shared dark: input styles turn every field black on phones in dark mode.
    darkMode: 'class',

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

    plugins: [forms, darkTheme],
};
