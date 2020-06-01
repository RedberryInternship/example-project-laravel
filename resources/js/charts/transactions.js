export default function() {
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

    // Chart Data
    let chartData = {
        labels: ["იანვარი", "თებერვალი", "მარტი", "აპრილი", "მაისი", "ივნისი", "ივლისი"],
        datasets: [
            {
                label: "ტრანზაქციების რაოდენობა",
                data: [65, 59, 80, 81, 200, 159, 321],
                backgroundColor: "#14afd7",
                hoverBackgroundColor: "#00acc1",
                borderColor: "transparent"
            }
        ]
    };

    // Chart Config
    let config = {
        type: "bar",
        options: chartOptions,
        data: chartData
    };

    new Chart(ctx, config);
};
