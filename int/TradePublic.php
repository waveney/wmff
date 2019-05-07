<?php

  include_once "fest.php";
  include_once "TradeLib.php";

  global $db;

function Trade_Type_Table($class='') {
  $tts = Get_Trade_Types(1);

  echo "<div class=tablecont><table class=$class>\n";
  echo "<tr><th>Trade Type<th>Description<th>Prices\n";

  foreach ($tts as $tt) {
    if ($tt['Addition']) continue;
    echo "<tr><td>" . $tt['SN'];
    echo "<td>" . $tt['Description'];
    echo "<td>";
    if (is_numeric($tt['BasePrice'])) {
      echo "<span style='color:grey'>From: </span>&pound;" . $tt['BasePrice'];
    } else {
      echo $tt['BasePrice'];   
    }
    if ($tt['PerDay']) echo " per day";
    echo "<td>" . ($tt['TOpen']?'Open':'Closed');
  }
  echo "</table></div><p>";

  foreach ($tts as $tt) {
    if (!$tt['Addition']) continue;
    echo "In addition, " . $tt['SN'] . " is available at some locations from &pound;" . $tt['BasePrice'] . "<p>\n";
  }

}

?>
