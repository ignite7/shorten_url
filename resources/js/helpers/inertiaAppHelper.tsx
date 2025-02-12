import PageModule from '@/interfaces/IPageModule';
import SingleColumnLayout from '@/layouts/SingleColumnLayout';
import PageModuleType from '@/types/PageModuleType';
import React from 'react';

export default class InertiaAppHelper {
  public static async resolve(
    name: string,
    pages: PageModuleType
  ): Promise<PageModule> {
    const pageImport = pages[`./pages/${name}.tsx`];

    if (!pageImport) {
      throw new Error(`Page ${name} not found.`);
    }

    const pageModule = (await pageImport()) as PageModule;
    const Page = pageModule.default;

    Page.layout =
      Page.layout ||
      ((page: React.ReactNode) => (
        <SingleColumnLayout>{page}</SingleColumnLayout>
      ));

    return pageModule;
  }
}
