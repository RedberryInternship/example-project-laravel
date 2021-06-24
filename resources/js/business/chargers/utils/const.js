
/**
 * Default configuration of the fetch api
 */
export const curlDefaultConfig = {
  headers: {
    'Accept': 'application/json',
    'Content-type': 'application/json',
  }
}

/**
 * Add phone number to whitelist mistake messages.
 * 
 * @var {object} mistakeMsg
 */
export const mistakeMsg = {
  georgianMistakeText: __('chargers.whitelist.format-error'),
  generalMistakeText: __('chargers.whitelist.georgian-phone-error'),
}