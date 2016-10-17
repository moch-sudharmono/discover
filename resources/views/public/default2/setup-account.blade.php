@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
 <section class="creat-page">
  <div class="form-bg"> 
    <!-- tabs right -->
    <div class="tabbable tabs-right">
      <div class="tab-content left-side col-md-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12" >
        <div class="tab-pane active" id="1">
          <div class="personal-form account-user">
           
              <ul class="nav nav-tabs" id="tabs">
		 <form class="form-inline" method="post" enctype="multipart/form-data">
                <li class="active">
					  
                  <div class=" per-1 active">
                    <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 ">
                      <h4 class="heading"> Set Up your Account</h4>
					  </div>
					    @if ($errors->has())
						   <div class="col-md-12 full-error alert">
					   <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
					   You have some form errors. Please check below.</div>						
						@endif
					   <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 ">
                     <h3>Account Details</h3>
					    <div class="col-lg-12 col-sm-12 col-xs-12 rem-text rt row">
                      <input type="checkbox" value="y" id="enotif_status" name="enotification_status">
                      Receive Email Event Notification
					  </div>
                    </div>
					
  <div class="col-lg-12 col-sm-12 col-xs-12" id="chos-category" style="display:none">
	<div class="col-lg-12 col-sm-12 col-xs-12 choose-catagory">
     <h3 class="option1">Choose Your Categories</h3>
	  <div class="catagories">
	   <div class="col-lg-6 col-sm-6 col-xs-12 row ">	
	    <select id="chYcat" class="selectpicker" name="interested_catagories[]" multiple>
		 <option value="All">ALL</option>
	      @if(!empty($evtsdata[0]))
		   @foreach($evtsdata as $etdata)
		    <option data-tokens="{!! $etdata !!}" value="{!! $etdata !!}">{!! $etdata !!}</option>
		   @endforeach 
	      @endif	
	    </select>  	  
	   </div>
	  </div>
	</div>
	<div class="col-lg-12 col-sm-12 col-xs-12">
	 <div class="form-group sel pick-event">
		<h3>Choose Your Event Range</h3>
	  <div class="col-lg-6 col-sm-6 col-xs-12 row">
     <select title="event" name="selected_event" class="selectpicker bs-select-hidden" id="lunch">
	     <option class="bs-title-option events-select" value="">Please choose the distance, from your location, you wish to receive event notifications</option>
      @if(!empty($get_ctyd[0]->country) && $get_ctyd[0]->country == '38')  
		 <option value="5">5 k/m</option>
         <option value="10">10 k/m</option>
         <option value="15">15 k/m</option>
         <option value="25">25 k/m</option>
         <option value="50">50 k/m</option>
		 <option value="100">100 k/m</option>
		 <option value="200">200 k/m</option>
	  @else
		 <option value="5">5 miles</option>
         <option value="10">10 miles</option>
         <option value="15">15 miles</option>
         <option value="25">25 miles</option>
         <option value="50">50 miles</option>
		 <option value="100">100 miles</option>
		 <option value="200">200 miles</option>
      @endif
     </select>
	  </div>
     </div>
	</div>
  </div>
  
 <input type="hidden" value="{{$grd_id}}" name="grd_id"/>
					  <div class="col-lg-12 col-sm-12 col-xs-12 rem-text rt">
                      <input type="checkbox" value="y" name="email_nbusiness" id="option">
                      Receive email notifications on event from pages you follow.
					  </div>
					  <div class="col-lg-12 col-sm-12 col-xs-12 rem-text rt">
                      <input type="checkbox" value="y" name="email_nupdate" id="option">
                      Receive email notifications on site updates & features.
					  </div>
					  </div>
                </li>
				 <input type="hidden" name="_token" value="{{ csrf_token() }}">	
                <div class="form-group col-lg-12 col-sm-12 col-xs-12"> 
			<input type="submit" class="btn discover-btn" value="Finish"/>            
        </div>
		 </form>
		 <!--   <form class="form-inline" method="post" enctype="multipart/form-data">
			 <input type="hidden" name="ckd" value="yes">	
			<input type="hidden" name="_token" value="{{ csrf_token() }}">	
		      <div class="form-group col-lg-12 col-sm-12 col-xs-12"> 
			  <input type="submit" class="btn discover-btn" value="Skip"/>
			 </div>
			</form> -->
          </ul>            
          </div>
        </div>
      </div>
    </div>
    <!-- /tabs --> 
  </div>
 </section>
@stop
@section('scripts')
 <script>
  jQuery(document).ready(function(){		
   function toggleSelectAll(control) {
    var allOptionIsSelected = (control.val() || []).indexOf("All") > -1;
    function valuesOf(elements) {
        return $.map(elements, function(element) {
            return element.value;
        });
    }

     if (control.data('allOptionIsSelected') != allOptionIsSelected) {
        // User clicked 'All' option
        if (allOptionIsSelected) {
            // Can't use .selectpicker('selectAll') because multiple "change" events will be triggered
            control.selectpicker('val', valuesOf(control.find('option')));
        } else {
            control.selectpicker('val', []);
        }
     } else {
        // User clicked other option
        if (allOptionIsSelected && control.val().length != control.find('option').length) {
            // All options were selected, user deselected one option
            // => unselect 'All' option
            control.selectpicker('val', valuesOf(control.find('option:selected[value!=All]')));
            allOptionIsSelected = false;
        } else if (!allOptionIsSelected && control.val().length == control.find('option').length - 1) {
            // Not all options were selected, user selected all options except 'All' option
            // => select 'All' option too
            control.selectpicker('val', valuesOf(control.find('option')));
            allOptionIsSelected = true;
        }
     }
      control.data('allOptionIsSelected', allOptionIsSelected);
   }
  $('#chYcat').selectpicker().change(function(){toggleSelectAll($(this));}).trigger('change');  


	$('body').delegate("#enotif_status", 'click', function(){
      var enotfValue = $("input[name='enotification_status']:checked").val();
     if(enotfValue){
	  $('#chos-category').show();
     } else {
	  $('#chos-category').hide();	 
	 }
    });	  
 });
 </script>
@stop