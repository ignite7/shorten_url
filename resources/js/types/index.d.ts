import IFlash from '@/interfaces/IFlash';
import IUser from '@/interfaces/IUser';
import { Config } from 'ziggy-js';

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: IUser;
  };
  ziggy: Config & { location: string };
  flash: IFlash;
};
