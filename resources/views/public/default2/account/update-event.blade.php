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
            <h3> Edit Event </h3>
            <form class="form-inline" method="post" action="" enctype="multipart/form-data">


                <!--error fields starts-->


                @if($errors->has() && count($errors) > 0 )
                @foreach ($errors->all() as $key => $error)
                <span class="col-md-12 full-error alert {{$key}}}">
                    {{$error}}
                </span>	
                @endforeach
                @endif


                @if(!empty(Session::get('share_event')))
                <span class="col-md-12 full-error alert">
                    {{Session::get('share_event')}}
                </span>
                @endif


                @if(!empty(Session::get('inv_tkurl')))
                <span class="col-md-12 full-error alert">
                    <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                    <strong>Whoops!</strong> {{Session::get('inv_tkurl')}}
                </span>
                @endif

                @if(!empty(Session::get('invl_url')))
                <span class="col-md-12 full-error alert">
                    <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                    <strong>Whoops!</strong> {{Session::get('invl_url')}}
                </span>
                @endif



                @if(!empty(Session::get('ev_date')))
                <span class="col-md-12 full-error alert">
                    <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                    <strong>Whoops!</strong> {{Session::get('ev_date')}}
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
                        <input type="text" name="contact_person" class="form-control" value="{!!$edit_event[0]->contact_person!!}"/>
                    </div>
                </div>
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <label class="col-lg-3 col-sm-3 col-xs-12 row"> Phone Number :
                    </label>
                    <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                        <input type="text" name="phone_no" id="phone_no" class="form-control" value="{!!$edit_event[0]->phone_no!!}"/>
                        {!! $errors->first('phone_no', '<span class="help-block with-errors">:message</span>') !!}	
                    </div>
                </div>
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <label class="col-lg-3 col-sm-3 col-xs-12 row"> Email Address:
                    </label>
                    <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                        <input type="email" name="email_address" class="form-control" value="{!!$edit_event[0]->email_address!!}"/>
                        {!! $errors->first('email_address', '<span class="help-block with-errors">:message</span>') !!}	 
                    </div>
                </div>
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <label class="col-lg-3 col-sm-3 col-xs-12 row"> Website:
                    </label>
                    <div class="col-lg-6 col-sm-9 col-xs-12 send"> 
                        <input type="text" name="website" class="form-control" value="{!!$edit_event[0]->website!!}"/>
                        {!! $errors->first('website', '<span class="help-block with-errors">:message</span>') !!}	
                    </div>
                </div>

                <h4 class="event-head"><span class="ico-box ico--small">2</span> Event Details</h4>
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Event will occur:
                    </label>
                    <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
                        <input type="radio" name="event_type" class="event_type ind" value="indoor" @if(!empty($edit_event[0]->event_type) && $edit_event[0]->event_type == 'indoor') checked @endif /> Indoor  &nbsp; | &nbsp;
                               <input type="radio" name="event_type" class="event_type ind" value="outdoor" @if(!empty($edit_event[0]->event_type) && $edit_event[0]->event_type == 'outdoor') checked @endif /> Outdoor 
                    </div>
                </div>
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Event Category:</label>
                    <div class="col-lg-6 col-sm-6 col-xs-12 send" id="event_category"> 
                        <img src="{!!URL::to('assets/public/default2/images/progress.gif')!!}" class="img-responsive" style="width:30px"/>
                    </div>
                    {!! $errors->first('event_catid', '<span class="help-block with-errors">Event Category is required field</span>') !!}	
                </div>			 

                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Event Name:
                    </label>
                    <div class="col-lg-6 col-sm-6 col-xs-12 send"> 
                        <input type="text" name="event_name" class="form-control" value="{!!$edit_event[0]->event_name!!}" required />
                        {!! $errors->first('event_name', '<span class="help-block with-errors">:message</span>') !!} 
                    </div>
                </div>	
                
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <label class="col-lg-3 col-sm-3 col-xs-12 row">Event image:</label>
                    <div class="col-lg-6 col-sm-6 col-xs-12">
                        <div class="col-lg-2 col-sm-2 col-xs-12">
                            @if(!empty($edit_event[0]->event_image))	
                            @if(is_numeric($edit_event[0]->event_image)) 	 
                            <?php $evtoimg = DB::table('event_catimages')->where('id', '=', $edit_event[0]->event_image)->select('id', 'ecat_name', 'ecat_path', 'ecat_image')->get(); ?>
                            @if(!empty($evtoimg[0]->id))			   
                            <img src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}" class="img-responsive"/>
                            @endif		
                            @else
                            <img src="{!!URL::to('uploads/events/'.$edit_event[0]->account_type.'/'.$edit_event[0]->event_image)!!}" class="img-responsive"/>  
                            @endif			   
                            @else
                            n/a	
                            @endif
                        </div>					
                        <div class="col-lg-10 col-sm-9 col-xs-12">		 
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
                </div>
                
                
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Venue/Location:
                    </label>
                    <div class="col-lg-9 col-sm-9 col-xs-12 send"> 
                        <?php
                        $all_address = null;
                        if (!empty($edit_event[0]->event_venue)) {
                            $all_address .= $edit_event[0]->event_venue;
                        }
                        if (!empty($edit_event[0]->event_address)) {
                            if (!empty($all_address)) {
                                $all_address .= ',' . $edit_event[0]->event_address;
                            } else {
                                $all_address = $edit_event[0]->event_address;
                            }
                        }
                        if (!empty($edit_event[0]->address_secd)) {
                            if (!empty($all_address)) {
                                $all_address .= ',' . $edit_event[0]->address_secd;
                            } else {
                                $all_address = $edit_event[0]->address_secd;
                            }
                        }
                        if (!empty($edit_event[0]->city)) {
                            if (!empty($all_address)) {
                                $all_address .= ',' . $edit_event[0]->city;
                            } else {
                                $all_address = $edit_event[0]->city;
                            }
                        }

                        if (!empty($edit_event[0]->state) && !empty($edit_event[0]->country)) {
                            $getsdata = DB::table('states')->where('country_id', '=', $edit_event[0]->country)->where('name', '=', $edit_event[0]->state)->select('id', 'name')->get();
                            if (!empty($getsdata[0]->id)) {
                                $staten = $getsdata[0]->name;
                            } else {
                                $staten = $edit_event[0]->state;
                            }
                        } else {
                            $staten = null;
                        }
                        if (!empty($staten)) {
                            if (!empty($all_address)) {
                                $all_address .= ',' . $staten;
                            } else {
                                $all_address = $staten;
                            }
                        }

                        if (!empty($edit_event[0]->country)) {
                            $getcdata = DB::table('countries')->where('id', '=', $edit_event[0]->country)->select('id', 'name')->get();
                            if (!empty($getcdata[0]->id)) {
                                $sel_count = '<option value="' . $getcdata[0]->id . '" selected >' . $getcdata[0]->name . '</option>';
                            }
                        } else {
                            $sel_count = null;
                        }
                        if (!empty($edit_event[0]->country)) {
                            if (!empty($all_address)) {
                                $all_address .= ',' . $getcdata[0]->name;
                            } else {
                                $all_address = $getcdata[0]->name;
                            }
                        }
                        ?>	  
                        <div id="slocation">
                            <input type="text" name="event_all" id="us3-address" class="form-control" value="{!!$all_address!!}" />				 
                            <input type="hidden" class="form-control" id="us3-radius"/>
                            <input type="hidden" class="form-control" id="us3-lat"/>
                            <input type="hidden" class="form-control" id="us3-lon"/>
                            <a href="javascript:void(0)" id="open_alldetail">Enter Address</a>
                            {!! $errors->first('event_venue', '<span class="help-block with-errors">Venue/Location field is required</span>') !!} 
                            {!! $errors->first('country', '<span class="help-block with-errors">Venue/Location field is required</span>') !!} 
                        </div> 
                        <div id="alloc_fileds" style="display:none">		
                            <div class="map-enter col-md-7 col-sm-12 col-xs-12">
                                <input type="text" name="event_venue" placeholder="Enter the venue's name" id="us3-venue" class="form-control" value="{!!$edit_event[0]->event_venue!!}"/> 
                                {!! $errors->first('event_venue', '<span class="help-block with-errors">:message</span>') !!} 	
                                <input type="text" name="event_address" placeholder="Address" id="ev_address" class="form-control" value="{!!$edit_event[0]->event_address!!}"/>	
                                {!! $errors->first('event_address', '<span class="help-block with-errors">:message</span>') !!} 
                                <input type="text" name="address_secd" placeholder="Address 2" id="ev_addres" class="form-control" value="{!!$edit_event[0]->address_secd!!}"/>	
                                {!! $errors->first('address_secd', '<span class="help-block with-errors">:message</span>') !!} 
                                <input type="text" name="city" placeholder="City" id="evt_city" class="form-control full" value="{!!$edit_event[0]->city!!}" required />	  
                                {!! $errors->first('city', '<span class="help-block with-errors">:message</span>') !!} 
                                <input type="text" name="state" placeholder="State" id="evt_state" class="form-control full" value="{!! $staten !!}"/>   
                                {!! $errors->first('state', '<span class="help-block with-errors">:message</span>') !!} 	
                                <input type="text" name="zip_code" placeholder="Zip/Postal" id="evt_zip" class="form-control full" value="{!! $edit_event[0]->zip_code !!}"/>  
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
                        </div>	  
                    </div>
                </div>
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <label class="col-lg-3 col-sm-3 col-xs-12 row">
                        <span class="require-val">*</span>
                        Event Description: 
                    </label>
                    <div class="col-lg-9 col-sm-9 col-xs-12 send"> 
                        <textarea name="event_description" id="event_description" class="form-control ckeditor">{!!$edit_event[0]->event_description!!}</textarea>
                        {!! $errors->first('event_description', '<span class="help-block with-errors">:message</span>') !!}  
                    </div>
                </div>
                <?php $event_cost = $edit_event[0]->event_cost; ?>
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    <label class="col-lg-3 col-sm-3 col-xs-12 row"> <span class="require-val">*</span>Cost: 
                    </label>
                    <div class="col-lg-6 col-sm-9 col-xs-12 send"> 

                        <input type="radio" name="event_cost" class="event_cost" value="free" 
                               @if(!empty($event_cost) && $event_cost == 'free') 
                               checked 
                               @else 
                               checked 
                               @endif
                               />
                               Free Event  &nbsp; &nbsp;
                               <input type="radio" name="event_cost" class="event_cost" value="paid" 
                               @if(!empty($event_cost) && $event_cost == 'paid') 
                               checked 
                               @endif /> 
                               Paid($) 
                               {!! $errors->first('event_cost', '<span class="help-block with-errors">:message</span>') !!}                
                    </div>
                </div>	
                <div class="form-group col-lg-12 col-sm-12 col-xs-12" id="event-price" @if(!empty($event_cost) && $event_cost == 'paid') style="display:block;" @else style="display:none;" @endif>	
                     <label class="col-lg-3 col-sm-3 col-xs-12 row">Ticket Price: 
                    </label>	
                    <?php
                    if (!empty($edit_event[0]->event_price)) {
                        $ev_price = explode("-", $edit_event[0]->event_price);
                    } else {
                        $ev_price = null;
                    }
                    ?>	
                    <div class="col-lg-6 col-sm-9 col-xs-12 send">
                        <label class="lab">
                            <span class="icon-prepend">$</span>
                            <input name="event_price" id="event_price" value="{!!$ev_price[0]!!}" placeholder="Enter a single dollar amount" type="text" class="form-control"/>				 
                            <b>Flat Rate/Starting Amount</b>
                        </label> 
                        <label class="lab">
                            <span class="icon-prepend">$</span>

                            <input name="mevent_price" id="mevent_price" value="@if(isset($ev_price[1])){{$ev_price[1]}}@endif" placeholder="Enter a single dollar amount" type="text" class="form-control"/>				 			
                            <b>Maximum Amount</b>
                        </label>
                        <b>Example:</b> 10 or 10.99
                        {!! $errors->first('event_price', '<span class="help-block with-errors">:message</span>') !!} 				  
                        @if(!empty(Session::get('req_evprice')))
                        <span class="help-block with-errors">{{ Session::get('req_evprice') }}</span>
                        @endif  
                    </div>
                </div> 
                <div class="form-group col-lg-12 col-sm-12 col-xs-12" id="maintk_surl" @if(!empty($event_cost) && $event_cost == 'paid') style="display:block;" @else style="display:none;" @endif>
                     <label class="col-lg-3 col-sm-3 col-xs-12 row">Ticket Sales URL: 
                    </label>
                    <div class="col-lg-9 col-sm-9 col-xs-12 send url-link"> 
                        <label>
                         <!-- <span class="icon-prepend">{!!URL('ev/ticket/')!!}/</span>-->
                            <input name="ticket_surl" value="{!!$edit_event[0]->ticket_surl!!}" id="ticket_surl" type="text" class="form-control"/>				 
                        </label>
                        @if(!empty(Session::get('inv_tkurl')))
                        <span class="help-block with-errors">{{ Session::get('inv_tkurl') }}</span>
                        @endif  			 
                        {!! $errors->first('ticket_surl', '<span class="help-block with-errors">The Ticket Sales URL field is required.</span>') !!} 
                    </div>
                </div>

                <h4 class="event-head"><span class="ico-box ico--small">3</span>Event Date(s)</h4>	
                <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                    @if($edit_event[0]->event_dtype == 'multi')
                    <div class="form-group col-lg-12 col-sm-12 col-xs-12 start-end" style="display:none">	 
                        @else 
                        <div class="form-group col-lg-12 col-sm-12 col-xs-12 start-end">	 
                            @endif 
                            <div class="col-lg-6 col-sm-6 col-xs-12 row margin-b10">
                                <div class="row">
                                    <label class="width-set"> <span class="require-val">*</span>Starts: </label>			
                                    <div class="input-group date custom-dtime padd-n-xs" id="datetimepicker2"> 
                                        <?php
                                        if (!empty($edit_event[0]->event_date)) {
                                            $ev_date = date('m/d/Y', strtotime($edit_event[0]->event_date));
                                        } else {
                                            $ev_date = null;
                                        }
                                        ?>
                                        <input type="text" name="sevent_date" class="form-control" id="sevent_date" value="{!!$ev_date!!}"/>				 
                                        {!! $errors->first('sevent_date', '<span class="help-block with-errors">:message</span>') !!}  		 
                                        @if(!empty(Session::get('ev_date')))
                                        <span class="help-block with-errors">Event Start date field is required</span>
                                        @endif
                                    </div>
                                    <div class="input-group date custom-dtime padd-n-xs">			 
                                        <select class="time-set form-control" name="sevent_time">
                                            <div class="ss">
                                                @if(!empty($edit_event[0]->event_time))
                                                <option value="{!!$edit_event[0]->event_time!!}" selected>{!!$edit_event[0]->event_time!!}</option>
                                                @endif	
                                                @foreach($st_cvs as $scsv)
                                                @if(!empty($edit_event[0]->event_time))
                                                @if($edit_event[0]->event_time != $scsv)	
                                                <option value="{!!$scsv!!}">{!!$scsv!!}</option> 
                                                @endif	
                                                @else
                                                <option value="{!!$scsv!!}">{!!$scsv!!}</option> 	
                                                @endif		  
                                                @endforeach 
                                            </div>
                                        </select>
                                        {!! $errors->first('sevent_time', '<span class="help-block with-errors">:message</span>') !!}   		 
                                    </div> 
                                </div>
                            </div>		
                            <div class="col-lg-12 col-sm-6 col-xs-12 row margin-b10">
                                <div class="row">
                                    <label class="width-set">Ends: &nbsp &nbsp </label>			
                                    <?php
                                    if (!empty($edit_event[0]->end_date)) {
                                        $end_date = date('m/d/Y', strtotime($edit_event[0]->end_date));
                                    } else {
                                        $end_date = null;
                                    }
                                    ?>
                                    <div class="input-group date custom-dtime padd-n-xs" id="datetimepicker3"> 
                                        <input type="text" name="event_date" id="event_date" class="form-control" value="{!!$end_date!!}"/>				
                                        {!! $errors->first('event_date', '<span class="help-block with-errors">:message</span>') !!} 				 
                                    </div>
                                    <div class="input-group date custom-dtime padd-n-xs">			 
                                        <select class="time-set" name="eevent_time">
                                            @if(!empty($edit_event[0]->end_time))
                                            <option value="{!!$edit_event[0]->end_time!!}" selected>{!!$edit_event[0]->end_time!!}</option>
                                            @endif	
                                            @foreach($st_cvs as $scsv)
                                            @if(!empty($edit_event[0]->end_time))
                                            @if($edit_event[0]->end_time != $scsv)	
                                            <option value="{!!$scsv!!}">{!!$scsv!!}</option> 
                                            @endif	
                                            @else
                                            <option value="{!!$scsv!!}">{!!$scsv!!}</option> 	
                                            @endif		  
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

                        <input type="hidden" name="event_dtype" class="form-control" id="event_dtype" value="{!!$edit_event[0]->event_dtype!!}"/>
                        <!-----multi-date----->	
                        <div class="new-period">
                            @if($edit_event[0]->event_dtype == 'multi')	 
                            <?php $wanotfdata = DB::table('event_multidate')->where('ev_id', '=', $evid)->get();
                            $p = 1;
                            ?>
                            @if(sizeof($wanotfdata) > 0)
                            @foreach($wanotfdata as $wtfd)
                            <p id="new-period{{$p}}" class="newitc-periods">
                                <span class="multi-ctdata">
                                    <label><span id="cvs-count{{$p}}" class="cvs-count">{!!$wtfd->total_day!!}</span><br>Dates</label>
                                </span>
                                <span class="multi-listdata">
                                    <span class="evdetails">{!!$wtfd->event_datetype!!} 
                                        @if($wtfd->event_datetype == 'Monthly')
                                        on the {!!$wtfd->mw_date!!}
                                        @endif 	  	  
                                        @if($wtfd->event_datetype == 'Weekly')
                                        on {!!$wtfd->mw_date!!}
                                        @endif 	 	  
                                        {!!$wtfd->st_format!!} - {!!$wtfd->et_format!!} 	  
                                        @if($wtfd->ofthe == 1)
                                        on the Next day    
                                        @elseif($wtfd->ofthe == 2)	  
                                        on the 2nd day
                                        @elseif($wtfd->ofthe == 3)
                                        on the 3rd day	   
                                        @elseif($wtfd->ofthe == 4)	
                                        on the 4th day
                                        @elseif($wtfd->ofthe == 5)	
                                        on the 5th day
                                        @elseif($wtfd->ofthe == 6)	
                                        on the 6th day
                                        @endif
                                    </span><br>
                                    <span class="sd">Starting <?php echo date('m/d/Y', strtotime($wtfd->start_date)); ?> through <?php echo date('m/d/Y', strtotime($wtfd->enddate)); ?></span>
                                </span>
                                <span class="multi-removediv"> 
                                    <a onclick="editmud({{$p}});" href="javascript:void(0)" class="event-edit">Edit</a> | 
                                    <a id="remScnt" onclick="removemud({{$p}});" href="javascript:void(0)" class="event-remove">Remove</a>
                                </span>
                                <input type="hidden" name="arrdate[]" id="arrdate{{$p}}" value="<?php echo date('m/d/Y', strtotime($wtfd->start_date)); ?>">
                                <input type="hidden" name="enddate[]" id="enddate{{$p}}" value="<?php echo date('m/d/Y', strtotime($wtfd->enddate)); ?>">
                                <input type="hidden" name="ofthe[]" id="ofthe{{$p}}" value="{!!$wtfd->ofthe!!}">
                                <input type="hidden" name="startime[]" id="startime{{$p}}" value="{!!$wtfd->start_time!!}">
                                <input type="hidden" name="endtime[]" id="endtime{{$p}}" value="{!!$wtfd->endtime!!}">
                                <input type="hidden" name="nspd[]" id="nspd{{$p}}" value="{!!$wtfd->event_datetype!!}">
                                <input type="hidden" name="wgtdaysvl[]" id="wgtdaysvl{{$p}}" value="{!!$wtfd->multype_wm!!}">
                                <input type="hidden" value="{!!$wtfd->st_format!!}" id="st_format{{$p}}" name="st_format[]"/>	 
                                <input type="hidden" value="{!!$wtfd->et_format!!}" id="et_format{{$p}}" name="et_format[]"/>	
                                <input type="hidden" value="{!!$wtfd->mw_date!!}" id="mw_date{{$p}}" name="mw_date[]"/>
                                <input type="hidden" value="{!!$wtfd->total_day!!}" id="total_day{{$p}}" name="total_day[]"/> 
                            </p>
                            <input type="hidden" value="{!!$wtfd->id!!}" id="multieid{{$p}}" name="multieid[]"/>
<?php $p++; ?>
                            @endforeach
                            @endif	
                            <input type="hidden" id="count_p" value="{!!sizeof($wanotfdata)!!}"/>	   
                            @else
                            <input type="hidden" id="count_p" value="0"/>	
                            @endif	

                        </div>

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
                        <!---multi-end--->  
                    </div>

                    <h4 class="event-head"><span class="ico-box ico--small">4</span>Additional options</h4>
                    <?php
                    if (!empty($edit_event[0]->fb_link) || !empty($edit_event[0]->tw_link)) {
                        $social_link = 'y';
                    } else {
                        $social_link = 'n';
                    }
                    ?>
                    <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                        Social Media<br>
                        <input type="checkbox" name="social_link" id="social_link" value="y" @if(!empty($social_link) && $social_link == 'y') checked @endif />
                               Include links to Facebook and Twitter 
                    </div>
                    <div class="col-lg-12 col-sm-12 col-xs-12" id="soc_url" @if(!empty($social_link) && $social_link == 'y') style="display:block;" @else style="display:none;" @endif>
                         <div>
                            <span class="fb"> </span> facebook.com/
                            <input type="text" name="fb_link" class="form-control mr-btm" value="{!! $edit_event[0]->fb_link !!}"/>
                        </div>
                        <div>
                            <span class="tw"> </span> twitter.com/ 
                            <input type="text" name="tw_link" class="form-control mr-btm" value="{!! $edit_event[0]->tw_link !!}"/>
                        </div>
                    </div>  
                    <h4 class="event-head">Listing Privacy</h4>
                    <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                        <input type="checkbox" class="pubprv" name="private_event" value="n" @if($edit_event[0]->private_event == 'n') checked @endif />
                               Public Event</div>
                    <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                        <input type="checkbox" class="pubprv" name="private_event" id="prv-evntjs" value="y" @if($edit_event[0]->private_event == 'y') checked @endif />
                               Private Event <h6>(If check, Event will not be advertised to public. Only people you send the Event link to will have access to the Information)</h6>
                    </div>
                    {!! $errors->first('private_event', '<span class="help-block with-errors">Listing Privacy: Please select one option on here (Public/Private)</span>') !!} 	
                    <div class="col-lg-11 col-sm-11 col-xs-11 rem-text" id="private_option" style="@if($edit_event[0]->private_event == 'y') display:block; @else  display:none; @endif float: right;">
                        <input type="checkbox" class="sip_event" name="sharepinvt_event" value="y" @if($edit_event[0]->share_event == 'y') checked @endif />
                               Attendees can share this event on Facebook, Twitter, and LinkedIn </br> 
                        <input type="checkbox" class="sip_event" name="sharepinvt_event" value="n" @if($edit_event[0]->share_event == 'n') checked @endif />
                               This event is by Invite-Only (guests must receive an Eventbrite invitation to attend) </br>

                        @if(!empty(Session::get('share_event')))
                        <span class="help-block with-errors">Please choose one option,its required.</span>
                        @endif 
                        <input type="checkbox" name="passprv_event" value="y" @if($edit_event[0]->password_estatus == 'y') checked @endif />
                               Require a password to view the event page:  
                               <input type="text" value="{!!$edit_event[0]->pass_event!!}" class="form-control" placeholder="Enter a password" name="pr-passevnt"/>	
                        @if(!empty(Session::get('pev_req')))
                        <span class="help-block with-errors">Private event password field is required.</span>
                        @endif  
                    </div>

                    <h4 class="event-head">Event is:</h4>	
                    <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                        <input type="checkbox" name="kid_event" value="y" @if($edit_event[0]->kid_event == 'y') checked @endif /> for Kids
                    </div>
                    <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                        <input type="checkbox" name="family_event" value="y" @if($edit_event[0]->family_event == 'y') checked @endif /> for Families 
                    </div>
                    <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
                        <input type="checkbox" name="religious_event" value="y" @if($edit_event[0]->religious_event == 'y') checked @endif /> a religious nature
                    </div>	  
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group col-lg-6 col-sm-12 col-md-6 col-xs-12"> 
                        <input type="submit" class="form-control follow update-btn" Value="Update">
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
</script>
{!!HTML::script("assets/public/default2/js/event/event-multidate.js")!!}
<script>
    $(document).ready(function(){
        
        $('#event_category').load('{!!URL("account/event-data/prsnl/".$edit_event[0]->event_type."/".$edit_event[0]->event_catid)!!}');
        
        $('body').delegate(".event_category", 'change', function(){
            var secat = $("select.event_category option:selected").text();
            var secat_url = secat.replace(/ /g, "_");
            $('#one_ours').load('{!!URL("account/event-img")!!}/' + secat_url);
        });
        $('#one_ours').delay(4000).queue(function(vsnxt) {
        var vsxct = $("select.event_category option:selected").text();
        var vsxct_ul = vsxct.replace(/ /g, "_");
        $(this).load('{!!URL("account/event-img/")!!}/' + vsxct_ul);
        vsnxt();
        });
     
        
          
          
        $('.pubprv').click(function() {
        $('input.pubprv').not(this).prop('checked', false);
        });
    });
    
    jQuery('body').delegate(".event_type", 'click', function(){
            
            var gradioValue = $("input[name='event_type']:checked").val();
            jQuery('#event_category').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive" style="width:30px"/>');
            if (gradioValue){
                console.log('{!!URL("account/event-data/prsnl/' + gradioValue + '/ndye")!!}');
                jQuery('#event_category').load('{!!URL("account/event-data/prsnl/' + gradioValue + '/ndye")!!}');
                
                } 
          });
          
    </script> 
    
    {!!HTML::script("assets/public/default2/js/event/create-update.js")!!}
    
@if(!empty($all_address))
<script>
    $(document).ready(function(){
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': '{!! $all_address !!}'}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK){
        var latlong = results[0].geometry.location.lat();
        var lnglong = results[0].geometry.location.lng();
        var vsLatLng = {lat: latlong, lng: lnglong}
        var map = new google.maps.Map(document.getElementById('us3'), {
            center: vsLatLng,
            zoom: <?php  echo config("app.googlezoom" , 10);?>,
        });
        
        var marker = new google.maps.Marker({
            position: vsLatLng,
            map: map,
            title: '{!! $all_address !!}'
            });
        }
        });
        jQuery( "#open_alldetail" ).trigger( "click" );
    });
    
</script>

@endif  
{!!HTML::script("assets/public/default2/js/event/event-multidate.js")!!}

@stop

