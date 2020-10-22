import { curlDefaultConfig } from './const'
import { getCSRF } from './meta'

/**
 * Customized fetch api.
 * 
 * @param {string} api 
 * @param {RequestInit} config 
 */
export const curl = (api, config) => {
  const mergedConfigs = {...config, ...curlDefaultConfig };
  mergedConfigs.body._token = getCSRF();
  mergedConfigs.body = JSON.stringify(mergedConfigs.body);

  return fetch(api, mergedConfigs);
}

