import InertiaAppHelper from '@/helpers/inertiaAppHelper';
import PageModuleType from '@/types/PageModuleType';
import { createInertiaApp } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import ReactDOMServer from 'react-dom/server';
import { RouteName } from 'ziggy-js';
import { route } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Shorten URL';

createServer((page) =>
  createInertiaApp({
    page,
    render: ReactDOMServer.renderToString,
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
      const pages = import.meta.glob('./pages/**/*.tsx') as PageModuleType;
      return InertiaAppHelper.resolve(name, pages);
    },
    setup: ({ App, props }) => {
      /* eslint-disable */
      // @ts-expect-error
      global.route<RouteName> = (name, params, absolute) =>
        route(name, params as any, absolute, {
          ...page.props.ziggy,
          location: new URL(page.props.ziggy.location),
        });
      /* eslint-enable */

      return <App {...props} />;
    },
  })
);
