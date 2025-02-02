import ShortenUrlForm from '@/components/ShortenUrlForm';
import styles from './index.module.css';
import { PageProps } from '@/types';

export default function Home({ anonymousToken }: PageProps) {
  return (
    <>
      <h1 className={styles.title}>Shorten Your Loooong Links :)</h1>
      <p className={styles.description}>
        ShortenURL is an efficient and easy-to-use URL shortening service that
        streamlines your online experience: {anonymousToken}
      </p>
      <ShortenUrlForm />
    </>
  );
}
