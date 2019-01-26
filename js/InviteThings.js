// Things for various pages


// InviteDance

function ChangeInvite(ev) {

//  if (DoingTableSort) return;
  var id=ev.target.id;
  var snumm = id.match(/Invite(\d*)/);
  var snum = snumm[1];
  var iv = ev.target.value;
  var year = $("#Year").val();
  $("#InformationPane").load("setfields.php", "I=" + snum + "&O=Y&F=Invite&V=" + iv + "&Y=" + year);
}

function ReportTed(ev) {

//  if (DoingTableSort) return;
  var id=ev.target.id;
  var snumm = id.match(/(\d+)/);
  var snum = snumm[1];
  var year = $("#Year").val();
  $("#Vited" + snum).load("setfields.php", "I=" + snum + "&O=I&Y=" + year);
}

function ProformaSend(name,snum,label,link) {
  var year = $("#Year").val();
  if ($('#BespokeM').is(':visible')) {
    $("#DebugPane").load("sendproforma.php", "I=" + snum + "&N=" + name);
    $("#Vited" + snum).load("setfields.php", "I=" + snum + "&O=I&Y=" + year + "&L=" + label);
  } else {
//    var sname = $("#SideName" + snum).value;
    window.open((link + "?id=" + snum + "&N=" + name + "&L=" + label),"Bespoke Message " + snum);
    $("#Vited" + snum).load("setfields.php", "I=" + snum + "&O=J&Y=" + year + "&L=" + label);
  }
}

function Add_Bespoke() {
  $('.ProfButton').addClass('BespokeBorder');
  $('.Bespoke').toggle();
}

function Remove_Bespoke() {
  $('.BespokeBorder').removeClass('BespokeBorder');
  $('.Bespoke').toggle();
}
