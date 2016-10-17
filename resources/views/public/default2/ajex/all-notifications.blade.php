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