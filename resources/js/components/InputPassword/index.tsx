import InputErrorText from '@/components/InputErrorText';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Eye, EyeOff } from 'lucide-react';
import { ChangeEvent, useState } from 'react';
import styles from './index.module.css';

interface IProps {
  value: string;
  onChange: (e: ChangeEvent<HTMLInputElement>) => void;
  errors?: string;
  placeholder?: string;
  required?: boolean;
}

export default function InputPassword({
  value,
  onChange,
  errors,
  placeholder = 'Password',
  required = true,
}: IProps) {
  const [show, setShow] = useState<boolean>(false);

  return (
    <>
      <div className={styles.inputPassword}>
        <Input
          type={show ? 'text' : 'password'}
          placeholder={placeholder}
          value={value}
          onChange={onChange}
          required={required}
        />
        <Button
          type={'button'}
          variant={'ghost'}
          size={'icon'}
          onClick={() => setShow(!show)}
        >
          {show ? <EyeOff /> : <Eye />}
        </Button>
      </div>
      <InputErrorText text={errors} />
    </>
  );
}
