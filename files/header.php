<?php
  global $MASTER,$MASTER_DATA,$CALYEAR;

  echo "<meta name=description content='Wimborne\'s annual folk festival takes place in the historic market town of Wimborne Minster in Dorset on the weekend of 14, 15, 16 June 2019.>\n";
  echo "<meta name=keywords content='wimborne, minster, folk, festival, folk festival, dorset, folkie, fringe, paddock, morris, dance, side, music, concerts, camping, 
	      parking, trade, trading, stewards, volunteer, tickets, line up, appalachian, ceildihs, procession, step dance, workshops, craft, sessions'>\n";
  echo "<meta name=copyright content='Copyright &copy; Wimborne Minster Folk Festival $CALYEAR'>\n"; 
 
  $V = $MASTER_DATA['V'];
  include_once("int/analyticstracking.php");
  echo "<link href=/files/style.css?V=$V type=text/css rel=stylesheet />";
  echo "<link href=/files/dropdown.css?V=$V type=text/css rel=stylesheet />\n";
?>

    <script src="/js/jquery-3.2.1.min.js"></script>
    <link href="/files/themes.css" type="text/css" rel="stylesheet" />
    <script src="/js/lightbox.js"></script>
    <link href="/css/lightbox.css" rel="stylesheet" />

