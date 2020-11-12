import { state } from '../data/state'
import * as Service from '../utils/service'
import { isHidden, getChargerId } from '../utils/meta'
import { 
  whitelistModalCloseButton,
  whitelistModalSelect,
  whitelistModalBG,
  whitelistInput,
  documentBody,
 } from '../UI/elements'
 import {
   removeSpacesFromPhoneNumber,
   formatPhoneNumber,
   isFullyTyped,
   isAcceptable,
   isBackspace,
   shouldType,
   isDigit,
 } from '../utils/validation'
import {
  doesNumberAlreadyExist,
  generateInputTextArray,
  expressInputMistake,
  assemblePhoneNumber,
  hideInputMistake,
  clearPhoneInput,
  assignInput,
} from './helpers'
import { fetchChargerWhitelistData } from '../data/fetch-all-data'
import { renderWhitelistRecords } from '../UI/renderer'
import { mistakeMsg } from '../utils/const'
import copy from '../utils/copy'

const { generalMistakeText, georgianMistakeText } = mistakeMsg;

/**
 * Toggle charger visibility.
 * 
 * @returns {void}
 */
export const toggleChargerVisibility = async () => {
  try {
    const params = {
      charger_id: getChargerId(), 
      hidden: isHidden(),
    };

    await Service.toggleChargerVisibility(params);
    window.location.reload();
  }
  catch(e)
  {
    console.log(e);
  }
}

/**
 * Open whitelist modal.
 * 
 * @returns {void}
 */
export const openWhitelistModal = () => {
  whitelistModalBG().style.display = 'block';
  documentBody().style.overflowY = 'hidden';
}

/**
 * Close whitelist modal.
 * 
 * @param {Event} e
 * @returns {void}
 */
export const closeWhitelistModal = (e) => {
  if( e.target == whitelistModalBG() || e.target === whitelistModalCloseButton())
  {
    whitelistModalBG().style.display = 'none';
    documentBody().style.overflowY = 'auto';
  }
}

/**
 * On phone code change, update 
 * phone input.
 * 
 * @param {Event} e
 * @returns {void}
 */
export const changePhoneCode = (e) => {
  state.isPhoneCodeGeorgian = whitelistModalSelect().value == '+995';
  const whitelistInputElem = whitelistInput();
  whitelistInputElem.setAttribute('placeholder', state.isPhoneCodeGeorgian ? '5XX XX XX XX' : 'Phone Number');
  
  let phoneNumber = generateInputTextArray(whitelistInputElem.value);
  
  if( state.isPhoneCodeGeorgian) {
    formatPhoneNumber(phoneNumber);
    phoneNumber = phoneNumber.join('');
  } else {
    phoneNumber = removeSpacesFromPhoneNumber(phoneNumber);
  } 

  whitelistInputElem.value = phoneNumber;
}

/**
 * Watch for phone number input
 * changes and behave accordingly.
 * 
 * @param {Event} e
 * @returns {void}
 */
export const watchPhoneNumber = (e) => {
  e.preventDefault();
  const { isPhoneCodeGeorgian } = state;

  if(isDigit(e) || isBackspace(e)) {
    const whitelistInputValue = generateInputTextArray(e);
    
    if(isBackspace(e)) {
      whitelistInputValue.splice(whitelistInputValue.length - 1, 1);
      assignInput(whitelistInputValue);
      hideInputMistake();
      return;
    }

    if(isPhoneCodeGeorgian) {
      if(shouldType(e, whitelistInputValue)) {
        formatPhoneNumber(whitelistInputValue);
        assignInput(whitelistInputValue);
        hideInputMistake();
      }
    }
    else {
      assignInput(whitelistInputValue);
      hideInputMistake();
    }
  }
}

/**
 * Add phone number to whitelists.
 * 
 * @param {Event} e
 * @returns {void}
 */
export const addPhoneNumber = () => {
  const { isPhoneCodeGeorgian } = state;
  const input = whitelistInput();

  if(isPhoneCodeGeorgian) {
    if(isFullyTyped(input)){
      addNewPhoneNumber();
    }
    else {
      expressInputMistake(georgianMistakeText);
    }
  }
  else {
    if(isAcceptable(input)) {
      addNewPhoneNumber();
    }
    else {
      expressInputMistake(generalMistakeText);
    }
  }
}

/**
 * Add phone number and re-fetch whitelist
 * and re-render the page.
 * 
 * @returns {Promise<void>} 
 */
const addNewPhoneNumber = async () => {
  const phoneNumber = assemblePhoneNumber();
  if(doesNumberAlreadyExist(phoneNumber)) {
    alert(copy.numberAlreadyExists);
    return;
  }
  const chargerId = getChargerId();

  try {
    await Service.addToWhitelist(
      {
        charger_id: chargerId,
        phone: phoneNumber,
      }
    );

    await fetchChargerWhitelistData();
    renderWhitelistRecords();
    clearPhoneInput();
  }
  catch(e) {
    console.log(e);
  }
}

/**
 * Remove phone number from whitelist.
 * 
 * @param {chargerId}
 * @param {id}
 * @returns {void}
 */
export const removePhoneFromWhitelist = async (chargerId, id, phone ) => {
  if(!confirm(copy.confirmRemovePhone(phone))) {
    return;
  }

  try {
    await Service.removeFromWhitelist(id);
    await fetchChargerWhitelistData();
    alert(copy.successfullyDeleted);
    renderWhitelistRecords();
  }
  catch(e) {
    console.log(e);
  }
}


