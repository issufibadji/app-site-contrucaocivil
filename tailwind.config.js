import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // este variavel blue-custom é blue
                'blue-custom': {
                    DEFAULT: '#29D32F',
                    50: '#e6fce7',
                    100: '#c5f6c8',
                    200: '#9bee9e',
                    300: '#6fe573',
                    400: '#41dc48',
                    500: '#29D32F',
                    600: '#20a624',
                    700: '#178a1b',
                    800: '#0e6f12',
                    900: '#07590b',
                    extra: '#005241',
                    deep: '#06440f', // novo tom escuro de verde
                    darkest: '#032808', // novo tom ainda mais profundo
                },
                camel: {
                    50: '#f5f0e3',
                    100: '#e7dcc0',
                    200: '#d9c69b',
                    300: '#ccb179',
                    400: '#c0af7d', // valor principal
                    500: '#a98f63',
                    600: '#8c744f',
                    700: '#6f5a3d',
                    800: '#53412d',
                    900: '#382a1d',
                },
                mint: {
                    50: '#e9fdf0',
                    100: '#d3f9e1',
                    200: '#bdf4d2',
                    300: '#a6e4b8', // valor principal
                    400: '#84d6a4',
                    500: '#65c28d',
                    600: '#49a674',
                    700: '#36815a',
                    800: '#266043',
                    900: '#1a402e',
                },
                vanilla: {
                    50: '#fffdeb',
                    100: '#fff9cf',
                    200: '#fff5b4',
                    300: '#fff199',
                    400: '#ffee7e',
                    500: '#ffe863',
                    600: '#e6cf51',
                    700: '#ccb63f',
                    800: '#b39e2e',
                    900: '#99861d',
                    950: '#7a6a14',
                },

                'blue-custom': {
                    DEFAULT: '#1D4ED8', // azul principal (similar ao blue-700 do Tailwind)
                    50: '#EFF6FF',
                    100: '#DBEAFE',
                    200: '#BFDBFE',
                    300: '#93C5FD',
                    400: '#60A5FA',
                    500: '#3B82F6',
                    600: '#2563EB',
                    700: '#1D4ED8',
                    800: '#1E40AF',
                    900: '#1E3A8A',
                    extra: '#2563EB', // azul escuro extra
                    deep: '#102A65', // azul escuro extra
                    darkest: '#0A1B3D' // azul ainda mais profundo
                },
            },
        },
    },

    plugins: [forms],
};
