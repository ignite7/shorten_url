import React from 'react';

interface PageModule {
  default: React.ComponentType & {
    layout?: (page: React.ReactNode) => React.ReactNode;
  };
}

export default PageModule;
