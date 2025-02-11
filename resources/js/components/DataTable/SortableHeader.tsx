import { router } from '@inertiajs/react';
import { ArrowUpDown } from 'lucide-react';

interface IProps {
  title: string;
  column: string;
  href: string;
  defaultOrderBy?: string;
  only?: string[];
}

export default function SortableHeader({
                                         title,
                                         column,
                                         href,
                                         defaultOrderBy,
                                         only,
                                       }: IProps) {
  const params = new URLSearchParams(window.location.search);
  const page: string | null = params.get('page');
  const order: string = params.get('order') ?? 'desc';
  const orderBy: string | undefined = params.get('orderBy') ?? defaultOrderBy;
  const isActiveSort: boolean = orderBy === column;

  const handleSort = (): void => {
    router.visit(href, {
      only,
      preserveScroll: true,
      data: {
        ...(page ? { page } : {}),
        order: isActiveSort ? (order === 'asc' ? 'desc' : 'asc') : order,
        orderBy: column,
      },
    });
  };

  return (
    <div
      className={
        'flex cursor-pointer items-center gap-2 ' +
        (isActiveSort ? 'text-primary' : '')
      }
      onClick={handleSort}
    >
      {title} <ArrowUpDown size={16} />
    </div>
  );
}
