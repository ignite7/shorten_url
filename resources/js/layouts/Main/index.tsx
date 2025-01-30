import Footer from '@/components/Footer';
import Header from '@/components/Header';
import { Toaster } from '@/components/ui/sonner';
import { Head, usePage } from '@inertiajs/react';
import { ReactNode, useEffect } from 'react';
import { toast } from 'sonner';
import styles from './index.module.css';

interface IProps {
  children: ReactNode;
}

export default function Layout({ children }: IProps) {
  const { props, component } = usePage();

  useEffect((): void => {
    const { message, type } = props.flash || {};

    if (!message) return;

    toast[type](message);
  }, [props.flash]);

  return (
    <>
      <Head title={component} />
      <Header />
      <main className={`${styles.main} ${component.toLowerCase()}`}>
        {children}
      </main>
      <Footer />
      <Toaster position={'top-center'} />
    </>
  );
}
