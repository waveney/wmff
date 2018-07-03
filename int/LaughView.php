<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("List Laugh Out Loud Applications");
  global $db,$PLANYEAR;
  include_once("SignupLib.php");

  $coln = 0;  
  echo "<form method=post action=LaughView.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Act Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Phone</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Style</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Started</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Bio</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Equipment</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Example</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>March 6</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>April 10</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>May 1</a>\n";
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM SignUp WHERE Year=$PLANYEAR AND State<2 AND Activity=5 ORDER BY SName");
  
  if ($res) {
    while ($lol = $res->fetch_assoc()) {
      $id = $lol['id'];
      echo "<tr><td>$id";
      echo "<td>" . $lol['SName'];
      echo "<td>" . $lol['Contact'];
      echo "<td>" . $lol['Email'];
      echo "<td>" . $lol['Phone'];
      echo "<td>" . $lol['Style'];
      echo "<td>" . $lol['Started'];
      echo "<td>" . $lol['Bio'];
      echo "<td>" . $lol['Equipment'];
      echo "<td>" . weblink( $lol['Example'] , "Example");
      echo "<td>" . ($lol['Avail1']?'Y':'');
      echo "<td>" . ($lol['Avail2']?'Y':'');
      echo "<td>" . ($lol['Avail3']?'Y':'');
    }
  }
  echo "</tbody></table>\n";

  dotail();
?>
