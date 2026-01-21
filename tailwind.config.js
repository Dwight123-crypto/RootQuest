import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['JetBrains Mono', 'Fira Code', 'monospace', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                hacker: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                terminal: {
                    bg: '#0a0a0a',
                    surface: '#111111',
                    border: '#1a1a1a',
                    text: '#00ff00',
                    dim: '#00aa00',
                },
            },
        },
    },

    plugins: [forms],
};
