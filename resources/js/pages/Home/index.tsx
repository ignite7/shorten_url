import styles from './index.module.css';
import ShortenUrlForm from '@/components/ShortenUrlForm';

export default function Home() {
  return (
    <>
      <h1 className={styles.title}>Shorten Your Loooong Links :)</h1>
      <p className={styles.description}>
        ShortenURL is an efficient and easy-to-use URL shortening service that
        streamlines your online experience.
      </p>
      <ShortenUrlForm />
    </>
  );
}
