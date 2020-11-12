import { alertCard } from './UI/elements'
import copy from './utils/copy'

/**
 * If alert card exists remove in
 * 5 seconds timeout.
 * 
 * @returns {void}
 */
export const removeAlertSoon = () => {
  const alert = alertCard();
  
  alert && setTimeout(function() {
      alert.remove();
  }, 5000);
}

/**
 * Confirm if it wants to delete group.
 * 
 * @param {Event} e
 * @returns {void}
 */
export const confirmDeletingGroup = function(e) {
  const name = this.dataset.groupName;
  const doseItWant = confirm(copy.confirmDeletingGroup(name));
  
  if(! doseItWant ) {
    e.preventDefault();
  }
}