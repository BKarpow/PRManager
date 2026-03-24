import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { VitePWA } from "vite-plugin-pwa";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js"],
            refresh: true,
        }),

        ,
        VitePWA({
            registerType: "autoUpdate",
            injectRegister: "auto",
            workbox: {
                // Вказуємо, які файли кешувати (js, css, html, іконки)
                globPatterns: ["**/*.{js,scss,css,html,ico,png,svg}"],

                // Налаштування стратегії для сторонніх JS (наприклад, з CDN)
                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/cdn\.jsdelivr\.net\/.*/i,
                        handler: "CacheFirst",
                        options: {
                            cacheName: "external-js-cache",
                            expiration: {
                                maxEntries: 10,
                                maxAgeSeconds: 60 * 60 * 24 * 365, // 1 рік
                            },
                            cacheableResponse: {
                                statuses: [0, 200],
                            },
                        },
                    },
                ],
            },
            manifest: {
                name: "Мої терміни",
                short_name: "Терміни",
                description: "Список ваших термінів придатності.",
                theme_color: "#ffffff",
                icons: [
                    {
                        src: "/icons/i256.png",
                        sizes: "256x256",
                        type: "image/png",
                    },
                    {
                        src: "/icons/i512.png",
                        sizes: "512x512",
                        type: "image/png",
                    },
                ],
            },
        }),

        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
});
