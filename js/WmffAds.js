
function setHeadRow() {
//  debugger;
  var ww = window.innerWidth;
  var scale = Math.floor(ww*250/(550*2+864+20+25));
  if (scale>250) scale=250;

  if (ww < 1000) {
    if (ww < 500) {
      scale = 250
    } else {
      scale = Math.floor(ww*250/(550+864+25+25));
    }
  }

  $('#HeadRow').css('max-height',scale);
  $('#leftspon').css('max-height',scale);
  $('#rightspon').css('max-height',scale);
  $('#HeadBan').css('max-height',scale);

  $('#leftspon').hide();
  $('#rightspon').hide();
  
  if (ww > 500) $('#leftspon').show();
  if (ww > 1000) $('#rightspon').show();
}

$(document).ready(function() {
  setHeadRow();
  window.onresize = setHeadRow;
})
