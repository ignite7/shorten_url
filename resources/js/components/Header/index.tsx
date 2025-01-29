import { Button } from '@/components/ui/button';
import { useMediaQueryContext } from '@/context/MediaQueryContext';
import { LogIn } from 'lucide-react';
import styles from './index.module.css';

export default function Header() {
  const { isMobile } = useMediaQueryContext();
  const buttonSize = isMobile ? 'sm' : 'default';

  return (
    <header className={styles.header}>
      <div className={styles.logo}>ShortenURL</div>
      <div className={styles.buttons}>
        <Button variant={'secondary'} size={buttonSize}>
          Login <LogIn />
        </Button>
        <Button size={buttonSize}>Register</Button>
      </div>
    </header>
  );
}
