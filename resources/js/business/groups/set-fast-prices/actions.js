import copy from './utils/copy'

/**
 * Confirm setting fast prices for 
 * all the group chargers connectors.
 * 
 * @param {Event} e
 * @returns {void}
 */
export const confirmSettingChargingPrices = function(e) {
  const groupName = this.dataset.groupName;
  const shouldSetPrices = confirm(copy.confirmSettingFastPrice(groupName));

  if(! shouldSetPrices) {
    e.preventDefault();
  }
}