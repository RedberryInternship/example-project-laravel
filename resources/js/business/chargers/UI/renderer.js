import { 
  documentBody, 
  whitelistModal as whitelistModalElem, 
} from './elements'
import whitelistModal from './WhitelistModal/whitelist-modal'
import generateWhitelistContent, { listenToRemoveClicks } from './WhitelistModal/components/whitelist-content'

/**
 * Render select ui.
 * 
 * @returns {void}
 */
export const renderWhitelistModal = () => {
  const modal =  whitelistModal();
  const body = documentBody();
  body.innerHTML = modal + body.innerHTML;
  listenToRemoveClicks();
}

/**
 * Render whitelist records.
 * 
 * @returns {void}
 */
export const renderWhitelistRecords = () => {
  whitelistModalElem().innerHTML = generateWhitelistContent();
  listenToRemoveClicks();  
}
