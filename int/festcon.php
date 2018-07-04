<?php
  global $MASTER_DATA;
  include_once("int/fest.php"); 
  $V = $MASTER_DATA['V'];
  echo "<link href=/files/festconstyle.css?V=$V type=text/css rel=stylesheet />\n";
  echo "<script src=/js/tablesort.js?V=$V></script>\n";
  echo "<script src=/js/Tools.js?V=$V></script>\n";
?>
