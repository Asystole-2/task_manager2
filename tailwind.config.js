import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        // Blade templates
        './resources/views/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',

        // JavaScript files
        './resources/js/**/*.js',

        // Vue components
        './resources/js/**/*.vue',
        './resources/js/**/*.jsx',

        // Storage views
        './storage/framework/views/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Your theme colors
                'red-theme': '#e63946',
                'purple-theme': '#6a4c93',
                'orange-theme': '#cc5500',

                // Optional: extend default colors
                gray: {
                    50: '#f9fafb',
                    // ... other gray shades
                }
            },
        },
    },
    plugins: [
        forms,
        // Add other Tailwind plugins here if needed
    ],
};
