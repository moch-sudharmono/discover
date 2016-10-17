@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
 {!!HTML::script("assets/public/default2/js/event/itc-js.js")!!} 
<?php 
   if(Sentry::check()){
	$getlid = Sentry::getUser()->id; 
   }
   $tok = csrf_token();
?>	  
<section class="event-select-area">
 <div class="map-address">
  <div class="map-responsive accountp-map" id="account-map"></div>
   <div class="address-box" id="saddress-box" data-uk-scrollspy="{cls:'uk-animation-scale-up', repeat:true, delay:1500}">
	@if(!empty($event_details[0]->upload_file))
	  <img src="{!!URL::to('uploads/account_type/'.$cimg_apath.'/'.$event_details[0]->upload_file)!!}" class="img-responsive"/>
    @else
	  <img src="{!!URL::to('assets/public/default2/images/fox-logo.png')!!}" class="img-responsive"/>	
    @endif  
      <h3 align="center">{!!$event_details[0]->name!!}</h3>
      <address>
      Address:<br>	
	 {!!$baddress!!} <br>
		@if(!empty($bcity))
		 {{$bcity}},	
		@endif 
		@if(!empty($bstates))
		 {{$bstates}},
		@endif <br>
		@if(!empty($bcountry))
		{{$bcountry}}, 
		@endif {!!$event_details[0]->zip_code!!} <br>
      <br>
	 @if(!empty($event_details[0]->phone)) 
      Contact:<br>
      {{$event_details[0]->phone}} <br>
	 @endif 
	 
	 @if(!empty($event_details[0]->email)) 	  
      <a href="mailto:{{$event_details[0]->email}}" id="mail">{{$event_details[0]->email}}</a>
     @endif
      </address>
	 @if(!empty($event_details[0]->website)) 
      <div class="website"><a href="{{$event_details[0]->website}}" >{!!$ev_dtstr!!}</a></div>
     @endif   
    <div style="text-align:center;">	
      @if(!empty($ufollow[0]->id))
	   @if($ufollow[0]->follow == 'y')	  
	    <a onclick="unflw({!! $ufollow[0]->id !!})" href="javascript:void(0)" id="unfollow-page" class="follow">Unfollow</a>
	   @endif
	  @else		
       @if(isset($getlid) && !empty($getlid))
	    @if($event_details[0]->u_id != $getlid) 
         <a href="javascript:void(0)" id="follow-page" class="follow">Follow</a> 
		@endif	
	   @else
		 <a href="javascript:void(0)" id="follow-page" class="follow">Follow</a>  
       @endif  
	  @endif 
         <div class="help-block with-errors" id="error-lreq"></div>
	</div>	 
		<input type="hidden" name="_token" id="_token" value="{!!$tok!!}">	
   </div>
   <!-- @if(!empty($acc_event[0]->id))	
	 <div class="ss" data-uk-scrollspy="{cls:'uk-animation-slide-right', repeat:true, delay:2000}">
      <div id="accordion" class="panel-group">       
      </div>
     </div>
    @endif -->
  </div>	
  <div class="container-fluid">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
      <div class="col-md-12 col-sm-12 col-xs-12 tab-events text-left">
	   @if(!empty($acc_event[0]->id))
        <div class="tab-content">
          <div id="all" class="tab-pane fade in active">            
            <!-- Wrapper for slides -->			
            <div class="container event-sel">
		<?php 
		    if(sizeof($acc_event) > 4) { 
			  $mdiv = 'carousel-inner';
		    } else {
			 $mdiv = 'carousel-inners';	
			}	
        ?>
              <div class="{!! $mdiv !!}" role="listbox">			  
                <div class="item active">
				<?php if(sizeof($acc_event) > 3){ for($ace = 0; $ace <= 3; $ace++){ ?> 
                  <div class="col-md-3 col-ms-6 col-xs-12">
                    <div class="rock-event">
	 <a href="{!!URL('event/'.$acc_event[$ace]->event_url)!!}" class="delegation-ajax" data-tipped-options="ajax:{data:{ event: '{!! $acc_event[$ace]->event_url !!}', _token: '{!! $tok !!}' } }">				
                      <div class="evnt-pic col-md-12 col-xs-12">
			@if(!empty($acc_event[$ace]->event_image))
@if(is_numeric($acc_event[$ace]->event_image)) 	 
   <?php $evtoimg = DB::table('event_catimages')->where('id', '=', $acc_event[$ace]->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	
    @if(!empty($evtoimg[0]->id))	
     <img src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}" class="img-responsive"/>
    @endif 
  @else			
			 <img src="{!!URL::to('uploads/events/'.$acc_event[$ace]->account_type.'/'.$acc_event[$ace]->event_image)!!}" class="img-responsive"/>	
 @endif 		 
            @else
			 <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"/>
            @endif  
					  </div>
                <div class="evnt-con col-md-12">
                        <h4> {!! ucfirst($acc_event[$ace]->event_name) !!} </h4>
					<?php 
				     if(!empty($acc_event[$ace]->event_date) && $acc_event[$ace]->event_dtype == 'single'){
					  $ev_date = date('M d, Y', strtotime($acc_event[$ace]->event_date));
					  $event_time = $acc_event[$ace]->event_time;
					 } else if($acc_event[$ace]->event_dtype == 'multi'){ 		
					   $getmdate = DB::table('event_multidate')->where('ev_id', '=', $acc_event[$ace]->id)->select('id','start_date','start_time')->get(); 
					  $ev_date = date('M d, Y', strtotime($getmdate[0]->start_date)); 
					  $event_time = $getmdate[0]->start_time;
					 } else {
					  $ev_date = 'n/a'; 
					  $event_time = $acc_event[$ace]->event_time;
					 }
					?>
                        <div class="date"> <span class="date-icon"> </span> {!! $ev_date !!} @ {!! $event_time !!} </div>
                        <div class="location"> <span class="location-icon"> </span>{!! $acc_event[$ace]->event_venue !!}
        <?php 
		 if(!empty($acc_event[$ace]->country)){	 
		   $gcname = DB::table('countries')->where('id', '=', $acc_event[$ace]->country)->select('id','name')->get(); 
		  if($gcname[0]->id){
			echo ' / '.$gcname[0]->name;  
		  } 
		 }   
		?>		   

						  {!! $acc_event[$ace]->event_address !!} </div>
                    <div class="price 134">
			<strong>
			
		  @if($aet->event_cost == 'paid')
			  
		   <?php $alet_price = explode("-", $aet->event_price); ?>
		   
			@if(!empty($alet_price[0]))
			 <?php
			  $lower = $alet_price[0];
			  
			  $alet_prc = toMoney( $lower );
			  for ($x = 1; $x < sizeof($alet_price); $x++) {
				$alet_prc .= ' - '. toMoney(  $alet_price[$x] );
			  }			 
			 ?> 
			  {!! $alet_prc !!}
			@else
			  ${!! ucfirst($aet->event_price) !!}	
		    @endif
			
		  @else
			 {!! ucfirst($aet->event_cost) !!}  
		  @endif 		
		  </strong>  </div>
   <div class="follow">More Details</div>
	
                </div>
				</a>
             </div>
         </div>
	<?php } } else { ?>
				@foreach($acc_event as $ac_et)
					<div class="col-md-3 col-ms-6 col-xs-12">
                    <div class="rock-event">
	<a href="{!!URL('event/'.$ac_et->event_url)!!}" class="delegation-ajax " data-tipped-options="ajax:{data:{ event: '{!!$ac_et->event_url!!}', _token: '{!!$tok!!}' } }"> 
                      <div class="evnt-pic col-md-12 col-xs-12">
 @if(!empty($ac_et->event_image))
  @if(is_numeric($ac_et->event_image)) 	 
    <?php $evstoimg = DB::table('event_catimages')->where('id', '=', $ac_et->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	
   @if(!empty($evstoimg[0]->id))	
     <img src="{!!URL::to('uploads/'.$evstoimg[0]->ecat_path.'/'.$evstoimg[0]->ecat_name.'/'.$evstoimg[0]->ecat_image)!!}" class="img-responsive"/>
   @endif 
  @else	
	<img src="{!!URL::to('uploads/events/'.$ac_et->account_type.'/'.$ac_et->event_image)!!}">
  @endif		 
 @else
	<img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive">
 @endif  
					  </div>
                      <div class="evnt-con col-md-12">
                        <h4> {!! ucfirst($ac_et->event_name) !!} </h4>
					<?php 
					 if(!empty($ac_et->event_date)){
					   $ev_date = date('M d, Y', strtotime($ac_et->event_date));
					 } else {
					   $ev_date = 'n/a'; 
					 }
					?>
                        <div class="date"> <span class="date-icon"> </span> {!! $ev_date !!} @ {!! $ac_et->event_time !!} </div>
                        <div class="location"> <span class="location-icon"> </span>{!! $ac_et->event_venue !!}
        <?php 
		 if(!empty($ac_et->country)){	 
		   $gcname = DB::table('countries')->where('id', '=', $ac_et->country)->select('id','name')->get(); 
		  if($gcname[0]->id){
			echo ' / '.$gcname[0]->name;  
		  } 
		 }   
		?>
	</div>
                    <div class="price 198"> <span class="price-icon"> </span> <strong>						
					  @if($ac_et->event_cost == 'paid')
					     <?php $alet_price = explode("-", $ac_et->event_price); ?>
					    @if(!empty($alet_price[0]))
						 <?php $alet_prc = '$'.$alet_price[0]; 			
						  for ($x = 1; $x < sizeof($alet_price); $x++) {
						   $alet_prc .= ' - $'.$alet_price[$x];
						  }			 
						 ?> 
						  {!! $alet_prc !!}
					    @else
						 ${!! $ac_et->event_price !!}	
					    @endif					
					  @else
					    {!! ucfirst($ac_et->event_cost) !!}  
					  @endif					  
					</strong> </div> 
   <div class="follow">More Details</div>
                      </div>
			 </a>		  
                    </div>
                  </div>
				@endforeach	
		<?php } ?> 
                </div>				
     <div class="item">
				<?php for($acev = 4; $acev < sizeof($acc_event); $acev++){ ?>
                  <div class="col-md-3 col-ms-6 col-xs-12">
                    <div class="rock-event">
	  <a href="{!!URL('event/'.$acc_event[$acev]->event_url)!!}" class="delegation-ajax " data-tipped-options="ajax:{data:{ event: '{!!$acc_event[$acev]->event_url!!}', _token: '{!!$tok!!}' } }"> 
        <div class="evnt-pic col-md-12 col-xs-12"> 
 @if(!empty($acc_event[$acev]->event_image))
  @if(is_numeric($acc_event[$acev]->event_image)) 	 
    <?php $evsstoimg = DB::table('event_catimages')->where('id', '=', $acc_event[$acev]->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	
   @if(!empty($evsstoimg[0]->id))	
     <img src="{!!URL::to('uploads/'.$evsstoimg[0]->ecat_path.'/'.$evsstoimg[0]->ecat_name.'/'.$evsstoimg[0]->ecat_image)!!}" class="img-responsive"/>
   @endif 
  @else					  
   <img src="{!!URL::to('uploads/events/'.$acc_event[$acev]->account_type.'/'.$acc_event[$acev]->event_image)!!}">	
  @endif 
 @else
   <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive">
 @endif
					  </div>
                      <div class="evnt-con col-md-12">
                        <h4> {!! ucfirst($acc_event[$acev]->event_name) !!} </h4>
					<?php 
					 if(!empty($acc_event[$acev]->event_date)){
					   $ev_date = date('M d, Y', strtotime($acc_event[$acev]->event_date));
					 } else {
					   $ev_date = 'n/a'; 
					 }
					?>
                        <div class="date"> <span class="date-icon"> </span> {!! $ev_date !!} @ {!! $acc_event[$acev]->event_time !!} </div>
       <div class="location"> <span class="location-icon"> </span>{!! $acc_event[$acev]->event_venue !!}
        <?php 
		 if(!empty($acc_event[$acev]->country)){	 
		   $gcname = DB::table('countries')->where('id', '=', $acc_event[$acev]->country)->select('id','name')->get(); 
		  if($gcname[0]->id){
			echo ' / '.$gcname[0]->name;  
		  } 
		 }   
		?>	   
	   </div>
                        <div class="price 262">
						<strong>
			
		  @if($aet->event_cost == 'paid')
			  
		   <?php $alet_price = explode("-", $aet->event_price); ?>
		   
			@if(!empty($alet_price[0]))
			 <?php
			  $lower = $alet_price[0];
			  
			  $alet_prc = toMoney( $lower);
			  for ($x = 1; $x < sizeof($alet_price); $x++) {
				$alet_prc .= ' - '. toMoney(  $alet_price[$x] );
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
  <div class="follow">More Details</div>
                      </div>
				 </a>	  
                    </div>
                  </div>                  
				<?php } ?>
       </div>
	</div>
   </div>			
  @if(sizeof($acc_event) > 4)	
   <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
	<i class="fa fa-chevron-left"></i> <span class=" fontawesome-icon-list"></span> <span class="sr-only">Previous</span>
   </a> 
   <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"> 
    <i class="fa fa-chevron-right"></i> <span class=" fontawesome-icon-list"></span> <span class="sr-only">Next</span> 
   </a> 
  @endif
   </div>
   </div>
  @else
    <div style="text-align:center; font-size:15px;">  
     @if(isset($getlid) && $event_details[0]->u_id == $getlid) 
	  You don’t have any upcoming events yet!  
       <a href="{!!URL($event_details[0]->account_url.'/account/createEvent')!!}">Create Event Now</a>   
	 @else
		<b>{!!$event_details[0]->name!!}</b> does not have any upcoming events.	 
     @endif	
	</div>	
  @endif 		  
      </div>
    </div>
  </div>
</section>
@stop
@section('scripts')
<script>
  jQuery(document).ready(function(){  
	 jQuery('body').delegate("#follow-page", 'click', function(){
		var token = jQuery('#_token').val();
        var postdata = {'ascid': {!! $event_details[0]->id !!}, '_token': token};
		jQuery("#follow-page").html('Following');
	    var datapst = $.post( '{!!URL("account/follow")!!}', postdata );
	   datapst.done(function( data ) {
	   if(data == 'success'){   
	    window.location='{!!URL($event_details[0]->account_url)!!}';		 
	   } else { 
	     jQuery("#follow-page").html('Follow');
		 //jQuery("#error-lreq").html('Login required'); 
		 window.location = '{!!URL("createPage/fap")!!}';		  
	   } 	
	  });		  
	 }); 
	 Tipped.delegate('.delegation-ajax', {
		 ajax: {
		   url: '{!!URL("evt")!!}',
		   type: 'post'
		 },
		  skin: 'light',
		  radius: false,
		  size: 'small',
		  position: 'topleft'
		});				
  });
   
 function unflw(flid){
   if(flid != null){
	 var tken = jQuery( "input#_token" ).val();  
	 var postdt = {flwid: flid, _token: tken};
	var datapost = $.post('{!!URL("account/unfollow")!!}', postdt);
	 datapost.done(function( data ) {
	  if(data == 'succ'){
		window.location='{!!URL($event_details[0]->account_url)!!}';	
	  } else {	
		jQuery("#unfollow-page").html('Unfollow');		
	  }	
	 });	
   } 
  }
 /* function topEvent(evurl){
   $.ajax({
       type: "GET",
       url: '{!!URL("accevt")!!}/'+evurl,
     beforeSend: function(){
      $("#collapseOne").html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive popup-event"/>');
	 },
     complete: function(){ },       	
    success: function(getdata) {	
	 $("#accordion").html(getdata);		      
    }
   });
  }
  
<?php if(!empty($acc_event[0]->id)) { ?>  
 topEvent('{!! $acc_event[0]->event_url !!}');
<?php }  ?>  
*/  
  function saveEvent(evurl){   
    $.ajax({
       type: "GET",
       url: '{!!URL("saveEvent")!!}/'+evurl,
     beforeSend: function(){ },
     complete: function(){ },       	
    success: function(sdata) { 
	 if(sdata == 'succ'){
	  $("#full-success").css("display", "block");
		$("#es-mess").html('Event successfully saved on your account');		
	  setTimeout(function () {
        window.location = '{!!URL($event_details[0]->account_url)!!}'; 
      }, 500);		 
	 } else if(sdata == 'not-log') {
		 <?php //session(['persignup' => 'lnot-saved']); ?>
	  window.location = '{!!URL("createPage/lnot-saved")!!}';
	 } else {
	  window.location = '{!!URL("/")!!}'; 
	 }         
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
		 window.location = '{!!URL($event_details[0]->account_url)!!}'; 
		}, 500); 	  
	  } else {	
		window.location='{!!URL("/")!!}';
	  }	
	 });	
   } 
  }  
</script>
<script> 
  var geocoder = new google.maps.Geocoder(); 
  function mgeocodeAddress(mlocations, orgtitles) {
    geocoder.geocode({address:mlocations}, function (results,status)
      { 
         if (status == google.maps.GeocoderStatus.OK) {
          var p = results[0].geometry.location;
          var lats=p.lat();
          var lngs=p.lng();
		  var myLatLng = {lat: lats, lng: lngs};
		var map = new google.maps.Map(document.getElementById('account-map'), {
          zoom: <?php echo config("app.googlezoom" , 10); ?>,
          center: myLatLng
        });
		iconSize = null;
	var markerImage = new google.maps.MarkerImage("{!!URL::to('assets/public/default2/images/account-page.png')!!}", null, null, null, iconSize);			
		var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: orgtitles,
		  icon: markerImage
        });
	  marker.addListener('click', function() {
		map.setZoom(8);
		map.setCenter(marker.getPosition());
	  });
        } else {  }
      }
    );
  } 
var mlocations = ['{!!$maddArr!!}'];
var morg_title = ['{!!$event_details[0]->name!!}'];
mgeocodeAddress(mlocations[0], morg_title[0]);

 jQuery(document).ready(function(){  
	setTimeout(function () {
	  jQuery( ".gm-style" ).after(jQuery("#saddress-box"));
    }, 2800); 
 });
</script>
@stop