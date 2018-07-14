<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("TimeLine", "js/timeline.js");

  include_once("TLLib.php");
  include_once("DocLib.php");
  global $YEAR,$PLANYEAR,$TL_State,$TL_States,$TL_Importance;

  $Years = Get_Years();

  echo "<div class=floatright><h2>";
    if (isset($Years[$YEAR-1])) echo "<a href=TimeLine.php?Y=" . ($YEAR-1) .">" . ($YEAR-1) . "</a> &nbsp; ";
    if (isset($Years[$YEAR+1])) echo "<a href=TimeLine.php?Y=" . ($YEAR+1) .">" . ($YEAR+1) . "</a>\n";
    if ($YEAR+1 < $PLANYEAR) echo " &nbsp; <a href=TimeLine.php?Y=$PLANYEAR>$PLANYEAR</a>\n";
  echo "</div>";

  echo "<h2>Timeline Management for $YEAR "; 
  $now = time();
  $month = mktime(0,0,0,$now['mon']+1,$now['mday'],$now['year']);
  
/* Thinking space
 Time line for YEAR                                      PREV, NEXT, PLAN (aka sideyears)
 Add a Task  Show: Your Open tasks      All Your Tasks      Due next month, 2 months     Simple display (No year, recur, etc) 
                   all Open tasks       All Tasks           Overdue                      Complex display (only for TLent?
 Use JS to swap display round quickly - always send all for year 

*/



  $All = Get_AllUsers(2);
  $AllActive = [];
  foreach ($All as $usr) {
    $id = $usr['UserId'];
    if ($usr['AccessLevel'] >=2 && $usr['AccessLevel']<= 6 ) $AllActive[$id] = (strlen($usr['Abrev'])?$usr['Abrev']:$usr['Login']);  
  }

  if (isset($_POST['ACTION'])) {
    if ($_POST['ACTION'] == "Copy Recuring to $PLANYEAR") {
      $res = $db->query("SELECT * FROM TimeLine WHERE Recuring=1 AND NextYearId=0 AND Year=$YEAR");
      if ($res) while ($tl = $res->fetch_assoc()) {
        $Ntl = $tl;
        $Ntl['TLid'] = -1;
        $Ntl['Year'] = $PLANYEAR;
        $Ntl['Status'] = $TL_State['Open'];
        $Ntl['Created'] = $now;
        $Ntl['CreatedBy'] = $USERID;
        $Ntl['Due'] =  strtotime(date("Y-m-d", ((isset($Ntl['Due']) && $Ntl['Due'] > 0) ? $Ntl['Due']: $now)) . " + 365 day");
        $Ntl['Progress'] = $Ntl['History'] = '';

        $tl['NextYearId'] = Insert_db('TimeLine',$Ntl);
         
        Put_TLent($tl);
      }
    } else {
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
            case 'Set Recuring':
              $tle['Recuring'] = 1;
              break;
            case "Copy to $PLANYEAR":
              if (!$tle['NextYearId']) {
                $Ntl = $tle;
                $Ntl['TLid'] = -1;
                $Ntl['Year'] = $PLANYEAR;
                $Ntl['Status'] = $TL_State['Open'];
                $Ntl['Created'] = $now;
                $Ntl['CreatedBy'] = $USERID;
                $Ntl['Due'] =  strtotime(date("Y-m-d", ((isset($Ntl['Due']) && $Ntl['Due'] > 0) ? $Ntl['Due']: $now)) . " + 365 day");
                $Ntl['Progress'] = $Ntl['History'] = '';

                $tle['NextYearId'] = Insert_db('TimeLine',$Ntl);
              }
              break;
          }
          Put_TLent($tle);
        }
      }
    }
  }

  $V = isset($_GET{'V'})? $_GET{'V'} : "";
/*  switch ($V) {
    case 'OPEN': echo "- All Open Tasks"; break;
    case 'ALL': echo "- All Tasks"; break;
    case 'MONTH': echo "- All Tasks Due in Next Month"; break;
    case 'OVERDUE': echo "- All Overdue Tasks"; break;
    case 'MINE': echo "- All Your Tasks"; break;
    default: echo "- Your Open Tasks"; break;
  }
*/
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

  echo "<table style=' width: auto;' border><tr><td><a class=PurpButton href=AddTimeLine.php>Add a Task</a>";
  echo "<td><div class=PurpSelect id=TasksYou onclick=TLSelect(this.id)>Your Tasks</div><br><div class=PurpButton id=TasksAll onclick=TLSelect(this.id)>Everyone's</div>";
  echo "<td><div class=PurpSelect id=OpenTasks onclick=TLSelect(this.id)>Open Tasks</div>";
  echo     "<div class=PurpButton id=NextMonth onclick=TLSelect(this.id)>Tasks Due Next Month</div>";
  echo     "<div class=PurpButton id=OverdueTasks onclick=TLSelect(this.id)>Overdue Tasks</div>";
  echo     "<div class=PurpButton id=AllTasks onclick=TLSelect(this.id)>All Tasks</div>";
  echo "<td><div class=PurpSelect id=DataLow onclick=TLSelect(this.id)>Basic Info</div><br><div class=PurpButton id=DataHigh onclick=TLSelect(this.id)>More Info</div>";
  echo "</table><p>\n";

  echo "Note the Year is the festival year, it is for not the calenda year.<p>\n";
  $TLents = TL_Select($V);


  if ($TLents) {
    echo "<form method=post >";
    echo fm_hidden('Y',$YEAR);
    echo "<table id=indextable border>\n";
    echo "<thead><tr>";

    $coln = ((Access('Committee','TLine'))? 1:0);  // Start at 1 because of select all
    if (Access('Committee','TLine')) echo "<td><input type=checkbox name=SelectAll id=SelectAll onchange=ToolSelectAll(event)>\n";
    if (Access('SysAdmin')) echo "<th class=FullD hidden><a href=javascript:SortTable(" . $coln++ . ",'N')>TL Id</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Title</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Assigned</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Importance</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Status</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D','dmy')>Due</a>\n";
    echo "<th class=FullD hidden><a href=javascript:SortTable(" . $coln++ . ",'N')>Year</a>\n";
    echo "<th class=FullD hidden><a href=javascript:SortTable(" . $coln++ . ",'T')>Recur</a>\n";
    echo "<th class=FullD hidden><a href=javascript:SortTable(" . $coln++ . ",'T')>Copied</a>\n";    
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
    echo "</thead><tbody>";
  

    foreach($TLents as $tl) {
      $tli = $tl['TLid'];
      $classes = "TL_" . $TL_Importance[$tl['Importance']] . " ";
      $hide = 0;
      if ($tl['Assigned']>0 && $tl['Assigned'] != $USERID) { $classes .=  "TL_EVERYONE "; $hide=1; }
      if ($TL_States[$tl['Status']] == 'Open') { $classes .= "TL_OPEN "; } else $hide=1;
      if ($tl['Due'] < $month ) $classes .= "TL_MONTH ";
      if ($tl['Due'] < $now ) $classes .= "TL_OVERDUE ";
      echo "<tr class='$classes' " . ($hide?'hidden':'') . ">";
      if (Access('Committee','TLine')) echo "<td><input type=checkbox name=E$tli class=SelectAllAble>";
      if (Access('SysAdmin')) echo "<td class=FullD hidden>" . $tli;
      echo "<td><a href=AddTimeLine.php?TLid=$tli>" . $tl['Title'] . "</a>";
      echo "<td>" . ($tl['Assigned'] ? $AllActive[$tl['Assigned']] : "<B>NOBODY</b>");
      echo "<td>" . $TL_Importance[$tl['Importance']];
      echo "<td>" . $TL_States[$tl['Status']];
      echo "<td" . ($tl['Due']<$now?" style='color:red;'":"") . ">" . date('d/m/Y',$tl['Due']);
      echo "<td class=FullD hidden>" .$tl['Year'];
      echo "<td class=FullD hidden>" . ["","Y"][$tl['Recuring']];
      echo "<td class=FullD hidden>" . ($tl['NextYearId']? ("<a href=AddTimeLine.php?TLid=" . $tl['NextYearId'] . ">Y</a>" ) : "");
      echo "<td>" . $tl['Notes'] . "\n";  
    }
    echo "</table>\n";
    if (Access('Committee','TLine')) {
      echo "<div class=floatright><input type=Submit name=ACTION value='Copy Recuring to $PLANYEAR' class=FullD hidden></div>";
      echo "Selected: <input type=Submit name=ACTION value=Cancel " .
           " onClick=\"javascript:return confirm('are you sure you want to cancel these?');\">, "; 
      echo "<input type=Submit name=ACTION value='Completed'>, ";
      echo "<input type=Submit name=ACTION value='Re Assign to'>: " . fm_select($AllActive,$_POST,'ReAssign',1);
      echo "<input type=Submit name=ACTION value='Copy to $PLANYEAR' class=FullD hidden> ";
      echo "<input type=Submit name=ACTION value='Set Recuring' class=FullD hidden> ";

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

