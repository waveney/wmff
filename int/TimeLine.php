<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("TimeLine");

  include_once("TLLib.php");
  include_once("DocLib.php");
  global $YEAR,$TL_State,$TL_States,$TL_Importance;

  echo "<div class=content><h2>Timeline Management "; 
  $V = isset($_GET{'V'})? $_GET{'V'} : "";
  switch ($V) {
    case 'OPEN': echo "- All Open Tasks"; break;
    case 'ALL': echo "- All Tasks"; break;
    case 'MONTH': echo "- All Tasks Due in Next Month"; break;
    case 'OVERDUE': echo "- All Overdue Tasks"; break;
    case 'MINE': echo "- All Your Tasks"; break;
    default: echo "- Your Open Tasks"; break;
  }

  echo "</h2>\n";

  echo "<ul id=TLacts>";
  echo "<li><a href=AddTimeLine.php>Add a Task</a>";
  echo "<li" . ($V==''?' id=TLactsel':'') . "><a href=TimeLine.php?Y=$YEAR>Your Open Tasks</a> ";
  echo "<li" . ($V=='MINE'?' id=TLactsel':'') . "><a href=TimeLine.php?Y=$YEAR&V=MINE>All Your Tasks</a> ";
  echo "<li" . ($V=='OPEN'?' id=TLactsel':'') . "><a href=TimeLine.php?Y=$YEAR&V=OPEN>All Open Tasks</a> ";
  echo "<li" . ($V=='ALL'?' id=TLactsel':'') . "><a href=TimeLine.php?Y=$YEAR&V=ALL>All Tasks</a> ";
  echo "<li" . ($V=='MONTH'?' id=TLactsel':'') . "><a href=TimeLine.php?Y=$YEAR&V=MONTH>Tasks Due Next Month</a> ";
  echo "<li" . ($V=='OVERDUE'?' id=TLactsel':'') . "><a href=TimeLine.php?Y=$YEAR&V=OVERDUE>Overdue Tasks</a>";
  echo "</ul>\n";

  
  $TLents = TL_Select($V);
  $now = time();

  $AllU = Get_AllUsers(0);
  $AllA = Get_AllUsers(1);
  $AllActive = array();
  foreach ($AllU as $id=>$name) if ($AllA[$id] >= 2 && $AllA[$id] <= 6) $AllActive[$id]=$name;
  
  if ($TLents) {
    echo "<table id=indextable border>\n";
    echo "<thead><tr>";

    $coln = 0;
    if (Access('SysAdmin')) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>TL Id</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Title</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Assigned</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Importance</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Status</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Due</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
    echo "</thead><tbody>";
  
    foreach($TLents as $tl) {
      $tli = $tl['TLid'];
      echo "<tr class=TL_" . $TL_Importance[$tl['Importance']] . ">";
      if (Access('SysAdmin')) echo "<td>" . $tli;
      echo "<td><a href=AddTimeLine.php?TLid=$tli>" . $tl['Title'] . "</a>";
      echo "<td>" . ($tl['Assigned'] ? $AllU[$tl['Assigned']] : "<B>NOBODY</b>");
      echo "<td>" . $TL_Importance[$tl['Importance']];
      echo "<td>" . $TL_States[$tl['Status']];
      echo "<td" . ($tl['Due']<$now?" style='color:red;'":"") . ">" . date('d/m/Y',$tl['Due']);
      echo "<td>" . $tl['Notes'] . "\n";  
    }
    echo "</table>\n";
  } else {
    echo "<h2>No Current Tasks in the records</h2>\n";
  }

  echo "<h2>Importing Data</h2>";
  echo "If you have a spreadsheet or file with lots of entries you would like to import directly without retyping them please send it to Richard.\n<p>"; 

  echo "</div>\n";
  dotail();
?>

