    @if(!empty($al_event[0]->id))
	 @foreach($al_event as $aet) 
 <div class="col-lg-3 col-sm-4 col-xs-12 ftr">
  <div class="box">
   <a href="{!!URL('event/'.$aet->event_url)!!}" class="delegation-ajax more-det" data-tipped-options="ajax:{data:{ event: '{!! $aet->event_url !!}', _token: '{!! csrf_token() !!}' } }" target="_blank">
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
			<img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"/> 	  
		 @endif
          <div class="overbox">
            <div class="title overtext"> {!! ucfirst($aet->event_name) !!} </div>
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
				   $getvcy = DB::table('countries')->where('id', '=', $aet->country)->select('name')->get(); 
                  $countryn = '/ '.$getvcy[0]->name;			   
				} else {
				  $countryn = null;	 
				} 
			?>
			   {!! $aev_date !!}  <br>
             demo   {!! $aet->event_venue !!} {!! $countryn !!}  </p>
              
			  <div class="price event-price home-event">
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
			   </div>
			   
 <div class="more-det">  More Details </div>
	 </div>
    </div>
   </div> 
  </div>
 </div>
	  @endforeach
	 @else 
	  <div class="col-lg-12 col-sm-12 col-xs-12 ftr">
        <div class="box">
		 <h2>Unfortunately we can’t find any events for you. Please adjust your search options</h2>
	   </div> 
     </div> 	   
     @endif 
@if(isset($geo_loc) && $geo_loc == 'geo')
<script>
 $('#cpage').val('{!! $al_event->currentPage() !!}');
 $('#max_page').val('{!! $al_event->lastPage() !!}');
</script>	
@endif 