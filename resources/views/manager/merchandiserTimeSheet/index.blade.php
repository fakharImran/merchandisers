@extends('manager.layout.app')

@section("top_links")
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"> --}}
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
@endsection

@section("bottom_links")
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
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
    
</style>
<div  class="d-flex align-items-center col-actions" style="   max-width: 99%; margin: 1px auto;">
    <div class="col-md-03 col-03">
        <div class="" >Period
        </div>
        
    </div>


    <div class="col-md-03 col-03">
        <label for="">Select Store</label>
        <select name="manager_store" id="manager_store">
            <option value="" selected>--Select-- </option>
            @foreach($stores as $store)
            <option value="{{$store['name_of_store']}}">{{$store['name_of_store']}}</option>
            @endforeach
        </select>

    </div>

    <div class="col-md-03 col-03">
        <label for="">Select Location</label>
        <select name="manager_store_location" id="manager_store_location">
            <option value="" selected>--Select-- </option>
            @foreach($stores as $store)
            <option value="{{$store['location']}}">{{$store['location']}}</option>
            @endforeach
        </select>

    </div>

    
    <div class="col-md-03 col-03">
        <label for="">Select Merchandiser</label>
        <select name="merchandiser_name" id="merchandiser_name">
            <option value="" selected>--Select-- </option>
            @foreach($merchandiserArray as $merchandiser)
            <option value="{{$merchandiser['name']}}">{{$merchandiser['name']}}</option>
            @endforeach
        </select>
    </div>
</div>
<div style="width: 800px; margin: auto;">
    <canvas id="myChart"></canvas>
</div>
            {{-- {{dd($merchandiserArray)}} --}}

    <button
        class="btn btn-primary btn-sm edit-address"
        type="button"
        data-bs-toggle="modal"
        data-bs-target="#pendingTimeSheet"
        >
        Pending Time Sheets
    </button>
<div class="table-responsive mt-2" style="overflow: auto;">

    <table id="dataTable" style="border: 1px solid #ccc; min-width: 1580px; ">
        <thead>
            <tr>
                <th>Name of Store</th>
                <th>Location</th>
                <th>Check-in Time</th>
                <th>Check-in GPS Location</th>
                <th>Break Time</th>
                <th>Lunch Time</th>
                <th>Check-out Time</th>
                <th>Check-out GPS Location</th>
                <th>Hours Worked</th>
                <th>Merchandiser</th>
                <th>Store Manager</th>
                <th>Signature</th>
                <th>Time of Signature</th>
            </tr>
        </thead>
        <tbody>
            
           @php
                $totalHourworked=0;
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

                        <td>{{$merchandiser_time_sheet->store->name_of_store}}</td>
                        <td>{{$merchandiser_time_sheet->store->location}}</td>
                        <td>{{$checkin_date_time}}</td>
                        <td>{{$checkin_location}}</td>
                        <td>{{$break_date_time}}</td>
                        <td>{{$lunch_date_time}}</td>
                        <td>{{$checkout_date_time}}</td>
                        <td>{{$checkout_location}}</td>
                        <td>{{$hoursWorked}} hrs, {{$minutesWorked}} mins</td>
                        {{-- <td>{{}}</td> --}}
                        <td>{{$merchandiser['name']}}</td>
                        <td>{{$manager->name}}</td>
                        <td>
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
                        <td>
                            <?php
                                $imagePath = public_path('storage/' . $merchandiser_time_sheet->signature);
                            
                                if (file_exists($imagePath)) 
                                {
                                    echo "$checkout_date_time";
                                } 
                                else 
                                {
                                    echo "N/A";
                                }
                            ?>    
                            
                        </td>
                    </tr>

                    {{-- {{dd('umer')}} --}}
                @endforeach
                
            @endforeach
            {{-- {{dd($totalHourworked)}} --}}
        </tbody>
    </table>
</div>

<script src="{{ mix('/js/app.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            // Add any DataTable options you need here
        });
    });
    
</script>
@include('manager/merchandiserTimeSheet/pendingTimeSheets')

@endsection
