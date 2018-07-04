<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("List Bugs");

  global $db;
  $yn = array('','Y');
  include_once("BugLib.php");
  include_once("DocLib.php");

  $AllU = Get_AllUsers(0);
  $AllA = Get_AllUsers(1);
  $AllActive = array();
  foreach ($AllU as $id=>$name) if ($AllA[$id] >= 2 && $AllA[$id] <= 6) $AllActive[$id]=$name;

  if (isset($_GET['OLD'])) {
    $res = $db->query("SELECT * FROM Bugs ORDER BY Severity DESC");
  } else {
    $res = $db->query("SELECT * FROM Bugs WHERE State<" . $Bug_Type['Finalised'] . " ORDER BY Severity DESC");
  }

  $coln = 0;
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Bug Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Who</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D','dmy')>Created</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Severity</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Status</a>\n";
  echo "</thead><tbody>";

  if ($res) {
    while ($bug = $res->fetch_assoc()) {
      $b = $bug['BugId'];
      echo "<tr><td>$b<td><a href=AddBug.php?b=$b>" . $bug['SName'] ;
      if (strlen($bug['SName']) < 2) echo " Nameless Bug ";
      echo "</a><td>" . $AllU[$bug['Who']];
      echo "<td>" . date('d/m/y H:i:s',$bug['Created']) . "<td>" . $Severities[$bug['Severity']];
      echo "<td>" . $Bug_Status[$bug['State']];
    }
  }
  echo "</tbody></table>\n";
  
  echo "<h2><a href=AddBug.php>Add Bug/Feature Request</a>, <a href=ListBugs.php?OLD>Old Bugs</a></h2>";

  dotail();
?>
