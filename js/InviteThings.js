// Things for various pages


// InviteDance

function ChangeInvite(ev) {
  debugger;
//  if (DoingTableSort) return;
  var id=ev.target.id;
  var snumm = id.match(/Invite(\d*)/);
  var snum = snumm[1];
  var iv = ev.target.value;
  var year = $("#Year").val();
  $("#InformationPane").load("setfields.php", "I=" + snum + "&O=Y&F=Invite&V=" + iv + "&Y=" + year);
}

function ReportTed(ev) {
  debugger;
//  if (DoingTableSort) return;
  var id=ev.target.id;
  var snumm = id.match(/(\d+)/);
  var snum = snumm[1];
  var year = $("#Year").val();
  $("#Vited" + snum).load("setfields.php", "I=" + snum + "&O=I&Y=" + year);
}

