import { createContext, ReactNode, useContext } from 'react';
import { useMediaQuery } from 'react-responsive';

const BREAKPOINT_MD = 768;
const BREAKPOINT_LG = 1024;

interface IMediaQueryContext {
  isMobile: boolean;
  isTablet: boolean;
  isDesktop: boolean;
}

interface IMediaQueryProvider {
  children: ReactNode;
}

const MediaQueryContext = createContext<IMediaQueryContext>({
  isMobile: false,
  isTablet: false,
  isDesktop: false,
});

export const useMediaQueryContext = (): IMediaQueryContext => useContext(MediaQueryContext);

export const MediaQueryProvider = ({ children }: IMediaQueryProvider) => {
  const isMobile = useMediaQuery({ maxWidth: BREAKPOINT_MD - 1 }); // Mobile: 0px - 767px
  const isTablet = useMediaQuery({ maxWidth: BREAKPOINT_MD - 1 }); // Tablet: 768px - 1023px
  const isDesktop = useMediaQuery({ minWidth: BREAKPOINT_LG }); // Desktop: 1024px+

  return (
    <MediaQueryContext.Provider value={{ isMobile, isTablet, isDesktop }}>
      {children}
    </MediaQueryContext.Provider>
  );
};
