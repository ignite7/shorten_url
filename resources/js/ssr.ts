import InertiaAppHelper from '@helpers/inertiaAppHelper';
import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { createSSRApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Shorten URL';

createServer((page) =>
  createInertiaApp({
    page,
    render: renderToString,
    title: (title: string) => `${title} - ${appName}`,
    resolve: (name: string) => {
      const pages: Record<string, DefineComponent> = import.meta.glob(
        './pages/**/*.vue',
        { eager: true },
      );

      return InertiaAppHelper.resolve(name, pages);
    },
    setup({ App, props, plugin }) {
      return createSSRApp({ render: () => h(App, props) })
        .use(plugin)
        .use(ZiggyVue, {
          ...page.props.ziggy,
          location: new URL(page.props.ziggy.location),
        });
    },
  }),
);
