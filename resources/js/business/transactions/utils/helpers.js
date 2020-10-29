import { getCSRF } from './meta'

/**
 * Fetch api wrapper with set config.
 * 
 * @param {RequestInfo} api
 * @param {RequestInit} config
 * @returns {Promise<Response>}
 */
export const curl = (api, config = {}) => {
  const mergedConfig = {
    ...config,
    ...{
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      }
    }
  };

  if(mergedConfig.method === 'POST' || mergedConfig.method === 'DELETE') {
    if(mergedConfig.body === undefined) {
      mergedConfig.body = {
        _token: getCSRF(),
      }
    }
    else {
      mergedConfig.body._token = getCSRF();
    }
  }

  return fetch(api, mergedConfig);
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