function blurbedit(ev) { // Not currently used
//  setTimeout( function(ev) {
   var stuff = document.getElementById('Blurb').value;
   var onefifty = stuff.substring(0,150);
   var m = onefifty.match(/^([\s\S]*?[.?!])\s/);
   var sent = (m ? m[1] : onefifty);
   document.getElementById('FirstBlurb').textContent = sent;   
//  }, 5000);
}

function nameedit(ev) {

//  if (document.getElementById("Name").value.length > 20 ) {
  var v = $("#Name").val();
  if (v.length > 20 ) {
    var r = v.substring(0,20);
    if ($('#ShortName').attr("hidden")) {
	$('#ShortName').val(r);
    }
    $(".ShortName").removeAttr("hidden");
  }
}

function ShowAdv(ev) {
  if ($('#ShowMore').text() == 'More features') {
    $('.Adv').show();
    $('#ShowMore').text('Less features');
  } else {
    $('.Adv').hide();
    $('#ShowMore').text('More features');
  }
}

function updateimps() {
//  debugger;
  var imps=0;
  if (document.getElementsByName('Mobile')[0].value.length > 8) imps++;
  if (document.getElementsByName('Address')[0].value.length > 12) imps++;
  if (document.getElementsByName('Performers')[0] != undefined) if (document.getElementsByName('Performers')[0].value > 0) imps++;
  if (document.getElementsByName('Ignored')[0] != undefined) if (document.getElementsByName('Ignored')[0].checked) imps++;

  if (document.getElementById('ImpC') != undefined) document.getElementById('ImpC').innerHTML = imps;
}

function AgentChange(ev) {
//  debugger;
  if ($("#AgentDetail").length) {
    var txt = ( $("[Name=HasAgent]").is(":checked"))? 'Direct Contact': 'Contact';
    document.getElementById('ContactLabel').innerHTML = txt;
    if ($("[Name=HasAgent]").is(":checked")) {
      $("#AgentDetail").show();
      document.getElementById("Help4Contact").title = "Direct Performer Contact Name";
    } else {
      $("#AgentDetail").hide();
      document.getElementById("Help4Contact").title = "Main Contact Name";
    }
  }
}

$(document).ready(function() {
  $(".Adv").hide();

  if(! $("[Name=Fri]").is(":checked")) $('.ComeFri').hide();
  if(! $("[Name=Sat]").is(":checked")) $('.ComeSat').hide();
  if(! $("[Name=Sun]").is(":checked")) $('.ComeSun').hide();
  updateimps();
  AgentChange();
} );

function ComeSwitch(ev) {
  var day =ev.target.name;
  if($("[Name=" + day + "]").is(":checked")) {
    $(".Come" + day).show();
    var come = document.getElementById('Coming_states');
    come.value = 2;
  } else {
    $(".Come" + day).hide();
  }
}

function CopyAndSubmit(name) {
  document.getElementById(name + 'Upload').value = document.getElementById(name + 'Form').value;
  document.forms[name].submit();
}

function setStagePA(ev) {
  if($("#StagePAtext").is(":checked")) {
    $("#StagePAtextF").show();
    $("#StagePAFileF").hide();
    $("#StagePA").text("");
  } else {
    $("#StagePAtextF").hide();
    $("#StagePAFileF").show();
    $("#StagePA").text("@@FILE@@");
  }
}

function PASpecChanged(ev) {
  debugger;

}

function AddBandRow(BPerR) {
//  debugger;
  var row=0;
  while (document.getElementById("BandRow" + row)) row++;
  var newrow = "<tr id=BandRow" + row + "><td>";
  for (var i=0;i<BPerR;i++) { 
    newrow += "<td><input name=BandMember" + (BPerR*row+i) + ":0 type=text size=16 onchange=BandChange(event)>";
  };
  newrow += "</tr>";
  $("#AddHere").before(newrow);
//  document.getElementById("BandMemRow1").rowSpan(row+1);
  return false;
}


function BandChange(ev) { 
}

function SetTradeType(p,c,i,r,d,dc) {
//  debugger;
  if (p) { $('.PublicHealth').show() } else { $('.PublicHealth').hide() };
  if (c) { $('.Charity').show() } else { $('.Charity').hide() };
  $('#TTDescription').text(d);
  $('#TTDescription').css('background',dc);
}
      
function PowerChange(t,i) {
//  debugger;
  if (t!=2) { 
    $('#Power' + i).val('') 
  } else { 
    $('#PowerTypeRequest' + i).attr('checked',true) 
  }
}
