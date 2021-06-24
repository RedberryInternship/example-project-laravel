import { Chart } from 'chart.js'
import { transactionsService } from '../utils/services'
import { charts } from '../utils/enum'

const { TRANSACTIONS } = charts;

export let transactionsChartObject = null;

export default async () => {
    const response = await fetch(transactionsService);
    const data = await response.json();

    const transactions = data.transactions;
    const energy = data.energy;
    const monthLabels = data.month_labels;

    transactionsChartObject = new Chart(TRANSACTIONS,{
        type: 'line',
        data: {
          labels:monthLabels,
          datasets: [
            {
              data: transactions,
              label: __('dashboard.transactions.transaction-density'),
              yAxisID: 'A',
              backgroundColor: '#f443367d',
            },
            {
              data: energy,
              label: __('dashboard.transactions.used-power'),
              yAxisID: 'B',
              backgroundColor: '#3f51b57a'
            }
          ],
        },
        options: {
          maintainAspectRatio: false,
          scales: {
            yAxes: [{
              id: 'A',
              type: 'linear',
              position: 'left',
              scaleLabel: {
                display: true,
                labelString: __('dashboard.transactions.charge-count'),
                fontSize: 14,
              },
              ticks: {
                fontSize: 14,
              }
            },
            {
              id: 'B',
              type: 'linear',
              position: 'right',
              scaleLabel: {
                display: true,
                labelString: __('dashboard.transactions.used-power'),
                fontSize: 14,
              },
              ticks: {
                fontSize: 14,
              }
            }
          ],
          xAxes: [
            {
              ticks: {
                fontSize: 14,
              }
            }
          ]
          },
          legend: {
            labels: {
              fontSize: 14,
            }
          }
        }
      });
}
