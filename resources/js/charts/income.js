export default function () {
    const ctx = $("#income-chart");

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

    let chartData = {
        labels: ["იანვარი", "თებერვალი", "მარტი", "აპრილი", "მაისი", "ივნისი", "ივლისი"],
        datasets: [
            {
                label: "შემოსავალი",
                data: [65, 59, 80, 81, 56, 55, 40],
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
                data: [28, 48, 40, 19, 86, 27, 90],
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

    let config = {
        type: "line",
        options: chartOptions,
        data: chartData
    };

    new Chart(ctx, config);
};
