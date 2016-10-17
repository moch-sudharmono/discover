@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
<div class="container inner-notification">
 <section class="sec">   
        <div class="tab-content">
         <div class="tab-pane active">			
		<div class="form-group col-lg-12 col-sm-12 col-xs-12">
<h3 class="mr-botom25 border-btm-head">Your Notifications</h3>		
 @if(!empty($all_notf[0]->id))	 
   <ul id="nfloadingdata">
<?php 
  if(!empty($all_notf[0]->id)){ 	  
   foreach($all_notf as $ntsd){
	if($ntsd->global_read == 1){
	  $rds_stats = 'notif-acc'; 
    } else {
	  $rds_stats = 'notif-acc active';
    } 
    if($ntsd->type == 'event'){		 
	 $allevntlu = DB::table('events')->where('id', '=', $ntsd->object_id)->select('event_url')->get(); 
?>
  <li id="allnotf-{!!$ntsd->id!!}">
	<div class="{!!$rds_stats!!}">
	  <a href="{!!URL('/event/'.$allevntlu[0]->event_url)!!}">{!!$ntsd->subject!!}</a>
	  <a href="javascript:void(0)" onClick="sAllRead({!!$ntsd->id!!});"><span class="fa fa-times"></span></a>
	</div>
  </li> 
<?php } else if($ntsd->type == 'page'){ 
 $allevntlu = DB::table('account_details')->where('id', '=', $ntsd->object_id)->select('account_url')->get(); 
?>
	<li id="allnotf-{!!$ntsd->id!!}">
	 <div class="{!!$rds_stats!!}">
	  <a href="{!!URL('/'.$allevntlu[0]->account_url)!!}">{!!$ntsd->subject!!}</a>
	  <a href="javascript:void(0)" onClick="sAllRead({!!$ntsd->id!!});"><span class="fa fa-times"></span></a>
	 </div>
	</li> 
<?php } else { ?>
    <li id="allnotf-{!!$ntsd->id!!}">
	 <div class="{!!$rds_stats!!}">
	  <a href="#">{!!$ntsd->subject!!}</a>
	  <a href="javascript:void(0)" onClick="sAllRead({!!$ntsd->id!!});"><span class="fa fa-times"></span></a>
	 </div>
	</li> 				
<?php }}} ?>   
   </ul>
	 <div id="loading-loader"></div>
 @else
	empty / Not founded any events
 @endif	 
   
		</div>	
<?php 
/*var_dump($all_notf->count());
var_dump($all_notf->currentPage());
var_dump($all_notf->total()); 
var_dump($all_notf->nextPageUrl()); */
?>
 <!-- Holds your page information!! -->
<input type="hidden" id="cpage" value="{!! $all_notf->currentPage() !!}" />
<input type="hidden" id="max_page" value="{!! $all_notf->lastPage() !!}" />

 @if(sizeof($all_notf) > 10)
   <!-- Your End of page message. Hidden by default -->
   <div id="end_of_page" class="center">
     <hr/>
     <span>Load More...</span>
   </div> 
  @endif 
 		
		</div>      
        </div> 
 </section>
</div>
@stop
@section('scripts')
 <Script> 
 var outerPane = $('#nfloadingdata'), didScroll = false;
 $(window).scroll(function() { //watches scroll of the window
  didScroll = true;
 });
 
//Sets an interval so your window.scroll event doesn't fire constantly. This waits for the user to stop scrolling for not even a second and then fires the pageCountUpdate function (and then the getPost function)
setInterval(function() {
  if (didScroll){
    didScroll = false;
   if(($(document).height()-$(window).height())-$(window).scrollTop() < 10){
    if(!$('#loading-loader').html() || $('#loading-loader').html() == ' '){ 	
	   pageCountUpdate(); 
	 }
   }
  }
}, 100);

//This function runs when user scrolls. It will call the new posts if the max_page isn't met and will fade in/fade out the end of page message
 function pageCountUpdate(){
   var page1 = parseInt($('#cpage').val());
    var max_page = parseInt($('#max_page').val());
    if(page1 < max_page){
		$('#cpage').val(page1+1);
       var npage = parseInt(page1+1);
       getPosts(npage);
       $('#end_of_page').hide();
    } else {
      $('#end_of_page').fadeIn();
    }	 
 }
//Ajax call to get your new posts
 function getPosts(npage){
    $.ajax({
        type: "GET",
        url: '{!!URL("all/ajexNotfication?page=")!!}'+npage, // whatever your URL is        
        beforeSend: function(){ //This is your loading message ADD AN ID
          $('#loading-loader').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive" style="width:65px"/>');
        },
        complete: function(){ //remove the loading message
         $('#loading-loader').html(' ');
        },
        success: function(datas) { // success! YAY!! Add HTML to content container
         $('#nfloadingdata').append(datas);
        }
     });
 } //end of getPosts function 
 function sAllRead(sntfid){	
  var notfRdUrl = base_url+'/isread-notf/' + sntfid;
  $.ajax({
    type: "get",
    url: notfRdUrl, 
   success: function(data) {
	  $( "li#allnotf-"+sntfid ).remove(); 
   }
  }); 
 } 
</script>
@stop