import copy from './utils/copy'

/**
 * Confirm saving profile information.
 * 
 * @param {Event} e
 * @returns {void}
 */
export const confirmSavingProfileInformation = (e) => {
  const shouldUpdate = confirm(copy.confirmSavingProfile);

  if(! shouldUpdate ) {
    e.preventDefault();
  }
}