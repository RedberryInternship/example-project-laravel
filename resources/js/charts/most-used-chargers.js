export default function() {
    const ctx = $("#most-used-chargers-chart");

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

    let chartData = {
        labels: ["others", "charger 1", "charger 2", "charger 3", "charger 4"],
        datasets: [
            {
                data: [133, 100, 80, 75, 45],
                backgroundColor: ["#03a9f4", "#00bcd4", "#ffc107", "#e91e63", "#4caf50"],
                label: "აქტიური ჩარჯერები"
            }
        ]
    };

    let config = {
        type: "polarArea",
        options: chartOptions,
        data: chartData
    };

    new Chart(ctx, config);
};
