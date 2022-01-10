<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  dostaffhead("Copy Last years acts to this year");


  global $db;
  $res = $db->query("SELECT y.* FROM SideYear as y, Sides as s WHERE");
  
  while ($ay = $res->fetch_assoc()) {
    if (!$ay['SideId'] || !$ay['Year']) continue;
    $ay['syId'] = $ay['ActId']+1000;
    Insert_db('SideYear',$ay);
    echo "Added " . $ay['SideId'] . " " . $ay['Year'] . "<br>";
  }
  echo "Finished<p>";
  
  dotail();
?>

