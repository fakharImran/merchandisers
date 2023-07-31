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
                        <h3>Company</h3>
                      </div>
                        <form method="POST" action="{{ route('company.store') }}">
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
                            <div class="">
                              <div class="user_form_box">
                                  <div class="form_title">
                                      <h4>General</h4>
                                  </div>
                                  <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Company') }}:</label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="text" class="form-control" id="company_name" name="company_name" required autocomplete="company_name" autofocus  placeholder="Company Name">
                                      </div>
                                  </div>

                                  <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Company Code') }}:</label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="text" min="1000" max="9999" pattern="\d{4}" maxlength="4"  class="form-control" id="company_code" name="company_code" required autocomplete="company_code" autofocus  placeholder="4-digit code only (e.g., 1234)">
                                        @error('company_code')
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
                                         <img src="{{asset('assets/images/next.png')}}" alt="->"> Submit
                                        </button>
                                      </div>
  
                                      {{-- <div class="user_btn  text-secondary" >
                                          <div class="user_btn_style"> <img src="{{asset('assets/images/del_user.png')}}"> Delete User</div>
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