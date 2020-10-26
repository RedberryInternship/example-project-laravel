import * as Service from '../utils/service'
import { isHidden, getChargerId } from '../utils/meta'
import { 
  whitelistModal, 
  whitelistModalBG,
  whitelistModalCloseButton,
 } from './elements'

/**
 * Toggle charger visibility.
 * 
 * @returns {void}
 */
export const toggleChargerVisibility = async () => {
  try {
    const params = {
      charger_id: getChargerId(), 
      hidden: isHidden(),
    };

    await Service.toggleChargerVisibility(params);
    window.location.reload();
  }
  catch(e)
  {
    console.log(e);
  }
}

/**
 * Open whitelist modal.
 * 
 * @returns {void}
 */
export const openWhitelistModal = () => {
  whitelistModalBG().style.display = 'block';
}

/**
 * Close whitelist modal.
 * 
 * @param {Event} e
 * @returns {void}
 */
export const closeWhitelistModal = (e) => {
  if( e.target == whitelistModalBG() || e.target === whitelistModalCloseButton())
  {
    whitelistModalBG().style.display = 'none';
  }
}