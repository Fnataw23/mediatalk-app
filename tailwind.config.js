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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                'cohere-display': ['Space Grotesk', 'Inter', ...defaultTheme.fontFamily.sans],
                'cohere-body': ['Inter', 'Arial', ...defaultTheme.fontFamily.sans],
                'cohere-mono': ['monospace', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                'cohere-primary': '#ffffff',
                'cohere-black': '#000000',
                'cohere-ink': '#f3f3f6',
                'cohere-deep-green': '#002520',
                'cohere-dark-navy': '#04101e',
                'cohere-canvas': '#0b0b0e',
                'cohere-stone': '#161622',
                'cohere-pale-green': '#002e26',
                'cohere-pale-blue': '#051829',
                'cohere-hairline': '#1f1f23',
                'cohere-border-light': '#27272a',
                'cohere-card-border': '#1f1f23',
                'cohere-muted': '#a0a0ab',
                'cohere-slate': '#71717a',
                'cohere-body-muted': '#a0a0ab',
                'cohere-blue': '#00f5d4',
                'cohere-focus-blue': '#00e5ff',
                'cohere-coral': '#ff7759',
                'cohere-coral-soft': '#ffad9b',
                'cohere-violet': '#9b60aa',
            },
            borderRadius: {
                'cohere-xs': '4px',
                'cohere-sm': '8px',
                'cohere-md': '16px',
                'cohere-lg': '22px',
                'cohere-xl': '30px',
                'cohere-pill': '32px',
            },
        },
    },

    plugins: [forms],
};
