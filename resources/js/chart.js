import Chart from 'chart.js/auto';
//  fetch('/chart-data')
//             .then(response => response.json())
//             .then(data => {
//                 // Create a chart using the fetched data
//                 var ctx = document.getElementById('myChart').getContext('2d');
//                 var myChart = new Chart(ctx, {
//                     type: 'bar', // Chart type (e.g., 'bar', 'line', 'pie')
//                     data: {
//                         labels: data.labels, // Label data
//                         datasets: [{
//                             label: 'Your Data',
//                             data: data.values, // Actual data values
//                             backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color
//                             borderColor: 'rgba(75, 192, 192, 1)', // Border color
//                             borderWidth: 1 // Border width
//                         }]
//                     },
//                     options: {
//                         // Chart options (e.g., title, scales, tooltips, etc.)
//                     }
//                 });
//             });

            
// const labels = [
//     'January 23',
//     'February 23',
//     'March 23',
//     'April 23',
//     'May 23',
//     'June 23',
// ];

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

new Chart(
    document.getElementById('myChart'),
    config
);