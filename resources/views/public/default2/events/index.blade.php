@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')

<script>
  var twitterHandle = 'DiscoverYourEvent';
	function tweetEvent(url, title) {
	  window.open('https://twitter.com/share?url='+escape(url)+'&text='+title+ 'via@' + twitterHandle, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
	 return false; 
	}

 	function linedinEvent(url, title) {
	  var intWidth = intWidth || '500';
	  var intHeight = intHeight || '400'; 
	  var strParam = 'width=' + intWidth + ',height=' + intHeight;
	  objWindow = window.open('http://www.linkedin.com/shareArticle?url='+url,title,strParam).focus();
	 }
</script>

@if($event_data[0]->password_estatus == 'y')	  
<div data-backdrop="static" role="dialog" class="modal fade in" id="eventpss-pop-up" style="display:block; width:100%;">
  <div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content sign_up-form">
	  <div class="modal-header text-center">
		<h3 class="modal-title">Event is under password protection</h3>						
	  </div>
	   <div id="error-lpopall" class="col-md-12"></div>
	  <div class="modal-body">
		<form method="post" id="protect-pass" class="form-inline">
		  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
		   <div class="col-lg-9 col-sm-9 col-xs-9">
			<input type="password" class="form-control evpass" placeholder="Password" name="event_password">
			<div id="error-evpass" class="help-block with-errors"></div>
		   </div>	
		   <div class="col-lg-3 col-sm-3 col-xs-3">
		    <input type="button" class="btn discover-btn save-btn" value="Unlock" style="margin-top:0 !important;" id="unlock">
		  </div>
		  </div>
		</form>
	  </div>
	</div>
  </div>
</div>

<div class="evpass modal-backdrop fade in"></div>
  <div class="full" id="fullevent" style="display:none">	
 @else
  <div class="full">	 
 @endif 
 
@if(!empty($event_data[0]->event_image))		  
	@if(is_numeric($event_data[0]->event_image)) 	 
   		<?php $evtoimg = DB::table('event_catimages')->where('id', '=', $event_data[0]->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	

    	@if(!empty($evtoimg[0]->id))
    		<?php $bac_img = URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image); ?>	
     	@else
			<?php $bac_img = URL::to('assets/public/default2/images/events-pic1.jpg'); ?> 		
    	@endif 
  	@else
		<?php $bac_img = URL::to('uploads/events/'.$event_data[0]->account_type.'/'.$event_data[0]->event_image); ?>
	@endif 	
@else
	<?php $bac_img = URL::to('assets/public/default2/images/events-pic1.jpg'); ?> 
@endif
	  
<meta property="og:image" content="{!!$bac_img!!}">
<meta property="og:url" content="{!!URL('event/'.$event_data[0]->event_url)!!}">
<meta property="og:title" content="{!!ucfirst($event_data[0]->event_name)!!}"> 

<?php 

/*Event Date*/
if(!empty($event_data[0]->event_date) && $event_data[0]->event_dtype == 'single'){
	$ev_date = date('M d, Y', strtotime($event_data[0]->event_date));
} else if($event_data[0]->event_dtype == 'multi'){ 		
	$getmdate = DB::table('event_multidate')->where('ev_id', '=', $event_data[0]->eid)->select('id','start_date')->get(); 
  	$ev_date = date('M d, Y', strtotime($getmdate[0]->start_date)); 
} else {
  	$ev_date = 'n/a'; 
}

//Country
if(!empty($event_data[0]->country)){	 
	$getvcy = DB::table('countries')->where('id', '=', $event_data[0]->country)->select('name')->get(); 
   	$countryn = $getvcy[0]->name;			   
} else {
	$countryn = null;	 
}  

//Description
if(!empty($event_data[0]->event_description)){
	$shortDescription = '';
	$fullDescription = trim(strip_tags($event_data[0]->event_description));
	$initialCount = 100;
  	
  	if(strlen($fullDescription) > $initialCount) {
		$shortDescription = substr($fullDescription,0,$initialCount)."…";
  	} else {
		$shortDescription = $fullDescription;
  	}

} else {
	$shortDescription = null;  
}
?>		

<div class="events-page" style="background:rgba(0, 0, 0, 0) url('{!!$bac_img!!}') no-repeat scroll 0 0 / 100% 100%;">
@if(!empty($event_data[0]->account_id)) 
	<div class="container cont">				  
	   <div class="event-detail col-md-8 col-sm-8 evnt">
        <h3>{!! ucfirst($event_data[0]->event_name) !!}</h3>		
		
		@if(!empty($accdata[0]->upload_file))
	  		<img src="{!!URL::to('uploads/account_type/'.$cimg_apath.'/'.$accdata[0]->upload_file)!!}" class="img-responsive"/>
    	@else
	  		<img src="{!!URL::to('assets/public/default2/images/fox-logo.png')!!}" class="img-responsive"/>	
    	@endif
    	
    	<p>
     		<span class="date-text">Address:</span>  
	  		@if(!empty($accdata[0]->address))
				{!!$accdata[0]->address!!},
	  		@endif 

	  		@if(!empty($bcity))
				{!!$bcity!!},  
	  		@endif	

	  		@if(!empty($bstates))
				{!!$bstates!!},  
	  		@endif 

	  		@if(!empty($bcountry))
				{!!$bcountry!!},  
	  		@endif

			{!!$accdata[0]->zip_code!!} 

	  		<br>
	  		@if(!empty($accdata[0]->phone) || !empty($accdata[0]->email)) 				 
		 	<span class="date-text"> Contact:</span> 
   			{!!$accdata[0]->phone!!} <br>

	   			@if(!empty($accdata[0]->email)) 	  
	        		<span class="date-text">Email:</span><a href="mailto:{{$accdata[0]->email}}" id="mail">{{$accdata[0]->email}}</a> <br>
	       		@endif
	  		@endif 	

	 		@if(!empty($accdata[0]->website))    
       			<a href="{{$accdata[0]->website}}">
					{{preg_replace('#^https?://#', '', $accdata[0]->website)}}  
       			</a>	  
       			<br>
     		@endif  					
   		</p>
<?php 
if(!empty($event_data[0]->account_id) && !empty($clid)){
	$followus = DB::table('user_follows')->where('follow_id', '=', $event_data[0]->account_id)->where('u_id', '=', $clid)->where('follow_type', '=', 'account')->select('id','follow')->get(); 
?>

@if(!empty($followus[0]->id))
	@if($followus[0]->follow == 'y')	  
		<a href="javascript:void(0)" onClick="unflw({!! $followus[0]->id !!})" class="unfollow-event save-event">Unfollow</a>
	@endif  
@else	  
    @if(!empty($accdata[0]->id))		  
		<a href="javascript:void(0)" onClick="followEvent({!! $event_data[0]->account_id !!})" class="follow-event save-event">Follow</a>
 	@endif  				 
@endif  				 

<?php } ?>

 <a href="{!!URL($accdata[0]->account_url)!!}" class="save-event" target="_blank" title="{!!$accdata[0]->name!!}">{!!$accdata[0]->name!!} Page</a>	
 <div class="help-block with-errors" id="error-lreq"></div>		  
		   <span class="col-md-12 full-success alert mar-top" id="full-success" style="display:none">
              <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
              <strong>Success!</strong> <span id="es-mess"></span>
             </span>  
        </div>
	 </div>
  @endif	 
 </div>
			<div class="container mar-top">	
			 <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">	
                  <div class="event-detail col-md-7 col-sm-7 col-xs-12 ">				  
                    <h3>{!! ucfirst($event_data[0]->event_name) !!}</h3>					
					<?php 
					 if(!empty($event_data[0]->event_date) && $event_data[0]->event_dtype == 'single'){
					  $ev_date = date('M d, Y', strtotime($event_data[0]->event_date));
					  $event_time = $event_data[0]->event_time;
					 } else if($event_data[0]->event_dtype == 'multi'){ 		
					   $getmdate = DB::table('event_multidate')->where('ev_id', '=', $event_data[0]->eid)->select('id','start_date','start_time')->get(); 
					  $ev_date = date('M d, Y', strtotime($getmdate[0]->start_date)); 
					  $event_time = $getmdate[0]->start_time;
					 } else {
					  $ev_date = 'n/a'; 
					  $event_time = $event_data[0]->event_time;
					 }
					?>					
                    <p class="ev-deat"><span class="date-text"> Date: </span>  {!! $ev_date !!} @ {!! $event_time !!}<br>
					 <span class="date-text">Venue:</span>  {!! $event_data[0]->event_venue !!}<br>
                     <span class="date-text"> Address: </span>
					  @if(!empty($event_data[0]->event_address))
					   {!!$event_data[0]->event_address!!},
					  @endif
					  @if(!empty($event_data[0]->address_secd))
						{!!$event_data[0]->address_secd!!},  
					  @endif	
					  @if(!empty($event_data[0]->city))
						{!!$event_data[0]->city!!},  
					  @endif
					  @if(!empty($event_data[0]->state))
						{!!$event_data[0]->state!!},  
					  @endif
					 {!! $countryn !!}<br>
  @if(!empty($event_data[0]->event_cost) && $event_data[0]->event_cost != 'free') 						
   <span class="date-text"> Tickets: </span> <span class="col-blue"> 
    <strong>
	<?php 
	 if(!empty($event_data[0]->event_price)){ 
	  $evprice = explode('-',$event_data[0]->event_price); 
	  if(isset($evprice[1])){	  
       echo '$'.number_format((float)$evprice[0], 2, '.', '').' - $'.number_format((float)$evprice[1], 2, '.', ''); 	   
	  } else {
	   echo '$'.number_format((float)$evprice[0], 2, '.', '');  
	  }
	 } 
	?>
    <span class="event-htag">
	 <a href="{{$event_data[0]->ticket_surl}}" target="_blank" class="save-event eventpage-attend">Buy Tickets</a>
	</span>
    </strong> 
   </span><br>
  @endif	
   	 <span class="date-text">Attending People:</span><strong>
	 <font color="#0c7ab3">{{ $attending_people }}</font>		  
	 @if(!empty($your_event[0]->e_id))	
	  <span class="event-htag">
       <a onclick="unattend({!! $your_event[0]->e_id !!})" href="javascript:void(0)" class="save-event eventpage-attend">Unattend</a>	
	  </span>
     @else
	  <span class="event-htag">
	   <a onclick="saveEvent()" href="javascript:void(0)" class="save-event eventpage-attend">Attend</a>  
      </span> 
     @endif 	
	</strong>
<div id="au-mess" style="color:red"></div>	
	  </p>
	 <div class="share-text">
	 <h2>SHARE THIS</h1>
 	  <div class="social-co">
<a title="Share On Facebook" target="_blank" onclick="javascript:window.open('//www.facebook.com/sharer/sharer.php?u={!!URL('event/'.$event_data[0]->event_url)!!}', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" href="javascript:" style="width: 45px;">
<img src="{!!URL::to('assets/public/default2/images/facebook1.png')!!}"/>
</a>
<a onClick="tweetEvent('{!!URL('event/'.$event_data[0]->event_url)!!}','{!!ucfirst($event_data[0]->event_name)!!}')" href="javascript:void(0);">
	<img src="{!!URL::to('assets/public/default2/images/twitter.png')!!}"/>
</a>
<!-- 
<a onClick="linedinEvent('{!!URL('event/'.$event_data[0]->event_url)!!}','{!!ucfirst($event_data[0]->event_name)!!}')" href="javascript:void(0);">
	<img src="{!!URL::to('assets/public/default2/images/share.png')!!}"/>
</a> 
-->
<a href="javascript:void(0);" data-toggle="modal" data-target="#share-pop-up">
	<img src="{!!URL::to('assets/public/default2/images/share1.png')!!}"/>
</a>
	<!-- Share pop_up Start-->
	<div id="share-pop-up" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content sign_up-form">
					  <div class="modal-header text-center">
						<button type="button" class="close pup-close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title text-left">Share this with your friend</h3>
						<div class="col-md-12" id="error-all"></div>
					  </div>
					   <div class="col-md-12" id="error-lpopall"></div>
				<div class="modal-body">
				 <form class="form-inline" id="sharewithf" method="post">
						<div class="form-group col-lg-12 col-sm-12 col-xs-12 share-mr">
							Friend's email
						</div>
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
							<input type="email" name="femail" id="femail" placeholder="Email Address" class="form-control sgmail" required/>
							  <div class="help-block with-errors" id="error-slemail"></div>
						  </div>
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12 share-mr">
							Message  
						</div>
						<input type="hidden" value="{!!$event_data[0]->event_url!!}" name="eurl"/>
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
							<textarea placeholder="Textarea" name="fmsg" id="fmsg" rows="3" class="form-control" required></textarea>
							 <div class="help-block with-errors" id="error-fn"></div>
						  </div>
						  <div class="share-content">
						   	<div class="col-lg-3 col-sm-4 col-xs-12 rem-text">
							  <img src="{!!$bac_img!!}"/>
							 </div>
						    <div class="col-lg-9 col-sm-8 col-xs-12">							
							<h5>{!! ucfirst($event_data[0]->event_name) !!}</h5>
							<p>{!!$shortDescription!!}</p>
							</div>
							<input type="hidden" name="_token" id="_token" value="{!!csrf_token()!!}">	
							   <div class="col-lg-12 col-sm-12 col-xs-12 share-btn">
							   <input type="reset" id="resetThis" style="display: none"/>
								<input type="submit" class="save-event" value="Share"/>
							   </div>
							</div>
				 </form>
				</div>
			</div>
		</div>
	</div>
	<!--Share pop_up END--> 
<!--	   <a href="#"> <i class="fa fa-facebook"></i> </a>  
		<a href="#"> <i class="fa fa-twitter"></i></a>-->
					</div>
					</div>					  
                    <br>
                  </div>	
<div class="col-md-5 col-sm-5 col-xs-12">				  
 <button id="wgetdir" class="gdirection-button">Get directions</button>	
  <div id="floating-panel" style="display:none">
    <b>Start: </b>
	<input type="text" id="start-dir" value=""/>  
    <b>End: </b>
	<input type="text" id="end-dir" value="" title="Event address" readonly />
	<input type="button" id="dir" class="gd-go" value="Go"/>
  </div>		  
 <div class="events-map" id="event-map"></div>
</div>
	<div class="descrip col-md-12 col-sm-12 col-xs-12">
     <p class="text bor"> <span class="des-icon"> </span> <strong>Description:</strong><br></p>
     <div class="text1">{!! $event_data[0]->event_description !!}</div>
    </div>
 </div>
 </div>
@stop
@section('scripts')
<script>  
 function unflw(flid){
   if(flid != null){
	 var tken = jQuery( "input#_token" ).val();  
	 var postdt = {flwid: flid, _token: tken};
	var datapost = $.post('{!!URL("account/unfollow")!!}', postdt);
	 datapost.done(function( data ) {
	  if(data == 'succ'){
		window.location='{!!URL("event/".$event_data[0]->event_url)!!}';	
	  } else {	
		window.location='{!!URL("event/".$event_data[0]->event_url)!!}';	
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
		 window.location = '{!!URL("event/".$event_data[0]->event_url)!!}';
		}, 500); 	  
	  } else {	
		window.location='{!!URL("event/".$event_data[0]->event_url)!!}';	
	  }	
	 });	
   } 
  }
  
  function saveEvent(){   
    $.ajax({
       type: "GET",
       url: '{!!URL("saveEvent/".$event_data[0]->event_url)!!}',
     beforeSend: function(){ },
     complete: function(){ },       	
    success: function(data) { 
	 if(data == 'succ'){
	  $("#full-success").css("display", "block");
		$("#es-mess").html('Event successfully saved on your account');		
	  setTimeout(function () {
        window.location = '{!!URL("event/".$event_data[0]->event_url)!!}'; 
      }, 500);		 
	 } if(data == 'not-log') {
	    window.location = '{!!URL("createPage/lnot-saved")!!}';
	 }        
    }
   });
  }

 function followEvent(eid){	 
  var token = jQuery('#_token').val();
        var postdata = {'ascid': eid, '_token': token};
		//jQuery("#follow-page").html('Following');
	    var datapst = $.post( '{!!URL("account/follow")!!}', postdata );
	   datapst.done(function( data ) {
	   if(data == 'success'){   
	    window.location='{!!URL("/event/".$event_data[0]->event_url)!!}';		 
	   } else { 
	    // jQuery("#follow-page").html('Follow');
		 jQuery("#error-lreq").html('Login required');  
	   } 	
	  });
 }  
 
  



/********direction-map***********/ 
function getLocation() {
 if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showAddress);		  
 }
}

function showAddress(position) {
	var zipcode = city = state = country = false;
	
	geocoder = new google.maps.Geocoder();

	var latlng = new google.maps.LatLng(position.coords.latitude , position.coords.longitude);
  
	geocoder.geocode({'latLng': latlng}, function(results, status) {
	 
		  if (status == google.maps.GeocoderStatus.OK) {
			  
		   if (results[1]) {
		   
		   var address = '';  
		   
			for (var i=0;  i< results[0].address_components.length; i++) {
				
				
			 for (var b=0; b < results[0].address_components[i].types.length; b++) {
				 
					//console.log( results[0].address_components[i].types );
					//console.log(results[0].address_components[i].long_name);
				
					if("postal_code" == results[0].address_components[i].types && zipcode == false){
						zipcode = results[0].address_components[i].long_name;
						break;
					}
		
					if ( results[0].address_components[i].types[b] == "administrative_area_level_2" && city == false) {
							city = results[0].address_components[i].long_name;
							break;
					  }
						
					  if (results[0].address_components[i].types[b] == "administrative_area_level_1" && state == false) {
							state = results[0].address_components[i].long_name;
							break;
					  }
					  
					  if (results[0].address_components[i].types[b] == "country" && country == false) {
								country = results[0].address_components[i].long_name;
								break;
					  }
				  
			 }
			 
			}
			
			var fulladdress = zipcode+", "+city+", "+state+", "+country;
			//console.log();
			
				jQuery( "#floating-panel" ).show();
				jQuery("#start-dir").val(fulladdress);
			
		   }
		  }
 });	  
}

function initvMap(myLatLng,orgtitles) {
  var directionsService = new google.maps.DirectionsService;
  var directionsDisplay = new google.maps.DirectionsRenderer;
  var map = new google.maps.Map(document.getElementById('event-map'), {	  
    zoom: 10,
   center: myLatLng
  });  
   iconSize = null;
  var markerImage = new google.maps.MarkerImage("{!!URL::to('assets/public/default2/images/event.png')!!}", null, null, null, iconSize);	
		var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: orgtitles,
		  icon: markerImage
        });  
    marker.addListener('click', function() {
	 map.setZoom(15);
	 map.setCenter(marker.getPosition());
	});
   directionsDisplay.setMap(map);
  document.getElementById('dir').addEventListener('click', function(){
   calculateAndDisplayRoute(directionsService, directionsDisplay);
  });
	  
 function calculateAndDisplayRoute(directionsService, directionsDisplay){
  directionsService.route({
   origin: document.getElementById('start-dir').value,
    destination: document.getElementById('end-dir').value,
    travelMode: google.maps.TravelMode.DRIVING
  }, function(response, status) {			
   if (status === google.maps.DirectionsStatus.OK) {
    directionsDisplay.setDirections(response);
   } else {
    window.alert('Directions request failed due to ' + status);
   }
  });		
 }
}
 
  var geocoder = new google.maps.Geocoder(); 
 function mgeocodeAddress(mlocations, orgtitles){
	 $( "#end-dir").val(mlocations);	 
   geocoder.geocode({address:mlocations}, function (results,status)
  { 
   if (status == google.maps.GeocoderStatus.OK) {
    var p = results[0].geometry.location;
    var lats=p.lat();
    var lngs=p.lng();
	var myLatLng = {lat: lats, lng: lngs};
	 initvMap(myLatLng,orgtitles);
   } else {  }
  });
 } 
  var mlocations = ['<?php echo implode('","', $maddArr);?>'];
  var morg_title = ['<?php echo ucfirst($event_data[0]->event_name);?>'];
 mgeocodeAddress(mlocations[0],morg_title[0]);
 
$( "#wgetdir" ).click(function() {
 getLocation();
});

 $("#sharewithf").submit(function(event) {
   event.preventDefault();
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;	  
	var frdmail = $( "input#femail" ).val();
   var frdmsg = $( "#fmsg").val();
    if(frdmsg == '' || frdmsg == ' '){
      $( "#error-fn" ).html( "Message field required" );	
	 return false;
	}	
	 if(frdmail.match(mailformat))
	{	
	  var datapst = $.post('{!!URL("sharewfriend")!!}', $('#sharewithf').serialize());
	  datapst.done(function( data ) {
	  if(data == 'error'){
		  $("#error-all").removeClass('full-success');
		$("#error-all").addClass('full-error');
		$( "#error-all" ).html( "Something wrong! please try again" );	
	  } else {	 
		$("#error-all").removeClass('full-error');
		$("#error-all").addClass('full-success');
		$( "#resetThis" ).trigger("click");
		$( "#error-all" ).html("Shared succefully!");	
	  }	
	 });
	} else {
	  $("#error-slemail").show();
	  $( "#error-slemail" ).html( "That email address is invalid" );
		return false;
	}	
 }); 
 $('#unlock').click(function(){
   var gepval = $('input[name=event_password]').val(); 
  if(gepval){
   <?php if(!empty($event_data[0]->pass_event)){ ?>
	if(gepval == '<?php echo $event_data[0]->pass_event;?>'){
	 $('#eventpss-pop-up').hide();
	 $('#fullevent').show();	
     $('.evpass').hide(); 	 
	} else {
	 $('#error-evpass').html('Invalid password');
	}
   <?php } ?>	
  }  
 });
</script>
@stop