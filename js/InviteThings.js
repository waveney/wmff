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
  if (iv != 3 && iv != 0) {
    $("#Invie" + snum).show();
  } else $("#Invie" + snum).hide();
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

function ProformaSend(name,snum,label,link,AlwaysBespoke=0,AltEmail='',UpdateId='') {
  var year = $("#Year").val();
  if (UpdateId == '') UpdateId = "Vited" + snum;
  
  if ($('#BespokeM').is(':visible') && (AlwaysBespoke == 0)) {
    $("#DebugPane").load("sendproforma.php", "I=" + snum + "&N=" + name +"&E=" +AltEmail);
    $("#" + UpdateId).load("setfields.php", "I=" + snum + "&O=J&Y=" + year + "&L=" + label, function() {$("#Vited" + snum).scrollTop(1E6+ProformasSent*100)});
  } else {
    var newwin = window.open((link + "?id=" + snum + "&N=" + name + "&L=" + label + "&E=" + AltEmail),"Bespoke Message " + snum);
    newwin.onbeforeunload = function(){
      setTimeout(function(){$("#" + UpdateId).load("setfields.php", "I=" + snum + "&O=R", function() {
       $("#" + UpdateId).scrollTop(1E6+ProformasSent*100)})
    },500)}
  }

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

