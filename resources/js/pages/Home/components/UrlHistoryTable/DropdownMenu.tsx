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
import UpdateUrlSourceForm from '@/pages/Home/components/UrlHistoryTable/UpdateUrlSourceForm';
import { router } from '@inertiajs/react';
import { Row } from '@tanstack/react-table';
import { MoreHorizontal } from 'lucide-react';

interface IProps {
  row: Row<IUrl>;
}

export default function DropdownMenu({ row }: IProps) {
  const id: string = row.getValue('id');
  const shortLink: string = route('redirect-to-source', { id });
  const source: string = row.getValue('source');
  const status: string = row.getValue('status');

  const handleOnAction = (): void =>
    router.put(route('urls.toggle-status', { id }));

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
          Copy Shortened URL
        </DropdownMenuItem>
        <DropdownMenuItem onClick={() => ClipboardHelper.copy(source)}>
          Copy Full URL
        </DropdownMenuItem>
        <DropdownMenuSeparator />
        {status === UrlStatus.ACTIVE ? (
          <AlertDialog
            headerTitle={'Turn Off this URL?'}
            headerDescription={
              'Turning off this URL will make it inaccessible.'
            }
            onAction={handleOnAction}
            actionBtnText={'Turn Off'}
          >
            <DropdownMenuItem>Turn Off URL</DropdownMenuItem>
          </AlertDialog>
        ) : (
          <AlertDialog
            headerTitle={'Turn On this URL?'}
            headerDescription={'Turning on this URL will make it accessible.'}
            onAction={handleOnAction}
            actionBtnText={'Turn On'}
          >
            <DropdownMenuItem>Turn On URL</DropdownMenuItem>
          </AlertDialog>
        )}
        <UpdateUrlSourceForm id={id} source={source}>
          <DropdownMenuItem>Edit Destination URL</DropdownMenuItem>
        </UpdateUrlSourceForm>
      </DropdownMenuContent>
    </DropdownMenuUI>
  );
}
