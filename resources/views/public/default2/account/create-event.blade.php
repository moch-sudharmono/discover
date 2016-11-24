@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
{!!HTML::style("assets/public/default2/css/jquery.filer-dragdropbox-theme.css")!!}
{!!HTML::style("assets/public/default2/css/jquery.filer.css")!!}
{!!HTML::script("assets/public/default2/js/locationpicker.jquery.js")!!}
{!!HTML::script("assets/public/default2/js/jquery.filer.min.js")!!}
{!!HTML::script("assets/public/default2/js/custom.js")!!}
{!!HTML::style("assets/public/default2/css/jquery-ui.css")!!}
{!!HTML::script("assets/public/default2/js/jquery-ui.js")!!}  
<section class="sec inp">
<div class="tabbable tabs-left">
    @include("public/default2/_layouts._AccountMenu")
    <div class="tab-content form-group col-lg-8 col-sm-8 col-xs-12 ">
        <h3>Create Your Event</h3>
        <form class="form-inline" method="post" action="" enctype="multipart/form-data">

        <!--error fields starts-->
            
            @if($errors->has() && (count($errors) > 0 || !empty(Session::get('share_event'))))
                @foreach ($errors->all() as $key => $error)
                <span class="col-md-12 full-error alert {{$key}}}">
                    {{$error}}
                </span>	
                @endforeach
            @endif
                
            @if(!empty(Session::get('ev_date')))
                <span class="col-md-12 full-error alert">Event Start date field is required</span>
            @endif
                
            @if(!empty(Session::get('inv_tkurl')))
                <span class="col-md-12 full-error alert">
                    <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                    <strong>Whoops!</strong> {{Session::get('inv_tkurl')}}
                </span>
            @endif
                
            @if(!empty(Session::get('ev_section')))
                <span class="col-md-12 full-error alert">
                    <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                    <strong>Whoops!</strong> {{Session::get('ev_section')}}
                </span>
            @endif
                
                
            @if(!empty(Session::get('req_evprice')))
                <span class="col-md-12 full-error alert">
                    <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                    <strong>Whoops!</strong> {{Session::get('req_evprice')}}
                </span>	
            @endif

            @if(!empty(Session::get('failed_upfile')))
                <span class="col-md-12 full-error alert">
                    <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                    <strong>Whoops!</strong> {{Session::get('failed_upfile')}}
                </span>	
            @endif

            @if(!empty(Session::get('pev_req')))
                <span class="col-md-12 full-error alert">
                    <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                    <strong>Whoops!</strong> {{Session::get('pev_req')}}
                </span>	
            @endif                
    
            <!--error fields ends-->
                
                
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
                    <input type="text" id="phone_no" name="phone_no" class="form-control" value="{{ old('phone_no') }}"/>
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
                    
                     <div class="col-lg-2 col-sm-2 col-xs-12">
                         		 
                            <img src="{{old('event_image')}}" class="img-responsive"/>
                            
                      </div>
                    
                    <div class="col-lg-6 col-sm-9 col-xs-12 padd-n-xs">

                        <div id="content">
                            <input type="file" name="event_image" id="filer_input2" value='{{ old('event_image') }}'>
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
                       
					   @if(old('event_all') == '')
					   <div id="slocation">
                            <input type="text" name="event_all" id="us3-address" class="form-control" value="{{ old('event_all') }}"  /><input type="hidden" class="form-control" id="us3-radius"/>
                            <input type="hidden" class="form-control" id="us3-lat"/>
                            <input type="hidden" class="form-control" id="us3-lon"/>
                            <a href="javascript:void(0)" id="open_alldetail">Enter Address</a>
                            {!! $errors->first('event_venue', '<span class="help-block with-errors">Venue/Location field is required</span>') !!}
                            {!! $errors->first('country', '<span class="help-block with-errors">Venue/Location field is required</span>') !!}
                        </div>
						
							<div id="alloc_fileds" style="display:none">
					   @else
							<div id="alloc_fileds" style="display:block">
						@endif
                        <div id="alloc_fileds" style="display:block">		
                            <div class="map-enter col-md-7 col-sm-12 col-xs-12">
                                <input type="text" name="event_venue" placeholder="Enter the venue's name" id="us3-venue" class="form-control" value="{{ old('event_venue') }}"/> 
                                {!! $errors->first('event_venue', '<span class="help-block with-errors">:message</span>') !!} 	
                                <input type="text" name="event_address" placeholder="Address" id="ev_address" class="form-control" value="{{ old('event_address') }}"/>	
                                {!! $errors->first('event_address', '<span class="help-block with-errors">:message</span>') !!} 
                                <input type="text" name="address_secd" placeholder="Address 2" id="ev_addres" class="form-control" value="{{ old('address_secd') }}"/>	
                                {!! $errors->first('address_secd', '<span class="help-block with-errors">:message</span>') !!} 
                                <input type="text" name="city" placeholder="City" id="evt_city" class="form-control full" value="{{ old('city') }}" required />	  
                                {!! $errors->first('city', '<span class="help-block with-errors">:message</span>') !!} 
                                <input type="text" name="state" placeholder="State" id="evt_state" class="form-control full" value="{{ old('state') }}"/>   
                                {!! $errors->first('state', '<span class="help-block with-errors">:message</span>') !!} 	
                                <input type="text" name="zip_code" placeholder="Zip/Postal" id="evt_zip" class="form-control full" value="{{ old('zip_code') }}"/>  
                                {!! $errors->first('zip_code', '<span class="help-block with-errors">:message</span>') !!}

                                <select name="country" id="us5-country" required>
                                    <option value="">Please select a country.</option>
                                    @if(!empty($cuntd[0]->id))
                                    @foreach($cuntd as $cdata)
                                    
                                    @if(old('country') == $cdata->id )
                                    <option value="{!! $cdata->id !!}" selected="selected">{!! $cdata->name !!}</option> 
                                     @else
                                        <option value="{!! $cdata->id !!}">{!! $cdata->name !!}</option> 
                                    @endif
                                    
                                    
                                    @endforeach
                                    @endif  
                                </select>

                                <div><a href="javascript:void(0)" id="restlocation">Reset location</a>
                                <!--<input type="checkbox" name="map_show" id="map_show" value="y" class="show-map" checked>Show map on event page-->
                                </div>   
                            </div>
                            <div id="us3" style="width: 240px; height: 193px;">  </div>
                                   <!--  <img src="{!!URL::to('assets/public/default2/images/no-map.jpg')!!}" class="img-responsive"/> 	  -->
                        </div>
						
						@if(old('event_all') == '')
							</div>
						@else
							</div>	
					    @endif		
						
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
                        <input type="radio" name="event_cost" class="event_cost" value="free" @if(!empty(old('event_cost')) && old('event_cost') == 'free') checked @else checked @endif/> Free &nbsp; &nbsp;
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
                            <b>Flat Rate/Starting Amount</b>
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
                     <label class="col-lg-3 col-sm-3 col-xs-12 row">Ticket Sales URL: 
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
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <div class="form-group col-lg-12 col-sm-12 col-xs-12 start-end">
                        <div class="col-lg-6 col-sm-6 col-xs-12 row margin-b10">
                            <div class="row">
                                <label class="width-set"> <span class="require-val">*</span>Starts: </label>			
                                <div class="input-group date custom-dtime padd-n-xs" id="datetimepicker2"> 
                                    <input type="text" name="sevent_date" class="form-control" id="sevent_date" value="{{ old('sevent_date') }}"/>
                                   <!-- <span class="input-group-addon">
                                                              <span class="glyphicon glyphicon-calendar"></span>
                                     </span> -->
                                    {!! $errors->first('sevent_date', '<span class="help-block with-errors">:message</span>') !!}
                                    
                                    @if(!empty(Session::get('ev_date')))
                                        <span class="help-block with-errors">Event Start date field is required</span>
                                    @endif  
                                </div>
                                <div class="input-group date custom-dtime padd-n-xs">			 
                                    <select class="time-set form-control" name="sevent_time">
                                        <div class="ss"> 
                                            @foreach($st_cvs as $scsv)
                                            <option value="{!!$scsv!!}">{!!$scsv!!}</option>  
                                            @endforeach
                                        </div>
                                    </select>
                                    {!! $errors->first('sevent_time', '<span class="help-block with-errors">:message</span>') !!}   		 
                                </div> 	
                            </div>
                        </div>		

                        <div class="col-lg-6 col-sm-6 col-xs-12 row margin-b10">
                            <div class="row">
                                <label class="width-set">Ends:</label>			
                                <div class="input-group date custom-dtime padd-n-xs" id="datetimepicker3"> 
                                    <input type="text" name="event_date" id="event_date" class="form-control" value="{{ old('event_date') }}"/>                         
                                    {!! $errors->first('event_date', '<span class="help-block with-errors">:message</span>') !!} 				 
                                </div>			  
                                <div class="input-group date custom-dtime padd-n-xs">			 
                                    <select class="time-set form-control" name="eevent_time">
                                        @foreach($st_cvs as $scsv)
                                        <option value="{!!$scsv!!}">{!!$scsv!!}</option>  
                                        @endforeach
                                    </select>
                                    {!! $errors->first('eevent_time', '<span class="help-block with-errors">:message</span>') !!}   		 
                                </div> 		  
                            </div> 
                        </div>

                        @if(!empty(Session::get('ev_section')))
                        <span class="help-block with-errors">Event date Section is required</span>
                        @endif			
                    </div>
                    <input type="hidden" name="event_dtype" class="form-control" id="event_dtype" value="single"/>	
                    <!-- multi-date -->	
                    <div class="new-period"></div>
                    <input type="hidden" id="count_p" value="0"/>		   
                    <div class="edit-se_multiple form-group col-lg-8 col-sm-12 col-xs-12">
                        <div><h4 class="text-body-large">Edit daily repeating dates</h4></div>
                        <hr>	
                        <div class="edit-series-schedule-monthly-day" id="essmd-monthly-day" style="display:none">
                            <label>It occurs every</label>
                            <select id="edit-series-schedule-monthly-day" class="form-control">
                                <option value="">Month Date</option>
                                <?php
                                foreach ($month_darray as $mdkey => $mdval) {
                                    echo '<option value="' . $mdkey . '">' . $mdval . '</option>';
                                }
                                ?>
                            </select> 
                            <span>day of the month.</span>
                        </div>

                        <div class="form_add_event series-schedule-weekly">
                            <label>What day(s) of the week?</label>
                            <select id="edit-series-schedule-weekly" class="js-series-weekly-weekdays form-control" multiple="multiple">	
                                <?php
                                foreach ($sdwkfmt as $sdfkey => $sdfval) {
                                    echo '<option value="' . $sdfkey . '">' . $sdfval . '</option>';
                                }
                                ?>                
                            </select>
                            <input type="hidden" value="" id="up_lid"/>	
                        </div>	
                        <label>From</label>					 
                        <div class="input-group date custom-dtime padd-n-xs">			 
                            <select class="time-set form-control" name="timestart" id="edit-start-time" onchange="tedcimecounts();">
                                <?php
                                foreach ($tmformat as $tmfkey => $tmfval) {
                                    echo '<option value="' . $tmfkey . '">' . $tmfval . '</option>';
                                }
                                ?> 
                            </select>
                        </div> 	
                        <label>To</label>					 
                        <div class="input-group date custom-dtime padd-n-xs">			 
                            <select class="time-set form-control" name="timeend" id="edit-end-time" onchange="tedcimecounts();">
                                <?php
                                foreach ($tmformat as $tmfkey => $tmfval) {
                                    echo '<option value="' . $tmfkey . '">' . $tmfval . '</option>';
                                }
                                ?> 
                            </select>
                        </div> 	
                        <label>of the</label>
                        <select id="edit-schd_event_seires" class="schedule_event_seires form-control" onchange="tedcimecounts();">	
                            <?php
                            foreach ($ofheday as $ofdkey => $ofdval) {
                                echo '<option value="' . $ofdkey . '">' . $ofdval . '</option>';
                            }
                            ?> 
                        </select>
                        <div class="date-range-section">
                            <div class="ouur_form_head">
                                <label id="edit-custom_change">Occurs from</label>		
                                <input type="text" id="edit_arrdatepicker" class="form-control"/>
                            </div>
                            <div class="until-datesection" id="edit-until-datesection">
                                <label>Until</label>
                                <input type="text" id="edit_depdatepicker" class="form-control"/>
                            </div>	
                        </div>	
                        <div style="display:none" id="edit_daycount">
                            <div id="edit_sdate_label">
                                <div id="edit_vslabel"></div>
                                dates
                            </div>
                            <div id="edit_timecount-est"></div>
                        </div>
                        <p id="edit_diff_time"></p>
                        <div>
                            <p id="edit_show_date"></p>
                            <input type='hidden' value="" id="edit_sd"/>
                            <input type='hidden' value="" id="edit_ed"/>
                            <div class="updt_cncl">
                                <a id="update-event-btn" href="javascript:void(0)">Update</a>
                                <a id="edit_cancel-event-btn" href="javascript:void(0)">Cancel</a>
                            </div>
                        </div>	 
                    </div>
                    <div class="start-end_multiple form-group col-lg-8 col-sm-12 col-xs-12" style="display: none;">
                        <div class="heading">
                            <h4 class="text-heading-secondary">Schedule dates</h4>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <div class="row">
                                <div class="form_add_event">
                                    <label>How often does this event occur?</label>
                                    <select id="new-series-period" class="js-series-period form-control">
                                        <option value="days">Daily</option>
                                        <option value="weeks">Weekly</option>
                                        <option value="months">Monthly</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>	
                            </div>
                        </div>      

                        <div class="form_add_event series-schedule-weekly">
                            <label>What day(s) of the week?</label>
                            <select id="ul-series-schedule-weekly" class="js-series-weekly-weekdays form-control" multiple="multiple">
                                <?php
                                foreach ($sdwkfmt as $sdfkey => $sdfval) {
                                    echo '<option value="' . $sdfkey . '">' . $sdfval . '</option>';
                                }
                                ?>  
                            </select>
                        </div>
                        <div class="series-schedule-monthly-day">
                            <label>It occurs every</label>
                            <select id="series-schedule-monthly-day" class="form-control">
                                <option value="">Month Date</option>
                                <?php
                                foreach ($month_darray as $mdkey => $mdval) {
                                    echo '<option value="' . $mdkey . '">' . $mdval . '</option>';
                                }
                                ?>
                            </select> 
                            <span>day of the month.</span>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 date_nd_time">
                            <div class="row">
                                <label class="fullheading">From</label>

                                <div class="input-group date custom-dtime padd-n-xs">			 
                                    <select class="time-set form-control" name="timestart" id="start-time" onchange="tcimecounts();">
                                        <?php
                                        foreach ($tmformat as $tmfkey => $tmfval) {
                                            echo '<option value="' . $tmfkey . '">' . $tmfval . '</option>';
                                        }
                                        ?> 
                                    </select>
                                </div>



                                <label>To</label>					 
                                <div class="input-group date custom-dtime padd-n-xs">			 
                                    <select class="time-set form-control" name="timeend" id="end-time" onchange="tcimecounts();">
                                        <?php
                                        foreach ($tmformat as $tmfkey => $tmfval) {
                                            echo '<option value="' . $tmfkey . '">' . $tmfval . '</option>';
                                        }
                                        ?> 
                                    </select>
                                </div>



                                <label>of the</label>
                                <select id="schedule_event_seires" class="schedule_event_seires form-control" onchange="tcimecounts();">
                                    <?php
                                    foreach ($ofheday as $ofdkey => $ofdval) {
                                        echo '<option value="' . $ofdkey . '">' . $ofdval . '</option>';
                                    }
                                    ?> 
                                </select>
                            </div>  
                        </div>   


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row"> 
                                <div class="date-range-section">
                                    <label id="custom_change" class="fullheading">Occurs from</label>	
                                    <div class="ouur_form_head">
                                        <input type="text" id="arrdatepicker" class="form-control" />
                                    </div>
                                    <div class="until-datesection" id="until-datesection">
                                        <label>Until</label>
                                        <input type="text" id="depdatepicker" class="form-control"/>
                                    </div>	
                                </div>	
                            </div>	
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">  
                                <div style="display:none" id="daycount">
                                    <div id="sdate_label"><div id="vslabel"></div> dates</div>
                                    <div id="timecount-est"></div>
                                </div>

                                <p id="diff_time"></p>
                                <div class="add_cancle"><p id="show_date"></p>
                                    <input type='hidden' value="" id="sd"/>
                                    <input type='hidden' value="" id="ed"/>
                                    <div>
                                        <a id="add-event-btn" href="javascript:void(0)">Add</a>
                                        <a id="cancel-event-btn" href="javascript:void(0)">Cancel</a>
                                    </div>
                                </div>	  
                            </div>	
                        </div>

                    </div>
                    <div class="form-group col-lg-12 col-sm-12 col-md-12 col-xs-12"> 
                        <div class="row">
                            <div class="schedule_event_div col-lg-3 col-sm-12 col-md-3 col-xs-12">
                                <div class="row">
                                    <a class="schedule_event" id="schedule_event" href="javascript:void(0);">Schedule multiple events</a>
                                    <a class="new_sevent" style="display:none;" id="add_edate" href="javascript:void(0);">Add Dates</a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12 col-md-3 col-xs-12">
                                <div class="row">
                                    <a class="new_sevent" style="display:none;" id="cancel_repevent" href="javascript:void(0);">Cancel repeating event schedule</a>
                                </div>     
                            </div>
                        </div>
                    </div>
                    <!-- multi-end-->		  
                </div>
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
                    Public Event
                </div>
                <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                    <input type="checkbox" class="pubprv" id="prv-evntjs" name="private_event" value="y" />
                    Private Event <h6>(If check, Event will not be advertised to public. Only people you send the Event link to will have access to the Information)</h6>
                </div>
                {!! $errors->first('private_event', '<span class="help-block with-errors">Listing Privacy: Please select one option on here (Public/Private)</span>') !!} 
                <div class="col-lg-11 col-sm-11 col-xs-11 rem-text" id="private_option" style=" @if(!empty(old('private_event')) && old('private_event') == 'y') display:block; @else  display:none; @endif float: right;">
                    <input type="checkbox" class="sip_event" name="sharepinvt_event" value="y" checked />
                    Attendees can share this event on Facebook, Twitter, and LinkedIn </br> 
                    <input type="checkbox" class="sip_event" name="sharepinvt_event" value="n"/>
                    This event is by Invite-Only (guests must receive an Eventbrite invitation to attend) </br>

                    @if(!empty(Session::get('share_event')))
                    <span class="help-block with-errors">Please choose one option,its required.</span>
                    @endif 
                    <input type="checkbox" name="passprv_event" value="y"/>
                    Require a password to view the event page:  
                    <input type="text" value="" class="form-control" placeholder="Enter a password" name="pr-passevnt"/>	
                    @if(!empty(Session::get('pev_req')))
                    <span class="help-block with-errors">Private event password field is required.</span>
                    @endif  
                </div>

                <h4 class="event-head">Event is:</h4>
                <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                    <input type="checkbox" name="kid_event" value="y">
                    for Kids
                </div>
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
    <input type="hidden" name="" id='baseCountryUrl' value='{!!URL("cotryevnt")!!}/' />
</section>
@stop
@section('scripts')
<script>
    var clicon = "{!!URL::to('assets/public/default2/images/calendar-icon.png')!!}";
    jQuery('#event_category').load('{!!URL("account/event-data/prsnl/indoor/ndye")!!}');
    jQuery('#one_ours').load('{!!URL("account/event-img/Camps")!!}');
    jQuery('body').delegate(".event_category", 'change', function () {
            var secat = $("select.event_category option:selected").text();
            var secat_url = secat.replace(/ /g, "_");
            $('#one_ours').load('{!!URL("account/event-img")!!}/' + secat_url);
        });
     jQuery('body').delegate(".event_type", 'click', function () {
            var gradioValue = jQuery("input[name='event_type']:checked").val();
            jQuery('#event_category').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive" style="width:30px"/>');
            if (gradioValue) {
                jQuery('#event_category').load('{!!URL("account/event-data/prsnl/' + gradioValue + '/ndye")!!}');
            }
        });   
</script>
{!!HTML::script("assets/public/default2/js/event/event-multidate.js")!!}
{!!HTML::script("assets/public/default2/js/event/create-update.js")!!}
@stop
