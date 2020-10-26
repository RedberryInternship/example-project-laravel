import { 
  toggleButton, 
  whitelistButton, 
  whitelistModalBG, 
} from './elements'
import { 
  toggleChargerVisibility,
  openWhitelistModal,
  closeWhitelistModal,
 } from './actions'

/**
 * Register all the event listeners.
 * 
 * @returns {void}
 */
export const listen = () => {
  toggleButton().addEventListener('click', toggleChargerVisibility);
  whitelistButton().addEventListener('click', openWhitelistModal);
  whitelistModalBG().addEventListener('click', closeWhitelistModal);
}