import SignupForm from '@/components/SignupForm';
import { Button } from '@/components/ui/button';
import { useMediaQueryContext } from '@/context/MediaQueryContext';
import ManageAnonymousTokenAccess from '@/pages/Home/components/ManageAnonymousTokenAccess';
import { router, usePage } from '@inertiajs/react';
import { LogIn, UserPlus } from 'lucide-react';
import styles from './index.module.css';

export default function Header() {
  const { user } = usePage().props.auth || {};
  const { isMobile } = useMediaQueryContext();
  const buttonSize = isMobile ? 'icon' : 'default';

  return (
    <header className={styles.header}>
      <div className={styles.logo}>
        <h5 onClick={() => router.visit(route('home'))}>ShortenURL</h5>
      </div>
      <div className={styles.buttons}>
        {!user ? <ManageAnonymousTokenAccess /> : null}
        <Button variant={'secondary'} size={buttonSize}>
          {isMobile ? (
            <LogIn />
          ) : (
            <>
              Login <LogIn />
            </>
          )}
        </Button>
        <SignupForm>
          <Button size={buttonSize}>
            {isMobile ? (
              <UserPlus />
            ) : (
              <>
                Register <UserPlus />
              </>
            )}
          </Button>
        </SignupForm>
      </div>
    </header>
  );
}
