import { saveProfileForm } from './UI/elements'
import { confirmSavingProfileInformation } from './actions'

/**
 * Register and listen to events.
 * 
 * @returns {void}
 */
export default () => {
  console.log(saveProfileForm());
  saveProfileForm().addEventListener('submit', confirmSavingProfileInformation);
}