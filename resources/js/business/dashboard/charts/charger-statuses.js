import { chargerStatusesService } from '../utils/services'
import { displayChargersModal } from '../utils/helpers'
import { charts, chargerType as chargerTypeEnum } from '../utils/enum'

const { LVL2, FAST } = chargerTypeEnum;
const { LVL2_CHARGER_STATUSES, FAST_CHARGER_STATUSES } = charts;

export default async () => {
    const result = await fetch(chargerStatusesService);
    const data = await result.json();
    
    renderChart({
        chart: LVL2_CHARGER_STATUSES,
        data: data
    });
  
    renderChart({
        chart: FAST_CHARGER_STATUSES,
        data: data,
    });
};


const renderChart = ({ chart, data }) => {

    const isFast = chart === FAST_CHARGER_STATUSES;
    const chargerStatusesCount = isFast ? data.fast : data.lvl2;
    const chargerType = isFast ? FAST : LVL2;
    const chargerStatuses = data.statuses;

    const chartObj = new Chart(chart, {
        type: "pie",
        data: {
            labels: data.labels,
            datasets: [
                {
                    data: chargerStatusesCount,
                    backgroundColor: ['#27AE60', '#EBC257', '#EB5757']
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            onClick: (e, activeElements) => {
                if(! activeElements.length ){
                    return;
                }

                const index = activeElements[0]._index;
                const chargersStatus = chargerStatuses[index];
                const chargersType = activeElements[0]._chart.chargersType;
                
                displayChargersModal(chargersType, chargersStatus);
            },

            legend: {
                labels: {
                    fontSize: 14,
                }
            }
        },
        
    });

    chartObj.chargersType = chargerType;
};