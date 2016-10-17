@foreach($all_event as $alevnt)
  <tr>
    <td>{!! ucfirst($alevnt->event_name) !!}</td>
    <td>
	 @if(!empty($alevnt->event_image))
			@if(is_numeric($alevnt->event_image)) 	 
   		<?php $evtoimg = DB::table('event_catimages')
   								->where('id', '=', $alevnt->event_image)
   								->select('id','ecat_name','ecat_path','ecat_image')
   								->get(); 
   		?>	
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
	<td>
	 <?php 
	  if(!empty($alevnt->event_date) && $alevnt->event_dtype == 'single'){
	    $aev_date = date('M d, Y', strtotime($alevnt->event_date));
	  } else if($alevnt->event_dtype == 'multi'){ 		
	    $getmdate = DB::table('event_multidate')->where('ev_id', '=', $alevnt->id)->select('id','start_date')->get(); 
	    $aev_date = date('M d, Y', strtotime($getmdate[0]->start_date)); 
	  } else {
	    $aev_date = 'n/a'; 
	  }
	 ?> 
	 {!! $aev_date !!}
	</td>
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