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
import { useMediaQueryContext } from '@/context/MediaQueryContext';
import ClipboardHelper from '@/helpers/clipboardHelper';
import IHomePageProps from '@/interfaces/IHomePageProps';
import { useForm, usePage } from '@inertiajs/react';
import { Copy, KeyRound } from 'lucide-react';
import { FormEvent, useEffect, useState } from 'react';
import { toast } from 'sonner';
import { v4 as uuidv4 } from 'uuid';
import styles from './index.module.css';

export default function ManageAnonymousTokenAccess() {
  const { anonymousToken } = usePage<IHomePageProps>().props;
  const { isMobile } = useMediaQueryContext();
  const [open, setOpen] = useState<boolean>(false);
  const {
    data,
    setData,
    put,
    processing,
    errors,
    isDirty,
    setDefaults,
    clearErrors,
    reset,
  } = useForm({
    anonymous_token: anonymousToken ?? '',
  });

  useEffect((): void => {
    if (open) return;

    reset();
    clearErrors();
  }, [open, reset]);

  const onSuccess = (): void => {
    toast.success('Token updated successfully.');
    setDefaults();
    setOpen(false);
  };

  const submit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault();
    if (!isDirty) return;
    put(route('urls.anonymous-token.update'), { onSuccess });
  };

  const generateToken = (): void => {
    setData('anonymous_token', uuidv4());
    clearErrors();
  };

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>
        <Button variant={'outline'} size={isMobile ? 'icon' : 'default'}>
          {isMobile ? (
            <KeyRound />
          ) : (
            <>
              Manage Access <KeyRound />
            </>
          )}
        </Button>
      </DialogTrigger>
      <DialogContent className={styles.manageAnonymousTokenAccess}>
        <DialogHeader>
          <DialogTitle>Manage Access</DialogTitle>
          <DialogDescription>
            Track your shortened URL history across devices with this token.
            Update or generate a new one, but keep it safe—losing it means
            losing your history.
          </DialogDescription>
        </DialogHeader>
        <form id={'form-token'} className={styles.form} onSubmit={submit}>
          <Input
            name={'anonymous_token'}
            type={'text'}
            placeholder={'Enter the token here'}
            value={data.anonymous_token}
            onChange={(e) => setData('anonymous_token', e.target.value)}
            required
          />
          <Button
            type={'button'}
            variant={'ghost'}
            size={'icon'}
            onClick={() => ClipboardHelper.copy(data.anonymous_token)}
          >
            <Copy />
          </Button>
        </form>
        <InputErrorText text={errors.anonymous_token} />
        <DialogFooter className={styles.footer}>
          <Button type={'button'} variant={'secondary'} onClick={generateToken}>
            Generate Token
          </Button>
          <Button
            form={'form-token'}
            type={'submit'}
            disabled={processing || !isDirty}
          >
            Save
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
