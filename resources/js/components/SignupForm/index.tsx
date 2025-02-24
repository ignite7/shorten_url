import InputErrorText from '@/components/InputErrorText';
import InputPassword from '@/components/InputPassword';
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
import FormDataType from '@/types/FormDataType';
import { FormEvent, ReactNode, useEffect, useState } from 'react';
import styles from './index.module.css';

interface IProps {
  children: ReactNode;
}

interface IForm extends FormDataType {
  first_name: string;
  last_name: string;
  email: string;
  password: string;
  password_confirmation: string;
  verification_code: string;
}

const codeSentCountDownSec: number = 60;

export default function SignupForm({ children }: IProps) {
  const { form, onSuccess, open, setOpen } = useFormDialog<IForm>({
    initialValues: {
      first_name: '',
      last_name: '',
      email: '',
      password: '',
      password_confirmation: '',
      verification_code: '',
    },
    setCurrentValuesAsNewDefaults: false,
  });
  const {
    data,
    setData,
    post,
    processing,
    errors,
    isDirty,
    setError,
    hasErrors,
  } = form;
  const isEmailDirty: boolean = data.email.trim() !== '';
  const isVerificationCodeDirty: boolean = data.verification_code.trim() !== '';
  const [codeSent, setCodeSent] = useState<boolean>(false);
  const [codeSentCountDown, setCodeSentCountDown] =
    useState<number>(codeSentCountDownSec);

  useEffect(() => {
    if (!codeSent) return;

    const interval: NodeJS.Timeout = setInterval((): void => {
      setCodeSentCountDown((prev: number): number => {
        if (prev <= 1) {
          clearInterval(interval);
          setCodeSent(false);

          return codeSentCountDownSec;
        }

        return prev - 1;
      });
    }, 1000);

    return (): void => clearInterval(interval);
  }, [codeSent]);

  const submit = (e: FormEvent<HTMLFormElement>): void => {
    e.preventDefault();
    if (!isDirty) return;
    post(route('signup'), { onSuccess });
  };

  const handleSendVerificationCode = (): void => {
    if (processing || codeSent || isVerificationCodeDirty) return;

    if (!isEmailDirty) {
      setError(
        'email',
        'The Email field is required to send the verification code.',
      );
      return;
    }

    setCodeSent(true);
    post(route('send-verification-code'), {
      onSuccess: (): void => {
        if (hasErrors) setError(errors as Record<keyof IForm, string>);
      },
      onError: (newErrors): void => {
        setError({
          ...errors,
          ...newErrors,
        } as Record<keyof IForm, string>);
      },
    });
  };

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Sign Up</DialogTitle>
          <DialogDescription>
            Create an account to have unlimited access to all features.
          </DialogDescription>
        </DialogHeader>
        <form
          id={'form-signup'}
          onSubmit={submit}
          className={styles.signupForm}
        >
          <Input
            type={'text'}
            placeholder={'First Name'}
            value={data.first_name}
            onChange={(e) => setData('first_name', e.target.value)}
            required
          />
          <InputErrorText text={errors.first_name} />
          <Input
            type={'text'}
            placeholder={'Last Name'}
            value={data.last_name}
            onChange={(e) => setData('last_name', e.target.value)}
            required
          />
          <InputErrorText text={errors.last_name} />
          <Input
            type={'email'}
            placeholder={'Email'}
            value={data.email}
            onChange={(e) => setData('email', e.target.value)}
            required
          />
          <InputErrorText text={errors.email} />
          <InputPassword
            value={data.password}
            onChange={(e) => setData('password', e.target.value)}
            errors={errors.password}
          />
          <InputPassword
            placeholder={'Confirm Password'}
            value={data.password_confirmation}
            onChange={(e) => setData('password_confirmation', e.target.value)}
            errors={errors.password_confirmation}
          />
          <div className={styles.verificationCode}>
            <Input
              type={'text'}
              placeholder={'# Verification Code'}
              value={data.verification_code}
              onChange={(e) => setData('verification_code', e.target.value)}
              maxLength={6}
              required
            />
            <Button
              type={'button'}
              variant={'secondary'}
              onClick={handleSendVerificationCode}
              disabled={
                codeSent ||
                processing ||
                !isEmailDirty ||
                isVerificationCodeDirty
              }
            >
              {codeSent ? `Resend after ${codeSentCountDown}s` : 'Send Code'}
            </Button>
          </div>
          <InputErrorText text={errors.verification_code} />
        </form>
        <DialogFooter>
          <Button
            form={'form-signup'}
            type={'submit'}
            disabled={processing || !isDirty}
          >
            Sign Up
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
