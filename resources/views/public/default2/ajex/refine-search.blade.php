 @if(!empty($refine_events[0]->id))
	@foreach($refine_events as $aet) 
     <div class="rock-event {!! $cust_class !!}">
     <div class="evnt-pic col-md-5 col-ms-5 col-xs-12">
	  @if(!empty($aet->event_image))	
@if(is_numeric($aet->event_image)) 	 
   <?php $evtoimg = DB::table('event_catimages')->where('id', '=', $aet->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	
    @if(!empty($evtoimg[0]->id))	
     <img src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}" class="img-responsive"/>
    @endif 
  @else		  
		<img src="{!!URL::to('uploads/events/'.$aet->account_type.'/'.$aet->event_image)!!}" class="img-responsive"/>
 @endif 	
	  @else
		<img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"> 	  
	  @endif
	 </div>
     <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
<h4> 
 <a href="{!!URL('event/'.$aet->event_url)!!}" class="delegation-ajax" data-tipped-options="ajax:{data:{ event: '{!! $aet->event_url !!}', _token: '{!! csrf_token() !!}' }}" target="_blank"> 
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
				?>
			   {!! $aev_date !!} @ {!! $event_time !!} 
				</div>
            <div class="location"> <span class="location-icon"> </span> 
			 <a href="#getm-{{$wo}}" onclick="chgvsEventmap('{{$wo}}')"> {!! $aet->event_venue !!} {!! $ev_cuntry !!} </a>
			</div>
        <div class="price line-ref"> 
		<strong>
			
		  @if($aet->event_cost == 'paid')
			  
		   <?php $alet_price = explode("-", $aet->event_price); ?>
		   
			@if(!empty($alet_price[0]))
			 <?php
			  $lower = $alet_price[0];
			  
			  $alet_prc = toMoney( $lower );
			  for ($x = 1; $x < sizeof($alet_price); $x++) {
				$alet_prc .= ' - '. toMoney(  $alet_price[$x]);
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
	<!-- <a class="event-more" href="{!!URL('event/'.$aet->event_url)!!}">More Details</a> -->		
		<?php $ur_event = DB::table('users_events')->where('e_id', '=', $aet->id)->where('u_id', '=', $clid)->select('e_id')->get(); ?>
		  @if(!empty($ur_event[0]->e_id))	
           <a onclick="unattend({!! $ur_event[0]->e_id !!})" href="javascript:void(0)" class="save-event event-attend">Unattend</a>		
          @else
		   <a onclick="saveEvent('{!! $aet->event_url !!}')" href="javascript:void(0)" class="save-event event-attend">Attend</a> 
          @endif 	
<?php  
 if(!empty($aet->account_id)){
	 
  $followu = DB::table('user_follows')->where('follow_id', '=', $aet->account_id)->where('u_id', '=', $clid)->where('follow_type', '=', 'account')->select('id','follow')->get(); 
?>		  
	 @if(!empty($followu[0]->id))
	  @if($followu[0]->follow == 'y')	  
		<a href="javascript:void(0)" class="unfollow-event save-event event-attend">Following</a>
	  @endif
    @else	
<?php $checm = DB::table('account_details')->where('id', '=', $aet->account_id)->where('u_id', '!=', $clid)->select('id')->get(); ?>
    @if(!empty($checm[0]->id))		  
	 <a href="javascript:void(0)" onClick="followEvent({!! $aet->account_id !!})" class="follow-event save-event event-attend">Follow</a>
 	@endif  				 
   @endif 
<?php } $wo++; ?>	
		</div>	   
     </div>
     </div>
	 @endforeach
    @else 
	 <div class="rock-event"> Unfortunately we can’t find any events for you. Please adjust your search options </div>  
    @endif 
<input type="hidden" id="rs_type" value="ref_search"/>
<script>
 $('#cpage').val('{!! $refine_events->currentPage() !!}');
 $('#max_page').val('{!! $refine_events->lastPage() !!}');
</script>
<script> 
 var vsmarkers = [];
<?php if(!empty($refine_events[0]->id)){ ?>
  var delay3 = 100;
 var infowindow2 = new google.maps.InfoWindow();
 var latlng2 = new google.maps.LatLng(37.0902, 95.7129);
  var mapOptions2 = {
    zoom: 2,
    center: latlng2,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var geocoder2 = new google.maps.Geocoder(); 
  var bound3 = new google.maps.LatLngBounds();
  function rgeocodeAddress(total_mval,rsmloc, orgtitles, orgsurls, orgcount2, theNext2) {
    geocoder2.geocode({address:rsmloc}, function (results2,status2)
      { 
         if (status2 == google.maps.GeocoderStatus.OK) {
          var p1 = results2[0].geometry.location;		   
          var lat1=p1.lat();
          var lng1=p1.lng();
          createMarker2(total_mval, rsmloc,lat1,lng1,orgtitles,orgsurls,orgcount2);
        } else {
           if (status1 == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
            next2Address--;
            delay3++;
          } else { }   
        }
        theNext2();
      }
    );
  }  
  
  var map3 = new google.maps.Map(document.getElementById("main-map"), mapOptions2);
 function createMarker2(tot_mval, adds,lat1,lng1,orgtitles,orgsurls,orgcount2) { 
   var markerImage = new google.maps.MarkerImage("{!!URL::to('assets/public/default2/images/event.png')!!}", null, null, null, iconSize);			
   var marker2 = new google.maps.Marker({
     position: new google.maps.LatLng(lat1,lng1),
     map: map3,
	 icon: markerImage
   });				 
    if(agent == "iphone") {
     var iconSize = new google.maps.Size(16,19);
    } else {
     iconSize = null;
    }	
    if(agent == "default") {
      google.maps.event.addListener(marker2, "mouseover", function() {
       this.old_ZIndex = this.getZIndex();
       this.setZIndex(9999);
       $("#marker"+orgcount2).css("display", "inline");
       $("#marker"+orgcount2).css("z-index", "99999");
      });
    }
   var contentString3 = adds;
	 gmarkers.push(marker2);	  
    google.maps.event.addListener(marker2, 'click', function() {
	 infowindow2.setContent("<div class='marker_title' id='getm-"+orgcount2+"'><a target='_blank' href='event/"+orgsurls+"'>"+orgtitles+"</a></div>"
	  +"<div class='marker_address'>"+String(contentString3)+"</div>"); 
     infowindow2.open(map3,marker2);
	 map3.setZoom(8);
	 map3.setCenter(marker2.getPosition());	 
   });
   vsmarkers.push(marker2);
   bound3.extend(marker2.position);
   
 /******circle********/ 
 @if($location_range != 0)
  @if($ucntyname == 'cn')
	  
	var radius = {!!$location_range!!}*1000; //km to meter 
	
  @else
	  
	var radius = {!!$location_range!!}*1609.344;  //miles to meter  
	
  @endif 	  
  
    geocoder2.geocode( { 'address': adds}, function(results2,status2) {
     if (status2 == google.maps.GeocoderStatus.OK) {
       side_bar_html = "";
	   map3.setZoom(4);
       map3.setCenter(results2[0].geometry.location);
      var searchCenter = results2[0].geometry.location;
     //  if (circle) circle.setMap(null);
        circle = new google.maps.Circle({
		 center:searchCenter,
         radius: radius,
		 strokeColor: '#0C79B2',
         strokeOpacity: 0.8,
         strokeWeight: 2,
         fillOpacity: 0.35, //#0C79B2 FF0000
         fillColor: "#0C79B2",
         map: map3
		});  
      if(tot_mval == 1){		
          var bounds = new google.maps.LatLngBounds();
	      var foundMarkers = 0;
            if (foundMarkers > 0) {
              map3.fitBounds(bounds);
	        } else {
              map3.fitBounds(circle.getBounds());
            }
			google.maps.event.addListenerOnce(map3, 'bounds_changed');	
      }			
     }
    });
	
   @endif	
   
  /*******circle********/	
 }
 
  var rsmloc = [<?php echo '"'.implode('","', $rs_maarray).'"';?>];
  var rsorg_title = [<?php echo '"'.implode('","', $rs_mTiAr).'"';?>];
  var rsorg_surl = [<?php echo '"'.implode('","', $rs_mog_slar).'"';?>];
  var total_val = <?php echo sizeof($rs_maarray); ?>; 
  var next2Address = 0;
 function theNext2() {
  if (next2Address < rsmloc.length) {
    setTimeout('rgeocodeAddress("'+total_val+'","'+rsmloc[next2Address]+'","'+rsorg_title[next2Address]+'","'+rsorg_surl[next2Address]+'","'+next2Address+'",theNext2)', delay3);
   next2Address++;
  } else {
    map3.fitBounds(bound3);
  }
 }
  theNext2();
<?php } ?>  
 function chgvsEventmap(val_addr){
  google.maps.event.trigger(vsmarkers[val_addr], 'click');	
 }
</script>