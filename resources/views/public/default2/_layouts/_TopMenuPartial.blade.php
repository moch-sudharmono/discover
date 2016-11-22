 <ul>
  @if($vs == 'yes')
    <li>
	 @if(!empty($sel_accname))
	  <a href="{!!URL($sel_accname)!!}">Home</a>
     @else
	  <a href="{!!URL('/')!!}">Home</a>
	 @endif	 
	</li>		
	<li class="menu-events active" id="notification" onClick="glbRead()">
		<a href="javascript:void(0)" class="btn dropdown-toggle" data-toggle="dropdown">
		<span class="ntf"> Notifications </span><span class="notif" id="up-notif">({!!$wanotfresult!!})</span></a>

		<input type="hidden" id="cup-notif" value="{!!$wanotfresult!!}"/>
		 <ul class="dropdown-menu" id="notf-list">
 <?php 
  if(!empty($wanotfdata[0]->id)){ 	  
   foreach($wanotfdata as $nsd){
	if($nsd->global_read == 1){
	  $rd_stats = 'notif-acc'; 
    } else {
	  $rd_stats = 'notif-acc active';
    } 
    if($nsd->type == 'event'){		 
	 $evntlu = DB::table('events')->where('id', '=', $nsd->object_id)->select('event_url')->get(); 
?>
  <li id="notf-{!!$nsd->id!!}">
	<div class="{!!$rd_stats!!}">
	  <a href="{!!URL('/event/'.$evntlu[0]->event_url)!!}">{!!$nsd->subject!!}</a>
	  <a class="notf-close" href="javascript:void(0)" onClick="sRead({!!$nsd->id!!});"><span class="fa fa-times"></span></a>
	</div>
  </li> 
<?php } else if($nsd->type == 'page'){ 
 $evntlu = DB::table('account_details')->where('id', '=', $nsd->object_id)->select('account_url')->get(); 
?>
	<li id="notf-{!!$nsd->id!!}">
	 <div class="{!!$rd_stats!!}">
	  <a href="{!!URL('/'.$evntlu[0]->account_url)!!}">{!!$nsd->subject!!}</a>
	  <a class="notf-close" href="javascript:void(0)" onClick="sRead({!!$nsd->id!!});"><span class="fa fa-times"></span></a>
	 </div>
	</li> 
<?php } else { ?>
    <li id="notf-{!!$nsd->id!!}">
	 <div class="{!!$rd_stats!!}">
	  <a href="#">{!!$nsd->subject!!}</a>
	  <a class="notf-close" href="javascript:void(0)" onClick="sRead({!!$nsd->id!!});"><span class="fa fa-times"></span></a>
	 </div>
	</li> 				
<?php }}} ?>
 @if($allntfCount > 5)
   <li><div class="all-notifications"><a href="{!!URL('all/notifications')!!}">See All</a></div></li>	
 @endif	 
		 </ul>
		</li>

    <li class="menu-eventss active" id="myDropdown"> <a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)"> Menu <i class="fa fa-bars"> </i></a> 
       <ul class="dropdown-menu">
				  		
        @if(!empty($sel_accname))
			<li><a href="{!!URL($sel_accname.'/account/createEvent')!!}">Create Event</a></li>
            <li><a href="{!!URL($sel_accname.'/account/manageEvent')!!}">Manage Events</a></li>
            <li><a href="{!!URL($sel_accname.'/account')!!}">Account</a></li>
         @else					 
            <li><a href="{!!URL('account/createEvent')!!}">Create Event</a></li>
            <li><a href="{!!URL('account/manageEvent')!!}">Manage Events</a></li>
            <li><a href="{!!URL('account')!!}">Account</a></li>
		@endif	
            <li class="help-bor"><a href="{!!URL('help')!!}">Help</a></li>
	   <li>				
		<div class="switch_menu">
		 <i>Switch Account to:</i> 
		<ul>
		@if(sizeof($an_dye) > 0)					
		 @foreach($an_dye as $menu_andye)
           <?php 
			if($menu_andye->g_id == 2) {
			 $img_apath = 'business';
			} elseif($menu_andye->g_id == 5) { 
			 $img_apath = 'municipality';
            } else {
			 $img_apath = 'club';
			}
			 $mvsac_murl = $menu_andye->account_url;						   
			 if(!empty($sel_accname)){
			  if($sel_accname == $mvsac_murl){ ?>
			   <li><a href="{!!URL('/')!!}">
			    @if(!empty($user_photo))
				 <img src="{!!URL::to('uploads/user_profile/'.$user_photo)!!}" class="img-responsive menu-img"/>   
				@else
				 <img src="{!!URL::to('assets/public/default2/images/blue-icon.png')!!}" class="img-responsive menu-img"/>   
                @endif						   
				{{$bcuname}}</a> </li>	
			<?php } else { ?>
			  <li><a href="{!!URL($mvsac_murl)!!}">
			 @if(!empty($menu_andye->upload_file))	
		<img src="{!!URL::to('uploads/account_type/'.$img_apath.'/'.$menu_andye->upload_file)!!}" class="img-responsive menu-img"/>
			 @else
		<img src="{!!URL::to('assets/public/default2/images/fox-logo.png')!!}" class="img-responsive menu-img"/>		
             @endif						
			{{$menu_andye->name}}</a> </li>	
		  <?php } } else { ?>
				<li>
					<a href="{!!URL($mvsac_murl)!!}">
					
					   @if(!empty($menu_andye->upload_file))	
							<img src="{!!URL::to('uploads/account_type/'.$img_apath.'/'.$menu_andye->upload_file)!!}" class="img-responsive menu-img"/>
					   @else
							<img src="{!!URL::to('assets/public/default2/images/fox-logo.png')!!}" class="img-responsive menu-img"/>		
					   @endif
					   
					{{$menu_andye->name}}
					</a>
				
				</li>	
			<?php } ?>	
		 @endforeach
        @endif
		     </ul>
			 </div>
			</li>		
				<li><a href="{!!URL('createPage')!!}">Create a Page</a></li>	
         <li class="home-login"><a href="{!!URL('logout')!!}">Logout</a></li>
       </ul>
    </li>
  @else
    <li class="" data-toggle="modal" data-target="#login-pop-up"><a href="#">LogIn</a></li>
	
	<!-- Login form pop_up -->
	<div id="login-pop-up" class="modal fade" role="dialog" data-backdrop="static">
	  <div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content sign_up-form">
			  	<div class="modal-header text-center">
					<button type="button" class="close pup-close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title">WELCOME TO </h3>
					<a href="{!!URL('/')!!}">
				 	<img src="{!!URL::to('assets/public/default2/images/logo-pop-up.png')!!}" class="img-responsive">
					</a>
			    </div>
		 	    <div class="col-md-12" id="error-lpopall"></div>
					<div class="modal-body">
						<form class="form-inline" id="signinevent" method="post">
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
							<input type="email" name="email" placeholder="Email Address" class="form-control sgmail">
							<div class="help-block with-errors" id="error-sgmail"></div>
						  </div>
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
							<input type="password" name="password" placeholder="Password" class="form-control sgpass">
							<div class="help-block with-errors" id="error-sgpass"></div>
						  </div>
						   	<div class="col-lg-8 col-sm-6 col-xs-6 rem-text">
							 <input id="option" type="checkbox" name="remember" value="checked">Remember Me</div>
						   <div class="col-lg-4 col-sm-6 col-xs-6 rem-text"><a href="{!!URL('forgot_password')!!}">Forgot Password?</a></div>
						  <input type="hidden" name="_token" value="{{ csrf_token() }}">	
						   <div class="sign-up">
							 <input type="submit" id="signin" value="LogIn" class="btn discover-btn">
						  </div>						 
						    <div class="col-lg-12 col-sm-12 col-xs-12 rem-text text-center" data-dismiss="modal" data-toggle="modal" data-target="#signup-popup">
							<a href="javascript:void(0)" id="popup-nsup">Not Signed Up?</a>
							</div>
						</form>
					  </div>
					</div>

				  </div>
				</div>
				<!-- Login form pop_up -->
				
                <li class="" data-toggle="modal" data-target="#signup-popup"><a href="#">SignUp</a></li>
				<!-- Signup form pop_up -->
				<div id="signup-popup" class="modal fade" role="dialog" data-backdrop="static">
				  <div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content sign_up-form">
					  <div class="modal-header text-center">
						<button type="button" class="close pup-close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title">WELCOME TO </h3>
						<a href="{!!URL('/')!!}">
						 <img src="{!!URL::to('assets/public/default2/images/logo-pop-up.png')!!}" class="img-responsive">
						</a>
					  </div>
					  <div class="col-md-12" id="error-popall"></div>
					  <div class="modal-body">
						<form class="form-inline" id="signupevent" method="post">
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
							<input type="text" name="full_name" placeholder="Full Name" id="pfullname" class="form-control">
							<div class="help-block with-errors" id="error-pfullname"></div>
						  </div>
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
							<input type="email" name="email" placeholder="Email Address " id="" class="form-control signemail">
							<div class="help-block with-errors" id="error-signemail"></div>
						  </div>
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
							<input type="password" name="password" placeholder="Password" id="" class="form-control signpass">
							<div class="help-block with-errors" id="error-signpass"></div>
						  </div>
						   <div class="sign-up">
							 <input type="submit" value="Sign Up" id="sigupevent" class="btn discover-btn">
						  </div>
					<div class="col-lg-12 col-sm-12 col-xs-12 rem-text text-center all" data-dismiss="modal" data-toggle="modal" data-target="#login-pop-up">
					 <a href="javascript:void(0)">Already Signed Up?</a>
					</div>	
						   <div class="col-lg-12 col-sm-12 col-xs-12 business-muni">
							<strong><i>Looking for a Business, Municipality or Club/Organization account?</i></strong>
								<input type="hidden" name="_token" value="{{ csrf_token() }}">		
				<a class="btn get-started" href="{{url('createPage/gsh')}}" data-toggle="tooltip" data-placement="top" title="FYI… You’ll need a personal account first!">Get Started Here </a>
						  </div>				  
						</form>
					  </div>
					</div>
				  </div>
				</div>
				<!-- Signup form pop_up -->
                <li><a href="{{url('help')}}">Help</a></li>
    <li class="create-event"><a href="{{url('createPage/cye')}}">Create Your Event</a></li>
  @endif				
 </ul>	
 <script> 
 $('#myDropdown .dropdown-menu').on({
	"click":function(e){
      e.stopPropagation();
    }
});
 </script>
 
  <script> 
 $('#notification .dropdown-menu').on({
	"click":function(e){
      e.stopPropagation();
    }
});
 </script> <!------
 {!! Services\MenuManager::generate('public-top-menu', '', 'dropdown', 'dropdown-menu', '', 'dropdown-submenu') !!}
---->