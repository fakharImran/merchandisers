
function formatDate(date) 
{
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
// console.log('----------------------------------------');
// create weekly dates
function convertingData(data) {
        
    const currentDate = new Date(); // Get the current date

    // Calculate the start date of the current week (Sunday)
    const currentWeekStartDate = new Date(currentDate);
    currentWeekStartDate.setDate(currentDate.getDate() - currentDate.getDay());

    // Initialize an array to store the previous 6 weeks
    const previousWeeks = [];
    // Calculate the start and end dates for each of the previous 6 weeks
    for (let i = 0; i < 6; i++) {
    const startDate = new Date(currentWeekStartDate);
    startDate.setDate(currentWeekStartDate.getDate() - 7 * i); // Subtract 7 days for each previous week
    const endDate = new Date(startDate);
    endDate.setDate(startDate.getDate() + 6); // Add 6 days to get the end of the week
    previousWeeks.push({ startDate, endDate });
    }
    // console.log('**************************************************');
    //check the weeks arroding to their hours
    var workedHrs=0;
    var weekarray=[];
    previousWeeks.forEach(week =>{
        data.forEach(element => {
            chkDate=element['date'];
            if(formatDateYMD(week.startDate)<=chkDate && formatDateYMD(week.endDate)>chkDate)
            {
                workedHrs+= element['hours'] ;
                // console.log("date for check is "+ chkDate+ ", now hours " +workedHrs);
            }else{
                // console.log("date for check is "+ chkDate+ " <br> start date "+ formatDateYMD(week.startDate)+ "<br> end date "+ formatDateYMD(week.endDate));
            }
        });

    weekarray.push(workedHrs);
    workedHrs =0 ;
    });
    // console.log('**************************************************');

    previousWeeks.forEach(function(element) {
    element.startDate = formatDate(element.startDate);
    element.endDate = formatDate(element.endDate);
    });
    // console.log('Previous 6 Weeks:', previousWeeks);
    const previousWeeksArray = [];
    previousWeeks.forEach(function(element) {
    //   console.log(element.startDate);
    previousWeeksArray.push(element.startDate + ' - ' + element.endDate);
    });
    console.log(previousWeeksArray);

    hoursWorked = weekarray.reverse();
    labels = previousWeeksArray.reverse();
}
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



// datatable

    //function for change the graph it is comming from datatable search filters 
    function changeGraph(table){
      var filteredIndexes = table.rows({ search: 'applied' }).indexes();
      var filteredData = [];
      filteredIndexes.each(function(index) {
          var rowData = table.row(index).data();
          filteredData.push(rowData);
      });
      var colData = [];
      filteredData.forEach(element => {
          const currentDate1 = new Date(element[6]);
          var inputString = element[8];
          var regex = /(\d+).*?(\d+)/; // Regular expression to match the first integers before and after the comma
          var match = inputString.match(regex);
          if (match) {
              var beforeComma = match[1]; // The first set of integers before the comma
              var afterComma = match[2]; // The first set of integers after the comma
              var Hours = (beforeComma*1) + (afterComma/60);
          } else {
              console.log('No match found.');
          }
          colData.push({'date':formatDateYMD(currentDate1),'hours':Hours});
      });
      return colData;
  }


  $(document).ready(function() {
      var table = $('#mechandiserDatatable').DataTable({
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
      $('#store-search').on('change', function() {

          // Perform the search on the first column of the DataTable
          table.column(0).search(this.value).draw();
          var convertedToChartData = changeGraph(table);
          console.log(convertedToChartData);
          convertingData(convertedToChartData);
          myChartJS.data.labels = labels;
          myChartJS.data.datasets[0].data = hoursWorked;
          myChartJS.update(); 

      });
      $('#location-search').on('change', function() {
          table.column(1).search(this.value).draw();
          var convertedToChartData = changeGraph(table);
          console.log(convertedToChartData);
          convertingData(convertedToChartData);
          myChartJS.data.labels = labels;
          myChartJS.data.datasets[0].data = hoursWorked;
          myChartJS.update(); 


      });
      $('#merchandiser-search').on('change', function() {
          table.column(9).search(this.value).draw();
          var convertedToChartData = changeGraph(table);
          console.log(convertedToChartData);
          convertingData(convertedToChartData);
          myChartJS.data.labels = labels;
          myChartJS.data.datasets[0].data = hoursWorked;
          myChartJS.update(); 

      });
      $('#period-search').on('change', function() {
          // console.log(this.value);
          
          if (this.value.includes('to')) {
              const parts = this.value.split('to');
              // console.log('parts: ', parts);

              var start = parts[0].trim(); // Remove leading/trailing spaces
              startDate = start.replace(/^\s+/, ''); // Remove the first space
              startDate=new Date(startDate);
              var startDate=formatDateYMD(startDate);
              // console.log("start date", startDate);

              var end = parts[1].trim(); // Remove leading/trailing spaces
              endDate = end.replace(/^\s+/, ''); // Remove the first space
              endDate=new Date(endDate);
              var endDate=formatDateYMD(endDate);
              // console.log("end date", endDate);

              table.column(6).search('', true, false).draw(); // Clear previous search

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
              // console.log(dateList.join('|'), 'umerrrr');
              table.column(6).search(dateList.join('|'), true, false, true).draw(); // Join and apply search terms
              var convertedToChartData = changeGraph(table);
              console.log(convertedToChartData);
              convertingData(convertedToChartData);
              myChartJS.data.labels = labels;
              myChartJS.data.datasets[0].data = hoursWorked;
              myChartJS.update(); 
          } else {
              console.log("The substring 'to' does not exist in the original string.");
          }
        
      });
  });