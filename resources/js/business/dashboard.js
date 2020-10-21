import axios from '../vendor/axios';
import chartIncomeExpense from './charts/income-expense';
import chartChargerStatuses from './charts/charger-statuses';
import chartTopChargers from './charts/top-chargers';
import chartTransactions from './charts/transactions';
import dashboardNavigation from './dashboard-navigation';

import { 
  registerTransactionsSelectAndEventListener,
  registerIncomeExpenseSelectAndEventListener,
  registerChargersModalBgCloseEventListener,
  registerLogoutEventListener,
} from './utils/helpers'

// Global variables
window.axios = axios;
window.csrf = document.querySelector('meta[name="_token"]').content


// Charts
document.addEventListener('DOMContentLoaded', chartIncomeExpense, false);
document.addEventListener('DOMContentLoaded', chartTransactions, false);
document.addEventListener('DOMContentLoaded', chartChargerStatuses, false);
document.addEventListener('DOMContentLoaded', chartTopChargers, false);


// Dashboard Navigation
document.addEventListener('DOMContentLoaded', dashboardNavigation, false);

// Register event listeners
document.addEventListener('DOMContentLoaded', registerIncomeExpenseSelectAndEventListener, false);
document.addEventListener('DOMContentLoaded', registerTransactionsSelectAndEventListener, false);
document.addEventListener('DOMContentLoaded', registerChargersModalBgCloseEventListener, false);
document.addEventListener('DOMContentLoaded', registerLogoutEventListener, false);
