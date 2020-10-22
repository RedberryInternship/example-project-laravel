import { curl } from './helpers'
import { 
  toggleChargerVisibilityApi,
  getChargerWhitelistApi,
  addToWhitelistApi,
  removeFromWhitelistApi,
 } from './api';

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
  return curl(getChargerWhitelistApi);
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
 * @param {*} whitelistParams 
 */
export const removeFromWhitelist = ({charger_id, whitelist_id}) => {
  return curl(removeFromWhitelistApi, {
    method: 'POST',
    body: {
      charger_id,
      whitelist_id,
    }
  });
}