@extends('manager.layout.app')
@section('title', 'Business Overview')

<head>
    {{-- <script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=myMap"></script> --}}

</head>

@section('top_links')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>


@endsection

@section('bottom_links')

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script></script>
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


        td,
        th {
            border: 2px solid #ccc;
            /* padding: 10px; */
        }

        th {
            background-color: #f7f7f7;
            color: #233D79;
        }

        /* Define a CSS class to apply the background image */
    </style>
    <div class="container business-overview">

        {{-- {{dd($userArr)}} --}}
        <div class="row d-flex align-items-center col-actions" style="max-width: 99%; margin: 1px auto;">
            <div class="col-md-3 col-3 p-3">

                <div class="form-group">
                    <label for="period-search" class="form-label filter period filter-search">Period</label>
                    <input type="text" id="period-search" value="Date Range" class=" form-control filter">
                    <i class="fas fa-times-circle clear-icon" id="clearDate"></i>

                </div>
            </div>
            <div class="col-md-3 col-3 p-3">
                <div class="form-group">
                    <label for="store-search" class="form-label filter store">Select Store</label>
                    <select name="store-search" class="filter form-select select2" id="store-search">
                        <option value="" selected>--Select--</option>
                        @if ($stores != null)
                            @foreach ($stores->unique('name_of_store')->sortBy('name_of_store') as $store)
                                <option value="{{ $store['name_of_store'] }}">{{ $store['name_of_store'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            {{-- for setting the filter dropdown unique and sorted value --}}
            @php
                $locationArr = [];
                $storesArr = [];

            @endphp
            @if ($stores != null)
                @foreach ($stores as $store)
                    @php
                        $tempLocation = [];
                    @endphp

                    @foreach ($store->locations->unique('location')->sort() as $location)
                        @php
                            array_push($locationArr, $location['location']);
                            array_push($tempLocation, $location['location']);
                        @endphp
                    @endforeach
                    @php
                        $uniqueLocation = array_unique($tempLocation);
                        sort($uniqueLocation);
                        array_push($storesArr, [$store->name_of_store, $uniqueLocation]);

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
                    <select name="location-search" class="filter form-select select2" id="location-search">
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
                    <select name="merchandiser-search" class=" filter form-select select2" id="merchandiser-search">
                        <option value="" selected>--Select-- </option>
                        @php
                            $uniqueMerchandisers = array_unique(array_column($userArr, 'name'));
                            asort($uniqueMerchandisers); // Sort the array alphabetically
                        @endphp
                        @foreach ($uniqueMerchandisers as $merchandiser)
                            <option value="{{ $merchandiser }}">{{ $merchandiser }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-3 p-3">
                <div class="form-group">
                    <label for="category-search" class="form-label filter category">Select Category</label>
                    <select name="category-search" class=" filter form-select select2" id="category-search">
                        <option value="" selected>--Select-- </option>
                        @foreach ($categories->unique('category')->sortBy('category') as $category)
                            <option value="{{ $category['category'] }}">{{ $category['category'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- {{dd($products)}} --}}
            <div class="col-md-3 col-3 p-3">
                <div class="form-group">
                    <label for="product-search" class="form-label filter product">Select product</label>
                    <select name="product-search" class=" filter form-select select2" id="product-search">
                        <option value="" selected>--Select-- </option>
                        @foreach ($products->unique('product_name')->sortBy('product_name') as $product)
                            <option value="{{ $product['product_name'] }}">{{ $product['product_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>.
        @php
            $sumTotalStock = 0;
            $sumTotalStockCases = 0;
        @endphp
        <div class='row  d-flex align-items-center col-actions' style="max-width: 99%; margin: 1px auto;">
            <div class="col-md-3-5 col-sm-3 col-6 pt-2">
                <div class="card manager-card-style">
                    <div class="card-header manager-card-header">Total stock count</div>
                    <div class="card-body">
                        <div class="content">
                            <div class="row">
                                <div class="col-12" style="color: #37A849;">
                                    <h3><b id="total_stock_count">{{ $sumTotalStockCases }} </b><sub
                                            style="font-size: small;"> Cases</sub></h3>
                                </div>
                                <div class="col-12" style="color: #37A849;">
                                    <h3><b id="total_stock_count_cases">{{ $sumTotalStock }} </b><sub
                                            style="font-size: small;"> Units</sub></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3-5 col-sm-3 col-6 pt-2">
                <div class="card manager-card-style">
                    <div class="card-header manager-card-header">Number of Stores serviced</div>
                    <div class="card-body content">
                        <small id="date_range_set" class="text-secondary date_range_set">
                            @php
                                $todayDate = new DateTime();
                                echo $todayDate->format('Y-m-d');
                            @endphp
                        </small>

                        @php

                        @endphp
                        <div class="Link0" id="serviced_stores"
                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                            {{ count($uniqueServicedStoreLocation) }} / {{ count($locationArr) }}</div>

                    </div>
                </div>
            </div>
            <div class="col-md-3-5 col-sm-3 col-6 pt-2">
                <div class="card manager-card-style">
                    <div class="card-header manager-card-header">Number of Stores with out of stock</div>
                    <div class="card-body content">
                        <small id="date_range_set" class="text-secondary date_range_set">
                            @php
                                $todayDate = new DateTime();
                                echo $todayDate->format('Y-m-d');
                            @endphp
                        </small>
                        @php
                            $totalStores = $stores->unique('name_of_store')->count();

                            $uniqueStores = $outOfStockData->unique('store_id')->sort();
                            $uniqueStoreCount = $uniqueStores->count();
                        @endphp
                        <div class="Link0" id="stores_out_of_stock"
                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                            <span style="color: #CA371B">{{ $uniqueStoreCount }}</span> / {{ count($locationArr) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3-5 col-sm-3 col-6 pt-2">
                <div class="card manager-card-style">
                    <div class="card-header manager-card-header">Number of Products out of stock in stores</div>
                    <div class="card-body content">
                        <small class="text-secondary date_range_set">
                            @php
                                $todayDate = new DateTime();
                                echo $todayDate->format('Y-m-d');
                            @endphp
                        </small>
                        @php
                            $totalProducts = $products->unique('product_name')->count();
                            $uniqueProducts = $outOfStockData->unique('product_id')->sort();
                            $uniqueProductCount = $uniqueProducts->count();
                        @endphp
                        <div class="Link0" id="products_out_of_stock"
                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                            <span style="color: #CA371B">{{ $uniqueProductCount }}</span> / {{ $totalProducts }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3-5 col-sm-3 col-6 pt-2">
                <div class="card manager-card-style">
                    <div class="card-header manager-card-header">Number of Stores with Expired Products</div>
                    <div class="card-body content">
                        <small class="text-secondary date_range_set">
                            @php
                                $todayDate = new DateTime();
                                echo $todayDate->format('Y-m-d');
                            @endphp
                        </small>
                        @php
							$uniqueExpProduct = $productExpiryTrackerData->unique('store_id')->sort();
                        @endphp
                        <div class="Link0" id="stores_with_exp_products"
                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                            <span style="color: #CA371B">{{ count($uniqueExpProduct) }} /</span> {{ count($locationArr) }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-12">
                <div style="width: 900px; margin: auto;">
                    <div class="row d-flex">
                        <div class="col-4">
                            <label for="merchandiser-search" class="form-label filter merchandiser">Stock Level of
                                products in store </label>
                        </div>
                        <div class="col-4">
                            <select name="casesorunits" onchange="changeUnitCount(this)"
                                style=" padding: 10px; text-align: center; font-size: revert; " class=" form-select"
                                id="casesorunits">
                                <option class="text-secondary" value="" selected disabled>Select Case or Units
                                </option>
                                <option value="Unit">Unit</option>
                                <option value="Case">Case</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <select onchange="changePeriod(this)" name="casesorunits"
                                style=" padding: 10px; text-align: center; font-size: revert; " class=" form-select"
                                id="casesorunits">
                                <option class="text-secondary" value="" selected disabled>Select Chart Period Filter
                                </option>
                                <option value="Daily">Days</option>
                                <option value="Weekly">Weeks</option>
                                <option value="Monthly">Months</option>
                            </select>
                        </div>
                    </div>

                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="col-12">
                <div class="card manager-card-style">
                    <div class="card-header manager-card-header">Number of Stores serviced by Channel</div>

                    <div id="map" style="height: 600px;"></div>

                </div>
            </div>
        </div>
        @php
            $totalHourworked = 0;
            $chartDateArray = [];
            $chartStockArray = [];
            $i = 1;
        @endphp

        <div class="col-12" style="display: block">

            <div class="table-responsive">
                {{-- table-responsive --}}
                {{-- nowrap --}}
                <table id="businessOverviewDatatable" class="table table-sm  datatable table-hover  "
                    style="border: 1px solid #ccc; min-width: 1580px; ">
                    <thead>
                        <tr>
                            <th class="thclass" style=" width: 47.4375px;" scope="col"> Date</th>

                            <th class="thclass" scope="col">Stock Name of Store</th>
                            <th class="thclass" scope="col"> Location</th>
                            <th class="thclass" scope="col"> Category</th>
                            <th class="thclass" scope="col">Stock Product Name</th>
                            <th class="thclass" scope="col"> Merchandiser</th>
                            <th class="thclass" scope="col">Stock Total Stocks (units)</th>
                            <th class="thclass" scope="col">Stock Total Stocks (cases)</th>

                            <th class="thclass" scope="col">Out_of_stock Name of Store</th>
                            <th class="thclass" scope="col">Out_of_stock Product Name</th>

                            <th class="thclass" scope="col">Product_expiry Name of Store</th>
                            <th class="thclass" scope="col">merchandiser_time_sheet Location</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalHourworked = 0;
                            $chartDateArray = [];
                            $chartStockArray = [];
                            $i = 1;
                            $shelfUnits = 0;
                            $shelfCases = 0;
                            $packedUnits = 0;
                            $packedCases = 0;
                            $storeRoomUnits = 0;
                            $storeRoomCases = 0;

                        @endphp
                        {{-- {{dd($outOfStockData)}} --}}
                        {{-- {{dd($stockCountData)}} --}}
                        @if (!$stockCountData->isEmpty())
                            @foreach ($stockCountData as $stockCount)
                                @php
                                    // dd($stockCount);
                                    if ($stockCount->stock_on_shelf_unit == 'Units' || $stockCount->stock_on_shelf_unit == 'units') {
                                        $shelfUnits = $stockCount->stock_on_shelf;
                                        $shelfCases = 0;
                                    } elseif ($stockCount->stock_on_shelf_unit == 'Cases' || $stockCount->stock_on_shelf_unit == 'cases') {
                                        $shelfCases = $stockCount->stock_on_shelf;
                                        $shelfUnits = 0;
                                    } else {
                                        $shelfCases = 0;
                                        $shelfUnits = 0;
                                    }
                                    if ($stockCount->stock_packed_unit == 'Units' || $stockCount->stock_packed_unit == 'units') {
                                        $packedUnits = $stockCount->stock_packed;
                                        $packedCases = 0;
                                    } elseif ($stockCount->stock_packed_unit == 'Cases' || $stockCount->stock_packed_unit == 'cases') {
                                        $packedCases = $stockCount->stock_packed;
                                        $packedUnits = 0;
                                    } else {
                                        $packedUnits = 0;
                                        $packedCases = 0;
                                    }
                                    if ($stockCount->stock_in_store_room_unit == 'Units' || $stockCount->stock_in_store_room_unit == 'units') {
                                        $storeRoomUnits = $stockCount->stock_in_store_room;
                                        $storeRoomCases = 0;
                                    } elseif ($stockCount->stock_in_store_room_unit == 'Cases' || $stockCount->stock_in_store_room_unit == 'cases') {
                                        $storeRoomCases = $stockCount->stock_in_store_room;
                                        $storeRoomUnits = 0;
                                    } else {
                                        $storeRoomUnits = 0;
                                        $storeRoomCases = 0;
                                    }

                                    $totalStock = $shelfUnits + $packedUnits + $storeRoomUnits;
                                    $totalStockCases = $shelfCases + $packedCases + $storeRoomCases;
                                @endphp
                                <tr>
                                    <td class="tdclass">
                                        @php
                                            $date = explode(' ', $stockCount->created_at);
                                        @endphp
                                        {{ $date[0] }}
                                    </td>
                                    <td class="tdclass">{{ $stockCount->store->name_of_store }}</td>
                                    <td class="tdclass">
                                        {{ $stockCount->storeLocation->location }}
                                    </td>
                                    <td class="tdclass">{{ $stockCount->category->category }}</td>
                                    <td class="tdclass">{{ $stockCount->product->product_name }}</td>
                                    <td class="tdclass">{{ $stockCount->companyUser->user->name }}</td>
                                    <td class="tdclass">{{ $totalStock }}</td>
                                    <td class="tdclass">{{ $totalStockCases }}</td>
									<td class="tdclass"></td>
									<td class="tdclass"></td>
									<td class="tdclass"></td>
									<td class="tdclass"></td>

                                    @php
                                        $sumTotalStock += $totalStock;
                                        $sumTotalStockCases += $totalStockCases;
                                    @endphp
                                </tr>
                                @php
                                    array_push($chartStockArray, ['stock' => $totalStock, 'date' => $date[0], 'stockCases' => $totalStockCases]);
                                @endphp
                            @endforeach

                        @endif
                        @if ($outOfStockData != null)
                            @foreach ($outOfStockData as $outOfStock)
                                <tr>
									<td class="tdclass">
                                        @php
                                            $date = explode(' ', $outOfStock->created_at);
                                        @endphp
                                        {{ $date[0] }}
                                    </td>
									<td class="tdclass">{{ $outOfStock->store->name_of_store }}</td>
									<td class="tdclass">
                                        {{ $outOfStock->storeLocation->location }}
                                    </td>
									<td class="tdclass">{{ $outOfStock->category->category }}</td>
									<td class="tdclass">{{ $outOfStock->product->product_name }}</td>
									<td class="tdclass">{{ $outOfStock->companyUser->user->name }}</td>
									<td class="tdclass"></td>
									<td class="tdclass"></td>
									
                                   
                                    <td class="tdclass">{{ $outOfStock->store->name_of_store }}</td>
                                    
                                    
                                    <td class="tdclass">{{ $outOfStock->product->product_name }}</td>
                                    

									<td class="tdclass"></td>
									<td class="tdclass"></td>

                                </tr>
                            @endforeach
                        @endif
                        @if (!$productExpiryTrackerData->isEmpty())
                            @foreach ($productExpiryTrackerData as $productExpiryTracker)
                                <tr>
									<td class="tdclass">
                                        @php
                                            $date = explode(' ', $productExpiryTracker->created_at);
                                        @endphp
                                        {{ $date[0] }}
                                    </td>
									<td class="tdclass">{{ $productExpiryTracker->store->name_of_store }}</td>
									<td class="tdclass">
                                        {{ $productExpiryTracker->storeLocation->location }}
                                    </td>
									<td class="tdclass">{{ $productExpiryTracker->category->category }}</td>
									<td class="tdclass">{{ $productExpiryTracker->product->product_name }}</td>
									<td class="tdclass">{{ $productExpiryTracker->companyUser->user->name }}</td>
									<td class="tdclass"></td>
									<td class="tdclass"></td>
									<td class="tdclass"></td>
									<td class="tdclass"></td>
                                    
                                    <td class="tdclass">{{ $productExpiryTracker->store->name_of_store }}</td>
									<td class="tdclass"></td>

                                    
                                    
                                  
                                   
                                </tr>
                            @endforeach
                        @endif

						@if (!$uniqueServicedStoreLocation->isEmpty())
						@foreach ($uniqueServicedStoreLocation as $merchandiserLocation)
							<tr>
								<td class="tdclass">
									@php
										$date = explode(' ', $merchandiserLocation->created_at);
									@endphp
									{{ $date[0] }}
								</td>
								<td  class="tdclass">{{$merchandiserLocation->store($merchandiserLocation->store_id)->name_of_store}}</td>
                                <td  class="tdclass">{{($merchandiserLocation->store_location($merchandiserLocation->store_location_id)->location)??null}}</td>
								<td class="tdclass"></td>
								<td class="tdclass"></td>
								<td class="tdclass">{{ $merchandiserLocation->companyUser->user->name }}</td>
								<td class="tdclass"></td>
								<td class="tdclass"></td>
								<td class="tdclass"></td>
								<td class="tdclass"></td>
								
								<td  class="tdclass">{{$merchandiserLocation->store($merchandiserLocation->store_id)->name_of_store}}</td>
                                <td  class="tdclass">{{($merchandiserLocation->store_location($merchandiserLocation->store_location_id)->location)??null}}</td>

							   
							</tr>
						@endforeach
						@endif

                    </tbody>
                </table>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('.select2').select2();
            });
        </script>

        {{-- <script>
 

    function initMap() {
        const map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 37.7749, lng: -122.4194 }, // Example: San Francisco coordinates
        zoom: 3 // Adjust the zoom level as needed
        });

        // Define an array of locations with latitude and longitude
        const locations = [
            { lat: 40.7128, lng: -74.0060 },  // New York, NY
            { lat: 34.0522, lng: -118.2437 }, // Los Angeles, CA
            { lat: 41.8781, lng: -87.6298 },  // Chicago, IL
            { lat: 29.7604, lng: -95.3698 },  // Houston, TX
            { lat: 33.4484, lng: -112.0740 }, // Phoenix, AZ
            { lat: 41.2524, lng: -95.9980 },  // Omaha, NE
            { lat: 37.7749, lng: -122.4194 }, // San Francisco, CA
            { lat: 32.7767, lng: -96.7970 },  // Dallas, TX
            { lat: 39.7392, lng: -104.9903 }, // Denver, CO
            { lat: 35.2271, lng: -80.8431 },  // Charlotte, NC
            { lat: 42.3601, lng: -71.0589 },  // Boston, MA
            { lat: 47.6062, lng: -122.3321 }, // Seattle, WA
            { lat: 37.3382, lng: -121.8863 }, // San Jose, CA
            { lat: 33.7490, lng: -84.3880 },  // Atlanta, GA
            { lat: 30.2672, lng: -97.7431 },  // Austin, TX
            { lat: 38.8951, lng: -77.0364 },  // Washington, D.C.
            { lat: 42.3314, lng: -83.0458 },  // Detroit, MI
            { lat: 46.6034, lng: -120.3226 }, // Yakima, WA
            { lat: 38.6270, lng: -90.1994 },  // St. Louis, MO
            { lat: 44.9778, lng: -93.2650 },  // Minneapolis, MN
            { lat: 37.3541, lng: -121.9552 }, // San Jose, CA
            { lat: 38.0293, lng: -78.4767 },  // Charlottesville, VA
            { lat: 33.7774, lng: -117.8726 }, // Riverside, CA
            { lat: 38.5805, lng: -121.4944 }, // Sacramento, CA
            { lat: 40.4406, lng: -79.9959 },  // Pittsburgh, PA
            { lat: 27.9506, lng: -82.4572 },  // Tampa, FL
            { lat: 35.1495, lng: -90.0490 },  // Memphis, TN
            { lat: 34.7465, lng: -92.2896 },  // Little Rock, AR
            { lat: 30.2672, lng: -97.7431 },  // Austin, TX
            { lat: 37.7749, lng: -122.4194 }  // San Francisco, CA
        ];

        // Loop through the locations and create a marker for each
        for (const location of locations) {
        new google.maps.Marker({
            position: location,
            map: map
        });
        }
    }
</script> --}}
        {{-- <script>
  // Call the initMap function after the Google Maps API is loaded
  google.maps.event.addDomListener(window, 'load', initMap);
</script> --}}
        {{-- <script>
    // Initialize the map
    const map = L.map('map').setView([41.2524, -95.9980], 4); // Set the initial center and zoom level

    // Add a tile layer for the map (you can choose different tile providers)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Define the locations and popup content for your pins
    const locations = [
        { lat: 40.7128, lng: -74.0060, title: 'New York, NY', description: 'The Big Apple' },  // New York, NY
        { lat: 34.0522, lng: -118.2437, title: 'New York, NY', description: 'The Big Apple' }, // Los Angeles, CA
        { lat: 41.8781, lng: -87.6298, title: 'New York, NY', description: 'The Big Apple' },  // Chicago, IL
        { lat: 29.7604, lng: -95.3698, title: 'New York, NY', description: 'The Big Apple' },  // Houston, TX
        { lat: 33.4484, lng: -112.0740, title: 'New York, NY', description: 'The Big Apple' }, // Phoenix, AZ
        { lat: 41.2524, lng: -95.9980, title: 'New York, NY', description: 'The Big Apple' },  // Omaha, NE
        { lat: 37.7749, lng: -122.4194, title: 'New York, NY', description: 'The Big Apple' }, // San Francisco, CA
        { lat: 32.7767, lng: -96.7970, title: 'New York, NY', description: 'The Big Apple' },  // Dallas, TX
        { lat: 39.7392, lng: -104.9903, title: 'New York, NY', description: 'The Big Apple' }, // Denver, CO
        { lat: 35.2271, lng: -80.8431, title: 'New York, NY', description: 'The Big Apple' },  // Charlotte, NC
        { lat: 42.3601, lng: -71.0589, title: 'New York, NY', description: 'The Big Apple' },  // Boston, MA
        { lat: 47.6062, lng: -122.3321, title: 'New York, NY', description: 'The Big Apple' }, // Seattle, WA
        { lat: 37.3382, lng: -121.8863, title: 'New York, NY', description: 'The Big Apple' }, // San Jose, CA
        { lat: 33.7490, lng: -84.3880, title: 'New York, NY', description: 'The Big Apple' },  // Atlanta, GA
        { lat: 30.2672, lng: -97.7431, title: 'New York, NY', description: 'The Big Apple' },  // Austin, TX
        { lat: 38.8951, lng: -77.0364, title: 'New York, NY', description: 'The Big Apple' },  // Washington, D.C.
        { lat: 42.3314, lng: -83.0458, title: 'New York, NY', description: 'The Big Apple' },  // Detroit, MI
        { lat: 46.6034, lng: -120.3226, title: 'New York, NY', description: 'The Big Apple' }, // Yakima, WA
        { lat: 38.6270, lng: -90.1994, title: 'New York, NY', description: 'The Big Apple' },  // St. Louis, MO
        { lat: 44.9778, lng: -93.2650, title: 'New York, NY', description: 'The Big Apple' },  // Minneapolis, MN
        { lat: 37.3541, lng: -121.9552, title: 'New York, NY', description: 'The Big Apple' }, // San Jose, CA
        { lat: 38.0293, lng: -78.4767, title: 'New York, NY', description: 'The Big Apple' },  // Charlottesville, VA
        { lat: 33.7774, lng: -117.8726, title: 'New York, NY', description: 'The Big Apple' }, // Riverside, CA
        { lat: 38.5805, lng: -121.4944, title: 'New York, NY', description: 'The Big Apple' }, // Sacramento, CA
        { lat: 40.4406, lng: -79.9959, title: 'New York, NY', description: 'The Big Apple' },  // Pittsburgh, PA
        { lat: 27.9506, lng: -82.4572, title: 'New York, NY', description: 'The Big Apple' },  // Tampa, FL
        { lat: 35.1495, lng: -90.0490, title: 'New York, NY', description: 'The Big Apple' },  // Memphis, TN
        { lat: 34.7465, lng: -92.2896, title: 'New York, NY', description: 'The Big Apple' },  // Little Rock, AR
        { lat: 30.2672, lng: -97.7431, title: 'New York, NY', description: 'The Big Apple' },  // Austin, TX
        { lat: 37.7749, lng: -122.4194, title: 'New York, NY', description: 'The Big Apple' }  // San Francisco, CA
    ];

     // Loop through the locations and add markers with tooltips to the map
     locations.forEach(location => {
            const marker = L.marker([location.lat, location.lng]).addTo(map);
            marker.bindTooltip(`<strong>${location.title}</strong><br>${location.description}`);
        });
</script> --}}

        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

        <script>
            // Initialize the map
            const map = L.map('map').setView([18.1096, -77.2975], 9); // Set the initial center and zoom level for Jamaica

            // Add a tile layer for the map
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Define the locations and popup content for Jamaican parishes
            const parishes = [{
                    name: 'Clarendon',
                    lat: 17.9649,
                    lng: -77.2452
                },
                {
                    name: 'Hanover',
                    lat: 18.4054,
                    lng: -78.0572
                },
                {
                    name: 'Kingston',
                    lat: 17.9702,
                    lng: -76.7936
                },
                {
                    name: 'Manchester',
                    lat: 18.0362,
                    lng: -77.5071
                },
                {
                    name: 'Portland',
                    lat: 18.1597,
                    lng: -76.5346
                },
                {
                    name: 'St. Andrew',
                    lat: 18.0340,
                    lng: -76.7495
                },
                {
                    name: 'St. Ann',
                    lat: 18.1096,
                    lng: -77.2975
                },
                {
                    name: 'St. Catherine',
                    lat: 17.9641,
                    lng: -76.8674
                },
                {
                    name: 'St. Elizabeth',
                    lat: 18.1352,
                    lng: -77.2177
                },
                {
                    name: 'St. James',
                    lat: 18.4153,
                    lng: -77.0810
                },
                {
                    name: 'St. Mary',
                    lat: 18.1026,
                    lng: -76.9873
                },
                {
                    name: 'St. Thomas',
                    lat: 17.9394,
                    lng: -76.7952
                },
                {
                    name: 'Trelawny',
                    lat: 18.3554,
                    lng: -77.5848
                },
                {
                    name: 'Westmoreland',
                    lat: 18.1226,
                    lng: -77.9710
                }
            ];
            // document.getElementById('total_stock_count_cases').innerHTML = {{ $sumTotalStockCases }};
            //  document.getElementById('total_stock_count').innerHTML = {{ $sumTotalStock }};

            // Loop through the parishes and add markers with tooltips to the map
            parishes.forEach(parish => {
                const marker = L.marker([parish.lat, parish.lng]).addTo(map);
                marker.bindTooltip(`<strong>${parish.name}</strong>`);
            });
        </script>



        <script>
            var startDate = 0;
            var endDate = 0;
            var allStores = {!! json_encode($storesArr) !!};
            var allUniqueLocations = {!! json_encode($locationArr) !!};
            console.log(allUniqueLocations);
            var sumTotalStockUnit = {!! json_encode($sumTotalStock) !!};
            var sumTotalStockCases = {!! json_encode($sumTotalStockCases) !!};

            var storeServiced = {!! json_encode($uniqueServicedStoreLocation) !!};
            var Stores = {!! json_encode($stores) !!};

            var outOfStockData = {!! json_encode($outOfStockData) !!};
            var products = {!! json_encode($products) !!};

            var productExpiryTrackerData = {!! json_encode($productExpiryTrackerData) !!};



            var graphFormat = 'weeks';
            var graphUnit = 'Unit';

            var labels = [];

            var convertedToChartData = {{ Js::from($chartStockArray) }};
        </script>


        <script src="{{ asset('assets/js/businessOverviewDatatableAndChart.js') }}"></script>

        {{-- @vite(['resources/js/chart.js']) --}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
