import { Chart } from 'chart.js'
import { incomeExpenseService } from '../utils/services'
import { charts } from '../utils/enum'

const { INCOME_EXPENSE } = charts;

export let incomeExpenseChartObject = null;

export default async () => {
  
  const result = await fetch(incomeExpenseService);
  const data = await result.json();

  console.log(data);

  incomeExpenseChartObject = new Chart(INCOME_EXPENSE, {
    type: 'bar',
    data: {
      labels: data.month_labels,
      datasets: [
        {
          label: 'შემოსავალი ჯარიმის გარეშე',
          backgroundColor: '#FFC107',
          data: data.income_without_penalty,
          stack: 'A',
        },
        {
          label: 'ჯარიმის შემოსავალი',
          backgroundColor: '#6a1b9a',
          data: data.penalty,
          stack: 'A',
        },
        {
          label: 'ხარჯი',
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
      legend: {
        labels: {
          fontSize: 14,
        }
      }
    }
  });

}