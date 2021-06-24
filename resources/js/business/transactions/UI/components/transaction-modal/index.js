/**
 * Generate transaction info modal.
 * 
 * @param {object} data
 * @returns {string}
 */
export default (data) => {
  const {
    charge_duration, 
    charge_power, 
    charge_price, 
    charge_time, 
    charger_code,
    charger_description,
    charger_type,
    consumed_kilowatts,
    start_time,
    end_time,
    penalty_fee, 
    refunded_money,
  } = data;

  const formattedStartTime = new Date(start_time).toLocaleString();
  const formattedChargeTime = new Date(charge_time).toLocaleString();
  const formattedEndTime = new Date(end_time).toLocaleString() 


  return `
  <div class="transaction-modal-bg bpg-arial">
    <div class="transaction-modal-wrapper">
      <img class="transaction-modal-close-btn" src="/images/simulator/close.png" />
      <div class="transactions-content-container">      
          <table class="responsive-table transaction-modal">
          <thead>
            <tr>
                <th>${__('transactions.charger-code')}</th>
                <th>${charger_code}</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>${__('transactions.address')}</td>
              <td>${charger_description}</td>
            </tr>
            <tr>
              <td>${__('transactions.charger-type')}</td>
              <td>${charger_type}</td>
            </tr>
            <tr>
              <td>${__('transactions.charge-power')}</td>
              <td>${charge_power}</td>
            </tr>
            <tr>
              <td>${__('transactions.consumed-kilowatts')}</td>
              <td>${consumed_kilowatts}</td>
            </tr>
            <tr>
              <td>${__('transactions.duration')}</td>
              <td>${charge_duration}</td>
            </tr>
            <tr>
              <td>${__('transactions.price')}</td>
              <td>${charge_price}</td>
            </tr>
            <tr>
              <td>${__('transactions.fine')}</td>
              <td>${penalty_fee}</td>
            </tr>
            <tr>
              <td>${__('transactions.refunded-money')}</td>
              <td>${refunded_money}</td>
            </tr>
            <tr>
              <td>${__('transactions.charge-start-time')}</td>
              <td>${formattedStartTime}</td>
            </tr>
            <tr>
              <td>${__('transactions.charge-stop-time')}</td>
              <td>${formattedChargeTime}</td>
            </tr>
            <tr style="border-bottom:0">
              <td>${__('transactions.charge-end-time')}</td>
              <td>${formattedEndTime}</td>
            </tr>
          </tbody>
        </table>
    </div>
    </div>
  </div>
  `;
}

