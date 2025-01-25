import PageModule from '@/interfaces/IPageModule';

type PageModuleType = Record<string, () => Promise<PageModule>>;

export default PageModuleType;
