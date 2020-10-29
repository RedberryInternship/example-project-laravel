import { deleteChargingPrices } from './utils/service'
import copy from './utils/copy'

/**
 * Delete all group chargers tariffs
 * and reload the page.
 * 
 * @returns {void}
 */
export const deleteAllChargingPricesAndReload =  async() => {
  if(! confirm(copy.confirmRemovingGroupPrices)) {
    return;
  }
  
  try {
    await deleteChargingPrices();
    alert(copy.successfullyRemovedGroupPrices);
    document.location.reload();
  }
  catch(e) {
    console.log(e);
  }
}