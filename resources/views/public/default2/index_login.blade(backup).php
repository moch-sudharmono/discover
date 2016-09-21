@section('styles')    
@stop
@section('content')
<section class="event-in-your-area">
  <div class="container text-center">
    <div class="col-md-8 col-sm-12 col-xs-12">
      <div class="map"> <img src="{!!URL::to('assets/public/default2/images/map.jpg')!!}" class="img-responsive"> </div>
      <div class="col-md-4 col-sm-4 col-xs-12 event-area-form">
       <form class="form-inline" id="refine_search" method="post">
          <h3> Refine Your Search </h3>
          <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
            <input type="text" placeholder="Location" name="ref_location" class="form-control">
          </div>
          <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
           <div class="sel">
            <select class="selectpicker" name="category" data-live-search="true" title="Category">
			 @if(!empty($evtsdata[0]))
			  @foreach($evtsdata as $etdt)	   
			   <option value="{!! $etdt !!}">{!! $etdt !!}</option>
			  @endforeach	
			 @endif
            </select>
           </div>
          </div>
		   <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
            <div class="sel">
              <select class="selectpicker" id="search_event" name="search_event" data-live-search="true">
                <option value="all">Search All</option> 
                <option value="attending">Attending Events</option>
                <option value="following">Following Events</option>
                <option value="your">Your Events</option>
              </select>
            </div>
          </div>
		   <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
            <div class="sel">
              <select class="selectpicker" id="search_page" name="search_page" data-live-search="true">
                <option value="all">Search All Page</option> 
                <option value="business">Bussiness Page</option>
                <option value="municipality">Municipality Page</option>
                <option value="club">Club Page</option>
              </select>
            </div>
          </div>
          <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
            <div class="sel input-group date" id="datetimepicker2"> 
			  <input type="text" name="event_date" class="form-control" placeholder="Date" value="{{ old('event_date') }}"/>
			   <span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			   </span>
            </div>
          </div>
          <div class="sel">
             <input type="name" name="optional_all" placeholder="Optional Keyword " class="form-control"/>          
          </div>
		  <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">	
          <input type="submit" value="Search" class="btn b-btn">
        </form>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12 tab-events text-left">
        <ul class="nav nav-pills">
          <li class="active"><a data-toggle="pill" href="#all_event" id="event_data">All Events</a></li>
          <!--<li><a data-toggle="pill" href="#attending">Attending</a></li>
          <li><a data-toggle="pill" href="#following">Following</a></li>
          <li><a data-toggle="pill" href="#our-events">Your Events</a></li>-->  
        </ul>
        <div class="tab-content">
          <div id="all_event" class="tab-pane fade in active">
		   @if(!empty($al_event[0]->id))
			@foreach($al_event as $aet) 
            <div class="rock-event all-events">
              <div class="evnt-pic col-md-5 col-ms-5 col-xs-12">
			  @if(!empty($aet->event_image))			  
			   <img src="{!!URL::to('uploads/events/'.$aet->account_type.'/'.$aet->event_image)!!}" class="img-responsive"/>
		      @else
			   <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"> 	  
			  @endif
			  </div>
              <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
                <h4><a href="{!!URL('event/'.$aet->event_url)!!}"> {!! $aet->event_name !!} </a> </h4>
                <div class="date"> <span class="date-icon"> </span>
				<?php 
				 if(!empty($aet->event_date)) {
				   $aev_date = date('M d, Y', strtotime($aet->event_date));
				 } else {
				   $aev_date = 'n/a'; 
				 }
				?>
			   {!! $aev_date !!} @ {!! $aet->event_time !!} 
				</div>
                <div class="location"> <span class="location-icon"> </span> {!! $aet->event_venue !!} / {!! $aet->event_address !!} </div>
                <div class="price"> <span class="price-icon"> </span> <strong>
				 @if($aet->event_cost == 'paid')
			   <?php $alet_price = explode("-",$aet->event_price ); ?>
			    @if(!empty($evnt_price[0]))
				<?php $alet_prc = '$'.$alet_price[0]; 			
			      for ($x = 1; $x < sizeof($alet_price); $x++) {
					  $alet_prc .= ' - $'.$alet_price[$x];
				  }			 
				 ?> 
				 {!! $alet_prc !!}
				@else
				${!! $aet->event_price !!}	
                @endif					
			   @else
			    {!! ucfirst($aet->event_cost) !!}  
               @endif  		
				</strong> </div>
                <?php $allevt_ct = DB::table('users_events')->where('e_id', '=', $aet->id)->count(); ?>		
               <div class="attend"> People Attending : <span class="blue-col"> <a href="#"> {!! $allevt_ct !!} </a> </span></div>
              </div>
            </div>
			@endforeach
		   @else 
			   <div class="rock-event"> Empty! not found </div>  
           @endif 
          </div>		  
		  
       <?php /*  <div id="attending" class="tab-pane fade">
		   @if(!empty($attend_event[0]->id))
			@foreach($attend_event as $att_evnt)    
            <div class="rock-event">
             <div class="evnt-pic col-md-5 col-ms-5 col-xs-12"> 
              @if(!empty($att_evnt->event_image))			  
			   <img src="{!!URL::to('uploads/events/'.$att_evnt->account_type.'/'.$att_evnt->event_image)!!}" class="img-responsive"/>
		      @else
			   <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"> 	  
			  @endif	
			 </div>
             <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
               <h4><a href="{!!URL('event/'.$att_evnt->event_url)!!}"> {!! $att_evnt->event_name !!} </a> </h4>
               <div class="date"> <span class="date-icon"> </span>
				<?php 
				 if(!empty($att_evnt->event_date)) {
				   $ev_date = date('M d, Y', strtotime($att_evnt->event_date));
				 } else {
				   $ev_date = 'n/a'; 
				 }
				?>
			   {!! $ev_date !!} @ {!! $att_evnt->event_time !!} </div>
               <div class="location"> <span class="location-icon"> </span> {!! $att_evnt->event_venue !!} / {!! $att_evnt->event_address !!} </div>
               <div class="price"> <span class="price-icon"> </span> <strong>
			   @if($att_evnt->event_cost == 'paid')
			   <?php $evnt_price = explode("-",$att_evnt->event_price ); ?>
			    @if(!empty($evnt_price[0]))
				<?php $et_prc = '$'.$evnt_price[0]; 			
			      for ($x = 1; $x < sizeof($evnt_price); $x++) {
					  $et_prc .= ' - $'.$evnt_price[$x];
				  }			 
				 ?> 
				 {!! $et_prc !!}
				@else
				${!! $att_evnt->event_price !!}	
                @endif					
			   @else
			    {!! ucfirst($att_evnt->event_cost) !!}  
               @endif  				   
			   </strong> </div>
		       <?php $atevt_count = DB::table('users_events')->where('e_id', '=', $att_evnt->id)->count(); ?>		
               <div class="attend"> People Attending : <span class="blue-col"> <a href="#"> {!! $atevt_count !!} </a> </span></div>
             </div>
            </div>
			@endforeach
		   @else 
			   <div class="rock-event"> Empty! You are not attending any event </div>  
           @endif 			   
			
          </div>
		  
         <div id="following" class="tab-pane fade">
		  @if(!empty($fl_event[0]->id))
		   @foreach($fl_event as $fl_evnts)
            <div class="rock-event">
              <div class="evnt-pic col-md-5 col-ms-5 col-xs-12"> 
			   @if(!empty($fl_evnts->event_image))			  
			    <img src="{!!URL::to('uploads/events/'.$fl_evnts->account_type.'/'.$fl_evnts->event_image)!!}" class="img-responsive"/>
		       @else
			    <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"> 	  
			   @endif
			  </div>
              <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
                <h4> <a href="{!!URL('event/'.$fl_evnts->event_url)!!}"> {!! $fl_evnts->event_name !!} </a> </h4>
                <div class="date"> <span class="date-icon"> </span> 
				<?php 
				 if(!empty($fl_evnts->event_date)) {
				   $ev_date = date('M d, Y', strtotime($fl_evnts->event_date));
				 } else {
				   $ev_date = 'n/a'; 
				 }
				?>
			   {!! $ev_date !!} @ {!! $fl_evnts->event_time !!} 
				</div>
                <div class="location"> <span class="location-icon"> </span> {!! $fl_evnts->event_venue !!} / {!! $fl_evnts->event_address !!} </div>
                <div class="price"> <span class="price-icon"> </span> <strong>
			   @if($fl_evnts->event_cost == 'paid')
			   <?php $flevnt_price = explode("-",$fl_evnts->event_price ); ?>
			    @if(!empty($flevnt_price[0]))
				<?php $fl_prc = '$'.$flevnt_price[0]; 			
			      for ($x = 1; $x < sizeof($flevnt_price); $x++) {
					  $fl_prc .= ' - $'.$flevnt_price[$x];
				  }			 
				 ?> 
				 {!! $fl_prc !!}
				@else
				${!! $fl_evnts->event_price !!}	
                @endif					
			   @else
			    {!! ucfirst($fl_evnts->event_cost) !!}  
               @endif  	
				</strong> </div>
               <?php $flevt_count = DB::table('users_events')->where('e_id', '=', $fl_evnts->id)->count(); ?>
                <div class="attend"> People Attending : <span class="blue-col"> <a href="#"> {!! $flevt_count !!} </a> </span></div>
              </div>
            </div>
           @endforeach
		   @else 
			 <div class="rock-event"> Empty! not found </div>  
           @endif 
         </div>
		  
          <div id="our-events" class="tab-pane fade">
		   @if(!empty($my_evnt[0]->id))
			@foreach($my_evnt as $my_evnts)
            <div class="rock-event">
              <div class="evnt-pic col-md-5 col-ms-5 col-xs-12"> 
			   @if(!empty($my_evnts->event_image))			  
			   <img src="{!!URL::to('uploads/events/'.$my_evnts->account_type.'/'.$my_evnts->event_image)!!}" class="img-responsive"/>
		      @else
			   <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"> 	  
			  @endif	
			  </div>
              <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
                <h4><a href="{!!URL('event/'.$my_evnts->event_url)!!}"> {!! $my_evnts->event_name !!} </a></h4>
                <div class="date"> <span class="date-icon"> </span> 
				<?php 
				 if(!empty($my_evnts->event_date)) {
				   $ev_date = date('M d, Y', strtotime($my_evnts->event_date));
				 } else {
				   $ev_date = 'n/a'; 
				 }
				?>
			   {!! $ev_date !!} @ {!! $my_evnts->event_time !!} 
				</div>
                <div class="location"> <span class="location-icon"> </span>
				{!! $my_evnts->event_venue !!} / {!! $my_evnts->event_address !!} 
				</div>
                <div class="price"> <span class="price-icon"> </span> <strong>
				  @if($my_evnts->event_cost == 'paid')
			   <?php $mevnt_price = explode("-",$my_evnts->event_price ); ?>
			    @if(!empty($mevnt_price[0]))
				<?php $met_prc = '$'.$mevnt_price[0]; 			
			      for ($x = 1; $x < sizeof($mevnt_price); $x++) {
					  $met_prc .= ' - $'.$mevnt_price[$x];
				  }			 
				 ?> 
				 {!! $met_prc !!}
				@else
				${!! $my_evnts->event_price !!}	
                @endif					
			   @else
			    {!! ucfirst($my_evnts->event_cost) !!}  
               @endif  	
				</strong> </div>
				<?php $myevt_count = DB::table('users_events')->where('e_id', '=', $my_evnts->id)->count(); ?>
                <div class="attend"> People Attending : <span class="blue-col"> <a href="#"> {!! $myevt_count !!} </a> </span></div>
              </div>
            </div>
			@endforeach
		   @else 
			   <div class="rock-event"> Empty! not found </div>  
           @endif 	
          </div>*/ ?>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
      <div class="right-side-event-area">
        <div class="events-hed">Your Upcoming Event Calendar </div>
        <div class="attend_follow">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="">Attending</div>
		<?php $att_event = DB::table('users_events')->where('u_id', '=', $clid)->count(); ?>
        <a href="javascript:void(0)" data-target="#attending_event" data-toggle="modal"> 
		 <strong> {!! $att_event !!} </strong> 
		</a> 
		</div>
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="">Pages You Follow</div>
           <a href="javascript:void(0)" data-target="#follow_account" data-toggle="modal"> <strong> {!! sizeof($fl_acc) !!} </strong> </a> </div>
        </div>
        <div class="cel"> <a href="#"><img alt="#" src="{!!URL::to('assets/public/default2/images/map-cel.jpg')!!}" class="img-responsive"> </a> </div>
        <div class="calendar">
       <span class="text-center blue-col featur">   <a href="#">Map Out Your Events</a> </span>
		<a href="#"><img alt="#" src="{!!URL::to('assets/public/default2/images/calendar.jpg')!!}" class="img-responsive"> </a> </div>
        <div class="featured-events">
          <div class="cou"> <span class="text-left blue-col featur">Featured Events In</span> <span class="text-left blue-col countory" id="current_location">Australia</span> </div>
     @if(!empty($al_event[0]->id))
	  @if(sizeof($al_event) > 1)
	   <?php for ($vsx = 0; $vsx < 2; $vsx++) { ?> 
	   <div class="rock-event">
         <div class="evnt-pic col-md-5 col-ms-5 col-xs-12"> 
          @if(!empty($al_event[$vsx]->event_image))			  
		   <img src="{!!URL::to('uploads/events/'.$al_event[$vsx]->account_type.'/'.$al_event[$vsx]->event_image)!!}" class="img-responsive"/>
		  @else
		   <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"> 	  
		  @endif		 
		 </div>
            <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
              <h4> {!! $al_event[$vsx]->event_name !!} </h4>
              <div class="location"> <span class="location-icon"> </span>{!! $al_event[$vsx]->event_venue !!} / {!! $al_event[$vsx]->event_address !!}  </div>
              <a href="{!!URL('event/'.$al_event[$vsx]->event_url)!!}">  <strong>Read More</strong> </a> </div>
        </div>
	   <?php } ?>  
	   @else
		<div class="rock-event">
         <div class="evnt-pic col-md-5 col-ms-5 col-xs-12"> 
          @if(!empty($al_event[0]->event_image))			  
		   <img src="{!!URL::to('uploads/events/'.$al_event[0]->account_type.'/'.$al_event[0]->event_image)!!}" class="img-responsive"/>
		  @else
		   <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"> 	  
		  @endif		 
		 </div>
            <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
              <h4> {!! $al_event[0]->event_name !!} </h4>
              <div class="location"> <span class="location-icon"> </span>{!! $al_event[0]->event_venue !!} / {!! $al_event[0]->event_address !!}  </div>
              <a href="{!!URL('event/'.$al_event[0]->event_url)!!}">  <strong>Read More</strong> </a> </div>
        </div> 
	   @endif 
	  @endif	  
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
   <!--attending-popup--> 
    <div class="modal fade" id="attending_event">
	 <div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">			
		 <button type="button" class="close" data-dismiss="modal">&times;</button>
		 <h4>Your Attending Event list</h4>
		</div>
		<div class="modal-body col-md-12 col-ms-12 col-xs-12">			
		 @if(!empty($attend_event[0]->id))
		  @foreach($attend_event as $att_evnt)  
           <div class="evnt-pic col-md-12 col-ms-12 col-xs-12">  
		  	<div class="evnt-pic col-md-6 col-ms-6 col-xs-6">	
			 <h4> {!! $att_evnt->event_name !!} </h4>
			</div>
            <div class="evnt-pic col-md-6 col-ms-6 col-xs-6">				  
			  <a href="{!!URL('event/'.$att_evnt->event_url)!!}" class="btn btn-primary" target="_blank">View Event</a>
			  <a onclick="unattend({!! $att_evnt->id !!})" href="javascript:void(0)" class="btn btn-primary">Unattend</a>
		    </div>	
		   </div>	
		  @endforeach	
		 @else
		   Empty...
		 @endif
		</div>
	   <div class="modal-footer"></div>
	  </div>
	 </div>
	</div>
	
 <!--follow-popup--> 
  <div class="modal fade" id="follow_account">
   <div class="modal-dialog">
	<div class="modal-content">
	 <div class="modal-header">			
	  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  <h5><b>List of all Business/Companies/Municipalities you are following</b></h5>
	 </div>
	<div class="modal-body col-md-12 col-ms-12 col-xs-12">			
	 @if(!empty($fl_acc[0]->id))
      @foreach($fl_acc as $flw_acc)	
       <div class="evnt-pic col-md-12 col-ms-12 col-xs-12">  
	    <div class="evnt-pic col-md-6 col-ms-6 col-xs-6">	
		 <h4> {!! $flw_acc->name !!} </h4>
		</div>
        <div class="evnt-pic col-md-6 col-ms-6 col-xs-6">
         <a href="{!!URL($flw_acc->account_url)!!}" class="btn btn-primary" target="_blank">View Page</a>		
		 <a onclick="unflw({!! $flw_acc->id !!})" href="javascript:void(0)" class="btn btn-primary">Unfollow</a>
		</div>	
	   </div>	
	  @endforeach	
	 @else
	   Empty...
	 @endif
	</div>
	<div class="modal-footer">			
	 <!--<a href="javascript:void(0)" class="btn" data-dismiss="modal">Cancel</a>-->
	</div>
	</div>
   </div>
  </div>
</section>
@stop
@section('scripts')
<script>
 $(document).ready(function(){	
  $('#datetimepicker2').datetimepicker({
      format: 'L'	
    });
	
	$( "#refine_search" ).submit(function( event ){
     event.preventDefault();	
      var ctext = $( "#search_event option:selected" ).val();
	  var datapst = $.post('{!!URL("refine-search")!!}', $('#refine_search').serialize());
	  datapst.done(function( data ) {
		if(ctext =='attending'){
		 $("#event_data").html('Attending');
		} else if(ctext =='following'){
		 $("#event_data").html('Following');	
		} else if(ctext =='your'){
		 $("#event_data").html('Your Events');	
		} else {
		 $("#event_data").html('All Events');	
		} 	
      $("#all_event").html(data);		  	
	 });
  }); 
  
 });	
  var vx = document.getElementById("current_location");
   function getLocation() {
  	if (navigator.geolocation) { 
	 navigator.geolocation.getCurrentPosition(showAddress);
	} else { 
	 vx.innerHTML = "Australia";
	}
   }
  getLocation();
   function showAddress(position) {
	var lat_long = position.coords.latitude+','+position.coords.longitude;
	 var tken = jQuery( "input#_token" ).val();
	 jQuery('#current_location').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" style="width:30px"/>');
	var postdata = {curlocation: lat_long, _token: tken};
	var datapst = jQuery.post( '{!!URL("clocation")!!}', postdata );
	 datapst.done(function( data ) {		  
	  jQuery("#current_location").html(data);        
	 });	
	}
 //jQuery(document).ready(function(){	
  function unflw(flid){
   if(flid != null){
	 var tken = jQuery( "input#_token" ).val();  
	 var postdt = {flwid: flid, _token: tken};
	var datapost = $.post('{!!URL("account/unfollow")!!}', postdt);
	 datapost.done(function( data ) {
	  if(data == 'succ'){
		window.location='{!!URL("/")!!}';	
	  } else {	
		window.location='{!!URL("/")!!}';	
	  }	
	 });	
   } 
  }
  
  function unattend(evid){
   if(evid != null){
	 var tken = jQuery( "input#_token" ).val();  
	 var postdt = {evntid: evid, _token: tken};
	var datapost = $.post('{!!URL("event/unattendEvent")!!}', postdt);
	 datapost.done(function( data ) {
	  if(data == 'succ'){
		window.location='{!!URL("/")!!}';	
	  } else {	
		window.location='{!!URL("/")!!}';	
	  }	
	 });	
   } 
  }  
 //});  
</script>
@stop