import { deleteAllChargingPricesButton } from './elements'
import { deleteAllChargingPricesAndReload } from './actions'

export default () => {
  deleteAllChargingPricesButton().addEventListener('click', deleteAllChargingPricesAndReload);
}