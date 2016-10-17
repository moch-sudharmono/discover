@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
  <!-- Login And Sign-Up form pop_up -->
  <div class="modal-dialog sign_login">
    <div class="sign_up-form tow-from">
      <div class="modal-header text-center">
        <a href="{!! url('/') !!}"><img src="{!!URL::to('assets/public/default2/images/logo-pop-up.png')!!}" class="img-responsive"></a>
      
	     @if(isset($personal) && $personal == 'gsh')
		<h4 class="modal-title">You must log in or sign up in order to create a new page</h4>
	   @elseif(isset($personal) && $personal == 'cye')
		<h4 class="modal-title">You must log in or sign up in order to Create An Event</h4> 
	   @elseif(isset($personal) && $personal == 'lnot-saved')
		<h4 class="modal-title">You must log in or sign up in order to Save Events</h4> 
       @elseif(isset($personal) && $personal == 'fap')
		<h4 class="modal-title">You must log in or sign up in order to follow a page</h4> 
	   @elseif(isset($personal) && $personal == 'fapb')
		<h4 class="modal-title">You must log in or sign up in order to Follow this page</h4> 
	   @else	   
		<h4 class="modal-title">You must log in or sign up in order to Create Your Event</h4>	
	   @endif
      </div> 
      <h3 class="text-center login_sign-hed"> Login <span class="or"> or </span> Signup </h3>
	  <div class="col-md-12" id="error-all"></div>
	  @if(isset($personal) && $personal == 'done-signup')
		<div class="col-md-12 full-success" id="error-all">You are registered successfully, please log in order to Create Your Event</div>
      @endif	
	    @if(!empty(Session::get('error_message')))
		 <span class="col-md-12 full-error">{{Session::get('error_message')}}</span>
	    @endif
		@if(!empty(Session::get('success_message')))
		 <div class="col-md-12 full-success">{!!Session::get('success_message')!!}</div>
		@endif
		
      <div class="modal-body">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <form class="form-inline log" id="psignin" method="post">
            <div class="col-lg-12 col-sm-12 col-xs-12"> </div>
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <input type="email" placeholder="Email Address" value="{{ old('email') }}" name="email" id="suemail" class="form-control" required />
			   <div class="help-block with-errors" id="error-suemail"></div>
            </div>
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <input type="password" placeholder="Password" name="password" id="lpass" class="form-control" required />
			  <div class="help-block with-errors" id="error-lpass"></div>
            </div>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">	
            <div class="col-lg-6 col-sm-6 col-xs-12 rem-text">
              <input id="option" type="checkbox" name="remember" value="checked">
              Remember Me</div><div class="col-lg-6 col-sm-6 col-xs-12 rem-text" id="forgot-pop-up">
		 <a href="{{url('forgot_password')}}" class="for-get pas">Forgot Password?</a>		   
		</div>
            <div class="sign-up">
                <input type="submit" class="btn discover-btn" value="LogIn"/>
            </div>
          </form>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 sign">
          <form class="form-inline log" id="psignup" method="post">
            <div class="col-lg-12 col-sm-12 col-xs-12"> </div>
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <input type="text" class="form-control" name="full_name" id="full_name" value="{{ old('full_name') }}" placeholder="Full Name" required />
			  <div class="help-block with-errors" id="error-fn"></div>
            </div>
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <input type="email" placeholder="Email Address" name="email" id="slemail" value="{{ old('email') }}" class="form-control" required />
			  <div class="help-block with-errors" id="error-slemail"></div>
            </div>
            <div class="form-group col-lg-12 col-sm-12 col-xs-12">
              <input type="password" placeholder="Password" name="password" id="password" class="form-control" required />
			  <div class="help-block with-errors" id="error-password"></div>
            </div>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">	
            <div class="sign-up">
              <input type="submit" class="btn discover-btn" value="Sign Up"/>
            </div>
          </form>
        </div>      	

      </div>
    </div>
  </div>
<!-- Login form pop_up -->
@stop
@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
 $("#psignin").submit(function(event){
	 event.preventDefault();
	  var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	  var sigpass = $("input#lpass").val();
	  var sgmail = $("input#suemail").val();
  
	 if(sgmail.match(mailformat))
	{
     if(sigpass == '' || sigpass == ' '){
	   $( "#error-lpass" ).html( "Password field required" );	
	  return false;	  
	 } else {
	  var datapst = $.post('{!!URL("login/user")!!}', $('#psignin').serialize());
	 datapst.done(function( data ) {	  
	  if(data == 'error'){
		   $("#error-all").removeClass('full-success');
		$("#error-all").addClass('full-error');	 
	    $( "#error-all" ).html( "Something wrong! please try again" );	
	  } else if(data == 'success') {	
		 window.location='{!!URL("/")!!}';
		// window.location='{!!URL("createPage")!!}';
		//window.location='{!!URL("account/createEvent")!!}';		
	  } else if(data == 'pro_succ'){
		window.location='{!!URL("/")!!}';  
	  }	else if(data == 'check_activation_email'){
        $( "#error-suemail" ).html( "Something wrong, Check the email address" );			
	  } else if(data == 'account_suspended'){
         $( "#error-suemail" ).html( "your account suspended" );			
	  } else {	
        $("#error-all").removeClass('full-success');
		$("#error-all").addClass('full-error');	  
	   $( "#error-all" ).html( "invalid username and password" );	
	  }
	 });		 
	 }		 
	} else {
	  $( "#error-suemail" ).html( "Bruh, that email address is invalid" );	
	 return false;
	}	
  });
  
 $( "#psignup" ).submit(function( event ) {
   event.preventDefault();
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	  
	var csignupm = $( "input#slemail" ).val();
	var sfname = $( "input#full_name").val();
	var sigpass = $( "input#password").val();

    if(sfname == '' || sfname == ' '){
      $( "#error-fn" ).html( "First name field required" );	
	 return false;
	}
	
	 if(csignupm.match(mailformat))
	{ 
     if(sigpass == '' || sigpass == ' '){
	  $( "#error-password" ).html( "Password field required" );
	  return false;	  
	 } else {
		  var cpl = sigpass.length;
	  if(cpl < 8){
		$( "#error-password" ).html( "The password must be at least 8 characters." );		  
	  } else {		
	  var datapst = $.post('{!!URL("signup")!!}', $('#psignup').serialize());
	  datapst.done(function( data ) {
	  if(data == 'error'){
		  $("#error-all").removeClass('full-success');
		$("#error-all").addClass('full-error');
		$( "#error-all" ).html( "Something wrong! please try again" );	
	  } else if(data == 'exist'){
		  $("#error-slemail").show();
		  $( "#error-slemail" ).html( "Email already used, please use other email address" );
	  } else {	 
	    /*$("#psignup")[0].reset();
		$("#error-all").removeClass('full-error');
		$("#error-all").addClass('full-success');
		$( "#error-all" ).html( 'You are registered successfully!' );*/
		window.location='{!!URL("getStarted")!!}';	
	  }	
	 });
	 }
	}
	} else {
	  $("#error-slemail").show();
	  $( "#error-slemail" ).html( "Bruh, that email address is invalid" );
		return false;
	}	
 }); 
});	 
</script>
@stop