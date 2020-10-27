import { state } from '../data/state';
import { 
  whitelistInput,
  mistakeTextElement,
  whitelistModalSelect,
 } from '../UI/elements'
 import { isDigit } from '../utils/validation'

/**
 * Generate input text array.
 * 
 * @param {Event} e
 * @returns {Array<char>}
 */
export const generateInputTextArray = (e) => {
  let whitelistInputValue = Array.from(whitelistInput().value);
  
  if(isDigit(e)){
    whitelistInputValue.push(e.key);
  }
  return whitelistInputValue;
}

/**
 * Assign input from array.
 * 
 * @param {Array<char>} inputTextArray
 * @returns {void}
 */
export const assignInput = (inputTextArray) => {
  whitelistInput().value = inputTextArray.join('');
}

/**
 * Express input mistake.
 * 
 * @param {string} text
 * @returns {void}
 */
export const expressInputMistake = (text) => {
  const mistakeTxtElement = mistakeTextElement();
  const whitelistInputElem = whitelistInput();

  mistakeTxtElement.innerHTML = text;
  mistakeTxtElement.classList.remove('hide');
  whitelistInputElem.classList.add('mistaken');
}

/**
 * Hide input mistake.
 * 
 * @returns {void}
 */
export const hideInputMistake = () => {
  mistakeTextElement().classList.add('hide');
  whitelistInput().classList.remove('mistaken');
}

/**
 * Assemble phone number from input.
 * 
 * @returns {string}
 */
export const assemblePhoneNumber = () => {
  return whitelistModalSelect().value + whitelistInput().value.replaceAll(' ', '');
}

/**
 * Check if number already exists.
 * 
 * @returns {boolean}
 */
export const doesNumberAlreadyExist = (phoneNumber) => {
  const foundNumber = state.whitelist.find(el => {
    return el.phone == phoneNumber;
  });

  return !! foundNumber;
}

/**
 * Clear input phone number.
 * 
 * @returns {void}
 */
export const clearPhoneInput = () => {
  whitelistInput().value = '';
}