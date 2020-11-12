/**
 * Get laravel csrf token.
 * 
 * @returns {string}
 */
export const getCSRF = () => {
  return document.querySelector('meta[name="_token"]').content;
}
