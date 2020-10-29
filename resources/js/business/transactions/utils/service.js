import { curl } from './helpers'
import * as API from './api'

/**
 * Get transaction info.
 * 
 * @param {bigint} id
 * @returns {Promise}
 */
export const getTransactionInfo = (id) => {
  return curl(API.getTransactionInfo(id));
}