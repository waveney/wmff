<?php
  include_once("fest.php");

  include_once("ProgLib.php");
  
  global $db, $YEAR,$ll,$SpecialImage,$Pictures;

  A_Check('Staff','Venues');
  
  dostaffhead("Complete Venues");
  
  echo "<h2>Mark Venues as complete</h2>";
  echo "Only use this to mark a Venue that is complete, when it is used for events that are not themselves complete.<p>";
  echo "In most cases, it is the event type that is marked as complete.  Use this to handle the odd cases only<p>";
  
  echo "Adding an entry will mark as complete, untick complete and click Update to remove the setting<p>";
  
  $VenY=Get_VenueYears($YEAR);
  $Vens=Get_AVenues(1);

  if (isset($_POST['Update'])) {
    $Change = 0;
    foreach ($Vens as $v) {
      $vid = $v['VenueId'];
      $SaveSet = $NewSet = 0;
      if (isset($VenY[$vid])) $SaveSet = $VenY[$vid]['Complete'];
      if (isset($_POST["Complete$vid"])) $NewSet = 1;
      if ($SaveSet != $NewSet) {
        if (isset($VenY[$vid])) {
          $VenY[$vid]['Complete'] = $NewSet;
          Put_VenueYear($VenY[$vid]);
        } else {
          $VC = ['VenueId'=>$vid,'Complete'=>$NewSet,'Year'=>$YEAR];
          Insert_db('VenueYear',$VC);
        }
      $Change = 1;
      }
    }
    if ($Change) $VenY=Get_VenueYears($YEAR);
  }

  $coln = 0;
  echo "<form method=post>";
  echo "<div class=tablecont><table id=indextable border style='width:500;'>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Venue</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Complete</a>\n";
  echo "</thead><tbody>";
  foreach($Vens as $v) {
    $i = $v['VenueId'];
    $vyi = 0;
    if (isset($VenY[$i])) $vyi = $VenY[$i]['id'];
    echo "<tr><td>$i";
    echo "<td width=400>" . $v['SN'];
    echo "<td>" . fm_checkbox('',$VenY[$i],'Complete','',"Complete$i");
    echo "\n";
  }
  echo "</table></div><p><input type=submit name=Update value=Update>\n";
  echo "</form></div>";




  dotail();
 
?>
