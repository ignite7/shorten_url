interface ILink {
  first: string;
  last: string;
  prev: string | null;
  next: string | null;
}

interface IMetaLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface IMeta {
  current_page: number;
  from: number;
  last_page: number;
  links: IMetaLink[];
  path: string;
  per_page: number;
  to: number;
  total: number;
}

export default interface IPagination<IData> {
  data: IData[];
  links: ILink;
  meta: IMeta;
}
