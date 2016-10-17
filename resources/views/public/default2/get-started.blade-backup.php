@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
<section class="creat-page">
  <div class="form-bg"> 
    <!-- tabs right -->
    <div class="tabbable tabs-right">
      <div class="tab-content left-side col-md-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12" >
        <div class="tab-pane active" id="1">
          <div class="personal-form account-user">
              <div class="per-1 active">
              <div class="col-lg-12 col-sm-12 col-xs-12 ">
                <h4> Personal Account Details </h4>
                <p>You are the admin user to your new account. Add additional admin or event manager to your account.</p>
              </div>
			   @if ($errors->has())
				<div class="col-md-12 full-error alert">
				  <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
				  You have some form errors. Please check below.
				</div>						
			   @endif
              <div class="personal-form">
           <form class="form-inline" method="post" enctype="multipart/form-data">
              <ul class="nav nav-tabs" id="tabs">
                <li class="active"> <a data-toggle="tab" href="#tab1" aria-expanded="false">
                  <div class=" per-1 active">
                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                      <input type="text" placeholder="Address " value="{{ old('address') }}" name="address" class="form-control">
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
					 <div class="sel">
                          <select id="lunch" name="city" class="selectpicker"  title="City">
                          <option value="1">Category select 1</option>
                          <option value="2">Category select 2</option>
                          <option value="3">Category select 3</option>
                          <option value="4">Category select 4</option>
                          <option value="5">Category select 5 </option>
                        </select>	
                        {!! $errors->first('city', '<span class="help-block with-errors">:message</span>') !!}	
						</div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                      <div class="sel">
                        <select id="lunch" name="state" class="selectpicker"  title="Province/State">
                          <option value="s1">Category select 1</option>
                          <option value="s2">Category select 2</option>
                          <option value="s3">Category select 3</option>
                          <option value="s4">Category select 4</option>
                          <option value="s5">Category select 5 </option>
                        </select>
						{!! $errors->first('state', '<span class="help-block with-errors">:message</span>') !!}
                      </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                      <input type="text" placeholder="Postal/Zip Code" value="{{ old('zip_code') }}" name="zip_code" class="form-control">
					  {!! $errors->first('zip_code', '<span class="help-block with-errors">:message</span>') !!}
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                      <div class="sel">
                      <select id="lunch" name="country" class="selectpicker"  title="Country">
                          <option value="c1">Category select 1</option>
                          <option value="c2">Category select 2</option>
                          <option value="c3">Category select 3</option>
                          <option value="c4">Category select 4</option>
                          <option value="c5">Category select 5 </option>
                        </select>
						{!! $errors->first('country', '<span class="help-block with-errors">:message</span>') !!}
                      </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                        <input type="text" placeholder="Phone" value="{{ old('phone') }}" name="phone" class="form-control">
					    {!! $errors->first('phone', '<span class="help-block with-errors">:message</span>') !!}	
                    </div>
                  </div>
                  </a></li>            
              </ul>
			  <input type="hidden" name="_token" value="{{ csrf_token() }}">	
			   <div class="form-group col-lg-12 col-sm-12 col-xs-12"> 
			<input type="submit" class="btn discover-btn" value="Next"/>
		  </div>
        </form>
          </div>
        </div>
        
      </div>
    </div>
  </div>
  </div>
  <!-- /tabs -->
  </div>
  </div>
  </div>
</section>
@stop
@section('scripts')

@stop