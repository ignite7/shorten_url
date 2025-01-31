import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/react';
import { Link } from 'lucide-react';
import { ChangeEvent, FormEvent, useEffect } from 'react';
import styles from './index.module.css';

export default function ShortenUrlForm() {
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
    if (wasSuccessful) reset();
  }, [reset, wasSuccessful]);

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
        <Link />
        <Input
          name={'source'}
          type={'url'}
          placeholder={'Enter the link here'}
          className={styles.input}
          value={data.source}
          onChange={change}
          required
        />
        <Button type={'submit'} disabled={processing}>
          Shorten Now!
        </Button>
      </form>
      {errors.source ? <p className={styles.error}>{errors.source}</p> : null}
    </>
  );
}
