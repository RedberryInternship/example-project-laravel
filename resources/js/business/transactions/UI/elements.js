export const documentBody     = () => document.querySelector('body')
export const openedModal      = () => document.querySelector('.transaction-modal-bg');
export const closeModalButton = () => document.querySelector('.transaction-modal-close-btn');
export const openModalButtons = () => Array.from(document.getElementsByClassName('open-modal-button'));
export const datePickers      = () => document.querySelectorAll('.datepicker');