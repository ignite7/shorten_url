import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useMediaQueryContext } from '@/context/MediaQueryContext';
import LocalStorageKeys from '@/enums/LocalStorageKeys';
import IHome from '@/interfaces/pages/IHome';
import { useForm, usePage } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { ChangeEvent, FormEvent, useEffect } from 'react';
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
      navigator.clipboard.writeText(lastShortenedUrl);
    }
  }, [reset, wasSuccessful, lastShortenedUrl]);

  const submit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault();
    if (!isDirty) return;
    post(route('urls.store'));
  };

  const change = (e: ChangeEvent<HTMLInputElement>): void =>
    setData('source', e.target.value);

  return (
    <>
      <form className={styles.form} onSubmit={submit}>
        <Input
          name={'source'}
          type={'url'}
          placeholder={'Enter the link here'}
          className={styles.input}
          value={data.source}
          onChange={change}
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
      {errors.source ? <p className={styles.error}>{errors.source}</p> : null}
    </>
  );
}
