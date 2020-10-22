import * as Service from '../utils/service'
import { isHidden, getChargerId } from '../utils/meta'

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