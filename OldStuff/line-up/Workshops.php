<?php
  include_once ("int/fest.php");
  include_once ("int/ProgLib.php");
  include_once ("int/DanceLib.php");
    
  dohead("Workshops");

?>
<h2 class="maintitle">Workshops</h2>

<?php
  global $db,$Coming_Type,$YEAR;
  $Es = Get_Events_Type('Workshop');
  $Vens = Get_Venues(1);
  
  if (is_array($Es)) {
    foreach($Es as $E){
      echo "<div class=floatleft>";
      echo "<div class=mini>";
      echo "<a href=/int/ShowDance.php?sidenum=" . $side['SideId'] . ">";
      if ($side['Photo']) echo "<img class=maxminiimg src='" . $side['Photo'] ."'>";
      echo "<h2 class=minittl>" . $side['Name'] . "</h2></a>";
      echo "<div class=minitxt>" . $E['Description'] . "</div>";
      echo "</div>";
      echo "</div>";
    }
  } else {
    echo "<h2 class=subtitle>No Workshops have yet been publicised</h2>";
  }

  dotail();
?>
