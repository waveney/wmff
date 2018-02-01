
var Dragged ;
var nexthl = 0;
var hlights = [];

// Old format Le:t:l:s
// New format Lv:t:l (L = G,S) - data-d: L:e:s:d:w L=(N,S)
// 1st line data-e:e:d, every line data data-s:s ...?

// Big Change Needed
  function RemoveGrid(loc,dmatch) {
// Drops usage count, clears content, changes id to no side    
    var Side=dmatch[5];
    var cur = $("#SideH"+Side).text();
    if (cur) { cur--; $("#SideH"+Side).text(cur); };
    loc.innerHTML='';
    loc.classList.remove('Side' + Side);
    if (hlights[Side]) loc.classList.remove(hlights[Side]);
    loc.setAttribute('id',"G" + dmatch.slice(1,5).join(':')+':0' );
  }

// Big Change needed
  function UpdateGrid(dst,Side,dmatch,text) {
// Increases usage count, enters content, change id to side
    var cur = $("#SideH"+Side).text();
    if (!cur) cur = 0;
    cur++;
    $("#SideH"+Side).text(cur); 
    dst.innerHTML=text;
    var newid = "G" + dmatch.slice(1,5).join(':') +  ':' + Side;
    dst.classList.add('Side' + Side);
    if (hlights[Side]) dst.classList.add(hlights[Side]);
    dst.setAttribute('id',newid);
  }

// Big Change needed
  function SetGrid(src,dst,sand) {
    var srcid = src.id;
    var dstid = dst.id;
    var dstmtch = dstid.match(/G(\d*):(\d*):(\d*)/);
    var srcmtch;
    var Txt = src.innerHTML;

// Need to gain event num for drop - I think (or is vtl sufficient? - prob is)
// paras may need changing to pull from dat
    if (!sand) $("#InformationPane").load("dpupdate.php", "D=" + dstId + "&S=" + srcId + "&A=" + $("#DayId").text() + "&E=" + 
			$("input[type='radio'][name='EInfo']:checked").val()	);
//    if (dstmtch && dstmtch[5]>0) RemoveGrid(dst,dstmtch);
    var dat = src.getAttribute("data-d");
    var s = srcid.match(/SideN(\d*)/);
    if (s) {
      var SideNum = s[1]; 
    } else if (srcmtch = srcId.match(/G(\d*):(\d*):(\d*):(\d*):(\d*)/)) {
      var SideNum = srcmtch[5]; 
      RemoveGrid(src,srcmtch);
    } 
    if (dstmtch) UpdateGrid(dst,SideNum,dstmtch,Txt);
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

  }
