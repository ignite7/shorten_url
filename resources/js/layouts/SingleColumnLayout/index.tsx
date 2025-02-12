import { Toaster } from '@/components/ui/sonner';
import Footer from '@/layouts/SingleColumnLayout/components/Footer';
import Header from '@/layouts/SingleColumnLayout/components/Header';
import { Head, usePage } from '@inertiajs/react';
import { ReactNode, useEffect } from 'react';
import { toast } from 'sonner';
import './index.css';
import styles from './index.module.css';

interface IProps {
  children: ReactNode;
}

export default function SingleColumnLayout({ children }: IProps) {
  const { props, component } = usePage();
  const [page] = component.split('/');

  useEffect((): void => {
    const { message, type } = props.flash || {};

    if (!message) return;

    toast[type](message);
  }, [props.flash]);

  return (
    <>
      <Head title={page} />
      <Header />
      <main className={`${styles.main} ${page.toLowerCase()}`}>{children}</main>
      <Footer />
      <Toaster position={'top-center'} />
    </>
  );
}
