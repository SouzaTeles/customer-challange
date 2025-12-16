export function getErrorMessage(error, defaultMessage = 'Ocorreu um erro') {
  return error?.response?.data?.error || error?.message || defaultMessage
}
