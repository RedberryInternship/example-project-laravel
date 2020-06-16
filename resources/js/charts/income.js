import months from '../constants/Months';

let initChart = chartData => {
    const ctx = 'income-chart';

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            position: "bottom"
        },
        hover: {
            mode: "label"
        },
        scales: {
            xAxes: [
                {
                    display: true,
                    gridLines: {
                        color: "#f3f3f3",
                        drawTicks: false
                    },
                    scaleLabel: {
                        display: true,
                        labelString: "Month"
                    }
                }
            ],
            yAxes: [
                {
                    display: true,
                    gridLines: {
                        color: "#f3f3f3",
                        drawTicks: false
                    },
                    scaleLabel: {
                        display: true,
                        labelString: "Value"
                    }
                }
            ]
        },
        title: {
            display: true,
            text: "შემოსავალი"
        }
    };

    let config = {
        type: "line",
        options: chartOptions,
        data: chartData
    };

    new Chart(ctx, config);
};

let getData = () => {
    axios
        .get('/business/analytics/income')
        .then(res => {
            // Chart Data
            let chartData = {
                labels: monthLabelsFromData(res.data.income),
                datasets: [
                    {
                        label: "შემოსავალი",
                        data: dataSetsFromData(res.data.income),
                        fill: false,
                        borderColor: "#ff5354",
                        pointBorderColor: "#ff5354",
                        pointBackgroundColor: "#ff5354",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: "ხარჯი",
                        data: dataSetsFromData(res.data.expense),
                        fill: false,
                        borderColor: "#f48eaf",
                        pointBorderColor: "#f48eaf",
                        pointBackgroundColor: "#f48eaf",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }
                ]
            };

            initChart(chartData);
        });
};

let monthLabelsFromData = data => {
    return Object
        .keys(data)
        .map(index => months[parseInt(index.split('-')[1])]);
};

let dataSetsFromData = data => {
    return Object
        .keys(data)
        .map(index => parseInt(data[index]));
};

export default function () {
    getData();
};
