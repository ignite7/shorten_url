import { Button } from '@/components/ui/button';
import { useMediaQueryContext } from '@/context/MediaQueryContext';
import { router } from '@inertiajs/react';
import { LogIn, UserPlus } from 'lucide-react';
import styles from './index.module.css';

export default function Header() {
  const { isMobile } = useMediaQueryContext();
  const buttonSize = isMobile ? 'icon' : 'default';

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
        <Button variant={'secondary'} size={buttonSize}>
          {isMobile ? (
            <LogIn />
          ) : (
            <>
              Login <LogIn />
            </>
          )}
        </Button>
        <Button size={buttonSize}>
          {isMobile ? (
            <UserPlus />
          ) : (
            <>
              Register <UserPlus />
            </>
          )}
        </Button>
      </div>
    </header>
  );
}
