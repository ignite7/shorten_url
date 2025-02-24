import FormDataType from '@/types/FormDataType';
import { useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';

interface IProps<TForm extends FormDataType> {
  initialValues: TForm;
  setCurrentValuesAsNewDefaults?: boolean;
}

export default function useFormDialog<TForm extends FormDataType>({
  initialValues,
  setCurrentValuesAsNewDefaults = true,
}: IProps<TForm>) {
  const [open, setOpen] = useState<boolean>(false);
  const form = useForm<TForm>(initialValues);
  const { setDefaults, reset, clearErrors } = form;

  useEffect((): void => {
    if (open) return;

    reset();
    clearErrors();
  }, [open]);

  const onSuccess = (): void => {
    if (setCurrentValuesAsNewDefaults) setDefaults();
    setOpen(false);
  };

  return { form, onSuccess, open, setOpen };
}
