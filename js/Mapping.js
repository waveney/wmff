var map;
var markers = [];
var mtypes = [];

$.getJSON("/cache/mapptypes.json").done (function(json1) {
  $.each(json1,function(key,data) {
    mtypes[data.id] = data;
  })
})

function initMap() {
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
	      case 'D__': cont += 'Dance'; break;
	      case 'DM_': cont += 'Dance and Music'; break;
	      case 'DMO': cont += 'Dance, Music and other things'; break;
	      case 'D_O': cont += 'Dance and other things'; break;
	      case '_M_': cont += 'Music'; break;
	      case '_MO': cont += 'Music and other things'; break;
	      case '__O': cont += 'Other things'; break;
	      case '___': cont += 'many things'; break;
	    };
	  }
	  cont += '<p><a href=/int/VenueShow.php?v=' + data.id + '>More Info</a><p>Directions';
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

  google.maps.event.addListener(map, 'zoom_changed', controlOnZoom);
}

