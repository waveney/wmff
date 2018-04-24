
function setHeadRow() {
  var ww = window.innerWidth;
  var scale = Math.floor(ww*250/(550*2+864+20));
  if (scale>250) scale=250;
  if (ww > 800) $('#leftspon').removeAttr('hidden'); 
  if (ww > 1200) $('#rightspon').removeAttr('hidden');

  $('#HeadRow').css('max-height',scale);
  $('#leftspon').css('max-height',scale);
  $('#rightspon').css('max-height',scale);
  $('#HeadBan').css('max-height',scale);
}

$(document).ready(function() {
  setHeadRow();
  window.onresize = setHeadRow;
})
