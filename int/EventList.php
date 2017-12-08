<?php
  include_once("fest.php");
  A_Check('Staff','Venues');

  dostaffhead("List Events","<script src=/js/Tools.js></script>");
  global $db,$Event_Types;
  $yn = array('','Y');
  include("ProgLib.php");
  include("EventCheck.php");

//var_dump($Event_Types);

  if (isset($_POST{'ACTION'})) {
    foreach ($_POST as $f=>$v) {
      if (preg_match('/E(\d*)/',$f,$res)) {
        $ev=$res[1];
	$Event = Get_Event($ev);

        switch ($_POST{'ACTION'}) {
        case 'Delete' :
	  $Event['Year'] -= 1000;
	  $Event['SubEvent'] = 0;
	  break;

        case 'Rename as':
	  $Event['Name'] = $_POST{'NewName'};
	  break;

        case 'Move by':
	  if ($delta = $_POST{'Minutes'}) {
	    $Event['Start'] = timeadd($Event['Start'],$delta);
	    $Event['End'] = timeadd($Event['End'],$delta);
	    if ($Event['SlotEnd']) $Event['SlotEnd'] = timeadd($Event['SlotEnd'],$delta);
	  }
	  break;

        case 'Move to':
	  if ($v = $_POST{'v'}) $Event['Venue'] = $v;
	  break;
        }
        Put_Event($Event);
      }
    }
  }

  $Venues = Get_Venues();
  if (isset($_POST{'LIST'})) {
    $se = 0;
    $SubE = " Year=$YEAR ";
    echo "<h2>List All Events</h2>";
  } else if (isset($_GET{'se'})) {
    $se = $_GET{'se'};
    $SubE = " ( SubEvent='$se' OR EventId='$se' )";
    echo "<h2>List Sub Events</h2>";
  } else if (isset($_POST{'se'})) {
    $se = $_POST{'se'};
    $SubE = " ( SubEvent='$se' OR EventId='$se' )";
    echo "<h2>List Sub Events</h2>";
  } else {
    $se = 0;
    $SubE = " SubEvent<=0 AND Year=$YEAR";
    echo "<h2>List Events</h2>";
  }

  $coln = 1;  // Start at 1 because of select all
  echo "<form method=post action=EventList.php>";
  echo "Click on Id/Name to edit, on Show for public page.<p>\n";
  if ($se) echo fm_hidden('se',$se);
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><input type=checkbox name=SelectAll id=SelectAll onchange=ToolSelectAll(event)>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Event Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Day</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Start</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>End</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Venue</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Public</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Price</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Size</a>\n";
  if ($se == 0) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sub Events</a>\n";
  if ($se != 0) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>With</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Show</a>\n";
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM Events WHERE $SubE ORDER BY Day, Start, Type");
  
  if ($res) {
    while ($evnt = $res->fetch_assoc()) {
      $i = $evnt['EventId'];
      echo "<tr><td>";
      echo "<input type=checkbox name=E$i class=SelectAllAble>";
      echo "<td>$i<td>";
      if (Access('Staff','Venues')) echo "<a href=EventAdd.php?e=$i>";
      if (strlen($evnt['Name']) >2) { echo $evnt['Name'] . "</a>"; } else { echo "Nameless</a>"; };
      echo "<td>" . $DayList[$evnt['Day']] . "<td>" . $evnt['Start'] . "<td>";
      if ($se > 0 && $evnt['SubEvent'] < 0) { echo $evnt['SlotEnd']; } else { echo $evnt['End']; }; 
      echo "<td>" . $Venues[$evnt['Venue']] . "<td>" . $Event_Types[$evnt['Type']];
      echo "<td>" . $Public_Event_Types[$evnt['Public']];
      echo "<td>" ; 
      if ($evnt['SubEvent'] <= 0 ) {
	if ($evnt['Price1']) { echo "&pound;" . $evnt['Price1']; } else echo "Free";
	if ($evnt['Price2']) echo " /&pound;" . $evnt['Price2']; 
	if ($evnt['DoorPrice']) echo " /&pound;" . $evnt['DoorPrice']; 
      }
      echo "<td>" .($evnt['BigEvent'] ? "Big" : "Normal" );
      if ($se == 0) {
        if ($evnt['SubEvent'] == 0) { echo "<td>No\n"; }
	else { echo "<td><a href=EventList.php?se=$i>Yes</a>\n"; }
      }
      if ($se != 0) {
	echo "<td>";
	if ($evnt['SubEvent']>0) {
	  echo Get_Event_Participants($i,2) ;
	} else {
	}
      }
      echo "<td>" . $evnt['Notes'];
      if ($se > 0 && $evnt['SubEvent'] < 0) echo " Full end: " . $evnt['End'] . " PARENT";
      echo "<td><a href=EventShow.php?e=$i>Show</a>\n";
    }
  }
  echo "</tbody></table>\n";
  echo "Selected: <input type=Submit name=ACTION value=Delete " .
	" onClick=\"javascript:return confirm('are you sure you want to delete these?');\">, "; 
  echo "<input type=Submit name=ACTION value='Rename as'> ";
  echo "<input type=text name=NewName>, <input type=Submit name=ACTION value='Move by'> ";
  echo "<input type=text name=Minutes size=4> Minutes, ";
  echo "<input type=Submit name=ACTION value='Move to'> " . fm_select(Get_Venues(),0,'v') . ",";
  echo "<input type=Submit name=LIST value='Show All'><br>\n";
  echo "</form>\n";

  if (Access('Committee','Venues')) {
    echo "<h2><a href=EventAdd.php>Add Event</a></a>";

    echo "<h2>Checking...</h2>";
    EventCheck();

  }
  dotail();
?>
