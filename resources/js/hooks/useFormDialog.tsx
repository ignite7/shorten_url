import FormDataType from '@/types/FormDataType';
import { useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export default function useFormDialog<TForm extends FormDataType>(
  initialValues?: TForm
) {
  const [open, setOpen] = useState<boolean>(false);
  const form = useForm<TForm>(initialValues);
  const { setDefaults, reset, clearErrors } = form;

  useEffect((): void => {
    if (open) return;

    reset();
    clearErrors();
  }, [open]);

  const onSuccess = (): void => {
    setDefaults();
    setOpen(false);
  };

  return { form, onSuccess, open, setOpen };
}
