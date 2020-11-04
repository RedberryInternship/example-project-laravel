import { 
  documentBody, 
  whitelistModal as whitelistModalElem, 
} from './elements'
import whitelistModal from './WhitelistModal/whitelist-modal'
import generateWhitelistContent, { 
  listenToRemoveClicks 
} from './WhitelistModal/components/whitelist-content'
import { parseIntoHTML } from '../utils/helpers'

/**
 * Render select ui.
 * 
 * @returns {void}
 */
export const renderWhitelistModal = () => {
  const modal =  parseIntoHTML(whitelistModal());
  documentBody().prepend(modal);
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
