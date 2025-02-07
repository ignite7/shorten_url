import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationLink,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import { useMediaQueryContext } from '@/context/MediaQueryContext';
import { ILink, IMeta, IMetaLink } from '@/interfaces/IPagination';
import { FormDataConvertible } from '@inertiajs/core';
import {
  ColumnDef,
  flexRender,
  getCoreRowModel,
  getPaginationRowModel,
  useReactTable,
} from '@tanstack/react-table';
import React from 'react';
import styles from './index.module.css';

interface IProps<TData, TValue> {
  columns: ColumnDef<TData, TValue>[];
  data: TData[];
  links: ILink;
  meta: IMeta;
  only?: string[];
  params?: Record<string, FormDataConvertible>;
  showTableWhenNoData?: boolean;
}

export default function DataTable<TData, TValue>({
  columns,
  data,
  links,
  meta,
  only = [],
  params = {},
  showTableWhenNoData = true,
}: IProps<TData, TValue>) {
  const { isMobile } = useMediaQueryContext();
  const table = useReactTable({
    data,
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
  });

  if (!data.length && !showTableWhenNoData) return null;

  const generatePaginationLinks = (): IMetaLink[] => {
    const links: IMetaLink[] = meta.links.slice(1, -1);

    if (links.length <= 5) {
      return links;
    }

    const firstPage = links[0] as IMetaLink;
    const lastPage = links[links.length - 1] as IMetaLink;
    const activeLink: IMetaLink | undefined = links.find(
      ({ active }: IMetaLink): boolean => active
    );
    const currentPage: number = activeLink ? parseInt(activeLink.label) : 1;
    const totalPages: number = parseInt(links[links.length - 1].label);
    const ellipsis: IMetaLink = { label: '...', url: null, active: false };

    // When on one of the first three pages:
    if (currentPage <= 3) {
      return [firstPage, links[1], links[2], ellipsis, lastPage];
    }

    // When on one of the last three pages:
    if (currentPage >= totalPages - 2) {
      return [
        firstPage,
        ellipsis,
        links[links.length - 3],
        links[links.length - 2],
        lastPage,
      ];
    }

    // When in the middle somewhere:
    const prevLink: IMetaLink | undefined = links.find(
      ({ label }: IMetaLink): boolean => parseInt(label) === currentPage - 1
    );
    const nextLink: IMetaLink | undefined = links.find(
      ({ label }: IMetaLink): boolean => parseInt(label) === currentPage + 1
    );

    return [
      firstPage,
      ellipsis,
      ...(!isMobile && prevLink ? [prevLink] : []),
      ...(activeLink ? [activeLink] : []),
      ...(!isMobile && nextLink ? [nextLink] : []),
      ellipsis,
      lastPage,
    ];
  };

  const handlePaginationClick = (
    e: React.MouseEvent<Element, MouseEvent>,
    active: boolean
  ): void => (active ? e.preventDefault() : undefined);

  return (
    <>
      <div className={`${styles.dataTable} rounded-md border`}>
        <Table>
          <TableHeader>
            {table.getHeaderGroups().map((headerGroup) => (
              <TableRow key={headerGroup.id}>
                {headerGroup.headers.map((header) => (
                  <TableHead key={header.id}>
                    {header.isPlaceholder
                      ? null
                      : flexRender(
                          header.column.columnDef.header,
                          header.getContext()
                        )}
                  </TableHead>
                ))}
              </TableRow>
            ))}
          </TableHeader>
          <TableBody>
            {data.length ? (
              table.getRowModel().rows?.map((row) => (
                <TableRow
                  key={row.id}
                  data-state={row.getIsSelected() && 'selected'}
                >
                  {row.getVisibleCells().map((cell) => (
                    <TableCell key={cell.id}>
                      {flexRender(
                        cell.column.columnDef.cell,
                        cell.getContext()
                      )}
                    </TableCell>
                  ))}
                </TableRow>
              ))
            ) : (
              <TableRow>
                <TableCell
                  colSpan={columns.length}
                  className={'h-24 text-center'}
                >
                  No data found.
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </div>
      {meta.total > 5 ? (
        <Pagination>
          <PaginationContent>
            <PaginationItem>
              <PaginationPrevious
                only={only}
                href={links.prev ?? '#'}
                isActive={links.prev !== null}
                onClick={(e) => handlePaginationClick(e, links.prev === null)}
                data={params}
              />
            </PaginationItem>
            {generatePaginationLinks().map(
              ({ url, label, active }: IMetaLink, idx: number) => (
                <PaginationItem key={`${idx}-${label}`}>
                  {url ? (
                    <PaginationLink
                      only={only}
                      href={url}
                      isActive={active}
                      onClick={(e) => handlePaginationClick(e, active)}
                      data={params}
                    >
                      {label}
                    </PaginationLink>
                  ) : (
                    <PaginationEllipsis />
                  )}
                </PaginationItem>
              )
            )}
            <PaginationItem>
              <PaginationNext
                only={only}
                href={links.next ?? '#'}
                isActive={links.next !== null}
                onClick={(e) => handlePaginationClick(e, links.next === null)}
                data={params}
              />
            </PaginationItem>
          </PaginationContent>
        </Pagination>
      ) : null}
    </>
  );
}
