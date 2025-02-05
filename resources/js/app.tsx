// noinspection JSIgnoredPromiseFromCall

import '../css/app.css';
import './bootstrap';

import { MediaQueryProvider } from '@/context/MediaQueryContext';
import InertiaAppHelper from '@/helpers/inertiaAppHelper';
import PageModuleType from '@/types/PageModuleType';
import { createInertiaApp } from '@inertiajs/react';
import { ReactNode } from 'react';
import { createRoot, hydrateRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'Shorten URL';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => {
    const pages = import.meta.glob('./pages/**/*.tsx') as PageModuleType;
    return InertiaAppHelper.resolve(name, pages);
  },
  setup({ el, App, props }) {
    const children: ReactNode = (
      <MediaQueryProvider>
        <App {...props} />
      </MediaQueryProvider>
    );

    if (import.meta.env.SSR) {
      hydrateRoot(el, children);
      return;
    }

    createRoot(el).render(children);
  },
  progress: {
    color: '#7C3AED',
  },
});
