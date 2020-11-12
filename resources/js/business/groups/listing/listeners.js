import { removableGroups } from './UI/elements'
import { removeAlertSoon, confirmDeletingGroup } from './actions'

/**
 * Register listeners and timeouts.
 * 
 * @returns {void}
 */
export default () => {
  removeAlertSoon();
  removableGroups().forEach(el => el.addEventListener('submit', confirmDeletingGroup));
}