@extends('backend/default/custom_layouts._layout')
@section('styles')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" href="{!! URL::to('assets/backend/default/plugins/data-tables/DT_bootstrap.css') !!}" />
    <!-- END PAGE LEVEL STYLES -->
@stop

@section('content')
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN EXAMPLE TABLE widget-->
            <div class="widget light-gray box">
                <div class="blue widget-title">
                    <h4>{!!$sing_event[0]->event_name!!} Event</h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#widget-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
				
                <div class="widget-body widget-container form">
                    <div class="tabbable widget-tabs">
                        <div class="tab-content">
                            <div id="widget_tab1" class="tab-pane active">
                                <!-- BEGIN FORM-->
                        <form enctype="multipart/form-data" class="form-horizontal" accept-charset="UTF-8" method="POST">
                                    <div class="control-group ">
                                        <label class="control-label"><b>Event Name:</b></label>
                                        <div class="controls">
                                            <label class="control-label">
											
		<a href="{!! URL::to('/event/'.$sing_event[0]->event_url) !!}" target="_blank">{!!$sing_event[0]->event_name!!}</a>
											
											</label>
                                            
                                        </div>
                                    </div>
									
                                   <div class="control-group ">
                                        <label class="control-label"><b>Event will occur:</b></label>
                                        <div class="controls">
                                            <label class="control-label">{!!$sing_event[0]->event_type!!}</label>
                                            
                                        </div>
                                    </div>
									  <div class="control-group ">
                                        <label class="control-label"><b>Event Category:</b></label>
                                        <div class="controls">
                                            <label class="control-label">
	<?php 
	 $ev_ctname = DB::table('event_data')->where('id', '=', $sing_event[0]->event_catid)->select('event_category')->get();		 
	?>										
	{!!$ev_ctname[0]->event_category!!}</label>
                                            
                                        </div>
                                    </div>
									
									  <div class="control-group ">
                                        <label class="control-label"><b>Event Image:</b></label>
                                        <div class="controls">
                                            <label class="control-label">
  @if(!empty($sing_event[0]->event_image))	
   @if(is_numeric($sing_event[0]->event_image)) 	 
     <?php $evtoimg = DB::table('event_catimages')->where('id', '=', $sing_event[0]->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	
    @if(!empty($evtoimg[0]->id))	
     <img style="width:100px;" src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}"/>
    @endif 
   @else			 
	 <img style="width:100px;" src="{!!URL::to('uploads/events/'.$sing_event[0]->account_type.'/'.$sing_event[0]->event_image)!!}"/>
   @endif 		
  @else
	  n/a
  @endif   										
											
	</label>
                                            
                                        </div>
                                    </div>
									  <div class="control-group ">
                                        <label class="control-label"><b>Venue/Location:</b></label>
                                        <div class="controls">
                                            <label class="control-label">
				{!!$sing_event[0]->event_venue!!} ,	{!!$sing_event[0]->event_address!!}							
				{!!$sing_event[0]->address_secd!!}		{!!$sing_event[0]->city!!}	 {!!$sing_event[0]->state!!}	
   <?php $event_cuntres = DB::table('countries')->where('id', '=', $sing_event[0]->country)->select('name')->get(); ?>	
  				
						 {!!$event_cuntres[0]->name!!}	,	{!!$sing_event[0]->zip_code!!}					
											
											</label>
                                            
                                        </div>
                                    </div>
									  <div class="control-group ">
                                        <label class="control-label"><b>Event Description:</b></label>
                                        <div class="controls">
                                            <label class="control-label">{!!$sing_event[0]->event_description!!}</label>
                                            
                                        </div>
                                    </div>
									  <div class="control-group ">
                                        <label class="control-label"><b>Cost:</b></label>
                                        <div class="controls">
                                            <label class="control-label">
	@if($sing_event[0]->event_cost == 'paid')
	  $ {!!$sing_event[0]->event_price!!}	<br> 	{!!$sing_event[0]->ticket_surl!!}	
	@else
	  {!!$sing_event[0]->event_cost!!}	
	@endif		
									
											</label>
                                            
                                        </div>
                                    </div>
									  <div class="control-group ">
                                        <label class="control-label"><b>Starts:</b></label>
                                        <div class="controls">
                                            <label class="control-label">{!!$sing_event[0]->event_date!!}	</label>
                                            
                                        </div>
                                    </div>
									  <div class="control-group ">
                                        <label class="control-label"><b>Ends:</b></label>
                                        <div class="controls">
                                            <label class="control-label">{!!$sing_event[0]->end_date!!}	</label>
                                            
                                        </div>
                                    </div>
									  <div class="control-group ">
                                        <label class="control-label"><b>Social Media:</b></label>
                                        <div class="controls">
                                            <label class="control-label">{!!$sing_event[0]->fb_link!!} <br>
										{!!$sing_event[0]->tw_link!!}	
											</label>
                                            
                                        </div>
                                    </div>
									  <div class="control-group ">
                                        <label class="control-label"><b>Listing Privacy:</b></label>
                                        <div class="controls">
                                            <label class="control-label">
			@if($sing_event[0]->private_event == 'n')
			  Public Event	
            @else
			  Private Event 
		    @endif	
											
											</label>
                                            
                                        </div>
                                    </div>
									  <div class="control-group ">
                                        <label class="control-label"><b>Event is</b></label>
                                        <div class="controls">
                                            <label class="control-label">
			@if($sing_event[0]->kid_event == 'y')
				 for Kids <br>
			@endif	
		
			@if($sing_event[0]->family_event == 'y')
				 for Families  <br>
            @endif				
			
			@if($sing_event[0]->religious_event == 'y')
				 a religious nature  <br>
            @endif				
											</label>
                                            
                                        </div>
                                    </div>
									

                                    <br>
                                </form>
                                <!-- END FORM-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE widget-->
        </div>
    </div>
@stop

@section('scripts')
     
@stop
