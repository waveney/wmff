var VolClass = ['SC_Stew','SC_Setup','SC_Art','SC_Media'];
var VolExtra = [0,1,1,0];

function Update_VolClasses() {
  var Odays = false;
  for (var i=0; i<4; i++) {
    var vc = VolClass[i];
    if ($("[Name=" + vc +"]").is(":checked")) {  
      $("." +vc).show();
      if (VolExtra[i]) Odays = true;
    } else  {  $("." +vc).hide() }
  }
  if (Odays) { $(".SC_Days").show() } else { $(".SC_Days").hide() } 
}

$(document).ready(function() {
  Update_VolClasses();
} );


