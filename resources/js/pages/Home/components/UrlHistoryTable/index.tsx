import DataTable from '@/components/DataTable';
import IHomePageProps from '@/interfaces/IHomePageProps';
import columns from '@/pages/Home/components/UrlHistoryTable/columns';
import { usePage } from '@inertiajs/react';

export default function UrlHistoryTable() {
  const { urls } = usePage<IHomePageProps>().props;
  const params = new URLSearchParams(window.location.search);
  const order: string | null = params.get('order');
  const orderBy: string | null = params.get('orderBy');

  return (
    <DataTable
      columns={columns}
      data={urls.data}
      links={urls.links}
      meta={urls.meta}
      only={['urls']}
      params={{
        ...(order ? { order } : {}),
        ...(orderBy ? { orderBy } : {}),
      }}
      showTableWhenNoData={false}
    />
  );
}
