export interface ILink {
  first: string;
  last: string;
  prev: string | null;
  next: string | null;
}

export interface IMetaLink {
  url: string | null;
  label: string;
  active: boolean;
}

export interface IMeta {
  current_page: number;
  from: number;
  last_page: number;
  links: IMetaLink[];
  path: string;
  per_page: number;
  to: number;
  total: number;
}

export default interface IPagination<TData> {
  data: TData[];
  links: ILink;
  meta: IMeta;
}
