@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
 <section class="sec">
      <div class="tabbable tabs-left">
       @include("public/default2/_layouts._AccountMenu")
        <div class="tab-content">
         <div class="tab-pane active">
		 <div class="form-group col-lg-12 col-sm-8 col-xs-12">	
		  <h3 class="mr-botom25"> Manage Users </h3>
		    <form class="form-inline" method="post" action="" enctype="multipart/form-data"> 
			  <img src="{!!URL::to('assets/public/default2/images/coming-soon.jpg')!!}"/>
			</form>
		</div> 
		</div> 
		
        </div>
      </div>
</section>
@stop
@section('scripts')
@stop