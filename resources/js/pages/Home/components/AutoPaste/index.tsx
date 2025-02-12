import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import LocalStorageKeys from '@/enums/LocalStorageKeys';
import { useEffect, useState } from 'react';
import styles from './index.module.css';

export default function AutoPaste() {
  const autoPasteItem: string | null = localStorage.getItem(
    LocalStorageKeys.AutoPaste
  );
  const [autoPaste, setAutoPaste] = useState<boolean>(
    autoPasteItem === null ? true : autoPasteItem === 'true'
  );

  useEffect((): void => {
    localStorage.setItem(LocalStorageKeys.AutoPaste, autoPaste.toString());
  }, [autoPaste]);

  return (
    <div className={styles.autoPate}>
      <Switch
        id={'switch-auto-paste'}
        checked={autoPaste}
        onCheckedChange={(checked: boolean) => setAutoPaste(checked)}
      />
      <Label htmlFor={'switch-auto-paste'}>Auto paste to clipboard</Label>
    </div>
  );
}
