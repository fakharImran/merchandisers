@extends('layouts.app')

@section("top_links")
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

@endsection

@section("bottom_links")

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function(){
        // alert("Ok");
        // $(".datatable").Datatable();
        let table = new DataTable('.datatable');
        
        table.on('click', 'tbody tr', function () {
            let data = table.row(this).data();
        
            // alert('You clicked on ' + data[0] + "'s row");
        });
    })
</script>
@endsection

@section('content')

<div class="row">
    <div class="col-md-9 col-6" style="margin: 1px auto;">
        <div class="Company" style="width: 100%; height: 100%; color: #2297C3; font-size: 22px; font-family: Inter; font-weight: 700; line-height: 39px; word-wrap: break-word">Company
        </div>
        
    </div>
    <div class="col-md-1 col-3"  style="margin: 1px auto;">
        <div class="add_btn">
            <a href="{{ route('company.create') }}"> <span>+</span>New</a>
        </div>
    </div>
</div>


 {{-- <div class="table_btn_list">

    <div class="add_btn">
        <a href="{{ route('company.create') }}"> <span>+</span>New</a>
    </div>
 </div> --}}
   {{-- <div class="select_field">
        <select>
            <option>Select Company</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
    </div>

    <div class="totla_company">
        <p>6 records in total</p>
    </div>
    <div class="pagination_links">
        <a href=""><img src="assets/images/privious.png"> 1 </a>
        <a href=""> 30 <img src="assets/images/next.png"></a>
    </div>

    <div class="totla_num">
        <a href=""> 30 </a>
    </div>

    <div class="refresh_btn">
        <a href=""><img src="assets/images/ref.png"> Refresh </a>
    </div>

    <div class="filter_btn">
        <a href=""><img src="assets/images/filter.png"> Filter </a>
    </div>

    <div class="search_bar">
        <div class="search_field">
            <input type="text" placeholder="">
        </div>
        <div class="search_btn">
            <a href=""> <img src="assets/images/search.png"> </a>
        </div>
    </div>
</div> --}}

<div class="row">
    
    <div class="col-11" style="margin: 1px auto; overflow: scroll;">
        <table class="table  datatable table-bordered table-hover table-responsive">
            <thead>
                <tr>
                  <th class="thclass" scope="col">#</th>
                  <th class="thclass"  scope="col">Company</th>
                  <th class="thclass"  scope="col">CompanyCode</th>
                  <th class="thclass"  scope="col">DateModified</th>
                  <th class="thclass"  scope="col">DateCreated</th>
                  <th class="thclass"  scope="col">Action</th>
                </tr>
              </thead>
              @php
                  $i=1;
              @endphp
            <tbody>
                @if($companies!=null)
                @foreach ($companies as $company)
                    <tr>
                        <td class="tdclass">{{ $i}}</td>
                        <td class="tdclass">{{ $company['company'] }}</td>
                        <td class="tdclass">{{ $company['code'] }}</td>
                        <td class="tdclass">{{ $company['updated_at'] }}</td>
                        <td class="tdclass">{{ $company['created_at'] }}</td>
                        <td>
                                    
                            <form action={{ route('company.destroy', $company['id']) }} method="post">
                                @csrf
                                @method('DELETE')
                              
                                <button class="submit" style="background: transparent;"><i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
                                </button>
                                <a href="{{ route('company-edit',  [$i, $company['id']]) }}"><i class="fa fa-pencil-square-o text-secondary" aria-hidden="true"></i>
                                </a>
                            </form>
                           
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection