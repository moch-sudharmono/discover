@section('styles')    
@stop
@section('content') 
{!!HTML::style("assets/public/default2/css/fullcalendar.css")!!}
{!!HTML::script("assets/public/default2/js/locationpicker.jquery.js")!!}
{!!HTML::script("assets/public/default2/js/map-label.js")!!}
{!!HTML::script("assets/public/default2/js/moment.min.js")!!}  
{!!HTML::script("assets/public/default2/js/fullcalendar.min.js")!!} 
{!!HTML::script("assets/public/default2/js/event/itc-js.js")!!} 
<?php 
$tok = csrf_token(); 
$wo = 0;
$priceSymbol = config("app.priceSymbol" , "$");
?>
 
<section class="event-in-your-area">
  <div class="container text-center">
    <div class="col-md-8 col-sm-12 col-xs-12">
      <div class="map col-sm-12 col-xs-12 col-md-12" id="main-map"></div>
      <div class="col-md-4 col-sm-4 col-xs-12 event-area-form">
       <form class="form-inline" id="refine_search" method="post">
        <h3> Refine Your Search</h3>
        <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
        	<input type="text" placeholder="Location" value="{!!$rsearch_detail!!}" name="ref_location" class="form-control"/>
        </div>
		
	  	<div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
           <div class="sel">
            <select class="selectpicker" name="location_range" title="Location Range">			
				<option value="" selected="selected">Search Everywhere</option>
             	@if($ucuntry == 'Canada') 			
			   	<option value="5">5 k/m</option>
			   	<option value="10">10 k/m</option>
			   	<option value="15">15k/m</option>
			   	<option value="25">25k/m</option>
			   	<option value="50">50k/m</option>
			   	<option value="100">100k/m</option>
			   	<option value="200">200k/m</option>
			 	@else   
			   	<option value="5">5 miles</option>
			   	<option value="10">10 miles</option>
			   	<option value="15">15 miles</option>
			   	<option value="25">25 miles</option>
			   	<option value="50">50 miles</option>
			   	<option value="100">100 miles</option>
			   	<option value="200">200 miles</option>
			 	@endif  
            </select>
           </div>
      </div>

  		@if($ucuntry == 'Canada') 	  
			<input type="hidden" value="cn" name="ucntyname"/>	
  		@else
			<input type="hidden" value="usa" name="ucntyname"/>
  		@endif 	  

		  <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
            <div class="sel input-group date" id="datetimepicker2">
	 			<input type="text" name="event_date" id="event_date" class="form-control" placeholder="Date" value="{{ old('event_date') }}"/>
			   <span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			   </span>
            </div>
          </div>
		  
		  <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non" id="reset-date-fields" style="display:none;">
				<div><button class="btn"> Select A Day </button></div>
          </div>		 
		  
		  <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non" id="eventday">
           <div class="sel">
            <select class="selectpicker" name="eventday">
				<option value="" selected>Select A Day</option>
			   <option value="today">Today</option>
			   <option value="tomorrow">Tomorrow</option>
			   <option value="week">This Week</option>
			   <option value="weekend">This Weekend</option>
			   <option value="month">This Month</option>
            </select>
           </div>
          </div>
		  
		   <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
           <div class="sel">
            <select class="selectpicker" name="evtype">	
               <option value="both" selected>Indoor & Outdoor</option>						
			   <option value="indoor">Indoor Only</option>
			   <option value="outdoor">Outdoor Only</option>
            </select>
           </div>
          </div>
          <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
           <div class="sel">
            <select class="selectpicker" name="category[]" data-live-search="true" multiple>
			 <option value="all">All Categories</option>
			 @if(!empty($evtsdata))
			  @foreach($evtsdata as $key_id => $etdt)
			   <option value="{!! $key_id !!}">{!! $etdt !!}</option>
			  @endforeach	
			 @endif
            </select>
           </div>
          </div>
		 
		   <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
            <div class="sel">
              <select class="selectpicker" id="search_page" name="search_page">
                <option value="all">Search All Page</option> 
				<option value="personal">Individuals</option>				
                <option value="business">Bussiness Page</option>
                <option value="municipality">Municipality Page</option>
                <option value="club">Club/Organization Page</option>
              </select>
            </div>
          </div>
		    <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
            <div class="sel">		       
              <select class="selectpicker" name="event_type">
                <option value="all">Search All Events</option> 			  
                <option value="free">Free Events</option>
                <option value="kids">Kids Events</option>
                <option value="family">Family Events</option>
				<option value="religious">Religious Events</option>
              </select>
            </div>
          </div> 
		  
		  
          <div class="sel">
             <input type="name" name="optional_all" placeholder="Event or Venue Name " class="form-control"/>          
          </div> 
		  <input type="hidden" name="_token" id="_token" value="{!! $tok !!}">	
          <input type="submit" value="Search" class="btn b-btn">
        </form>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12 tab-events text-left">
        <div class="tab-content">
		
          <div id="all_event" class="tab-pane fade in active">
		   @if(isset($al_event[0]) && !empty($al_event[0]->id))
			@foreach($al_event as $aet) 
            <div class="rock-event all-events">
              <div class="evnt-pic col-md-5 col-ms-5 col-xs-12">
			  @if(!empty($aet->event_image))
@if(is_numeric($aet->event_image)) 	 
   <?php $evtoimg = DB::table('event_catimages')->where('id', '=', $aet->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	
    @if( isset($evtoimg[0]) &&  !empty($evtoimg[0]->id))	
     <img src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}" class="img-responsive"/>
    @endif 
  @else				  
			   <img src="{!!URL::to('uploads/events/'.$aet->account_type.'/'.$aet->event_image)!!}" class="img-responsive"/>
		     @endif   
		      @else
			   <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"/> 	  
			  @endif
			  </div>
              <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
	<h4 id='container'>
 <a href="{!!URL('event/'.$aet->event_url)!!}" class="delegation-ajax" data-tipped-options="ajax:{data:{ event: '{!! $aet->event_url !!}', _token: '{!! $tok !!}' } }" target="_blank"> 
  {!! ucfirst($aet->event_name) !!} 
 </a> 
	</h4>				
     <div class="date"> <span class="date-icon"> </span>
	<?php 		
	    if(!empty($aet->event_date) && $aet->event_dtype == 'single'){
		 $aev_date = date('M d, Y', strtotime($aet->event_date));
		 $event_time = $aet->event_time;
		} else if($aet->event_dtype == 'multi'){ 		
		  $getmdate = DB::table('event_multidate')->where('ev_id', '=', $aet->id)->select('id','start_date','start_time')->get(); 
		 $aev_date = date('M d, Y', strtotime($getmdate[0]->start_date)); 
		 $event_time = $getmdate[0]->start_time;
		} else {
		 $aev_date = 'n/a'; 
		 $event_time = $aet->event_time;
		}
	  $getvcy = DB::table('countries')->where('id', '=', $aet->country)->select('id','name')->get(); 
      if(!empty($getvcy[0]->id)){ $ev_cuntry = '/ '.$getvcy[0]->name; } else { 	$ev_cuntry = null;  } 	
       //$ev_vaddrs = $aet->event_venue.', '.$aet->event_address.', '.$aet->address_secd.', '.$aet->city.', '.$aet->state.', '.$ev_cuntry; 	  
	?>
		{!! $aev_date !!} @ {!! $event_time !!} 
	 </div>
   <div class="location"> <span class="location-icon"> </span> 
    <a href="#getm-{{$wo}}" onclick="chgEventmap('{{$wo}}')"> {!! $aet->event_venue !!} {!! $ev_cuntry !!} </a>
   </div>
        <div class="price">
			<strong>
			
		  @if($aet->event_cost == 'paid')
			  
		   <?php $alet_price = explode("-", $aet->event_price); ?>
		   
			@if(!empty($alet_price[0]))
			 <?php
			  $lower = $alet_price[0];
			  
			  $alet_prc = toMoney( $lower , $priceSymbol);
			  for ($x = 1; $x < sizeof($alet_price); $x++) {
				$alet_prc .= ' - '. toMoney(  $alet_price[$x] , $priceSymbol);
			  }			 
			 ?> 
			  {!! $alet_prc !!}
			@else
			  ${!! ucfirst($aet->event_price) !!}	
		    @endif
			
		  @else
			 {!! ucfirst($aet->event_cost) !!}  
		  @endif 		
		  </strong> 
		</div>
          <?php $allevt_ct = DB::table('users_events')->where('e_id', '=', $aet->id)->count(); ?>		
    <div class="attend"> People Attending : <span class="blue-col"> {!! $allevt_ct !!} </span></div>
<div>		
 <?php $ur_event = DB::table('users_events')->where('e_id', '=', $aet->id)->where('u_id', '=', $clid)->select('e_id')->get(); ?>
		  @if(!empty($ur_event[0]->e_id))	
           <a onclick="unattend({!! $ur_event[0]->e_id !!})" href="javascript:void(0)" class="save-event event-attend">Unattend</a>		
          @else
		   <a onclick="saveEvent('{!! $aet->event_url !!}')" href="javascript:void(0)" class="save-event event-attend">Attend</a> 
          @endif 			
<?php  
 if(!empty($aet->account_id)){
  $followu = DB::table('user_follows')->where('follow_id', '=', $aet->account_id)->where('u_id', '=', $clid)->where('follow_type', '=', 'account')->select('id','follow')->get(); 
  //var_dump($fl_acc); // try using this variable
  //var_dump($followu);
?>
				 @if(!empty($followu[0]->id))
				   @if($followu[0]->follow == 'y')	  
					<a href="javascript:void(0)" onClick="unflw({!! $followu[0]->id !!})" class="unfollow-event save-event event-attend">Unfollow</a>
				   @endif
				  @else	  
<?php $checm = DB::table('account_details')->where('id', '=', $aet->account_id)->where('u_id', '!=', $clid)->select('id')->get(); ?>			
    @if(!empty($checm[0]->id))		  
	 <a href="javascript:void(0)" onClick="followEvent({!! $aet->account_id !!})" class="follow-event save-event event-attend">Follow</a>
 	@endif  				 
   @endif  				 
<?php } $wo++; ?>
 <div class="help-block with-errors" id="error-lreq"></div>				 
			   </div> 
              </div>
            </div>
			@endforeach
		   @else 
			   <div class="rock-event"> Unfortunately we can’t find any events for you. Please adjust your search options </div>  
           @endif 
          </div> <!-- event process div end---->
		 <div id="loading-loader"></div> 
		 
<input type="hidden" id="cpage" value="{!! $al_event->currentPage() !!}" />
<input type="hidden" id="max_page" value="{!! $al_event->lastPage() !!}" />
  <?php /* @if($al_event->total() > 3)
   <!-- Your End of page message. Hidden by default -->
   <div id="end_of_page" class="center">
     <hr/>
     <span>Load More...</span>
   </div> 
  @endif */ ?>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
      <div class="right-side-event-area">
        <div class="events-hed">Your Upcoming Event Calendar </div>
        <div class="attend_follow">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="">Attending</div>
				<?php //$att_event = DB::table('users_events')->where('u_id', '=', $clid)->count(); ?>
				<a href="javascript:void(0)" data-target="#attending_event" data-toggle="modal"> 
				 <strong> {!! count($attend_event) !!} </strong> 
				</a> 
				</div>
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="">Pages You Follow</div> 
           <a href="javascript:void(0)" data-target="#follow_account" data-toggle="modal"> <strong> {!! sizeof($fl_acc) !!} </strong> </a> </div>
        </div>
        <div class="cel map-right map-out-event" id="your-emap"></div>
        <div class="calendar">
				<span class="text-center blue-col featur">   <a href="javascript:void(0);">Map Out Your Events</a> </span>
				<div id="event_calendar"></div>
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
		<div class="event-arrange">
		 <select class="earr-dropbox" id="alph-arrange">
		  <option value="a">a->z</option>
		  <option value="z">z->a</option>
		 </select>
		 <select class="earr-dropbox" id="date-arrange">
		  <option value="1">1->9</option>
		  <option value="9">9->1</option>
		 </select> 
		</div>
		<div class="modal-body col-md-12 col-ms-12 col-xs-12 attend-evlist" id="arrange-event">			
		
		 @if( isset($attend_event[0]) && !empty($attend_event[0]->id))
		  @foreach($attend_event as $att_evnt)  
           <div class="evnt-pic col-md-12 col-ms-12 col-xs-12">  
		  	<div class="evnt-pic col-md-6 col-ms-6 col-xs-6">	
			 <h4> {!! ucfirst($att_evnt->event_name) !!} </h4>
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
	  </div>
	 </div>
	</div>	
 <!--follow-popup--> 
  <div class="modal fade" id="follow_account">
   <div class="modal-dialog">
	<div class="modal-content">
	 <div class="modal-header">			
	  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  <h4><b>Event Pages</b></h4>
	 </div>
	<div class="modal-body col-md-12 col-ms-12 col-xs-12">	
	<div class="ef-search">
	 <input type="text" value="" placeholder="page Name" name="efsearch_val" id="efsearch_val"/> 
	 <input type="button" value="Search" name="flplistsearch" id="flplistsearch" class="btn btn-primary"/>
	</div>
 <div class="eventp-follow" id="eventp_follow">
	@if( isset($unfl_acc[0]) && !empty($unfl_acc[0]->id))
	 @foreach($unfl_acc as $unflw_acc)
 <?php $unfep = DB::table('user_follows')->where('u_id', '=', $clid)->where('follow_id', '=', $unflw_acc->id)->distinct('follow_id')->select('id')->get(); ?>  
  @if(empty($unfep[0]->id))    
	<div class="evnt-pic col-md-12 col-ms-12 col-xs-12">  
	    <div class="evnt-pic col-md-6 col-ms-6 col-xs-6">	
		 <h4> {!! $unflw_acc->name !!} </h4>
		</div>
        <div class="evnt-pic col-md-6 col-ms-6 col-xs-6">
         <a href="{!!URL($unflw_acc->account_url)!!}" class="btn btn-primary" target="_blank">View Page</a>	
         <a onclick="followEvent({!! $unflw_acc->id !!})" href="javascript:void(0)" class="btn btn-primary">Follow</a>
		</div>	
	   </div>	
  @endif	   
     @endforeach 
	@else
	  Empty...
	@endif
 </div>	 
	 <h5><b>List of all Business/Companies/Municipalities you are following</b></h5>
	<div class="eventp-unfollow">  
	 @if(isset($fl_acc[0]) && !empty($fl_acc[0]->id))
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
	</div>
	</div>
   </div>
  </div>
</section>
@stop
@section('scripts')
<script type="text/javascript">
   var markers = [];   
   
	$(window).on('scroll', function() {
	   /*if ($(window).scrollTop() > 95) {
			$('.right-side-event-area').addClass('scroll-rs-event');
	   } else {
			$('.right-side-event-area').removeClass('scroll-rs-event');    
	   }
	   if ($(window).scrollTop() > 300) {
			$('.event-area-form').addClass('scroll-event-area-form');   
	   } else {
			$('.event-area-form').removeClass('scroll-event-area-form');     
	   } 

	   if ($(window).scrollTop() > 95) {
            $('.header-main1').addClass('scroll-rs-event');
       } else {
            $('.header-main1').removeClass('scroll-rs-event');    
       }*/
	});

	 var outerPane = $('#all_event'), didScroll = false;
		$(window).scroll(function() { 
			didScroll = true;
		});
 
setInterval(function() {
  if (didScroll){
    didScroll = false;
    if(($(document).height()-$(window).height())-$(window).scrollTop() < 10){
     if(!$('#loading-loader').html() || $('#loading-loader').html() == ' '){ 	
	   pageCountUpdate(); 
	 }	 
	}  
  }
}, 100);

 function pageCountUpdate(){
   var page1 = parseInt($('#cpage').val()); 
    var max_page = parseInt($('#max_page').val());
    if(page1 < max_page){
		$('#cpage').val(page1+1);
		var npage = parseInt(page1+1);
		getPosts(npage);   
    } else {
      $('#end_of_page').fadeIn();
    }	 
 } 
 
 function getPosts(npage){ 
	 var resech = $('#rs_type').val();
	if( typeof resech === 'undefined' || !resech ){	 
     $.ajax({
        type: "GET",
        url: '{!!URL("evnt/get-events?page=")!!}'+npage,       
        beforeSend: function(){ 
			$('#loading-loader').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive" style="width:65px"/>');
        },
        complete: function(){
			$('#loading-loader').html(' ');
        },
        success: function(idata) {
			$('#all_event').append(idata);
        }
     });
	}
	
	if( resech == 'ref_search'){
	  var datapst = $.post('{!!URL("evnt/refine-search")!!}/'+npage, $('#refine_search').serialize());
	  $('#loading-loader').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive" style="width:65px"/>');
	  
	 datapst.done(function( ifdata ) {
			$('#loading-loader').html(' ');	 
			$("#all_event").append(ifdata);		  	
	 });
	}
 }  
 
 $(document).ready(function(){
	 
	 /** index_login-blade.php **/
	 
  $('#datetimepicker2').datetimepicker({
      format: 'L'	
    });
	
	$( "#refine_search" ).submit(function( event ){
     event.preventDefault();	
      var ctext = $( "#search_event option:selected" ).val();
	  var datapst = $.post('{!!URL("refine-search")!!}', $('#refine_search').serialize());
	  datapst.done(function( data ) {		   
      	$("#all_event").html(data);		  	
	 	});
  });  
  
  Tipped.delegate('.delegation-ajax', {
     ajax: {
       url: '{!!URL("evt")!!}',
	    type: 'post',
	    success: function(data, textStatus, jqXHR) {
          return data;
        }      
     },
      skin: 'light',
      radius: false,
      size: 'small',
      position: 'topleft',
	  afterUpdate: function(content) {
	  }
    });	
 
 });
 
 function followEvent(eid) {
	 
		var token = jQuery('#_token').val();
        var postdata = {'ascid': eid, '_token': token};
	    var datapst = $.post( '{!!URL("account/follow")!!}', postdata );
	   datapst.done(function( data ) {
	   if(data == 'success'){   
			window.location='{!!URL("/")!!}';		 
	   } else { 
			jQuery("#error-lreq").html('Login required');  
	   } 	
	  });
 }
 
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
			   $("#full-success").css("display", "block");
			   $("#es-mess").html('You are successfully unattend the event');		
				setTimeout(function () {
				 window.location = '{!!URL("/")!!}'; 
				}, 500); 	  
			  } else {	
				window.location='{!!URL("/")!!}';
			  }	
			});	
	   } 
	  else{
		  console.log("error for "+evid);
	  } 
	}  
  
  function saveEvent(evurl){   
    $.ajax({
       type: "GET",
       url: '{!!URL("saveEvent")!!}/'+evurl,
     beforeSend: function(){ },
     complete: function(){ },       	
    success: function(data) { 
	 if(data == 'succ'){
		$("#full-success").css("display", "block");
		$("#es-mess").html('Event successfully saved on your account');		
	  setTimeout(function () {
        window.location = '{!!URL("/")!!}'; //will redirect to your blog page (an ex: blog.html)
      }, 500); //will call the function after 2 secs
	  // window.location='{!!URL("/")!!}';	 
	 } else {
	  window.location='{!!URL("/")!!}';	 
	 }        
    }
   });
  }  

 
$('#flplistsearch').on('click', function(){	 
   var evpgval = $('#efsearch_val').val();
   if(evpgval != null){
	 var tken = jQuery("input#_token").val();  
	 var postdt = {evntsdata: evpgval, _token: tken};
	var datapost = $.post('{!!URL("event/pageList")!!}', postdt);
	 datapost.done(function(data) {
		$('#eventp_follow').html(data);
	 });	
   }
 });
  
  $('select#alph-arrange').on('change', function(){  
    var evagval = $( "select#alph-arrange" ).val();
	  var tken = jQuery("input#_token").val();  
	  var postdst = {evtsdata: evagval, _token: tken};
	  var datapost = $.post('{!!URL("event/arrangeList")!!}', postdst);
	 datapost.done(function(data) {
		$('#arrange-event').html(data);
	 });
  });
  
  $('select#date-arrange').on('change', function(){  
    var evagval = $( "select#date-arrange" ).val();
	  var tken = jQuery("input#_token").val();  
	  var postdst = {evtsdata: evagval, _token: tken};
	  var datapost = $.post('{!!URL("event/arrangeList")!!}', postdst);
	 datapost.done(function(data) {
		$('#arrange-event').html(data);
	 });
  });
 
/***********map*********************/
  var delay = delay1 = 100;
  
  var infowindow = new google.maps.InfoWindow();
  var infowindow1 = new google.maps.InfoWindow();
  
  var latlng = new google.maps.LatLng( 21.0000, 78.0000);	// user latitude and longitude
  var latlng1 = new google.maps.LatLng( 21.0000, 78.0000);	// user latitude and longitude
  
   var mapOptions1 = { zoom: 2,center: latlng1,mapTypeId: google.maps.MapTypeId.ROADMAP};
   
  var map1 = new google.maps.Map(document.getElementById("main-map"), mapOptions1);
  
 @if(!empty($all_address))
<?php if( isset($full_event[0]) && !empty($full_event[0]->id)){ ?>
 
  var geocoder = new google.maps.Geocoder(); 
  var bound1 = new google.maps.LatLngBounds();
  function mgeocodeAddress(mlocations, orgtitles, orgsurls, orgcount1, theNext1) {
    geocoder.geocode({address:mlocations}, function (results1,status1)
      { 
         if (status1 == google.maps.GeocoderStatus.OK) {
          var p1 = results1[0].geometry.location;
          var lat1=p1.lat();
          var lng1=p1.lng();
          createMarker1(mlocations,lat1,lng1,orgtitles,orgsurls,orgcount1);
        } else {
           if (status1 == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
            next1Address--;
            delay1++;
			  } else { 
			  
			  }   
        }
        theNext1();
      }
    );
  }  
  
 function createMarker1(adds,lat1,lng1,orgtitles,orgsurls,orgcount1) {
   var contentString = adds;
   var markerImage = new google.maps.MarkerImage("{!!URL::to('assets/public/default2/images/event.png')!!}", null, null, null, iconSize);			
   var marker1 = new google.maps.Marker({
     position: new google.maps.LatLng(lat1,lng1),
     map: map1,
	 icon: markerImage
   });
          if(agent == "iphone") {
            var iconSize = new google.maps.Size(16,19);
          } else {
				iconSize = null;
          }	
          if(agent == "default") {
            google.maps.event.addListener(marker1, "mouseover", function() {
				  this.old_ZIndex = this.getZIndex();
				  this.setZIndex(9999);
				  $("#marker"+orgcount1).css("display", "inline");
				  $("#marker"+orgcount1).css("z-index", "99999");
            });
          }		  
		  google.maps.event.addListener(marker1, 'click', function() {
			  infowindow1.setContent( "<div class='marker_title' id='getm-"+orgcount1+"'><a target='_blank' href='event/"+orgsurls+"'>"+orgtitles+"</a></div>"
			  +"<div class='marker_address'>"+String(contentString)+"</div>"
			 ); 
			 infowindow1.open(map1,marker1);
			 map1.setZoom(8);
			 map1.setCenter(marker1.getPosition());
		   });
			markers.push(marker1);
		   bound1.extend(marker1.position);
 }
 
	var mlocations = [<?php echo '"'.implode('","', $maarray).'"';?>];
	var morg_title = [<?php echo '"'.implode('","', $mTiAr).'"';?>];
	var morg_surl = [<?php echo '"'.implode('","', $mog_slar).'"';?>]; 
  var next1Address = 0;
  function theNext1() {
    if (next1Address < mlocations.length) {
      setTimeout('mgeocodeAddress("'+mlocations[next1Address]+'","'+morg_title[next1Address]+'","'+morg_surl[next1Address]+'","'+next1Address+'",theNext1)', delay1);
		next1Address++;
    } else {
		map1.fitBounds(bound1);
    }
  }
  theNext1();   
<?php } ?>  
</script>
 @else
  <script>

	 function initialize() {
	  var mapProp = {
		center:new google.maps.LatLng( 21.0000, 78.0000 ),
		zoom: 1,
		mapTypeId:google.maps.MapTypeId.ROADMAP
	  };
	  var map=new google.maps.Map(document.getElementById("main-map"),mapProp);
	 }
	  google.maps.event.addDomListener(window, 'load', initialize);
  </script>	 
 @endif 
 
 
<script type="text/javascript">
<?php 
if(isset($upcevnt[0]) && !empty($upcevnt[0]->id)){ ?>

   var mapOptions = {
		zoom: 10,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  
  var geocoder = new google.maps.Geocoder(); 
  var bounds = new google.maps.LatLngBounds();
   var map2 = new google.maps.Map(document.getElementById("your-emap"), mapOptions);
   
 function geocodeAddress(address, orgtitle, orgsurl, orgcount, theNext) {
	 
	 console.log(address+"=="+orgtitle+"=="+orgsurl+"=="+orgcount);
	 
    geocoder.geocode({address:address}, function (results,status)
      { 
         if (status == google.maps.GeocoderStatus.OK) {
          var p = results[0].geometry.location;
          var lat=p.lat();
          var lng=p.lng();
		  
          createMarker( address ,lat,lng,orgtitle,orgsurl,orgcount);
        }
        else {
           if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
            nextAddress--;
            delay++;
          } else {
                        }   
        }
        theNext();
      }
    );
  }
 
 function createMarker( add,lat,lng,orgtitle,orgsurl,orgcount ){
   var contentString = add;
   var markerImage = new google.maps.MarkerImage("{!!URL::to('assets/public/default2/images/event.png')!!}", null, null, null, iconSize);			
   var marker = new google.maps.Marker({
     position: new google.maps.LatLng(lat,lng),
     map: map2,
	 icon: markerImage
    });  
	// show smaller marker icons on mobile
       if(agent == "iphone") {
          var iconSize = new google.maps.Size(16,19);
       } else {
          iconSize = null;
       }	
	 // add marker hover events (if not viewing on mobile)
        if(agent == "default") {
            google.maps.event.addListener(marker, "mouseover", function() {
              this.old_ZIndex = this.getZIndex();
              this.setZIndex(9999);
              $("#marker"+orgcount).css("display", "inline");
              $("#marker"+orgcount).css("z-index", "99999");
            });
         /*   google.maps.event.addListener(marker, "mouseout", function() {
              if (this.old_ZIndex && zoomLevel <= 20) {
                this.setZIndex(this.old_ZIndex);
                $("#marker"+orgcount).css("display", "none");
              }
            });*/
        }	
 // add marker label
        /*  var label = new Label({
            map: map2,
			id: orgcount
          });
           label.bindTo('position', marker);
          label.set("text", orgtitle);
          label.bindTo('visible', marker);
          label.bindTo('clickable', marker);
          label.bindTo('zIndex', marker); */
		  
   google.maps.event.addListener(marker, 'click', function(){
	   console.log("clicking....");
	 infowindow.setContent( "<div class='marker_title'><a target='_blank' href='event/"+orgsurl+"'>"+orgtitle+"</a></div>"
	  +"<div class='marker_address'>"+String(contentString)+"</div>"); 
     infowindow.open(map2,marker);
	  map2.setZoom(10);
	  map2.setCenter(marker.getPosition());
   });
   bounds.extend(marker.position);
 }
 
var locations = [<?php echo '"'.implode('","', $addressArray).'"';?>];
var org_title = [<?php echo '"'.implode('","', $orgTitleArray).'"';?>];
var org_surl = [<?php echo '"'.implode('","', $org_surlArray).'"';?>]; 
  var nextAddress = 0;
  
  function theNext(){
	//console.log(locations);
	var directionsService = new google.maps.DirectionsService;
	var directionsDisplay = new google.maps.DirectionsRenderer;
    directionsDisplay.setMap(map2);
	var waypts = [];
	<?php 
		if(!empty($addressArray)){
				foreach($addressArray as $key => $router ){
	?>
			waypts.push({location: "<?php echo $router;?>" ,stopover: true});
	<?php
				}
		}
	?>
		directionsService.route({
          origin: "Banff Park Museum,91 Banff Ave,Banff,Alberta,Canada",
          destination: "banaf canada,Unnamed Road,Banaff,Alberta,Canada",
          waypoints: waypts,
          optimizeWaypoints: true,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
            var route = response.routes[0];
           // var summaryPanel = document.getElementById('directions-panel');
          //  summaryPanel.innerHTML = '';
            // For each route, display summary information.
            for (var i = 0; i < route.legs.length; i++) {
              var routeSegment = i + 1;
            //  summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
              //    '</b><br>';
            //  summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
            //  summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
            //  summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
            }
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
	   
    if (nextAddress < locations.length) {
      setTimeout('geocodeAddress("'+locations[nextAddress]+'","'+org_title[nextAddress]+'","'+org_surl[nextAddress]+'","'+nextAddress+'",theNext)', delay);
      nextAddress++;
	  
    } else {
      map2.fitBounds(bounds);
    }
	
  }
  
  
 theNext(); 
 $(document).ready(function() {
   $('#event_calendar').fullCalendar({
	 defaultDate: '<?php echo date("Y-m-d"); ?>',
	 editable: true,
	 eventLimit: true, // allow "more" link when too many events
	 events: [
	  @if(!empty($upcevnt[0]->id))
	   @foreach($upcevnt as $evt_cld)
		{ id: {!!$evt_cld->id!!},
		  title: '{!!ucfirst($evt_cld->event_name)!!}',
		  url: '{!!URL("event/".$evt_cld->event_url)!!}',			
		 @if(!empty($evt_cld->end_date))
		  start: '{!!date("Y-m-d", strtotime($evt_cld->event_date))!!}',
		  end: '{!!date("Y-m-d", strtotime($evt_cld->end_date))!!}' 	  
		 @else
		  start: '{!!date("Y-m-d", strtotime($evt_cld->event_date))!!}'
		 @endif
		},
	   @endforeach
	  @endif	
	 ]
   });		
 });
<?php } 
else { ?>
 function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(21.0000, 78.0000),
    zoom: 1,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("your-emap"),mapProp);
 }
  
  google.maps.event.addDomListener(window, 'load', initialize); 
  
  $('#event_calendar').fullCalendar({
	 defaultDate: '<?php echo date("Y-m-d"); ?>',
	 editable: true,
	 eventLimit: true, // allow "more" link when too many events
	 events: [ ]
   });	
<?php } ?>
	$( "#datetimepicker2" ).focusout(function() {
		var ckvsdate = $('#event_date').val();	
		 if(ckvsdate){
			$('#eventday').hide();
			$('#reset-date-fields').show();
		 } else  {
		  $('#eventday').show(); 	
			$('#reset-date-fields').hide();
		 }
	  });
	  jQuery("#reset-date-fields").click(function(){
		  $('#event_date').val('');	
		  $('#reset-date-fields').hide();
		  $('#eventday').show();
	  });
	  

 function chgEventmap(val_addr){
  google.maps.event.trigger(markers[val_addr], 'click');	
 }
</script>
@stop