import { AlertDialog } from '@/components/AlertDialog';
import { Button } from '@/components/ui/button';
import {
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
  DropdownMenu as DropdownMenuUI,
} from '@/components/ui/dropdown-menu';
import UrlStatus from '@/enums/UrlStatus';
import ClipboardHelper from '@/helpers/clipboardHelper';
import IUrl from '@/interfaces/IUrl';
import { router } from '@inertiajs/react';
import { Row } from '@tanstack/react-table';
import { MoreHorizontal } from 'lucide-react';

interface IProps {
  row: Row<IUrl>;
}

export default function DropdownMenu({ row }: IProps) {
  const id: string = row.getValue('id');
  const params: { [key: string]: string } = { url: id };
  const shortLink: string = route('redirect-to-source', params);
  const source: string = row.getValue('source');
  const status: string = row.getValue('status');

  const handleOnAction = (): void =>
    router.put(route('urls.toggle-status', params));

  return (
    <DropdownMenuUI>
      <DropdownMenuTrigger asChild>
        <Button variant={'ghost'} size={'icon'}>
          <MoreHorizontal />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align={'end'}>
        <DropdownMenuLabel>Actions</DropdownMenuLabel>
        <DropdownMenuItem onClick={() => ClipboardHelper.copy(shortLink)}>
          Copy Short Link
        </DropdownMenuItem>
        <DropdownMenuItem onClick={() => ClipboardHelper.copy(source)}>
          Copy Original Link
        </DropdownMenuItem>
        <DropdownMenuSeparator />
        {status === UrlStatus.ACTIVE ? (
          <AlertDialog
            headerTitle={'Do you want to inactivate this URL?'}
            headerDescription={
              'When inactivated, the URL will no longer be accessible.'
            }
            onAction={handleOnAction}
          >
            <DropdownMenuItem>Inactivate URL</DropdownMenuItem>
          </AlertDialog>
        ) : (
          <AlertDialog
            headerTitle={'Do you want to activate this URL?'}
            headerDescription={'When activated, the URL will be accessible.'}
            onAction={handleOnAction}
          >
            <DropdownMenuItem>Activate URL</DropdownMenuItem>
          </AlertDialog>
        )}
        <DropdownMenuItem>Update Original Link</DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenuUI>
  );
}
