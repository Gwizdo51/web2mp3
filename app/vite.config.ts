import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';
import fs from 'fs';
import path from 'path';

export default defineConfig({
    // https://laracasts.com/discuss/channels/vite/docker-vite-blocked-by-cors-policy
    server: {
        host: '0.0.0.0', // exposes vite dev server to the network
        port: 5173,
        strictPort: true, // vite should fail if the 5173 port is not available
        cors: {
            // origin: 'http://100.100.151.68', // the IP address of the server
            origin: 'https://yt2mp3.100.100.151.68.nip.io', // the IP address of the server
            credentials: true,
        },
        hmr: {
            // host: '100.100.151.68' // the IP address of the server
            host: 'yt2mp3.100.100.151.68.nip.io', // the IP address of the server
        },
        https: {
            cert: fs.readFileSync(path.resolve(__dirname, '/mnt/self.crt')),
            key: fs.readFileSync(path.resolve(__dirname, '/mnt/self.key')),
        }
    },
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
