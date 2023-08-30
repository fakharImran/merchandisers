import Chart from 'chart.js/auto';

const labels = [
    'January 23',
    'February 23',
    'March 23',
    'April 23',
    'May 23',
    'June 23',
];

const data = {
    labels: labels,
    datasets: [{
        label: 'Total Hours Worked',
        backgroundColor: '#1892C0',
        borderColor: 'rgb(255, 99, 132)',
        data: [1, 10, 5, 2, 20, 30, 45],
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

new Chart(
    document.getElementById('myChart'),
    config
);