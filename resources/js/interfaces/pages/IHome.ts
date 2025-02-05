import IPagination from '@/interfaces/IPagination';
import IUrl from '@/interfaces/IUrl';
import { PageProps } from '@/types';

interface IHome extends PageProps {
  lastShortenedUrl: string | null;
  anonymousToken: string | null;
  urls: IPagination<IUrl>;
}

export default IHome;
