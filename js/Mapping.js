var map;
var markers = [];
var mtypes = [];
var me;
var direct = navigator.geolocation;
var dirDisp;
var dirServ;
var MapFeatures;
var lastwin;
var mtypready = 0;
var docready = 0;
var gmap;
var DSrequest;
var Wimb = {lat: 50.800150, lng: -1.988000};
var MapDataDate = 0;


$.getJSON("/cache/mapptypes.json?" + MapDataDate).done (function(json1) {
  $.each(json1,function(key,data) {
    mtypes[data.id] = data;
  })
  mtypready = 1;
  if (mtypready == 1 && docready == 1) initMap();
})

function CloseDir() {
//  debugger;

}

function SetTravelMode(Nmode) {
//  debugger;
  DSrequest.travelMode = Nmode; 
}

function ShowDirect(MarkId) { // Open directions window from current loc (me) to the given Marker
//  debugger;
    var zoom = map.getZoom();
    if (!dirServ) dirServ = new google.maps.DirectionsService();
//      suppressMarkers: true,
    if (!dirDisp) {
      dirDisp = new google.maps.DirectionsRenderer();
      dirDisp.setMap(map);
    }
    DSrequest = {
      origin: (direct && me && me.position)?me.position:Wimb,
      destination: markers[MarkId].position,
      travelMode: (zoom < 15?'DRIVING':'WALKING'),
      unitSystem: 1, //IMPERIAL
    };
    dirServ.route(DSrequest, function(response, status) {
      if (status == 'OK') {
        dirDisp.setDirections(response);
      }
    });
  
    dirDisp.setPanel(document.getElementById('Directions'));
    if (markers[MarkId].dirExtra) {
      $('#Directions').after("<hr>Please ignore the last part of Google's instructions:<p>" + markers[MarkId].dirExtra);
    }
    var ht = $('#map').height();
    var wi = $('#map').width();
    if (wi < 400) {
      $('#map').css('max-height',Math.floor(ht*.7));
      $('#DirPane').css('max-height',Math.floor(ht*.28));
    } else {
      $('#map').css('width','70%');
      $('#DirPaneWrap').css({"width":"28%","height":ht,"max-height":ht,'float':'right'});
      $('#DirPane').css({"height":ht,"max-height":ht});
    }
}

function initMap() {
//  debugger;
  var MapLat = +$('#MapLat').val();
  var MapLong = +$('#MapLong').val();
  var MapZoom = +$('#MapZoom').val();
  MapFeatures = +$('#MapFeat').val();
  MapDataDate = +$('#MapDataDate').val();
  var Mapf = MapFeatures;
  var customStyled = [{
    featureType: "all",
    elementType: "labels",
    stylers: [
      { visibility: ((Mapf == 1)?"on":"off") }
    ]
  }];
  var Center = ((MapLat == 0 && MapLong == 0)?Wimb:{lat: MapLat, lng: MapLong});

  gmap = map = new google.maps.Map(document.getElementById('map'), {
    center: Center,
    mapTypeId: (MapZoom>13?'hybrid':'roadmap'),
    zoom: MapZoom,
    styles: customStyled,
    });

  $.getJSON("/cache/mappoints.json?" + MapDataDate).done ( function(json1) {
    var zoom=map.getZoom();
    $.each(json1, function(key, data) {
      var latLng = new google.maps.LatLng(data.lat, data.long); 
      var minz=16, maxz=30;
      if (data.imp != '0') {
        var mtch = data.imp.match(/(\d*)(-?)(\d*)?/);
        minz=mtch[1];
        if (mtch[3]) maxz=mtch[3]; 
      }
      if ((MapFeatures == 2) && (data.id < 1000000)) return;
      if ((MapFeatures == 3) && (!data.usage || !data.usage.match(/^D/))) return;
      if ((MapFeatures == 4) && (data.icon != 3) && (data.icon != 5)) return; // Car Parks
      if ((MapFeatures == 5) && (!data.usage || !data.usage.match(/^.M/))) return;
      if ((MapFeatures == 6) && (!data.usage || !data.usage.match(/^...C/))) return;
      if ((MapFeatures == 7) && (!data.usage || !data.usage.match(/^..F/))) return;
      if ((MapFeatures == 8) && (!data.usage || !data.usage.match(/^.....Y/))) return;
      if (data.icon != 1) { // text
        var marker = new google.maps.Marker({
          position: latLng,
          title: data.name,
          importance: ((MapFeatures == 4)?"15-36":data.imp),
          dirExtra:data.extra,
        });
        if (MapFeatures == 4) minz=15;
        if (data.icon > 1 && mtypes[data.icon].Icon) marker.setIcon("/images/icons/" + mtypes[data.icon].Icon);
        marker.setMap(map);
        marker.setVisible(zoom>=minz && zoom <= maxz);
        markers[data.id] = marker;
        if (data.id < 1000000) data.atxt = data.name;

        if (MapFeatures && (data.id < 1000000 || data.link != '' || data.direct)) { 
          var cont = '<div class=MapInfo><h3>' + data.name + '</h3>';
          if (data.image) cont += '<img src=' + data.image + ' class=mapimage><br>';
          cont += (data.desc || '');
          if (data.usage && data.usage != '_____') {
            cont += '<p>Venue for ' + data.used4;
          }
          if (data.id < 1000000) cont += '<p><a href=/int/VenueShow.php?v=' + data.id + '>More Info</a>&nbsp; &nbsp;';
          if (data.link) cont += '<p><a href=' + data.link + '>More Info</a>';
          if (data.id <1000000 || data.direct == 1) cont += '<span style="float:right;"><a onclick=ShowDirect(' + data.id + ')>Directions</a></span>';
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

  if ((MapFeatures == 1) && direct) {
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

