import { router } from '@inertiajs/react';
import { ArrowUpDown } from 'lucide-react';

interface IProps {
  title: string;
  column: string;
}

export default function SortableHeader({ title, column }: IProps) {
  const params = new URLSearchParams(window.location.search);
  const page: string | null = params.get('page');
  const order: string = params.get('order') ?? 'desc';
  const orderBy: string = params.get('orderBy') ?? 'date'; // Default sorting column
  const isSorted: boolean = orderBy === column;

  const handleSort = (): void => {
    router.visit(route('home'), {
      only: ['urls'],
      preserveScroll: true,
      data: {
        ...(page ? { page } : {}),
        order: order === 'desc' ? 'asc' : 'desc',
        orderBy: column,
      },
    });
  };

  return (
    <div
      className={
        'flex cursor-pointer items-center gap-2 ' +
        (isSorted ? 'text-primary' : '')
      }
      onClick={handleSort}
    >
      {title} <ArrowUpDown size={16} />
    </div>
  );
}
