<?php

  include_once "fest.php";
  include_once "TradeLib.php";

  global $db;

function Trade_Type_Table($class='') {
  $tts = Get_Trade_Types(1);

  echo "<table class=$class>\n";
  echo "<tr><th>Trade Type<th>Description<th>Prices From\n";

  foreach ($tts as $tt) {
    if ($tt['Addition']) continue;
    echo "<tr><td>" . $tt['SName'];
    echo "<td>" . $tt['Description'];
    echo "<td>&pound;" . $tt['BasePrice'];
    if ($tt['PerDay']) echo " per day";
  }
  echo "</table><p>";

  foreach ($tts as $tt) {
    if (!$tt['Addition']) continue;
    echo "In addition, " . $tt['SName'] . " is available at some locations from &pound;" . $tt['BasePrice'] . "<p>\n";
  }

}

function Traders_List() {


}

?>
