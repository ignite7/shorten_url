import UrlStatus from '@/enums/UrlStatus';

interface IUrl {
  id: string;
  source: string;
  clicks: number;
  status: UrlStatus;
  created_at: string;
}

export default IUrl;
