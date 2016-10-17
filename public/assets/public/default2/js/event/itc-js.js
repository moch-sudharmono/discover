/********map-function************/ 
 var geocodervs = new google.maps.Geocoder(); 
  function mcitcaddress(vtsml, ogitctitle,divmcl) {
    geocodervs.geocode({address:vtsml}, function (resultvs,statusvs)
      { 
         if (statusvs == google.maps.GeocoderStatus.OK) {
		  $(".pop-design").removeClass("evtm-popup");		 
		  $( ".home-map" ).removeClass( "without-map" );
          var pvs = resultvs[0].geometry.location;
          var lats=pvs.lat();
          var lngs=pvs.lng();
		  var myLatLng = {lat: lats, lng: lngs};
		  //alert(lats+'-'+lngs);
		var mapopup = new google.maps.Map(document.getElementById(divmcl), {
          zoom: 5,
          center: myLatLng
        });
		var marker = new google.maps.Marker({
          position: myLatLng,
          map: mapopup,
          title: ogitctitle
        });
        } else { 
         $(".pop-design").addClass("evtm-popup");	
		 $(".home-map").addClass("without-map");
		}
     });
  }   