import { defineConfig } from 'vite';
import { resolve } from 'path';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'
const projectRootDir = resolve(__dirname);
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/admin.js',
                'resources/js/app.js',
            ],
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
            '@': resolve(projectRootDir, 'resources/js'),
            '~': resolve(projectRootDir, 'resources'),
        },
        extensions: ['.js', '.vue', '.json'],
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@use "~/sass/element/index.scss" as *;`,
            },
        },
    },
    build: {
        sourcemap: false,
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    if (id.includes("node_modules")) {
                        if (id.includes("@element-plus/icons-vue")) {
                            return "@element-plus/icons-vue";
                        } else if (id.includes("@fortawesome/fontawesome-svg-core")) {
                            return "@fortawesome/fontawesome-svg-core";
                        } else if (id.includes("@fortawesome/free-brands-svg-icons")) {
                            return " @fortawesome/free-brands-svg-icons";
                        } else if (id.includes("@fortawesome/free-regular-svg-icons")) {
                            return "@fortawesome/free-regular-svg-icons";
                        }
                        else if (id.includes("@fortawesome/free-solid-svg-icons")) {
                            return "@fortawesome/free-solid-svg-icons";
                        } else if (id.includes("@fortawesome/vue-fontawesome")) {
                            return "@fortawesome/vue-fontawesome";
                        }
                        return id.toString().split('node_modules/')[1].split('/')[0].toString();// all other package goes here
                    }
                },
            },
        },
    },
});
