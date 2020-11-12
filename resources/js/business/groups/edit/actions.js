import { 
  deleteChargingPrices,
  storeAllChargersIntoGroup, 
} from './utils/service'
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

/**
 * Store all the company chargers into this 
 * group and reload the page.
 * 
 * @returns {void}
 */
export const storeAllChargersIntoGroupAndReload = async () => {
  if(! confirm(copy.confirmStoringAllChargersIntoGroup)) {
    return;
  }

  try {
    await storeAllChargersIntoGroup();
    document.location.reload();
  } catch(e) {
    console.log(e);
  }
}