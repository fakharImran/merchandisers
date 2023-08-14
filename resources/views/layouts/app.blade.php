<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" user-scalable="no">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">

    <!-- StyleSheet -->
    {{-- <link href="{{asset('assets/css/bootstrap.min_.css')}}" rel="stylesheet"> --}}
    {{-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- ICons --}}
    <link rel="stylesheet" href="{{asset('assets/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield("top_links")
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
   
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">

</head>
<body>
    <div id="app">
        @if(isset($pageConfigs))
            @guest
                <main class="">
                    @yield('content')
                </main>
            @else
                <div class="site-wrapper">
                    <div class="main_header">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3 col-0 p-0"  style="
                                    width: 20%;
                                    background: white;
                                    box-shadow: 16px 16px 30px rgba(0, 0, 0, 0.02);
                                    min-height: 100vh;
                                    overflow: hidden;">
                                    <div class="logo_image">
                                        <img src="{{asset('assets/images/logo.png')}}">
                                    </div>
                                    <div class="company_table d-flex align-items-start">
                                        <div class="tab_list_box tab_btn nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <button class="nav-link @if($pageConfigs['pageSidebar'] == 'company') active @endif" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" onclick=" window.location.href = '{{ route('company.index') }}'" role="tab" aria-controls="v-pills-home" aria-selected="true"><img class="simple_one max-width-10" src="{{asset('assets/images/company.png')}}"><img class="hover_one  max-width-10" src="{{asset('assets/images/company_hover.png')}}"> Company</button>
                                        <button class="nav-link @if($pageConfigs['pageSidebar'] == 'user') active @endif" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" onclick=" window.location.href = '{{ route('user.index') }}' " role="tab" aria-controls="v-pills-profile" aria-selected="false"><img class="simple_one  max-width-10" src="{{asset('assets/images/user.png')}}"><img class="hover_one max-width-10" src="{{asset('assets/images/user_hover.png')}}">User</button>
                                        <button class="nav-link @if($pageConfigs['pageSidebar'] == 'store') active @endif" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" onclick=" window.location.href = '{{ route('store.index') }}' " role="tab" aria-controls="v-pills-messages" aria-selected="false"><img class="simple_one max-width-10" src="{{asset('assets/images/shop.png')}}"><img class="hover_one  max-width-10" style="width:40px;" src="{{asset('assets/images/store_hover.png')}}">Store</button>
                                        <button class="nav-link @if($pageConfigs['pageSidebar'] == 'product') active @endif" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" onclick=" window.location.href = '{{ route('product.index') }}' " role="tab" aria-controls="v-pills-settings" aria-selected="false"><img class="simple_one max-width-10" src="{{asset('assets/images/product.png')}}"><img class="hover_one max-width-10" style="width:40px;"x src="{{asset('assets/images/product_hover.png')}}">Product</button>
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-md-9 col-12 p-0" style="width: 80%;">
                                    <nav class="navbar navbar-expand-md navbar-dark navbar-color " style="background-color: #1892C0;">
                                        <div class="container">
                                            <a class="navbar-brand" href="{{ url('/') }}">
                                                {{-- {{ config('app.name', 'Laravel') }} --}}
                                                {{ __('Dashboard') }}
                                            </a>
                                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                                                <span class="navbar-toggler-icon"></span>
                                            </button>
                        
                                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                                <!-- Left Side Of Navbar -->
                                                <ul class="navbar-nav me-auto">
                                                    {{-- <li class="nav-item">
                                                        <a class="nav-link" href="#">Business Overview</a>
                                                    </li> --}}
                                                </ul>
                        
                                                <!-- Right Side Of Navbar -->
                                                <ul class="navbar-nav ms-auto">
                                                    <!-- Authentication Links -->
                                                    @guest
                                                        @if (Route::has('login'))
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                                            </li>
                                                        @endif
                        
                                                        @if (Route::has('register'))
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                                            </li>
                                                        @endif
                                                    @else
                                                        <li class="nav-item dropdown">
                                                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                                {{ Auth::user()->name }}
                                                            </a>
                        
                                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                                onclick="event.preventDefault();
                                                                                document.getElementById('logout-form').submit();">
                                                                    {{ __('Logout') }}
                                                                </a>
                        
                                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                                    @csrf
                                                                </form>
                                                            </div>
                                                        </li>
                                                    @endguest
                                                </ul>
                                            </div>
                                        </div>
                                    </nav>
                                    <main class="py-4">
                                        @yield('content')
                                    </main>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endguest
        @else
            <main class="">
                @yield('content')
            </main>
        @endif
    </div>

{{-- <script src="assets/js/jquery.js"></script> --}}
{{-- <script src="assets/js/bootstrap.min_.js"></script> --}}
@yield("bottom_links")
</body>
</html>
