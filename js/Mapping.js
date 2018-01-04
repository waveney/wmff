var map;
var markdata;

function initMap(lat,lng,zm) {
  var Wimb = {lat: 50.800150, lng: -1.988000};

  map = new google.maps.Map(document.getElementById('map'), {
    center: Wimb,
    mapTypeId: 'hybrid',
    zoom: 16,
//    zoom_changed: ,
    });

  var marker = new google.maps.Marker({
    position: Wimb,
    label: '!',
    map: map
  });

  $.getJSON("/cache/mappoints.json").done ( function(json1) {
    $.each(json1, function(key, data) {
      var latLng = new google.maps.LatLng(data.lat, data.long); 
      var marker = new google.maps.Marker({
        position: latLng,
        title: data.name,
        importance: data.imp,
        iconforfuture: data.icon,
      });
      marker.setMap(map);
    });
  });
}

