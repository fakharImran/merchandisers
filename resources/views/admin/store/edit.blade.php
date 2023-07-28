@extends('layouts.app')

@section('content')
{{-- 
<form action="{{ route('company.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="company_name">Company Name</label>
      <input type="text" class="form-control" id="company_name" name="company_name">
    </div>
    <div class="form-group">
      <label for="code">Code</label>
      <input type="number" class="form-control" id="code" name="code">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form> --}}



  <div class="site-wrapper">
    <div class="admin_form">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="admin_box">

                      <div class="tab_title">
                        <h3>Store</h3>
                      </div>
                      <form method="POST" action="{{route("store.update", $id)}}">
                        @method('PUT')
                        @csrf
                            {{-- <div class="admin_form">
                                <div class="admin_form_content">
                                    <div class="admin_email_label">
                                        <label for="email" class=" ">{{ __('Company Name') }}</label>
                                    </div>
                                    <div class="admin_email">
                                        <input type="text" class="form-control" id="company_name" name="company_name" required autocomplete="company_name" autofocus  placeholder="Company Name">
                                    </div>
                                </div>

                                <div class="admin_form_content">
                                  <div class="admin_email_label">
                                      <label for="email" class=" ">{{ __('Company Code') }}</label>
                                  </div>
                                  <div class="admin_email">
                                      <input type="text" class="form-control" id="company_code" name="company_code" required autocomplete="company_code" autofocus  placeholder="Company Code">
                                  </div>
                              </div>

                                <div class="submit_box_content">
                                   
                                    <button style="border: none; background-color: none; outline: none;" type="submit" class="submit_btn">
                                        {{ __('Sign in') }}
                                    </button>
                                </div>
                            </div> --}}
                            {{-- {{dd($companies)}} --}}
                            <div class="">
                              <div class="user_form_box">
                                  <div class="form_title">
                                      <h4>General</h4>
                                  </div>
                                  <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Company') }}:</label>
                                      </div>
                                      <div class="user_select_form">
                                        <select id="company" name="company" required>
                                            <option value="">Select Company</option>
                                            @if($companies!=null)
                                            @foreach($companies as $company)
                                            {{-- <option value="{{$company['company']}}">{{$company['company']}}</option> --}}
                                            <option {{ $company['company'] == $store['company'] ? 'selected' : '' }} value="{{ $store['company'] }}">{{ $store['company'] }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                  </div>
                                  <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Name of Store') }}:</label>
                                    </div>
                                    <div class="user_input_form">
                                        <input type="text" required value="{{$store['name_of_store']}}"  class="form-control" id="name_of_store" name="name_of_store" required autocomplete="name_of_store" autofocus  placeholder="">
                                    @error('name_of_store')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                </div>
                                    <div class="user_form_content">
                                        <div class="label">
                                            <label>{{ __('Location') }}:</label>
                                        </div>
                                        <div class="user_input_form">
                                            <input type="text" required class="form-control"  value="{{$store['location']}}" id="location" name="location" required autocomplete="location" autofocus  placeholder="">
                                        @error('location')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        </div>
                                    </div>
                                    <div class="user_form_content">
                                        <div class="label">
                                            <label>{{ __('Parish') }}:</label>
                                        </div>
                                        <div class="user_select_form">
                                          <select id="parish" name="parish" required>
                                              <option  {{($store['parish']=='')? "selected":""}} value="">Select Parish</option>
                                              <option  {{($store['parish']=='Horace')? "selected":""}} value="Horace">Horace</option>
                                              <option  {{($store['parish']=='John')? "selected":""}} value="John">John</option>
                                              <option  {{($store['parish']=='Billing')? "selected":""}} value="Billing">Billing</option>
                                              <option  {{($store['parish']=='All Modules')? "selected":""}} value="All Modules">All Modules</option>
                                              <option  {{($store['parish']=='Type Approval')? "selected":""}} value="Type Approval">Type Approval</option>
                                              <option  {{($store['parish']=='Maritime')? "selected":""}} value="Maritime">Maritime</option>
                                          </select>
                                      </div>
                                    </div>
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Access Privileges') }}:</label>
                                    </div>
                                    <div class="user_select_form">
                                      <select id="channel" name="channel">
                                          <option {{($store['channel']=='')? "selected":""}} value="">Select Channel</option>
                                          <option {{($store['channel']=='Activated')? "selected":""}} value="Activated">Activated</option>
                                          <option {{($store['channel']=='Deactivated')? "selected":""}} value="Deactivated">Deactivated</option>
                                          <option {{($store['channel']=='Deleted')? "selected":""}} value="Deleted">Deleted</option>
                                      </select>
                                    </div>
                                </div>
                                
                                  <div class="user_btn_list">
                                      <div class="user_btn text-secondary" >
                                          <div class="user_btn_style"> <img src="{{asset('assets/images/save.png')}}"> Save Changes</div>
                                      </div>
  
                                      <div class="user_btn">
                                        <div  class="user_btn_style">
                                         <img src="{{asset('assets/images/next.png')}}" alt="->"> <button   type="submit" class="submit">Submit</button>
                                          
                                        </div>
                                      </div>
  
                                      <div class="user_btn  text-secondary" >
                                          <div class="user_btn_style"> <img src="{{asset('assets/images/del_user.png')}}"> Delete User</div>
                                      </div>
  
                                      <div class="user_btn" onclick="window.history.go(-1); return false;" >
                                          <div  class="user_btn_style" > <img src="{{asset('assets/images/close.png')}}"> Close</div>
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