@extends('manager.layout.app')

@section("top_links")
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"> --}}
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
@endsection

@section("bottom_links")
{{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> --}}
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
<div class="row mb-5" style="   max-width: 99%; margin: 1px auto;">
    <div class="col-md-12 col-12">
        <div class="Company" >Period
        </div>
        
    </div>
    {{-- <div class="col-md-1 col-3"  style="margin: 1px auto;">
        <div class="add_btn">
            <a href="{{ route('company.create') }}"> <span>+</span>New</a>
        </div>
    </div> --}}
</div>
<div style="width: 800px; margin: auto;">
    <canvas id="myChart"></canvas>
</div>
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
            
            {{-- {{dd($merchandiserArray)}} --}}
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
                        <td><img width='100' src="{{ asset('storage/' . $merchandiser_time_sheet->signature) }}" /></td>
                        <td>{{$checkout_date_time}}</td>
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
@endsection