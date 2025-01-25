import styles from './index.module.css';

interface IProps {
  status: number;
}

interface IError {
  [key: number]: string;
}

const titles: IError = {
  503: 'Service Unavailable',
  500: 'Server Error',
  404: 'Page Not Found',
  403: 'Forbidden',
};

const descriptions: IError = {
  503: 'Sorry, we are doing some maintenance. Please check back soon.',
  500: 'Whoops, something went wrong on our servers.',
  404: 'Sorry, the page you are looking for could not be found.',
  403: 'Sorry, you are forbidden from accessing this page.',
};

export default function Error({ status }: IProps) {
  const title: string = `${status}: ${titles[status] ?? 'Error'}`;
  const description: string = descriptions[status] ?? 'Something went wrong';

  return (
    <div className={styles.error}>
      <h1 className={styles.text}>{title}</h1>
      <p>{description}</p>
    </div>
  );
}
