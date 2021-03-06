import { deleteAllChargingPricesButton, storeAllChargersIntoGroupButton } from './elements'
import { deleteAllChargingPricesAndReload, storeAllChargersIntoGroupAndReload } from './actions'

export default () => {
  deleteAllChargingPricesButton().addEventListener('click', deleteAllChargingPricesAndReload);
  storeAllChargersIntoGroupButton() && storeAllChargersIntoGroupButton().addEventListener('click', storeAllChargersIntoGroupAndReload);
}