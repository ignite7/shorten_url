import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
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
      input: 'resources/js/app.tsx',
      ssr: 'resources/js/ssr.tsx',
      refresh: true,
    }),
    react(),
    checker({
      typescript: true,
    }),
  ],
  css: {
    modules: {
      localsConvention: 'camelCase',
    },
  },
  // resolve: {
  //   alias: {
  //     '@components': path.resolve(__dirname, 'resources/js/components'),
  //     '@helpers': path.resolve(__dirname, 'resources/js/helpers'),
  //     '@interfaces': path.resolve(__dirname, 'resources/js/interfaces'),
  //     '@layouts': path.resolve(__dirname, 'resources/js/layouts'),
  //     '@types': path.resolve(__dirname, 'resources/js/types'),
  //     '@pages': path.resolve(__dirname, 'resources/js/pages'),
  //   },
  // },
});
