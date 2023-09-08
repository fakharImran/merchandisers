@extends('manager.layout.app')

@section("top_links")
@vite(['resources/js/chart.js'])


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endsection

@section("bottom_links")
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    
<script>
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
            var tempData = table.column(0).search(this.value).draw();
            data = changeGraph(table);
            console.log(data);
        });
        $('#location-search').on('change', function() {
            table.column(1).search(this.value).draw();
            data = changeGraph(table);
            console.log(data);
        });
        $('#merchandiser-search').on('change', function() {
            table.column(9).search(this.value).draw();
            data = changeGraph(table);
            console.log(data);
        });
        $('#period-search').on('change', function() {
            console.log(this.value);
            
            if (this.value.includes('to')) {
                const parts = this.value.split('to');
                console.log('parts: ', parts);
                

                var start = parts[0].trim(); // Remove leading/trailing spaces
                startDate = start.replace(/^\s+/, ''); // Remove the first space
                startDate=new Date(startDate);
                var startDate=formatDateYMD(startDate);
                console.log("start date", startDate);


                var end = parts[1].trim(); // Remove leading/trailing spaces
                endDate = end.replace(/^\s+/, ''); // Remove the first space
                endDate=new Date(endDate);
                var endDate=formatDateYMD(endDate);
                console.log("end date", endDate);


                table.column(6).search('', true, false).draw(); // Clear previous search

                var searchTerms = []; // Initialize an array to store search terms

                table.column(6).data().each(function (value, index) {
                    var rowDate = new Date(value);
                    rowDate = formatDateYMD(rowDate);
                    console.log(rowDate);

                    if (startDate <= rowDate && rowDate <= endDate) {
                        searchTerms.push(value); // Add valid dates to the array
                    }
                });

                table.column(6).search(searchTerms.join('|'), true, false, true).draw(); // Join and apply search terms
               
            } else {
                console.log("The substring 'to' does not exist in the original string.");
            }
          
        });
    });
</script>
@endsection

@section('content')
<style>
    td, th{
        border: 2px solid #ccc;
        /* padding: 10px; */
    }
    th{
        background-color: #f7f7f7;
        color: #233D79;
    }
    /* Define a CSS class to apply the background image */
</style>
<div class="container">

    <div  class="row d-flex align-items-center col-actions" style="   max-width: 99%; margin: 1px auto;">
        <div class="col-md-3 col-3 p-4">
            
            <div class="form-group" >
                <label for="period-search" class="form-label filter -search">Period</label>
                <input type="text" id="period-search" value="Date Range" class=" form-control filter">
            </div>
        </div>
        <div class="col-md-3 col-3 p-4">
            <div class="form-group">
                <label for="store-search" class="form-label filter store">Select Store</label>
                <select name="store-search" class=" filter form-select" id="store-search">
                    <option value="" selected>--Select-- </option>
                    @foreach($stores as $store)
                    <option value="{{$store['name_of_store']}}">{{$store['name_of_store']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3 col-3 p-4">
            <div class="form-group">
                <label for="location-search" class="form-label filter location">Select Location</label>
                <select name="location-search" class=" filter form-select"  id="location-search">
                    <option value="" selected>--Select-- </option>
                    @foreach($stores as $store)
                    <option value="{{$store['location']}}">{{$store['location']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3 col-3 p-4">
            <div class="form-group">
                <label for="merchandiser-search" class="form-label filter merchandiser">Select Merchandiser</label>
                <select name="merchandiser-search" class=" filter form-select"  id="merchandiser-search">
                    <option value="" selected>--Select-- </option>
                    @foreach($merchandiserArray as $merchandiser)
                    <option value="{{$merchandiser['name']}}">{{$merchandiser['name']}}</option>
                    @endforeach
                </select>   
            </div>
        </div>
    </div>
    <div style="width: 800px; margin: auto;">
        <canvas id="myChart"></canvas>
    </div>
    {{-- <div class="row">
        <div class="col-12">
            <button
                class="btn btn-primary btn-sm edit-address"
                style="float: right"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#pendingTimeSheet"
                >
                Pending Time Sheets
            </button>
        </div>
    </div> --}}
    <div class="row pt-5" style="     margin: 1px auto; font-size: 12px;">
        <div class="col-12">

            <div class="table-responsive" >
                    {{-- table-responsive --}}
                    {{-- nowrap --}}
                <table id="mechandiserDatatable" class="table table-sm  datatable table-hover  " style="border: 1px solid #ccc; min-width: 1580px; ">
                    <thead>
                        <tr>
                            <th class="thclass" scope="col">Name of Store</th>
                            <th class="thclass" scope="col">Location</th>
                            <th class="thclass" scope="col">Check-in Time</th>
                            <th class="thclass" scope="col">Check-in GPS Location</th>
                            <th class="thclass" scope="col">Break Time</th>
                            <th class="thclass" scope="col">Lunch Time</th>
                            <th class="thclass" scope="col">Check-out Time</th>
                            <th class="thclass" scope="col">Check-out GPS Location</th>
                            <th class="thclass" scope="col">Hours Worked</th>
                            <th class="thclass" scope="col">Merchandiser</th>
                            <th class="thclass" scope="col">Store Manager</th>
                            <th class="thclass" scope="col">Signature</th>
                            <th class="thclass" scope="col">Time of Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                            $totalHourworked=0;
                            $chartDateArray = array();
                            $chartHoursArray = array();
                    @endphp
                        @foreach ($merchandiserArray as $merchandiser)
                            {{-- {{dd($merchandiser['time_sheets'])}} --}}
                            @foreach ($merchandiser['time_sheets'] as $merchandiser_time_sheet)
                                @php
                                    $manager = $merchandiser_time_sheet->manager($merchandiser_time_sheet->store_manager_id);

                                    $checkin_date_time = 'N/A';
                                    $checkin_location = 'N/A';
                                    $lunch_date_time = 'N/A';
                                    $break_date_time = 'N/A';
                                    $checkout_date_time = 'N/A';
                                    $checkout_location = 'N/A';
                                @endphp
                                @foreach ($merchandiser_time_sheet->timeSheetRecords as $time_sheet_record)
                                
                                    @switch($time_sheet_record->status)
                                        @case('check-in')
                                            @php
                                                $checkin_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                                $checkin_location = $time_sheet_record->gps_location;
                                            @endphp
                                            @break
                                        @case('lunch')
                                            @php
                                                $lunch_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            @endphp
                                            @break
                                        @case('break')
                                            @php
                                                $break_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            @endphp
                                            @break
                                        @case('check-out')
                                            @php
                                                $checkout_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                                $checkout_location = $time_sheet_record->gps_location;
                                            @endphp
                                            @break
                                        @default
                                    @endswitch
                                @endforeach
                                @php
                                    
                                    $checkinDateTime = new DateTime($checkin_date_time); // Replace with your actual check-in date and time
                                    // Check-out date and time
                                    $timestamp = strtotime($checkin_date_time);
                                    $formatedCheckinDateTime = date("Y-m-d h:i A", $timestamp);
                                    $checkoutDateTime = new DateTime($checkout_date_time); // Replace with your actual check-out date and time
                                    // Calculate the difference
                                    $timestamp = strtotime($checkout_date_time);
                                    $formatedCheckoutDateTime = date("Y-m-d h:i A", $timestamp);

                                    $timestamp = strtotime($break_date_time);
                                    $formatedBreakTime = date("Y-m-d h:i A", $timestamp);

                                    $timestamp = strtotime($lunch_date_time);
                                    $formatedLunchTime = date("Y-m-d h:i A", $timestamp);

                                    $interval = $checkinDateTime->diff($checkoutDateTime);
                                    
                                    // Calculate the total hours
                                    $hoursWorked = $interval->days * 24 + $interval->h ;
                                    $minutesWorked = $interval->i ;
                                    $totalHours=  $interval->days * 24 + $interval->h + $interval->i/60;
                                    $totalHourworked+= $totalHours;

                                    // dd(
                                    //    "chekin", $checkin_date_time, $formatedCheckinDateTime, "checkout",   $checkout_date_time, $formatedCheckoutDateTime, "break",
                                    // $break_date_time, $formatedBreakTime, 'lunch' ,$lunch_date_time, $formatedLunchTime , $interval, $hoursWorked, $minutesWorked);
                                @endphp         
                                <tr>
                                    <td  class="tdclass">{{$merchandiser_time_sheet->store->name_of_store}}</td>
                                    <td  class="tdclass">{{$merchandiser_time_sheet->store->location}}</td>
                                    <td  class="tdclass">
                                        @if($checkin_date_time!='N/A')
                                        {{$formatedCheckinDateTime}}
                                        @endif
                                        </td>
                                    <td  class="tdclass">{{$checkin_location}}</td>
                                    <td  class="tdclass">
                                        @if($break_date_time!='N/A')
                                        {{$formatedBreakTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($lunch_date_time!='N/A')
                                        {{$formatedLunchTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($checkout_date_time!='N/A')
                                           {{$formatedCheckoutDateTime}}
                                        @endif
                                        
                                    </td>
                                    <td  class="tdclass">{{$checkout_location}}</td>
                                    <td  class="tdclass">{{$hoursWorked}} hrs, {{$minutesWorked}} mins</td>
                                    {{-- <td  class="tdclass">{{}}</td> --}}
                                    <td  class="tdclass">{{$merchandiser['name']}}</td>
                                    <td  class="tdclass">{{$manager->name}}</td>
                                    <td  class="tdclass">
                                        @php
                                            $imagePath = public_path('storage/' . $merchandiser_time_sheet->signature);
                                            if (file_exists($imagePath)) 
                                            {
                                                echo "<img width='100' src='" . asset('storage/' . $merchandiser_time_sheet->signature) . "' />";
                                            } 
                                            else 
                                            {
                                                echo "N/A";
                                            }
                                        @endphp     
                                    </td>
                                    <td  class="tdclass">
                                        @php
                                            $imagePath = public_path('storage/' . $merchandiser_time_sheet->signature);
                                        
                                            if (file_exists($imagePath)) 
                                            {
                                                echo "$formatedCheckoutDateTime";
                                            } 
                                            else 
                                            {
                                                echo "N/A";
                                            }
                                        @endphp   
                                    </td>
                                </tr>
                                @php
                                    array_push($chartHoursArray ,['date'=>$formatedCheckoutDateTime ]);
                                @endphp
                            @endforeach
                        @endforeach
                        {{-- {{dd($chartHoursArray)}} --}}
                    </tbody>
                </table>
            </div>
        </div>  
    </div>
</div>
<script>
    var labels = [];
    var chartData =  {{ Js::from($chartHoursArray) }};

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



        // console.log(weekarray, 'working hours array weekly');
        // console.log('Current Week Start Date:', currentWeekStartDate);
        // console.log('Previous 6 Weeks:', previousWeeks);

        previousWeeks.forEach(function(element) {
        //   console.log(element.startDate);
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
    convertingData(chartData);

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#period-search", {
            dateFormat: "M d, Y",
            altFormat: "F j, Y",
            mode: "range",
            });
    });
</script>
 


@include('manager/merchandiserTimeSheet/pendingTimeSheets')

@endsection
