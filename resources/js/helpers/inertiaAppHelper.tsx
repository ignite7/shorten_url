import PageModule from '@/interfaces/IPageModule';
import Layout from '@/layouts/Main';
import PageModuleType from '@/types/PageModuleType';
import React from 'react';

export default class InertiaAppHelper {
  public static async resolve(
    name: string,
    pages: PageModuleType
  ): Promise<PageModule> {
    const pageImport = pages[`./pages/${name}/index.tsx`];

    // Ensure the module exists
    if (!pageImport) {
      throw new Error(`Page ${name} not found.`);
    }

    // Import the page module dynamically
    const pageModule = (await pageImport()) as PageModule;
    const Page = pageModule.default;

    // Assign the default layout if none is specified
    Page.layout =
      Page.layout || ((page: React.ReactNode) => <Layout>{page}</Layout>);

    return pageModule;
  }
}
