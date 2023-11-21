@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script>
    /**
     * @license
     * Copyright 2019 Google LLC. All Rights Reserved.
     * SPDX-License-Identifier: Apache-2.0
     */
    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: 40.749933,
                lng: -73.98633
            },
            zoom: 13,
            mapTypeControl: false,
        });
        const card = document.getElementById("pac-card");
        const input = document.getElementById("pac-input");
        const biasInputElement = document.getElementById("use-location-bias");
        const strictBoundsInputElement =
            document.getElementById("use-strict-bounds");
        const options = {
            fields: ["formatted_address", "geometry", "name"],
            strictBounds: false,
            types: ["establishment"],
        };

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(card);

        const autocomplete = new google.maps.places.Autocomplete(
            input,
            options
        );

        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        autocomplete.bindTo("bounds", map);

        const infowindow = new google.maps.InfoWindow();
        const infowindowContent = document.getElementById("infowindow-content");

        infowindow.setContent(infowindowContent);

        const marker = new google.maps.Marker({
            map,
            anchorPoint: new google.maps.Point(0, -29),
        });

        autocomplete.addListener("place_changed", () => {
            infowindow.close();
            marker.setVisible(false);

            const place = autocomplete.getPlace();

            if (!place.geometry || !place.geometry.location) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert(
                    "No details available for input: '" + place.name + "'"
                );
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
            infowindowContent.children["place-name"].textContent = place.name;
            infowindowContent.children["place-address"].textContent =
                place.formatted_address;
            infowindow.open(map, marker);
        });

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
            const radioButton = document.getElementById(id);

            radioButton.addEventListener("click", () => {
                autocomplete.setTypes(types);
                input.value = "";
            });
        }

        setupClickListener("changetype-all", []);
        setupClickListener("changetype-address", ["address"]);
        setupClickListener("changetype-establishment", ["establishment"]);
        setupClickListener("changetype-geocode", ["geocode"]);
        setupClickListener("changetype-cities", ["(cities)"]);
        setupClickListener("changetype-regions", ["(regions)"]);
        biasInputElement.addEventListener("change", () => {
            if (biasInputElement.checked) {
                autocomplete.bindTo("bounds", map);
            } else {
                // User wants to turn off location bias, so three things need to happen:
                // 1. Unbind from map
                // 2. Reset the bounds to whole world
                // 3. Uncheck the strict bounds checkbox UI (which also disables strict bounds)
                autocomplete.unbind("bounds");
                autocomplete.setBounds({
                    east: 180,
                    west: -180,
                    north: 90,
                    south: -90,
                });
                strictBoundsInputElement.checked = biasInputElement.checked;
            }

            input.value = "";
        });
        strictBoundsInputElement.addEventListener("change", () => {
            autocomplete.setOptions({
                strictBounds: strictBoundsInputElement.checked,
            });
            if (strictBoundsInputElement.checked) {
                biasInputElement.checked = strictBoundsInputElement.checked;
                autocomplete.bindTo("bounds", map);
            }

            input.value = "";
        });
    }

    window.initMap = initMap;
</script>
{{-- <style>
    /**
     * @license
     * Copyright 2019 Google LLC. All Rights Reserved.
     * SPDX-License-Identifier: Apache-2.0
     */
    /*
     * Always set the map height explicitly to define the size of the div element
     * that contains the map.
     */
    #map {
        height: 50%;
    }

    /*
     * Optional: Makes the sample page fill the window.
     */
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
    }

    #infowindow-content .title {
        font-weight: bold;
    }

    #infowindow-content {
        display: none;
    }

    #map #infowindow-content {
        display: inline;
    }

    .pac-card {
        background-color: #fff;
        border: 0;
        border-radius: 2px;
        box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
        margin: 10px;
        padding: 0 0.5em;
        font: 400 18px Roboto, Arial, sans-serif;
        overflow: hidden;
        font-family: Roboto;
        padding: 0;
    }

    #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
    }

    .pac-controls {
        display: inline-block;
        padding: 5px 11px;
    }

    .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }

    #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
    }
</style> --}}
@section('content')

    <div class="site-wrapper">
        <div class="admin_form">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="admin_box">

                            <div class="tab_title">
                                <h3>Store</h3>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('store.store') }}">
                                @csrf

                                <div class="">
                                    <div class="user_form_box">
                                        <div class="form_title">
                                            <h4>General</h4>
                                        </div>
                                        <div class="user_form_content">
                                            <div class="label">
                                                <label>{{ __('Company') }} <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="user_select_form">
                                                <select id="company" name="company_id" class="form-select" required>
                                                    <option value selected disabled>Select Company</option>
                                                    @if ($companies != null)
                                                        @foreach ($companies as $company)
                                                            <option value="{{ $company['id'] }}">{{ $company['company'] }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="user_form_content">
                                            <div class="label">
                                                <label>{{ __('Name of Store') }} <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="user_input_form">
                                                <input type="text" required class="form-control" id="name_of_store"
                                                    name="name_of_store" required autocomplete="name_of_store" autofocus
                                                    placeholder="">
                                                @error('name_of_store')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="user_form_content">
                                            <div class="label">
                                                <label>{{ __('Parish') }} <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="user_select_form">
                                                <select id="parish" name="parish[]" class="form-select"
                                                    placeholder="Select Parish" required multiple>
                                                    <option value="" disabled selected>Select Parish</option>
                                                    <option value="Clarendon">Clarendon</option>
                                                    <option value="Hanover">Hanover</option>
                                                    <option value="Kingston">Kingston</option>
                                                    <option value="Manchester">Manchester</option>
                                                    <option value="Portland">Portland</option>
                                                    <option value="St. Andrew">St. Andrew</option>
                                                    <option value="St. Ann">St. Ann</option>
                                                    <option value="St. Catherine">St. Catherine</option>
                                                    <option value="St. Elizabeth">St. Elizabeth</option>
                                                    <option value="St. James">St. James</option>
                                                    <option value="St. Mary">St. Mary</option>
                                                    <option value="St. Thomas">St. Thomas</option>
                                                    <option value="Trelawny">Trelawny</option>
                                                    <option value="Westmoreland">Westmoreland</option>
                                                </select>
                                                @error('parish')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="user_form_content">
                                            <div class="label">
                                                <label>{{ __('Search Location') }}</label>
                                            </div>
                                            <div class=" user_input_form d-flex ">

                                                <div class="col-9">
                                                    <input id="pac-input" class="form-control" type="text"
                                                        placeholder="Enter a location" />
                                                </div>
                                                <div class="col-3">
                                                    <div id="map" style="display: none"></div>
                                                    <div class=" user_btn myborder ">
                                                        <div class=" user_btn_style submit clickable-element"
                                                            onclick="addRepeaterItem()">Add Location</div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        {{-- <div class="user_form_content">
                                            <div class="pac-card" id="pac-card">
                                                <div>
                                                    <div id="title">Autocomplete search</div>
                                                    <div id="type-selector" class="pac-controls">
                                                        <input type="radio" name="type" id="changetype-all"
                                                            checked="checked" />
                                                        <label for="changetype-all">All</label>

                                                        <input type="radio" name="type" id="changetype-establishment" />
                                                        <label for="changetype-establishment">establishment</label>

                                                        <input type="radio" name="type" id="changetype-address" />
                                                        <label for="changetype-address">address</label>

                                                        <input type="radio" name="type" id="changetype-geocode" />
                                                        <label for="changetype-geocode">geocode</label>

                                                        <input type="radio" name="type" id="changetype-cities" />
                                                        <label for="changetype-cities">(cities)</label>

                                                        <input type="radio" name="type" id="changetype-regions" />
                                                        <label for="changetype-regions">(regions)</label>
                                                    </div>
                                                    <br />
                                                    <div id="strict-bounds-selector" class="pac-controls">
                                                        <input type="checkbox" id="use-location-bias" value="" checked />
                                                        <label for="use-location-bias">Bias to map viewport</label>

                                                        <input type="checkbox" id="use-strict-bounds" value="" />
                                                        <label for="use-strict-bounds">Strict bounds</label>
                                                    </div>
                                                </div>
                                                <div id="pac-container">
                                                    <input id="pac-input" class="form-control" type="text" placeholder="Enter a location" />
                                                </div>
                                            </div>
                                            <div id="map"></div>
                                            <div id="infowindow-content">
                                                <span id="place-name" class="title"></span><br />
                                                <span id="place-address"></span>
                                            </div>
                                        </div> --}}



                                        <div class="user_form_content">
                                            <div class="label">
                                                <label>{{ __('Location') }} <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="user_input_form row " id="repeater-container">
                                                {{-- <div class="col-6 p-1">
                                                    <div class="w-100">
                                                        <div class="user_btn myborder"
                                                            style="border: 1px solid #37A849 !important">
                                                            <input type="text" required
                                                                class="border-none user_input_form_90 height-30px"
                                                                name="locations[]" required autocomplete="location"
                                                                autofocus placeholder="" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="text-danger cross-btn clickable-element p-1"
                                                        onclick="removeRepeaterItem(this)">x</div>

                                                </div> --}}
                                                <!-- This is the container for the repeater items -->
                                            </div>



                                        </div>

                                        

                                        <div class="user_form_content">
                                            <div class="label">
                                                <label>{{ __('Channel') }} <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="user_select_form">
                                                <select id="channel" class="form-select" name="channel" required>
                                                    <option value disabled selected>Select Channel</option>
                                                    <option value="Bar">Bar</option>
                                                    <option value="Pharmacy">Pharmacy</option>
                                                    <option value="Supermarket">Supermarket</option>
                                                    <option value="Wholesale">Wholesale</option>
                                                </select>
                                                @error('channel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="user_btn_list">
                                            {{-- <div class="user_btn text-secondary" >
                                        <div class="user_btn_style"> <img src="{{asset('assets/images/save.png')}}"> Save Changes</div>
                                    </div> --}}
                                            <div class="user_btn myborder">
                                                <button type="submit" class=" user_btn_style submit ">
                                                    <img src="{{ asset('assets/images/next.png') }}" alt="->">
                                                    Submit
                                                </button>
                                            </div>

                                            {{-- <div class="user_btn  text-secondary" >
                                        <div class="user_btn_style"> <img src="{{asset('assets/images/del_user.png')}}"> Delete User</div>
                                    </div> --}}

                                            <div class="user_btn myborder" onclick="window.history.go(-1); return false;">
                                                <button class="user_btn_style submit"> <img
                                                        src="{{ asset('assets/images/close.png') }}"> Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAob5VEgwOWzV91Q7y4ZncX5XrM33Fa-eo&callback=initMap&libraries=places&v=weekly"
    defer></script>
<script>
    function addRepeaterItem() {
        
        const searchInput = document.getElementById('pac-input');
       
        const repeaterContainer = document.getElementById('repeater-container');
        const newItem = document.createElement('div');
        newItem.classList.add("col-6");
        newItem.classList.add("p-1");
        newItem.innerHTML = `
        <div class="w-100">
            <div class="user_btn myborder" style="border: 1px solid #37A849 !important">
                <input type="text" required class="border-none user_input_form_90 height-30px" name="locations[]" required autocomplete="location" autofocus placeholder="" readonly value="` + searchInput.value + `">
            </div>
        </div>
        <div  class="text-danger cross-btn clickable-element p-1" onclick="removeRepeaterItem(this)">x</div>
       
    `;
        // user_input_form
        repeaterContainer.appendChild(newItem);

        console.log(searchInput.value);

        searchInput.value = "";
    }

    function removeRepeaterItem(button) {
        button.parentElement.remove();
    }
</script>
<script>
    $(document).ready(function() {
        $('#parish').select2();
    });
</script>
