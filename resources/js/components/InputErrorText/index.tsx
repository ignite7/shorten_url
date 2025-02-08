import styles from './index.module.css';

interface IProps {
  text?: string | null;
}

export default function InputErrorText({ text }: IProps) {
  if (!text) return null;

  return <p className={styles.inputErrorText}>{text}</p>;
}
