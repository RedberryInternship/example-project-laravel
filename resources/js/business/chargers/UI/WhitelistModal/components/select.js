import { state } from '../../../data/state'

/**
 * Generate country codes
 * select ui.
 * 
 * @returns {string}
 */
export const phoneCodesSelect = () => {
  const options = selectOptions();
  
  return `
  <div class="col s2">
    <select name="country-code" class="whitelist-modal-select" style="display: block !important">
      ${options}
    </select>
  </div>
  `;
}

/**
 * Generate options for select country
 * codes.
 * 
 * @returns {string}
 */
const selectOptions = () => {
  return state.phoneNumbers
    .map(el => el.phone_code)
    .filter((value, index, self) => self.indexOf(value) === index)
    .filter(el => !!el.trim())
    .map(el => el[0] == '+' ? el : '+' + el)
    .sort((a, b) => a.length - b.length)
    .map(el => {

      const isGeorgianCode = el == '+995';

      return `
        <option value="${el}" ${isGeorgianCode && 'selected'}>${el}</option>
      `;
    })
    .join(' ');
}