import SortableHeader from '@/components/DataTable/SortableHeader';
import { Button } from '@/components/ui/button';
import DateFormat from '@/enums/DateFormat';
import ClipboardHelper from '@/helpers/clipboardHelper';
import IUrl from '@/interfaces/IUrl';
import { ColumnDef } from '@tanstack/react-table';
import dayjs from 'dayjs';
import { Copy } from 'lucide-react';

const columns: ColumnDef<IUrl>[] = [
  {
    accessorKey: 'id',
    header: 'Short Link',
    cell: ({ row }) => {
      const id: string = row.getValue('id');
      const shortLink: string = route('redirect-to-source', { url: id });

      return (
        <div className={'flex items-center gap-2'}>
          <div title={shortLink}>{id}</div>
          <Button
            variant={'ghost'}
            size={'icon'}
            onClick={() => ClipboardHelper.copy(shortLink)}
          >
            <Copy />
          </Button>
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
          <Button
            variant={'ghost'}
            size={'icon'}
            onClick={() => ClipboardHelper.copy(source)}
          >
            <Copy />
          </Button>
        </div>
      );
    },
  },
  {
    accessorKey: 'clicks',
    header: () => <SortableHeader title={'Clicks'} column={'clicks'} />,
  },
  {
    accessorKey: 'created_at',
    header: () => <SortableHeader title={'Date'} column={'date'} />,
    cell: ({ row }): string => {
      const createdAt: string = row.getValue('created_at');

      return dayjs(createdAt).format(DateFormat.READABLE_DATE);
    },
  },
];

export default columns;
