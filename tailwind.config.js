const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './vendor/livewire/livewire/src/views/pagination/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/**/*.php',
        "./node_modules/flowbite/**/*.js"
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                gray: {
                    50: '#fafafa',
                    100: '#f5f5f5',
                    200: '#e5e5e5',
                    300: '#d4d4d4',
                    400: '#a3a3a3',
                    500: '#0f172a',
                },
                // Azul noche de la marca (mismo tono del header original)
                ink: {
                    DEFAULT: '#0f172a',
                    light: '#1e293b',
                    muted: '#64748b',
                },
            },

            boxShadow: {
                card: '0 1px 2px rgba(15, 23, 42, .04), 0 4px 16px rgba(15, 23, 42, .06)',
                'card-hover': '0 2px 4px rgba(15, 23, 42, .06), 0 12px 32px rgba(15, 23, 42, .12)',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'), require('@tailwindcss/typography'),
        require('flowbite/plugin')
    ],
};
