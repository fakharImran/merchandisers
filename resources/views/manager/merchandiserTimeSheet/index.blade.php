@extends('manager.layout.app')

@section("top_links")

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

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
                <select name="store-search" class=" filter form-select" id="store-search">
                    <option value="" selected>--Select-- </option>
                    @if($stores!=null)
                    @foreach ($stores->unique('name_of_store')->sort() as $store)
                    <option value="{{$store['name_of_store']}}">{{$store['name_of_store']}}</option>
                    @endforeach
                    @endif
                </select>

                


            </div>
        </div>
        <div class="col-md-3 col-3 p-4">
            <div class="form-group">
                <label for="location-search" class="form-label filter location">Select Location</label>
                <select name="location-search" class=" filter form-select"  id="location-search">
                    <option value="" selected>--Select-- </option>
                    @if($stores!=null)
                    @foreach ($stores->unique('location')->sort() as $store)
                    <option value="{{$store['location']}}">{{$store['location']}}</option>
                    @endforeach
                    @endif
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
                                    $manager = $merchandiser_time_sheet->store_manager_name;
                                    // dd($manager);
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

                                    // Check-out date and time
                                    $timestamp = strtotime($break_date_time);
                                    $formatedBreakDateTime = date("Y-m-d h:i A", $timestamp);


                                    $timestamp = strtotime($lunch_date_time);
                                    $formatedLunchDateTime = date("Y-m-d h:i A", $timestamp);




                                    $checkoutDateTime = new DateTime($checkout_date_time); // Replace with your actual check-out date and time
                                    // Calculate the difference
                                    $timestamp = strtotime($checkout_date_time);
                                    $formatedCheckoutDateTime = date("Y-m-d h:i A", $timestamp);
                                    $interval = $checkinDateTime->diff($checkoutDateTime);
                                    
                                    // Calculate the total hours
                                    $hoursWorked = $interval->days * 24 + $interval->h ;
                                    $minutesWorked = $interval->i ;
                                    $totalHours=  $interval->days * 24 + $interval->h + $interval->i/60;
                                    $totalHourworked+= $totalHours;

                                    // dd(
                                    //     $checkin_date_time,  $checkout_date_time,
                                    // $formatedCheckinDateTime, $formatedCheckoutDateTime , $interval, $hoursWorked, $minutesWorked);
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
                                        {{$formatedBreakDateTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($lunch_date_time!='N/A')
                                        {{$formatedLunchDateTime}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">
                                        @if($checkout_date_time!='N/A')
                                        @php
                                            $checkout_time_converted= $formatedCheckoutDateTime
                                        @endphp
                                        {{$checkout_time_converted}}
                                        @endif
                                    </td>
                                    <td  class="tdclass">{{$checkout_location}}</td>
                                    <td  class="tdclass">{{$hoursWorked}} hrs, {{$minutesWorked}} mins</td>
                                    {{-- <td  class="tdclass">{{}}</td> --}}
                                    <td  class="tdclass">{{$merchandiser['name']}}</td>
                                    <td  class="tdclass">{{$manager}}</td>
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
                                                echo "$checkout_time_converted";
                                            } 
                                            else 
                                            {
                                                echo "N/A";
                                            }
                                        @endphp   
                                    </td>
                                </tr>
                                @php
                                    array_push($chartHoursArray ,['date'=>Carbon\carbon::parse(strval($checkout_time_converted))->format('Y-m-d'), 'hours'=>$totalHours] );
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
    console.log(chartData);
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

    document.getElementById('clearDate').addEventListener('click', function () {
        document.getElementById('period-search').clear;
        convertingData(chartData);
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = hoursWorked;
        myChartJS.update(); 
        document.getElementById('period-search').value= 'Date Range';

    });
</script>
 


@include('manager/merchandiserTimeSheet/pendingTimeSheets')

@endsection
