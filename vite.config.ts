import { defineConfig } from "vite";
import i18n from "@intlify/unplugin-vue-i18n/vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
import swc from "unplugin-swc";
import vuetify, { transformAssetUrls } from "vite-plugin-vuetify";
import path from "path";

export default defineConfig({
    build: {
        chunkSizeWarningLimit: 700,
        rolldownOptions: {
            output: {
                manualChunks: (id) => {
                    if (id.includes("vue-advanced-chat")) {
                        return "advanced-chat";
                    }

                    return null;
                },
            },
        },
        sourcemap: true,
    },
    define: {
        __VUE_PROD_DEVTOOLS__: true,
    },
    plugins: [
        laravel({
            input: ["resources/js/app.ts", "resources/js/win.ts"],
            refresh: true,
            //assets: "resources/images/**",
        }),
        tailwindcss(),
        vue({
            template: {
                compilerOptions: {
                    isCustomElement: (tag) => tag === "vue-advanced-chat" || tag === "emoji-picker",
                },
                transformAssetUrls,
            },
        }),
        swc.vite({
            include: /\.(ts|tsx|js|jsx|vue\?vue&type=script)/,
            jsc: {
                parser: {
                    syntax: "typescript",
                    decorators: true,
                },
                transform: {
                    decoratorVersion: "2022-03",
                },
                target: "es2022",
            },
        }),
        vuetify({
            autoImport: true,
        }),
        i18n({
            include: [path.resolve(import.meta.dirname, "./resources/js/locales/**")],
        }),
    ],
});
