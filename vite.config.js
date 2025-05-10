import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'public/vendor/adminlte/dist/css/adminlte.min.css',
                'public/vendor/adminlte/dist/js/adminlte.min.js',
            ],
            refresh: true,
        }),
    ],
}); 