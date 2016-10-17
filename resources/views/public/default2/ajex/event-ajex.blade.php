<div class="full pop-design">
  <a href="javascript:void(0)"><img src="{!!URL::to('assets/public/default2/images/map.jpg')!!}" class="img-responsive"></a>
        <!-- <span class="col-md-12 full-success alert" id="full-success" style="display:none">
              <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
              <strong>Success!</strong> <span id="es-mess"></span>
             </span> -->			
			 <div class="col-md-4 col-sm-4 text-center round-pic">
			@if(!empty($event_data[0]->event_image))
	  @if(is_numeric($event_data[0]->event_image)) 	 
	   <?php $evtoimg = DB::table('event_catimages')->where('id', '=', $event_data[0]->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	
		@if(!empty($evtoimg[0]->id))	
		 <img src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}" class="img-responsive"/>
		@endif 
	  @else 				
		<img src="{!!URL::to('uploads/events/'.$event_data[0]->account_type.'/'.$event_data[0]->event_image)!!}"/>	
	  @endif 	 
            @else
			 <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}"/>
            @endif
		<!--  @if(!empty($your_event[0]->e_id))	
            <a onclick="unattend({!! $your_event[0]->e_id !!})" href="javascript:void(0)" class="save-event">Unattend</a>		
          @else
		    <a onclick="saveEvent('{!! $event_data[0]->event_url !!}')" href="javascript:void(0)" class="save-event">Attend</a> 
          @endif -->
         </div>
		 <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}"/>				
	     <div class="event-detail col-md-8 col-sm-8">
           <h3>
			<a href="{!!URL('event/'.$event_data[0]->event_url)!!}" target="_blank">
				{!! ucfirst($event_data[0]->event_name) !!}
			</a>
		   </h3>	
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
						 $getmdate = DB::table('event_multidate')->where('ev_id', '=', $event_data[0]->id)->select('id','start_date','start_time')->get(); 
						$ev_date = date('M d, Y', strtotime($getmdate[0]->start_date)); 
						$event_time = $getmdate[0]->start_time;
					   } else {
 						$event_time = $event_data[0]->event_time;
						$ev_date = 'n/a'; 
					   }
					?>
                    <p>Date:  {!! $ev_date !!} @ {!! $event_time !!}<br>
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
 Attending People: <font color="#0c7ab3"> {{ $attending_people }}</font>
 <font style="color:#0c7ab3; float:right; margin-right:15px;">			  
  <?php if(!empty($clid)){
	$ur_event = DB::table('users_events')->where('e_id', '=', $event_data[0]->eid)->where('u_id', '=', $clid)->select('e_id')->get(); ?>
   @if(!empty($ur_event[0]->e_id))
    <a href="javascript:void(0)" onclick="unattend('{!!$ur_event[0]->e_id!!}')"><u>Unattend</u></a>   
   @else
	<a href="javascript:void(0)" onclick="saveEvent('{!!$event_data[0]->event_url!!}')"><u>Attend</u></a>
   @endif 
  <?php } ?> 
 </font>  
</p>				  
					  
                    <br>
                    <div class="des">
                      <p class="text"><strong>Description:</strong><br>
                      </p>
                      <p class="text1">{!! $event_data[0]->event_description !!}</p>
                    </div>                   
  </div>
    <div class="gry-bg">   
  
	<a href="" class="btn-gr"> Like </a> <a href="" class="btn-gr"> Fllowing </a> <a href="" class="btn-gr"> Like </a>
	
   </div>
</div>