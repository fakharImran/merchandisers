import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin'; // Assuming this is the correct import path

export default defineConfig({
    outDir: 'public',
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/chart.js'
            ],
            refresh: true,
        }),
    ],
});