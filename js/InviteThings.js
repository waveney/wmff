// Things for various pages


// InviteDance

function ChangeInvite(ev) {
  debugger;
  var id=ev.target.id;
  var snumm = id.match(/Invite(\d*)/);
  var snum = snumm[1];
  var iv = ev.target.value;
  $("#InformationPane").load("setfields.php", "I=" + snum + "&O=Y&F=Invite&V=" + iv );
}

function ReportTed(ev) {
  debugger;
  var id=ev.target.id;
  var snumm = id.match(/(\d+)/);
  var snum = snumm[1];
  $("#Vited" + snum).load("setfields.php", "I=" + snum + "&O=I" );
}

