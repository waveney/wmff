<?php
  global $MASTER,$MASTER_DATA,$CALYEAR;

  echo "<meta name=description content='Wimborne\'s annual folk festival takes place in the historic market town of Wimborne Minster in Dorset on the weekend of 6th to 9th June 2019.>\n";
  echo "<meta name=keywords content='wimborne, minster, folk, festival, folk festival, dorset, folkie, fringe, paddock, morris, dance, side, music, concerts, camping, 
	      parking, trade, trading, stewards, volunteer, tickets, line up, appalachian, ceildihs, procession, step dance, workshops, craft, sessions'>\n";
  echo "<meta name=viewport content='width=device-width, initial-scale=1.0'>";

  $V = $MASTER_DATA['V'];
  echo "<script>" . $MASTER_DATA['Analytics'] . "</script>";
  if (Feature('NewStyle')) {
    echo "<link href=/files/Newstyle.css?V=$V type=text/css rel=stylesheet />";
    echo "<link href=/files/Newdropdown.css?V=$V type=text/css rel=stylesheet />\n";  
  } else {
    echo "<link href=/files/style.css?V=$V type=text/css rel=stylesheet />";
    echo "<link href=/files/dropdown.css?V=$V type=text/css rel=stylesheet />\n";
  }
?>

    <script src="/js/jquery-3.2.1.min.js"></script>
    <link href="/files/themes.css" type="text/css" rel="stylesheet" />
    <script src="/js/lightbox.js"></script>
    <link href="/css/lightbox.css" rel="stylesheet" />
    <script src="/js/responsiveslides.js"></script>
    <link href="/css/responsiveslides.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Montserrat%3A300%2C400%2C600%2C700' type='text/css' media='all' />
<?php
  echo "<script src=/js/tablesort.js?V=$V></script>\n";
  echo "<script src=/js/Tools.js?V=$V></script>\n";
?>
