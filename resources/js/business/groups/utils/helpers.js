import { getCSRF } from './meta'

/**
 * Define custom fetch api
 * with already default config.
 * 
 * @param {RequestInfo} api
 * @param {RequestInit} config
 * @returns {Promise}
 */
export const curl = (api, config ) => {
  const mergedConfig = {
    ...config,
    ...{
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      }
    }
  };

  if(mergedConfig.method == 'POST' || mergedConfig.method == 'DELETE') {

    if(mergedConfig.body === undefined) {
      mergedConfig.body = {
        _token: getCSRF(),
      };
    }
    else {
      mergedConfig.body._token = getCSRF();
    }
  }

  console.log(mergedConfig);

  mergedConfig.body = JSON.stringify(mergedConfig.body);

  return fetch(api, mergedConfig);
}