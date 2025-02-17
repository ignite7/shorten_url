import InputErrorText from '@/components/InputErrorText';
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import useFormDialog from '@/hooks/useFormDialog';
import IUrl from '@/interfaces/IUrl';
import FormDataType from '@/types/FormDataType';
import { FormEvent, ReactNode } from 'react';

interface IProps {
  id: IUrl['id'];
  source: IUrl['source'];
  children: ReactNode;
}

interface IForm extends FormDataType {
  source: IUrl['source'];
}

export default function UpdateUrlSourceForm({ id, source, children }: IProps) {
  const { form, onSuccess, open, setOpen } = useFormDialog<IForm>({ source });
  const { data, setData, put, processing, errors, isDirty } = form;

  const submit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault();
    if (!isDirty) return;
    put(route('urls.source.update', { id }), { onSuccess });
  };

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Edit Destination URL</DialogTitle>
          <DialogDescription>
            Modify the destination URL for this shortened link. Users accessing
            the short link will be redirected to the updated URL.
          </DialogDescription>
        </DialogHeader>
        <form id={'form-update-url-source'} onSubmit={submit}>
          <Input
            type={'url'}
            placeholder={'Enter the new destination URL'}
            value={data.source}
            onChange={(e) => setData('source', e.target.value)}
            required
          />
        </form>
        <InputErrorText text={errors.source} />
        <DialogFooter>
          <Button
            form={'form-update-url-source'}
            type={'submit'}
            disabled={processing || !isDirty}
          >
            Save Changes
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
