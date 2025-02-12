import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip';
import AutoPaste from '@/pages/Home/components/AutoPaste';
import ShortenUrlForm from '@/pages/Home/components/ShortenUrlForm';
import UrlHistoryTable from '@/pages/Home/components/UrlHistoryTable';
import { CircleHelp } from 'lucide-react';
import './index.css';
import styles from './index.module.css';

export default function Home() {
  return (
    <>
      <h1 className={styles.title}>Shorten Your Loooong Links :)</h1>
      <p className={styles.description}>
        ShortenURL is an efficient and easy-to-use URL shortening service that
        streamlines your online experience.
      </p>
      <ShortenUrlForm />
      <AutoPaste />
      <TooltipProvider>
        <p className={styles.register}>
          Register now to enjoy unlimited usage
          <Tooltip>
            <TooltipTrigger asChild>
              <CircleHelp size={16} />
            </TooltipTrigger>
            <TooltipContent>
              <p>Usage is limited per IP address. Register for full access.</p>
            </TooltipContent>
          </Tooltip>
        </p>
      </TooltipProvider>
      <UrlHistoryTable />
    </>
  );
}
