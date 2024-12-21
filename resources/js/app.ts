import '@styles/index.module.css';
import '../css/app.css';
import './bootstrap';
import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import InertiaAppHelper from '@helpers/inertiaAppHelper';

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
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
