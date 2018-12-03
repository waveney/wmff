<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  dostaffhead("Copy ActYear to SideYear");


  global $db;
  $res = $db->query("SELECT * FROM ActYear");
  
  while ($ay = $res->fetch_assoc()) {
    if (!$ay['SideId'] || !$ay['Year']) continue;
    $ay['syId'] = $ay['ActId']+1000;
    Insert_db('SideYear',$ay);
    echo "Added " . $ay['SideId'] . " " . $ay['Year'] . "<br>";
  }
  echo "Finished<p>";
  
  dotail();
?>

