<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Dance Types");
  include_once("DanceLib.php");
  echo "<h2>Dance Dance Types</h2>\n";
  include_once("fest.php");
  include_once("TradeLib.php");

  $Types=Get_Dance_Types(1);
  if (UpdateMany('DanceTypes','Put_Dance_Type',$Types,0)) $Types=Get_Dance_Types(1);

  echo "<h2>Dance Types</h2><p>";
  echo "You do not have to have all Dance Types here, these are just the categories brought out in the summary page.<p>";
  echo "<form method=post action=DanceTypes.php>";
  echo "<div class=tablecont><table border><tr><td>id<td>Name<td>Importance<td>Colour\n";
  foreach($Types as $i=>$t) {
    echo "<tr><td>$i<td><input type=text name=SN$i value='" . $t['SN'] . "'>";
    echo "<td><input type=text name=Importance$i value='" . $t['Importance'] . "'>\n";
    echo "<td><input type=text name=Colour$i value='" . $t['Colour'] . "'>\n";
  }
  echo "<tr><td><td><input type=text name=SN0 >";
  echo "<td><input type=text name=Importance0>\n";
  echo "<td><input type=text name=Colour0>\n";
  echo "</table></div>";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form>";
  dotail();
?>

