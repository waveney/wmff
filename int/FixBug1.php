<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  include_once("DanceLib.php");
  include_once("TradeLib.php");
  include_once("ImageLib.php");
  dostaffhead("Update image info for all participants");
  echo "This may be extended to other image categories later.<p>";

  global $db;
  
    $ans = $db->query("SELECT * FROM Sides");
    while ($side = $ans->fetch_assoc()) {
      $Photo = $side['Photo'];
      $id = $side['SideId'];
echo "Checking $id $Photo<br>";
      if (preg_match("/\/images\/$id\.(.*)/",$Photo,$mtch)) {
        $newP = "/images/Sides/$id." . strtolower($mtch[1]);
//        rename("../$Photo","../$newP");
        $side['Photo'] = $newP;
//        Put_Side($side);
        echo "Unscrambled " . $side['SN'] . "<br>";
      }
    }
  echo "Finished<p>";
  
  dotail();
?>

