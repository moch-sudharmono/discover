@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
 <section class="sec">
      <div class="tabbable tabs-left">
       @include("public/default2/_layouts._AccountMenu")
        <div class="tab-content form-group col-lg-8 col-sm-8 col-xs-12">
         <div class="tab-pane active">		 
		  <b>Account name:</b> {{ $event_details[0]->name }}
		  <h3> Manage Users & Close/Transfer Accounts  </h3>
		     @if (Session::has('error_mesg'))
                <span class="col-md-12 full-error alert">
                  <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                  <strong>Error!</strong> {!! Session::get('error_mesg') !!}
                </span>			
              @endif

			    @if (Session::has('succ_mesg'))
                <span class="col-md-12 full-success alert">
                  <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                  <strong>Success!</strong> {!! Session::get('succ_mesg') !!}
                </span>			
              @endif			  
				 <span class="col-md-12" id="error-all"></span>	
		  <div class="form-group row">
		      <div class="col-lg-3 col-sm-4 col-xs-12 send"> 
               <a href="javascript:void(0)" id="add-event" data-toggle="modal" data-target="#addeventu-pop-up" class="btn discover-btn muni-btn">Add Event Manager</a>						
              </div>		   
		      <div class="col-lg-3 col-sm-4 col-xs-12 send"> 
               <a href="javascript:void(0)" id="add-nevent" data-toggle="modal" data-target="#transferacc-pop-up" class="btn discover-btn muni-btn">Transfer Account</a>						
              </div>
              <div class="col-lg-3 col-sm-4 col-xs-12">			  
			   <a href="javascript:void(0)" id="add-nadmin" data-toggle="modal" data-target="#closeacc-pop-up" class="btn discover-btn muni-btn">Close Account</a> 						
              </div>              
          </div> 
		  
		  <!-- add event pop_up -->
				<div id="addeventu-pop-up" class="modal fade" role="dialog">
				  <div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content sign_up-form">
					 <div class="modal-header text-center">
						<button type="button" class="close" id="event-pclose" data-dismiss="modal">&times;</button>
					  </div>
					  <div class="modal-body">
						
					   <form class="form-inline" id="add-eventu" method="post">
					    <div class="form-group col-lg-12 col-sm-12 col-xs-12">
						 <strong>Add event Users for '{{ $event_details[0]->name }}' account</strong>
                         <input type="email" id="event-mail" placeholder="Enter invite event manager user email" value="{{ old('email') }}" name="email" class="form-control email-box" required />
						 <div class="help-block with-errors" id="error-vntemail"></div>
						 <input type="submit" class="btn discover-btn save-btn" value="Send request"/>
					     
					    </div>
						<input type="hidden" name="event_add" value="y"/>	
						 <input type="hidden" name="account" value="{{ $event_details[0]->id }}"/>				 
						 <input type="hidden" name="_token" value="{{ csrf_token() }}">	
				        <input type="hidden" name="user_type" value="event"/>	                            					
					   </form>
						
					  </div>
					</div>
				  </div>
				</div>
				<!-- pop_up -->
		  
<!-- Close pop_up -->
				<div id="closeacc-pop-up" class="modal fade" role="dialog">
				  <div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content sign_up-form">
					 <div class="modal-header text-center">
						<button type="button" class="close" id="admin-pclose" data-dismiss="modal">&times;</button>
					  </div>
					  <div class="modal-body">
						<form class="form-inline" action="{!!URL($event_details[0]->account_url.'/account/closeAccount')!!}" method="post">
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
						 Do you really want to close <strong>" {{ $event_details[0]->name }} "</strong> account if yes, then press the <b>Agree</b> button.<br><strong> Once the account is closed then this action can't be undone.</strong>
						 
						 <input type="hidden" name="acc_did" value="{{ $event_details[0]->id }}">	
						 <input type="hidden" name="_token" value="{{ csrf_token() }}">	
                      	<input type="submit" class="btn discover-btn save-btn" value="Agree"/>
						 </div>							
						</form>
					  </div>
					</div>
				  </div>
				</div>
				<!-- pop_up -->
				
				<!-- Transfer  pop_up -->
				<div id="transferacc-pop-up" class="modal fade" role="dialog">
				  <div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content sign_up-form">
					 <div class="modal-header text-center">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					  </div>
					  <div class="modal-body">
						<form class="form-inline" action="{!!URL($event_details[0]->account_url.'/account/transferAccount')!!}" method="post">
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
						  If you would like to become one of the Event Managers for this account then choose <b>yes</b>, and your account access will be changed to Event Manager.<br> 
						If you are chose <b>no</b>, then your access will be closed.<br> <b>After choosing option yes/no, click on Send button</b><br>
						  <input type="radio" name="future-access" value="y" /> Yes &nbsp; | &nbsp;
                          <input type="radio" name="future-access" value="n" checked /> No
						<hr>
						
						Do you really want to Transfer <strong>" {{ $event_details[0]->name }} "</strong> account if yes, then enter the email of the person you wish to transfer the account too and press the <b>Send</b> button.<br>
						<strong>Once you are send request for new user then this action can't be undone.</strong><br>
                        <input type="email" placeholder="Enter an email address " value="{{ old('email') }}" name="email" class="form-control email-box" required />
						 <input type="hidden" name="acc_did" value="{{ $event_details[0]->id }}">	
						    <input type="hidden" name="_token" value="{{ csrf_token() }}">	
						<input type="submit" class="btn discover-btn save-btn" value="Send"/>
						 </div>
						</form>
					  </div>
					</div>

				  </div>
				</div>
				<!-- pop_up -->		
    			
        </div> 
   </div>
</section>
@stop
@section('scripts')
<script>  
 jQuery(document).ready(function(){
  $( "#add-eventu" ).submit(function( event ) {
   event.preventDefault();
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;	  
	var csignupm = $( "input#event-mail" ).val();
	 if(csignupm.match(mailformat))
	{ 	
	  var datapst = $.post('{!!URL("account-user/add-eventu")!!}', $('#add-eventu').serialize());
	  datapst.done(function( data ) {
	  if(data == 'error'){
		 $("#error-vntemail").show();
		$( "#error-vntemail" ).html( '<strong>Error!</strong> Something wrong! please try again' );	
	  } if(data == 'exist_mail'){
		  $("#error-vntemail").show();
		$( "#error-vntemail" ).html( '<strong>Error!</strong> You are already sent request for this user.' );	   
	  } if(data == 'success') {	
	    $("#add-eventu")[0].reset();
		$( "#error-vntemail" ).html( '' );	
		 $( "#event-pclose" ).trigger( "click" );
		$("#error-all").removeClass('full-error');
		$("#error-all").addClass('full-success');
		$( "#error-all" ).html( '<strong>Success!</strong> Reuest sent successfully!' );	
	  }	
	 });
	} else {                  
	  $("#error-vntemail").show();
	  $( "#error-vntemail" ).html( '<strong>Error!</strong> That email address is invalid' );
		return false;
	}	
  });  
 });  
</script>
@stop