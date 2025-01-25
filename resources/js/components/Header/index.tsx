import styles from './index.module.css';

export default function Header() {
  return (
    <header className={styles.header}>
      <div className={styles.logo}>ShortenURL</div>
      <div className={styles.buttons}>
        <button>Login</button>
        <button>Register</button>
      </div>
    </header>
  );
}
