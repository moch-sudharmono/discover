@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
{!!HTML::style("assets/public/default2/css/jquery.filer-dragdropbox-theme.css")!!}
{!!HTML::style("assets/public/default2/css/jquery.filer.css")!!}
 {!!HTML::script("assets/public/default2/js/locationpicker.jquery.js")!!}
  {!!HTML::script("assets/public/default2/js/jquery.filer.min.js")!!}
  {!!HTML::script("assets/public/default2/js/custom.js")!!}
 <section class="sec inp">
      <div class="tabbable tabs-left">
       @include("public/default2/_layouts._AccountMenu")
         <div class="tab-content form-group col-lg-8 col-sm-8 col-xs-12 ">
		 <h3>Create Your Event</h3>
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
     <h4 class="event-head">  <span class="ico-box ico--small">1</span> Contact Details</h4>	 
		    <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> Contact Person:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}"/>
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> Phone Number :
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" name="phone_no" class="form-control" value="{{ old('phone_no') }}"/>
				  {!! $errors->first('phone_no', '<span class="help-block with-errors">:message</span>') !!}	
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> Email Address:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="email" name="email_address" class="form-control" value="{{ old('email_address') }}"/>
				   {!! $errors->first('email_address', '<span class="help-block with-errors">:message</span>') !!}	 
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> Website:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" name="website" class="form-control" value="{{ old('website') }}"/>
				   {!! $errors->first('website', '<span class="help-block with-errors">:message</span>') !!}	
              </div>
            </div>
		  
			<h4 class="event-head"><span class="ico-box ico--small">2</span> Event Details</h4>	 
		     <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Event will occur:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
	    <input type="radio" name="event_type" class="event_type ind" value="indoor" checked /> <span>Indoor</span>&nbsp; | &nbsp;
        <input type="radio" name="event_type" class="event_type ind" value="outdoor"/> <span> Outdoor </span>
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Event Category:</label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send" id="event_category"> 
			   <img src="{!!URL::to('assets/public/default2/images/progress.gif')!!}" class="img-responsive" style="width:30px"/>
              </div>
			  {!! $errors->first('event_catid', '<span class="help-block with-errors">Event Category is required field</span>') !!}	
            </div>	
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Event Name:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" name="event_name" class="form-control" value="{{ old('event_name') }}" required />
				 {!! $errors->first('event_name', '<span class="help-block with-errors">:message</span>') !!} 
              </div>
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
		  <label class="col-lg-3 col-sm-3 col-xs-12 row">Event Image:</label>
		 <div class="col-lg-6 col-sm-9 col-xs-12 padd-n-xs">
		 
    <div id="content">
        <input type="file" name="event_image" id="filer_input2">
		  <small> We recommend using at least a 2160x1080px (2:1 ratio) image that's no larger than 2MB</small>
    </div>
	<div id="sel_ourimg" style="display:none"> </div>
	<input type="hidden" value="" name="our_pid" id="our_pid"/>
			 {!! $errors->first('event_image', '<span class="help-block with-errors">:message</span>') !!}	
			 	@if(!empty(Session::get('failed_upfile')))
				 <span class="help-block with-errors">We recommend using at least a 2160x1080px (2:1 ratio) image that's no larger than 2MB.</span>
			    @endif   
            <span>Don't have an image?<br>Use one of 
			<a href="javascript:void(0)" data-toggle="modal" data-target="#our-img-up"> ours </a></span>	
	       <div id="our-img-up" class="modal fade" role="dialog">
                <div class="modal-dialog"> 
                 <div class="modal-content sign_up-form" id="one_ours">
				 <img src="{!!URL::to('assets/public/default2/images/progress.gif')!!}" class="img-responsive" style="width:30px"/>
				 </div>
                </div>
            </div>	
         </div>				
        </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Venue/Location:
              </label>
              <div class="col-lg-9 col-sm-9 col-xs-12 send padd-n-xs">  
<div id="slocation">
			  <input type="text" name="event_all" id="us3-address" class="form-control" value="{{ old('event_all') }}"  />				 
				   <input type="hidden" class="form-control" id="us3-radius"/>
				   <input type="hidden" class="form-control" id="us3-lat"/>
				   <input type="hidden" class="form-control" id="us3-lon"/>
				   <a href="javascript:void(0)" id="open_alldetail">Enter Address</a>
 {!! $errors->first('event_venue', '<span class="help-block with-errors">Venue/Location field is required</span>') !!} 
 {!! $errors->first('country', '<span class="help-block with-errors">Venue/Location field is required</span>') !!} 
</div> 
<div id="alloc_fileds" style="display:none">		
<div class="map-enter col-md-7 col-sm-12 col-xs-12">
<input type="text" name="event_venue" placeholder="Enter the venue's name" id="us3-venue" class="form-control" value="{{ old('event_venue') }}"/> 
 {!! $errors->first('event_venue', '<span class="help-block with-errors">:message</span>') !!} 	
<input type="text" name="event_address" placeholder="Address" id="ev_address" class="form-control" value="{{ old('event_address') }}"/>	
  {!! $errors->first('event_address', '<span class="help-block with-errors">:message</span>') !!} 
<input type="text" name="address_secd" placeholder="Address 2" id="ev_addres" class="form-control" value="{{ old('address_secd') }}"/>	
   {!! $errors->first('address_secd', '<span class="help-block with-errors">:message</span>') !!} 
<input type="text" name="city" placeholder="City" id="evt_city" class="form-control full" value="{{ old('city') }}"/>	  
 {!! $errors->first('city', '<span class="help-block with-errors">:message</span>') !!} 
<input type="text" name="state" placeholder="State" id="evt_state" class="form-control full" value="{{ old('state') }}"/>   
{!! $errors->first('state', '<span class="help-block with-errors">:message</span>') !!} 	
<input type="text" name="zip_code" placeholder="Zip/Postal" id="evt_zip" class="form-control full" value="{{ old('zip_code') }}"/>  
 {!! $errors->first('zip_code', '<span class="help-block with-errors">:message</span>') !!} 	
 <select name="country" id="us5-country" required>
  <option value="">Please select a country.</option>
  @if(!empty($cuntd[0]->id))
   @foreach($cuntd as $cdata)
     <option value="{!! $cdata->id !!}">{!! $cdata->name !!}</option> 
   @endforeach
  @endif  
 </select>	
<div><a href="javascript:void(0)" id="restlocation">Reset location</a><input type="checkbox" name="map_show" id="map_show" value="y" class="show-map" checked>Show map on event page</div>   
</div>
			  <div id="us3" style="width: 240px; height: 193px;">  </div>
			  	 <!--  <img src="{!!URL::to('assets/public/default2/images/no-map.jpg')!!}" class="img-responsive"/> 	  -->
</div>	  
 </div>
</div>
			
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Event Description: 
              </label>
              <div class="col-lg-9 col-sm-9 col-xs-12 send padd-n-xs padd-n-xs"> 
                  <textarea name="event_description" id="event_description" class="form-control ckeditor">{{ old('event_description') }}</textarea>
				   {!! $errors->first('event_description', '<span class="help-block with-errors">:message</span>') !!}  
              </div>
            </div>

<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Cost: 
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
				 <input type="radio" name="event_cost" class="event_cost" value="free" @if(!empty(old('event_cost')) && old('event_cost') == 'free') checked @else checked @endif/> Free Event  &nbsp; &nbsp;
                 <input type="radio" name="event_cost" class="event_cost" value="paid" @if(!empty(old('event_cost')) && old('event_cost') == 'paid') checked @endif /> Paid 
				  {!! $errors->first('event_cost', '<span class="help-block with-errors">:message</span>') !!} 
             </div>
</div>	
<div class="form-group col-lg-12 col-sm-12 col-xs-12" id="event-price" @if(!empty(old('event_cost')) && old('event_cost') == 'paid') style="display:block;" @else style="display:none;" @endif>	
   <label class="col-lg-3 col-sm-3 col-xs-12 row">Ticket Price: 
              </label>	 
  <div class="col-lg-6 col-sm-9 col-xs-12 send">
		   <label class="lab">
			  <span class="icon-prepend">$</span>
          <input name="event_price" id="event_price" value="{{ old('event_price') }}" placeholder="Enter a single dollar amount" type="text" class="form-control"/>				 
		   <b>Flate Rate/Starting Amount</b>
		 </label> 
		  <label class="lab">
			  <span class="icon-prepend">$</span>
			
        <input name="mevent_price" id="mevent_price" value="{{ old('mevent_price') }}" placeholder="Enter a single dollar amount" type="text" class="form-control"/>				 			
		<b>Maximum Amount</b>
     </label>
			 <b>Example:</b> 10 or 10.99
				 {!! $errors->first('event_price', '<span class="help-block with-errors">:message</span>') !!} 				  
				 @if(!empty(Session::get('req_evprice')))
				 <span class="help-block with-errors">{{ Session::get('req_evprice') }}</span>
			    @endif  
 </div>
</div> 
	<div class="form-group col-lg-12 col-sm-12 col-xs-12" id="maintk_surl" @if(!empty(old('event_cost')) && old('event_cost') == 'paid') style="display:block;" @else style="display:none;" @endif>
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Ticket Sales URL: 
              </label>
              <div class="col-lg-9 col-sm-9 col-xs-12 send url-link padd-n-xs"> 
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
<h4 class="event-head"><span class="ico-box ico--small">3</span>Event Date(s)</h4>
<div class="form-group col-lg-12 col-sm-12 col-xs-12 ">
			<div class="form-group col-lg-12 col-sm-12 col-xs-12 start-end">
			<div class="col-lg-12 col-sm-6 col-xs-12 row margin-b10">
			 <label class="width-set"> <span class="require-val">*</span>Starts: </label>			
			  <div class="input-group date custom-dtime padd-n-xs" id="datetimepicker2"> 
                  <input type="text" name="sevent_date" class="form-control" value="{{ old('sevent_date') }}" required />
				  <span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				  </span>
				 {!! $errors->first('sevent_date', '<span class="help-block with-errors">:message</span>') !!}  		 
              </div>
			 <div class="input-group date custom-dtime padd-n-xs">			 
<select class="time-set" name="sevent_time">
   <div class="ss"> 
    @foreach($st_cvs as $scsv)
	  <option value="{!!$scsv!!}">{!!$scsv!!}</option>  
	@endforeach
   </div>
</select>
 {!! $errors->first('sevent_time', '<span class="help-block with-errors">:message</span>') !!}   		 
</div> 	
</div>		
			<div class="col-lg-12 col-sm-6 col-xs-12 row margin-b10">
			 <label class="width-set">Ends:</label>			
			<div class="input-group date custom-dtime padd-n-xs" id="datetimepicker3"> 
                  <input type="text" name="event_date" class="form-control" value="{{ old('event_date') }}"/>
				  <span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				  </span>
				 {!! $errors->first('event_date', '<span class="help-block with-errors">:message</span>') !!} 				 
              </div>			  
 <div class="input-group date custom-dtime padd-n-xs">			 
<select class="time-set" name="eevent_time">
  @foreach($st_cvs as $scsv)
	<option value="{!!$scsv!!}">{!!$scsv!!}</option>  
  @endforeach
</select>
 {!! $errors->first('eevent_time', '<span class="help-block with-errors">:message</span>') !!}   		 
              </div> 		  
			</div>           
          </div>
		 <div class="start-end_multiple form-group col-lg-12 col-sm-12 col-xs-12">
		 <div class="heading">
		 <h4 class="text-heading-secondary">
        Schedule dates
        <a style="float: right;" class="l-align-right text-link-aside js-d-modal text-heading--understated" href="#">Learn more!</a>
		</h4>
		</div>
		<div class="form_add_event">
		<form>
		<h4>How often does this event occur?</h4>
		<select id="new-series-period" class="js-series-period">
        <option value="days">Daily</option><option value="weeks">Weekly</option><option value="months">Monthly</option><option value="custom">Custom</option></select>
		             </div>	
<h4>From</h4>					 
 <div class="input-group date custom-dtime padd-n-xs">			 
<select class="time-set" name="timestart" id="start-time">
  @foreach($st_cvs as $scsv)
	<option value="{!!$scsv!!}">{!!$scsv!!}</option>  
  @endforeach
</select>
 {!! $errors->first('eevent_time', '<span class="help-block with-errors">:message</span>') !!}   		 
              </div> 	
			  <h4>To</h4>					 
<div class="input-group date custom-dtime padd-n-xs">			 
<select class="time-set" name="timeend" id="end-time">
  @foreach($st_cvs as $scsv)
	<option value="{!!$scsv!!}">{!!$scsv!!}</option>  
  @endforeach
</select>
 {!! $errors->first('eevent_time', '<span class="help-block with-errors">:message</span>') !!}   		 
              </div> 	
			  <h4>of the</h4>
			 <select id="schedule_event_seires" class="schedule_event_seires">
        <option value="same-day">Same day</option><option value="next-day">Next day</option><option value="2nd-day">2nd day</option><option value="3rd-day">3rd day</option>
		<option value="4th-day">4th day</option>
		<option value="5th-day">5th day</option>
		<option value="6th-day">6th day</option>
		
		</select> 
		<h4>Occurs from</h4>
		<input type="text" id="arrdatepicker" /><br />
		<h4>Until</h4>
		<input type="text" id="depdatepicker" /><br />
		<div id="label"></div><p id="diff_time"></p>
		<div class=""><p id="show_date"></p>
       <div class="">
			  <a id="add-event-btn" href="#">Add</a>
			  <a id="cancel-event-btn" href="#">Cancel</a>
			  </div>
		</form>
		</div>
		 
	  
      </div>
	  <div class="schedule_event_div"><a class="schedule_event" href="#">Schedule multiple events</a></div>	
<h4 class="event-head"><span class="ico-box ico--small">4</span>Additional options</h4>
<div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
Social Media<br>
<input type="checkbox" name="social_link" id="social_link" value="y">
    Include links to Facebook and Twitter
</div>
<div class="col-lg-8 col-sm-12 col-xs-12" id="soc_url" @if(!empty(old('social_link')) && old('social_link') == 'y') style="display:block;" @else style="display:none;" @endif>
<div>
<span class="fb"> </span> facebook.com/
<input type="text" name="fb_link" class="form-control mr-btm" value="{{ old('fb_link') }}"/>
</div>
<div>
<span class="tw"> </span> twitter.com/ 
<input type="text" name="tw_link" class="form-control mr-btm" value="{{ old('tw_link') }}"/>
</div>
</div>
<h4 class="event-head">Listing Privacy</h4>
<div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
<input type="checkbox" class="pubprv" name="private_event" value="n" checked />
    Public Event</div>
<div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
<input type="checkbox" class="pubprv" name="private_event" value="y" />
        Private Event <h6>(If check, Event will not be advertised to public. Only people you send the Event link to will have access to the Information)</h6>
</div>
{!! $errors->first('private_event', '<span class="help-block with-errors">Listing Privacy: Please select one option on here (Public/Private)</span>') !!} 
<h4 class="event-head">Event is:</h4>
<div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                      <input type="checkbox" name="kid_event" value="y">
                      for Kids</div>
					  <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                      <input type="checkbox" name="family_event" value="y">
                      for Families </div>
				 <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                      <input type="checkbox" name="religious_event" value="y">
                      a religious nature
			</div>	
		<?php /* <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row">Event URL:
              </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send url-link"> 
			  <label>
			  <span class="icon-prepend">{!!URL('event/')!!}/</span>
                  <input type="text" name="event_url" id="event_url" style="width:67px" class="form-control" value="{{ old('event_url') }}" />
			  </label>	  
				 {!! $errors->first('event_url', '<span class="help-block with-errors">:message</span>') !!} 	
				<h6><b>Note:</b> Please pick a name related to your event so users can find/remember your event easier. If left blank you will be assigned a random number for your event.</h6> 
              </div>			  					
            </div>
			<div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Address: </label>
              <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                  <input type="text" name="event_address" class="form-control" value="{{ old('event_address') }}" required/>
				 {!! $errors->first('event_address', '<span class="help-block with-errors">:message</span>') !!}  
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
            </div> */ ?>  
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
	$('#one_ours').load('{!!URL("account/event-img/Camps")!!}');
	  $("#event_description").cleditor({
		controls: 
            "bold italic underline | size " +
            "style | color | bullets numbering | outdent " +
            "indent | alignleft center alignright justify | undo redo | " +
            "rule | cut copy paste pastetext | source",
	  });
 $('body').delegate(".event_category", 'change', function(){	
   var secat = $( "select.event_category option:selected" ).text();
   var secat_url = secat.replace(/ /g, "_");
    $('#one_ours').load('{!!URL("account/event-img")!!}/'+secat_url);
 });	  
     $("#event_url").keyup(function () {
      this.value = this.value.replace(/ /g, "_");
     });	
	$('#datetimepicker2').datetimepicker({
      format: 'L'	
    });
	$('#datetimepicker3').datetimepicker({
      format: 'L'	
    });
	
	$('#datetimepicker4').datetimepicker({
     format: 'LT'
    });	
	 $('#datetimepicker5').datetimepicker({
     format: 'LT'
    });	
	$("input#us3-address").blur(function(){	
	  $('#alloc_fileds').show(); 
	  $('#slocation').hide();
    }); 	
	/* $("#event_price").keyup(function () {
      this.value = this.value.replace(/ /g, "-");
     }); */
	
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
	$('body').delegate("input[name='social_link']", 'click', function(){
      var soclink = $("input[name='social_link']:checked").val();
     if(soclink){
      $('#soc_url').show();
     } else {
	  $('#soc_url').hide();	
	 }
    });	
	$('.pubprv').click(function() {
     $('input.pubprv').not(this).prop('checked', false); 
    });	
	$('#open_alldetail').click(function() {
     $('#alloc_fileds').show(); 
	  $('#slocation').hide();
    });
	$('#restlocation').click(function() {
     $('#alloc_fileds').hide(); 
	  $('#slocation').show();
	  $('#us3-address').val('');
	  $('#event_venue').val('');
	  $('#ev_address').val('');
	  $('#ev_addres').val('');
	  $('#evt_city').val('');
	  $('#evt_state').val('');
      $('#evt_zip').val('');		  
    });			 	 
	 /* $("#ticket_surl").keyup(function () {
      this.value = this.value.replace(/ /g, "_");
     });*/
	 function updateLvsControls(addressComponents) {
		$('#us3-venue').val(addressComponents.addressLine1);
		$('#evt_city').val(addressComponents.city);
		$('#evt_state').val(addressComponents.stateOrProvince);
		$('#evt_zip').val(addressComponents.postalCode);
		var countcode = addressComponents.country;
	if(countcode){	
     $.ajax({
        type: "GET",
        url: '{!!URL("cotryevnt")!!}/'+countcode,       
        beforeSend: function(){ 
          $('#us5-country').html('<option selected>loading</option>');
        },
        complete: function(){
        },
        success: function(data) {
         $('#us5-country').html(data);
        }
     });	
	}	 
    }
	 $('#us3').locationpicker({
        location: {latitude: 0, longitude: 0},
        radius: 1,
        inputBinding: {
            latitudeInput: $('#us3-lat'),
            longitudeInput: $('#us3-lon'),
            radiusInput: $('#us3-radius'),
            locationNameInput: $('#us3-address'),		
        },
        enableAutocomplete: true,
        onchanged: function (currentLocation, radius, isMarkerDropped) {
            var addressComponents = $(this).locationpicker('map').location.addressComponents;
           updateLvsControls(addressComponents);
        }
    });
	
	$("input#evt_state").blur(function(){
		var cyadd = $('input#evt_state').val();
		var cutryadd = $('#us5-country').find(":selected").text();
	 if(cutryadd){
	  var gsadd = cyadd+','+cutryadd;
	 } else {
	  var gsadd = cyadd;
	 }
       var geocoder =  new google.maps.Geocoder();
    geocoder.geocode( { 'address': gsadd}, function(results, status) {
        if(status == google.maps.GeocoderStatus.OK){
			 var latlong = results[0].geometry.location.lat();
			 var lnglong = results[0].geometry.location.lng();		 
	         var vsLatLng = {lat: latlong, lng: lnglong}
	     var map = new google.maps.Map(document.getElementById('us3'), {
                center: vsLatLng,
			    zoom: 6,
              });
         var marker = new google.maps.Marker({
             position: vsLatLng,
			 map: map,
			title: cyadd
           });	
       } else {
         //alert("Something got wrong " + status);
       }
      });
	}); 
	$("#us5-country").change(function(){
	 var cyadd = $('input#evt_state').val();
	 var cutryadd = $('#us5-country').find(":selected").text();	 
	 if(cyadd){
	  var gsadd = cyadd+','+cutryadd;
	  var vstitle = cyadd; 
	 } else {
	  var gsadd = cutryadd;
	  var vstitle = cutryadd;  
	 }	 
       var geocoder =  new google.maps.Geocoder();
    geocoder.geocode( { 'address': gsadd}, function(results, status) {
        if(status == google.maps.GeocoderStatus.OK){
			 var latlong = results[0].geometry.location.lat();
			 var lnglong = results[0].geometry.location.lng();		 
	         var vsLatLng = {lat: latlong, lng: lnglong}
	     var map = new google.maps.Map(document.getElementById('us3'), {
                center: vsLatLng,
			    zoom: 6,
              });			 
         var marker = new google.maps.Marker({
             position: vsLatLng,
			 map: map,
			title: vstitle
           });	
       }
      });
	});
	
 });
 function selimg(imgid){
  $("#our_pid").val(imgid); 	
 }
  $('body').delegate("a.ourimg_tag", 'click', function(){
    var src= $(this).find('img').attr('src');
	$('#sel_ourimg').html('<div class="jFiler-item-thumb-image"><img draggable="false" src="'+src+'"/></div>'); 	
	$('#sel_ourimg').css("display", "block");
    $( "#csimg" ).trigger( "click" );
  });
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
$("#arrdatepicker").datepicker({
    showOn: "button",
    buttonImage: "calendar-ico.gif",
    buttonImageOnly: true,
    onSelect: function(dateText, inst) {
        //dateText comes in as MM/DD/YY
        var datePieces = dateText.split('/');
        var month = datePieces[0];
        var day = datePieces[1];
        var year = datePieces[2];
        //define select option values for
        //corresponding element
        $('select#arrmonth').val(month);
        $('select#arrday').val(day);
        $('select#arryear').val(year);
        dateDifference();
    }
});
$("#depdatepicker").datepicker({
    showOn: "button",
    buttonImage: "calendar-ico.gif",
    buttonImageOnly: true,
    onSelect: function(dateText, inst) {
        //dateText comes in as MM/DD/YY
        var datePieces = dateText.split('/');
        var month = datePieces[0];
        var day = datePieces[1];
        var year = datePieces[2];
        //define select option values for
        //corresponding element
        $('select#depmonth').val(month);
        $('select#depday').val(day);
        $('select#depyear').val(year);
        dateDifference();
    }
});
});

function dateDifference() {
    if($("#depdatepicker").val()!='' && $("#depdatepicker").val()!='') {
        
        var diff = ($("#depdatepicker").datepicker("getDate") - $("#arrdatepicker").datepicker("getDate")) / 1000 / 60 / 60 / 24;
        $('#label').html(diff+" dates");
    }
}

</script>
 <script>
   $(document).ready(function() {  
   $(".start-end_multiple").hide();
	 $(".schedule_event").click(function(event){
		  event.preventDefault();
        $(".start-end").hide();
		$(".start-end_multiple").show();
	 });
   var start_day = $("#arrdatepicker").val();
   var end_day = $("#depdatepicker").val();  
 $("#show_date").html("Starting "+start_day +" through "+ end_day);       		   
    
    });
   </script>
   <script>
   
$(document).ready(function(){
	var AJAX_URL_PLUS = 'http://dev.opalweb.in/discoveryourevent/event-date';
$("#add-event-btn").click(function(event){
	// alert("hello");
	// die();
	 event.preventDefault();
	//alert("hello");
	$.ajax({
    type: "POST",
	 url:AJAX_URL_PLUS+ '?action=saveEventdate', 
    //url: 'http://dev.opalweb.in/discoveryourevent/event-date',
    data: jQuery("#form").serialize(),
    success: function(data) {
      alert(data);
      }
    });
  });
  return false;

});	
</script>
@stop