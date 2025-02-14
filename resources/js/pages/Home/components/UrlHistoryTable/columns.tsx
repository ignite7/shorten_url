import SortableHeader from '@/components/DataTable/SortableHeader';
import { Badge } from '@/components/ui/badge';
import DateFormat from '@/enums/DateFormat';
import UrlStatus from '@/enums/UrlStatus';
import IUrl from '@/interfaces/IUrl';
import DropdownMenu from '@/pages/Home/components/UrlHistoryTable/DropdownMenu';
import { ColumnDef } from '@tanstack/react-table';
import dayjs from 'dayjs';

const href: string = route('home');
const defaultOrderBy: string = 'date';
const only: string[] = ['urls'];

const columns: ColumnDef<IUrl>[] = [
  {
    accessorKey: 'id',
    header: 'Short Link',
    cell: ({ row }) => {
      const id: string = row.getValue('id');
      const shortLink: string = route('redirect-to-source', { url: id });

      return <div title={shortLink}>{id}</div>;
    },
  },
  {
    accessorKey: 'source',
    header: 'Original Link',
    cell: ({ row }) => {
      const source: string = row.getValue('source');

      return (
        <div className={'max-w-96 truncate'} title={source}>
          {source}
        </div>
      );
    },
  },
  {
    accessorKey: 'clicks',
    header: () => (
      <SortableHeader
        title={'Clicks'}
        column={'clicks'}
        href={href}
        defaultOrderBy={defaultOrderBy}
        only={only}
      />
    ),
  },
  {
    accessorKey: 'status',
    header: 'Status',
    cell: ({ row }) => {
      const status: string = row.getValue('status');

      return (
        <Badge
          variant={status === UrlStatus.ACTIVE ? 'default' : 'secondary'}
          className={'capitalize'}
        >
          {status}
        </Badge>
      );
    },
  },
  {
    accessorKey: 'created_at',
    header: () => (
      <SortableHeader
        title={'Date'}
        column={'date'}
        href={href}
        defaultOrderBy={defaultOrderBy}
        only={only}
      />
    ),
    cell: ({ row }): string => {
      const createdAt: string = row.getValue('created_at');

      return dayjs(createdAt).format(DateFormat.READABLE_DATE);
    },
  },
  {
    accessorKey: 'actions',
    header: () => null,
    cell: ({ row }) => <DropdownMenu row={row} />,
  },
];

export default columns;
