@extends('manager.layout.app')
@section('title', 'Business Overview')
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
<div class="container business-overview">

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
    </div>.
    @php
        $sumTotalStock=0;
    @endphp
    <div class='row  d-flex align-items-center col-actions' style="max-width: 99%; margin: 1px auto;">
        <div class="col-md-3-5 col-sm-3 col-6 pt-2">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Total Stock Count</div>    
                <div class="card-body content">
                    <small class="text-secondary">
                        @php
                            $todayDate = (new DateTime());
                            echo $todayDate->format('Y-m-d');
                        @endphp 
                    </small>
                    <div class="Link0" style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word"><span>{{$sumTotalStock}}</div>                
                </div>
            </div>
        </div>
        <div class="col-md-3-5 col-sm-3 col-6 pt-2">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Number of Stores serviced</div>    
                <div class="card-body content">
                    <small class="text-secondary">
                        @php
                            $todayDate = (new DateTime());
                            echo $todayDate->format('Y-m-d');
                        @endphp 
                    </small>

                     @php
                            $totalStoresOfMerchandiserCheckin=$stores->unique('name_of_store')->count();

                        @endphp
                    <div class="Link0" style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word"><span style="color: #CA371B">{{$totalStoresOfMerchandiserCheckin}}</span> / {{$systemStoreCount}}</div>
                
                </div>
            </div>
        </div>
        <div class="col-md-3-5 col-sm-3 col-6 pt-2">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Number of Stores with out of stock</div>    
                <div class="card-body content">
                    <small class="text-secondary">
                        @php
                            $todayDate = (new DateTime());
                            echo $todayDate->format('Y-m-d');
                        @endphp
                    </small>
                        @php
                            $totalStores=$stores->unique('name_of_store')->count();

                            $uniqueStores = $outOfStockData->unique('store_id')->sort();
                            $uniqueStoreCount = $uniqueStores->count();
                        @endphp
                    <div class="Link0" style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word"><span style="color: #CA371B">{{$uniqueStoreCount}}</span> / {{$totalStores}}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3-5 col-sm-3 col-6 pt-2">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Number of Products out of stock in stores</div>    
                <div class="card-body content">
                    <small class="text-secondary">
                        @php
                            $todayDate = (new DateTime());
                            echo $todayDate->format('Y-m-d');
                        @endphp 
                    </small>
                    @php
                        $totalProducts= $products->unique('product_name')->count();
                        $uniqueProducts = $outOfStockData->unique('product_id')->sort();
                        $uniqueProductCount = $uniqueProducts->count();
                    @endphp 
                    <div class="Link0" style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word"><span style="color: #CA371B">{{$uniqueProductCount}}</span> / {{$totalProducts}}</div>                
                </div>
            </div>
        </div>
        <div class="col-md-3-5 col-sm-3 col-6 pt-2">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Number of Stores with Expired Products</div>    
                <div class="card-body content">
                    <small class="text-secondary">
                        @php
                            $todayDate = (new DateTime());
                            echo $todayDate->format('Y-m-d');
                        @endphp 
                    </small>
                    @php
                        $totalStores=$stores->unique('name_of_store')->count();

                        $uniqueStores = $productExpiryTrackerData->unique('store_id')->sort();
                        $uniqueStoreCount = $uniqueStores->count();
                    @endphp
                    <div class="Link0" style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word"><span style="color: #CA371B">{{$uniqueStoreCount}} /</span> {{$totalStores}}</div>                
                
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-5">
        <div class="col-12">
            <div style="width: 900px; margin: auto;">
                <div class="row d-flex">
                    <div class="col-4">
                        <label for="merchandiser-search" class="form-label filter merchandiser">Stock Level of products in store </label>
                    </div>
                    <div class="col-4">
                        <select name="casesorunits"  style=" padding: 10px; text-align: center; font-size: revert; " class=" form-select"  id="casesorunits">
                            <option class="text-secondary" value="" selected disabled>Select Case or Units </option>
                            
                            <option value="Unit">Unit</option>
                            <option value="Case">Case</option>
                        </select>              
                    </div>
                    <div class="col-4">
                        <select onchange="changePeriod(this)" name="casesorunits" style=" padding: 10px; text-align: center; font-size: revert; "
                         class=" form-select"  id="casesorunits">
                            <option class="text-secondary" value="" selected disabled>Select Chart Period Filter</option>
                            <option value="Daily">Days</option>
                            <option value="Weekly">Weeks</option>
                            <option value="Monthly">Months</option>
                        </select>              
                    </div>
                </div>
               
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
    @php
        $totalHourworked=0;
        $chartDateArray = array();
        $chartStockArray = array();
        $i=1;
    @endphp
    @if ($stockCountData!=null)
        @foreach ($stockCountData as $stockCount)
            @php
                // dd($stockCount);
                $totalStock=$stockCount['stock_on_shelf']+$stockCount['stock_packed']+$stockCount['stock_in_store_room'];
                $date= explode(' ', $stockCount->created_at);
                $sumTotalStock+= $totalStock;
                array_push($chartStockArray, ['stock'=>$totalStock, 'date'=>$date[0]]);
            @endphp
        @endforeach
    @endif      

    {{-- {{dd($chartStockArray)}} --}}
    
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
     {{-- document.getElementById('total_stock_count').innerHTML = {{$sumTotalStock}};
     document.getElementById('opening_week_stock').innerHTML = {{$sumOpeningWeekStock}};
    document.getElementById('closing_week_stock').innerHTML = {{$sumClosingWeekStock}}; --}}
</div>

<script>
    
    var startDate= 0;
    var endDate = 0;
    var allStores = {!! json_encode($storesArr) !!};
    var allUniqueLocations = {!! json_encode($locationArr) !!};
    
    var graphFormat = 'days';

    var labels = [];

    var convertedToChartData =  {{ Js::from($chartStockArray) }};
</script>


<script src="{{ asset('assets/js/businessOverviewDatatableAndChart.js') }}"></script>

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

    // function downloadTable(table) {
    //     const rows = table.getElementsByTagName('tr');
    //     let csvContent = 'data:text/csv;charset=utf-8,';

    //     // Add headers as bold and uppercase
    //     const headers = table.querySelectorAll('thead th');
    //     const headerText = Array.from(headers)
    //         .map(header => header.innerText.toUpperCase())
    //         .join(',');
    //     csvContent += headerText + '\r\n';

    //     // Add data rows
    //     for (let i = 0; i < rows.length; i++) {
    //         const cells = rows[i].getElementsByTagName('td');
    //         for (let j = 0; j < cells.length; j++) {
    //             csvContent += cells[j].innerText + ',';
    //         }
    //         csvContent += '\r\n';
    //     }

    //     const encodedUri = encodeURI(csvContent);
    //     const link = document.createElement('a');
    //     link.setAttribute('href', encodedUri);
    //     link.setAttribute('download', 'table_data.csv');
    //     document.body.appendChild(link);
    //     link.click();
        
    // }
    // document.getElementById('downloadButton').addEventListener('click', () => {
    //     const timeSheetTable = document.getElementById('mechandiserDatatable');
    //     downloadTable(timeSheetTable);
    // });
</script>


@endsection
