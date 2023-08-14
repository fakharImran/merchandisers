@extends('layouts.app')

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
                      <form method="POST" action="{{route("store.update", $id)}}">
                        @method('PUT')
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
                                            <option value disabled>Select Company</option>
                                            @if($companies!=null)
                                            @foreach($companies as $comp)
                                            <option {{ $comp['id'] == $store['company_id'] ? 'selected' : '' }} value="{{ $comp['id'] }}">{{ $comp['company'] }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                  </div>
                                  <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Name of Store') }} <span class="text-danger">*</span></label>
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
                                            <label>{{ __('Location') }} <span class="text-danger">*</span></label>
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
                                            <label>{{ __('Parish') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="user_select_form">
                                          <select id="parish" name="parish" class="form-select" required>
                                            <option {{($store['parish']=='')? "selected":""}} value disabled>Select Parish</option>
                                            <option {{($store['parish']=='Clarendon')? "selected":""}} value="Clarendon">Clarendon</option>
                                            <option {{($store['parish']=='Hanover')? "selected":""}} value="Hanover">Hanover</option>
                                            <option {{($store['parish']=='Kingston')? "selected":""}} value="Kingston">Kingston</option>
                                            <option {{($store['parish']=='Manchester')? "selected":""}} value="Manchester">Manchester</option>
                                            <option {{($store['parish']=='Portland')? "selected":""}} value="Portland">Portland</option>
                                            <option {{($store['parish']=='St. Andrew')? "selected":""}} value="St. Andrew">St. Andrew</option>
                                            <option {{($store['parish']=='St. Ann')? "selected":""}} value="St. Ann">St. Ann</option>
                                            <option {{($store['parish']=='St. Catherine')? "selected":""}} value="St. Catherine">St. Catherine</option>
                                            <option {{($store['parish']=='St. Elizabeth')? "selected":""}} value="St. Elizabeth">St. Elizabeth</option>
                                            <option {{($store['parish']=='St. James')? "selected":""}} value="St. James">St. James</option>
                                            <option {{($store['parish']=='St. Mary')? "selected":""}} value="St. Mary">St. Mary</option>
                                            <option {{($store['parish']=='St. Thomas')? "selected":""}} value="St. Thomas">St. Thomas</option>
                                            <option {{($store['parish']=='Trelawny')? "selected":""}} value="Trelawny">Trelawny</option>
                                            <option {{($store['parish']=='Westmoreland')? "selected":""}} value="Westmoreland">Westmoreland</option>
                                          </select>

                                      </div>
                                    </div>
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Channel') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_select_form">
                                      <select id="channel" class="form-select" required name="channel">
                                        <option {{($store['channel']=='')? "selected":""}} value disabled>Select Channel</option>
                                        <option {{($store['channel']=='Bar')? "selected":""}} value="Bar">Bar</option>
                                        <option {{($store['channel']=='Pharmacy')? "selected":""}} value="Pharmacy">Pharmacy</option>
                                        <option {{($store['channel']=='Supermarket')? "selected":""}} value="Supermarket">Supermarket</option>
                                        <option {{($store['channel']=='Wholesale')? "selected":""}} value="Wholesale">Wholesale</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="user_btn_list">
                                    <div class="user_btn myborder">
                                        <button type="submit" class=" user_btn_style submit  ">
                                         <img src="{{asset('assets/images/save.png')}}" alt="->"> Save Changes
                                        </button>
                                    </div>
                                    {{-- <div class="user_btn  text-secondary">
                                        <div  class="user_btn_style">
                                         <img src="{{asset('assets/images/next.png')}}" alt="->"> Submit
                                        </div>
                                    </div> --}}
                                    {{-- <div class="user_btn  myborder" >
                                        <a href="{{ route('store-delete',   $id) }}" class="user_btn_style"  style="color: black; border:none;" >
                                        <img src="{{asset('assets/images/del_user.png')}}"> Delete User
                                        
                                        </a>
                                    </div> --}}
                                    <div class="user_btn myborder" onclick="window.history.go(-1); return false;" >
                                        <button  class="user_btn_style submit" > <img src="{{asset('assets/images/close.png')}}"> Close</button>
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