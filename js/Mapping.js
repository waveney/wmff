var map;
var markers = [];
var mtypes = [];
var me;
var direct = navigator.geolocation;
var dirDisp;
var dirServ;
var lastwin;
var mtypready = 0;
var docready = 0;


$.getJSON("/cache/mapptypes.json").done (function(json1) {
  $.each(json1,function(key,data) {
    mtypes[data.id] = data;
  })
  mtypready = 1;
  if (mtypready == 1 && docready == 1) initMap();
})

function ShowDirect(MarkId) { // Open directions window from current loc (me) to the given Marker
  if (!dirServ) dirServ = new google.maps.DirectionsService();
//      suppressMarkers: true,
  if (!dirDisp) {
    dirDisp = new google.maps.DirectionsRenderer();
    dirDisp.setMap(map);
  }
  var request = {
      origin: me.position,
      destination: markers[MarkId].position,
      travelMode: ((map.getZoom()) < 15?'DRIVING':'WALKING'),
      unitSystem: 1, //IMPERIAL

  };
  dirServ.route(request, function(response, status) {
    if (status == 'OK') {
      dirDisp.setDirections(response);
    }
  });
  
  dirDisp.setPanel(document.getElementById('Directions'));
  if ($(window).width() < 1000) {
    $('#map').css('height','70%');
    $('#DirPane').css('height','30%');
  } else {
    $('#map').css('width','70%');
    $('#DirPane').css('float','right');
    $('#DirPane').css('width','28%');
  }
  $('#DirPaneTop').html('<button onclick=SetTravelMode("DRIVING")>Drive</button> <button onclick=SetTravelMode("WALKING")>Walk</button>');
}

function initMap() {
  debugger;
  var Wimb = {lat: 50.800150, lng: -1.988000};
  var MapLat = +$('#MapLat').val();
  var MapLong = +$('#MapLong').val();
  var MapZoom = +$('#MapZoom').val();
  var Center = ((MapLat == 0 && MapLong == 0)?Wimb:{lat: MapLat, lng: MapLong}),

  map = new google.maps.Map(document.getElementById('map'), {
    center: Center,
    mapTypeId: 'hybrid',
    zoom: MapZoom,
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
	if (data.icon > 1 && mtypes[data.icon].Icon) marker.setIcon("/images/icons/" + mtypes[data.icon].Icon);
        marker.setMap(map);
        marker.setVisible(zoom>=minz && zoom <= maxz);
        markers[data.id] = marker;

	if (data.id < 1000000 || data.link != '' || data.direct == 1) { // Venue
	  var cont = '<div class=MapInfo><h3>' + data.name + '</h3>';
	  if (data.image) cont += '<img src=' + data.image + ' class=mapimage><br>';
	  cont += (data.desc || '');
	  if (data.usage && data.usage != '_____') {
	    cont += '<p>Venue for ';
	    switch (data.usage) {
	      case 'DMFCO': cont += 'Dance, Music, Family, Craft and other things'; break;
	      case 'DMFC_': cont += 'Dance, Music, Family and Craft'; break;
	      case 'DMF_O': cont += 'Dance, Music, Family and other things'; break;
	      case 'DMF__': cont += 'Dance, Music and Family'; break;
	      case 'DM_CO': cont += 'Dance, Music, Craft and other things'; break;
	      case 'DM_C_': cont += 'Dance, Music and Craft'; break;
	      case 'DM__O': cont += 'Dance, Music and other things'; break;
	      case 'DM___': cont += 'Dance and Music'; break;
	      case 'D_FCO': cont += 'Dance, Family, Craft and other things'; break;
	      case 'D_FC_': cont += 'Dance, Family and Craft'; break;
	      case 'D_F_O': cont += 'Dance, Family and other things'; break;
	      case 'D_F__': cont += 'Dance and Family'; break;
	      case 'D__CO': cont += 'Dance, Craft and other things'; break;
	      case 'D__C_': cont += 'Dance and Craft'; break;
	      case 'D___O': cont += 'Dance and other things'; break;
	      case 'D____': cont += 'Dance'; break;
	      case '_MFCO': cont += 'Music, Family, Craft and other things'; break;
	      case '_MFC_': cont += 'Music, Family and Craft'; break;
	      case '_MF_O': cont += 'Music, Family and other things'; break;
	      case '_MF__': cont += 'Music and Family'; break;
	      case '_M_CO': cont += 'Music, Craft and other things'; break;
	      case '_M_C_': cont += 'Music and Craft'; break;
	      case '_M__O': cont += 'Music and other things'; break;
	      case '_M___': cont += 'Music'; break;
	      case '__FCO': cont += 'Family, Craft and other things'; break;
	      case '__FC_': cont += 'Family and Craft'; break;
	      case '__F_O': cont += 'Family and other things'; break;
	      case '__F__': cont += 'Family'; break;
	      case '___CO': cont += 'Craft and other things'; break;
	      case '___C_': cont += 'Craft'; break;
	      case '____O': cont += 'Other things'; break;
	      case '_____': cont += 'many things'; break;
	    };
	  }
	  if (data.id < 1000000) cont += '<p><a href=/int/VenueShow.php?v=' + data.id + '>More Info</a>';
	  if (data.link) cont += '<p><a href=' + data.link + '>More Info</a>';
	  if (direct && (data.id <1000000 || data.direct == 1)) cont += '<p><a onclick=ShowDirect(' + data.id + ')>Directions</a>';
          cont += '</div>';
	  var infowindow = new google.maps.InfoWindow({
            content: cont,
	    zIndex: 2000,
          });

          marker.addListener('click', function() {
	    if (lastwin) lastwin.close();
            infowindow.open(map, marker);
	    lastwin = infowindow;
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
	  zIndex:1000,
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

  debugger;
  if (direct) {
    direct.getCurrentPosition(function(position) {
      var pos = { lat: position.coords.latitude, lng: position.coords.longitude };
      me = new google.maps.Marker({
	position: pos,
	icon: '/images/icons/me20.png',
        map: map,
      });
      setInterval(function() { 
        direct.getCurrentPosition(function(position) {
          var pos = { lat: position.coords.latitude, lng: position.coords.longitude };
	  me.setPosition(pos);
	})
      }, 10000);
    });
  }

  google.maps.event.addListener(map, 'zoom_changed', controlOnZoom);
}

$(document).ready(function() {
  docready = 1;
  if (mtypready == 1 && docready == 1) initMap();
})

