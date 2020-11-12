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

/**
 * Store all the company chargers
 * into this group.
 * 
 * @returns {Promise}
 */
export const storeAllChargersIntoGroup = () => {
  return curl(API.storeAllChargersIntoGroup, {
    method: 'POST',
    body: {
      group_id: getGroupId(),
    }
  });
}

