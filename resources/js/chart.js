import Chart from 'chart.js/auto';
const data = {
    labels: labels,
    datasets: [{
        label: 'Total Hours Worked',
        backgroundColor: '#1892C0',
        borderColor: 'rgb(255, 99, 132)',
        data: hoursWorked,
    }]
};
const config = {
    type: 'bar',
    data: data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    },
  };

var myChartJS= new Chart(
    document.getElementById('myChart'),
    config
);

function updateChart() {
  // Update the chart data
  myChartJS.data.labels = labels;
  myChartJS.data.datasets[0].data = hoursWorked;
  myChartJS.update(); // Refresh the chart
}
