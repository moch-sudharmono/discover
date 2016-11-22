@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
<section class="sec">
  <div class="tabbable tabs-left">
	 @include("public/default2/_layouts._AccountMenu")
        <div class="tab-content form-group col-lg-8 col-sm-8 col-xs-12 ">
		 <h3 class="mr-botom25"> Account Details </h3>
		 @if (Session::has('succ_mesg'))
                <span class="col-md-12 full-success alert">
                  <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                  <strong>Success!</strong> {!! Session::get('succ_mesg') !!}
                </span>			
          @endif
		  @if (Session::has('error'))
                <span class="col-md-12 full-error alert">
                  <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                   <strong>Error!</strong> {!! Session::get('error') !!}
                </span>			
          @endif
		  @if (count($errors) > 0)
			<span class="col-md-12 full-error alert">
             <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
             <strong>Whoops!</strong> There were some problems with your input.
            </span>	
		  @endif
		 <form class="form-inline" method="post" action="" enctype="multipart/form-data">
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Full Name :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" name="full_name" class="form-control" value="{!!$user_data[0]->full_name!!}" required />
				  {!! $errors->first('full_name', '<span class="help-block with-errors">:message</span>') !!}
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Email :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="email" name="email" class="form-control" value="{!!$user_data[0]->email!!}" required />
				  @if (Session::has('email')) 
					<span class="help-block with-errors">{!! Session::get('email_error') !!}</span>    
				   @endif
				  {!! $errors->first('email', '<span class="help-block with-errors">:message</span>') !!}
              </div>
            </div>			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>Address :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" name="address" class="form-control" value="{!!$user_data[0]->address!!}" required />
				  {!! $errors->first('address', '<span class="help-block with-errors">:message</span>') !!}
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>Country :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 			 
			<select id="country" name="country" title="Country">			
                @if(!empty($cuntd[0]->id)) 
			     <?php $getsdata = DB::table('countries')->where('id', '=', $user_data[0]->country)->select('id','name')->get(); ?> 
				 @foreach($cuntd as $cntdt)
				  @if($cntdt->id == $getsdata[0]->id)
				   <option value="{!! $getsdata[0]->id !!}" selected>{!! $getsdata[0]->name !!}</option>	  
				  @else
				   <option value="{!! $cntdt->id !!}">{!! $cntdt->name !!}</option>	  
                  @endif  					  
                 @endforeach				 
                @endif
            </select>
						{!! $errors->first('country', '<span class="help-block with-errors">:message</span>') !!}
				 </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>Province/State :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 						
				<?php $getsdata = DB::table('states')->where('country_id', '=', $user_data[0]->country)->select('id','name')->get(); ?>  
                  <select id="state" name="state" title="Province/State">
					@if(!empty($getsdata[0]->id))
					  @foreach($getsdata as $sdata)			
				        @if($sdata->id == $user_data[0]->state)
						 <option value="{!! $sdata->id !!}" selected>{!! $sdata->name !!}</option>		
                        @else
						 <option value="{!! $sdata->id !!}">{!! $sdata->name !!}</option>		
                        @endif 							
                      @endforeach	
					@endif	 
                  </select>
				{!! $errors->first('state', '<span class="help-block with-errors">:message</span>') !!}						
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>City :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send">                   
                 <input type="text" value="{!!$user_data[0]->city!!}" name="city" class="form-control" required/>
                    {!! $errors->first('city', '<span class="help-block with-errors">:message</span>') !!}							
              </div>
            </div>			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>Postal/Zip Code :</label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" value="{!!$user_data[0]->zip_code!!}" name="zip_code" class="form-control" required />
				  {!! $errors->first('zip_code', '<span class="help-block with-errors">:message</span>') !!}
              </div>
            </div>			
			    
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>Phone :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" id="phone" name="phone" class="form-control" value="{!!$user_data[0]->phone!!}" required />
				  {!! $errors->first('phone', '<span class="help-block with-errors">:message</span>') !!}
              </div>
            </div>
			 	<input type="hidden" name="_token" value="{{ csrf_token() }}">			 
		 <div class="form-group col-lg-12 col-sm-12 col-xs-12"> 		 
		   <label class="col-lg-3 col-sm-3 col-xs-12 row"> &nbsp </label>
			  <div class="col-lg-6 col-sm-9 col-xs-12 send"> <input type="submit" class="form-control follow update-btn" Value="update"> </div>
		 </div>		  
		</form>
	  </div>     
   </div>
</section>
@stop
@section('scripts')
 <Script>
  jQuery(function($){
    $("#phone").mask("(999) 999-9999");
  })	
  jQuery(document).ready(function(){
    document.getElementById("uploadBtn").onchange = function () {
	  document.getElementById("uploadFile").value = this.value;
	};
  }); 	
 </script>
@stop