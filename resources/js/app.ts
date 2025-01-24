import InertiaAppHelper from '@helpers/inertiaAppHelper';
import { createInertiaApp } from '@inertiajs/vue3';
import { createSSRApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import '../css/app.css';
import './bootstrap';

const appName = import.meta.env.VITE_APP_NAME || 'Shorten URL';

createInertiaApp({
  title: (title: string) => `${title} - ${appName}`,
  resolve: (name: string) => {
    const pages: Record<string, DefineComponent> = import.meta.glob(
      './pages/**/*.vue',
      { eager: true },
    );

    return InertiaAppHelper.resolve(name, pages);
  },
  setup({ el, App, props, plugin }) {
    createSSRApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
