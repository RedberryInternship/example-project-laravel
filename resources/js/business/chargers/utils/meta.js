/**
 * Load csrf token to window variable.
 * 
 * @returns {string}
 */
export const getCSRF = () => {
  return document.querySelector('meta[name="_token"]').content;
}

/**
 * Get charger id.
 * 
 * @returns {string}
 */
export const getChargerId = () => {
  return document.querySelector('meta[name="charger_id"]').content;
}

/**
 * Determine if charger is hidden.
 * 
 * @returns {boolean}
 */
export const isHidden = () => {
  return document.querySelector('meta[name="hidden"]').content === '0';
}