@extends('layouts.app')

@section('sidebar-content')

<nav id="sidebar" class=" navbar-expand-md navbar-light bg-white  " style="height: 97vh;">
    <div class="container">
            <div class="sidebar-header text-center">
                <img class="LinkNewLogoPng"  src="{{asset('assets/images/logo.png')}}" />
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Company</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Store</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="#">Products</a>
                </li>
            </ul>
    </div>
</nav>

@endsection