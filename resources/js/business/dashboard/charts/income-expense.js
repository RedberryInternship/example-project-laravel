import { Chart } from 'chart.js'
import { incomeExpenseService } from '../utils/services'
import { charts } from '../utils/enum'

const { INCOME_EXPENSE } = charts;

export let incomeExpenseChartObject = null;

export default async () => {
  
  const result = await fetch(incomeExpenseService);
  const data = await result.json();

  const headings = ['შემოსავალი ჯარიმის გარეშე', 'ჯარიმის შემოსავალი', 'ხარჯი' ];

  incomeExpenseChartObject = new Chart(INCOME_EXPENSE, {
    type: 'bar',
    data: {
      labels: data.month_labels,
      datasets: [
        {
          label: headings[0],
          backgroundColor: '#FFC107',
          data: data.income_without_penalty,
          stack: 'A',
        },
        {
          label: headings[1],
          backgroundColor: '#6a1b9a',
          data: data.penalty,
          stack: 'A',
          
        },
        {
          label: headings[2],
          backgroundColor: 'crimson',
          data: data.expense,
          stack: 'B',
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      scales: {
        yAxes: [
          {
            ticks: {
              fontSize: 14,
            },
          }
        ],
        xAxes: [
          {
            ticks: {
              fontSize: 14,
            },
            stacked: true,
          }
        ]
      },

      tooltips: {
        callbacks: {
          label: (item) => { 
            const { datasetIndex, value } = item;
            const heading = headings[datasetIndex];
            return `${heading}: ${value} GEL`;
           },
        }
      },
      legend: {
        labels: {
          fontSize: 14,
        }
      }
    }
  });

}