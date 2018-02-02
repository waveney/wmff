
var Dragged ;
var nexthl = 0;
var hlights = [];

// Old format Le:t:l:s
// New format Lv:t:l (L = G,S) - data-d: L:e:s:d:w L=(N,S)
// 1st line data-e:e:d, every line data data-s:s ...?

// Big Change Needed
  function RemoveGrid(loc) {
// Drops usage count, clears content, changes id to no side    
    var Side=loc.getAttribute('data-d');
    var cur = $("#SideH"+Side).text();
    if (cur) { cur--; $("#SideH"+Side).text(cur); };
    loc.innerHTML='';
    loc.classList.remove('Side' + Side);
    if (hlights[Side]) loc.classList.remove(hlights[Side]);
    loc.removeAttribute('data-d');
    loc.removeAttribute('rowspan');
  }

// Big Change needed
  function UpdateGrid(dst,Side,text) {
// Increases usage count, enters content, change id to side
    var cur = $("#SideH"+Side).text();
    if (!cur) cur = 0;
    cur++;
    $("#SideH"+Side).text(cur); 
    dst.innerHTML=text;
    dst.classList.add('Side' + Side);
    if (hlights[Side]) dst.classList.add(hlights[Side]);
    dst.setAttribute('data-d',Side);

    var datw = $("#SideN"+Side).attr("data-w");
    if (datw) {
      var dstmtch = dst.id.match(/G:(\d*):(\d*):(\d*)/);
      if (dstmtch[3] == 0) {
        dst.setAttribute('rowspan',4);
        var gp = "G:" + dstmtch[1] + ":" + dstmtch[2] + ":";
        for (var i=1;i<4;i++) $("#" + gp + i).hide();
      }
    }
  }

// Big Change needed
  function SetGrid(src,dst,sand) {
    var dstmtch = dst.id.match(/G:(\d*):(\d*):(\d*)/);
    var Txt = src.innerHTML;

    var s = src.id.match(/SideN(\d*)/);
    if (s) {
      var SideNum = s[1]; 
    } else if (src.id.match(/G:(\d*):(\d*):(\d*)/)) {
      var SideNum = src.getAttribute("data-d");
      RemoveGrid(src);
    } 
    if (dstmtch) UpdateGrid(dst,SideNum,Txt);
    if (!sand) $("#InformationPane").load("dpupdate.php", "D=" + dst.id + "&S=" + src.id + "&I=" + SideNum + "&A=" + $("#DayId").text() + "&E=" + 
			$("input[type='radio'][name='EInfo']:checked").val()	);
  }

// Prob working new
  function UpdateInfo(cond) {
    $("#InformationPane").load("dpupdate.php", "E=" + $("input[type='radio'][name='EInfo']:checked").val() );
  }

// Working on New
  $(document).ready(function() {
    $("#Grid").tableHeadFixer({'left':1});
  } );

// Prob ok - changed ?? .id
  function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
    Dragged = ev.target
  }

// Prob ok - changed
  function drop(ev,sand) {
    ev.preventDefault();
    SetGrid(Dragged,ev.target,sand);    
  }

// Need to make work for non shared use
// Grey, Big = not ok,  data-d? (not = ok) - you have a hook to allow some large event adds using drop 
  function allow(ev) {
    var dat = ev.target.getAttribute("data-d");
    if (!dat) ev.preventDefault();
  }    

// Should work on New as unchanged
  function dispinfo(t,s) {
    $("#InfoPane").load("dpinfo.php", "S=" + s + "&T=" + t);
  }

// Works on New
  function highlight(id) {
    var oc=hlights[id];
    if (oc) {
      $('.'+oc).removeClass(oc);
      hlights[id]='';
    } else {
      $('.BGColour'+nexthl).removeClass('BGColour'+nexthl);
      $('.Side'+id).addClass('BGColour'+nexthl);
      hlights[id] = 'BGColour' + nexthl;
      if (nexthl++ >8) nexthl=0;
    }
  }

// New Code
  function UnhideARow(t) {
// search venues to find non hidden td, not wrapped, search lines to find hidden line
//  each venue, if line above not hidden unhide
  }
