@extends('manager.layout.app')

@section("top_links")

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


@endsection

@section("bottom_links")

{{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> --}}
{{-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> --}}
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    
<script>

</script>
@endsection

@section('content')
<style>

    /* Add appropriate styles for your layout */
.date-input-container {
    position: relative;
}

.clear-icon {
    position: absolute;
    right: -9px;
    top: 56%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #ccc;
}


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
                <label for="period-search" class="form-label filter period filter-search">Period</label>
                <input type="text" id="period-search" value="Date Range" class=" form-control filter">
                <i class="fas fa-times-circle clear-icon" id="clearDate"></i>

            </div>
        </div>
        <div class="col-md-3 col-3 p-4">
            <div class="form-group">
                <label for="store-search" class="form-label filter store">Select Store</label>
                <select name="store-search" class="filter form-select" id="store-search">
                    <option value="" selected>--Select--</option>
                    @if($stores!=null)
                        @foreach ($stores->unique('name_of_store')->sort() as $store)
                            <option value="{{$store['name_of_store']}}">{{$store['name_of_store']}}</option>
                        @endforeach
                    @endif
                </select>
                


            </div>
        </div>
        {{-- for setting the filter dropdown unique and sorted value --}}
        @php
            $locationArr=array();
            $storesArr=array();
            
        @endphp
        @if($stores!=null)
        @foreach ($stores as $store)
                @php
                    $tempLocation=array();
                @endphp

                @foreach($store->locations->unique('location')->sort() as $location)
                    @php
                        array_push($locationArr, $location['location']); 
                        array_push($tempLocation, $location['location']) ;                             
                    @endphp    
                @endforeach
                @php
                    $uniqueLocation = array_unique($tempLocation);
                    sort($uniqueLocation);
                    array_push($storesArr, [$store->name_of_store,$uniqueLocation ]);

                @endphp
            @endforeach
        @endif
        @php
            $locationArr = array_unique($locationArr);
            sort($locationArr);
        @endphp
        {{-- end sorting and unique location value in filter search --}}
        <div class="col-md-3 col-3 p-4">
            <div class="form-group">
                <label for="location-search" class="form-label filter location">Select Location</label>
                <select name="location-search" class="filter form-select" id="location-search">
                    <option value="" selected>--Select--</option>
                    @foreach ($locationArr as $location)
                        <option value="{{$location}}">{{$location}}</option>
                    @endforeach
                </select>                
            </div>

            {{-- <script>
            function updateLocations() {
                const selectedStore = document.getElementById('store-search').value;
                const locationDropdown = document.getElementById('location-search');
                const allLocations = {!! json_encode($stores) !!};
                const uniqueLocations = [];

                // Clear existing options
                locationDropdown.innerHTML = '<option value="" selected>--Select--</option>';

                if (selectedStore === '') {
                    // If no store is selected, show all locations
                    for (const store of allLocations) {
                        for (const location of store.locations) {
                            if (!uniqueLocations.includes(location.location)) {
                                uniqueLocations.push(location.location);
                                const option = document.createElement('option');
                                option.value = location.location;
                                option.textContent = location.location;
                                locationDropdown.appendChild(option);
                            }
                        }
                    }
                } else {
                    // If a store is selected, filter locations based on the selected store
                    for (const store of allLocations) {
                        if (store.name_of_store === selectedStore) {
                            for (const location of store.locations) {
                                if (!uniqueLocations.includes(location.location)) {
                                    uniqueLocations.push(location.location);
                                    const option = document.createElement('option');
                                    option.value = location.location;
                                    option.textContent = location.location;
                                    locationDropdown.appendChild(option);
                                }
                            }
                        }
                    }
                }
            }

            </script> --}}
        </div>
        <div class="col-md-3 col-3 p-3">
            <div class="form-group">
                <label for="merchandiser-search" class="form-label filter merchandiser">Select Merchandiser</label>
                <select name="merchandiser-search" class=" filter form-select"  id="merchandiser-search">
                    <option value="" selected>--Select-- </option>
                    @php
                        $uniqueMerchandisers = array_unique(array_column($userArr, 'name'));
                    @endphp
                    @foreach($uniqueMerchandisers as $merchandiser)
                         <option value="{{$merchandiser}}">{{$merchandiser}}</option>
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
            <button id="downloadButton" class="btn btn-dark m-3 float-end">Download Time Sheet</button>
        </div>
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
                            <th class="thclass" scope="col">Start Break Time</th>
                            <th class="thclass" scope="col">End Break Time</th>
                            <th class="thclass" scope="col">Start Lunch Time</th>
                            <th class="thclass" scope="col">End Lunch Time</th>
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
                                    $manager = $merchandiser_time_sheet->store_manager_name;
                                    // dd($manager);
                                    $checkin_date_time = null;
                                    $checkin_location = null;
                                    $start_lunch_date_time = null;
                                    $end_lunch_date_time = null;
                                    $start_break_date_time = null;
                                    $end_break_date_time = null;
                                    $checkout_date_time = null;
                                    $checkout_location = null;
                                @endphp
                                @foreach ($merchandiser_time_sheet->timeSheetRecords as $time_sheet_record)
                                
                                    @switch($time_sheet_record->status)
                                        @case('check-in')
                                            @php
                                                $checkin_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                                $checkin_location = $time_sheet_record->gps_location;
                                            @endphp
                                            @break
                                        @case('start-lunch-time')
                                            @php
                                                $start_lunch_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            @endphp
                                            @break
                                        @case('end-lunch-time')
                                            @php
                                                $end_lunch_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            @endphp
                                            @break
                                        @case('start-break-time')
                                            @php
                                                $start_break_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            @endphp
                                            @break
                                        @case('end-break-time')
                                            @php
                                                $end_break_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
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
                                  
                                //   echo ($checkin_date_time." ". $start_lunch_date_time." ". $end_lunch_date_time." ". $start_break_date_time." ". $end_break_date_time." ". $checkout_date_time." ". "<br>");
                                    
                                  $checkinDateTime = new DateTime($checkin_date_time); // Replace with your actual check-in date and time
                                    // Check-out date and time
                                    $timestamp = strtotime($checkin_date_time);
                                    $formatedCheckinDateTime = date("Y-m-d h:i A", $timestamp);

                                    if($start_break_date_time!=null && $end_break_date_time!=null )
                                    {
                                        $startBreakDateTime = new DateTime($start_break_date_time);
                                        $endBreakDateTime = new DateTime($end_break_date_time);
                                     
                                    //getting break time interval
                                    $breakTimeInterval=$startBreakDateTime->diff($endBreakDateTime);
                                    $breakSeconds = $breakTimeInterval->s + $breakTimeInterval->i * 60 + $breakTimeInterval->h * 3600 + $breakTimeInterval->d * 86400;


                                    $timestamp = strtotime($start_break_date_time);
                                    $formatedStartBreakDateTime = date("Y-m-d h:i A", $timestamp);

                                    $timestamp = strtotime($end_break_date_time);
                                    $formatedEndBreakDateTime = date("Y-m-d h:i A", $timestamp);


                                    }
                                    else {
                                        // $formatedStartBreakDateTime= null;
                                        // $formatedEndBreakDateTime=null;
                                        $breakSeconds=0;
                                        $breakTimeInterval=0;
                                        # code...
                                    }

                                    if($start_lunch_date_time!=null && $end_lunch_date_time!=null )
                                    {
                                        $startLunchDateTime = new DateTime($start_lunch_date_time);
                                    $endLunchDateTime = new DateTime($end_lunch_date_time);

                                    $LunchTimeInterval=$startLunchDateTime->diff($endLunchDateTime);
                                    
                                    
                                    $lunchSeconds = $LunchTimeInterval->s + $LunchTimeInterval->i * 60 + $LunchTimeInterval->h * 3600 + $LunchTimeInterval->d * 86400;
                                        

                                    $timestamp = strtotime($start_lunch_date_time);
                                    $formatedStartLunchDateTime = date("Y-m-d h:i A", $timestamp);

                                    $timestamp = strtotime($end_lunch_date_time);
                                    $formatedEndLunchDateTime = date("Y-m-d h:i A", $timestamp);

                                    }
                                    else {
                                        // $startBreakDateTime= null;
                                        // $endBreakDateTime= null;
                                        $LunchTimeInterval=0;
                                        $lunchSeconds=0;
                                        // $formatedStartLunchDateTime= null;
                                        // $formatedEndLunchDateTime= null;
                                        # code...
                                    }
                                    
                                   
                                    // dd ($breakTimeInterval, $LunchTimeInterval);

                                    // Add the seconds together
                                    $totalBreakLunchSeconds = $breakSeconds + $lunchSeconds;

                                    $checkoutDateTime = new DateTime($checkout_date_time); // Replace with your actual check-out date and time

                                    // Calculate the difference
                                    $timestamp = strtotime($checkout_date_time);
                                    $formatedCheckoutDateTime = date("Y-m-d h:i A", $timestamp);

                                    //getting difference between checkin and checkout;
                                    $interval = $checkinDateTime->diff($checkoutDateTime);
                                        // $interval->sub($breakTimeInterval);

                                    //here calculating total hours worked after subtracting break and lunch

                                    $intervalSeconds = $interval->s + $interval->i * 60 + $interval->h * 3600 + $interval->d * 86400;
                                    $intervalAfterBreakLunch= $intervalSeconds-$totalBreakLunchSeconds;
                                    $resultIntervalAfterBreakLunch = new DateInterval('PT' . $intervalAfterBreakLunch . 'S');
                                    // dd($intervalAfterBreakLunch);
                                    $tempTotalMinutes=  $intervalAfterBreakLunch % 3600;
                                    // dd($tempTotalMinutes);
                                    if($resultIntervalAfterBreakLunch->days==false)
                                    {
                                        $daysWorked=0;
                                    }
                                    else {
                                        $daysWorked=$resultIntervalAfterBreakLunch->days *24;
                                    }
                                    #
                                    // Calculate hours and minutes
                                    $tempHours = floor($intervalAfterBreakLunch / 3600); // 3600 seconds in an hour
                                    $tempRemainingSeconds = $intervalAfterBreakLunch % 3600;
                                    $tempMinutes = floor($tempRemainingSeconds / 60); 

                                    // dd($interval, $checkinDateTime, $checkoutDateTime);
                                    // Calculate the total hours
                                    $hoursWorked = $daysWorked+ $tempHours;
                                    $minutesWorked = $tempMinutes;
                                    // $totalHours=  $interval->days * 24 + $interval->h + $interval->i/60;
                                    // dd($hoursWorked, $minutesWorked);
                                    $totalHourworked+= $hoursWorked;
                                    $hoursWorked = [$hoursWorked + ($minutesWorked / 60)];
                                    $timeFormatted = $daysWorked+ $tempHours . ' hours ' . $minutesWorked . ' minutes';
                                    // dd($hoursWorked);
                                    // dd(
                                    //     $checkin_date_time,  $checkout_date_time,
                                    // $formatedCheckinDateTime, $formatedCheckoutDateTime , $interval, $hoursWorked, $minutesWorked);
                                @endphp
                          
                                <tr>
                                    <td  class="tdclass">{{$merchandiser_time_sheet->store($merchandiser_time_sheet->store_id)->name_of_store}}</td>
                                    <td  class="tdclass">{{($merchandiser_time_sheet->store_location($merchandiser_time_sheet->store_location_id)->location)??null}}</td>
                                    <td  class="tdclass">
                                        @if($checkin_date_time!=null)
                                        {{$formatedCheckinDateTime}}
                                        @endif
                                        </td>
                                    <td  class="tdclass">{{$checkin_location}}</td>
                                    <td  class="tdclass">
                                        @if($start_break_date_time!=null)
                                        {{$formatedStartBreakDateTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($end_break_date_time!=null)
                                        {{$formatedEndBreakDateTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($start_lunch_date_time!=null)
                                        {{$formatedStartLunchDateTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($end_lunch_date_time!=null)
                                        {{$formatedEndLunchDateTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($checkout_date_time!=null)
                                        @php
                                            $checkout_time_converted= $formatedCheckoutDateTime
                                        @endphp
                                        {{$checkout_time_converted}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">{{$checkout_location}}</td>
                                    <td  class="tdclass">{{$tempHours}} hrs, {{$tempMinutes}} mins</td>
                                    {{-- <td  class="tdclass">{{}}</td> --}}
                                    <td  class="tdclass">{{$merchandiser['name']}}</td>
                                    <td  class="tdclass">{{$manager}}</td>
                                    <td  class="tdclass">
                                        @php
                                        if($merchandiser_time_sheet->signature!=null)
                                        {
                                            $imagePath = public_path('storage/' . $merchandiser_time_sheet->signature);
                                            if (file_exists($imagePath)) 
                                            {
                                                echo "<img width='100' src='" . asset('storage/' . $merchandiser_time_sheet->signature) . "' />";
                                            } 
                                            else 
                                            {
                                                echo "N/A";
                                            }
                                        }
                                        else {
                                            echo "N/A";
                                        }
                                            
                                        @endphp     
                                    </td>
                                    <td  class="tdclass">
                                        @if($merchandiser_time_sheet->signature!=null)
                                           {{$checkout_time_converted}} 
                                        @else
                                            {{"N/A"}}
                                        @endif
                                        </td>
                                </tr>
                                @php
                                    array_push($chartHoursArray ,['date'=>Carbon\carbon::parse(strval($checkout_time_converted))->format('Y-m-d'), 'hours'=>$intervalAfterBreakLunch] );
                                @endphp
                            @endforeach
                            @foreach ($merchandiser['pending_time_sheets'] as $merchandiser_time_sheet)
                              
                            @php
                                $manager = $merchandiser_time_sheet->store_manager_name;
                                // dd($manager);
                                $checkin_date_time = null;
                                $checkin_location = null;
                                $start_lunch_date_time = null;
                                $end_lunch_date_time = null;
                                $start_break_date_time = null;
                                $end_break_date_time = null;
                                $checkout_date_time = null;
                                $checkout_location = null;
                            @endphp
                            @foreach ($merchandiser_time_sheet->timeSheetRecords as $time_sheet_record)
                            
                                @switch($time_sheet_record->status)
                                    @case('check-in')
                                        @php
                                            $checkin_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            $checkin_location = $time_sheet_record->gps_location;
                                        @endphp
                                        @break
                                    @case('start-lunch-time')
                                        @php
                                            $start_lunch_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                        @endphp
                                        @break
                                    @case('end-lunch-time')
                                        @php
                                            $end_lunch_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                        @endphp
                                        @break
                                    @case('start-break-time')
                                        @php
                                            $start_break_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                        @endphp
                                        @break
                                    @case('end-break-time')
                                        @php
                                            $end_break_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
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
                                //   echo ($checkin_date_time." ". $start_lunch_date_time." ". $end_lunch_date_time." ". $start_break_date_time." ". $end_break_date_time." ". $checkout_date_time." ". "<br>");
                                    
                                    $checkinDateTime = new DateTime($checkin_date_time); // Replace with your actual check-in date and time
                                    // Check-out date and time
                                    $timestamp = strtotime($checkin_date_time);
                                    $formatedCheckinDateTime = date("Y-m-d h:i A", $timestamp);

                                    if($start_break_date_time!=null  )
                                    {
                                        $timestamp = strtotime($start_break_date_time);
                                        $formatedStartBreakDateTime = date("Y-m-d h:i A", $timestamp);
                                    }
                                    if($end_break_date_time!=null)
                                    {
                                        $timestamp = strtotime($end_break_date_time);
                                        $formatedEndBreakDateTime = date("Y-m-d h:i A", $timestamp);
                                    }

                                    if($start_lunch_date_time!=null)
                                    {
                                        $timestamp = strtotime($start_lunch_date_time);
                                        $formatedStartLunchDateTime = date("Y-m-d h:i A", $timestamp);
                                    }
                                    if($end_lunch_date_time!=null )
                                    {
                                        $timestamp = strtotime($end_lunch_date_time);
                                        $formatedEndLunchDateTime = date("Y-m-d h:i A", $timestamp);

                                    }
                                    // dd($end_lunch_date_time);
                                @endphp
                                        
                                <tr>
                                    <td  class="tdclass">{{$merchandiser_time_sheet->store($merchandiser_time_sheet->store_id)->name_of_store}}</td>
                                    <td  class="tdclass">{{($merchandiser_time_sheet->store_location($merchandiser_time_sheet->store_location_id)->location)??null}}</td>
                                    <td  class="tdclass">
                                        @if($checkin_date_time!=null)
                                        {{$formatedCheckinDateTime}}
                                        @endif
                                        </td>
                                    <td  class="tdclass">{{$checkin_location}}</td>
                                    <td  class="tdclass">
                                        @if($start_break_date_time!=null)
                                        {{$formatedStartBreakDateTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($end_break_date_time!=null)
                                        {{$formatedEndBreakDateTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($start_lunch_date_time!=null)
                                        {{$formatedStartLunchDateTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($end_lunch_date_time!=null)
                                        {{$formatedEndLunchDateTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($checkout_date_time!=null)
                                        @php
                                            $checkout_time_converted= $formatedCheckoutDateTime
                                        @endphp
                                        @endif
                                    </td>
                                    <td  class="tdclass">{{$checkout_location}}</td>
                                    <td  class="tdclass"></td>
                                    {{-- <td  class="tdclass">{{}}</td> --}}
                                    <td  class="tdclass">{{$merchandiser['name']}}</td>
                                    <td  class="tdclass">{{$manager}}</td>
                                    <td  class="tdclass">
                                        
                                    </td>
                                    <td  class="tdclass">
                                        
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var startDate= 0;
    var endDate = 0;
    var allStores = {!! json_encode($storesArr) !!};
    var allUniqueLocations = {!! json_encode($locationArr) !!};

    var labels = [];
    var chartData =  {{ Js::from($chartHoursArray) }};
    console.log(chartData, "chart datwaaaaaa");
</script>

<script src="{{ asset('assets/js/merchandiserTimeSheetDataTableAndChart.js') }}"></script>

{{-- @vite(['resources/js/chart.js']) --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#period-search", {
            dateFormat: "M d, Y",
            altFormat: "F j, Y",
            mode: "range",
            });
    });

</script>
<script>

    function downloadTable(table) {
        const rows = table.getElementsByTagName('tr');
        let csvContent = 'data:text/csv;charset=utf-8,';

        // Add headers as bold and uppercase
        const headers = table.querySelectorAll('thead th');
        const headerText = Array.from(headers)
            .map(header => header.innerText.toUpperCase())
            .join(',');
        csvContent += headerText + '\r\n';

        // Add data rows
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            for (let j = 0; j < cells.length; j++) {
                csvContent += cells[j].innerText + ',';
            }
            csvContent += '\r\n';
        }

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'table_data.csv');
        document.body.appendChild(link);
        link.click();
        
    }
    document.getElementById('downloadButton').addEventListener('click', () => {
        const timeSheetTable = document.getElementById('mechandiserDatatable');
        downloadTable(timeSheetTable);
    });

</script>
@endsection
