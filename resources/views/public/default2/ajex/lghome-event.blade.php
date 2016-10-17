<?php
	$priceSymbol = config("app.priceSymbol" , "$");
?>
 @if(!empty($al_event[0]->id))
			@foreach($al_event as $aet) 
            <div class="rock-event all-events">
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
 <a href="{!!URL('event/'.$aet->event_url)!!}" class="delegation-ajax" data-tipped-options="ajax:{data:{ event: '{!! $aet->event_url !!}', _token: '{!! csrf_token() !!}' } }" target="_blank"> 
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
	  $getvcy = DB::table('countries')->where('id', '=', $aet->id)->select('id','name')->get(); 
	  if(!empty($getvcy[0]->id)){ $ev_cuntry = '/ '.$getvcy[0]->name; } else { 	$ev_cuntry = null;  } 
	?>
			   {!! $aev_date !!} @ {!! $event_time !!} 
				</div>
                <div class="location"> <span class="location-icon"> </span>
				 <a href="#getm-{{$wo}}" onclick="chgEventmap('{{$wo}}')">{!! $aet->event_venue !!} {!! $ev_cuntry !!}</a>
				</div>
                <div class="price line-45">		
					<strong>
			
		  @if($aet->event_cost == 'paid')
			  
		   <?php $alet_price = explode("-", $aet->event_price); ?>
		   
			@if(!empty($alet_price[0]))
			 <?php
			  $lower = $alet_price[0];
			  
			  $alet_prc = toMoney( $lower , $priceSymbol);
			  for ($x = 1; $x < sizeof($alet_price); $x++) {
				$alet_prc .= ' - '. toMoney(  $alet_price[$x] , $priceSymbol);
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
					<a href="javascript:void(0)" class="unfollow-event save-event event-attend">Unfollow</a>
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