@extends('manager.layout.app')
@section('title', 'Stock Count By Store')

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
                    @foreach ($locationArr as $location)
                        <option value="{{$location}}">{{$location}}</option>
                    @endforeach
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
    </div>
    <div class='row  d-flex align-items-center col-actions' style="max-width: 99%; margin: 1px auto;">
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
        <div class="col-md-3 col-3 p-3">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Opening Week Stock</div>    
                <div class="card-body">
                    <small class="text-secondary">
                        @php
                            $sevenDaysAgo = (new DateTime())->sub(new DateInterval('P7D'));
                            echo $sevenDaysAgo->format('Y-m-d');
                        @endphp 
                    </small>
                    <div><b>0.0</b></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-3 p-3">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Closing Week Stock</div>    
                <div class="card-body">
                    <small class="text-secondary">
                        @php
                            $sevenDaysAgo = (new DateTime());
                            echo $sevenDaysAgo->format('Y-m-d');
                        @endphp 
                    </small>
                    <div><b>0.0</b></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-3 p-3">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Average Week Stock</div>    
                <div class="card-body">
                    <div><b>0.0</b></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-5">
        <div class="col-12">
            <div style="width: 800px; margin: auto;">
                <canvas id="myChart"></canvas>
            </div>
        </div>
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
            <div class=" m-3 float-end d-flex">
                <label class="download_filter_label">Download filtered table in excel</label>
                <button id="downloadButton" class="btn btn-light" ><img src="{{ asset('assets/images/managericons/download.png') }}" alt="Download"></button>
            </div>
        </div>
        <div class="col-12">

            

            <div class="table-responsive" >
                    {{-- table-responsive --}}
                    {{-- nowrap --}}
                <table id="stockCoutntByStoreDatatable" class="table table-sm  datatable table-hover  " style="border: 1px solid #ccc; min-width: 1580px; ">
                    <thead>
                        <tr>
                            <th class="thclass" scope="col">Date</th>
                            <th class="thclass" scope="col">Name of Store</th>
                            <th class="thclass" scope="col">Location</th>
                            <th class="thclass" scope="col">Category</th>
                            <th class="thclass" scope="col">Product Name</th>
                            <th class="thclass" scope="col">Product Number</th>
                            <th class="thclass" scope="col">Stocks on Shelf (Qty)</th>
                            <th class="thclass" scope="col">Stocks on Shelf (units/cases)</th>
                            <th class="thclass" scope="col">Stocks Packed (Qty)</th>
                            <th class="thclass" scope="col">Stocks Packed (units/cases)</th>
                            <th class="thclass" scope="col">Stocks in Storeroom (Qty)</th>
                            <th class="thclass" scope="col">Stocks in Storeroom  (units/cases)</th>
                            <th class="thclass" scope="col">Total Stocks</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                            $totalHourworked=0;
                            $chartDateArray = array();
                            $chartHoursArray = array();
                            $i=1;
                    @endphp
                    
                    {{-- {{dd($stockCountData)}} --}}
                      @if ($stockCountData!=null)
                        @foreach ($stockCountData as $stockCount)
                        @php
                            // dd($stockCount);

                            $totalStock=$stockCount['stock_on_shelf']+$stockCount['stock_packed']+$stockCount['stock_in_store_room'];
                        @endphp
                            <tr>
                                <td class="tdclass">
                                    @php
                                        $date= explode(' ', $stockCount->created_at);
                                    @endphp
                                    {{$date[0]}}
                                </td>
                                <td class="tdclass">{{$stockCount->store->name_of_store}}</td>
                                <td class="tdclass">
                                    {{$stockCount->storeLocation->location}}
                                </td>
                                
                                <td class="tdclass">{{$stockCount->category->category}}</td>
                                <td class="tdclass">{{$stockCount->product->product_name}}</td>
                                <td class="tdclass">{{$stockCount->product_sku}}</td>
                                <td class="tdclass">{{$stockCount->stock_on_shelf}}</td>
                                <td class="tdclass">{{$stockCount->stock_on_shelf_unit}}</td>
                                <td class="tdclass">{{$stockCount->stock_packed}}</td>
                                <td class="tdclass">{{$stockCount->stock_packed_unit}}</td>
                                <td class="tdclass">{{$stockCount->stock_in_store_room}}</td>
                                <td class="tdclass">{{$stockCount->stock_in_store_room_unit}}</td>
                                <td class="tdclass">{{$totalStock}}</td>
                            </tr>
                        @endforeach
                      @endif                     
                        
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

    // var chartData =  {{ Js::from($chartHoursArray) }};
    // console.log(chartData, "chart datwaaaaaa");
</script>

<script src="{{ asset('assets/js/stockCountByStoreDatatable.js') }}"></script>

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
        link.setAttribute('download', 'Stock_count_by_store_table.csv');
        document.body.appendChild(link);
        link.click();
        
    }
    document.getElementById('downloadButton').addEventListener('click', () => {
        const timeSheetTable = document.getElementById('stockCoutntByStoreDatatable');
        downloadTable(timeSheetTable);
    });
</script>


@endsection
