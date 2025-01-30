import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/react';
import { LogIn } from 'lucide-react';
import styles from './index.module.css';

export default function Header() {
  const onClick = (): void =>
    router.push({
      url: route('home'),
      component: 'Home',
    });

  return (
    <header className={styles.header}>
      <div className={styles.logo} onClick={onClick}>
        ShortenURL
      </div>
      <div className={styles.buttons}>
        <Button variant={'secondary'}>
          Login <LogIn />
        </Button>
        <Button>Register</Button>
      </div>
    </header>
  );
}
