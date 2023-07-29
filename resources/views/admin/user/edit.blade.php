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
                        <h3>Users</h3>
                      </div>
                        <form method="POST" action="{{route("user.update", $id)}}">
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
                                        <select id="company" class="select2" name="company_id">
                                            <option value="">Select Company</option>
                                            @if($companies!=null)
                                            @foreach($companies as $comp)
                                            <option {{ $comp['id'] == $companyUser['company_id'] ? 'selected' : '' }} value="{{$comp['id']}}">{{$comp['company']}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('company_id')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                        @enderror
                                    </div>
                                  </div>
                                  <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Role') }}:</label>
                                    </div>
                                    <div class="user_select_form">
                                        <select id="roles" class="select2" name="roles[]" multiple="multiple">
                                            @foreach ($roles as $role)
                                                <option {{ in_array($role, $userRole)? "selected":""}} value="{{$role}}">{{$role}}</option>
                                            @endforeach
                                        </select>
                                        @error('roles')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                    </div>
                                </div>

                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Email Address') }}:</label>
                                    </div>
                                    <div class="user_input_form">
                                      <input id="email" value="{{$user['email']}}" type="email" class="form-control @error('email') is-invalid @enderror" name="email"  placeholder="Enter email">
                                      @error('email')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                    </div>
                                </div>
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Full Name') }}:</label>
                                    </div>
                                    <div class="user_input_form">
                                        <input type="text" value="{{$user['name']}}"  class="form-control" id="name" name="name" required autocomplete="name" autofocus  placeholder="Full Name">
                                      @error('name')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                    </div>
                                </div>
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Access Privileges') }}:</label>
                                    </div>
                                    <div class="user_select_form">
                                      <select id="access_privilege" class="select2"  name="access_privilege">
                                          <option>Select Access Privileges</option>
                                          <option {{($companyUser['access_privilege']=='Active')? "selected":""}}  value="Active">Active</option>
                                          <option  {{($companyUser['access_privilege']=='Deactivated')? "selected":""}} value="Deactivated">Deactivated</option>
                                      </select>
                                      @error('access_privilege')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                  </div>
                                </div>
                                <br>
                                <br>
                                <div class="form_title">
                                    <h4>Password</h4>
                                </div>
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Password') }}:</label>
                                    </div>
                                    <div class="user_input_form">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required  placeholder="Password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Confirm Password') }}:</label>
                                    </div>
                                    <div class="user_input_form">
                                        <input id="password-confirm"  type="password" class="form-control" name="confirm-password" required autocomplete="confirm-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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
<script>
    $(document).ready(function() {
    $('.select2').select2();
});
    </script>


@endsection