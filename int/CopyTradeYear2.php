<?php
  include_once("fest.php");
  include_once("TradeLib.php");

  A_Check('SysAdmin');
  
  dostaffhead("Copy Trade Year to New Trade Year - Only to be used after a cancelled festival");

  global $db,$YEAR,$NEXTYEARDATA;
  $res = $db->query("SELECT * FROM TradeYear WHERE DateChange='12");
 
  while ($ty = $res->fetch_assoc()) {
    $Tid = $ty['Tid'];
    $tys = Get_Trade_Years($Tid);
    if (!isset($tys[$YEARDATA['NextFest']])) {
      $ty['TYid'] = 0;
      $ty['Year'] = $YEARDATA['NextFest'];
      Insert_db('TradeYear',$ty);
      echo "Added: " . $ty['Tid'] . " for " . $Tid . "<br>";
    }
  }

  echo "Finished<p>";
  
  dotail();
?>

