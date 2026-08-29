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
                sans: ['Space Grotesk', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    DEFAULT: '#473636',
                    50: '#EFE9E9',
                    100: '#DDD3D3',
                    200: '#BFA9A9',
                    300: '#A08484',
                    400: '#6E5555',
                    500: '#473636',
                    600: '#3F3030',
                    700: '#332828',
                    800: '#272020',
                    900: '#1B1616',
                },
            },
        },
    },

    plugins: [forms],
};
