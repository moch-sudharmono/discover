<div class="full pop-design">
 <div class="map-evnt home-map" id="event-map"> </div>
<script>
  function fbShare(url, title, descr, image, winWidth, winHeight) {
	var winWidth = 520;
	var winHeight = 350;
    var winTop = (screen.height / 2) - (winHeight / 2);
    var winLeft = (screen.width / 2) - (winWidth / 2);
   window.open('http://www.facebook.com/sharer.php?s=100&p[title]='+ title +'&p[summary]='+ descr +'&p[url]='+ url +'&p[images][0]='+ image, 'sharer', 'top='+ winTop +',left='+ winLeft +',toolbar=0,status=0,width='+ winWidth + ',height=' + winHeight);
  }
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
					 if(!empty($event_data[0]->event_date)) {
					   $ev_date = date('M d, Y', strtotime($event_data[0]->event_date));
					 } else {
						$ev_date = 'n/a'; 
					 }
					?>
                    <p class="font-set">Date:  {!! $ev_date !!} @ {!! $event_data[0]->event_time !!}<br>
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
					  <a href="{{$event_data[0]->ticket_surl}}" target="_blank"><u> Buy Tickets</u></a>
					 </font>
					 <br>
					@endif
  Attending People: <font color="#0c7ab3" class="col-blue"> {{ $attending_people }}</font>
 <font style="color:#0c7ab3; float:right; margin-right:15px;">			  
  <?php if(!empty($clid)){
	$ur_event = DB::table('users_events')->where('e_id', '=', $event_data[0]->eid)->where('u_id', '=', $clid)->select('e_id')->get(); ?>
   @if(!empty($ur_event[0]->e_id))
    <a href="javascript:void(0)" onclick="unattend('{!!$ur_event[0]->e_id!!}')"><u>Unattend</u></a>   
   @else
	<a href="javascript:void(0)" onclick="saveEvent('{!!$event_data[0]->event_url!!}')"><u>Attend</u></a>
   @endif 
  <?php } else { ?>
  <a href="javascript:void(0)" onclick="saveEvent('{!!$event_data[0]->event_url!!}')"><u>Attend</u></a>
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
	 <a onClick="fbShare('{!!URL('event/'.$event_data[0]->event_url)!!}','{!!ucfirst($event_data[0]->event_name)!!}','{!!$shortDescription!!}','{!!URL::to($event_img)!!}')" href="javascript:void(0)" class="social-fb">
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
  var geocoder3 = new google.maps.Geocoder(); 
  function mcaddress(vsml, ogtitle) {
    geocoder3.geocode({address:vsml}, function (result3,status3)
      { 
         if (status3 == google.maps.GeocoderStatus.OK) {
		  $(".pop-design").removeClass("evtm-popup");		 
		  $( ".home-map" ).removeClass( "without-map" );
          var p3 = result3[0].geometry.location;
          var lats=p3.lat();
          var lngs=p3.lng();
		  var myLatLng = {lat: lats, lng: lngs};
		var mapopup = new google.maps.Map(document.getElementById('event-map'), {
          zoom: 7,
          center: myLatLng
        });
		var marker = new google.maps.Marker({
          position: myLatLng,
          map: mapopup,
          title: ogtitle
        });
        } else { 
         $(".pop-design").addClass("evtm-popup");	
		 $(".home-map").addClass("without-map");
		}
      }
    );
  } 
var vsml = ['<?php echo implode('","', $maddArr);?>'];
var mrgtitle = ['<?php echo ucfirst($event_data[0]->event_name);?>'];
mcaddress(vsml[0],mrgtitle[0]);
</script>