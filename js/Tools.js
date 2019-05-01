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


function PCatSel(e) {
  $('[id^=MPC_').hide();
  var selectedOption = $("input:radio[name=PCAT]:checked").val()
  $('#MPC_' + selectedOption).show();
}

$(document).ready(function() {
  //caches a jQuery object containing the header element
  var header = $(".main-header");
  var dhead = header[0]; // jquery to dom
  var scroll = $(window).scrollTop();  
  if (scroll >= 1) header.addClass("fixedheader");
  
  $(window).scroll(function() {
    var scroll = $(window).scrollTop();
    if (scroll >= 1) {
      header.addClass("fixedheader");
    } else {
      header.removeClass("fixedheader");
  	}
  });
  dhead.addEventListener("mouseover",function() {
    header.addClass("fixedheader");
  });
  dhead.addEventListener("mouseout",function() {
    var scroll = $(window).scrollTop();
    if (scroll < 1) {
      header.removeClass("fixedheader");
  	}
  });

});

// Sticky menus for mobiles

var StickyTime;

function RemoveSticky() {
  $('.stick').removeClass('stick');  
}

function NoHoverSticky(e) {
  $('.active').removeClass('active');
}

function HoverSticky(e) {
  NoHoverSticky();
  RemoveSticky();
  e.currentTarget.lastElementChild.className += " active";
}

function NavStick(e) { // Toggle sticking of menus
  if (e.currentTarget.nextElementSibling.classList.contains('stick')) {
    RemoveSticky();
  } else {
    RemoveSticky();
    e.currentTarget.nextElementSibling.className += " stick";
    StickyTime = setTimeout(RemoveSticky,3000);
  }
}

function NavSetPosn(e,labl) {
  // Find Actual width of div labl, position child half of width to the left
  var lablwid = $("#MenuParent" + labl).outerWidth();
  $("#MenuChild" + labl).css({"margin-left":(-110+(lablwid/2)) })
}

function MenuResize() {
// Work out effective width
// if < Threshold 2 - hide level 2 elements
// Work out effective Width
// if < Threshold 1 then
  // Show Menu Icon
  // copy menus to menu icon
  // hide those that can be hidden
//  return;
//  debugger;
  var Ewidth = $(".Main-Header").width();
  var IconWidth = $(".header-logo").width();
  if (Ewidth > 1380 ) {  // Show all
    $(".MenuIcon").hide();
    $(".MenuMinor0").show();
    $(".MenuMinor1").show();
    $(".MenuMinor2").show();
    $("#MenuBars").css({"right":0,"width":(Ewidth-IconWidth-40)});
  } else {
    $(".MenuMinor2").hide();
    if (Ewidth < 1275) { // Show limited
      $(".MenuMinor1").hide();
      $("#MenuBars").css({"right":80, "width":(Ewidth-IconWidth-120)});
      if (Ewidth < 845) { // Show none
        $(".MenuMinor0").hide();
      } else {
        $(".MenuMinor0").show();
      }
      $(".MenuIcon").show();
    } else { // Show most
      $(".MenuIcon").hide();    
      $(".MenuMinor0").show();
      $(".MenuMinor1").show();
      $("#MenuBars").css({"right":0, "width":(Ewidth-IconWidth-40)});
    }
  }
}


$(document).ready(function() {
  MenuResize();
  window.addEventListener('resize',MenuResize);  
})


function ShowHoverMenu() {
//  debugger;
//  if (!MenuCopied) CopyHoverMenu();
//  $("#HoverContainer").show();
  $("#HoverContainer").addClass("Slide-Left");
  $(".MenuMenuIcon").hide();
  $(".MenuMenuClose").show();
  $(".MenuSubMenu").hide();
  $(".MenuSubMenuIcon").hide();
}

function CloseHoverMenu() {
//  $("#HoverContainer").hide();
  $("#HoverContainer").removeClass("Slide-Left");
  $(".MenuMenuIcon").show();
  $(".MenuMenuClose").hide();
}

function HoverDownShow(labl) {
  $("#HoverChild" + labl).toggle();
  $("#DownArrow" + labl).toggleClass("Flip");
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

function getDocHeight(doc) {
    doc = doc || document;
    // stackoverflow.com/questions/1145850/
    var body = doc.body, html = doc.documentElement;
    var height = Math.max( body.scrollHeight, body.offsetHeight, 
        html.clientHeight, html.scrollHeight, html.offsetHeight );
    return height;
}

function setIframeHeight(id) {
    var ifrm = document.getElementById(id);
    var doc = ifrm.contentDocument? ifrm.contentDocument: 
        ifrm.contentWindow.document;
    ifrm.style.visibility = 'hidden';
    ifrm.style.height = "10px"; // reset to minimal height ...
    // IE opt. for bing/msn needs a bit added or scrollbar appears
    ifrm.style.height = getDocHeight( doc ) + 4 + "px";
    ifrm.style.visibility = 'visible';
}

function AddLineUpHighlight(id) {
//  debugger;
  $('#LineUp' + id).addClass("LUHighlight");
//  var xx=1;
}

function RemoveLineUpHighlight(id) {
  $('#LineUp' + id).removeClass("LUHighlight");
}

function Set_MinHeight(p1,p2) {
//debugger;
  var ht = $(p1).height();
  $(p2).css({"min-height":ht});
  var a = 1;
}

var LoadStack = [];

function Register_Onload(fun,p1,p2) {
  LoadStack.push([fun,p1,p2]);
}

$(document).ready(function() {
//  debugger;
  if (!LoadStack) return;
  for (var f in LoadStack) {
    [fun,p1,p2] = LoadStack[f];
    fun(p1,p2);
  }
})

