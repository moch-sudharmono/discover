 jQuery(document).ready(function () {
		
        
		
        jQuery("#event_description").cleditor({
            controls:
                    "bold italic underline | size " +
                    "style | color | bullets numbering | outdent " +
                    "indent | alignleft center alignright justify | undo redo | " +
                    "rule | cut copy paste pastetext | source",
        });
		
		
       
        jQuery("#event_url").keyup(function () {
            this.value = this.value.replace(/ /g, "_");
        });
        // jQuery('#datetimepicker2').datetimepicker({
        // format: 'L',	  
        // });	
        jQuery("#sevent_date").datepicker({
            showOn: "button",
            minDate: '0',
            buttonImage: clicon,
            buttonImageOnly: true,
        });
		
        jQuery("#event_date").datepicker({
            showOn: "button",
            minDate: '0',
            buttonImage: clicon,
            buttonImageOnly: true,
        });


        jQuery('#datetimepicker4').datetimepicker({
            format: 'LT'
        });
        jQuery('#datetimepicker5').datetimepicker({
            format: 'LT'
        });
        jQuery("input#us3-address").blur(function () {
            jQuery('#alloc_fileds').show();
            jQuery('#slocation').hide();
        });
        /* jQuery("#event_price").keyup(function () {
         this.value = this.value.replace(/ /g, "-");
         }); */
		 
        jQuery('body').delegate(".event_cost", 'click', function () {
            var ecradioValue = jQuery("input[name='event_cost']:checked").val();
            if (ecradioValue) {
                if (ecradioValue == 'paid') {
                    jQuery('#event-price').show();
                    jQuery('#maintk_surl').show();
                } else {
                    jQuery('#event-price').hide();
                    jQuery('#maintk_surl').hide();
                }
            }
        });
        jQuery('body').delegate("input[name='social_link']", 'click', function () {
            var soclink = jQuery("input[name='social_link']:checked").val();
            if (soclink) {
                jQuery('#soc_url').show();
            } else {
                jQuery('#soc_url').hide();
            }
        });
        jQuery('.pubprv').click(function () {
            jQuery('input.pubprv').not(this).prop('checked', false);
            if (jQuery('input#prv-evntjs').is(":checked"))
            {
                jQuery('#private_option').show();
            } else {
                jQuery('#private_option').hide();
            }
        });
		
		
	
        jQuery('.sip_event').click(function () {
            jQuery('input.sip_event').not(this).prop('checked', false);
        });
        jQuery('#open_alldetail').click(function () {
            jQuery('#alloc_fileds').show();
            jQuery('#slocation').hide();
        });

        jQuery('#restlocation').click(function () {
            jQuery('#alloc_fileds').hide();
            jQuery('#slocation').show();
            jQuery('#us3-address').val('');
            jQuery('#us3-venue').val('');
            jQuery('#ev_address').val('');
            jQuery('#ev_addres').val('');
            jQuery('#evt_city').val('');
            jQuery('#evt_state').val('');
            jQuery('#evt_zip').val('');
        });
        /* jQuery("#ticket_surl").keyup(function () {
         this.value = this.value.replace(/ /g, "_");
         });*/
        function updateLvsControls(addressComponents) {
			var baseCountryUrl = jQuery("#baseCountryUrl").val();
			jQuery('#ev_address').val(addressComponents.addressLine1);
            jQuery('#evt_city').val(addressComponents.city);
            jQuery('#evt_state').val(addressComponents.stateOrProvince);
            jQuery('#evt_zip').val(addressComponents.postalCode);
            var countcode = addressComponents.country;
			console.log(countcode);
            if (countcode) {
                jQuery.ajax({
                    type: "GET",
                    url: baseCountryUrl + countcode,
                    beforeSend: function () {
                        jQuery('#us5-country').html('<option selected>loading</option>');
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        jQuery('#us5-country').html(data);
                    }
                });
            }
        }
		
		
        jQuery('#us3').locationpicker({
            location: {latitude: 0, longitude: 0},
            radius: 1,
            inputBinding: {
                latitudeInput: jQuery('#us3-lat'),
                longitudeInput: jQuery('#us3-lon'),
                radiusInput: jQuery('#us3-radius'),
                locationNameInput: jQuery('#us3-address'),
            },
            enableAutocomplete: true,
            onchanged: function (currentLocation, radius, isMarkerDropped) {
                var addressComponents = jQuery(this).locationpicker('map').location.addressComponents;
                updateLvsControls(addressComponents);
            }
        });
		
		
		
		jQuery('#us3-address').keyup(function(){
			jQuery("#us3-venue").val(jQuery(this).val());
		})

        jQuery("input#evt_state").blur(function () {
            var cyadd = jQuery('input#evt_state').val();
            var cutryadd = jQuery('#us5-country').find(":selected").text();
            if (cutryadd) {
                var gsadd = cyadd + ',' + cutryadd;
            } else {
                var gsadd = cyadd;
            }
			
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': gsadd}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var latlong = results[0].geometry.location.lat();
                    var lnglong = results[0].geometry.location.lng();
                    var vsLatLng = {lat: latlong, lng: lnglong}
                    var map = new google.maps.Map(document.getElementById('us3'), {
                        center: vsLatLng,
                        zoom: 15,
                    });
                    var marker = new google.maps.Marker({
                        position: vsLatLng,
                        map: map,
                        title: cyadd
                    });
                } else {
                    //alert("Something got wrong " + status);
                }
            });
        });
		
        jQuery("#us5-country").change(function () {
            var cyadd = jQuery('input#evt_state').val();
            var cutryadd = jQuery('#us5-country').find(":selected").text();
            if (cyadd) {
                var gsadd = cyadd + ',' + cutryadd;
                var vstitle = cyadd;
            } else {
                var gsadd = cutryadd;
                var vstitle = cutryadd;
            }
            var geocoder = new google.maps.Geocoder();
			
            geocoder.geocode({'address': gsadd}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var latlong = results[0].geometry.location.lat();
                    var lnglong = results[0].geometry.location.lng();
                    var vsLatLng = {lat: latlong, lng: lnglong}
                    var map = new google.maps.Map(document.getElementById('us3'), {
                        center: vsLatLng,
                        zoom: 15,
                    });
                    var marker = new google.maps.Marker({
                        position: vsLatLng,
                        map: map,
                        title: vstitle
                    });
                }
            });
        });
			
    });
	
    function selimg(imgid) {
        jQuery("#our_pid").val(imgid);
    }
	
    jQuery('body').delegate("a.ourimg_tag", 'click', function () {
        var src = jQuery(this).find('img').attr('src');
        jQuery('#sel_ourimg').html('<div class="jFiler-item-thumb-image"><img draggable="false" src="' + src + '"/></div>');
        jQuery('#sel_ourimg').css("display", "block");
        jQuery("#csimg").trigger("click");
    });
	
    jQuery(function (jQuery) {
        jQuery("#phone_no").mask("(999) 999-9999");
    })