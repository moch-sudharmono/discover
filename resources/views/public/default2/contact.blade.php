@section('styles')
@stop

@section('content')
<section class="sec">
  <div class="tabbable tabs-left">  
      <!-- BEGIN SIDEBAR -->
            @include("public/default2/posts.sidebar")
            <!-- END SIDEBAR -->
 <div class="form-group col-lg-8 col-sm-8 col-xs-12 ">	
  <div class="row margin-bottom-40"> 
    <!-- BEGIN CONTENT -->
    <div class="col-md-12">
    <!--   <h2 class="entry-title">Contacts</h2> -->
      <div class="content-page">
        <div class="row">
          <div class="col-md-12">
            <!-- <div id="contact-map" class="gmaps margin-bottom-40" style="height:360px;"> <img src="{!!URL::to('images/map.jpg')!!}"> </div> -->
			
			
			 <div id="errors-div"> @if (Session::has('error_message'))
              <div class="alert alert-error alert-danger"> <strong>Error!</strong> {!! Session::get('error_message') !!} </div>
              @endif
              @if (Session::has('success_message'))
              <div class="alert alert-success"> <strong>Success!</strong> {!! Session::get('success_message') !!} </div>
              @endif
              @if( $errors->count() > 0 )
              <div class="alert alert-error alert-danger">
                <p>The following errors have occurred:</p>
                <ul id="form-errors">
				{!! $errors->first('name', '
                  <li>:message</li>
                  ') !!}
                  {!! $errors->first('email', '
                  <li>:message</li>
                  ') !!}  
                 {!! $errors->first('phone', '
                  <li>:message</li>
                  ') !!}  	
				 {!! $errors->first('subject', '
                  <li>:message</li>
                  ') !!} 	
				 {!! $errors->first('message', '
                  <li>:message</li>
                  ') !!} 				  
                </ul>
              </div>
              @endif </div>
			  
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12 contact-forms">
             <div class="col-lg-4 col-sm-12 get-touch"> 
              @if ($post->content != '')
                <h4><span class="makeitblue"><i class="fa fa-map-marker"></i>Our Contacts:</span></h4>
                {!! $post->content !!}
              @endif </div>           
            
            <!-- BEGIN CONTACT FORM --> 
			<div class="col-md-8 col-sm-8 col-xs-12"> 
				{!! Form::open(array('url'=>'contact/send', 'id'=>'contact-form', 'class'=>'contact-form')) !!}
				<div class="grid_3 omega">
          	<div class="form-group col-lg-6 col-sm-12"> 
          {!! Form::text('name',Input::old('name'),array('id'=>'name','class'=>'form-control','placeholder'=>'Full Name','required'=>'required')) !!} 
          	</div>
          <div class="form-group col-lg-6 col-sm-12"> 
          {!! Form::text('email', Input::old('email'),array('id'=>'email','class'=>'form-control','placeholder'=>'Email','required'=>'required')) !!} 
          </div>
          				  <div class="form-group col-lg-6 col-sm-12"> 
          {!! Form::text('phone', Input::old('phone'), array('id'=>'phone','class'=>'form-control','placeholder'=>'Phone Number','required'=>'required')) !!} 
          </div>
          <div class="form-group col-lg-6 col-sm-12"> 
          {!! Form::text('subject', Input::old('subject'), array('id'=>'subject', 'class'=>'form-control', 'placeholder'=>'Subject','required'=>'required')) !!} 
          </div>

				  <div id="response"></div>
				</div>
				<div class="alpha col-lg-12 col-sm-12">
				  <div class="form-group"> 
          {!! Form::textarea('message', Input::old('message'),array('id'=>'message','cols'=>'30','rows'=>'10','class'=>'form-control','placeholder'=>'Message...','required'=>'required')) !!} 
          </div>
				</div>
				
				<div class="contact-captha">   </div>
				<input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}">	
				
				<div class="btn-wrapper col-lg-12 col-sm-12">
				  <input type="submit" class="btn b-btn con-send" id="submit" value="Send"/>
  
				  <i class="btn-marker"></i> </div>
				{!! Form::close() !!} 
			</div>
            <!-- END CONTACT FORM --> 
		
            
          </div>
        </div>
      </div>
      <!-- END CONTENT --> 
    </div>
</div>
    </div>
	   </div>
</section>
<!-- @stop

@section('scripts') 
<script> function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(21.0000, 78.0000),
    zoom: 3,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("contact-map"),mapProp);
 }
  google.maps.event.addDomListener(window, 'load', initialize); </script> 
@stop  -->