function blurbedit(ev) {
//  setTimeout( function(ev) {
   debugger;
   var stuff = document.getElementById('Blurb').value;
   var onefifty = stuff.substring(0,150);
   var m = onefifty.match(/^([\s\S]*?[.?!])\s/);
   var sent = (m ? m[1] : onefifty);
   document.getElementById('FirstBlurb').textContent = sent;   
//  }, 5000);
}

function nameedit(ev) {
  debugger;

//  if (document.getElementById("SName").value.length > 20 ) {
  var v = $("#SName").val();
  if (v.length > 20 ) {
    var r = v.substring(0,20);
    if ($('#ShortName').attr("hidden")) {
	$('#ShortName').val(r);
    }
    $(".ShortName").removeAttr("hidden");
  }
}

