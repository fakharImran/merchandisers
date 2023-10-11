
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

//create last days dates
function createLastDaysDates(data, startDate = 0, endDate = 0)
{
     // Initialize an array to store the previous 7 days
     const previousSevenDays = [];
    
     if (startDate == 0 && endDate == 0) {
         // Calculate the end date (today)
         endDate = new Date();
         
         // Calculate the start date (7 days ago from today)
         startDate = new Date();
         startDate.setDate(endDate.getDate() - 6);
     } else {
         // Parse provided start and end dates
         startDate = new Date(startDate);
         endDate = new Date(endDate);
     }
 
     // Iterate for each day in the last 7 days
     for (let i = 0; i < 7; i++) {
         const currentDate = new Date(startDate);
         currentDate.setDate(startDate.getDate() + i);
         
         // Calculate the start and end times for the current day
         const dayStart = new Date(currentDate);
         dayStart.setHours(0, 0, 0, 0);
         const dayEnd = new Date(currentDate);
         dayEnd.setHours(23, 59, 59, 999);
 
         // Filter data for the current day
         const filteredData = data.filter(element => {
             const elementDate = new Date(element['date']);
             return elementDate >= dayStart && elementDate <= dayEnd;
         });
 
         // Calculate the total stock for the current day
         const totalStock = filteredData.reduce((acc, element) => acc + element['stock'], 0);
         previousSevenDays.push(totalStock);
     }
 
     // Format dates and reverse the arrays
     const formattedDates = previousSevenDays.map((_, i) => {
         const currentDate = new Date(startDate);
         currentDate.setDate(startDate.getDate() + i);
         return formatDate(currentDate);
     });
     
         labels= formattedDates;
         periodData= previousSevenDays;
}
//create last week dates
function createLastWeeksDates(data, startDate = 0, endDate = 0)
{
    // Initialize an array to store the previous 6 weeks
    const previousWeeks = [];

    if (startDate == 0 && endDate == 0) {
        // Calculate the start date of the current week (Sunday)
        const currentWeekStartDate = new Date();
        currentWeekStartDate.setDate(currentWeekStartDate.getDate() - currentWeekStartDate.getDay());


        // Calculate the start and end dates for each of the previous 6 weeks
        for (let i = 0; i < 6; i++) {
            startDate = new Date(currentWeekStartDate);
            startDate.setDate(currentWeekStartDate.getDate() - 7 * i); // Subtract 7 days for each previous week
            endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6); // Add 6 days to get the end of the week
            previousWeeks.push({ startDate, endDate });
        }
    }
    else {
        startDate = new Date(startDate);
        endDate = new Date(endDate);
        startDate.setDate(startDate.getDate() - 7);
        let currentWeekStartDate = endDate; // Initialize with the provided end date
        currentWeekStartDate.setDate(currentWeekStartDate.getDate() + currentWeekStartDate.getDay());

        // Calculate the difference in milliseconds
        const timeDifference = currentWeekStartDate.getTime() - startDate.getTime();
        // Convert milliseconds to weeks (1 week = 7 days)
        const weeks = Math.floor(timeDifference / (1000 * 60 * 60 * 24 * 7));

        for (let i = 0; i <= weeks; i++) {
            const weekEndDate = new Date(currentWeekStartDate);
            weekEndDate.setDate(currentWeekStartDate.getDate() - 1); // Subtract 1 day to get the week's end date
            const weekStartDate = new Date(weekEndDate);
            weekStartDate.setDate(weekEndDate.getDate() - 6); // Subtract 6 days to get the start date
            // Check if the week's start date is within the provided range
            if (weekStartDate >= startDate) {
                previousWeeks.push({ startDate: weekStartDate, endDate: weekEndDate });
            }

            currentWeekStartDate = weekStartDate; // Set the next week's start date
        }
    }

//check the weeks arroding to their hours
    var totalStock = 0;
    var weekarray = [];
    previousWeeks.forEach(week => {
        data.forEach(element => {
            chkDate = element['date'];
            if (formatDateYMD(week.startDate) <= chkDate && formatDateYMD(week.endDate) >= chkDate) {
                totalStock += element['stock'];
            } else {
            }
        });
        weekarray.push(totalStock);
        totalStock = 0;
    });
    previousWeeks.forEach(function (element) {
        element.startDate = formatDate(element.startDate);
        element.endDate = formatDate(element.endDate);
    });
    const previousWeeksArray = [];
    previousWeeks.forEach(function (element) {
        previousWeeksArray.push(element.startDate + ' - ' + element.endDate);
    });

    periodData = weekarray.reverse();
    labels = previousWeeksArray.reverse();
}
//create last months dates
function createLastMonthsDates(data, startDate = 0, endDate = 0)
{
    // Initialize an array to store the previous 7 months
    const previousMonths = [];

    if (startDate == 0 && endDate == 0) {
        // Calculate the start date of the current month
        const currentMonthStartDate = new Date();
        currentMonthStartDate.setDate(1); // Set the date to the first day of the month

        // Calculate the start and end dates for each of the previous 7 months
        for (let i = 0; i < 7; i++) {
            startDate = new Date(currentMonthStartDate);
            startDate.setMonth(currentMonthStartDate.getMonth() - i); // Subtract i months for each previous month
            startDate.setDate(1); // Set the date to the first day of the month

            endDate = new Date(startDate);
            endDate.setMonth(startDate.getMonth() + 1); // Add 1 month to get the end of the month
            endDate.setDate(0); // Set the date to the last day of the month

            previousMonths.push({ startDate, endDate });
        }
    }
    else {
        startDate = new Date(startDate);
        endDate = new Date(endDate);
        startDate.setDate(1); // Set the date to the first day of the month

        let currentMonthStartDate = endDate; // Initialize with the provided end date
        currentMonthStartDate.setDate(1); // Set the date to the first day of the month

        // Calculate the difference in months
        const monthsDifference = (currentMonthStartDate.getFullYear() - startDate.getFullYear()) * 12
            + currentMonthStartDate.getMonth() - startDate.getMonth();

        for (let i = 0; i <= monthsDifference; i++) {
            const monthEndDate = new Date(currentMonthStartDate);
            monthEndDate.setMonth(currentMonthStartDate.getMonth() + 1); // Add 1 month to get the end date
            monthEndDate.setDate(0); // Set the date to the last day of the month

            const monthStartDate = new Date(monthEndDate);
            monthStartDate.setMonth(monthEndDate.getMonth() - 1); // Subtract 1 month to get the start date
            monthStartDate.setDate(1); // Set the date to the first day of the month

            // Check if the month's start date is within the provided range
            if (monthStartDate >= startDate) {
                previousMonths.push({ startDate: monthStartDate, endDate: monthEndDate });
            }

            currentMonthStartDate = monthStartDate; // Set the next month's start date
        }
    }

    // Calculate the data for each month
    const monthArray = [];
    previousMonths.forEach(month => {
        let totalStock = 0;
        data.forEach(element => {
            const chkDate = new Date(element.date);
            if (chkDate >= month.startDate && chkDate <= month.endDate) {
                totalStock += element.stock;
            }
        });
        monthArray.push(totalStock);
    });

    // Format the labels for each month
    const previousMonthsArray = previousMonths.map(month => {
        const formattedStartDate = formatDate(month.startDate);
        const formattedEndDate = formatDate(month.endDate);
        return `${formattedStartDate} - ${formattedEndDate}`;
    });

    // Reverse the arrays for proper display order
    periodData = monthArray.reverse();
    labels = previousMonthsArray.reverse();
}
// create weekly dates
// function createLastWeeksDates(data, startDate = 0, endDate = 0) {

//     // Initialize an array to store the previous 6 weeks
//     const previousWeeks = [];

//     if (startDate == 0 && endDate == 0) {
//         // Calculate the start date of the current week (Sunday)
//         const currentWeekStartDate = new Date();
//         currentWeekStartDate.setDate(currentWeekStartDate.getDate() - currentWeekStartDate.getDay());


//         // Calculate the start and end dates for each of the previous 6 weeks
//         for (let i = 0; i < 6; i++) {
//             startDate = new Date(currentWeekStartDate);
//             startDate.setDate(currentWeekStartDate.getDate() - 7 * i); // Subtract 7 days for each previous week
//             endDate = new Date(startDate);
//             endDate.setDate(startDate.getDate() + 6); // Add 6 days to get the end of the week
//             previousWeeks.push({ startDate, endDate });
//         }
//     }
//     else {
//         startDate = new Date(startDate);
//         endDate = new Date(endDate);
//         // startDate.setDate(startDate.getDate() - startDate.getDay());
//         startDate.setDate(startDate.getDate() - 7);
//         let currentWeekStartDate = endDate; // Initialize with the provided end date
//         currentWeekStartDate.setDate(currentWeekStartDate.getDate() + currentWeekStartDate.getDay());

//         // Calculate the difference in milliseconds
//         const timeDifference = currentWeekStartDate.getTime() - startDate.getTime();
//         // Convert milliseconds to weeks (1 week = 7 days)
//         const weeks = Math.floor(timeDifference / (1000 * 60 * 60 * 24 * 7));

//         for (let i = 0; i <= weeks; i++) {
//             const weekEndDate = new Date(currentWeekStartDate);
//             weekEndDate.setDate(currentWeekStartDate.getDate() - 1); // Subtract 1 day to get the week's end date
//             const weekStartDate = new Date(weekEndDate);
//             weekStartDate.setDate(weekEndDate.getDate() - 6); // Subtract 6 days to get the start date
//             // Check if the week's start date is within the provided range
//             if (weekStartDate >= startDate) {
//                 previousWeeks.push({ startDate: weekStartDate, endDate: weekEndDate });
//             }

//             currentWeekStartDate = weekStartDate; // Set the next week's start date
//         }


//     }

//     //check the weeks arroding to their hours
//     var workedHrs = 0;
//     var weekarray = [];
//     previousWeeks.forEach(week => {
//         data.forEach(element => {
//             chkDate = element['date'];
//             if (formatDateYMD(week.startDate) <= chkDate && formatDateYMD(week.endDate) >= chkDate) {
//                 workedHrs += element['hours'];
//             } else {
//             }
//         });
//         weekarray.push(workedHrs);
//         workedHrs = 0;
//     });
//     previousWeeks.forEach(function (element) {
//         element.startDate = formatDate(element.startDate);
//         element.endDate = formatDate(element.endDate);
//     });
//     const previousWeeksArray = [];
//     previousWeeks.forEach(function (element) {
//         previousWeeksArray.push(element.startDate + ' - ' + element.endDate);
//     });

//     periodData = weekarray.reverse();
//     labels = previousWeeksArray.reverse();
// }
createLastDaysDates(chartData);

function changePeriod(e) {
    console.log(e.value);
    switch (e.value) {
        case 'Daily':
            graphFormat = 'days';
            break;
        case 'Weekly':
            graphFormat = 'weeks';
            break;
        case 'Monthly':
            graphFormat = 'months';
            break;
        default:
            graphFormat = 'days';
            break;
    }
}
const data = {
    labels: labels,
    datasets: [{
        backgroundColor: '#1892C0',
        borderColor: 'rgb(255, 99, 132)',
        // data: periodData,
        data: periodData,
    }]
};
       
const config = {
    type: 'bar',
    data: data,
    options: {
        // responsive: true,
        // maintainAspectRatio: false,
        scales: {
            // y: {
            //     type: 'time',
            //     time: {
            //         unit: 'month'
            //     }
            // },
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Stocks'
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
                    return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel
                }
            }
        }
    }
};

const epoch_to_hh_mm_ss = epoch => {
    const hours = Math.floor(epoch / 3600);
    const minutes = Math.floor((epoch % 3600) / 60);
    const seconds = epoch % 60;

    const formattedHours = hours.toString().padStart(2, '0');
    const formattedMinutes = minutes.toString().padStart(2, '0');
    const formattedSeconds = seconds.toString().padStart(2, '0');

    return `${formattedHours}:${formattedMinutes}:${formattedSeconds}`;
};

var myChartJS = new Chart(
    document.getElementById('myChart'),
    config
);


// datatable

//function for change the graph it is comming from datatable search filters 
function changeGraph(table) {
    var filteredIndexes = table.rows({ search: 'applied' }).indexes();
    var filteredData = [];
    filteredIndexes.each(function (index) {
        var rowData = table.row(index).data();
        filteredData.push(rowData);
    });
    var colData = [];
    filteredData.forEach(element => {
        const dateTime = element[0].split(' '); // element[6] is date and time ex: 12-09-2023 7:50 PM
        const currentDate1 = new Date(dateTime[0]); // dateTime is only date ex: 12-09-2023
        var inputString = element[13];
        colData.push({ 'date': formatDateYMD(currentDate1), 'stock': inputString });
    });
    return colData;
}


$(document).ready(function () { 
    var table = $('#stockCoutntByStoreDatatable').DataTable({
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
            // table.lengthMenu= [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ];
            table.column(2).search('', true, false).draw(); // Clear previous search
            dropdown.empty();
            dropdown.append('<option value="" selected>--Select--</option>');
           
        }
        // Empty the dropdown to remove previous options
        var convertedToChartData = changeGraph(table);
        switch (graphFormat) {
            case 'days':
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
            case 'weeks':
                createLastWeeksDates(convertedToChartData , startDate, endDate);
                break;
            case 'months':
                createLastMonthsDates(convertedToChartData , startDate, endDate);
                break;
            default:
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
        }
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = periodData;
        myChartJS.update();

    });

    $('#location-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(2).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        // table.column(1).search(this.value).draw();
        var convertedToChartData = changeGraph(table);

        switch (graphFormat) {
            case 'days':
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
            case 'weeks':
                createLastWeeksDates(convertedToChartData , startDate, endDate);
                break;
            case 'months':
                createLastMonthsDates(convertedToChartData , startDate, endDate);
                break;
            default:
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
        }
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = periodData;
        myChartJS.update();
    });

    $('#category-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(3).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        var convertedToChartData = changeGraph(table);

        switch (graphFormat) {
            case 'days':
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
            case 'weeks':
                createLastWeeksDates(convertedToChartData , startDate, endDate);
                break;
            case 'months':
                createLastMonthsDates(convertedToChartData , startDate, endDate);
                break;
            default:
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
        }
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = periodData;
        myChartJS.update();
    });
    $('#merchandiser-search').on('change', function () {
        // const searchValue = this.value.trim();
        table.column(5).search(this.value ? `^${this.value}$` : '', true, false).draw();
        console.log(this.value);
        var convertedToChartData = changeGraph(table);

        switch (graphFormat) {
            case 'days':
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
            case 'weeks':
                createLastWeeksDates(convertedToChartData , startDate, endDate);
                break;
            case 'months':
                createLastMonthsDates(convertedToChartData , startDate, endDate);
                break;
            default:
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
        }
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = periodData;
        myChartJS.update();

    });

    $('#product-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(4).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        // console.log("search product", searchValue);
        var convertedToChartData = changeGraph(table);

        switch (graphFormat) {
            case 'days':
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
            case 'weeks':
                createLastWeeksDates(convertedToChartData , startDate, endDate);
                break;
            case 'months':
                createLastMonthsDates(convertedToChartData , startDate, endDate);
                break;
            default:
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
        }
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = periodData;
        myChartJS.update();
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
            var convertedToChartData = changeGraph(table);
            switch (graphFormat) {
                case 'days':
                    createLastDaysDates(convertedToChartData , startDate, endDate);
                    break;
                case 'weeks':
                    createLastWeeksDates(convertedToChartData , startDate, endDate);
                    break;
                case 'months':
                    createLastMonthsDates(convertedToChartData , startDate, endDate);
                    break;
                default:
                    createLastDaysDates(convertedToChartData , startDate, endDate);
                    break;
            }
            myChartJS.data.labels = labels;
            myChartJS.data.datasets[0].data = periodData;
            myChartJS.update();
        } else {
            console.log("The substring 'to' does not exist in the original string.");
        }

    });

    document.getElementById('clearDate').addEventListener('click', function (element) {
        table.column(0).search('', true, false).draw(); // Clear previous search
        document.getElementById('period-search').clear;
        endDate = 0;
        startDate = 0;
        // table.column(0).search('').draw();
        var convertedToChartData = changeGraph(table);
        switch (graphFormat) {
            case 'days':
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
            case 'weeks':
                createLastWeeksDates(convertedToChartData , startDate, endDate);
                break;
            case 'months':
                createLastMonthsDates(convertedToChartData , startDate, endDate);
                break;
            default:
                createLastDaysDates(convertedToChartData , startDate, endDate);
                break;
        }
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = periodData;
        myChartJS.update();
        document.getElementById('period-search').value = 'Date Range';

    });
});