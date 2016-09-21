@extends('backend/default/custom_layouts._layout')
@section('styles')
@stop
@section('content')
    <div id="page" class="dashboard">
        <h1>Welcome</h1>
		 <div class="row-fluid">

         <div class="span3">
		  <b>Total Events:</b>  {!!$eventCount!!}
         </div>  
		<div class="span3">
		  <b>Total Account Page:</b> {!!$acc_count!!}
        </div>
       <div class="span3">
		  <b>Total Website Views:</b> {!!$wvcount[0]->count!!}
        </div>

         <div class="span3">
			<b>Total Unique Visits:</b> {!!$unvistr[0]->uvd!!}
         </div>
		
        </div>
		
        <div class="row-fluid">
         <div class="span12">
		   <div class="span6">
		     <img src="{!! url('backend/adminGraph') !!}" />                   
            </div>  
            <div class="span6">
               <div id="hero-donut"></div>
            </div>
        </div>
    </div>
 </div>	
@stop

@section('scripts')
    @parent
    <script src="{!! URL::to('assets/backend/default/scripts/stats/modernizr-2.6.2-respond-1.1.0.min.js') !!}"></script> 
<script src="{!! URL::to('assets/backend/default/scripts/stats/jquery.knob.js') !!}"></script> 
 <script src="{!! URL::to('assets/backend/default/scripts/stats/raphael-min.js') !!}"></script> 
 <script src="{!! URL::to('assets/backend/default/scripts/stats/morris/morris.min.js') !!}"></script>  
    <script>
        $('.board-widgets').mouseover(function(e) {
            $(this).find('.board-sub').show();
        }).mouseout(function(e) {
            $(this).find('.board-sub').hide();
        });
    </script>
	    <script type="text/javascript">		
	     // Morris Donut Chart
        Morris.Donut({
            element: 'hero-donut',
            data: [
                {label: 'Direct', value: {!!$drcount!!} },
                {label: 'Referrals', value: {!!$rucount!!} },
                {label: 'Unique visitors', value: {!!$unvistr[0]->uvd!!} }
            ],
            colors: ["#30a1ec", "#76bdee", "#c4dafe"],
            formatter: function (y) { return y + "%" }
        });	    
    </script>
@stop