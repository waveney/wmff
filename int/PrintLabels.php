<?php
  include_once("fest.php");
  A_Check('Committee','Dance');

  if (!isset($_GET['Lines'])) {
    dostaffhead("Starting Label Offset");
    echo "<form>" . fm_text("Starting Label Offset 0-7",$_GET,'Lines') . "</form>";
    dotail();
    exit;
  }

?>

<html>
<head>
<title>WMFF Staff | Print Dance Address Labels</title>
<?php include_once("minimalheader.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php

  include_once("DanceLib.php");

  $Lines = 0;
  if (isset($_GET['Lines'])) $Lines = $_GET['Lines'];

/* 
  Select Sides to print
  build pages 3 x 8 tables pad between
  display - no back links
*/

  $sides = Select_Come_All();

  $LINESADR = 7;

  echo "<table class=adrlabels border>";
  if ($Lines) {
    for ($i = 0; $i < $Lines; $i++) echo "<tr><td class=adrlabbox><td class=adrlabbox><td class=adrlabbox>\n";
    $PerLine = 3;
  } else {
    $PerLine = 0;
  }
  foreach($sides as $side) {
    if ($side['Performers'] < 1 || strlen($side['Address']) < 10 || $side['WristbandsSent']) continue;
    if ($PerLine == 3) {
      if (($Lines%8) == 7) {
        echo "</table>";
        echo "<table class=adrlabels border>";
      }
      echo "<tr>";
      $Lines++;
      $PerLine = 0;
    }
    echo "<td class=adrlabbox>";
    $adr = $side['Address'];
    if (!preg_match('/,/',$adr)) {
      if ($a = preg_replace('/ (\d)/',", $1",$adr)) $adr = $a;
      if ($a = preg_replace('/Road /i',"Road, ",$adr)) $adr = $a;
      if ($a = preg_replace('/Way /i',"Way, ",$adr)) $adr = $a;
      if ($a = preg_replace('/Close /i',"Close, ",$adr)) $adr = $a;
      if ($a = preg_replace('/House /i',"House, ",$adr)) $adr = $a;
    }

    if ($a = preg_replace('/(\d), /',"$1 ",$adr)) $adr = $a;
    if ($a = preg_replace("/" . $side['PostCode'] . "/","",$adr)) $adr = $a;
    if ($a = preg_replace('/, ,/',",",$adr)) $adr = $a;
    if ($a = preg_replace('/,,/',",",$adr)) $adr = $a;
    if ($a = preg_replace('/,$/',"",$adr)) $adr = $a;
    if ($a = preg_replace('/, $/',"",$adr)) $adr = $a;

    $tl = array_merge(array(SName($side),$side['Contact']),explode(',',$adr),array($side['PostCode']));
    $ind = "";
    $l = 0;
    foreach ($tl as $tls) {
      if ($l > $LINESADR) echo ","; 
      echo $ind . $tls;
      if ($l == 0) echo " &nbsp; &nbsp; <span class=floatright><span class=smalltext>" . $side['Performers'] . "</span></span>";
      if ($l++ < $LINESADR) { 
        $ind .= "&nbsp; ";
        echo "<br>"; 
      } else { 
        $ind = "&nbsp; ";
      }
    }
    while ($l++ < $LINESADR ) echo "<br>"; 
    echo "<br>"; 
    $PerLine++;
  }
  while ($PerLine++ < 3) echo "<td class=adrlabbox><br>"; 
  echo "</table>\n";
  echo "</body></html>\n";
?>
      
 
