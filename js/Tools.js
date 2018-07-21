// Tools for any page - will move many things here in time


// SelectAll/None for any page - 
function ToolSelectAll(e) {
  $(".SelectAllAble").prop("checked",$("#SelectAll").prop("checked"));
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


