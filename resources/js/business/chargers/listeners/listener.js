import { 
  toggleButton, 
  whitelistButton, 
  whitelistModalBG,
  whitelistModalSelect,
  whitelistInput,
  addWhitelistButton,
} from '../UI/elements'
import { 
  toggleChargerVisibility,
  openWhitelistModal,
  closeWhitelistModal,
  changePhoneCode,
  watchPhoneNumber,
  addPhoneNumber,
 } from './actions'

/**
 * Register all the event listeners.
 * 
 * @returns {void}
 */
export const listen = () => {
  toggleButton().addEventListener('click', toggleChargerVisibility);
  whitelistButton() && whitelistButton().addEventListener('click', openWhitelistModal);
  whitelistModalBG().addEventListener('click', closeWhitelistModal);
  whitelistModalSelect().addEventListener('change', changePhoneCode);
  whitelistInput().addEventListener('keydown', watchPhoneNumber);
  addWhitelistButton().addEventListener('click', addPhoneNumber);
}