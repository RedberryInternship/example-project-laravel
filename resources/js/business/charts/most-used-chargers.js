let initChart = chartData => {
    const ctx = "most-used-chargers-chart";

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
        .get('/business/analytics/active-chargers')
        .then(res => {
            console.log(res);

            let chartData = {
                labels: chargerLabelsFromData(res.data),
                datasets: [
                    {
                        data: dataSetsFromData(res.data),
                        backgroundColor: ["#03a9f4", "#00bcd4", "#ffc107", "#e91e63", "#4caf50"],
                        label: "აქტიური ჩარჯერები"
                    }
                ]
            };

            initChart(chartData);
        });
};

let chargerLabelsFromData = data => {
    return Object
        .keys(data)
        .map(index => data[index].location.en);
};

let dataSetsFromData = data => {
    return Object
        .keys(data)
        .map(index => parseInt(data[index].charger_connector_type_orders_count));
};

export default function () {
    getData();
};

