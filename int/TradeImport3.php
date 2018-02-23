<?php
  include_once("fest.php");
  A_Check('SysAdmin');
  dostaffhead('Import Old Trade Data');
  global $YEAR,$db;
  include_once("TradeLib.php");

  $res = $db->query("SELECT * FROM Trade");
  while ($T = $res->fetch_assoc()) {
    if (!empty($T['AccessKey'])) continue;
    $Old = $T;
    $T['AccessKey'] = rand_string(40);
    Update_db('Trade',$Old,$T);
    echo "Updated " . $T['SName'] . "<br>";
  }

  dotail();
?>
