import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/auth.js',
                'resources/js/ai.js',
                'resources/js/meal-log.js',
                'resources/js/toast.js',
                'resources/js/recipe.js',
                'resources/js/forgot-password.js',
                'resources/js/password-toggle.js',
                'resources/js/home.js',
                'resources/js/mobile-nav.js',
                'resources/js/app-layout.js',
                'resources/js/bootstrap.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: 'localhost',
        hmr: {
            host: 'localhost',
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
