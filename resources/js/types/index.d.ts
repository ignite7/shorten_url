import { Config } from "ziggy-js";
import IUser from "@interfaces/IUser";

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: IUser;
  };
  ziggy: Config & { location: string };
};
