import axios from '../vendor/axios';
import chartIncome from './charts/income';
import chartChargerStatuses from './charts/charger-statuses';
import chartMostUsedChargers from './charts/most-used-chargers';
import chartTransactions from './charts/transactions';
import dashboardNavigation from './dashboard-navigation';

// Global variables
window.axios = axios;


// Charts
document.addEventListener('DOMContentLoaded', chartIncome, false);
document.addEventListener('DOMContentLoaded', chartTransactions, false);
document.addEventListener('DOMContentLoaded', chartChargerStatuses, false);
document.addEventListener('DOMContentLoaded', chartMostUsedChargers, false);


// Dashboard Navigation
document.addEventListener('DOMContentLoaded', dashboardNavigation, false);
