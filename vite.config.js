import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // biar bisa diakses dari LAN
        port: 5173,
        cors: true,        // 🔥 IZINKAN akses cross-origin
        strictPort: true,
        hmr: {
            host: '192.168.1.30', // Ganti dengan IP WiFi kamu
        },
    },
});
