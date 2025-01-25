interface IFlash {
  message: string | null;
  type: 'success' | 'info' | 'warning' | 'error';
}

export default IFlash;
