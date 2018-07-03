<?php
  include_once("int/fest.php");

  dohead("Other Festivals");
  include_once("int/TradeLib.php");

  echo "<h2 class=subtitle>Other Festivals</h2>\n";

  $fests = Get_OtherLinks("WHERE Year=$SHOWYEAR");
  $fcount = count($fests);
  if ($fcount) {
    echo "Wimborne Minster Folk Festival is also recomending the following festival" . ($fcount>1?'s':'') . "<p>";
    foreach ($fests as $f) {
      echo "<h2><a class=subtitle href=" . $f['URL'] . " target=_blank>";
      if ($f['Image']) echo "<img src=" . $f['Image'] . "> ";
      echo $f['SName'] . "</a></h2>\n";
    }
  } else {
    echo "Nothing else yet this year.<p>";
  }
      
  dotail();
?>
