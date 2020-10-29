import { curl } from './helpers'
import { getGroupId } from './meta'
import * as API from './api'

/**
 * Remove all the tariffs from
 * group chargers.
 * 
 * @returns {Promise}
 */
export const deleteChargingPrices = () => {
  return curl(API.deleteChargingPrices, {
    method: 'DELETE',
    body: {
      group_id: getGroupId(),
    }
  });
}

