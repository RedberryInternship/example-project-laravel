let initChart = (chartData, ctx) => {
    // Chart Options
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration: 500,
        legend: {
            position: "top"
        },
        title: {
            display: false,
            text: "Chart.js Polar Area Chart"
        },
        scale: {
            ticks: {
                beginAtZero: true
            },
            reverse: false
        },
        animation: {
            animateRotate: false
        }
    };

    // Chart Config
    let config = {
        type: "polarArea",
        options: chartOptions,
        data: chartData
    };

    new Chart(ctx, config);
};

let getData = () => {
    axios
        .get('/business/analytics/charger-statuses')
        .then(res => {
            // Chart Data
            let lvl2ChartData = {
                labels: chargerStatusesLabelsFromData(res.data.lvl2),
                datasets: [
                    {
                        data: dataSetsFromData(res.data.lvl2),
                        backgroundColor: ["#03a9f4", "#00bcd4", "#ffc107", "#e91e63", "#4caf50"]
                    }
                ]
            };

            // Chart Data
            let fastChartData = {
                labels: chargerStatusesLabelsFromData(res.data.fast),
                datasets: [
                    {
                        data: dataSetsFromData(res.data.fast),
                        backgroundColor: ["#03a9f4", "#00bcd4", "#ffc107", "#e91e63", "#4caf50"]
                    }
                ]
            };

            initChart(lvl2ChartData, 'charger-statuses-chart-lvl2');
            initChart(fastChartData, 'charger-statuses-chart-fast');
        });
};

let chargerStatusesLabelsFromData = data => {
    return Object
        .keys(data)
        .map(index => index);
};

let dataSetsFromData = data => {
    return Object
        .keys(data)
        .map(index => parseInt(data[index]));
};

export default function () {
    getData();
};
