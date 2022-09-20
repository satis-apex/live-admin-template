import { defineConfig } from 'vite';
import path from 'path';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            reactivityTransform: true,//experimintal
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        AutoImport({
            resolvers: [ElementPlusResolver()],
        }),
        Components({
            resolvers: [ElementPlusResolver({
                importStyle: 'sass',
            })],
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve('./resources/js'),
            '~': path.resolve('./resources'),
        },
        extensions: ['.js', '.vue', '.json'],
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@use "~/sass/app.scss" as *;`,
            },
        },
    },
});
