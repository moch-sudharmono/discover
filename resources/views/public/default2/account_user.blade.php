@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
<section class="creat-page">
  <div class="form-bg"> 
    <!-- tabs right -->
    <div class="tabbable tabs-right">
      <div class="tab-content left-side col-md-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12" >
        <div class="tab-pane active" id="1">
          <div class="personal-form account-user">
           <form class="form-inline" method="post" enctype="multipart/form-data">        
			<div class="col-md-12" id="error-all"></div>
                  <div class=" per-1 active">
                    <div class="col-lg-12 col-sm-12 col-xs-12 ">
                      <h4> Account User </h4>
                  
                    </div>
                  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
<div class="col-lg-12 col-sm-12 col-xs-12 row">
  <p>You are the admin user to your new account. Add additional admin or event manager to your account.</p>
 <div class="col-lg-6 col-sm-6 col-xs-12 row"> 
	<a href="javascript:void(0)" id="add-nevent" data-toggle="modal" data-target="#addevent-pop-up" class="btn discover-btn muni-btn">Add Event Manager</a>
 </div>
 <div class="col-lg-6 col-sm-6 col-xs-12 row">
  <div class="add-mlist">
	<ul id="added-mlist"></ul>
  </div>	
 </div>	
</div>
<input type="hidden" name="evtmg_count" value="0" id="evtmg_count"/>						
					<div class="col-lg-12 col-sm-12 col-xs-12 row"> 
					 Enter your account page URL		
					</div>
				   <div class="col-lg-12 col-sm-12 col-xs-12 row"> 			
					<label class="input-itc">
					 <span class="icon-prepend">{!!URL('/')!!}/</span>
					  <input type="text" value="{{ old('account_url') }}" name="account_url" id="account_url"/>
					 {!! $errors->first('account_url', '<span class="help-block with-errors">:message</span>') !!}	
					</label>										   
					<h6>Note: Please pick a name related to your page so users can find/remember your page easier. If left blank you will be assigned a random number for your page. You can add or change your name later in your account settings.</h6>	
				  </div>
						
					   <input type="hidden" name="account" id="get-aid" value="{{$acid}}"/>
					  	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}"/>	           
                  </div>
                    <div class="col-lg-12 col-sm-12 col-xs-12 rem-text">
					  <p><b> Email Updates</b></p>
                      <input type="checkbox" value="y" name="email_nupdate"/>Receive Email Update
					</div>
                  </div> 
                
				 <input type="submit" class="btn discover-btn" value="Finish"/>
              
            </form>
			
			<!-- admin pop_up 
				<div id="addadmin-pop-up" class="modal fade" role="dialog">
				  <div class="modal-dialog">
					<div class="modal-content sign_up-form">
					 <div class="modal-header text-center">
						<button type="button" class="close" id="admin-pclose" data-dismiss="modal">&times;</button>
					  </div>
					  <div class="modal-body">
						<form class="form-inline" id="add-eadminu" method="post">
						  <div class="form-group col-lg-12 col-sm-12 col-xs-12">
						 <strong>Add admin Users for current account<strong>
						 
                        <input type="email" placeholder="Enter an email address " id="admin-mail" value="{{ old('email') }}" name="email" class="form-control email-box" required />
						<input type="submit" class="btn discover-btn save-btn" value="Save"/>
						<div class="help-block with-errors" id="error-slemail"></div>
						 </div>
					  <input type="hidden" name="user_type" value="admin"/>	 
				  <input type="hidden" name="account" value="{{$acid}}"/>
						    <input type="hidden" name="_token" value="{{ csrf_token() }}">	
							
						</form>
					  </div>
					</div>

				  </div>
				</div>
				 pop_up -->
				
				<!-- event pop_up -->
				<div id="addevent-pop-up" class="modal fade" role="dialog">
				  <div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content sign_up-form">
					 <div class="modal-header text-center">
						<button type="button" class="close" id="event-pclose" data-dismiss="modal">&times;</button>
					  </div>
					  <div class="modal-body">
				    <form class="form-inline" id="add-eventu" method="post">
					   <div class="form-group col-lg-12 col-sm-12 col-xs-12">
						<strong>Add event Users for current account</strong>
                        <input type="email" id="event-mail" placeholder="Enter an email address " value="{{ old('email') }}" name="email" class="form-control email-box" required />
						 <input type="submit" class="btn discover-btn save-btn" value="Save"/>
					    <div class="help-block with-errors" id="error-vntemail"></div>
					   </div>
						 <input type="hidden" name="account" value="{{$acid}}"/>				 
						    <input type="hidden" name="_token" value="{{ csrf_token() }}">	
				        <input type="hidden" name="user_type" value="event"/>	
                            					
					</form>
				   </div>
				  </div>

				  </div>
				</div>
				<!-- pop_up -->
			
          </div>
        </div>
      </div>
    </div>
    <!-- /tabs --> 
  </div>
  </div>
  </div>
</section>
@stop
@section('scripts')
<script>
    jQuery(document).ready(function(){
		
     jQuery("#account_url").keyup(function () {
      this.value = this.value.replace(/ /g, "_");
     });
	 
  /*$( "#add-eadminu" ).submit(function( event ) {
   event.preventDefault();
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;	  
	var csignupm = $( "input#admin-mail" ).val();
	 if(csignupm.match(mailformat))
	{ 	
	  var datapst = $.post('{!!URL("account-user/add-eventu")!!}', $('#add-eadminu').serialize());
	  datapst.done(function( data ) {
	  if(data == 'error'){
		  $("#error-all").removeClass('full-success');
		$("#error-all").addClass('full-error');
		$( "#error-all" ).html( "Something wrong! please try again" );	
	  } if(data == 'success') {	
	    $("#add-eadminu")[0].reset();
		 $( "#admin-pclose" ).trigger( "click" );
		$("#error-all").removeClass('full-error');
		$("#error-all").addClass('full-success');
		$( "#add-nadmin" ).text( "Add Another Admin" );
		 $('#sendMail').prop("disabled", false); 
		$( "#error-all" ).html( 'User saved successfully!' );	
	  }	
	 });
	} else {
	  $("#error-slemail").show();
	  $( "#error-slemail" ).html( "That email address is invalid" );
		return false;
	}	
  }); */
  
    $( "#add-eventu" ).submit(function( event ) {
   event.preventDefault();
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;	  
	var csignupm = $( "input#event-mail" ).val();
	 if(csignupm.match(mailformat))
	{ 	
	  var datapst = $.post('{!!URL("account-user/add-eventu")!!}', $('#add-eventu').serialize());
	  datapst.done(function( data ) {
	  if(data == 'error'){
		  $("#error-all").removeClass('full-success');
		$("#error-all").addClass('full-error');
		$( "#error-all" ).html( '<strong>Error!</strong> Something wrong! please try again' );	
	  } if(data == 'exist_mail'){
		  $("#error-all").removeClass('full-success');
		$("#error-all").addClass('full-error');
		$( "#error-all" ).html( '<strong>Error!</strong> You are already sent request for this user.' );	
	   
	  } if(data.success == 'success') {	
	    $("#add-eventu")[0].reset();
		 $( "#event-pclose" ).trigger( "click" );	
		$( "#add-nevent" ).text( "Add Another Event Manager" );		
		// $('#sendMail').prop("disabled", false); 	
     var evtcnt = $("#evtmg_count").val();	
     var addevtcnt = parseInt(evtcnt)+1;
      $("#evtmg_count").val(addevtcnt);	 
	$("#added-mlist").append("<li id='anelist-"+data.aid+"'>"+data.email+" <Span class='rmclose'><a href='javascript:void(0);' onClick='raEmail("+data.aid+")'>x</a></span></li>");
	 }	
	 });
	} else {
	  $("#error-vntemail").show();
	  $( "#error-vntemail" ).html( '<strong>Error!</strong> That email address is invalid' );
		return false;
	}	
  });   
  
   $( "#sendMail" ).click(function() {
	  $("#sendMail").prop('value', 'Sending...'); 
	  var sendVal = 'yes';
	  var accid = $( "input#get-aid" ).val();
	  var tken = $( "input#_token" ).val();
	  var datapst = $.post('{!!URL("account-user/send-request")!!}', { svl: sendVal, acid: accid, _token: tken } );
	 datapst.done(function( data ) {
	  if(data == 'error'){
		  $("#error-all").removeClass('full-success');
		$("#error-all").addClass('full-error');
		$( "#error-all" ).html( '<strong>Error!</strong> Firstly add the event manager user mail on list' );	
	  } if(data == 'success'){
		$("#error-all").removeClass('full-error');
		$("#error-all").addClass('full-success');
		$( "#error-all" ).html( '<strong>Success!</strong> Email reuest sent successfully!' );
		$("#sendMail").prop('value', 'Sent'); 
        $('#sendMail').prop("disabled", true);		
	  }	
	 });	
  }); 
	 
  });
  
 function raEmail(erid){
  if(erid){		
	$.ajax({
        type: "GET",
        url: '{!!URL("account-user/remove-eventu/'+erid+'")!!}',
        success: function(data) {
		 if(data == 'success'){
          var evtdcnt = $("#evtmg_count").val();	
          var adddevtcnt = parseInt(evtdcnt)-1;
         $("#evtmg_count").val(adddevtcnt);				 
          $( "li#anelist-"+erid ).remove();
		 }
        }
    });
  }	
 } 
</script>
@stop