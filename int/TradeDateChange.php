<?php

  include_once("fest.php");
  include_once("TradeLib.php");
  A_Check('SysAdmin');

  dostaffhead("Trade Stall Booking", ["/js/Participants.js","js/dropzone.js",'js/emailclick.js',"/js/clipboard.min.js","css/dropzone.css"]);

// Get all traders for this year
// Get TradeYear, bump to Next Fest 
// Save as new record
// Report progress
  
  global $YEAR,$YEARDATA,$db;
  if ($YEAR != '2020') {
    echo "Not valid setup<p>";
    dotail();
  }
  
  $Next = $YEARDATA['NextFest'];
  
  $qry = "SELECT y.Tid FROM TradeYear y WHERE y.Year='$Next'";
  $res = $db->query($qry);
  $NY = [];
  
  if ($res)   while ($fetch = $res->fetch_assoc()) {
    $Tid = $fetch['Tid'];
    $NY[$Tid] = 1;
  }
  
  $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Status!=2 AND t.Tid = y.Tid AND y.Year='$YEAR' ORDER BY SN";
  $res = $db->query($qry);

  while ($fetch = $res->fetch_assoc()) {
    $Tid = $fetch['Tid'];
    
    if (isset($NY[$Tid])) {
      echo "Not doing " . $fetch['Tid'] . " - " . $fetch['SN'] . "<br>";
    } else {
      $fetch['Year'] = $Next;
      Put_Trade_Year($fetch);
      echo "Doing " . $fetch['Tid'] . " - " . $fetch['SN'] . "<br>";
    }
  }
  echo "All Done";
  
  dotail();


?>
