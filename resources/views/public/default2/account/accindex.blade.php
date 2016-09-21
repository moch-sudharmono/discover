@extends('public/default2/_layouts._layout')
@section('styles')
@stop
@section('content')
 <section class="sec">
  <div class="tabbable tabs-left">	
	  @include("public/default2/_layouts._AccountMenu")
        <div class="tab-content form-group col-lg-8 col-sm-8 col-xs-12">
   <?php 
	   if(isset($event_details[0]->g_id)){
	    if($event_details[0]->g_id == 2) {
		  $imgs_apath = 'business';
		  $acc_name = 'Business Name';
		  $tacc_name = 'Business';
		} elseif($event_details[0]->g_id == 5) {  
		  $imgs_apath = 'municipality';
		  $acc_name = 'Name';
		  $tacc_name = 'Municipality';
        } else {
		  $imgs_apath = 'club';
		  $acc_name = 'Club or Organization Name';
		  $tacc_name = 'Club or Organization';
		}
	   }
   ?>
  <div class="col-lg-12 col-sm-12 col-xs-12 account-psection">		 
	<b>Account Type:</b> {!!$tacc_name!!} <b>Account Name:</b> {{ $event_details[0]->name }}
  </div>		 
		 <h3> Account Details </h3>	
		@if($auser_role == 'admin') 
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
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>{{$acc_name}} :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" name="name" class="form-control" value="{!!$event_details[0]->name!!}" required />
				   @if (Session::has('name_error')) 
					<span class="help-block with-errors">The {{$acc_name}} has already been taken.</span>    
				   @endif
				  {!! $errors->first('name', '<span class="help-block with-errors">:message</span>') !!}	
              </div>
            </div>
			
			  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Account URL:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send url-link"> 
               <label>
				<span class="icon-prepend">{!!URL('/')!!}/</span>
				<input type="text" value="{!!$event_details[0]->account_url!!}" id="account_url" name="account_url" class="form-control" required />				  
				@if (Session::has('acurl_error')) 
					<span class="help-block with-errors">{!! Session::get('acurl_error') !!}</span>    
				@endif
					 {!! $errors->first('account_url', '<span class="help-block with-errors">:message</span>') !!}	
					</label>
              </div>
            </div>
		  
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Account email address :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="email" name="email" class="form-control" value="{!!$event_details[0]->email!!}" required />
				   {!! $errors->first('email', '<span class="help-block with-errors">:message</span>') !!}
              </div>
            </div>
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
			<label class="col-lg-3 col-sm-3 col-xs-12 row"> Account image:  </label>
			  <div class="col-lg-6 col-sm-9 col-xs-12"> 
		<div class="col-lg-2 col-sm-2 col-xs-3">	  
	   @if(!empty($event_details[0]->upload_file))	
		 <img src="{!!URL::to('uploads/account_type/'.$imgs_apath.'/'.$event_details[0]->upload_file)!!}" class="img-responsive"/>
		@else
		 <img src="{!!URL::to('assets/public/default2/images/fox-logo.png')!!}" class="img-responsive"/>		
        @endif
		</div>
		<div class="col-lg-10 col-sm-10 col-xs-9 padd-n">
                      <input id="uploadFile" placeholder="Choose File"  class="form-control" disabled="disabled" />
                      <div class="fileUpload btn btn-primary"> <span>Upload</span>
                        <input id="uploadBtn" name="upload_file" type="file" class="upload" />
                      </div>
				 {!! $errors->first('upload_file', '<span class="help-block with-errors">:message</span>') !!}	
                </div>	
            </div>				
            </div>
				<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row">Website :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" name="website" class="form-control" value="{!!$event_details[0]->website!!}" />
				    {!! $errors->first('website', '<span class="help-block with-errors">:message</span>') !!}
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>Address :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" name="address" class="form-control" value="{!!$event_details[0]->address!!}" required />
				  {!! $errors->first('address', '<span class="help-block with-errors">:message</span>') !!}
              </div>
            </div>			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">    
			<label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>Country :         
			</label>        
			<div class="col-lg-6 col-sm-9 col-xs-12 send"> 		
			<select id="country" name="country">             
			@foreach($cou_data as $cd)		
			@if($cd->id == $event_details[0]->country)		
				<option value="{!! $cd->id !!}" selected>{!! $cd->name !!}</option>		
			@else				 
				<option value="{!! $cd->id !!}">{!! $cd->name !!}</option>			
			@endif	                          
			@endforeach	       
			</select>		
			{!! $errors->first('country', '<span class="help-block with-errors">:message</span>') !!}		
			</div>          
			</div>	
					
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>Province/State :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 		
			  <?php $getsdata = DB::table('states')->where('country_id', '=', $event_details[0]->country)->select('id','name')->get(); ?>  
                    <select id="state" name="state">
                      @if(!empty($getsdata[0]->id))	
						  @foreach($getsdata as $sdata) 		
					  @if($sdata->id == $event_details[0]->state)	
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
 <input type="text" value="{!!$event_details[0]->city!!}" name="city" class="form-control" required/>			
			{!! $errors->first('city', '<span class="help-block with-errors">:message</span>') !!}	
			</div>    
			</div>
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>Postal/Zip Code :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" value="{!!$event_details[0]->zip_code!!}" name="zip_code" class="form-control" required />
				  {!! $errors->first('zip_code', '<span class="help-block with-errors">:message</span>') !!}
              </div>
            </div>
			<input type="hidden" name="acc_id" value="{!!$event_details[0]->id!!}">	
			    
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"><span class="require-val">*</span>Phone :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" id="phone" name="phone" class="form-control" value="{!!$event_details[0]->phone!!}" required />
				    {!! $errors->first('phone', '<span class="help-block with-errors">:message</span>') !!}
              </div>
            </div>
			
			 	<input type="hidden" name="_token" value="{{ csrf_token() }}">	
          <div class="form-group col-lg-6 col-sm-12 col-md-6 col-xs-12 col-md-offset-3"> 
			<input type="submit" class="form-control follow update-btn" Value="Update">
		  </div>
		 </form> 
      @else
		 <!-- event user view -->  
	<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> {{$acc_name}}:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  {!!$event_details[0]->name!!}
              </div>
            </div>
			
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> Account URL:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                <a href="{!!URL('/'.$event_details[0]->account_url)!!}" target="_blank">{!!URL('/'.$event_details[0]->account_url)!!}</a>
              </div>
            </div>			
		  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> Account email address:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
			   {!!$event_details[0]->email!!}
              </div>
            </div>
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
			<label class="col-lg-3 col-sm-3 col-xs-12 row"> Account image:  </label>
	  <div class="col-lg-2 col-sm-2 col-xs-12">
        @if(!empty($event_details[0]->upload_file))	
		 <img src="{!!URL::to('uploads/account_type/'.$imgs_apath.'/'.$event_details[0]->upload_file)!!}" class="img-responsive account-img"/>
		@else
		 <img src="{!!URL::to('assets/public/default2/images/fox-logo.png')!!}" class="img-responsive account-img"/>		
        @endif
      </div>				 
            </div>
				<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> 	Website:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                 @if(!empty($event_details[0]->website))	
				 {{$event_details[0]->website}}
				 @else
					 n/a
                 @endif  					 
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> 	Address:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                 @if(!empty($event_details[0]->address))
					 {{$event_details[0]->address}}
				 @else	 
					 n/a
				 @endif	 
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> 	Country:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
			     @if(!empty($event_details[0]->country))
				  {{$event_details[0]->country}}
				 @else	 
					 n/a
				 @endif
			 </div>
            </div>			
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> 	Province/State:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                 @if(!empty($event_details[0]->state))
				  {{$event_details[0]->state}}
				 @else	 
					 n/a
				 @endif
              </div>
            </div>
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> 	City:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                 @if(!empty($event_details[0]->city))
					 {{$event_details[0]->city}}
				 @else	 
					 n/a
				 @endif	 
              </div>
            </div>
				
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> 	Postal/Zip Code:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                 @if(!empty($event_details[0]->zip_code))
				  {{$event_details[0]->zip_code}}
				 @else	 
					 n/a
				 @endif
              </div>
            </div>
			
			    
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> 	Phone:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                @if(!empty($event_details[0]->phone))
				  {{$event_details[0]->phone}}
				 @else	 
					 n/a
				 @endif
              </div>
            </div>
		<div class="form-group col-lg-12 col-sm-12 col-xs-12">
          <label class="col-lg-12 col-sm-12 col-xs-12 row">	
			Only the account can change account details. Please contact <a href="mailto:{!!$gadmin_detail[0]->email!!}">{!!$gadmin_detail[0]->full_name!!}</a>.
		  </label>
        </div>		  
			
      @endif		  
        </div> 
   </div>
</section>
@stop
@section('scripts')
<script>
 jQuery(document).ready(function(){
  jQuery("#account_url").keyup(function () {
      this.value = this.value.replace(/ /g, "_");
  });
  document.getElementById("uploadBtn").onchange = function () {
	  document.getElementById("uploadFile").value = this.value;
	};
 }); 
  jQuery(function($){
    $("#phone").mask("(999) 999-9999");
  })
</script>
@stop