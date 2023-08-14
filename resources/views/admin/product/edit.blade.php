@extends('layouts.app')

@section('content')
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
                                        <label>{{ __('Category') }} <span class="text-danger">*</span></label>
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
                                        <label>{{ __('Product Name') }} <span class="text-danger">*</span></label>
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
                                            <label>{{ __('Product Number / SKU') }} <span class="text-danger">*</span></label>
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
                                            <label>{{ __('Competitor Product Name') }} <span class="text-danger">*</span></label>
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
                                        {{-- <div class="user_btn  text-secondary">
                                            <div  class="user_btn_style">
                                             <img src="{{asset('assets/images/next.png')}}" alt="->"> Submit
                                            </div>
                                        </div> --}}
                                        {{-- <div class="user_btn  myborder" >
                                            <a href="{{ route('product-delete',   $company['id']) }}" class="user_btn_style" style="color: black; border:none;" >
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