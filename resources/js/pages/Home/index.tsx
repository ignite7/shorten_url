import { PageProps } from '@/types';

interface IProps extends PageProps {
  name: string;
}

export default function Home({ name }: IProps) {
  return (
    <div>
      <h1>Welcome Home: {name}</h1>
    </div>
  );
}
