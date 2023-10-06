@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                            @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
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
                                            @if($companies!=null)
                                            @foreach($companies as $company)
                                                <option value="{{$company['id']}}">{{$company['company']}}</option>
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
                                        <input type="text" required  class="form-control" id="name_of_store" name="name_of_store" required autocomplete="name_of_store" autofocus  placeholder="">
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
                                        <div class="user_input_form row " id="repeater-container">
                                            <!-- This is the container for the repeater items -->
                                        </div>
                                        
                                        <div class=" user_btn myborder label">
                                            <div class=" user_btn_style submit clickable-element" onclick="addRepeaterItem()">Add Location</div>
                                        </div>
                                        
                                    </div>

                                    <div class="user_form_content">
                                        <div class="label">
                                            <label>{{ __('Parish') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="user_select_form">
                                            <select id="parish" name="parish[]" class="form-select" placeholder="Select Parish" required multiple>
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

<script>
function addRepeaterItem() {
    const repeaterContainer = document.getElementById('repeater-container');
    const newItem = document.createElement('div');
    newItem.classList.add("col-6");
    newItem.classList.add("p-1");
    newItem.innerHTML = `
        <div class="w-100">
            <div class="user_btn myborder" style="border: 1px solid #37A849 !important">
                <input type="text" required class="border-none user_input_form_90 height-30px" name="locations[]" required autocomplete="location" autofocus placeholder="">
            </div>
        </div>
        <div  class="text-danger cross-btn clickable-element p-1" onclick="removeRepeaterItem(this)">x</div>
       
    `;
    // user_input_form
    repeaterContainer.appendChild(newItem);
}

function removeRepeaterItem(button) {
    button.parentElement.parentElement.remove();
}

</script>
<script>
    $(document).ready(function() {
        $('#parish').select2();
    });
</script>
