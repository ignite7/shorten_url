import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";
import checker from "vite-plugin-checker";
import path from "path";

export default defineConfig({
  server: {
    host: true,
    port: 3000,
    strictPort: true,
    hmr: {
      host: "localhost",
    },
  },
  plugins: [
    laravel({
      input: "resources/js/app.ts",
      ssr: "resources/js/ssr.ts",
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    checker({
      vueTsc: true,
    }),
  ],
  resolve: {
    alias: {
      "@styles": path.resolve(__dirname, "resources/js/assets/styles"),
      "@interfaces": path.resolve(__dirname, "resources/js/interfaces"),
      "@types": path.resolve(__dirname, "resources/js/types"),
      "@pages": path.resolve(__dirname, "resources/js/pages"),
    },
  },
});
