@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
 <section class="sec">
      <div class="tabbable tabs-left">
       @include("public/default2/_layouts._AccountMenu")
        <div class="tab-content">
         <div class="tab-pane active">	
   <?php 
	   if(isset($event_details[0]->g_id)){
	    if($event_details[0]->g_id == 2) {
		  $tacc_name = 'Business';
		} elseif($event_details[0]->g_id == 5) {
		  $tacc_name = 'Municipality';
        } else {
		  $tacc_name = 'Club or Organization';
		}
	   }
   ?>		 
  <div class="col-lg-12 col-sm-12 col-xs-12 account-psection">		 
	<b>Account Type:</b> {!!$tacc_name!!} <b>Account Name:</b> {{ $event_details[0]->name }}
  </div>
		 <h3> Email Notifications </h3>
		  @if(isset($succ_mesg))
           <span class="col-md-12 full-success alert">
            <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
            <strong>Success!</strong> {!!$succ_mesg!!}
           </span>			
          @endif
		<form class="form-inline" method="post" enctype="multipart/form-data"> 
         <?php $flemail_update = $event_details[0]->flemail_update; ?>			  
			 <div class="col-md-12 col-sm-12 col-xs-12 border-btm">
			  <div class="on-off-main col-md-1"> 			
				<div class="on-off"> <strong>On / Off</strong> </div>
			  </div>
			  <div class="main-notifaction"> 
				<strong> Notifaction </strong>
			  </div>
			 </div>
			  
			<div class="col-md-12 col-sm-12 col-xs-12 padd-n">
			  <div class="on-off-main col-md-1"> 
			   <div class="check-email"> 
				<input type="checkbox" value="y" name="flemail_update" class="ntfcheck" @if($flemail_update == 'y') checked @endif /> 
			   </div>
			  </div>
			  <div class="email-notifaction"> 
			  Receive email about page notifications for <b>{!!$event_details[0]->name!!}</b> account.
			  </div>			  
			</div>
			   <input type="hidden" name="_token" id="_token" value="{!!csrf_token()!!}">	
	<div class="form-group col-lg-6 col-sm-12 col-md-6 col-xs-12">
      <input class="form-control follow update-btn" type="submit" value="Update Setting">
    </div>
		</form>		
	 </div>   
   </div>      
  </div>
 </div>
      <!-- /tabs -->
    <!-- /left nav bar end --> 
</section>
@stop
@section('scripts')
@stop