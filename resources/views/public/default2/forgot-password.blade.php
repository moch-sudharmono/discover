@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
  <!-- Login And Sign-Up form pop_up -->
  <div class="container forget">
	<div class="col-md-offset-2 col-md-8 col-xs-12">
	<div style="" id="show-forgot" class="col-md-12 col-sm-12 col-xs-12 forget-bg pad-s">
        @if(!empty(Session::get('error_message')))
		 <span class="col-md-12 full-error alert">
			<button data-dismiss="alert" aria-label="close" class="close">&times;</button>
			{{Session::get('error_message')}}</span>
	    @endif
		@if(!empty(Session::get('success_message')))
		 <div class="col-md-12 full-success alert">
			<button data-dismiss="alert" aria-label="close" class="close">&times;</button>
			{!!Session::get('success_message')!!}
		</div>
		@endif
		        
		<div class="modal-header text-center">
			<p><b>Enter your e-mail address below to reset your password. </b></p>
		  </div>
                    <div class="modal-body forgot itc-inp">
                        <form method="post" class="form-inline">						
                          <div class="form-group col-lg-12 col-sm-12 col-xs-12">
						  <div class="col-md-8 col-sm-8 col-xs-12 small-pa">
                               <input type="email" placeholder="Email" value="{{ old('email') }}" name="email" class="form-control" required />							
						  </div>	
							<div>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">	
							  <input type="submit" value="Submit" class="btn discover-btn">
							</div>						  
                          </div>
                        </form>
                    </div>
    </div>
	</div>
  </div>
<!-- Login form pop_up -->
@stop
@section('scripts')
@stop