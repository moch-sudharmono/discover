  // Define the overlay, derived from google.maps.OverlayView
function Label(opt_options) {
  // Initialization
  this.setValues(opt_options);
  // Label specific
  var span = this.span_ = document.createElement('span');
  span.className = "marker_label";
  span.setAttribute("id", "marker"+this.id);
  var div = this.div_ = document.createElement('div');
  div.appendChild(span);
  div.style.cssText = 'position: absolute; display: none';
};
Label.prototype = new google.maps.OverlayView;

// Implement onAdd
Label.prototype.onAdd = function() {
  var pane = this.getPanes().overlayImage;
  pane.appendChild(this.div_);
  // Ensures the label is redrawn if the text or position is changed.
  var me = this;
  this.listeners_ = [
    google.maps.event.addListener(this, 'position_changed', function() { me.draw(); }),
    google.maps.event.addListener(this, 'visible_changed', function() { me.draw(); }),
    google.maps.event.addListener(this, 'clickable_changed', function() { me.draw(); }),
    google.maps.event.addListener(this, 'text_changed', function() { me.draw(); }),
    google.maps.event.addListener(this, 'zindex_changed', function() { me.draw(); }),
    google.maps.event.addDomListener(this.div_, 'click', function() {
      if (me.get('clickable')) {
        google.maps.event.trigger(me, 'click');
      }
    })
  ];
};

// Implement onRemove
Label.prototype.onRemove = function() {
  this.div_.parentNode.removeChild(this.div_);
  // Label is removed from the map, stop updating its position/text.
  for (var i = 0, I = this.listeners_.length; i < I; ++i) {
    google.maps.event.removeListener(this.listeners_[i]);
  }
};

// Implement draw
Label.prototype.draw = function() {
  var projection = this.getProjection();
  var position = projection.fromLatLngToDivPixel(this.get('position'));  
  var div = this.div_;
  div.style.left = position.x + 'px';
  div.style.top = position.y + 'px';
  var visible = this.get('visible');
  div.style.display = visible ? 'block' : 'none';
  var clickable = this.get('clickable');
  this.span_.style.cursor = clickable ? 'pointer' : '';
  var zIndex = this.get('zIndex');
  div.style.zIndex = zIndex;
  this.span_.innerHTML = this.get('text').toString();
};

/***********************************************************/
var delay = 100;
  var infowindow = new google.maps.InfoWindow();
 // var latlng = new google.maps.LatLng(21.0000, 78.0000);
 var latlng = new google.maps.LatLng(40.4333, 3.7000);
   var gmarkers = [];
  var markerTitles =[];
  var highestZIndex = 0;
   var agent = "default";  
    var zoomControl = true;
      // detect browser agent
      $(document).ready(function(){
        if(navigator.userAgent.toLowerCase().indexOf("iphone") > -1 || navigator.userAgent.toLowerCase().indexOf("ipod") > -1) {
          agent = "iphone";
          zoomControl = false;
        }
        if(navigator.userAgent.toLowerCase().indexOf("ipad") > -1) {
          agent = "ipad";
          zoomControl = false;
        }
      });
  
  var mapOptions = {
    zoom: 2,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    zoomControl: zoomControl,
	zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.LEFT_CENTER
          }
  };
  var geocoder = new google.maps.Geocoder(); 
  var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
  //var map = new google.maps.Map($(".bigger-map"), mapOptions);
  zoomLevel = map.getZoom();  
   google.maps.event.addListener(map, 'zoom_changed', function() {
          zoomLevel = map.getZoom();
          if(zoomLevel <= 20) {
            $(".marker_label").css("display", "none");
          } else {
            $(".marker_label").css("display", "inline");
          }
        });
  
  var bounds = new google.maps.LatLngBounds();
  var circle = null; 