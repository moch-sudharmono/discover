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
                <h4>
				<a onClick="selEvent('{!! $aet->event_url !!}');" href="javascript:void(0)" data-target="#event_popup" data-toggle="modal"> {!! $aet->event_name !!} </a>
				</h4>
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
			   <div><a class="event-more" href="{!!URL('event/'.$aet->event_url)!!}">More Details</a>
		
		<?php $ur_event = DB::table('users_events')->where('e_id', '=', $aet->id)->where('u_id', '=', $clid)->select('e_id')->get(); ?>
		  @if(!empty($ur_event[0]->e_id))	
           <a onclick="unattend({!! $ur_event[0]->e_id !!})" href="javascript:void(0)" class="save-event event-attend">Unattend</a>		
          @else
		   <a onclick="saveEvent('{!! $aet->event_url !!}')" href="javascript:void(0)" class="save-event event-attend">Attend</a> 
          @endif 
			
<?php $followu = DB::table('user_follows')->where('follow_id', '=', $aet->id)->where('u_id', '=', $clid)->where('follow_type', '=', 'event')->select('id','follow')->get(); ?>
				 @if(!empty($followu[0]->id))
				   @if($followu[0]->follow == 'y')	  
					<a onclick="unflw({!! $followu[0]->id !!})" href="javascript:void(0)" class="unfollow-event">Unfollow</a>
				   @endif
				  @else	  
					<a href="javascript:void(0)" onClick="followEvent({!! $aet->id !!})" class="follow-event">Follow</a>
				 @endif  				 
			   </div>
              </div>
            </div>
			@endforeach
		   @else 
			   <div class="rock-event"> Empty! not found </div>  
           @endif 
          </div>
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
		<?php $att_event = DB::table('users_events')->where('u_id', '=', $clid)->count(); ?>
        <a href="javascript:void(0)" data-target="#attending_event" data-toggle="modal"> 
		 <strong> {!! $att_event !!} </strong> 
		</a> 
		</div>
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="">Pages You Follow</div> 
           <a href="javascript:void(0)" data-target="#follow_account" data-toggle="modal"> <strong> {!! sizeof($fl_acc) !!} </strong> </a> </div>
        </div>
        <div class="cel"> <a href="#"><img alt="#" src="{!!URL::to('assets/public/default2/images/map-cel.jpg')!!}" class="img-responsive"/> </a> </div>
        <div class="calendar">
       <span class="text-center blue-col featur">   <a href="#">Map Out Your Events</a> </span>
		<a href="#"><img alt="#" src="{!!URL::to('assets/public/default2/images/calendar.jpg')!!}" class="img-responsive"/> </a> </div>
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
		   <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"/> 	  
		  @endif		 
		 </div>
            <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
              <h4> {!! $al_event[$vsx]->event_name !!} </h4>
              <div class="location"> <span class="location-icon"> </span>{!! $al_event[$vsx]->event_venue !!} / {!! $al_event[$vsx]->event_address !!}  </div> 
             <a onClick="selEvent('{!! $al_event[$vsx]->event_url !!}');" href="javascript:void(0)" data-target="#event_popup" data-toggle="modal">
			  <strong>Read More</strong> 
			 </a> 
			</div>
        </div>
	   <?php } ?>  
	   @else
		<div class="rock-event">
         <div class="evnt-pic col-md-5 col-ms-5 col-xs-12"> 
          @if(!empty($al_event[0]->event_image))			  
		   <img src="{!!URL::to('uploads/events/'.$al_event[0]->account_type.'/'.$al_event[0]->event_image)!!}" class="img-responsive"/>
		  @else
		   <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"/> 	  
		  @endif		 
		 </div>
            <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
              <h4> {!! $al_event[0]->event_name !!} </h4>
              <div class="location"> <span class="location-icon"> </span>{!! $al_event[0]->event_venue !!} / {!! $al_event[0]->event_address !!}  </div>
           <a onClick="selEvent('{!! $al_event[$vsx]->event_url !!}');" href="javascript:void(0)" data-target="#event_popup" data-toggle="modal">
		     <strong>Read More</strong>
		   </a> </div>
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
  
 
  <div id="event_popup" class="modal fade in" role="dialog" style="display: none;"> </div>
  <div id="fade-popup"></div>
</section>
@stop
@section('scripts')
<script>
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
 function followEvent(eid) {	 
 var token = jQuery('#_token').val();
        var postdata = {'ascid': eid, '_token': token};
		//jQuery("#follow-page").html('Following');
	    var datapst = $.post( '{!!URL("event/follow")!!}', postdata );
	   datapst.done(function( data ) {
	   if(data == 'success'){   
	    window.location='{!!URL("/")!!}';		 
	   } else { 
	    // jQuery("#follow-page").html('Follow');
		 jQuery("#error-lreq").html('Login required');  
	   } 	
	  });
 }
 
 function selEvent(eurl) {	
   $.ajax({
       type: "GET",
       url: '{!!URL("evt")!!}/'+eurl, // whatever your URL is 
     beforeSend: function(){   
          $('#event_popup').html('<div class="modal-dialog sign_login"><div class="modal-content sign_up-form feature-event"><div class="modal-header"><button type="button" id="epopupclose" class="close" data-dismiss="modal">&times;</button><img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive popup-event"/></div></div></div></div>');
     },
     complete: function(){ },       	
     success: function(data) { 
         $('#event_popup').html(data);
     }
   });
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