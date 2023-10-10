@extends('manager.layout.app')
@section('title', 'Notification')

@section("top_links")

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


@endsection

@section("bottom_links")

<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    
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

    {{-- {{dd($userArr)}} --}}
    <div  class="row d-flex align-items-center col-actions" style="max-width: 99%; margin: 1px auto;">
        <div class="col-md-3 col-3 p-3">
            
            <div class="form-group" >
                <label for="period-search" class="form-label filter period filter-search">Period</label>
                <input type="text" id="period-search" value="Date Range" class=" form-control filter">
                <i class="fas fa-times-circle clear-icon" id="clearDate"></i>

            </div>
        </div>
        <div class="col-md-3 col-3 p-3">
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
        <div class="col-md-3 col-3 p-3">
            <div class="form-group">
                <label for="location-search" class="form-label filter location">Select Location</label>
                <select name="location-search" class="filter form-select" id="location-search">
                    <option value="" selected>--Select--</option>
                    {{-- @foreach ($locationArr as $location)
                        <option value="{{$location}}">{{$location}}</option>
                    @endforeach --}}
                </select>                
            </div>

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
        <div class="col-md-3 col-3 p-3">
            <div class="form-group">
                <label for="category-search" class="form-label filter category">Select Category</label>
                <select name="category-search" class=" filter form-select"  id="category-search">
                    <option value="" selected>--Select-- </option>
                     @foreach($categories->unique('category')->sort() as $category)
                     <option value="{{$category['category']}}">{{$category['category']}}</option>
                    @endforeach
                </select>   
            </div>
        </div>
        <div class="col-md-3 col-3 p-3">
            <div class="form-group">
                <label for="product-search" class="form-label filter product">Select product</label>
                <select name="product-search" class=" filter form-select"  id="product-search">
                    <option value="" selected>--Select-- </option>
                    @foreach($products->unique('product_name')->sort() as $product)
                    <option value="{{$product['product_name']}}">{{$product['product_name']}}</option>
                    @endforeach
                </select>   
            </div>
        </div>
    </div>
    <div class="row pt-5" style="margin: 1px auto; font-size: 12px;">
        <div class="col-12">
            <div class="col-12">
                <div class="user_btn myborder float-end m-3" onclick="window.location.href = '{{ route('web_notification.create')}}'; return false;" >
                    <button  class="user_btn_style submit" > <img src="{{asset('assets/images/managericons/send_button.svg')}}"  width=20 alt="send"> Send Notification</button>
                </div>
            </div>
        </div>
        <div class="col-12">

            <div class="table-responsive" >
                    {{-- table-responsive --}}
                    {{-- nowrap --}}
                <table id="mechandiserDatatable-test" class="table table-sm  table-hover  " style="border: 1px solid #ccc; min-width: 1580px; ">
                    <thead>
                        <tr>
                            <th class="thclass" scope="col">#ID</th>
                            <th class="thclass" scope="col">Title</th>
                            <th class="thclass" scope="col">Message</th>
                            <th class="thclass" scope="col">Merchandiser</th>
                            <th class="thclass" scope="col">Image</th>
                            <th class="thclass" scope="col">Date Created</th>
                            <th class="thclass" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                            $totalHourworked=0;
                            $chartDateArray = array();
                            $chartHoursArray = array();
                    @endphp
                    @php
                        $i=1;
                    @endphp
                    <tr>
                        @if($allNotifications!=null)
                            @foreach($allNotifications as $notification)
                                <tr>
                                    {{-- {{dd($notification)}} --}}

                                    <td class="tdclass">{{$i++}}</td>
                                    <td class="tdclass">{{$notification->title}}</td>
                                    <td class="tdclass">{{$notification->message}}</td>
                                    <td class="tdclass">{{$notification->companyUser->user->name}}</td>
                                    <td  class="tdclass">
                                        @php
                                        if($notification->attachment!=null)
                                        {
                                            $imagePath = public_path('storage/' . $notification->attachment);
                                            if (file_exists($imagePath)) 
                                            {
                                                echo "<img width='100' src='" . asset('storage/' . $notification->attachment) . "' />";
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
                                     @php
                                    // dd($userTimeZone);
                                        $created_at = convertToTimeZone($notification->created_at, 'UTC', $userTimeZone);
                                        $createdTime = new DateTime($created_at);
                                
                                        // Format the DateTime object in 12-hour format
                                        $formattedCreatedTime = $createdTime->format("Y-m-d h:i:s A");
                                    @endphp
                                    <td class="tdclass">{{$formattedCreatedTime}}</td>
                                    <td class="tdclass">

                                        <form action={{ route('web_notification.destroy', $notification->id) }} method="post">
                                            @csrf
                                            @method('DELETE')
                                        
                                            <button class="submit delete-button"><i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
                                            </button>
                                            <a href="{{ route('edit-notification',  [$i, $notification->id]) }}"><i class="fa fa-pencil-square-o text-secondary" aria-hidden="true"></i>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        {{-- <td> abc store</td>
                        <td>test location</td>
                        <td>Bevereges</td>
                        <td>Shampain</td>
                        <td>wisky</td>
                        <td>this is best opportunity</td>
                        <td>
                            <img  src="{{asset('assets/images/pctracker1683118440.jpg.png')}}" alt="Image Description" width="100" height="100">
                        </td> --}}
                    </tr>
                       @php
                           array_push($chartHoursArray ,['product name'=>"first_product", 'price'=>50] );
                       @endphp 
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

</script>

<script src="{{ asset('assets/js/notificationsDatatable.js') }}"></script>
{{-- @include('manager/modal/modalAddNotification') --}}

{{-- @vite(['resources/js/chart.js']) --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#period-search", {
            dateFormat: "M d, Y",
            altFormat: "F j, Y",
            mode: "range",
            });
    });

    
    // document.getElementById('clearDate').addEventListener('click', function () {
    //     document.getElementById('period-search').clear;
    //     // document.getElementById('merchandiser-search').value='';
    //     // document.getElementById('location-search').value='';
    //     // document.getElementById('store-search').value='';
        
    //     // convertingData(chartData);
    //     // myChartJS.data.labels = labels;
    //     // myChartJS.data.datasets[0].data = hoursWorked;
    //     // myChartJS.update(); 
    //     document.getElementById('period-search').value= 'Date Range';

    // });
</script>
@endsection
