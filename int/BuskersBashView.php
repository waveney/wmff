<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("List Buskers Bash Applications");
  global $db,$PLANYEAR;
  include_once("SignupLib.php");

  $coln = 0;  
  echo "<form method=post action=BuskersBashView.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Band Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Contact</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Example</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State/Actions</a>\n";
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM SignUp WHERE Year=$PLANYEAR AND State<2 AND Activity=4 ORDER BY SN");
  
  if ($res) {
    while ($bb = $res->fetch_assoc()) {
      $id = $bb['id'];
      echo "<tr><td>$id";
      echo "<td>" . $bb['SN'];
      echo "<td>" . $bb['Contact'];
      echo "<td>" . $bb['Email'];
      echo "<td>" . weblink( $bb['Example'] , "Example");
      echo "<td>" . $States[$bb['State']];

    }
  }
  echo "</tbody></table>\n";

  dotail();
?>
