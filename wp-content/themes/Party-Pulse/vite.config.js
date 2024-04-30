import { defineConfig } from 'vite'
import path from 'path';

const ROOT = path.resolve('../../../')
const BASE = __dirname.replace(ROOT, '');

export default defineConfig(() => ({

    base: '',
    server:{
        protocol : "ws",
        host: "localhost",
        port: 5173
    },
    build: {
        emptyOutDir: true,
        manifest: true,
        outDir: 'build',
        assetsDir: 'assets',
        minify: false,
        rollupOptions: {
            input: [
                'resources/js/app.js',
                'resources/scss/app.scss',
            ],
            output: {
                entryFileNames: `src/[name].js`,
                chunkFileNames: `src/[name].js`,
                assetFileNames: `src/[name].[ext]`
            }
        }
    },
    resolve: {
        alias: [
            {
                find: /~(.+)/,
                replacement: process.cwd() + '/node_modules/$1'
            }
        ]
    }
}))
