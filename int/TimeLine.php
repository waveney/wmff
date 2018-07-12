<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("TimeLine");

  include_once("TLLib.php");
  include_once("DocLib.php");
  global $YEAR,$TL_State,$TL_States,$TL_Importance;

  echo "<div class=content><h2>Timeline Management "; 
  $now = time();

  $All = Get_AllUsers(2);
  $AllActive = [];
  foreach ($All as $usr) {
    $id = $usr['UserId'];
    if ($usr['AccessLevel'] >=2 && $usr['AccessLevel']<= 6 ) $AllActive[$id] = (strlen($usr['Abrev'])?$usr['Abrev']:$usr['Login']);  
  }

  if (isset($_POST['ACTION'])) {
    foreach ($_POST as $f=>$v) {
      if (preg_match('/E(\d*)/',$f,$res)) {
        $tl=$res[1];
        $tle = Get_TLent($tl);

        switch ($_POST['ACTION']) {
          case 'Cancel':
            $tle['Status'] = $TL_State['Cancelled'];
            $tle['History'] .= " Cancelled by " . $USER['Login'] . " on " . date('d/m/Y');
            break;
          case 'Completed' :
            $tle['Status'] = $TL_State['Completed'];
            $tle['Completed'] = $now;
            $tle['History'] .= " Completed by " . $USER['Login'] . " on " . date('d/m/Y');
            break;
          case 'Re Assign to':
            $tle['Assigned'] = $_POST['ReAssign'];
            $tle['History'] .= " Reassign to " . $AllActive[$tle['Assigned']] . " by " . $USER['Login'] . " on " . date('d/m/Y');
            break;
        }
        Put_TLent($tle);
      }
    }
  }

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

  echo "Note the Year is the festival it is for not the calenda year.<p>\n";
  $TLents = TL_Select($V);


  if ($TLents) {
    echo "<form method=post >";
    echo "<table id=indextable border>\n";
    echo "<thead><tr>";

    $coln = ((Access('Committee','TLine'))? 1:0);  // Start at 1 because of select all
    if (Access('Committee','TLine')) echo "<th><input type=checkbox name=SelectAll id=SelectAll onchange=ToolSelectAll(event)>\n";
    if (Access('SysAdmin')) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>TL Id</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Title</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Assigned</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Importance</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Status</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Due</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Year</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
    echo "</thead><tbody>";
  
    foreach($TLents as $tl) {
      $tli = $tl['TLid'];
      echo "<tr class=TL_" . $TL_Importance[$tl['Importance']] . ">";
      if (Access('Committee','TLine')) echo "<td><input type=checkbox name=E$tli class=SelectAllAble>";
      if (Access('SysAdmin')) echo "<td>" . $tli;
      echo "<td><a href=AddTimeLine.php?TLid=$tli>" . $tl['Title'] . "</a>";
      echo "<td>" . ($tl['Assigned'] ? $AllActive[$tl['Assigned']] : "<B>NOBODY</b>");
      echo "<td>" . $TL_Importance[$tl['Importance']];
      echo "<td>" . $TL_States[$tl['Status']];
      echo "<td" . ($tl['Due']<$now?" style='color:red;'":"") . ">" . date('d/m/Y',$tl['Due']);
      echo "<td>" .$tl['Year'];
      echo "<td>" . $tl['Notes'] . "\n";  
    }
    echo "</table>\n";
    if (Access('Committee','TLine')) {
      echo "Selected: <input type=Submit name=ACTION value=Cancel " .
           " onClick=\"javascript:return confirm('are you sure you want to cancel these?');\">, "; 
      echo "<input type=Submit name=ACTION value='Completed'>, ";
      echo "<input type=Submit name=ACTION value='Re Assign to'>: " . fm_select($AllActive,$_POST,'ReAssign',1);
//    echo " <input type=text name=Minutes size=4> Minutes, ";
//    echo "<input type=Submit name=ACTION value='Move to'> ";
//    echo "<input type=Submit name=LIST value='Show All'><br>\n";
//  add copy to planyear
    }
  } else {
    echo "<h2>No Current Tasks in the records</h2>\n";
  }

  echo "<h2>Importing Data</h2>";
  echo "If you have a spreadsheet or file with lots of entries you would like to import directly without retyping them please send it to Richard.\n<p>"; 

  echo "</div>\n";
  dotail();
?>

