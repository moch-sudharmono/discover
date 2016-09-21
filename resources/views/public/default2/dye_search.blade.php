@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
 {!!HTML::script("assets/public/default2/js/locationpicker.jquery.js")!!}
 {!!HTML::script("assets/public/default2/js/map-label.js")!!}
  {!!HTML::script("assets/public/default2/js/event/itc-js.js")!!} 
<?php 
	$tok = csrf_token();
	
 $evday = array('today'=>'Today','tomorrow'=>'Tomorrow','week'=>'This Week','weekend'=>'This Weekend','month'=>'This Month');
 

  if(!empty($search_data['category'])){
		$fdata = $search_data['category']; 
  } else {
		$fdata = old('category');  
  }
  if(!is_array($fdata)){
	  $fdata = array();
  }
   
   
  if(!empty($search_data['city'])){ 
		$fcydata = $search_data['city'];	
  } else {
		$fcydata = old('city');
  }
 
  
  if(!empty($search_data['eventday'])){ 
   $feddata = $search_data['eventday'];
   if($feddata == 'today'){
	$fedname = 'Today';   
   } else if($feddata == 'tomorrow') {
	$fedname = 'Tomorrow';    
   } else if($feddata == 'week'){
	$fedname = 'This Week';  
   } else if($feddata == 'weekend'){
	$fedname = 'This Weekend';   
   } else {
	$fedname = 'This Month';   
   }
  } else {
	$feddata = old('eventday'); 
	if($feddata == 'today'){
	$fedname = 'Today';   
   } else if($feddata == 'tomorrow') {
	$fedname = 'Tomorrow';    
   } else if($feddata == 'week'){
	$fedname = 'This Week';  
   } else if($feddata == 'weekend'){
	$fedname = 'This Weekend';   
   } else {
	$fedname = 'This Month';   
   }
  }  
  if(!empty($search_data['optional_all'])){ 
   $foalldata = $search_data['optional_all'];	
  } else {
	$foalldata = old('optional_all'); 
  }
?>
<section class="event-in-your-area">
  <div class="container text-center"> 
 <div class="mainsmap-cover"> 
  <div class="map search_map" id="search_map"></div>  
 </div> 
    <div class="col-md-3 col-sm-4 col-xs-12">
      <div class="col-md-12 col-sm-12 col-xs-12 event-area-form">
        <form class="form-inline" method="post">
          <h3> Refine Your Search </h3>		  
          <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">		  
            <input type="text" placeholder="City or Postal Code" value="{!! $fcydata !!}" name="city" class="form-control">
          </div>
          <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
           <div class="sel">
            <select class="dye_search selectpicker" name="category[]" data-live-search="true" multiple>
				 <option value="">All Categories</option>
				@foreach($evtsdata as $key => $etdt)    
					@if(in_array($key , $fdata))
						<option value="{!! $key !!}" selected>{!! $etdt !!}</option>
					@else
						<option value="{!! $key !!}">{!! $etdt !!}</option>
					@endif
				@endforeach
            </select>
           </div>
          </div>		 
		  
          <div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">			  
			<select class="selectpicker" name="eventday" title="Event Day(s)">
			<option value="" selected>Select Day</option>
			  @if(!empty($feddata))
			   <option value="{!! $feddata !!}">{!! $fedname !!}</option>
               @foreach($evday as $edkey=>$edval)
			    @if($edkey != $feddata)
                 <option value="{!!$edkey!!}">{!!$edval!!}</option>  			  
			    @endif
               @endforeach 		   
		      @else
			   @foreach($evday as $edkey=>$edval)
                <option value="{!!$edkey!!}">{!!$edval!!}</option>  			  
               @endforeach   
			  @endif             
            </select>
          </div>
<div class="form-group col-lg-12 col-sm-12 col-xs-12 pad-non">
  <input type="name" class="form-control" placeholder="Event Name or Venu Name " value="{!! $foalldata !!}" name="optional_all">		  
</div>
		   <input type="hidden" name="hs" value="y1"/>	
		  <input type="hidden" name="_token" id="_token" value="{!! $tok !!}">	
          <input type="submit" value="Search" class="btn b-btn">		 
        </form>
      </div>     
    </div>
    <div class="col-md-9 col-sm-8 col-xs-12">	
      <div class="sright-side-event-area">   
	  <div class="featu-sear">
	   <div class="col-md-12 col-sm-12 col-xs-12 tab-events text-left">	   
	   <div class="cou"> <span class="text-left blue-col featur">Search Result </span> </div>
        <div class="tab-content">
          <div id="all_event" class="tab-pane fade in active">
		    @if(!empty($al_event[0]->id))
			@foreach($al_event as $aet) 
            <div class="rock-event">
              <div class="evnt-pic col-md-3 col-ms-5 col-xs-12"> 			  
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
              <div class="evnt-con col-md-9 col-ms-7 col-xs-12">
<h4>
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
				 if(!empty($aet->country)){	 
				   $getvcy = DB::table('countries')->where('id', '=', $aet->country)->select('name')->get(); 
                  $countryn = '/ '.$getvcy[0]->name;			   
				 } else {
				  $countryn = null;	 
				 } 
				?>
			   {!! $aev_date !!} @ {!! $event_time !!} 
			</div>
        <div class="location"> <span class="location-icon"> </span> {!! $aet->event_venue !!} {!! $countryn !!} </div>
                <div class="price line-170"> <strong>
			
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
		  </strong> 
			</div>
                <div class="attend">
                  <?php $allevt_ct = DB::table('users_events')->where('e_id', '=', $aet->id)->count(); ?>	
				People Attending : <span class="blue-col"> {!! $allevt_ct !!} </span></div>	
              </div>
            </div>
			@endforeach
		   @else 
			   <div class="rock-event"> Unfortunately we can’t find any events for you. Please adjust your search options </div>  
           @endif 
          </div>
		 <div id="loading-loader"></div> 
 @if(!empty($al_event))		 
	<input type="hidden" id="cpage" value="{!! $al_event->currentPage() !!}" />
    <input type="hidden" id="max_page" value="{!! $al_event->lastPage() !!}" />	 
 @endif		 
        </div>
      </div> 
	  </div>
	  </div>
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
$(window).on('scroll', function() {  
 var vwind_width = $( window ).width();
 if(vwind_width > 1023){
   if ($(window).scrollTop() > 350) {	   
	 $('.event-area-form').addClass('scroll-eventar-form');   
	$('#search_map').addClass('scroll-smap-top');  
   } else {
	 $('.event-area-form').removeClass('scroll-eventar-form'); 
	$('#search_map').removeClass('scroll-smap-top');     
   } 
   if ($(window).scrollTop() > 350) {	   
	 $('.sright-side-event-area').addClass('search_content_area');  
   } else { 
    $('.sright-side-event-area').removeClass('search_content_area');  
   } 
 }
}); 
 
setInterval(function() {
	 didScroll = false;
  if (didScroll){
    didScroll = false;
    if(($(document).height()-$(window).height())-$(window).scrollTop() < 10){
     if(!$('#loading-loader').html() || $('#loading-loader').html() == ' '){ 	
	   pageCountUpdate(); 
	 }	 
	}  
  }
}, 1000);

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
	<?php
		$cat_string = implode('&amp;cat[]=' , array_map('urlencode', $fdata));
	?>

     $.ajax({
        type: "GET",
        url: '{!!URL("evnt/search-events?page=")!!}'+npage+'&cat={!! $cat_string !!}&city={!! $fcydata !!}&day={!! $feddata !!}&all={!! $foalldata !!}',       
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
	/*if( resech == 'ref_search'){
	  var datapst = $.post('{!!URL("evnt/drt-search")!!}/'+npage, $('#refine_search').serialize());
	  $('#loading-loader').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive" style="width:65px"/>');
	 datapst.done(function( ifdata ) {	
      $('#loading-loader').html(' ');	 
      $("#all_event").append(ifdata);		  	
	 });
	}*/
 }
 
$(document).ready(function(){	
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

@if(isset($addressArray))
	
 var delay = 100;
  var infowindow = new google.maps.InfoWindow();
  var latlng = new google.maps.LatLng(21.0000, 78.0000);
   var mapOptions = {
    zoom: 2,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var geocoder = new google.maps.Geocoder(); 
  var bounds = new google.maps.LatLngBounds();
   var map2 = new google.maps.Map(document.getElementById("search_map"), mapOptions);
     function geocodeAddress(address, orgtitle, orgsurl, orgcount, thesNext) {
    geocoder.geocode({address:address}, function (results,status)
      { 
         if (status == google.maps.GeocoderStatus.OK) {
          var p = results[0].geometry.location;
          var lat=p.lat();
          var lng=p.lng();
          createMarker(address,lat,lng,orgtitle,orgsurl,orgcount);
        }
        else {
           if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
            nextAddress--;
            delay++;
          } else {
                        }   
        }
        thesNext();
      }
    );
  }
  
 function createMarker(add,lat,lng,orgtitle,orgsurl,orgcount){
   var contentString = add;
   var markerImage = new google.maps.MarkerImage("{!!URL::to('assets/public/default2/images/event.png')!!}", null, null, null, iconSize);		
   var marker = new google.maps.Marker({
     position: new google.maps.LatLng(lat,lng),
     map: map2,
	 icon: markerImage
    }); 
       if(agent == "iphone") {
          var iconSize = new google.maps.Size(16,19);
       } else {
          iconSize = null;
       }
        if(agent == "default") {
            google.maps.event.addListener(marker, "mouseover", function() {
              this.old_ZIndex = this.getZIndex();
              this.setZIndex(9999);
              $("#marker"+orgcount).css("display", "inline");
              $("#marker"+orgcount).css("z-index", "99999");
            });
        }	  
   google.maps.event.addListener(marker, 'click', function(){
	 infowindow.setContent( "<div class='marker_title'><a target='_blank' href='event/"+orgsurl+"'>"+orgtitle+"</a></div>"
	  +"<div class='marker_address'>"+String(contentString)+"</div>"
	 ); 
     infowindow.open(map2,marker);
   });
   bounds.extend(marker.position);
 } 
 
var locations = [<?php echo '"'.implode('","', $addressArray).'"';?>];
var org_title = [<?php echo '"'.implode('","', $orgTitleArray).'"';?>];
var org_surl = [<?php echo '"'.implode('","', $org_surlArray).'"';?>]; 
  var nextAddress = 0;
  function thesNext(){
    if (nextAddress < locations.length) {
      setTimeout('geocodeAddress("'+locations[nextAddress]+'","'+org_title[nextAddress]+'","'+org_surl[nextAddress]+'","'+nextAddress+'",thesNext)', delay);
      nextAddress++;
    } else {
      map2.fitBounds(bounds);
    }
  }
 thesNext();  
 
@else
	
 function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(21.0000, 78.0000),
    zoom:2,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("search_map"),mapProp);
 }
  google.maps.event.addDomListener(window, 'load', initialize);
  
@endif	
</script> 
@stop