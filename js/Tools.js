// Tools for any page - will move many things here in time


// SelectAll/None for any page - 
function ToolSelectAll(e) {
  $(".SelectAllAble").prop("checked",$("#SelectAll").prop("checked"));
}

var SelTools;

function ToolSelect(e,c) { // e= event, c = colnum
  debugger;
// 1 Parse SelTools if not already parsed
// 2 Find each check value
// 3 Go through each thing that is Selectable (see above) set if appropriate, clear otherwise

  if (!SelTools) {
    SelTools = [];
 //   $("#SelTools");// TODO
  }
  

}

// Sticky menus for mobiles

function NoHoverSticky(e) {
  $('.active').removeClass('active');
}

function HoverSticky(e) {
  $('.active').removeClass('active');
  e.currentTarget.lastElementChild.className += " active";
}

function PCatSel(e) {
  $('[id^=MPC_').hide();
  var selectedOption = $("input:radio[name=PCAT]:checked").val()
  $('#MPC_' + selectedOption).show();
}

function NavStick(e) { // Toggle sticking of menus
  if (e.currentTarget.nextElementSibling.classList.contains('stick')) {
    $('.stick').removeClass('stick');
  } else {
    $('.stick').removeClass('stick');
    e.currentTarget.nextElementSibling.className += " stick";
  }
}

var isAdvancedUpload = function() {
  var div = document.createElement('div');
  return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

function InvoiceCatChange(e,v) {
  debugger;
  $('.InvOrg1').hide();
  $('.InvOrg2').hide();
  if (v == 0) $('.InvOrg1').show();
  if (v == 1) $('.InvOrg2').show();
}

// Maintain id D for size siz form fld
function SetDSize(D,siz,fld) {
  var len = document.getElementById(fld).value.length;
  document.getElementById(D).innerHTML = "<b>(" + len + "/" + siz + ")</b>";
}


