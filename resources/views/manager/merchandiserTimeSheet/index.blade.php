@extends('manager.layout.app')

@section("top_links")
@vite(['resources/js/chart.js'])

 <!-- Include jQuery UI for date picker -->
 {{-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> --}}
 {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


{{-- 
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" rel="stylesheet"> --}}
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
    
$(document).ready(function() {
    var table = $('#datatable').DataTable({
    // Add your custom options here
    scrollX: true, // scroll horizontally
    paging: true, // Enable pagination
    searching: true, // Enable search bar
    ordering: true, // Enable column sorting
    lengthChange: true, // Show a dropdown for changing the number of records shown per page
    pageLength: 10, // Set the default number of records shown per page to 10
    dom: 'lBfrtip', // Define the layout of DataTable elements (optional)
    buttons: ['copy', 'excel', 'pdf', 'print'], // Add some custom buttons (optional)
    "pagingType": "full_numbers"

    
  });
  // Custom search input for 'Name' column
  $('#store-search').on('change', function() {

    table.column(0).search(this.value).draw();
    // changeGraph(this.value)

  });
  $('#location-search').on('change', function() {
    table.column(1).search(this.value).draw();
  });
  $('#merchandiser-search').on('change', function() {
    table.column(9).search(this.value).draw();
  });
});
</script>
@endsection

@section('content')
<style>
    td, th{
        border: 2px solid #ccc;
        padding: 10px;
    }
    th{
        background-color: #f7f7f7;
        color: #233D79;
    }


    /* Define a CSS class to apply the background image */


</style>

<div  class="row d-flex align-items-center col-actions" style="   max-width: 99%; margin: 1px auto;">
    <div class="col-md-3 col-3 p-4">
        
        <div class="form-group" >
            <label for="start_date" class="form-label filter period">Period</label>

            {{-- <form id="date-form"> --}}
                <input type="date" id="start_date" name="start_date" class="form-control filter" required>
            
                {{-- <input type="date" id="end_date" name="end_date" required> --}}
            
                {{-- <button type="button" id="submit-button">Submit</button> --}}
            {{-- </form> --}}
            
            {{-- <div id="result-container"></div> --}}
            
        </div>
    </div>


    <div class="col-md-3 col-3 p-4">
        <div class="form-group">
            <label for="store-search" class="form-label filter store">Select Store</label>
            <select name="store-search" class="form-control filter" id="store-search">
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
            <select name="location-search" class="form-control filter"  id="location-search">
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
            <select name="merchandiser-search" class="form-control filter"  id="merchandiser-search">
                <option value="" selected>--Select-- </option>
                @foreach($merchandiserArray as $merchandiser)
                <option value="{{$merchandiser['name']}}">{{$merchandiser['name']}}</option>
                @endforeach
            </select>   
        </div>
    </div>
</div>
<div style="width: 1000px; margin: auto;">
    <canvas id="myChart"></canvas>
</div>
            {{-- {{dd($merchandiserArray)}} --}}
<div class="row">
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
</div>
    
<div class="table-responsive mt-2" style="overflow: auto;">
    {{-- <table id="customDataTable" class="table  datatable table-bordered table-hover table-responsive nowrap" style="width:100%"> --}}
        {{-- table-responsive --}}
    <table id="datatable" class="table table-sm  datatable table-bordered table-hover  nowrap" style="border: 1px solid #ccc; min-width: 1580px; ">
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
                        $checkoutDateTime = new DateTime($checkout_date_time); // Replace with your actual check-out date and time

                        // Calculate the difference
                        $interval = $checkinDateTime->diff($checkoutDateTime);

                        // Calculate the total hours
                        $hoursWorked = $interval->days * 24 + $interval->h ;
                        $minutesWorked = $interval->i ;

                        $totalHours=  $interval->days * 24 + $interval->h + $interval->i/60;
                        $totalHourworked+= $totalHours;

                        
                        // $totalHrsWorked= $hoursWorked.":"$minutesWorked;
                        // dd(" Duration: $hoursWorked hours and minutes: $minutesWorked");

                    @endphp         
                    <tr>

                        <td  class="tdclass">{{$merchandiser_time_sheet->store->name_of_store}}</td>
                        <td  class="tdclass">{{$merchandiser_time_sheet->store->location}}</td>
                        <td  class="tdclass">
                            @if($checkin_date_time!='N/A')
                            {{Carbon\carbon::parse(strval($checkin_date_time))->format('Y-m-d h:m A')}}
                            @endif
                            </td>
                        <td  class="tdclass">{{$checkin_location}}</td>
                        <td  class="tdclass">
                            @if($break_date_time!='N/A')
                            {{Carbon\carbon::parse(strval($break_date_time))->format('Y-m-d h:m A')}}
                            @endif
                        </td>
                        <td  class="tdclass">
                            @if($lunch_date_time!='N/A')
                            {{Carbon\carbon::parse(strval($lunch_date_time))->format('Y-m-d h:m A')}}
                            @endif
                        </td>
                        <td  class="tdclass">
                            @if($checkout_date_time!='N/A')
                            @php
                                $checkout_time_converted= Carbon\carbon::parse(strval($checkout_date_time))->format('Y-m-d h:m A')
                            @endphp
                            {{$checkout_time_converted}}
                            @endif
                        </td>
                        <td  class="tdclass">{{$checkout_location}}</td>
                        <td  class="tdclass">{{$hoursWorked}} hrs, {{$minutesWorked}} mins</td>
                        {{-- <td  class="tdclass">{{}}</td> --}}
                        <td  class="tdclass">{{$merchandiser['name']}}</td>
                        <td  class="tdclass">{{$manager->name}}</td>
                        <td  class="tdclass">
                            <?php
                                $imagePath = public_path('storage/' . $merchandiser_time_sheet->signature);
                            
                                if (file_exists($imagePath)) 
                                {
                                    echo "<img width='100' src='" . asset('storage/' . $merchandiser_time_sheet->signature) . "' />";
                                } 
                                else 
                                {
                                    echo "N/A";
                                }
                            ?>    
                        </td>
                        <td  class="tdclass">
                            <?php
                                $imagePath = public_path('storage/' . $merchandiser_time_sheet->signature);
                            
                                if (file_exists($imagePath)) 
                                {
                                    echo "$checkout_time_converted";
                                } 
                                else 
                                {
                                    echo "N/A";
                                }
                            ?>    
                            
                        </td>
                    </tr>

                    {{-- {{dd('umer')}} --}}
                    @php
                        // array_push($chartDateArray ,Carbon\carbon::parse(strval($checkout_time_converted)));
                        array_push($chartHoursArray ,['date'=>Carbon\carbon::parse(strval($checkout_time_converted))->format('Y-m-d'), 'hours'=>$totalHours] );
                    @endphp
                @endforeach
                
            @endforeach
            {{-- {{dd($chartDateArray, $chartHoursArray)}} --}}
        </tbody>
    </table>
</div>
<script>
     var labels = [];
    var data =  {{ Js::from($chartHoursArray) }};

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
</script>


<script>
    // $(document).ready(function () {
    //     $('#dataTable').DataTable({
    //         // Add any DataTable options you need here
    //     });
    // });
    
</script>
<script>

    //     flatpickr('#startDate', {
    //   enableTime: true,
    //   allowInput: true,
    //   dateFormat: "m/d/Y h:iK",
    //   "plugins": [new rangePlugin({ input: "#endDate"})]
    // });
</script>
@include('manager/merchandiserTimeSheet/pendingTimeSheets')

@endsection
