@if(!empty($al_event[0]->id))
 @if(sizeof($al_event) > 1)
 <?php for ($vsx = 0; $vsx < 2; $vsx++) { 
  $getfvcy = DB::table('countries')->where('id', '=', $al_event[$vsx]->country)->select('id','name')->get(); 
 ?> 
 <div class="rock-event">
  <div class="evnt-pic col-md-5 col-ms-5 col-xs-12"> 
   @if(!empty($al_event[$vsx]->event_image))
	@if(is_numeric($al_event[$vsx]->event_image)) 
      <?php $evtoimg = DB::table('event_catimages')->where('id', '=', $al_event[$vsx]->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>
     @if(!empty($evtoimg[0]->id))			   
	   <img src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}" class="img-responsive"/>
     @else
	   <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"/> 	  
	 @endif 
    @else 		
	 <img src="{!!URL::to('uploads/events/'.$al_event[$vsx]->account_type.'/'.$al_event[$vsx]->event_image)!!}" class="img-responsive"/>
    @endif
   @else
	 <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"/> 	  
   @endif		 
  </div>
  <div class="evnt-con col-md-7 col-ms-7 col-xs-12">
   <h4>{!! ucfirst($al_event[$vsx]->event_name) !!}</h4>
    <div class="location"> <span class="location-icon"> </span>{!! $al_event[$vsx]->event_venue !!} 
     @if(!empty($getfvcy[0]->id)) / {!! $getfvcy[0]->name !!} @endif
    </div> 
    <a href="{!!URL('event/'.$al_event[$vsx]->event_url)!!}">
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
   <h4> {!! ucfirst($al_event[0]->event_name) !!} </h4>
  <div class="location"><span class="location-icon"> </span>{!! $al_event[0]->event_venue !!} / {!! $al_event[0]->event_address !!}</div>
   <a href="{!!URL('event/'.$al_event[$vsx]->event_url)!!}">
	<strong>Read More</strong>
   </a> 
  </div>
 </div>
@endif 
@endif	