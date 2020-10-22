import { Chart } from 'chart.js'
import { incomeExpenseService } from '../utils/services'
import { charts } from '../utils/enum'

const { INCOME_EXPENSE } = charts;

export let incomeExpenseChartObject = null;

export default async () => {
  
  const result = await fetch(incomeExpenseService);
  const data = await result.json();

  incomeExpenseChartObject = new Chart(INCOME_EXPENSE, {
    type: 'bar',
    data: {
      labels: data.month_labels,
      datasets: [
        {
          label: 'შემოსავალი',
          backgroundColor: '#FFC107',
          data: data.income,
        },
        {
          label: 'ხარჯი',
          backgroundColor: 'crimson',
          data: data.expense,
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
    }
  });

}