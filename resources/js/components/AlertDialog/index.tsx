import {
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
  AlertDialog as AlertDialogUI,
} from '@/components/ui/alert-dialog';
import { MouseEventHandler, ReactNode } from 'react';

interface IProps {
  children: ReactNode;
  headerTitle?: ReactNode | string;
  headerDescription?: ReactNode | string;
  onAction?: MouseEventHandler<HTMLButtonElement>;
  actionBtnText?: string;
}

export function AlertDialog({
  children,
  headerTitle,
  headerDescription,
  onAction,
  actionBtnText = 'Continue',
}: IProps) {
  return (
    <AlertDialogUI>
      <AlertDialogTrigger asChild>{children}</AlertDialogTrigger>
      <AlertDialogContent>
        <AlertDialogHeader>
          {typeof headerTitle === 'string' ? (
            <AlertDialogTitle>{headerTitle}</AlertDialogTitle>
          ) : (
            headerTitle
          )}
          {typeof headerDescription === 'string' ? (
            <AlertDialogDescription>{headerDescription}</AlertDialogDescription>
          ) : (
            headerDescription
          )}
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel>Cancel</AlertDialogCancel>
          <AlertDialogAction onClick={onAction}>
            {actionBtnText}
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialogUI>
  );
}
