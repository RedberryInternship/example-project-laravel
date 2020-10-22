import { transactionsChartObject } from '../charts/transactions'
import { incomeExpenseChartObject } from '../charts/income-expense'
import { 
  transactionsService, 
  incomeExpenseService, 
  filterChargersService,
  logout, 
} from './services';

export const registerTransactionsSelectAndEventListener = () => {
  $('#transactions-select').on('change', updateTransactionsChart);
}

export const registerIncomeExpenseSelectAndEventListener = () => {
  $('#income-expense-select').on('change', updateIncomeExpenseChart);
}

export const registerChargersModalBgCloseEventListener = () => {
  $('.chargers-modal-bg').on('click', closeChargersModal.bind(null, false));
  $('.charger-modal-close-btn').on('click', closeChargersModal.bind(null, true));
}

export const registerLogoutEventListener = () => {
  document.querySelector('.logout-from-business-admin').addEventListener('click', () => {
    const shouldLogout = confirm('ნამდვილად გსურთ Business ადმინ პანელიდან გასვლა?');

    shouldLogout && (window.location = logout);
  });
}

/**
 * Update transactions chart when changing
 * year from select.
 * 
 * @returns {void}
 */
const updateTransactionsChart = async () => {
  const year = document.querySelector('#transactions-select').value;
  
  const result = await fetch(transactionsService + `?year=${year}`)
  const data = await result.json()

  const transactions = data.transactions;
  const energy = data.energy;

  transactionsChartObject.data.datasets[0].data = transactions;
  transactionsChartObject.data.datasets[1].data = energy;
  transactionsChartObject.update();
}

/**
 * Update income/expense chart when changing
 * year from select.
 * 
 * @returns {void}
 */
const updateIncomeExpenseChart = async () => {
  const year = document.querySelector('#income-expense-select').value;
  
  const result = await fetch(incomeExpenseService + `?year=${year}`)
  const data = await result.json()

  incomeExpenseChartObject.data.datasets[0].data = data.income;
  incomeExpenseChartObject.data.datasets[1].data = data.expense;
  incomeExpenseChartObject.update();
}

/**
 * Display chargers modal on chargers statues section.
 * 
 * @param {string} chargersType
 * @param {string} chargersStatus
 * @returns {void}
 */
export const displayChargersModal = async (chargersType, chargerStatus) => {

  const body = JSON.stringify({
    status: chargerStatus,
    type: chargersType,
    _token: csrf,
  });

  const result = await fetch(filterChargersService, {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    method: 'POST',
    body: body, 
  });

  const data = await result.json();
  const nodes = data.map((el) => createChargerModalNode(el)).join('');
  const modalContent = wrapChargerModalNodes(nodes);
  openChargersModal(modalContent);
}

/**
 * Open chargers modal.
 * 
 * @returns {void}
 */
const openChargersModal = (modalContent) => {
  document.querySelector('.chargers-modal-bg').style.display = 'block';
  document.querySelector('.chargers-modal').innerHTML = modalContent;
}

/**
 * Close chargers modal.
 * 
 * @returns {void}
 */
const closeChargersModal = (byForce, e) => {
  const chargersModalBg = document.querySelector('.chargers-modal-bg');

  if(e.target === chargersModalBg || byForce){
    chargersModalBg.style.display = 'none';
    document.querySelector('.chargers-modal').innerHTML = '';
  }
}

/**
 * Wrap charger modal nodes.
 * 
 * @param {string} nodes
 * @returns {string}
 */
const wrapChargerModalNodes = ( nodes ) => {
  return `
  <table class="responsive-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>სახელი</th>
            <th>კოდი</th>
            <th class="center">საჯარო</th>
            <th class="center">სტატუსი</th>
            <th class="center">რედაქტირება</th>
        </tr>
    </thead>

    <tbody>
      ${nodes}
    </tbody>
  </table>
  `;
}

/**
 * Create charger modal node.
 * 
 * @returns {string}
 */
const createChargerModalNode = (data) => {
  const {id, name, code, status } = data;

  return `
  <tr>
    <td>${id}</td>
    <td>${name.ka}</td>
    <td>${code}</td>
    <td class="center">
        <i class="material-icons dp48" style="${ data.public ? 'color: green' : 'color: red' }">${ data.public ? 'check' : 'close' }</i>
    </td>
    <td>${status}</td>
    <td class="center">
        <a href="/business/chargers/${id}/edit" target="_blank" class="btn waves-effect waves-light btn-small">
            <i class="material-icons">edit</i>
        </a>
    </td>
  </tr>
`;
}