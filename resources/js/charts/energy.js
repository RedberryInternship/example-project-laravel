export default function() {
    const ctx = $("#energy-chart");

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
            text: "ელ. ენერგია"
        }
    };

    let chartData = {
        labels: ["იანვარი", "თებერვალი", "მარტი", "აპრილი", "მაისი", "ივნისი", "ივლისი"],
        datasets: [
            {
                label: "დახარჯული კილოვატები",
                data: [65, 59, 80, 81, 56, 55, 40],
                fill: false,
                borderColor: "#ff7101",
                pointBorderColor: "#e91e63",
                pointBackgroundColor: "#FFF",
                pointBorderWidth: 2,
                pointHoverBorderWidth: 2,
                pointRadius: 4
            },
            {
                label: "ხარჯი",
                data: [28, 48, 40, 19, 86, 27, 90],
                fill: false,
                borderColor: "#ffc727",
                pointBorderColor: "#03a9f4",
                pointBackgroundColor: "#FFF",
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
