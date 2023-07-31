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
                        <h3>Product</h3>
                      </div>
                      <form method="POST" action="{{route("product.update", $id)}}">
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
                                        <select id="company" name="company_id" class="form-select"  required>
                                            <option value disabled>Select Company</option>
                                            @if($companies!=null)
                                            @foreach($companies as $company)
                                            <option {{ $company['id'] == $product['company_id'] ? 'selected' : '' }} value="{{$company['id']}}">{{$company['company']}}</option>
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
                                        <label>{{ __('Category') }}:</label>
                                    </div>
                                    <div class="user_select_form">
                                        <input type="text" required  class="form-control" id="category" name="category" required autocomplete="category" autofocus value="{{$product['category']}}"  placeholder="">
                                        @error('category')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                  <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Product Name') }}:</label>
                                    </div>
                                    <div class="user_input_form">
                                        <input type="text" required value="{{$product['product_name']}}" class="form-control" id="product_name" name="product_name" required autocomplete="product_name" autofocus  placeholder="">
                                        @error('product_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                    <div class="user_form_content">
                                        <div class="label">
                                            <label>{{ __('Product Number / SKU') }}:</label>
                                        </div>
                                        <div class="user_input_form">
                                            <input type="text" required class="form-control" value="{{$product['product_number_sku']}}" id="product_number_sku" name="product_number_sku" required autocomplete="product_number_sku" autofocus  placeholder="">
                                            @error('product_number_sku')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="user_form_content">
                                        <div class="label">
                                            <label>{{ __('Competitor Product Name') }}:</label>
                                        </div>
                                        <div class="user_input_form">
                                            <input type="text" required class="form-control" value="{{$product['competitor_product_name']}}" id="competitor_product_name" name="competitor_product_name" autocomplete="competitor_product_name" autofocus  placeholder="">
                                            @error('competitor_product_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="user_btn_list">
                                        <div class="user_btn myborder">
                                            <button type="submit" class=" user_btn_style submit ">
                                             <img src="{{asset('assets/images/save.png')}}" alt="->"> Save Changes
                                            </button>
                                        </div>
                                        <div class="user_btn  text-secondary">
                                            <div  class="user_btn_style">
                                             <img src="{{asset('assets/images/next.png')}}" alt="->"> Submit
                                            </div>
                                        </div>
                                        <div class="user_btn  myborder" >
                                            <a href="{{ route('product-delete',   $company['id']) }}" class="user_btn_style" style="color: black; border:none;" >
                                            <img src="{{asset('assets/images/del_user.png')}}"> Delete User
                                            
                                            </a>
                                        </div>
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