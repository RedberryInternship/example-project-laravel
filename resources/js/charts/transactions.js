import months from '../constants/Months';

let initChart = chartData => {
    const ctx = 'transactions-chart';

    // Chart Options
    const chartOptions = {
        elements: {
            rectangle: {
                borderWidth: 2,
                borderColor: "rgb(0, 255, 0)",
                borderSkipped: "left"
            }
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration: 500,
        legend: {
            position: "top"
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
                        display: true
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
                        display: true
                    }
                }
            ]
        },
        title: {
            display: false,
            text: "Chart.js Horizontal Bar Chart"
        }
    };

    // Chart Config
    let config = {
        type: "bar",
        options: chartOptions,
        data: chartData
    };

    new Chart(ctx, config);
};

let getData = () => {
    axios
        .get('/business/analytics/transactions')
        .then(res => {
            console.log(res);

            // Chart Data
            let chartData = {
                labels: monthLabelsFromData(res.data),
                datasets: [
                    {
                        label: "ტრანზაქციების რაოდენობა",
                        data: dataSetsFromData(res.data),
                        backgroundColor: "#14afd7",
                        hoverBackgroundColor: "#00acc1",
                        borderColor: "transparent"
                    },
                    {
                        label: "დახარჯული ელ. ენერგია",
                        data: [45, 50, 70, 31, 100, 129, 331],
                        backgroundColor: "#efc964",
                        hoverBackgroundColor: "#00acc1",
                        borderColor: "transparent"
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

export default function() {
    getData();
};
