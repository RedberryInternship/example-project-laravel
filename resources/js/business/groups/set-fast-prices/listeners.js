import { setFastPricesForm } from './UI/elements'
import { confirmSettingChargingPrices } from './actions'

/**
 * Listen to and register events.
 * 
 * @returns {void}
 */
export default () => {
  setFastPricesForm().addEventListener('submit', confirmSettingChargingPrices);
}