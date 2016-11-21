
@section('styles')    
@stop
@section('slider')
{!!HTML::style("assets/public/default2/css/component.css")!!}
 {!!HTML::script("assets/public/default2/js/event/itc-js.js")!!} 
 <?php $tok = csrf_token(); ?>
<div class="container-fluid banner">
 <div class="container text-center">
    <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1 banner-text">
      <p class="wel-text" data-uk-scrollspy="{cls:'uk-animation-slide-top', repeat:true, delay:800}"> WELCOME TO </p>
      <h1 data-uk-scrollspy="{cls:'uk-animation-slide-left', repeat:true,}"> DiscoverYour<span class="blue-col">Event<span></h1>
      <p data-uk-scrollspy="{cls:'uk-animation-scale-down', repeat:true, delay:500}"> Discover Your Events Ipsum is simple event database for your area</p>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="slider-form">
        <form class="form-inline" method="post" action="{!!URL('search')!!}">
          <div class="col-lg-3 col-sm-3 col-xs-12">
            <div class="form-group sel">
              <select class="selectpicker" data-live-search="true" name="category[]" multiple>
			           <option value="all" selected="selected">All Categories</option>
        				  @if(!empty($evtsdata))
        				   @foreach($evtsdata as $etdt)	   
        				    <option value="{!! $etdt !!}">{!! $etdt !!}</option>
        				   @endforeach	
        				  @endif
              </select>
            </div>
          </div>
          <div class="col-lg-3 col-sm-3 col-xs-12">
            <div class="form-group sel">
			 <input type="text" name="city" value="{{ old('city') }}" placeholder="City or Postal Code" class="form-control">
            </div>
          </div>
          <div class="col-lg-3 col-sm-3 col-xs-12">
            <div class="form-group sel">
              <select class="selectpicker" name="eventday" title="Event Day(s)">
				<option value="">Select Day</option> 
                <option value="today">Today</option> 
                <option value="tomorrow">Tomorrow</option>
                <option value="week">This Week</option>
                <option value="weekend">This Weekend</option>
                <option value="month">This Month</option>
              </select>
            </div>
          </div>
          <div class="form-group col-lg-3 col-sm-3 col-xs-12">
            <input type="text" name="optional_all" placeholder="Event or Venue Name" value="{{ old('optional_all') }}" class="form-control">
          </div>
		  	<input type="hidden" name="_token" id="_token" value="{!! $tok !!}">	
		   <div class="form-group col-lg-12 col-sm-12 col-xs-12">
			<input type="submit" class="btn discover-btn" value="Discover Your Events"/>
		  </div>
        </form>
      </div>
    </div>
 </div>
</div>
    <div class="container">	
	 <div class="main" id="ipshe">
   <!-- Slideshow -->
		<ul id="cbp-bislideshow" class="cbp-bislideshow">		
		 <li><img id="default-1" src="{!!URL::to('uploads/city_photos/ca_ab.jpg')!!}"></li>	
         <li><img id="default-2" src="{!!URL::to('uploads/city_photos/ca_on_ottawa.jpg')!!}"></li>		
         <li><img id="default-3" src="{!!URL::to('uploads/city_photos/ca_on_toronto.jpg')!!}"></li>	
         <li><img id="default-4" src="{!!URL::to('uploads/city_photos/us_ca_la.jpg')!!}"></li>	
         <li><img id="default-5" src="{!!URL::to('uploads/city_photos/us_ct.jpg')!!}"></li>	
         <li><img id="default-6" src="{!!URL::to('uploads/city_photos/us_nv_vegas.jpg')!!}"></li>	 
		</ul>
    <!-- End of Slideshow -->
	 </div> 
	</div>
@stop
@section('content')
<section class="featured-events">
  <div class="container text-center">
    <h2 data-uk-scrollspy="{cls:'uk-animation-scale-down', repeat:true, delay:600}"> Featured Events <span id="geo-cs" style="display:none;">In</span> 
	 <a href="javascript:void(0)" data-target="#change_location" data-toggle="modal"><span class="your-city" id="current_location"></span></a>
	</h2>   
  <div class="all-features" id="all_event">   
    @if(!empty($al_event[0]->id))
	    @foreach($al_event as $aet) 
        <div class="col-lg-3 col-sm-4 col-xs-12 ftr">
          <div class="box">
	          <a href="{!!URL('event/'.$aet->event_url)!!}" class="delegation-ajax more-det" data-tipped-options="ajax:{data:{ event: '{!! $aet->event_url !!}', _token: '{!! $tok !!}' } }"> 
            @if(!empty($aet->event_image))	
              @if(is_numeric($aet->event_image)) 	 

              <?php 
                $evtoimg = DB::table('event_catimages')->where('id', '=', $aet->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); 
              ?>	
              
                @if(!empty($evtoimg[0]->id))	
                  <img src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}" class="img-responsive"/>
                @endif 
              
              @else			 
	             <img src="{!!URL::to('uploads/events/'.$aet->account_type.'/'.$aet->event_image)!!}" class="img-responsive"/>
              @endif 
            @else
	            <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"/> 	  
            @endif

<div class="overbox">
  <div class="title overtext">{!! ucfirst($aet->event_name) !!}</div>
    <div class="tagline overtext">
      <p> 
      <?php 
		    if(!empty($aet->event_date) && $aet->event_dtype == 'single'){
		      $aev_date = date('M d, Y', strtotime($aet->event_date));
		    } else if($aet->event_dtype == 'multi'){ 		
		      $getmdate = DB::table('event_multidate')->where('ev_id', '=', $aet->id)->select('id','start_date')->get(); 
		      $aev_date = date('M d, Y', strtotime($getmdate[0]->start_date)); 
		    } else {
		      $aev_date = 'n/a'; 
		    }				 

				if(!empty($aet->country)){
          if(is_numeric($aet->country)){ 						 
  			    $getvcy = DB::table('countries')->where('id', '=', $aet->country)->select('name')->get(); 
            $countryn = '/ '.$getvcy[0]->name;	
       	  } else {
				    $countryn = null;	 	  
				  }	

				 } else {
				  $countryn = null;	 
				 }
				 
				?>
				
			   {!! $aev_date !!}  <br>
                {!! $aet->event_venue !!} {!! $countryn !!}  </p>				
              <div class="price"> 
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
		  </strong>
		 </div>
	<div class="more-det">More Details</div>   
  </div>
          </div>
		  </a>
        </div>
       </div>
	  @endforeach
	 @else 
	  <div class="col-lg-3 col-sm-4 col-xs-12 ftr">
        <div class="box">
		Empty! not found 
	   </div> 
     </div> 	   
     @endif 	 
    </div>
  <div id="loading-loader"></div> 
 <input type="hidden" id="cpage" value="{!! $al_event->currentPage() !!}" />
 <input type="hidden" id="max_page" value="{!! $al_event->lastPage() !!}" />
  </div>
  </div>
  </div>  
   <!--change-location-popup--> 
  <div class="modal fade" id="change_location">
   <div class="modal-dialog">
	<div class="modal-content">
	 <div class="modal-header">			
	  <button type="button" class="close" id="loc_pp" data-dismiss="modal">&times;</button>
	  <h4>Change the Feature event location</h4>
	 </div>
	<div class="modal-body">
       <div class="evnt-pic col-md-12 col-ms-12 col-xs-12">
	    <div class="col-md-6 col-ms-6 col-xs-6">
         <div class="home-eventcss"><b>Country</b></div>
  <?php $giocount = DB::table('countries')->select('id','name')->get(); ?>	 
	<select id="country" name="country" title="Country">
	 @foreach($giocount as $gocy)
	  @if($gocy->id == '13')
		<option value="{!!$gocy->id!!}" selected>{!!$gocy->name!!}</option>  
	  @else
		<option value="{!!$gocy->id!!}">{!!$gocy->name!!}</option>  
      @endif		  
     @endforeach	 
	</select>
	    </div> 
		<div class="col-md-6 col-ms-6 col-xs-6">
		 <div class="home-eventcss"><b>State/City</b></div>
  <?php $giosts = DB::table('states')->where('country_id', '=', '13')->select('id','name')->get(); ?>			 
	<select id="state" name="state" title="State/Province">
	 @foreach($giosts as $gise)
	  <option value="{!!$gise->id!!}">{!!$gise->name!!}</option>		 
	 @endforeach 
	</select> 
		</div>	
	   </div>
	</div>
	 <div class="modal-footer">
	 <div class="col-md-6 col-ms-6 col-xs-6">
	  <input type="button" id="geo_location" class="btn b-btn" name="loc_reload" value="Change"/>
	 </div>
	 <div class="col-md-6 col-ms-6 col-xs-6">
	  <input type="reset" id="geo_lrest" class="btn b-btn" value="Reset"/> 
	 </div> 
	 </div>

	</div>
   </div>
  </div>  
</section>
 {!!HTML::script("assets/public/default2/js/modernizr.custom.js")!!}
 {!!HTML::script("assets/public/default2/js/jquery.imagesloaded.min.js")!!} 
 {!!HTML::script("assets/public/default2/js/cbpBGSlideshow.min.js")!!}
@stop
@section('scripts')
<script>
 $(function() {
  cbpBGSlideshow.init(); 	
 });
</script>
<script>
  var outerPane = $('#all_event'), didScroll = false;
 $(window).scroll(function(){ 
  didScroll = true;
 }); 
setInterval(function(){
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
       $('#end_of_page').hide();
    } else {
      $('#end_of_page').fadeIn();
    }	 
 }
 
 function getPosts(npage){
    $.ajax({
        type: "GET",
        url: '{!!URL("evnt/get-events?page=")!!}'+npage,       
        beforeSend: function(){ 
          $('#loading-loader').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive" style="width:65px"/>');
        },
        complete: function(){
         $('#loading-loader').html(' ');
        },
        success: function(html) {
         $('#all_event').append(html);
        }
     });
 }
 
  var vx = document.getElementById("current_location");
  function getLocation() {
  	if (navigator.geolocation) { 
	    navigator.geolocation.getCurrentPosition(showAddress);
	  } else {
      $("#geo-cs").show(); 		
	    vx.innerHTML = "Australia";
	  }
  }

  getLocation();
  
function showAddress(position) {
	console.log("showAddress.-1");
	var lat_long = position.coords.latitude+','+position.coords.longitude;
	var tken = jQuery( "input#_token" ).val();
	jQuery('#current_location').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" style="width:30px"/>');
	var postdata = {curlocation: lat_long, _token: tken};
	var datapst = jQuery.post( '{!!URL("clocation")!!}', postdata );
	
  datapst.done(function( data ){
    var ecy = data.split('~');
	  $("#geo-cs").show(); 
		  jQuery("#current_location").html(ecy[0]);
      if(ecy[1]){
		    var scurl = ecy[1].replace(/ /g, "_");
	      $('#all_event').load('{!!URL("ghome-ecity/'+scurl+'")!!}'); 
	    }		       			       
	 });		
     $.ajax({
       type: "GET",
       url: '{!!URL("ghome-slide/'+lat_long+'")!!}',
       beforeSend: function(){ },
       complete: function(){ },       	
       success: function(data) { 
	    if(data){
		 $("#ipshe").html('<ul id="cbp-bislideshow" class="cbp-bislideshow">'+data+'</ul>');
		 
	var $slideshow = $( '#cbp-bislideshow' ),
	  $items = $slideshow.children( 'li' ),
	  itemsCount = $items.length,
	  $controls = $( '#cbp-bicontrols' ),
	  navigation = {
	   $navPrev : $controls.find( 'span.cbp-biprev' ),
	   $navNext : $controls.find( 'span.cbp-binext' ),
	   $navPlayPause : $controls.find( 'span.cbp-bipause' )
	  },
      current = 0,
      slideshowtime,
     isSlideshowActive = true,
     interval = 6000;
   function dyeinit( config ) {
    $slideshow.imagesLoaded( function() {   
	 if( Modernizr.backgroundsize ) {
		$items.each( function() {
		 var $item = $( this );
		 $item.css( 'background-image', 'url(' + $item.find( 'img' ).attr( 'src' ) + ')' );
		} );
	 } else {
      $slideshow.find( 'img' ).show();
     }
      $items.eq( current ).css( 'opacity', 1 );
      initEvents();
     startSlideshow();
    });  
   }
  function initEvents() {
   navigation.$navPlayPause.on( 'click', function() {
    var $control = $( this );
    if( $control.hasClass( 'cbp-biplay' ) ) {
     $control.removeClass( 'cbp-biplay' ).addClass( 'cbp-bipause' );
     startSlideshow();
    } else {
      $control.removeClass( 'cbp-bipause' ).addClass( 'cbp-biplay' );
     stopSlideshow();
    }
   });
    navigation.$navPrev.on( 'click', function() { 
     navigate( 'prev' ); 
     if( isSlideshowActive ) { 
      startSlideshow(); 
     }  
    });
   navigation.$navNext.on( 'click', function() { 
    navigate( 'next' ); 
     if( isSlideshowActive ) { 
      startSlideshow(); 
     }
   });
  }
 function navigate( direction ) {
  var $oldItem = $items.eq( current );
  if( direction === 'next' ) {
   current = current < itemsCount - 1 ? ++current : 0;
  } else if( direction === 'prev' ) {
   current = current > 0 ? --current : itemsCount - 1;
  }
   var $newItem = $items.eq( current );
   $oldItem.css( 'opacity', 0 );
   $newItem.css( 'opacity', 1 );
 }
 function startSlideshow() {
  isSlideshowActive = true;
  clearTimeout( slideshowtime );
  slideshowtime = setTimeout( function() {
   navigate( 'next' );
   startSlideshow();
  }, interval );
 }
 function stopSlideshow() {
  isSlideshowActive = false;
  clearTimeout( slideshowtime );
 }
   setTimeout(dyeinit(), 700);		   
 }     
 }
 });   
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
        window.location = '{!!URL("/")!!}';
      }, 500); 
	 } else if(data == 'not-log') {
		 <?php //session(['persignup' => 'lnot-saved']); ?>
	  window.location = '{!!URL("createPage/lnot-saved")!!}';
	 } else {
	  window.location = '{!!URL("/")!!}'; 
	 }        
    }
   });
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

 $("#geo_location").click(function(){ 
  var vcycode = $("select#country").val();
  var vscode = $("select#state").val();	
  var vcytext = $("#country option:selected").text();
  var vstext = $("#state option:selected").text();	
   if(vcycode){
	$('#all_event').load('{!!URL("ghome-ecity/location")!!}/'+vcycode+'/'+vscode);   
	$("#geo-cs").show(); 
    $('#current_location').html(vstext);	 
    $( "#loc_pp" ).trigger( "click" );	 
   }
  });	

  $("#geo_lrest").click(function(){ 
	$('#all_event').load('{!!URL("ghome-ecity/location")!!}/y/n'); 
    $("#geo-cs").hide(); 	
    $('#current_location').html(' ');	
     setTimeout(function () {
        $( "#loc_pp" ).trigger( "click" );
      }, 500); 	    
  });
});	

 function followEvent(eid){	 
  //jQuery("#error-lreq").html('Login required');   
   window.location = '{!!URL("createPage/fapb")!!}';
 }
 </script>
@stop