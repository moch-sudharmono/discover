@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
 <section class="sec">
      <div class="tabbable tabs-left">
       @include("public/default2/_layouts._AccountMenu")
         <div class="tab-content form-group col-lg-8 col-sm-8 col-xs-12 ">
		 <h3> Create Event </h3>
		  <form class="form-inline" method="post" action="" enctype="multipart/form-data">
		  
		  @if(count($errors) > 0)
			<span class="col-md-12 full-error alert">
             <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
             <strong>Whoops!</strong> There were some problems with your input.
            </span>	
		  @endif
		   @if(!empty(Session::get('inv_tkurl')))
			<span class="col-md-12 full-error alert">
             <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
             <strong>Whoops!</strong> There were some problems with your input.
            </span>	  
		   @endif	
		    
		  @if(!empty(Session::get('req_evprice')) || !empty(Session::get('failed_upfile')))
			<span class="col-md-12 full-error alert">
             <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
             <strong>Whoops!</strong> There were some problems with your input.
            </span>	
		  @endif	  
		  
		     <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Event Type:
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
			  	  <input type="radio" name="event_type" class="event_type" value="indoor" checked /> Indoor  &nbsp; | &nbsp;
                          <input type="radio" name="event_type" class="event_type" value="outdoor"/> Outdoor 
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Event Categories:</label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send" id="event_category"> 
			   <img src="{!!URL::to('assets/public/default2/images/progress.gif')!!}" class="img-responsive" style="width:30px"/>
              </div>
			  {!! $errors->first('event_catid', '<span class="help-block with-errors">Event Category is required field</span>') !!}	
            </div>
			 
		    <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> Contact Person:
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
                  <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}"/>
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> Phone Number :
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
                  <input type="text" name="phone_no" class="form-control" value="{{ old('phone_no') }}"/>
				  {!! $errors->first('phone_no', '<span class="help-block with-errors">:message</span>') !!}	
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> Email Address:
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
                  <input type="email" name="email_address" class="form-control" value="{{ old('email_address') }}"/>
				   {!! $errors->first('email_address', '<span class="help-block with-errors">:message</span>') !!}	 
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> Website:
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
                  <input type="text" name="website" class="form-control" value="{{ old('website') }}"/>
				   {!! $errors->first('website', '<span class="help-block with-errors">:message</span>') !!}	
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Event Name:
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
                  <input type="text" name="event_name" class="form-control" value="{{ old('event_name') }}" required />
				 {!! $errors->first('event_name', '<span class="help-block with-errors">:message</span>') !!} 
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row">Event URL:
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send url-link"> 
			  <label>
			  <span class="icon-prepend">{!!URL('event/')!!}/</span>
                  <input type="text" name="event_url" id="event_url" style="width:67px" class="form-control" value="{{ old('event_url') }}" />
			  </label>	  
				 {!! $errors->first('event_url', '<span class="help-block with-errors">:message</span>') !!} 	
				<h6><b>Note:</b> Please pick a name related to your event so users can find/remember your event easier. If left blank you will be assigned a random number for your event.</h6> 
              </div>			  					
            </div>
			
		<div class="form-group col-lg-12 col-sm-12 col-xs-12">
		  <label class="col-lg-3 col-sm-3 col-xs-12 row">Event image:</label>
		 <div class="col-lg-6 col-sm-6 col-xs-12">
            <input id="uploadFile" placeholder="Choose File"  class="form-control" disabled="disabled" />
            <div class="fileUpload btn btn-primary"> <span>Upload</span>
             <input id="uploadBtn" name="event_image" type="file" class="upload" />
            </div>
			 {!! $errors->first('event_image', '<span class="help-block with-errors">:message</span>') !!}	
			 	@if(!empty(Session::get('failed_upfile')))
				 <span class="help-block with-errors">maximum allow 2mb file size.</span>
			    @endif         	
         </div>				
        </div>
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Venue/Location:
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
                  <input type="text" name="event_venue" class="form-control" value="{{ old('event_venue') }}" required/>
				   {!! $errors->first('event_venue', '<span class="help-block with-errors">:message</span>') !!} 
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Address: </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
                  <input type="text" name="event_address" class="form-control" value="{{ old('event_address') }}" required/>
				 {!! $errors->first('event_address', '<span class="help-block with-errors">:message</span>') !!}  
              </div>
            </div>			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Date(s): </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 input-group date custom-dtime" id="datetimepicker2"> 
                  <input type="text" name="event_date" class="form-control" value="{{ old('event_date') }}" required/>
				  <span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				  </span>
				 {!! $errors->first('event_date', '<span class="help-block with-errors">:message</span>') !!}   
              </div>
            </div>	
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Time: </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 input-group date custom-dtime" id="datetimepicker3"> 
				  <input name="event_time" value="{{ old('event_time') }}" type="text" class="form-control" required/>
					 <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
				{!! $errors->first('event_time', '<span class="help-block with-errors">:message</span>') !!}  	
              </div> 
            </div>			   
			 
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Cost: 
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
				 <input type="radio" name="event_cost" class="event_cost" value="free" @if(!empty(old('event_cost')) && old('event_cost') == 'free') checked @else checked @endif/> Free Event  &nbsp; &nbsp;
                 <input type="radio" name="event_cost" class="event_cost" value="paid" @if(!empty(old('event_cost')) && old('event_cost') == 'paid') checked @endif /> Paid($) 
				  {!! $errors->first('event_cost', '<span class="help-block with-errors">:message</span>') !!}  
                <div id="event-price" @if(!empty(old('event_cost')) && old('event_cost') == 'paid') style="display:block;" @else style="display:none;" @endif>
				 <input name="event_price" id="event_price" value="{{ old('event_price') }}" placeholder="Enter a single dollar amount or range." type="text" class="form-control"/>				 
				 <b>Example:</b> 10 or 10.99 or 5-10
				 {!! $errors->first('event_price', '<span class="help-block with-errors">:message</span>') !!} 				  
				 @if(!empty(Session::get('req_evprice')))
				 <span class="help-block with-errors">{{ Session::get('req_evprice') }}</span>
			    @endif   
				</div> 
              </div>
            </div> 
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12" id="maintk_surl" @if(!empty(old('event_cost')) && old('event_cost') == 'paid') style="display:block;" @else style="display:none;" @endif>
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Ticket Sales URL: 
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send url-link"> 
			  <label>
			   <!-- <span class="icon-prepend">{!!URL('ev/ticket/')!!}/</span>-->
                 <input name="ticket_surl" value="{{ old('ticket_surl') }}" id="ticket_surl" type="text" class="form-control"/>				 
			  </label>
		 @if(!empty(Session::get('inv_tkurl')))
			<span class="help-block with-errors">{{ Session::get('inv_tkurl') }}</span>
		 @endif  			 
				 {!! $errors->first('ticket_surl', '<span class="help-block with-errors">The Ticket Sales URL field is required.</span>') !!} 
              </div>
            </div>			
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Description: 
              </label>
              <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
                  <textarea name="event_description" class="form-control" required/>{{ old('event_description') }}</textarea>
				   {!! $errors->first('event_description', '<span class="help-block with-errors">:message</span>') !!}  
              </div>
            </div>	
			<div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                      <input type="checkbox" name="kid_event" value="y">
                      Kids Event</div>
					  <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                      <input type="checkbox" name="family_event" value="y">
                      Family Event</div>
					  <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                      <input type="checkbox" name="religious_event" value="y">
                      Religious Event</div>
					  <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                      <input type="checkbox" name="private_event" value="y">
                      Private Event <h6>(If check, Event will not be advertised to public. Only people you send the Event link to will have access to the Information)</h6></div>
					  
			 	<input type="hidden" name="_token" value="{{ csrf_token() }}">	
          <div class="form-group col-lg-6 col-sm-12 col-md-6 col-xs-12"> 
			<input type="submit" class="form-control follow update-btn" Value="Create">
		  </div>
		 </form>  
        </div>
      </div>
 </section>
@stop
@section('scripts')
 <Script>
  $(document).ready(function(){	
    $('#event_category').load('{!!URL("account/event-data/prsnl/indoor/ndye")!!}');

     $("#event_url").keyup(function () {
      this.value = this.value.replace(/ /g, "_");
     });
	
	$('#datetimepicker2').datetimepicker({
      format: 'L'	
    });
	
    $('#datetimepicker3').datetimepicker({
     format: 'LT'
    });	
        
	document.getElementById("uploadBtn").onchange = function () {
	 document.getElementById("uploadFile").value = this.value;
	};	
	
	 $("#event_price").keyup(function () {
      this.value = this.value.replace(/ /g, "-");
     });
	
	$('body').delegate(".event_type", 'click', function(){
     var gradioValue = $("input[name='event_type']:checked").val();
	   $('#event_category').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive" style="width:30px"/>');
     if(gradioValue){
        $('#event_category').load('{!!URL("account/event-data/prsnl/'+gradioValue+'/ndye")!!}');
     }
    });
		
	$('body').delegate(".event_cost", 'click', function(){
      var ecradioValue = $("input[name='event_cost']:checked").val();
     if(ecradioValue){
	  if(ecradioValue == 'paid'){
	   $('#event-price').show();
	   $('#maintk_surl').show();	  
	  } else {
	   $('#event-price').hide();
	   $('#maintk_surl').hide();	  
	  }
     }
    });
	 /* $("#ticket_surl").keyup(function () {
      this.value = this.value.replace(/ /g, "_");
     });*/
  });	
 </script>
@stop