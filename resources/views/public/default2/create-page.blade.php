@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
  <section class="creat-page">
  <div class="form-bg container"> 
    <!-- tabs right -->
    <div class="tabbable tabs-right">
      <div class="col-md-offset-2 col-md-8 text-center">
        <h3> Choose Your Account Type </h3>
        <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text eve</p>
      </div>
      <ul class="nav nav-tabs right-side  text-center">
        <li class=" col-md-4 col-sm-4 col-xs-4 business" data-uk-scrollspy="{cls:'uk-animation-slide-right', repeat:true, delay:100}"> <a href="#2" data-toggle="tab" id="business-select">
          <div class="col-md-12"> <span class="personal-icon"> </span> </div>
          <div class="col-md-12 text-center">
            <h3>Business</h3>
            <small> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when </small> </div>
          </a> </li>
        <li class="col-md-4 col-sm-4 col-xs-4 municipality" data-uk-scrollspy="{cls:'uk-animation-slide-right', repeat:true, delay:400}"> <a href="#5" data-toggle="tab" id="municipality-select">
          <div class="col-md-12"> <span class="business-icon"> </span> </div>
          <div class="col-md-12 text-center">
            <h3>Municipality</h3>
            <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when </small> </div>
          </a> </li>
        <li class="col-md-4 col-sm-4 col-xs-4 club" data-uk-scrollspy="{cls:'uk-animation-slide-right', repeat:true, delay:800}"> <a href="#6" data-toggle="tab" id="club-select">
          <div class="col-md-12"> <span class="municipality-icon"> </span> </div>
          <div class="col-md-12 text-center">
            <h3>Club/Organization</h3>
            <small> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when </small> </div>
          </a> </li>
        <a href="#" class='next'>
        <input type="submit" class="btn discover-btn" value="Next">
        </a>       
      </ul>
    </div>
    <!-- /tabs --> 
  </div>
  </div>
  </div>
</section>
@stop
@section('scripts')
  <script>
		  jQuery(document).ready(function(){
		  jQuery('.business').click(function(){
        jQuery('#business-select').attr('style','background-color:cornsilk');
        jQuery('#municipality-select').attr('style','background-color:""');
        jQuery('#club-select').attr('style','background-color:""');
				jQuery(".next").attr("href", "{!!URL('business-form')!!}");
		  });
		  jQuery('.municipality').click(function(){
        jQuery('#business-select').attr('style','background-color:""');
        jQuery('#municipality-select').attr('style','background-color:cornsilk');
        jQuery('#club-select').attr('style','background-color:""');
				jQuery(".next").attr("href", "{!!URL('municipality-form')!!}");
		  }); 
		  jQuery('.club').click(function(){
        jQuery('#business-select').attr('style','background-color:""');
        jQuery('#municipality-select').attr('style','background-color:""');
        jQuery('#club-select').attr('style','background-color:cornsilk');
				jQuery(".next").attr("href", "{!!URL('club-organization-form')!!}");
		  });
		  
		  });
 </script>
@stop