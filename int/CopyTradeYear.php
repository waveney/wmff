<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  dostaffhead("Copy Trade Year to New Trade Year - Only to be used after a cancelled festival");

  global $db,$YEAR,$NEXTYEARDATA;
  $res = $db->query("SELECT * FROM TradeYear WHERE Year=$YEAR");
 
  while ($ty = $res->fetch_assoc()) {
    $ty['TYid'] = 0;
    $ty['Year'] = $YEARDATA['NextFest'];
    Insert_db('TradeYear',$ty);
    echo "Added: " . $ty['Tid'] . "<br>";
  }

  echo "Finished<p>";
  
  dotail();
?>

