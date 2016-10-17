@if(!empty($event_data[0]->id))
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"> <a class="accordion-toggle" id="apevt-title" href="#collapseOne" data-parent="#accordion" data-toggle="collapse">{!! $event_data[0]->event_name !!}</a> </h4>
  </div>
<div id="collapseOne" class="panel-collapse in" style="height: auto;">            
 <div class="panel-body"> 
  <div class="dropdown-menu">
  <span class="col-md-12 full-success alert" id="full-success" style="display:none">
              <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
              <strong>Success!</strong> <span id="es-mess"></span>
             </span>
                <div class="col-md-offest-1 col-md-3 col-sm-12 text-center"> 
 @if(!empty($event_data[0]->event_image))
  @if(is_numeric($event_data[0]->event_image)) 	 
 <?php $evtoimg = DB::table('event_catimages')->where('id', '=', $event_data[0]->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	
    @if(!empty($evtoimg[0]->id))	
     <img src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}" class="img-responsive"/>
    @endif 
  @else 
	<img src="{!!URL::to('uploads/events/'.$event_data[0]->account_type.'/'.$event_data[0]->event_image)!!}" class="img-responsive"/>
  @endif 
 @else
  <img src="{!!URL::to('assets/public/default2/images/events-pic1.jpg')!!}" class="img-responsive"/>
 @endif 
				 
                  <div class="social-media">
                    <a href="#"><img src="{!!URL::to('assets/public/default2/images/facebook1.png')!!}"></a>
                   <a href="#"><img src="{!!URL::to('assets/public/default2/images/twitter.png')!!}"></a> 
                   <a href="#"><img src="{!!URL::to('assets/public/default2/images/share.png')!!}"></a> 
                   <a href="#"><img src="{!!URL::to('assets/public/default2/images/share1.png')!!}"></a> 
                  </div>
                </div>
                <div class="col-md-8 col-sm-12 det">
                  <div class="event-detail">
                    <h3><a href="{!!URL('event/'.$event_data[0]->event_url)!!}" target="_blank">{!! ucfirst($event_data[0]->event_name) !!}</a></h3>
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
                    <p>Date: {!! $ev_date !!} @ {!! $event_time !!}<br>
                      Venue: {!! $event_data[0]->event_venue !!}<br>  
                      Address: {!! $event_data[0]->event_address !!}<br>
					Tickets:  
					  @if($event_data[0]->event_cost == 'paid')
					     <?php $alet_price = explode("-", $event_data[0]->event_price); ?>
					    @if(!empty($alet_price[0]))	
						 <?php $alet_prc = '$'.$alet_price[0]; 			
						  for ($x = 1; $x < sizeof($alet_price); $x++) {
						   $alet_prc .= ' - $'.$alet_price[$x];
						  }			 
						 ?> 
						 <font color="#0c7ab3"> {!! $alet_prc !!} <a href="{{$event_data[0]->ticket_surl}}" target="_blank" class="save-event"><u> Buy Tickets</u></a></font>
					    @else
						 <font color="#0c7ab3"> ${!! $event_data[0]->event_price !!} <a href="{{$event_data[0]->ticket_surl}}" target="_blank" class="save-event"><u> Buy Tickets</u></a></font>
					    @endif					
					  @else
					    <font color="#0c7ab3"> {!! ucfirst($event_data[0]->event_cost) !!}  </font>
					  @endif
					   <br>
                      Attending People: <font color="#0c7ab3"> {!! $attending_people !!}</font></p>
                    <br>
                    <div class="des">
                      <p class="text"><strong>Description:</strong><br>
                      </p>
                      <p class="text1">{!! ucfirst($event_data[0]->event_description) !!}</p>
                    </div>
					 @if(!empty($your_event[0]->e_id))	
					  <a onclick="unattend({!! $your_event[0]->e_id !!})" href="javascript:void(0)" class="save-event">Unattend</a>		
					 @else
					  <a onclick="saveEvent('{!! $event_data[0]->event_url !!}')" href="javascript:void(0)" class="save-event">Attend</a>  
					 @endif 
                  </div>
                </div>
      </div>
	</div>	
 </div>
</div>	
@endif