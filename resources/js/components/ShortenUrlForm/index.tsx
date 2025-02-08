import InputErrorText from '@/components/InputErrorText';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useMediaQueryContext } from '@/context/MediaQueryContext';
import LocalStorageKeys from '@/enums/LocalStorageKeys';
import ClipboardHelper from '@/helpers/clipboardHelper';
import IHome from '@/interfaces/pages/IHome';
import { useForm, usePage } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { FormEvent, useEffect } from 'react';
import styles from './index.module.css';

export default function ShortenUrlForm() {
  const { isMobile } = useMediaQueryContext();
  const { lastShortenedUrl } = usePage<IHome>().props;
  const {
    data,
    setData,
    post,
    processing,
    errors,
    isDirty,
    wasSuccessful,
    reset,
  } = useForm({
    source: '',
  });

  useEffect((): void => {
    if (!wasSuccessful) return;

    reset();

    if (
      lastShortenedUrl &&
      localStorage.getItem(LocalStorageKeys.AutoPaste) === 'true'
    ) {
      ClipboardHelper.copy(lastShortenedUrl, false);
    }
  }, [reset, wasSuccessful, lastShortenedUrl]);

  const submit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault();
    if (!isDirty) return;
    post(route('urls.store'));
  };

  return (
    <>
      <form className={styles.form} onSubmit={submit}>
        <Input
          name={'source'}
          type={'url'}
          placeholder={'Enter the link here'}
          className={styles.input}
          value={data.source}
          onChange={(e) => setData('source', e.target.value)}
          required
        />
        <Button
          type={'submit'}
          disabled={processing}
          size={isMobile ? 'icon' : 'default'}
        >
          {isMobile ? (
            <ArrowRight />
          ) : (
            <>
              Shorten <ArrowRight />
            </>
          )}
        </Button>
      </form>
      <InputErrorText text={errors.source} />
    </>
  );
}
