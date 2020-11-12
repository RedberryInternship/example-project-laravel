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
  
  if( mergedConfigs.method === 'POST')
  {
    mergedConfigs.body._token = getCSRF();
    mergedConfigs.body = JSON.stringify(mergedConfigs.body);
  }

  return fetch(api, mergedConfigs);
}

/**
 * Parse string into html.
 * 
 * @param {string} htmlString
 * @return {HTMLElement}
 */
export const parseIntoHTML = (htmlString) => {
  const html = document
    .createRange()
    .createContextualFragment(htmlString)
    .children;
    
  return html.length > 1 ? html : html[0]
}