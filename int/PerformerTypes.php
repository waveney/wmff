<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Performer Types");
  include_once("DanceLib.php");
  echo "<h2>Performer Types</h2>\n";
  global $PerfListStates;

  $Types=Get_Perf_Types(1);
  $t = [];
  if (UpdateMany('PerformerTypes','Put_Perf_Type',$Types,0)) $Types=Get_Perf_Types(1);

  echo "<h2>Performer Types</h2><p>";
  echo "<form method=post>";
  echo "<div class=tablecont><table border><tr><td>id<td>Name<td>Status\n";
  foreach($Types as $i=>$t) {
    echo "<tr><td>$i<td><input type=text name=SN$i value='" . $t['SN'] . "'>";
    echo "<td>" . fm_select($PerfListStates,$t,'ListState',1,'',"ListState$i");
  }
  echo "<tr><td><td><input type=text name=SN0 >";
  echo "<td>" . fm_select($PerfListStates,$t,'ListState',1,'',"ListState0");
  echo "</table></div>";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form>";
  dotail();
?>

