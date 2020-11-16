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
              label: 'ტრანზაქციების სიხშირე',
              yAxisID: 'A',
              backgroundColor: '#f443367d',
            },
            {
              data: energy,
              label: 'მოხმარებული ელ. ენერგია',
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
                labelString: "დამუხტვების სიხშირე",
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
                labelString: "მოხმარებული ელ. ენერგია",
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
              // fontFamily: 'pbg-arial'
            }
          }
        }
      });
      console.log(transactionsChartObject)
}

