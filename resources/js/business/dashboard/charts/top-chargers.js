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
    });
    
    generateTopChart({
        chart: TOP_BY_KILOWATTS,
        title: 'მოხმარებული კილოვატები',
        rawData: topByKilowatts,
        column: 'kilowatts',
        color: '#1976D2',
    });

    generateTopChart({
        chart: TOP_BY_DURATION,
        title: 'ხანგრძივიბა',
        rawData: topByDuration,
        column: 'duration',
        color: '#FFC107',
    });
}

const generateTopChart = ({ chart, title, rawData, column, color }) => {
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
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            tooltips:{
                enabled: true,
                mode: 'nearest',
                callbacks: {
                    title: (item) => `დამტენი - ${item[0].label}`,
                    label: (item) => `${title}: ${item.value}`,
                }
            }
        }
    });
}

