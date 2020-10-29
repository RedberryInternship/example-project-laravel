import { openModalButtons, openedModal } from './UI/elements'
import { openTransactionsModal, closeModal } from './actions'

export default () => {
  openModalButtons().forEach(el => el.addEventListener('click', openTransactionsModal));
}

/**
 * Register close modal event
 * 
 * @returns {void} 
 */
export const registerCloseModalEvent = () => {
  openedModal().addEventListener('click', closeModal);
}