// Tools for any page - will move many things here in time


// SelectAll/None for any page - 
function ToolSelectAll(e) {
  debugger;
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
