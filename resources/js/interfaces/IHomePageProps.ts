import IPagination from '@/interfaces/IPagination';
import IUrl from '@/interfaces/IUrl';
import { PageProps } from '@/types';

interface IHomePageProps extends PageProps {
  lastShortenedUrl: string | null;
  anonymousToken: string | null;
  urls: IPagination<IUrl>;
  order: string | null;
  page: string | null;
}

export default IHomePageProps;
