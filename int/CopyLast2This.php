<?php
  include_once("fest.php");
  include_once("DanceLib.php");
  include_once("ProgLib.php");
  A_Check('SysAdmin');
  
  dostaffhead("Copy Last years acts to this year");


  global $db;

  $res = $db->query("SELECT * FROM SideYear WHERE Year=2021");
  
  while ($ay = $res->fetch_assoc()) {
    if (!$ay['SideId'] || !$ay['Year']) continue;
    $Side = Get_Side($ay['SideId']);
    if (!$Side['IsAnAct']) continue;
    echo "Copy forward " . $Side['SN'] ."<br>";
    $ay['id'] = 0;
    $ay['Year'] = 2022;
    Insert_db('SideYear', $ay);
  }

  echo "Finished<p>";
  
  dotail();
?>

