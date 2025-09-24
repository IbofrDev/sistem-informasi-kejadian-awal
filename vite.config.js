import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path'; // <-- Tambahkan ini

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // v-- TAMBAHKAN SELURUH BLOK 'resolve' DI BAWAH INI --v
    resolve: {
        alias: {
            'jquery': path.resolve(__dirname, 'node_modules/jquery/dist/jquery.min.js'),
        },
    },
    // ^-- SAMPAI DI SINI --^
});
