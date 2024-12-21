import Layout from '@layouts/index.vue';
import { DefineComponent } from 'vue';

export default class InertiaAppHelper {
  public static resolve(
    name: string,
    pages: Record<string, DefineComponent>
  ): DefineComponent {
    const page: DefineComponent | undefined = pages[`./pages/${name}.vue`];

    if (!page) {
      throw new Error(`Page "${name}" not found.`);
    }

    page.default.layout = page.default.layout || Layout;

    return page;
  }
}
