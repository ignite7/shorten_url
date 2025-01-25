// noinspection JSIgnoredPromiseFromCall

import '../css/app.css';
import './bootstrap';

import InertiaAppHelper from '@/helpers/inertiaAppHelper';
import PageModuleType from '@/types/PageModuleType';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot, hydrateRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'Shorten URL';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => {
    const pages = import.meta.glob('./pages/**/*.tsx') as PageModuleType;
    return InertiaAppHelper.resolve(name, pages);
  },
  setup({ el, App, props }) {
    if (import.meta.env.SSR) {
      hydrateRoot(el, <App {...props} />);
      return;
    }

    createRoot(el).render(<App {...props} />);
  },
  progress: {
    color: '#4B5563',
  },
});
