import { toggleButton, whitelistButton } from './elements'
import { toggleChargerVisibility } from './actions'

/**
 * Register all the event listeners.
 * 
 * @returns {void}
 */
export const listen = () => {
  toggleButton().addEventListener('click', toggleChargerVisibility);
}