import { setLvl2PricesForm } from './UI/elements'
import { confirmSettingChargingPrices } from './actions'

/**
 * Listen to and register events.
 * 
 * @returns {void}
 */
export default () => {
  console.log(setLvl2PricesForm());
  setLvl2PricesForm().addEventListener('submit', confirmSettingChargingPrices);
}