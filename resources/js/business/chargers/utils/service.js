import { curl } from './helpers'
import { 
  toggleChargerVisibilityApi,
  getChargerWhitelistApi,
  addToWhitelistApi,
  removeFromWhitelistApi,
  getPhoneCodesApi,
 } from './api';
 import { getChargerId } from './meta'

/**
 * Service for toggling charger visibility.
 * 
 * @param whitelistParams 
 */
export const toggleChargerVisibility = ({ charger_id, hidden }) => {
  return curl(toggleChargerVisibilityApi, {
    method: 'POST',
    body: {
      charger_id,
      hidden,
    }
  });
}

/**
 * Get charger whitelist.
 * 
 * @returns {Promise<string[]>}
 */
export const getChargerWhitelist = () => {
  const chargerId = getChargerId();
  const api = getChargerWhitelistApi(chargerId);
  
  return curl(api);
}

/**
 * Add phone number to charger's whitelist.
 * 
 * @param {*} whitelistParams 
 */
export const addToWhitelist = ({charger_id, phone}) => {
  return curl(addToWhitelistApi, {
    method: 'POST',
    body: {
      charger_id,
      phone,
    }
  });
}

/**
 * Remove phone number from whitelist.
 * 
 * @param {BigInteger} whitelist_id 
 */
export const removeFromWhitelist = (whitelist_id) => {
  return curl(removeFromWhitelistApi, {
    method: 'POST',
    body: {
      whitelist_id,
    }
  });
}

export const getPhoneCodes = () => {
  return curl(getPhoneCodesApi);
}
