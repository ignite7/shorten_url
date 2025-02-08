import { toast } from 'sonner';

export default class ClipboardHelper {
  static copy(text: string, addToast: boolean = true): void {
    navigator.clipboard.writeText(text);

    if (addToast) {
      toast.success('Copied to clipboard');
    }
  }
}
