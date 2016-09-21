@extends('backend/default/custom_layouts._layout')
@section('styles')
@stop

@section('content')			    	
 @if (Session::has('succ_mesg'))				 
	<div id="errors-div">
	   <div class="alert alert-success">
		   <button class="close" data-dismiss="alert">×</button>
		   <strong>Success!</strong> {!! Session::get('succ_mesg') !!}
		</div>
	</div>
 @endif
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN TABLE widget-->
            <div class="widget box light-grey">
                <div class="blue widget-title">
                    <h4><i class="icon-table"></i>Event List</h4>
                </div>
                <div class="widget-body">

                <div class="form-horizontal">
                
				 <div class="widget-body">
                  
                    <table class="table table-striped table-hover table-bordered" id="sample_1">
                        <thead>
                            <tr>
                            <!--    <th class="span1"><input type="checkbox" class="select_all" /></th>-->
                                <th>Title</th>
								<th>User Name</th>
                                <th>Account Type</th>
                                <th>Event Image</th>
                                <th>Event Date</th>
                                <th>Event Cost</th>
								 <th>Event Type</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($all_event as $event)

                                <tr class="">
                                  <!--  <td>{!! Form::checkbox($event->id, 'checked', false) !!}</td> -->
                                    <td>{!! $event->event_name !!}</td>
                                    <td>{!! $event->full_name !!}</td>
                                    <td>{!! $event->account_type !!}</td>
                                  <td>
									
  @if(!empty($event->event_image))	
   @if(is_numeric($event->event_image)) 	 
     <?php $evtoimg = DB::table('event_catimages')->where('id', '=', $event->event_image)->select('id','ecat_name','ecat_path','ecat_image')->get(); ?>	
    @if(!empty($evtoimg[0]->id))	
     <img style="width:100px;" src="{!!URL::to('uploads/'.$evtoimg[0]->ecat_path.'/'.$evtoimg[0]->ecat_name.'/'.$evtoimg[0]->ecat_image)!!}"/>
    @endif 
   @else			 
	 <img style="width:100px;" src="{!!URL::to('uploads/events/'.$event->account_type.'/'.$event->event_image)!!}"/>
   @endif 		
  @else
	  n/a
  @endif   
			                       </td>
                                    <td>{!! $event->event_date !!}</td>
									<td>{!! $event->event_cost !!}</td>
									<td>
			@if($event->private_event == 'y')
				Private Event
            @else
				Public Event
            @endif	
			</td>
									 
        <td>
         <a href="{!! URL::to('/event/'.$event->event_url) !!}" target="_blank" class="btn btn-mini">
		  <i class="icon-eye-open"></i>
		 </a>
		 
		 <div class="actions inline">
          <div class="btn btn-mini"><i class="icon-cog"> Actions</i></div>
            <ul class="btn btn-mini">
             <li>
			@if($event->event_block == 'n') 
               <a href="{!! URL::to('/backend/event/'.$event->id.'/y') !!}">Block</a>
			@else  
			   <a href="{!! URL::to('/backend/event/'.$event->id.'/n') !!}">UnBlock</a>           
			@endif  
             </li>
			 <hr>
             <li>
              <a href="{!! URL::to('/backend/review-event/'.$event->id) !!}" target="_blank"><i class="icon-eye-open"></i> Review</a>            
             </li>
            </ul>
         </div>
        </td>
       </tr>
          @endforeach
      </tbody>
     </table>
    </div>
					  
					  
					  
					  
					  
					  
                    </div>

                </div>
            </div>
            <!-- END TABLE widget-->
        </div>
    </div>
@stop
@section('scripts')
      <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script type="text/javascript" src="{!! URL::to('assets/backend/default/plugins/data-tables/jquery.dataTables.js') !!}"></script>
        <script type="text/javascript" src="{!! URL::to('assets/backend/default/plugins/data-tables/DT_bootstrap.js') !!}"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        @parent
        <script src="{!! URL::to('assets/backend/default/scripts/table-managed.js') !!}"></script>
        <script>
           jQuery(document).ready(function() {
              TableManaged.init();
           });
        </script>
        <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        $(function() {
            $('#selected_ids').val('');

            $('.select_all').change(function() {
                var checkboxes = $('#sample_1 tbody').find(':checkbox');

                if ($(this).is(':checked')) {
                    checkboxes.attr('checked', 'checked');
                    restore_uniformity();
                } else {
                    checkboxes.removeAttr('checked');
                    restore_uniformity();
                }
            });
        });
        function deleteRecords(th, type) {
            if (type === undefined) type = 'record';

            doDelete = confirm("Are you sure you want to delete the selected " + type + "s ?");
            if (!doDelete) {
                // If cancel is selected, do nothing
                return false;
            }

            $('#sample_1 tbody').find('input:checked').each(function() {
                value = $('#selected_ids').val();
                $('#selected_ids').val(value + ' ' + this.name);
            });
        }
        function restore_uniformity() {
            $.uniform.restore("input[type=checkbox]");
            $('input[type=checkbox]').uniform();
        }
    </script>
@stop