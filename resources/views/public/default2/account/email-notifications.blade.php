@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
 <section class="sec"> 
      <div class="tabbable tabs-left">
      @include("public/default2/_layouts._AccountMenu")
        <div class="tab-content col-lg-8 col-sm-8 col-xs-12 ">
         <div class="tab-pane active">		 
		 <div class="form-group">		 
		 <h3 class="mr-botom25"> Email Notifications </h3>		 
		  @if(isset($succ_mesg))
           <span class="col-md-12 full-success alert">
            <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
            <strong>Success!</strong> {!!$succ_mesg!!}
           </span>			
          @endif
		<form class="form-inline" method="post" enctype="multipart/form-data"> 
			  <!-- <img src="{!!URL::to('assets/public/default2/images/coming-soon.jpg')!!}"/>  -->	
        <?php 
		  $entfs = $getmn_data[0]->enotification_status; 
		  $eatt = $getmn_data[0]->event_attend;
		  $ynfp = $getmn_data[0]->yfollow_page;
		  $ourntf = $getmn_data[0]->dye_notf;
		 ?>			  
			  <div class="col-md-12 col-sm-12 col-xs-12 border-btm">
			  <div class="on-off-main col-md-1"> 
			
				<div class="on-off"> <strong>On / Off</strong> </div>
			  </div>
			  <div class="main-notifaction"> 
				<strong> Notification </strong>
			  </div>
			  </div>
			  
			 <div class="col-md-12 col-sm-12 col-xs-12 padd-n">
			  <div class="on-off-main col-md-1"> 
				<div class="check-email">
				<?php if(($entfs == 'y' && $eatt == 'y') && ($ourntf == 'y' && $ynfp == 'y')){ ?>
					<input type="checkbox" value="y" name="allEnable" class="allnEnable" checked /> 
				<?php } else { ?>
					<input type="checkbox" value="y" name="allEnable" class="allnEnable"/> 
				<?php } ?>				
				</div>
			  </div>

			  <div class="email-notifaction "> 
					Enable all email notification.
			  </div>
			 </div>
			  
			    <div class="col-md-12 col-sm-12 col-xs-12 padd-n">
			  <div class="on-off-main col-md-1"> 
				<div class="check-email"> 
				<input type="checkbox" value="y" name="enotification_status" class="ntfcheck" @if($entfs == 'y') checked @endif /> 				
				</div>
			  </div>
			  <div class="email-notifaction "> 
					Receive Email Event Notification.
			  </div>
			  </div>
			  
			   <div class="col-md-12 col-sm-12 col-xs-12 padd-n">
			  <div class="on-off-main col-md-1"> 
				<div class="check-email"> 
				<input type="checkbox" value="y" name="event_attend" class="ntfcheck" @if($eatt == 'y') checked @endif/> 
				</div>
			  </div>
			  <div class="email-notifaction "> 
				Receive email notifications when users attend/unattend your event, email notification.
			  </div>
			  </div>
			<div class="col-md-12 col-sm-12 col-xs-12 padd-n">
			  <div class="on-off-main col-md-1"> 
				<div class="check-email"> 
				<input type="checkbox" value="y" name="dye_notf" class="ntfcheck" @if($ourntf == 'y') checked @endif />
				</div>
			  </div>
			  <div class="email-notifaction ">  
					Receive email notifications on site updates & features.
			  </div>
			  </div>
			  
			<div class="col-md-12 col-sm-12 col-xs-12 padd-n">
			  <div class="on-off-main col-md-1"> 
				<div class="check-email"> 
				<input type="checkbox" value="y" name="yfollow_page" class="ntfcheck" @if($ynfp == 'y') checked @endif /> 
				</div>
			  </div>

			  <div class="email-notifaction "> 
					Receive email notifications on event from pages you follow.
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
 <Script>
	$('body').delegate(".allnEnable", 'click', function(){		
		if ($('input.allnEnable').is(':checked')) {
			$('input.ntfcheck').prop('checked', true);
		} else {;
			$('input.ntfcheck').prop('checked', false);
		}
	});
 </script>
@stop