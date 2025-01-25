import Footer from '@/components/Footer';
import Header from '@/components/Header';
import { Head, usePage } from '@inertiajs/react';
import React from 'react';

interface IProps {
  children: React.ReactNode;
}

export default function Layout({ children }: IProps) {
  const { props, component } = usePage();
  const { message, type } = props.flash || {};

  return (
    <>
      <Head title={component} />
      <Header />
      <main className={'main'}>
        {message ? (
          <div className={`flash-message ${type}`}>{message}</div>
        ) : null}
        {children}
      </main>
      <Footer />
    </>
  );
}
