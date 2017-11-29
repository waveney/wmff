  var Dragged;

/* ids are
	E$CurOrder::	Empty box
	N$CurOrder::	Note id
	I$CurOrder::	Note input
	S$CurOrder:$tt:$id	Grid with entry
	M$CurOrder:$tt:$id	Note with entry
	J$CurOrder:$tt:$id	Note input entry
	Z0:Side:$id	Side Side
	Z0:Act:$id	Act Side
	Z0:Other:$id	Other Side
*/

  function SetGrid(srcId,dstId) {
    var src = document.getElementById(srcId);
    var dst = document.getElementById(dstId);
    var dstmtch = dstId.match(/(.)(\d*):(.*):(\d*)/);
    var srcmtch = dstId.match(/(.)(\d*):(.*):(\d*)/);
    var Txt = src.innerHTML;

    $("#InformationPane").load("beupdate.php", "D=" + dstId + "&S=" + srcId + "&EV=" + $("#EVENT").text() + "&E=" + 
			$("input[type='radio'][name='EInfo']:checked").val()	);
    if (dstmtch[1] == 'Z') {
      src.innerHTML = '';
      src.setAttribute('id',0); // fix if used
    } else {
      dst.innerHTML = Txt;
      dst.setAttribute('id',0);
    }
  }

  function UpdateInfo() {
    $("#InformationPane").load("dpupdate.php", "E=" + $("input[type='radio'][name='EInfo']:checked").val() );  // Yes that is dp not be
  }

  function ShowThing() {
    debugger;
    var Thing = $("input[type='radio'][name='ShowThings']:checked").val() ;
    $("#SideSide").hide();
    $("#ActSide").hide();
    $("#OtherSide").hide();
    if (Thing == 0) {
      $("#SideSide").show();
    } else if (Thing == 1) {
      $("#ActSide").show();
    } else {
      $("#OtherSide").show();
    }
  }

  $(document).ready(function() {
    $("#Grid").tableHeadFixer({'left':1});
    $("#ActSide").hide();
    $("#OtherSide").hide();
  } );

  function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
    Dragged = ev.target.id
  }

  function drop(ev) {
    ev.preventDefault();
    SetGrid(Dragged,ev.target.id);    
  }

  function dropgrid(ev) {
    ev.preventDefault();
    var srcId = Dragged;
    var dstId = ev.target.id;
    var src = document.getElementById(srcId);
    var dst = document.getElementById(dstId);
    var dstmtch = dstId.match(/(.)(\d*):(.*):(\d*)/);
    var srcmtch = srcId.match(/(.)(\d*):(.*):(\d*)/);
    var Txt = src.innerHTML;
    debugger;

    $("#InformationPane").load("beupdate.php", "D=" + dstId + "&S=" + srcId + "&EV=" + $("#EVENT").text() + "&E=" + 
			$("input[type='radio'][name='EInfo']:checked").val()	);
    if (srcmtch[1] == 'S') {
      src.innerHTML = '';
      src.setAttribute('id','E' + srcmtch[2] + '::');
      document.getElementById(srcmtch[3] + 'P' + srcmtch[4]).innerHTML = '';
      var nsrc = document.getElementById('J' + srcmtch[2] + ':' + srcmtch[3] + ':' + srcmtch[4]);
      var Note = nsrc.value;
      var nsrcp = document.getElementById('M' + srcmtch[2] + ':' + srcmtch[3] + ':' + srcmtch[4]);
      nsrcp.innerHTML = "<input type=text size=30 id=I" + srcmtch[2] + ":: value=''>";
      nsrcp.setAttribute('id','N' + srcmtch[2] + '::');
    } else {
      Note = ''
    }

    switch (dstmtch[1]) {
    case 'E':
      dst.innerHTML = Txt;
      dst.setAttribute('id','S' + dstmtch[2] + ':' + srcmtch[3] + ':' + srcmtch[4]);

      document.getElementById(srcmtch[3] + 'P' + srcmtch[4]).innerHTML = dstmtch[2];

      var ndst = document.getElementById('N' + dstmtch[2] + '::');
      ndst.innerHTML = "<input type=text size=30 id=J" + dstmtch[2] + ':' + srcmtch[3] + ':' + srcmtch[4] + " value='" + Note + "'>";
      ndst.setAttribute('id','M' + dstmtch[2] + ':' + srcmtch[3] + ':' + srcmtch[4]);
      break;
    case 'S':
      dst.innerHTML = Txt;
      dst.setAttribute('id','S' + dstmtch[2] + ':' + srcmtch[3] + ':' + srcmtch[4]);

      document.getElementById(dstmtch[3] + 'P' + dstmtch[4]).innerHTML = '';
      document.getElementById(srcmtch[3] + 'P' + srcmtch[4]).innerHTML = dstmtch[2];
      ndst = document.getElementById('N' + dstmtch[2] + ':' + dstmtch[3] + ':' + dstmtch[4]);
      ndst.innerHTML = "<input type=text size=30 id=J" + dstmtch[2] + ':' + srcmtch[3] + ':' + srcmtch[4] + " value='" + Note + "'>";
      ndst.setAttribute('id','M' + dstmtch[2] + ':' + srcmtch[3] + ':' + srcmtch[4]);
      break;
    defult:
      break;
    }
  }

// Remove from grid
  function dropside(ev) {
    ev.preventDefault();
    var srcId = Dragged;
    var src = document.getElementById(srcId);
    var srcmtch = srcId.match(/(.)(\d*):(.*):(\d*)/);

    $("#InformationPane").load("beupdate.php", "D=" + ev.target.id + "&S=" + srcId + "&EV=" + $("#EVENT").text() + "&E=" + 
			$("input[type='radio'][name='EInfo']:checked").val()	);
    switch (srcmtch[1]) {
    case 'S': 
      src.innerHTML = '';
      src.setAttribute('id','E' + srcmtch[2] + '::');
      break;
    case 'M' :
    case 'J' :
    case 'N' :
      var sfx = srcmtch[2] + ':' + srcmtch[3] + ':' + srcmtch[4];
      var osrc = document.getElementById('M' + sfx);
      osrc.innerHTML = '<input type=text size=30 oninput=newnote(event) id=I' + sfx + '>';
      osrc.setAttribute('id','N' + sfx);
      break;
    case 'Z' :
    default:
      break;
    }
  }

  function allow(ev) {
    var dstmtch = ev.target.id.match(/(.)(\d*):(.*):(\d*)/);
    if (dstmtch && ((dstmtch[1] == "E" && dstmtch[4] == 0 ) || dstmtch[1] == "Z")) ev.preventDefault();
  }

  function newnote(ev) {
    var id = ev.target.id;
    var note = ev.target.value;
    $.get("beupdatenote.php", {D:id, N:note, EV:$("#EVENT").text() } ).done( function(data) { 
//	    document.getElementById("InformationPane").innerHTML = data  // THis is for testing only
    });
  }


  function dispinfo(t,s) {
    $("#InfoPane").load("dpinfo.php", "S=" + s + "&T=" + t);
  }
