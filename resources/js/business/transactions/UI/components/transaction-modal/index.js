/**
 * Generate transaction info modal.
 * 
 * @param {object} data
 * @returns {string}
 */
export default (data) => {
  const {
    ID, 
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
  } = data;

  const formattedStartTime = new Date(start_time).toGMTString();
  const formattedChargeTime = new Date(charge_time).toGMTString();
  const formattedEndTime = new Date(end_time).toGMTString();
  
  return `
  <div class="transaction-modal-bg bpg-arial">
    <div class="transaction-modal-wrapper">
      <img class="transaction-modal-close-btn" src="/images/simulator/close.png" />
      
      <table class="responsive-table transaction-modal">
      <thead>
        <tr>
            <th>დამტენის კოდი</th>
            <th>${charger_code}</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>მისამართი</td>
          <td>${charger_description}</td>
        </tr>
        <tr>
          <td>დამტენის ტიპი</td>
          <td>${charger_type}</td>
        </tr>
        <tr>
          <td>დამუხტვის სიმძლავრე</td>
          <td>${charge_power}</td>
        </tr>
        <tr>
          <td>მოხმარებული კილოწატები</td>
          <td>${consumed_kilowatts}</td>
        </tr>
        <tr>
          <td>დამუხტვის ხანგრძლივობა</td>
          <td>${charge_duration}</td>
        </tr>
        <tr>
          <td>დამუხტვის ფასი</td>
          <td>${charge_price}</td>
        </tr>
        <tr>
          <td>ჯარიმა</td>
          <td>${penalty_fee}</td>
        </tr>
        <tr>
          <td>დამუხტვის დაწყება</td>
          <td>${formattedStartTime}</td>
        </tr>
        <tr>
          <td>დამუხტვის შეჩერება</td>
          <td>${formattedChargeTime}</td>
        </tr>
        <tr style="border-bottom:0">
          <td>დამუხტვის დასრულება</td>
          <td>${formattedEndTime}</td>
        </tr>
      </tbody>
    </table>
    </div>
  </div>
  `;
}

