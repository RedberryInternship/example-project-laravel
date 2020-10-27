import { state } from '../../../data/state'
import { removePhoneFromWhitelist } from '../../../listeners/actions'
import noContent from '../../components/no-phone-numbers'
/**
 * Create whitelist content HTML.
 */
export default () => {
  let content = state.whitelist.map(el => item(el)).join(' ');
  
  if( content == '' ) {
    return noContent;
  }

  return content;
}

/**
 * Create whitelist record HTML.
 * 
 * @param {obj} whitelistRecord 
 */
const item = ({ id, phone }) => `
<li class="collection-item">
 <i class="material-icons phone">contact_phone</i>
 <span class="phone-number">${phone}</span>
 <i class="material-icons remove text-red remove-phone-${id}">remove_circle</i>
 </li>
`;

/**
 * Add event listeners to removing items.
 * 
 * @returns {void}
 */
export const listenToRemoveClicks = () => {
  return state.whitelist.forEach(el => {
    document
    .querySelector(`.remove-phone-${el.id}`)
    .addEventListener('click', removePhoneFromWhitelist.bind(null, el.charger_id, el.id, el.phone));
  });
}