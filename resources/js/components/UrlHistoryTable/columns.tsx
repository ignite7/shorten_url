import { Button } from '@/components/ui/button';
import DateFormat from '@/enums/DateFormat';
import IUrl from '@/interfaces/IUrl';
import { router } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';
import dayjs from 'dayjs';
import { ArrowUpDown, Copy } from 'lucide-react';
import { toast } from 'sonner';

const copyToClipboard = (text: string): void => {
  navigator.clipboard.writeText(text);
  toast.success('Copied to clipboard');
};

const columns: ColumnDef<IUrl>[] = [
  {
    accessorKey: 'id',
    header: 'Short Link',
    cell: ({ row }) => {
      const id: string = row.getValue('id');
      // TODO: use route() when the route is available
      const shortLink: string = `${window.location.origin}/${id}`;

      return (
        <div className={'flex items-center gap-2'}>
          <div title={shortLink}>{id}</div>
          <Copy
            size={16}
            className={'cursor-pointer'}
            onClick={() => copyToClipboard(shortLink)}
          />
        </div>
      );
    },
  },
  {
    accessorKey: 'source',
    header: 'Original Link',
    cell: ({ row }) => {
      const source: string = row.getValue('source');

      return (
        <div className={'flex items-center gap-2'}>
          <div className={'max-w-96 truncate'} title={source}>
            {source}
          </div>
          <Copy
            size={16}
            className={'cursor-pointer'}
            onClick={() => copyToClipboard(source)}
          />
        </div>
      );
    },
  },
  {
    accessorKey: 'created_at',
    header: () => {
      const handleSort = (): void => {
        const params = new URLSearchParams(window.location.search);
        const order: string = params.get('order') ?? 'desc';
        const page: string | null = params.get('page');
        router.visit(route('home'), {
          only: ['urls'],
          preserveScroll: true,
          data: {
            order: order === 'desc' ? 'asc' : 'desc',
            ...(page ? { page } : {}),
          },
        });
      };

      return (
        <Button variant={'ghost'} onClick={handleSort}>
          Date <ArrowUpDown />
        </Button>
      );
    },
    cell: ({ row }): string => {
      const createdAt: string = row.getValue('created_at');

      return dayjs(createdAt).format(DateFormat.READABLE_DATE);
    },
  },
];

export default columns;
