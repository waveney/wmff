<?php
  include_once("fest.php");
  A_Check('Committee');

  include_once("DanceLib.php");
  include_once("ProgLib.php");
  include_once("MusicLib.php");
  global $YEAR;

  if (isset($_GET{'e'})) { $Eid = $_GET{'e'}; } else { Error_Page('Big Event without Event'); };
  $Event = Get_Event($Eid);
  if (!$Event['BigEvent']) Error_Page('Not A Big Event');
  $YEAR = $Event['Year'];

  dominimalhead("Big Event Display");

  $Sides = Part_Come_All();
//  $Acts = Select_Act_Come_Full();
//  $Others = Select_Other_Come_Full();

  $things = Get_Other_Things_For($Eid);

  if ($things) {
    $Posn = 1;
    echo "<h2>" . $Event['SN'] . "</h2>\n";
    echo "<div class=tablecont><table border><tr><th>Position<th>What<th>Notes\n";
    foreach ($things as $i=>$t) {
      $id = $t['Identifier'];
      $tt = $t['Type'];
      if ($tt == 'Venue') Continue;
      switch ($tt) {
        case 'Perf':
        case 'Side':
        case 'Act':
        case 'Other':
          echo "<tr><td>" . ($Posn++) . "<td>" . SName($Sides[$id]);
          if ($Sides[$id]['Type']) echo " (" . trim($Sides[$id]['Type']) . ")";
          if ($t['Notes']) echo "<td>" . $t['Notes'];
          break;

        case 'Note':
          echo "<tr><td><td>";
          if ($t['Notes']) echo "<td>" . $t['Notes'];
          break;
        default: // inc Venues
          break;
      }
    }
    echo "</table></div>\n";
  }

  dotail();

?>
