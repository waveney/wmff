var map;
var markers = [];
var mtypes = [];
var me;

$.getJSON("/cache/mapptypes.json").done (function(json1) {
  $.each(json1,function(key,data) {
    mtypes[data.id] = data;
  })
})

$(document).ready(function() {
//function initMap() {
  var Wimb = {lat: 50.800150, lng: -1.988000};

  map = new google.maps.Map(document.getElementById('map'), {
    center: Wimb,
    mapTypeId: 'hybrid',
    zoom: 16,
    });

  $.getJSON("/cache/mappoints.json").done ( function(json1) {
    var zoom=map.getZoom();
    $.each(json1, function(key, data) {
      var latLng = new google.maps.LatLng(data.lat, data.long); 
      var minz=16, maxz=30;
      if (data.imp != '0') {
        var mtch = data.imp.match(/(\d*)(-?)(\d*)?/);
	minz=mtch[1];
        if (mtch[3]) maxz=mtch[3]; 
      }  
      if (data.icon != 1) {
        var marker = new google.maps.Marker({
          position: latLng,
          title: data.name,
          importance: data.imp,
        });
	if (data.icon > 1) marker.setIcon("/images/icons/" + mtypes[data.icon].Icon);
        marker.setMap(map);
        marker.setVisible(zoom>=minz && zoom <= maxz);
        markers.push(marker);

	if (data.id < 1000000) { // Venue
	  var cont = '<h3>' + data.name + '</h3>';
	  if (data.image) cont += '<img src=' + data.image + ' class=mapimage><br>';
	  cont += (data.desc || '');
	  if (data.usage) {
	    cont += '<p>Venue for ';
	    switch (data.usage) {
	      case 'DMCO': cont += 'Dance, Music, Family and other things'; break;
	      case 'DMC_': cont += 'Dance, Music and Family'; break;
	      case 'DM_O': cont += 'Dance, Music and other things'; break;
	      case 'DM__': cont += 'Dance and Music'; break;
	      case 'D_CO': cont += 'Dance, Family and other things'; break;
	      case 'D_C_': cont += 'Dance and Family'; break;
	      case 'D__O': cont += 'Dance and other things'; break;
	      case 'D___': cont += 'Dance'; break;
	      case '_MCO': cont += 'Music, Family and other things'; break;
	      case '_MC_': cont += 'Music and Family'; break;
	      case '_M_O': cont += 'Music and other things'; break;
	      case '_M__': cont += 'Music'; break;
	      case '__CO': cont += 'Family and Other things'; break;
	      case '__C_': cont += 'Family'; break;
	      case '___O': cont += 'Other things'; break;
	      case '____': cont += 'many things'; break;
	    };
	  }
	  cont += '<p><a href=/int/VenueShow.php?v=' + data.id + '>More Info</a>';
//	  cont += '<p>Directions';
	  var infowindow = new google.maps.InfoWindow({
            content: cont
          });

          marker.addListener('click', function() {
            infowindow.open(map, marker);
          });
	}
      }

      if (data.icon == 1 || data.atxt) {
        var lbl = new MapLabel({
	  text: data.name,
	  position: latLng,
	  fontSize: data.atxt,
	  minZoom: minz,
	  maxZoom: maxz,
	  map: map,
	});
      }
    });
  });

  function controlOnZoom() {
    var zoom=map.getZoom();
    for (var i in markers) {
      var hide=true;
      var imp=markers[i].importance;
      if (imp != '0') {
        var mtch = imp.match(/(\d*)(-?)(\d*)?/);
        if (mtch[3]) {
	  hide = (zoom >= mtch[1] && zoom <= mtch[3]);
        } else {
          hide = (zoom >= mtch[1]);
        }
      } else { hide = (zoom >= 16) };
      markers[i].setVisible(hide);
    }
    if (zoom == 13) map.setMapTypeId('roadmap');
    if (zoom == 14) map.setMapTypeId('hybrid');
  }

  controlOnZoom();

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = { lat: position.coords.latitude, lng: position.coords.longitude };
      me = new google.maps.Marker({
	position: pos,
	icon: '/images/icons/me20.png',
        map: map,
      });
      setInterval(function() { 
        navigator.geolocation.getCurrentPosition(function(position) {
          var pos = { lat: position.coords.latitude, lng: position.coords.longitude };
	  me.setPosition(pos);
	})
      }, 10000);
    });
  }

  google.maps.event.addListener(map, 'zoom_changed', controlOnZoom);
});

