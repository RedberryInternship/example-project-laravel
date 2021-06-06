import { getTransactionInfo } from './utils/service'
import generateTransactionModal from './UI/components/transaction-modal'
import { 
  documentBody, 
  closeModalButton, 
  openedModal,
} from './UI/elements'
import { registerCloseModalEvent } from './listeners'
import { parseIntoHTML } from './utils/helpers'

/**
 * Transactions state.
 */
const transactions = {}; 

/**
 * Open transaction modal on click.
 * 
 * @returns {void}
 */
export const openTransactionsModal = async function() {
  const transactionId = +this.dataset.transactionId;
  let data = null;
  if(!transactions[transactionId]) {
      const result = await getTransactionInfo(transactionId);
      data = await result.json();
      transactions[transactionId] = data;
  }
  else {
      data = transactions[transactionId];
  }

  const transactionModalString = generateTransactionModal(data);
  const transactionModalHTML = parseIntoHTML(transactionModalString);
  documentBody().style.overflowY = 'hidden';
  documentBody().prepend(transactionModalHTML);
  registerCloseModalEvent();
}

/**
 * Close transaction modal.
 * 
 * @param {Event} e
 * @returns {void}
 */
export const closeModal = (e) => {
  const { target } = e;

  if(target == openedModal() || target == closeModalButton()) {
    openedModal().remove();
    documentBody().style.overflowY = 'auto';
  }
}
