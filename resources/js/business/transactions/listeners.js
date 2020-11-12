import { openModalButtons, openedModal, datePickers } from './UI/elements'
import { openTransactionsModal, closeModal } from './actions'
import Materialize from 'materialize-css'
import { datePickerConfig } from './utils/materialize-config'

export default () => {
  openModalButtons().forEach(el => el.addEventListener('click', openTransactionsModal));
  Materialize.Datepicker.init(datePickers(), datePickerConfig);
}

/**
 * Register close modal event
 * 
 * @returns {void} 
 */
export const registerCloseModalEvent = () => {
  openedModal().addEventListener('click', closeModal);
}