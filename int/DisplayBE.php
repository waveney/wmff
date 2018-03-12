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

  dostaffhead("Big Event Display");

  $Sides = Select_Come_All();
  $Acts = Select_Act_Come(1);
  $Others = Select_Other_Come(1);

  $things = Get_Other_Things_For($Eid);

  if ($things) {
    $Posn = 1;
    echo "<h2>" . $Event['SName'] . "</h2>\n";
    echo "<table border><tr><th>Position<th>What<th>Notes\n";
    foreach ($things as $i=>$t) {
      $id = $t['Identifier'];
      $tt = $t['Type'];
      if ($tt == 'Venue') Continue;
      switch ($tt) {
        case 'Side':
	  echo "<tr><td>" . ($Posn++) . "<td>" . SName($Sides[$id]) . " (" . trim($Sides[$id]['Type']) . ")";
	  if ($t['Notes']) echo "<td>" . $t['Notes'];
	  break;
        case 'Act':
	  echo "<tr><td>" . ($Posn++) . "<td>" . SName($Acts[$id]);
	  if ($Acts[$id]['Type']) echo " (" . trim($Acts[$id]['Type']) . ")";
	  if ($t['Notes']) echo "<td>" . $t['Notes'];
	  break;
        case 'Other':
	  echo "<tr><td>" . ($Posn++) . "<td>" . SName($Others[$id]);
	  if ($Others[$id]['Type']) echo " (" . trim($Others[$id]['Type']) . ")";
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
    echo "</table>\n";
  }

  dotail();

?>
