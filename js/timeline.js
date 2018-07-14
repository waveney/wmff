// Code for handling timeline stuff

function TLSelect(id) {
  debugger;
  switch (id) {
  case 'DataLow':
    $('.FullD').hide();
    $("#DataLow").removeClass("PurpButton");
    $("#DataLow").addClass("PurpSelect");
    $("#DataHigh").removeClass("PurpSelect");
    $("#DataHigh").addClass("PurpButton");
    break;
  case 'DataHigh':
    $('.FullD').show();
    $("#DataLow").addClass("PurpButton");
    $("#DataLow").removeClass("PurpSelect");
    $("#DataHigh").addClass("PurpSelect");
    $("#DataHigh").RemoveClass("PurpButton");
    break;
  case 'TasksYou' :
    $('.TL_EVERYONE').hide();
    $("#TasksYou").removeClass("PurpButton");
    $("#TasksYou").addClass("PurpSelect");
    $("#TasksAll").removeClass("PurpSelect");
    $("#TasksAll").addClass("PurpButton");
    break;
  case 'TasksAll' :
    $('.TL_EVERYONE').show();
    $("#TasksYou").addClass("PurpButton");
    $("#TasksYou").removeClass("PurpSelect");
    $("#TasksAll").addClass("PurpSelect");
    $("#TasksAll").RemoveClass("PurpButton");
    break;
  case 'OpenTasks' : // Hide on all - think about who select effects
    $('.TL_OPEN').show();
    break;
  case 'NextMonth' :
    $('.TL_OPEN').hide();
    $('.TL_MONTH').show();
    break;
  case 'OverdueTasks' :
    $('.TL_OPEN').hide();
    $('.TL_OVERDUE').show();
    break;
  case 'AllTasks' :
    break;
  
  }
}
