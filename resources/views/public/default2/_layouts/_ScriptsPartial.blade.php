<!-- BEGIN CORE PLUGINS (REQUIRED FOR ALL PAGES) -->
<script type="text/javascript">
    var base_url = '{!! url("/") !!}';
</script>
<!--[if lt IE 9]>
{!!HTML::script("assets/public/default2/plugins/respond.min.js")!!}
<![endif]-->
<!--{!!HTML::script("assets/public/default2/plugins/jquery.min.js")!!}-->

<!-- END CORE PLUGINS -->

<script type="text/javascript">

$(document).ready(function(){
	$( ".pup-close" ).click(function() { 
  		$(".corporate").css("padding-right", "0px");
  		$('body').removeClass('custom-model');
 	});

  	$( "#signinevent" ).submit(function(event) {
	  	event.preventDefault();
	  	
	  	var mailformat 	= /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	   	var sigpass 	= $( "input.sgpass").val();
	   	var sgmail 		= $( "input.sgmail" ).val();

	 	if(sgmail.match(mailformat))
		{
     		if(sigpass == '' || sigpass == ' '){
	  			
	  			$( "#error-sgpass" ).html( "Password field required" );
	  			return false;	  

	 		} else {		 

	  			var datapst = $.post( "<?php echo url('login/user'); ?>", $("#signinevent").serialize() );
	   			
	   			datapst.done(function( data ) {	  
		   			if(data == 'error'){
					
						$("#error-lpopall").removeClass('full-success');
						$("#error-lpopall").addClass('full-error');
						$("#error-lpopall" ).html( "Something wrong! please try again" );
		  			
		  			} else if(data == 'success') {	 
			 		
			 			window.location='{!!URL("/")!!}';

		  			} else if(data == 'pro_succ'){

						window.location='{!!URL("MyBusinessName/account")!!}';  

		  			} else if(data == 'check_activation_email'){

						$( "#error-sgmail" ).html( "Something wrong, Check the email address" );	 
		  			
		  			} else if(data == 'account_suspended'){

			  			$("#error-lpopall").removeClass('full-success');
						$("#error-lpopall").addClass('full-error');
						$( "#error-lpopall" ).html( "account suspended" );

		  			} else {

			   			$("#error-lpopall").removeClass('full-success');
						$("#error-lpopall").addClass('full-error');
						$( "#error-lpopall" ).html( "invalid email and password" ); 

		  			}
	 			});		 
	 		}		 

		}else {

			$( "#error-sgmail" ).html( "Something wrong, Check the email address" );	
			return false;

		}	
  	});	   
	
  	$( "#signupevent" ).submit(function(event) {
		event.preventDefault();

    	var mailformat 	= /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		var csignupm 	= $( "input.signemail" ).val();
		var sfname 		= $( "input#pfullname").val();
		var sigpass 	= $( "input.signpass").val();
 
		if(sfname == '' || sfname == ' '){
	  		$( "#error-pfullname" ).html( "First name field required" );	
	 		return false;
		}
	
		if(csignupm.match(mailformat)){ 
     		if(sigpass == '' || sigpass == ' '){
       			$( "#error-signpass" ).html( "Password field required" );
	  			return false;	  
	 		} else {
	   			var cpl = sigpass.length;
	  			
	  			if(cpl < 8){
					$( "#error-signpass" ).html( "The password must be at least 8 characters." );		  
	  			} else {	

		  			var datapst = $.post( "<?php echo url('signup'); ?>", $("#signupevent").serialize() );
		  			
		  			datapst.done(function( data ) {
		  				if(data == 'error'){
			 				$("#error-popall").removeClass('full-success');
							$("#error-popall").addClass('full-error');
							$( "#error-popall" ).html( "Something wrong! please try again" );	 
		  				} else if(data == 'exist'){
			  				$( "#error-signemail" ).html( "Email already used, please use other email address" );
		  				} else {	 
							window.location='{!!URL("getStarted")!!}';
		  				}	
		 			});

	  			}
			}

		} else {

			$( "#error-signemail" ).html( "Bruh, that email address is invalid" );
			return false;
		}	
	
 	});
 
 	$( "#popup-nsup" ).click(function() {  
   		$('body').addClass('custom-model'); 
 	});  
});	 

$(function () {	 
	$('[data-toggle="tooltip"]').tooltip()
})

</script>

<!-- END PAGE LEVEL JAVASCRIPTS -->

<script type="text/javascript">
function getState(){
  var ctryid = $( "select#country" ).val();
     $.ajax({
        type: "GET",
        url: '{!!URL("cscdata/state")!!}/'+ctryid,       
        beforeSend: function(){ 
         $('select#state').html('<option>loading...</option>');  
		 $('select#city').html('<option>loading...</option>');
        },
        complete: function(){ 
        },
        success: function(cscdata) {
         $('select#state').html(cscdata); 
		 var sid = $( "select#state" ).val();
         $('select#city').load('{!!URL("cscdata/city")!!}/'+sid);		
        }
     }); 
}
 
function getCity(){
  var stateid = $( "select#state" ).val();
   $('select#city').html('<option>loading...</option>');
   $('select#city').load('{!!URL("cscdata/city")!!}/'+stateid);
}
 
$(document).ready(function(){ 
	$( "select#country" ).change( getState );
});

</script>

@if($vs == 'yes')

<script type="text/javascript"> 
NovComet.subscribe('notificationAlert', function(data){	
    console.log('notificationAlert');
}); 

$(document).ready(function() {
    NovComet.run(base_url); 
}); 

function notfiydata(ntfid){
 var notfCheckUrl = base_url+'/global-notf/' + ntfid;  
     $.ajax({
        type: "GET",
        url: notfCheckUrl, 
        success: function(data) {
			if(data){
          		if(data.nlist != 0){ 
		   			$("ul#notf-list").html(data.nlist);		
		  		}
		  		
		  		if(data.ncount > 5){		  
		   			$("ul#notf-list").append('<li><div class="all-notifications"><a href="{!!URL('all/notifications')!!}">See All</a></div></li>');
		  		}	 
		 	} else {
		 	
		 	}
        }
     });   
}

function sRead(sntfid){	
  var notfRdUrl = base_url+'/isread-notf/' + sntfid;
  $.ajax({
    type: "get",
    url: notfRdUrl, 
   success: function(data) {
	  $( "li#notf-"+sntfid ).remove(); 
   }
  }); 
} 
function glbRead(){
 var alludt = $('#cup-notif').val();
 if(alludt){
 if(alludt != 0){	 
  var notfgbRdU = base_url+'/globalrd-notf/' + alludt;
  $.ajax({
    type: "get",
    url: notfgbRdU, 
   success: function(data){
	 NovComet.run(base_url); 
   }
  }); 
  }
 } 
}
</script>
@endif
@if (!Services\MenuManager::isImageShown())
<script type="text/javascript">
</script>
@endif
@section('scripts')
@show