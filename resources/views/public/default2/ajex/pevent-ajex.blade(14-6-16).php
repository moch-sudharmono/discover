<div class="full pop-design">
 <div class="map-evnt home-map" id="event-map"> </div>
<script>  
  var twitterHandle = 'DiscoverYourEvent';
	function tweetEvent(url, title) {
	  window.open('https://twitter.com/share?url='+escape(url)+'&text='+title+ ' via @' + twitterHandle, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
	 return false; 
	}
 function linedinEvent(url, title) {
  var intWidth = intWidth || '500';
  var intHeight = intHeight || '400'; 
  var strParam = 'width=' + intWidth + ',height=' + intHeight;
  objWindow = window.open('http://www.linkedin.com/shareArticle?url='+url,title,strParam).focus();
 }
</script>
  <?php
   if(!empty($event_data[0]->event_image)){
	if(is_numeric($event_data[0]->event_image)){ 	 
    $evtoimg = DB::table('event_catimages')->where('id', '=', $event_data[0]->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get();	
    if(!empty($evtoimg[0]->id)){     
	  $event_img = "uploads/".$evtoimg[0]->ecat_path."/".$evtoimg[0]->ecat_name."/".$evtoimg[0]->ecat_image;
    } else {
	$event_img = "assets/public/default2/images/events-pic1.jpg";	
	}
	} else { 
	 $event_img = "uploads/events/".$event_data[0]->account_type."/".$event_data[0]->event_image;
    }	 
   } else {
	 $event_img = "assets/public/default2/images/events-pic1.jpg";
   }
  ?>
  <meta property="og:image" content="{!!URL::to($event_img)!!}">
<meta property="og:url" content="{!!URL('event/'.$event_data[0]->event_url)!!}">
<meta property="og:title" content="{!!ucfirst($event_data[0]->event_name)!!}"> 

		<div class="col-md-4 col-sm-4 text-center round-pic">
		 <img src="{!!URL::to($event_img)!!}"/>        
        </div>
		 <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}"/>				
	     <div class="event-detail col-md-8 col-sm-8">
           <h3><a href="{!!URL('event/'.$event_data[0]->event_url)!!}" target="_blank">{!! ucfirst($event_data[0]->event_name) !!}</a></h3>	
<?php 
 if(!empty($event_data[0]->account_id)){ 
  $acp_name = DB::table('account_details')->where('id', '=', $event_data[0]->account_id)->select('name','account_url')->get(); 
?>
 <div class="hosted-vname"><span>Hosted By:</span> <a href="{!!URL($acp_name[0]->account_url)!!}">{!!$acp_name[0]->name!!}</a></div>
<?php } ?> 
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
                    <p class="font-set">Date:  {!! $ev_date !!} @ {!! $event_time !!}<br>
                      Venue:  {!! $event_data[0]->event_venue !!}<br>
                      Address: {!! $event_data[0]->event_address !!}<br>
					@if(!empty($event_data[0]->event_cost) && $event_data[0]->event_cost != 'free') 
                      Tickets:<font color="#0c7ab3"> 
					  @if($event_data[0]->event_cost == 'paid')
					     <?php $alet_price = explode("-", $event_data[0]->event_price); ?>
					    @if(!empty($alet_price[0]))
						 <?php $alet_prc = '$'.$alet_price[0]; 			
						  for ($x = 1; $x < sizeof($alet_price); $x++) {
						   $alet_prc .= ' - $'.$alet_price[$x];
						  }			 
						 ?> 
						 {!! $alet_prc !!}
					    @else
						 ${!! $event_data[0]->event_price !!}	
					    @endif					
					  @else
					   {!! ucfirst($event_data[0]->event_cost) !!}  
					  @endif
					  </font>
					 <font style="color:#0c7ab3; float:right; margin-right:15px;">
					  <a href="{{$event_data[0]->ticket_surl}}" target="_blank" class="btn-gr social-fb">Buy Tickets</a>
					 </font>
					 <br>
					@endif
  Attending People: <font color="#0c7ab3" class="col-blue"> {{ $attending_people }}</font>
 <font style="color:#0c7ab3; float:right; margin-right:15px;">			  
  <?php if(!empty($clid)){
	$ur_event = DB::table('users_events')->where('e_id', '=', $event_data[0]->eid)->where('u_id', '=', $clid)->select('e_id')->get(); ?>
   @if(!empty($ur_event[0]->e_id))
    <a href="javascript:void(0)" onclick="unattend('{!!$ur_event[0]->e_id!!}')" class="btn-gr social-fb">Unattend</a>   
   @else
	<a href="javascript:void(0)" onclick="saveEvent('{!!$event_data[0]->event_url!!}')" class="btn-gr social-fb">Attend</a>
   @endif 
  <?php } else { ?>
  <a href="javascript:void(0)" onclick="saveEvent('{!!$event_data[0]->event_url!!}')" class="btn-gr social-fb">Attend</a>
  <?php } ?> 
 </font>
</p>
                    <div class="des">
                      <p class="text bor"><span class="des-icon"> </span><strong>Description:</strong>
                      </p>
					 <?php 
					  if(!empty($event_data[0]->event_description)){
						$shortDescription = '';
						$fullDescription = trim(strip_tags($event_data[0]->event_description));
						$initialCount = 100;
						if (strlen($fullDescription) > $initialCount) {
						 $shortDescription = substr($fullDescription,0,$initialCount)."…";
						} else {
         				  $shortDescription = $fullDescription;
						}
					  } else {
						$shortDescription = null;  
					  }
				     ?>  
                     <p class="text1">
					  {!!$shortDescription!!}
					 </p>
                    </div>                   
  </div>
 <div class="gry-bg"> 	
	<div class="col-md-10 col-sm-8 col-xs-12 col-md-offset-2">		
	<a title="Share On Facebook" target="_blank" onclick="javascript:window.open('//www.facebook.com/sharer/sharer.php?u={!!URL('event/'.$event_data[0]->event_url)!!}', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" href="javascript:" style="width: 45px;">
      <img src="{!!URL::to('assets/public/default2/images/facebook1.png')!!}"/>
    </a>
	 <a onClick="tweetEvent('{!!URL('event/'.$event_data[0]->event_url)!!}','{!!ucfirst($event_data[0]->event_name)!!}')" href="javascript:void(0)" class="social-fb">
	  <img src="{!!URL::to('assets/public/default2/images/twitter.png')!!}"/>
	 </a>
	<a onClick="linedinEvent('{!!URL('event/'.$event_data[0]->event_url)!!}','{!!ucfirst($event_data[0]->event_name)!!}')" href="javascript:void(0);" class="social-fb">
	 <img src="{!!URL::to('assets/public/default2/images/share.png')!!}"/>
	</a> 	
<?php 
 if(!empty($clid)){
  if(!empty($event_data[0]->account_id)){
  $followu = DB::table('user_follows')->where('follow_id', '=', $event_data[0]->account_id)->where('u_id', '=', $clid)->where('follow_type', '=', 'account')->select('id','follow')->get(); 
?>
	 @if(!empty($followu[0]->id))
		@if($followu[0]->follow == 'y')	  
			<a href="javascript:void(0)" class="unfollow-event btn-gr social-fb">Unfollow</a>
		@endif
	@else	  
<?php $checm = DB::table('account_details')->where('id', '=', $event_data[0]->account_id)->where('u_id', '!=', $clid)->select('id')->get(); ?>			
    @if(!empty($checm[0]->id))		  
	 <a href="javascript:void(0)" onClick="followEvent({!! $event_data[0]->account_id !!})" class="follow-event btn-gr social-fb">Follow</a>
 	@endif  				 
   @endif  				 
<?php }} else { ?>
	 <a href="javascript:void(0)" onClick="followEvent({!! $event_data[0]->account_id !!})" class="follow-event btn-gr social-fb">Follow</a>
	  <div class="help-block with-errors" id="error-lreq"></div>	
<?php } ?>
	</div>
 </div>
</div>
<script> 
  var geocodervs = new google.maps.Geocoder(); 
  function mcitcaddress(vtsml, ogitctitle) {
	//  alert(vtsml);
    geocodervs.geocode({address:vtsml}, function (resultvs,statusvs)
      { 
         if (statusvs == google.maps.GeocoderStatus.OK) {
		  $(".pop-design").removeClass("evtm-popup");		 
		  $( ".home-map" ).removeClass( "without-map" );
          var pvs = resultvs[0].geometry.location;
          var lats=pvs.lat();
          var lngs=pvs.lng();
		  var myLatLng = {lat: lats, lng: lngs};
		var mapopup = new google.maps.Map(document.getElementById('event-map'), {
          zoom: 5,
          center: myLatLng
        });
		var marker = new google.maps.Marker({
          position: myLatLng,
          map: mapopup,
          title: ogitctitle
        });
        } else { 
         $(".pop-design").addClass("evtm-popup");	
		 $(".home-map").addClass("without-map");
		}
     });
  } 
var vtsml = ['<?php echo implode('","', $maddArr);?>'];
var mrgitctitle = ['<?php echo ucfirst($event_data[0]->event_name);?>'];
mcitcaddress(vtsml[0],mrgitctitle[0]);
</script>