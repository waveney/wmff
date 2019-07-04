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
  if (iv != 3) $("#Invie" + snum).show();
}

function ReportTed(ev) {

//  if (DoingTableSort) return;
  var id=ev.target.id;
  var snumm = id.match(/(\d+)/);
  var snum = snumm[1];
  var year = $("#Year").val();
  $("#Vited" + snum).load("setfields.php", "I=" + snum + "&O=I&Y=" + year);
}

var ProformasSent = 1;

function ProformaSend(name,snum,label,link) {
  var year = $("#Year").val();
  
  if ($('#BespokeM').is(':visible')) {
    $("#DebugPane").load("sendproforma.php", "I=" + snum + "&N=" + name);
  } else {
    window.open((link + "?id=" + snum + "&N=" + name + "&L=" + label),"Bespoke Message " + snum);
  }
  $("#Vited" + snum).load("setfields.php", "I=" + snum + "&O=J&Y=" + year + "&L=" + label, function() {$("#Vited" + snum).scrollTop(1E6+ProformasSent*100)});

  ProformasSent++;
}

function Add_Bespoke() {
  $('.ProfButton').addClass('BespokeBorder');
  $('.Bespoke').toggle();
}

function Remove_Bespoke() {
  $('.BespokeBorder').removeClass('BespokeBorder');
  $('.Bespoke').toggle();
}

  $(document).ready(function() {
    Add_Bespoke();
    $('.scrollableY').scrollTop(1E6);
  } );

