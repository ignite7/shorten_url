import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';
import checker from 'vite-plugin-checker';

export default defineConfig({
  server: {
    host: true,
    port: 3000,
    strictPort: true,
    hmr: {
      host: 'localhost',
    },
  },
  plugins: [
    laravel({
      input: 'resources/js/app.ts',
      ssr: 'resources/js/ssr.ts',
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
  css: {
    modules: {
      localsConvention: 'camelCase',
    },
  },
  resolve: {
    alias: {
      '@styles': path.resolve(__dirname, 'resources/js/assets/styles'),
      '@helpers': path.resolve(__dirname, 'resources/js/helpers'),
      '@interfaces': path.resolve(__dirname, 'resources/js/interfaces'),
      '@layouts': path.resolve(__dirname, 'resources/js/layouts'),
      '@types': path.resolve(__dirname, 'resources/js/types'),
      '@pages': path.resolve(__dirname, 'resources/js/pages'),
    },
  },
});
