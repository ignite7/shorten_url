import DataTable from '@/components/DataTable';
import columns from '@/components/UrlHistoryTable/columns';
import IHome from '@/interfaces/pages/IHome';
import { usePage } from '@inertiajs/react';

export default function UrlHistoryTable() {
  const { urls } = usePage<IHome>().props;
  const params = new URLSearchParams(window.location.search);
  const order: string | null = params.get('order');

  return (
    <DataTable
      columns={columns}
      data={urls.data}
      links={urls.links}
      meta={urls.meta}
      only={['urls']}
      params={order ? { order } : undefined}
      showTableWhenNoData={false}
    />
  );
}
