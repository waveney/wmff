<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  include_once("DanceLib.php");
  dostaffhead("Update image info for all participants");
  echo "This may be extended to other image categories later.<p>";

  global $db;
  $ans = $db->query("SELECT * FROM Sides");
  while ($fside = $ans->fetch_assoc()) {
    if ($fside['Photo']) {
      $side = Get_Side($fside['SideId']);

      if (preg_match('/^https?://i',$side['Photo'])) {
        $stuff = getimagesize($side['Photo']);
      } else if (preg_match('/^\/(.*)/',$side['Photo'],$mtch)) {
        $stuff = getimagesize($mtch[1]);
      } else {
        $stuff = getimagesize($side['Photo']);
      }
      if ($stuff) {
        $wi = $stuff[0];
        $ht = $stuff[1];
        $side['ImageHeight'] = $ht;
        $side['ImageWidth'] = $wi;
        Put_Side($side);
        echo "Done " . $side['SideId'] . "<br>\n";
      } else {
        echo "Not Done " . $side['SideId'] . "<br>\n";
      }
    }
  }

  dotail();
?>

