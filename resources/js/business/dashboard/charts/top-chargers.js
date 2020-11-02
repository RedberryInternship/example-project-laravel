import { topChargersService } from '../utils/services'
import { charts } from '../utils/enum'

const { TOP_BY_ORDERS, TOP_BY_KILOWATTS, TOP_BY_DURATION } = charts;

export default async () => {
    const result = await fetch(topChargersService);
    const data = await result.json();

    const topByOrders = data.top_by_number_of_orders;
    const topByKilowatts = data.top_by_kilowatts;
    const topByDuration = data.top_by_duration;

    generateTopChart({
        chart: TOP_BY_ORDERS,
        title: 'დამუხტვების რაოდენობა',
        rawData: topByOrders,
        column: 'charge_count',
        color: 'yellowgreen',
        unit: '',
    });
    
    generateTopChart({
        chart: TOP_BY_KILOWATTS,
        title: 'მოხმარებული კილოვატები',
        rawData: topByKilowatts,
        column: 'kilowatts',
        color: '#1976D2',
        unit: 'კვტ.'
    });

    generateTopChart({
        chart: TOP_BY_DURATION,
        title: 'ხანგრძივიბა',
        rawData: topByDuration,
        column: 'duration',
        color: '#FFC107',
        unit: 'წუთი',
    });
}

const generateTopChart = ({ chart, title, rawData, column, color, unit }) => {
    const data = rawData.map((el) => el[column]);
    const labels = rawData.map((el) => el.code);

    const chartObj = new Chart(chart, {
        type: 'horizontalBar',
        data: {
            labels: labels,
            datasets: [
                {
                    backgroundColor: color,
                    label: `TOP ${title}`,
                    data: data,
                    fill: false,
                    xAxisID:'X',
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            tooltips:{
                enabled: true,
                mode: 'nearest',
                callbacks: {
                    title: (item) => {
                        const descriptionJSON = rawData[item[0].index].location;
                        return JSON.parse(descriptionJSON).ka;
                    },
                    label: (item) => `${title}: ${item.value} ${unit}`,
                }
            },
            scales: {
                xAxes: [
                    {
                        id: 'X',
                        scaleLabel: {
                            display: true,
                            labelString: title,
                        },
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


/** 
 * 
 *   type: 'line',
  data: {
    labels:['Георгий', 'Нино', 'Сандро'],
    datasets: [
      {
        data: [ 2, 3, 7 ],
        label: '-- House del Lada --',
        yAxisID: 'A',
        backgroundColor: '#f443367d'
      },
      {
        data: [ 10, 25, 3 ],
        label: '-- House del Shala --',
        yAxisID: 'B',
        backgroundColor: '#3f51b57a'
      }
    ],
  },
  options: {
    scales: {
      yAxes: [{
        id: 'A',
        type: 'linear',
        position: 'left',
        scaleLabel: {
          display: true,
          labelString: "Laada"
        }
      }, {
        id: 'B',
        type: 'linear',
        position: 'right',
        scaleLabel: {
          display: true,
          labelString: "shala"
        }
      }]
    }
  }
});
*/