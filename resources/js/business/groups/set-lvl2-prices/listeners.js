import { setLvl2PricesForm } from './UI/elements'
import { confirmSettingChargingPrices } from './actions'

/**
 * Listen to and register events.
 * 
 * @returns {void}
 */
export default () => {
  setLvl2PricesForm().addEventListener('submit', confirmSettingChargingPrices);
}