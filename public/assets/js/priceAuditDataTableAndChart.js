function formatDate(date) {
    // Define an array of month names
    const monthNames = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];
    // Get the current month and day
    const currentMonth = monthNames[date.getMonth()];
    const currentDay = String(date.getDate()).padStart(2, '0');

    // Create the formatted string
    var formattedDate = `${currentMonth} ${currentDay}`;
    return formattedDate;
}
function formatDateYMD(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

const data = {
    labels: products_name,
    datasets: [{
        label: 'Our Product',
        backgroundColor: '#1BC018',
        borderColor: 'rgb(255, 99, 132)',
        data: our_products_price,
        barPercentage: 0.4,  // Adjust the width of the bars (0.4 means 40% of the available space)
        categoryPercentage: 0.5  // Adjust the space between the bars (0.5 means 50% of the available space)
    },
    {
        label: 'Competitor Product',
        backgroundColor: '#1892C0',
        borderColor: 'rgb(255, 99, 132)',
        data: competitor_products_price,
        barPercentage: 0.4,  // Adjust the width of the bars (0.4 means 40% of the available space)
        categoryPercentage: 0.5  // Adjust the space between the bars (0.5 means 50% of the available space)
    }]
};
const config = {
    type: 'bar',

    // type: 'bar',
    data: data,
    options: {
        scales: {
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Price in USD'
                },
                ticks: {
                    stepSize: 10,
                    beginAtZero: true
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function (tooltipItem, data) {
                    return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.xLabel
                }
            }
        },
      indexAxis: 'y',
      elements: {
        bar: {
          borderWidth: 2,
        }
      },
      responsive: true,
    },
  };

var myChartJS = new Chart(
    document.getElementById('myChart'),
    config
);


// datatable

//function for change the data it is comming from datatable search filters nnd getting it as required
function changeGraph(table) {
    var filteredIndexes = table.rows({ search: 'applied' }).indexes();
    var filteredData = [];
    filteredIndexes.each(function (index) {
        var rowData = table.row(index).data();
        filteredData.push(rowData);
    });
    let colData = [];
    let products_name = [];
    let our_products_price = [];
    let competitor_products_price = [];
    console.log('umerrrr', filteredData);

    filteredData.forEach(element => {
        products_name.push([element[4], element[9]]);
        our_products_price.push(element[8]);
        competitor_products_price.push(element[10]);
        
    });
    colData.push({'products_name':products_name, 'our_products_price':our_products_price, 'competitor_products_price':competitor_products_price});
    return colData;
}


$(document).ready(function () { 
    var table = $('#pricaAuditDatatable').DataTable({
        // Add your custom options here
        scrollX: true, // scroll horizontally
        paging: true, // Enable pagination
        searching: true, // Enable search bar
        ordering: true, // Enable column sorting
        lengthChange: false, // Show a dropdown for changing the number of records shown per page
        pageLength: 10, // Set the default number of records shown per page to 10
        dom: 'lBfrtip', // Define the layout of DataTable elements (optional)
        buttons: ['copy', 'excel', 'pdf', 'print'], // Add some custom buttons (optional)
        "pagingType": "full_numbers"
    });
    // Custom search input for 'Name' column
    $('#store-search').on('change', function () {

        // Perform the search on the first column of the DataTable
        const searchValue = this.value.trim();
        table.column(1).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        // table.column(0).search(this.value).draw();
        var storeName = this.value;

        // Assuming you have a dropdown with ID 'location-search'
        var dropdown = $('#location-search');

        allStores.forEach(function (store) {
            if (storeName == store[0]) {
                // Append each option into the select list
                // Append the column data to the dropdown
                table.column(2).search('', true, false).draw(); // Clear previous search
                dropdown.empty();
                dropdown.append('<option value="" selected>--Select--</option>');
                var storeLocations = store[1];
                storeLocations.forEach(function (storeLocation) {
                    dropdown.append('<option value="' + storeLocation + '">' + storeLocation + '</option>');
                });
            }
        });
        if (storeName == "") {
            table.column(2).search('', true, false).draw(); // Clear previous search location
            dropdown.empty();
            dropdown.append('<option value="" selected>--Select--</option>');
            allUniqueLocations.forEach(function (location) {
                // Append each option into the select list
                // Append the column data to the dropdown
                dropdown.innerHTML = '<option value="" selected>--Select--</option>';
                // dropdown.append('<option value="' + location + '">' + location + '</option>');
            });
        }
    });

    $('#location-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(2).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
    });
    $('#category-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(3).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
    });

    $('#merchandiser-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(11).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
    });

    $('#product-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(4).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        console.log("search product", searchValue);

        if(searchValue!='')
        {
        var convertedToChartData = changeGraph(table);
        myChartJS.data.labels = convertedToChartData[0].products_name;
        myChartJS.data.datasets[0].data = convertedToChartData[0].our_products_price;
        myChartJS.data.datasets[1].data = convertedToChartData[0].competitor_products_price;
        myChartJS.update();
        }
        else
        {
            myChartJS.data.labels = '';
            myChartJS.data.datasets[0].data ='';
            myChartJS.data.datasets[1].data ='';
            myChartJS.update();
        }
        
    });
    $('#period-search').on('change', function () {

        if (this.value.includes('to')) {
            const parts = this.value.split('to');

            var start = parts[0].trim(); // Remove leading/trailing spaces
            startDate = start.replace(/^\s+/, ''); // Remove the first space
            startDate = new Date(startDate);
             startDate = formatDateYMD(startDate);

            var end = parts[1].trim(); // Remove leading/trailing spaces
            endDate = end.replace(/^\s+/, ''); // Remove the first space
            endDate = new Date(endDate);
             endDate = formatDateYMD(endDate);

            table.column(0).search('', true, false).draw(); // Clear previous search

            var searchTerms = []; // Initialize an array to store search terms
            function dateRange(startDate, endDate) {
                var currentDate = new Date(startDate);
                var endDateObj = new Date(endDate);
                var dates = [];

                while (currentDate <= endDateObj) {
                    dates.push(formatDateYMD(new Date(currentDate)));
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                return dates;
            }
            var dateList = dateRange(startDate, endDate);
            table.column(0).search(dateList.join('|'), true, false, true).draw(); // Join and apply search terms
        } else {
            console.log("The substring 'to' does not exist in the original string.");
        }

    });

    document.getElementById('clearDate').addEventListener('click', function (element) {
        table.column(0).search('', true, false).draw(); // Clear previous search
        document.getElementById('period-search').clear;
        document.getElementById('period-search').value = 'Date Range';

    });
});