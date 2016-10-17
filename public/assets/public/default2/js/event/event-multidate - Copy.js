/*-----------------------multi-date-setup-------------------*/
$(function(){ 
 $(".edit-se_multiple").hide();
 /*-------add-multi-event-fun()--------*/
 $("#arrdatepicker").datepicker({
    showOn: "button",	
	minDate: '0',
    buttonImage: clicon,
    buttonImageOnly: true,
    onSelect: function(dateText, inst) {
        //dateText comes in as MM/DD/YY
        var datePieces = dateText.split('/');
        var month = datePieces[0];
        var day = datePieces[1];
        var year = datePieces[2];
        //define select option values for
        //corresponding element
        $('select#arrmonth').val(month);
        $('select#arrday').val(day);
        $('select#arryear').val(year);
        dateDifference();
    }
 });

 $("#depdatepicker").datepicker({
    showOn: "button",	
	minDate: '0',
    buttonImage: clicon,
    buttonImageOnly: true,
    onSelect: function(dateText, inst) {
        //dateText comes in as MM/DD/YY
        var datePieces = dateText.split('/');
        var month = datePieces[0];
        var day = datePieces[1];
        var year = datePieces[2];
        //define select option values for
        //corresponding element
        $('select#depmonth').val(month);
        $('select#depday').val(day);
        $('select#depyear').val(year);
        dateDifference();
    }
 });
});
 $("#start-time").click(function(event)
 { 
   event.preventDefault();    
   dateDifference();
   tcimecounts();
 });
 $("#end-time").click(function(event)
 { 
   event.preventDefault();    
   dateDifference();
   tcimecounts();
 });
 $("#schedule_event_seires").click(function(event)
 { 
   event.preventDefault();    
   dateDifference();
   tcimecounts();
 });
 $("#ul-series-schedule-weekly").click(function(event)
 { 
   event.preventDefault();    
   dateDifference();
   tcimecounts();
 }); 
 $("#series-schedule-monthly-day").click(function(event)
 { 
   event.preventDefault();    
   dateDifference();
   tcimecounts();
 });
 function tcimecounts(){	
  if($("#start-time option:selected").val()!='' && $("#end-time option:selected").val()!='') {
    var ckday = parseInt($("#schedule_event_seires option:selected").val());	 
    var now  = $("#end-time option:selected").val();
    var then = $("#start-time option:selected").val();
    var mss = moment(now,"HH:mm").diff(moment(then,"HH:mm"));
    var vxd = moment.duration(mss);
    var vxsh = Math.floor(vxd.asHours());
    var vxsm = moment.utc(mss).format("mm");
   if(ckday > 0){	  	
	var vxsresh = (24*ckday)+vxsh;
   } else {
	var vxsresh = vxsh; 
   }
    $('#timecount-est').html('('+vxsresh+' hours '+vxsm+' minutes each)');
  } 
 }

 function dateDifference(){	
   var gtdays = []; 	
  $('#ul-series-schedule-weekly option:selected').each(function(i, selected){ 
   gtdays[i] = [$(selected).val()]; 
  });
    var mntoday = $('#series-schedule-monthly-day option:selected').val();
  if($("#arrdatepicker").val()!='' && $("#new-series-period option:selected" ).val() == 'custom'){
	  var start_day = $("#arrdatepicker").val();
	$('#daycount').show();
	$('#sdate_label').html('<div id="vslabel">1</div> date');
	$("#show_date").html("Starting on "+start_day); 
	$('#daycount').show();
	$("#sd").val(start_day);
  } else {
   if(gtdays && gtdays !=  ''){
	var startDate = new Date($("#arrdatepicker").datepicker("getDate"));
	var endDate = new Date($("#depdatepicker").datepicker("getDate"));	
    var ndays = 1 + Math.round((endDate-startDate)/(24*3600*1000));
    var sums = function(a,b) {
      return a + Math.floor( ( ndays + (startDate.getDay()+6-b) % 7 ) / 7 ); 
	};			
	var diff = gtdays.reduce(sums,0);	
   } else if(mntoday && mntoday != '') {	
      var vstart = $("#arrdatepicker").datepicker("getDate"),
        vsend = $("#depdatepicker").datepicker("getDate"),
		vsbetween = [],
	    cndtvs = null;
        xvz = 0;
	 while (vstart <= vsend) {		
		vsbetween.push([vstart.getDate()]);	
       vstart.setDate(vstart.getDate() + 1);
     }
	 $.each(vsbetween, function( ind, vale ) {
	  if(vale == mntoday){ 
		cndtvs += xvz+1;	
	  }	
    });
	var diff = cndtvs;	
   } else {	    
	var diff = ($("#depdatepicker").datepicker("getDate") - $("#arrdatepicker").datepicker("getDate")) / 1000 / 60 / 60 / 24;	
   }	   
      $('#vslabel').html(diff);
	  $('#daycount').show();
	 var start_day = $("#arrdatepicker").val();
     var end_day = $("#depdatepicker").val();  
	$("#show_date").html("Starting "+start_day +" through "+ end_day);
	$("#sd").val(start_day); 
	$("#ed").val(end_day); 
  }	  
 }
  function removemud(cpv){	//remove m-event
     var cps_vl = parseInt($( "#count_p" ).val());
    if( cps_vl > 0 ) {
	 $('p#new-period'+cpv).remove();
     $("#count_p").val(cps_vl-1);
    }
   return false;
  } 
  
  $(document).ready(function()
 {  
	 // $(".start-end_multiple").hide();
	  $(".series-schedule-weekly").hide();
	  $(".series-schedule-monthly-day").hide();
	 $(".schedule_event").click(function(event)
	{
	   event.preventDefault();
	  $(".start-end").hide();
	  $(".start-end_multiple").show();
	});
		
	 $('.schedule_event_div').on('click', 'a', function(event)
	{
	   event.preventDefault();		
      $('.new-period').hide();	   
	  $("#add_edate").hide();
	  $("#cancel_repevent").hide();	
	  $(".start-end").hide();
	  $(".start-end_multiple").show();
	  $('#schedule_event').hide(); 
      $('#event_dtype').val('multi'); 
	});
	
	 $('#cancel-event-btn').click(function()
	{			
	   $(".start-end_multiple").hide();
	   $(".new-period").show();
	   $("#add_edate").show();
	   $("#cancel_repevent").show();	
	   $("#schedule_event").hide();
	});
		     		   
	  var curr_date = $.datepicker.formatDate('mm/dd/yy', new Date());
	   $('#arrdatepicker').prop('placeholder', curr_date);
	   $('#depdatepicker').prop('placeholder', curr_date);
	 var new_series_period1 = $("#new-series-period option:selected" ).text();
	 var new_series_period2 = $("#start-time option:selected" ).text();
	 var new_series_period3 = $("#end-time option:selected" ).text();
	 var new_series_period4 = $("#schedule_event_seires option:selected" ).text();
		
  $("#add-event-btn").click(function(event)
 {
 	var scntDiv = $('.new-period');	
	var cp_vl = parseInt($( "#count_p" ).val());
   if(cp_vl && cp_vl != 'NaN'){
	 var i = cp_vl+1;  
   } else {
	 var i = 1;  
   }	//show_date
     $( "#count_p" ).val(i);
	var dctc = $('#vslabel').html();
	var show_date =	$("#show_date").html();
	var ev_occur = $( "#new-series-period option:selected" ).text();
	var st_ftime = $( "#start-time option:selected" ).text();
	var ed_totime = $( "#end-time option:selected" ).text();
	var stvl_ftime = $( "#start-time option:selected" ).val();
	var edvl_totime = $( "#end-time option:selected" ).val();
    var nsp_itcperiod = $("#schedule_event_seires option:selected" ).text();
    var nspvl_itcperiod = $("#schedule_event_seires option:selected" ).val();
    var nsp_itcmontyday = $("#series-schedule-monthly-day option:selected" ).text();
    var nspvl_itcmontyday = $("#series-schedule-monthly-day option:selected" ).val();
  if(nsp_itcperiod && nsp_itcperiod == 'Same day'){
	var sd_text  = ' ';
  } else {
	var sd_text  = ' on the '+nsp_itcperiod; 
  }
  var wgtdaysvl = [];
  if(ev_occur && ev_occur == 'Monthly'){ 
	var evdetails = ev_occur+' on the '+nsp_itcmontyday+' '+st_ftime+' - '+ed_totime+sd_text; 
     wgtdaysvl[0] = nspvl_itcmontyday;	
  } else if(ev_occur && ev_occur == 'Weekly'){ 
     var wgtdays = []; 
	 $('#ul-series-schedule-weekly option:selected').each(function(i, selected){ 
      wgtdays[i] = $(selected).text()+' '; 
	  wgtdaysvl[i] = $(selected).val(); 
     });
	var evdetails = ev_occur+' on '+wgtdays+st_ftime+' - '+ed_totime+sd_text; 	
  } else if(ev_occur && ev_occur == 'Custom'){ 
   var evdetails = 'Single event '+st_ftime+' - '+ed_totime+sd_text; 
  } else {
	var evdetails = ev_occur+' '+st_ftime+' - '+ed_totime+sd_text; 
  } //arrdate  
   $('<p class="newitc-periods" id="new-period'+i+'"><span class="multi-ctdata"><label><span class="cvs-count" id="cvs-count'+i+'">'+dctc+'</span><br>Dates</label></span><span class="multi-listdata"><span class="evdetails">'+evdetails+'</span><br><span class="sd">'+show_date+'</span></span><span class="multi-removediv"> <a href="javascript:void(0)" onClick="editmud('+i+');">Edit</a> | <a href="javascript:void(0)" onClick="removemud('+i+');" id="remScnt">Remove</a></span><input type="hidden" value="'+$("#sd").val()+'" id="arrdate'+i+'" name="arrdate[]"/><input type="hidden" value="'+$("#ed").val()+'" id="enddate'+i+'" name="enddate[]"/><input type="hidden" value="'+nspvl_itcperiod+'" id="ofthe'+i+'" name="ofthe[]"/><input type="hidden" value="'+stvl_ftime+'" id="startime'+i+'" name="startime[]"/><input type="hidden" value="'+edvl_totime+'" id="endtime'+i+'" name="endtime[]"/><input type="hidden" value="'+ev_occur+'" id="nspd'+i+'" name="nspd[]"/><input type="hidden" value="'+wgtdaysvl+'" id="wgtdaysvl'+i+'" name="wgtdaysvl[]"/></p>').appendTo(scntDiv);
	$(".start-end_multiple").hide();
	$(".new-period").show();
	$("#add_edate").show();
	$("#cancel_repevent").show();	
	$("#schedule_event").hide();
 });				
  $("#new-series-period").click(function(event)
 { 
     event.preventDefault();
    if($("#new-series-period option:selected" ).val() == 'weeks')
   {
	$(".series-schedule-weekly").show();
	$('#until-datesection').show();
	$(".series-schedule-monthly-day").hide();
	$("#series-schedule-monthly-day option:selected").prop("selected", false);					
	$('#custom_change').html('Occurs from');
   }
	else if($("#new-series-period option:selected" ).val() == 'months')
   {
	$("#ul-series-schedule-weekly option:selected").prop("selected", false); 
	$(".series-schedule-monthly-day").show();
	$('#until-datesection').show();
	$(".series-schedule-weekly").hide();
	$('#custom_change').html('Occurs from');
   } else if($("#new-series-period option:selected" ).val() == 'custom'){
	 $('#custom_change').html('On');	
	 $('#until-datesection').hide();
	 $(".series-schedule-weekly").hide();
	 $(".series-schedule-monthly-day").hide();
	 $("#ul-series-schedule-weekly option:selected").prop("selected", false);
	 $("#series-schedule-monthly-day option:selected").prop("selected", false);				   
	 $('#depdatepicker').val('');				   
   } else { 
     $("#ul-series-schedule-weekly option:selected").prop("selected", false);
	 $("#series-schedule-monthly-day option:selected").prop("selected", false);
	 $('#until-datesection').show();
	 $(".series-schedule-weekly").hide();
	 $(".series-schedule-monthly-day").hide();
	 $('#custom_change').html('Occurs from');	
   }
  });
				
   $(document).on('click', 'a#cancel_repevent', function(event)
  {		   
    $('#event_dtype').val('single'); 
	$('.start-end').show(); 
	$('#schedule_event').show();
	$('#cancel_repevent').hide();		
    $('#add_edate').hide();		
	$('.new-period').hide();	
	$('.new-period').html(' ');
  });		
 });
 /*---------------m-edit-functionality---------------*/
   $("#edit_arrdatepicker").datepicker({
    showOn: "button",	
	minDate: '0',
    buttonImage: clicon,
    buttonImageOnly: true,
    onSelect: function(dateText, inst) {
      var datePieces = dateText.split('/');
      var month = datePieces[0];
      var day = datePieces[1];
      var year = datePieces[2];
     $('select#arrmonth').val(month);
     $('select#arrday').val(day);
     $('select#arryear').val(year);
     dateditDifference();
	  tedcimecounts();
    }
   });
  $("#edit_depdatepicker").datepicker({
    showOn: "button",		
	minDate: '0',
    buttonImage: clicon,
    buttonImageOnly: true,
    onSelect: function(dateText, inst) {
        var datePieces = dateText.split('/');
        var month = datePieces[0];
        var day = datePieces[1];
        var year = datePieces[2];
       $('select#depmonth').val(month);
       $('select#depday').val(day);
       $('select#depyear').val(year);
      dateditDifference();
	  tedcimecounts();
    }
   });
 function editmud(spanid){
    $('.new-period').hide();	 
	var arrdate = $("#arrdate"+spanid).val();
	var enddate = $("#enddate"+spanid).val();
	var ofthe = $("#ofthe"+spanid).val();
	var startime = $("#startime"+spanid).val();
	var endtime = $("#endtime"+spanid).val();
	var wgtdaysvl = $("#wgtdaysvl"+spanid).val();
	var cxdatack = $("#nspd"+spanid).val();
	 if(cxdatack && cxdatack == 'Weekly')
	{
	  var getRus = wgtdaysvl.split(',');
	 getRus.forEach(function(entry){
	  $('select#edit-series-schedule-weekly').find('option[value="'+entry+'"]').attr("selected",true);
     }); 
	}	
	 if(cxdatack && cxdatack == 'Monthly')
	{ 
      $('#essmd-monthly-day').show();
	  $('select#edit-series-schedule-monthly-day').find('option[value="'+wgtdaysvl+'"]').attr("selected",true);
	}	
	if(cxdatack && cxdatack == 'Custom'){
     $('#edit-until-datesection').hide();	  	
	 $('#edit-custom_change').html('On');
	} else {
	  $('#edit-until-datesection').show();	
	  $('#edit-custom_change').html('Occurs from');	
	}			
	$('#up_lid').val(spanid);	
  if(arrdate){
	$('#edit_arrdatepicker').val(arrdate);  
  }	
  if(enddate){
	$('#edit_depdatepicker').val(enddate);  
  }	
  if(ofthe){
	$('#edit-schd_event_seires').val(ofthe);  
  }
  if(startime){
	$('#edit-start-time').val(startime);  
  }
  if(endtime){
	$('#edit-end-time').val(endtime);  
  }	  
	  $("#add_edate").hide();
	  $("#cancel_repevent").hide();
	  $(".start-end").hide();
	  $(".edit-se_multiple").show();
 }
  $("#edit-start-time").click(function(event)
 { 
   event.preventDefault();    
   dateditDifference();
    tedcimecounts();
 });
  $("#edit-end-time").click(function(event)
 { 
   event.preventDefault();    
   dateditDifference();
    tedcimecounts();
 }); 
  $("#edit-schd_event_seires").click(function(event)
 { 
   event.preventDefault();    
   dateditDifference();
   tedcimecounts();
 });
  $("#edit-series-schedule-weekly").click(function(event)
 { 
   event.preventDefault();    
   dateditDifference();
   tedcimecounts();
 }); 
  $("#edit-series-schedule-monthly-day").click(function(event)
 { 
   event.preventDefault();    
   dateditDifference();
   tedcimecounts();
 }); 
 function dateditDifference(){	
	 var gtdays = []; 	
	$('#edit-series-schedule-weekly option:selected').each(function(i, selected){ 
	  gtdays[i] = [$(selected).val()]; 
	});	
	 var spid = $('#up_lid').val();
     var mntoday = $('#edit-series-schedule-monthly-day option:selected').val();
     var vltype = $('#nspd'+spid).val();
   if($("#edit_arrdatepicker").val()!='' && vltype == 'Custom'){
	  var start_day = $("#edit_arrdatepicker").val();
	 $('#edit_daycount').show();
	 $('#edit_sdate_label').html('<div id="edit_vslabel">1</div> date');
	 $("#edit_show_date").html("Starting on "+start_day); 
	 $('#edit_daycount').show();
	 $("#edit_sd").val(start_day);
   } else {
    if(gtdays && gtdays !=  ''){
	  var startDate = new Date($("#edit_arrdatepicker").datepicker("getDate"));
	  var endDate = new Date($("#edit_depdatepicker").datepicker("getDate"));	
	  var ndays = 1 + Math.round((endDate-startDate)/(24*3600*1000));
      var sums = function(a,b) {
       return a + Math.floor( ( ndays + (startDate.getDay()+6-b) % 7 ) / 7 ); 
	  };
	var diff = gtdays.reduce(sums,0);	
    } else if(mntoday && mntoday != '') {	
	  var vstart = $("#edit_arrdatepicker").datepicker("getDate"),
        vsend = $("#edit_depdatepicker").datepicker("getDate"),
		vsbetween = [],
	    cndtvs = null;
        xvz = 0;
	  while (vstart <= vsend) {		
		vsbetween.push([vstart.getDate()]);	
       vstart.setDate(vstart.getDate() + 1);
      }
	  $.each(vsbetween, function( ind, vale ) {
	   if(vale == mntoday){ 
		cndtvs += xvz+1;	
	   }	
      });
	 var diff = cndtvs;	
    } else {	    
	 var diff = ($("#edit_depdatepicker").datepicker("getDate") - $("#edit_arrdatepicker").datepicker("getDate")) / 1000 / 60 / 60 / 24;	
    }	   
      $('#edit_vslabel').html(diff);
	  $('#edit_daycount').show();
	 var start_day = $("#edit_arrdatepicker").val();
     var end_day = $("#edit_depdatepicker").val();  
	$("#edit_show_date").html("Starting "+start_day +" through "+ end_day);
	$("#edit_sd").val(start_day); 
	$("#edit_ed").val(end_day); 
   } 	  
 }
 function tedcimecounts(){	
  if($("#edit-start-time option:selected").val()!='' && $("#edit-end-time option:selected").val()!='') {
    var ckday = parseInt($("#edit-schd_event_seires option:selected").val());	 
    var now  = $("#edit-end-time option:selected").val();
    var then = $("#edit-start-time option:selected").val();
    var mss = moment(now,"HH:mm").diff(moment(then,"HH:mm"));
    var vxd = moment.duration(mss);
    var vxsh = Math.floor(vxd.asHours());
    var vxsm = moment.utc(mss).format("mm");
   if(ckday > 0){	  	
	var vxsresh = (24*ckday)+vxsh;  
   } else {
	var vxsresh = vxsh; 
   } 
    $('#edit_timecount-est').html('('+vxsresh+' hours '+vxsm+' minutes each)');
  } 
 }
  $(document).on( "click", "#update-event-btn", function(event)
 {
   	var gt_ulid = $('#up_lid').val();     	
	var dctc = $('#edit_vslabel').html();
	var show_date =	$("#edit_show_date").html();
	var ev_occur = $('#nspd'+gt_ulid).val();
	var st_ftime = $( "#edit-start-time option:selected" ).text();
	var ed_totime = $( "#edit-end-time option:selected" ).text();
	var stvl_ftime = $( "#edit-start-time option:selected" ).val();
	var edvl_totime = $( "#edit-end-time option:selected" ).val();
    var nsp_itcperiod = $("#edit-schd_event_seires option:selected" ).text();
    var nspvl_itcperiod = $("#edit-schd_event_seires option:selected" ).val();
    var nsp_itcmontyday = $("#edit-series-schedule-monthly-day option:selected" ).text();
	var nspvl_itcmontyday = $("#edit-series-schedule-monthly-day option:selected" ).val();
   if(nsp_itcperiod && nsp_itcperiod == 'Same day'){
	 var sd_text  = ' ';
   } else {
	 var sd_text  = ' on the '+nsp_itcperiod; 
   }  
     var wgtdaysvl = [];
   if(ev_occur && ev_occur == 'Monthly') { 
	var evdetails = ev_occur+' on the '+nsp_itcmontyday+' '+st_ftime+' - '+ed_totime+sd_text; 
     wgtdaysvl[0] = nspvl_itcmontyday;		
   } else if(ev_occur && ev_occur == 'Weekly') { 
     var wgtdays = []; 
	$('#edit-series-schedule-weekly option:selected').each(function(i, selected){ 
      wgtdays[i] = $(selected).text()+' '; 
	  wgtdaysvl[i] = $(selected).val(); 
    });
	 var evdetails = ev_occur+' on '+wgtdays+st_ftime+' - '+ed_totime+sd_text; 	
   } else if(ev_occur && ev_occur == 'Custom') { 
     var evdetails = 'Single event '+st_ftime+' - '+ed_totime+sd_text; 
   } else {
	var evdetails = ev_occur+' '+st_ftime+' - '+ed_totime+sd_text; 
   } //arrdate evdetails
  $('#new-period'+gt_ulid).html('<span class="multi-ctdata"><label><span class="cvs-count" id="cvs-count'+gt_ulid+'">'+dctc+'</span><br>Dates</label></span><span class="multi-listdata"><span class="evdetails">'+evdetails+'</span><br><span class="sd">'+show_date+'</span></span><span class="multi-removediv"> <a href="javascript:void(0)" onClick="editmud('+gt_ulid+');">Edit</a> | <a href="javascript:void(0)" onClick="removemud('+gt_ulid+');" id="remScnt">Remove</a></span><input type="hidden" value="'+$("#sd").val()+'" id="arrdate'+gt_ulid+'" name="arrdate[]"/><input type="hidden" value="'+$("#ed").val()+'" id="enddate'+gt_ulid+'" name="enddate[]"/><input type="hidden" value="'+nspvl_itcperiod+'" id="ofthe'+gt_ulid+'" name="ofthe[]"/><input type="hidden" value="'+stvl_ftime+'" id="startime'+gt_ulid+'" name="startime[]"/><input type="hidden" value="'+edvl_totime+'" id="endtime'+gt_ulid+'" name="endtime[]"/><input type="hidden" value="'+ev_occur+'" id="nspd'+gt_ulid+'" name="nspd[]"/><input type="hidden" value="'+wgtdaysvl+'" id="wgtdaysvl'+gt_ulid+'" name="wgtdaysvl[]"/>');
	$(".edit-se_multiple").hide();
	$(".new-period").show();
	$("#add_edate").show();
	$("#cancel_repevent").show();	
	$("#schedule_event").hide();
	$('#edit-series-schedule-weekly').prop('selectedIndex',555);
	$('#essmd-monthly-day').hide();
 }); 
   $('#edit_cancel-event-btn').click(function()
  {			
	$(".edit-se_multiple").hide();
	$(".new-period").show();
	$("#add_edate").show();
	$("#cancel_repevent").show();	
	$("#schedule_event").hide();
	$('#edit-series-schedule-weekly').prop('selectedIndex',555);
	$('#essmd-monthly-day').hide();
  });