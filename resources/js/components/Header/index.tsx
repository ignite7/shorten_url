import { Button } from '@/components/ui/button';
import { LogIn } from 'lucide-react';
import styles from './index.module.css';

export default function Header() {
  return (
    <header className={styles.header}>
      <div className={styles.logo}>ShortenURL</div>
      <div className={styles.buttons}>
        <Button variant={'secondary'}>
          Login <LogIn />
        </Button>
        <Button>Register</Button>
      </div>
    </header>
  );
}
