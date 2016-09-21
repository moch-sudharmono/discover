@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
<section class="sec">
<div class="tabbable tabs-left">
        @include("public/default2/_layouts._AccountMenu")
        <div class="tab-content">
         <div class="tab-pane active">
		 
		    
				 @if(Session::has('succ_mesg'))
                <span class="col-md-12 full-success alert">
                  <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                  <strong>Success!</strong> {!! Session::get('succ_mesg') !!}
                </span>			
             @endif
			
  <div class="form-group col-lg-12 col-sm-8 col-xs-12 table-responsive" id="content">		
	  <h3 class="mr-botom25"> Following </h3>	
     @if(!empty($follow_acc[0]->id))		
      <table class="table table-striped padd">
       <thead>	
        <tr>
         <th>Page Name</th>
         <th>Page Picture</th>
		 <th>Email</th>	
         <th>Page Website URL</th>			 
		 <th>Operation</th>
        </tr>
       </thead>
       <tbody>
	    @foreach($follow_acc as $fllacc)
	     <tr>
          <td>{!! ucfirst($fllacc->name) !!}</td>
          <td>
		   @if(!empty($fllacc->upload_file))
			<?php 
			 if($fllacc->g_id == 2) {
				 $cimg_apath = 'business';
			 } elseif($fllacc->g_id == 5) { 
				 $cimg_apath = 'municipality';
			 } else {
				 $cimg_apath = 'club';
			 }		 
			?>
		    <img src="{!!URL::to('uploads/account_type/'.$cimg_apath.'/'.$fllacc->upload_file)!!}" class="img-responsive" style="width:35px"/>
	       @else
		    n/a 
		   @endif	 
		  </td>
          <td>{!! $fllacc->email !!}</td>
		  <?php $event_detailsstr = preg_replace('#^https?://#', '', $fllacc->website); ?>
		  <td><a href="{!! $fllacc->website !!}" target="_blank"> {!! $event_detailsstr !!} </a></td>
          <td>
<a href='{!!URL($fllacc->account_url)!!}' target="_blank" class="btn btn-primary btn-xs">View</a>
<!-- onClick="window.open('{!!URL($fllacc->account_url)!!}','_blank','scrollbars=1,width=1000,height=600'); return false;"-->
		   <a href="javascript:void(0)" onClick="unflw({!! $fllacc->id !!})" class="btn btn-primary btn-xs">Unfollow Page</a>		    
		  </td>		  
         </tr>         
        @endforeach         
       </tbody>
      </table>
	   {!! $follow_acc->render() !!}
     @else
	   You are not following any pages, Click <a href="javascript:void(0)" data-target="#follow_account" data-toggle="modal">here</a> to see the event page list. 
     @endif		
   </div>			
  </div>      
 </div>
</div>
 <input type="hidden" name="_token" id="_token" value="{!!csrf_token()!!}">	

 <!--follow-popup--> 
  <div class="modal fade" id="follow_account">
   <div class="modal-dialog">
	<div class="modal-content">
	 <div class="modal-header">			
	  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  <h4><b>Event Pages</b></h4>
	 </div>
	<div class="modal-body col-md-12 col-ms-12 col-xs-12">	
	<div class="ef-search">
	 <input type="text" value="" placeholder="page Name" name="efsearch_val" id="efsearch_val"/> 
	 <input type="button" value="Search" name="flplistsearch" id="flplistsearch" class="btn btn-primary"/>
	</div>
 <div class="eventp-follow" id="eventp_follow">	 
 @if($unfolow != 'gc')
  @if(!empty($unfolow[0]->id)) 
   @foreach($unfolow as $unfp)	
<?php
 $bcsuid = Sentry::getUser()->id; 
 $unfep = DB::table('user_follows')->where('u_id', '=', $bcsuid)->where('follow_id', '=', $unfp->id)->distinct('follow_id')->select('id')->get();  
?>
 @if(empty($unfep[0]->id))    
	<div class="evnt-pic col-md-12 col-ms-12 col-xs-12">  
	 <div class="evnt-pic col-md-6 col-ms-6 col-xs-6">	
		<h4>{!! $unfp->name !!}</h4>
	 </div>
     <div class="evnt-pic col-md-6 col-ms-6 col-xs-6">
        <a href="{!!URL($unfp->account_url)!!}" class="btn btn-primary" target="_blank">View Page</a>	
        <a onclick="followEvent({!! $unfp->id !!})" href="javascript:void(0)" class="btn btn-primary">Follow</a>
	 </div>	
	</div>
 @endif	
   @endforeach 	
  @endif
 @endif  
 </div>	 
	</div>
	</div>
   </div>
  </div>
</section>
@stop
@section('scripts')
 <script>
  function unflw(flid){
   if(flid != null){
	 var tken = jQuery( "input#_token" ).val();  
	 var postdt = {flwid: flid, _token: tken};
	var datapost = $.post('{!!URL("account/unfollow")!!}', postdt);
	 datapost.done(function( data ) {
	  if(data == 'succ'){
		window.location='{!!URL("/account/following")!!}';	
	  } else {	
		window.location='{!!URL("/account/following")!!}';	
	  }	
	 });	
   } 
  }

  function followEvent(eid) {	 
    var token = jQuery('#_token').val();
        var postdata = {'ascid': eid, '_token': token};
	    var datapst = $.post( '{!!URL("account/follow")!!}', postdata );
	   datapst.done(function( data ) {
	    if(data == 'success'){   
	     window.location='{!!URL("/account/following")!!}';		 
	    } else { 
		 jQuery("#error-lreq").html('Login required');  
	    } 	
	  });
  } 
  $("#flplistsearch").click(function(){ 
   var evpgval = $('#efsearch_val').val();
   if(evpgval != null){
	 var tken = jQuery("input#_token").val();  
	 var postdt = {evntsdata: evpgval, _token: tken};
	var datapost = $.post('{!!URL("event/pageList")!!}', postdt);
	 datapost.done(function(data) {
		$('#eventp_follow').html(data);
	 });	
   } 
  });  
 </script>
@stop