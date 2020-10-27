/**
 * Should type.
 * 
 * @param {Event} e
 * @param {Array} phoneNumber
 * @returns {boolean} 
 */
export const shouldType = ( e, phoneNumber ) => {
  return phoneNumber.length <= 12
    && e.keyCode != 8;
}

/**
 * Format phone number.
 * 
 * @returns {void}
 */
export const formatPhoneNumber = ( phoneNumber ) => {
  if(phoneNumber[3] !== undefined && phoneNumber[3] != ' ') {
    phoneNumber.splice(3, 0, ' ');
  }
  
  if(phoneNumber[6] !== undefined && phoneNumber[6] != ' ') {
    phoneNumber.splice(6, 0, ' ');
  }
  if(phoneNumber[9] !== undefined && phoneNumber[9] != ' ') {
    phoneNumber.splice(9, 0, ' ');
  }

  if( phoneNumber.length > 12) {
    phoneNumber = phoneNumber.substr(0, 12);
  }
}

/**
 * Remove spaces from phone number.
 * 
 * @param {Array} phoneNumber
 */
export const removeSpacesFromPhoneNumber = (phoneNumber) => {
  return phoneNumber.join('').replaceAll(' ', '');
}

/**
 * Is digit.
 * 
 * @param {Event} e
 * @returns {boolean}
 */
export const isDigit = (e) => {
  return e.keyCode >= 48 && e.keyCode <= 57
}

/**
 * Determine if Backspace is pressed.
 * 
 * @param {Event} e
 * @returns {Boolean} 
 */
export const isBackspace = (e) => {
  return e.keyCode === 8;
}

/**
 * Determine if input length is 12 chars.
 * 
 * @param {HTMLInputElement} elem
 * @returns {boolean}
 */
export const isFullyTyped = (elem) => {
  return elem.value.length === 12;
}

/**
 * Determine if input length is at least 9 chars.
 * 
 * @param {HTMLInputElement} elem
 * @returns {boolean}
 */
export const isAcceptable = (elem) => {
  return elem.value.length >= 9;
}