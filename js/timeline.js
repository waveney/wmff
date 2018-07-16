// Code for handling timeline stuff

var TaskIds = ['OpenTasks', 'NextMonth', 'OverdueTasks', 'CompleteTasks', 'AllTasks'];

// All entries have class TL
function TL_ShowHide() {
//  debugger;
  var You = $("#TasksYou").hasClass("PurpButton");

  var Tsks = '';
  
  if      ($("#OpenTasks").hasClass("PurpButton")) Tsks = 'Open'
  else if ($("#NextMonth").hasClass("PurpButton")) Tsks = 'Month'
  else if ($("#CompleteTasks").hasClass("PurpButton")) Tsks = 'Complete'
  else if ($("#OverdueTasks").hasClass("PurpButton")) Tsks = 'Overdue'
  else if ($("#AllTasks").hasClass("PurpButton")) Tsks = 'All';
  // More
  
  $(".TL").each(function(index) {
//    debugger;
    var Thide=true;
    switch (Tsks) {
    case 'Open' : 
    default :
      if ( $(this).hasClass("TL_OPEN")) Thide=false;
      break;
    case 'Month' :
      if ( $(this).hasClass("TL_MONTH")) Thide=false;
      break;
    case 'Complete' : 
      if ( $(this).hasClass("TL_COMPLETE")) Thide=false;
      break;
    case 'Overdue' : 
      if ( $(this).hasClass("TL_OVERDUE")) Thide=false;
      break;
    case 'All' :
      Thide=false;
      break;
    }
    
    if (You && $(this).hasClass("TL_EVERYONE")) Thide=true;
    if (Thide) {
      $(this).hide();
    } else {
      $(this).show();
    }
  });
}

function SetPurple(id) {
  TaskIds.forEach(function(val){
    if (val == id) {
      $("#" + val).removeClass("PurpSelect");
      $("#" + val).addClass("PurpButton");
    } else {
      $("#" + val).removeClass("PurpButton");
      $("#" + val).addClass("PurpSelect");
    }
  });
}

function TLSelect(id) {
//  debugger;
  switch (id) {
  case 'DataLow':
    $('.FullD').hide();
    $("#DataLow").addClass("PurpButton");
    $("#DataLow").removeClass("PurpSelect");
    $("#DataHigh").addClass("PurpSelect");
    $("#DataHigh").removeClass("PurpButton");
    break;
  case 'DataHigh':
    $('.FullD').show();
    $("#DataLow").removeClass("PurpButton");
    $("#DataLow").addClass("PurpSelect");
    $("#DataHigh").removeClass("PurpSelect");
    $("#DataHigh").addClass("PurpButton");
    break;
    
  case 'TasksYou' :
    $("#TasksYou").addClass("PurpButton");
    $("#TasksYou").removeClass("PurpSelect");
    $("#TasksAll").addClass("PurpSelect");
    $("#TasksAll").removeClass("PurpButton");
    TL_ShowHide();
    break;
  case 'TasksAll' :
    $("#TasksYou").removeClass("PurpButton");
    $("#TasksYou").addClass("PurpSelect");
    $("#TasksAll").removeClass("PurpSelect");
    $("#TasksAll").addClass("PurpButton");
    TL_ShowHide();
    break;
    
  case 'OpenTasks' : // Hide on all - think about who select effects
  case 'NextMonth' :
  case 'CompleteTasks' :
  case 'OverdueTasks' :
  case 'AllTasks' :      
    SetPurple(id);
    TL_ShowHide();
    break;
  }
}

$(document).ready(function() {
  $(".slider").each(function(){
//     debugger;
     var tlid = (this.id.match(/(\D*)(\d*)$/))[2];
     var val = $('#Progress' + tlid).val();
     var ival = val;
     if (val == "") val = $('#Progress' + tlid).html();
     $(this).slider({
      step: 5,
      range: "min",
      value: val,
      slide: function( event, ui ) {
 //     debugger;
        var tlid = (this.id.match(/(\D*)(\d*)$/))[2];
        if (ival == "") {
          $('#Progress' + tlid).html(ui.value);
        } else {
          $('#Progress' + tlid).val(ui.value);
        }
        if (!tlid) tlid = $('#TLid').val();
        $.ajax("setfields.php?O=TP&I=" + tlid + "&V=" + ui.value );
      }, 
    });
  });
});


