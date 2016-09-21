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
 <a href="{!!URL('event/'.$aet->event_url)!!}" class="delegation-ajax" data-tipped-options="ajax:{data:{ event: '{!! $aet->event_url !!}', _token: '{{ csrf_token() }}' } }" target="_blank"> 
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
                <div class="price line-47">
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
                <div class="attend">
                  <?php $allevt_ct = DB::table('users_events')->where('e_id', '=', $aet->id)->count(); ?>	
				People Attending : <span class="blue-col"> {!! $allevt_ct !!} </span></div>	
              </div>
            </div>
			@endforeach
		   @else 
			   <div class="rock-event"> Unfortunately we can’t find any events for you. Please adjust your search options </div>  
           @endif 