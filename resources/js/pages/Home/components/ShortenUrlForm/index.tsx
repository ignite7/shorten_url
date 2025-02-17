import InputErrorText from '@/components/InputErrorText';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useMediaQueryContext } from '@/context/MediaQueryContext';
import LocalStorageKeys from '@/enums/LocalStorageKeys';
import ClipboardHelper from '@/helpers/clipboardHelper';
import IHomePageProps from '@/interfaces/IHomePageProps';
import IUrl from '@/interfaces/IUrl';
import FormDataType from '@/types/FormDataType';
import { Page } from '@inertiajs/core';
import { useForm } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { FormEvent } from 'react';
import styles from './index.module.css';

interface IForm extends FormDataType {
  source: IUrl['source'];
}

export default function ShortenUrlForm() {
  const { isMobile } = useMediaQueryContext();
  const { data, setData, post, processing, errors, isDirty, reset } =
    useForm<IForm>({
      source: '',
    });

  const handleOnSuccess = (params: Page<IHomePageProps>): void => {
    const { lastShortenedUrl } = params.props;
    reset();

    if (
      localStorage.getItem(LocalStorageKeys.AutoPaste) === 'true' &&
      lastShortenedUrl
    ) {
      ClipboardHelper.copy(lastShortenedUrl, false);
    }
  };

  const submit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault();
    if (!isDirty) return;
    post(route('urls.store'), {
      onSuccess: (params): void =>
        handleOnSuccess(params as Page<IHomePageProps>),
    });
  };

  return (
    <>
      <form className={styles.form} onSubmit={submit}>
        <Input
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
