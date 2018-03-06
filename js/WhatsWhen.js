function ShowDay(d) {
  if ($('#DayClick'+d).text() == 'Expand') {
    $('.Day'+d).show();
    $('#DayClick'+d).text('Close');
  } else {
    $('.Day'+d).hide();
    $('#DayClick'+d).text('Expand');
  }
}

// No sub event expansion yet
function ShowAll() {
  var days=['Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
  if ($('#ShowAll').text() == 'Expand All') {
    for (d in days) {
      $('.Day'+days[d]).show();
      $('#DayClick'+days[d]).text('Close');
    }
    $('#ShowAll').text('Close All');
  } else {
    for (d in days) {
      $('.Day'+days[d]).hide();
      $('#DayClick'+days[d]).text('Expand');
    }
    $('#ShowAll').text('Expand All');
  }
}
