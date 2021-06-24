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
        title: __('dashboard.top-chargers.charge-count'),
        rawData: topByOrders,
        column: 'charge_count',
        color: 'yellowgreen',
        unit: '',
    });

    generateTopChart({
        chart: TOP_BY_KILOWATTS,
        title: __('dashboard.top-chargers.used-kilowatts'),
        rawData: topByKilowatts,
        column: 'kilowatts',
        color: '#1976D2',
        unit: __('dashboard.top-chargers.kwt'),
    });

    generateTopChart({
        chart: TOP_BY_DURATION,
        title: __('dashboard.top-chargers.duration'),
        rawData: topByDuration,
        column: 'duration',
        color: '#FFC107',
        unit: __('dashboard.top-chargers.minute'),
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
                        return JSON.parse(descriptionJSON)[locale];
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
                            fontSize: 14,
                        },
                        ticks: {
                          fontSize: 14,
                        },
                    }
                ]
            },
            legend: {
              labels: {
                fontSize: 14,
              }
            },
        }
    });
}
