@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
 <section class="sec">
      <div class="tabbable tabs-left">
       @include("public/default2/_layouts._AccountMenu")
        <div class="tab-content">
         <div class="tab-pane active">			
		<div class="form-group col-lg-12 col-sm-8 col-xs-12 table-responsive">
<h3 class="mr-botom25">  Manage Event </h3>
 @if(Session::has('succ_mesg'))
                <span class="col-md-12 full-success alert">
                  <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                  <strong>Success!</strong> {!! Session::get('succ_mesg') !!}
                </span>			
  @endif		
 @if(!empty($all_event[0]->id))	 
   <table class="table table-striped padd" id="cload">
     <thead>	
      <tr>
        <th>Event</th>
        <th>Event Image</th>
		<th>Event type</th>		
		<th>Event Cost type</th>
		<th>Event date</th>
		<th>Event venue</th>		
		<th>Operation</th>
      </tr>
     </thead>
    <tbody id="loadingdata">	
	@foreach($all_event as $alevnt)
	  <tr>
        <td>{!! ucfirst($alevnt->event_name) !!}</td>
        <td>
		 @if(!empty($alevnt->event_image))
  @if(is_numeric($alevnt->event_image)) 	 
<?php $evtoimg = DB::table('event_catimages')->where('id', '=', $alevnt->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	
 @if(!empty($evtoimg[0]->id))			   
	<img src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}" class="img-responsive" style="width:35px"/>
  @endif		
		 @else		 
		  <img src="{!!URL::to('uploads/events/'.$alevnt->account_type.'/'.$alevnt->event_image)!!}" class="img-responsive" style="width:35px"/>
	   @endif
	     @else
		 n/a
		 @endif	 
		</td>
        <td>{!! ucfirst($alevnt->event_type) !!}</td>
		<td>{!! ucfirst($alevnt->event_cost) !!}</td>
		<td>{!! $alevnt->event_date !!}</td>
        <td>{!! $alevnt->event_venue !!}</td>
		<?php 
		 if(isset($acc_url) && !empty($acc_url)) {
			$event_edit = URL($acc_url.'/account/updateEvent/'.$alevnt->id);
			$acul = $acc_url;
			$fevdelete = "eventdelete('".$acul."',".$alevnt->id.")";
		 } else {
			$event_edit = URL('account/updateEvent/'.$alevnt->id);
			$fevdelete = 'eventdelete('.$alevnt->id.')';
		 } 
		?>
        <td><a href="{!!URL('event/'.$alevnt->event_url)!!}" class="btn btn-inverse btn-xs">View</a>
		<a href="{!!$event_edit!!}" class="btn btn-primary btn-xs">Edit</a>
		<a href="javascript:void(0)" onClick="{!! $fevdelete !!}"  data-target="#eventdelete" class="btn btn-danger btn-xs" data-toggle="modal">
		<span class="fa fa-times"></span>Delete</a>
		</td>
      </tr>         
    @endforeach         
    </tbody>
     </table>
	 <div id="loading-loader"></div>
	<!-- {!! $all_event->render() !!}--> 
 @else
	You have no events to manage
 @endif	 
   
		<div class="modal fade" id="eventdelete">
		 <div class="modal-dialog">
		 		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<h4>Are you sure to delete this..</h4>
			</div>
			<div class="modal-footer">
				<a id="delevent" class="btn btn-primary">Confirm</a>
				<a href="javascript:void(0)" class="btn" data-dismiss="modal">Cancel</a>
			</div>	</div>
		</div>
		</div>
		</div>	
<?php 
/*var_dump($all_event->count());
var_dump($all_event->currentPage());
var_dump($all_event->total()); 
var_dump($all_event->nextPageUrl()); */
?>
 <!-- Holds your page information!! -->
<input type="hidden" id="cpage" value="{!! $all_event->currentPage() !!}" />
<input type="hidden" id="max_page" value="{!! $all_event->lastPage() !!}" />

 @if(sizeof($all_event) > 10)
   <!-- Your End of page message. Hidden by default -->
   <div id="end_of_page" class="center">
     <hr/>
     <span>Load More...</span>
   </div> 
  @endif 
 		
		</div>      
        </div>
      </div>
 </section>
@stop
@section('scripts')
 <Script>  
  function eventdelete(acul,uid){
   if(uid == null){
	document.getElementById("delevent").href='{!!URL("account/deletevent/'+acul+'")!!}'; 
   } else {
	document.getElementById("delevent").href='{!!URL("'+acul+'/account/deletevent/'+uid+'")!!}'; 
   }
  }
  
 var outerPane = $('#loadingdata'), didScroll = false;
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
        url: '{!!URL("account/getmEvent?page=")!!}'+npage, // whatever your URL is        
        beforeSend: function(){ //This is your loading message ADD AN ID
          $('#loading-loader').html('<img src="{!!URL::to("assets/public/default2/images/progress.gif")!!}" class="img-responsive" style="width:65px"/>');
        },
        complete: function(){ //remove the loading message
         $('#loading-loader').html(' ');
        },
        success: function(datas) { // success! YAY!! Add HTML to content container
         $('#loadingdata').append(datas);
        }
     });
 } //end of getPosts function 
</script>
@stop