
var Dragged ;
var nexthl = 0;
var hlights = [];


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

  function SetGrid(srcId,dstId,sand) {
    var src = document.getElementById(srcId);
    var dst = document.getElementById(dstId);
    var dstmtch = dstId.match(/G(\d*):(\d*):(\d*):(\d*):(\d*)/);
    var srcmtch;
    var Txt = src.innerHTML;

    if (!sand) $("#InformationPane").load("dpupdate.php", "D=" + dstId + "&S=" + srcId + "&A=" + $("#DayId").text() + "&E=" + 
			$("input[type='radio'][name='EInfo']:checked").val()	);
    if (dstmtch && dstmtch[5]>0) RemoveGrid(dst,dstmtch);
    var s = srcId.match(/SideN(\d*)/);
    if (s) {
      var SideNum = s[1]; 
    } else if (srcmtch = srcId.match(/G(\d*):(\d*):(\d*):(\d*):(\d*)/)) {
      var SideNum = srcmtch[5]; 
      RemoveGrid(src,srcmtch);
    } 
    if (dstmtch) UpdateGrid(dst,SideNum,dstmtch,Txt);
  }

  function UpdateInfo(cond) {
    $("#InformationPane").load("dpupdate.php", "E=" + $("input[type='radio'][name='EInfo']:checked").val() );
  }

  $(document).ready(function() {
    sleep(5000);
    $("#Grid").tableHeadFixer({'left':1});
  } );

  function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
    Dragged = ev.target.id
  }

  function drop(ev,sand) {
    ev.preventDefault();
    SetGrid(Dragged,ev.target.id,sand);    
  }

// Need to make work for non shared use
  function allow(ev) {
    var dstmtch = ev.target.id.match(/G(\d*):(\d*):(\d*):(\d*):(\d*)/);
    if (!dstmtch || dstmtch[5] == 0) ev.preventDefault();
  }    

  function dispinfo(t,s) {
    $("#InfoPane").load("dpinfo.php", "S=" + s + "&T=" + t);
  }

  function highlight(id) {
    debugger;
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

