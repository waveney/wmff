<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Manage Event Types");

  include_once("ProgLib.php");
  include_once("TradeLib.php");
  global $EType_States,$PLANYEAR;

  echo "<div class='content'><h2>Manage Event Types</h2>\n";
  echo "Please don't have too many types.<p>\n";
  echo "The only event types that should be not public are Sound Checks (probably)<p>\n";
  echo "Set Inc Type to indicate event type in description if it is not part of the events name.<p>";
  echo "State drives lots: - set to draft to enable the performers to see their own events. Set to complete when all events of given type are in<p>\n";
  echo "Set <b>No Part</b> if event type is valid without any participants.<p>";
  echo "First Year - first year this event type is listed - prevents backtracking.<p>\n";
  
  $Types = Get_Event_Types(1);
  if (UpdateMany('EventTypes','Put_Event_Type',$Types,1)) $Types = Event_Types_ReRead();

  $coln = 0;
  echo "<h2>Event Types</h2><p>";
  echo "Set the Not critical flag for sound checks - means that this event type does not have to be complete for contract signing.<p>";
  echo "Set the Use Imp flag to bring headline particpants to top of an event, they still get bigger fonts.<p>";
  echo "Set Format to drive EventShow rules 0=All Large, 2=Switch to large at Importance-High, 9+=All Small<p>";
  echo "<form method=post action=EventTypes.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Event Type</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Plural</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Public</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Has Dance</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Has Music</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Has Other</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Not Critical</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Use Imp</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Format</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Inc Type</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>No Part</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>First Year</a>\n";
  echo "</thead><tbody>";
  foreach($Types as $t) {
    $i = $t['ETypeNo'];
    echo "<tr><td>$i" . fm_text1("",$t,'SN',1,'','',"SN$i");
    echo          fm_text1("",$t,'Plural',1,'','',"Plural$i");
    echo "<td>" . fm_checkbox('',$t,'Public','',"Public$i");
    echo "<td>" . fm_checkbox('',$t,'HasDance','',"HasDance$i");
    echo "<td>" . fm_checkbox('',$t,'HasMusic','',"HasMusic$i");
    echo "<td>" . fm_checkbox('',$t,'HasOther','',"HasOther$i");
    echo "<td>" . fm_checkbox('',$t,'NotCrit','',"NotCrit$i");
    echo "<td>" . fm_checkbox('',$t,'UseImp','',"UseImp$i");
    echo fm_number1('',$t,'Format','','min=0 max=1000',"Format$i");
    echo "<td>" . fm_select($EType_States,$t,'State',0,'',"State$i");
    echo "<td>" . fm_checkbox('',$t,'IncType','',"IncType$i");
    echo "<td>" . fm_checkbox('',$t,'NoPart','',"NoPart$i");
    echo fm_number1('',$t,'FirstYear','','',"FirstYear$i");
    echo "\n";
  }
  echo "<tr><td><td><input type=text name=SN0 >";
  echo "<td><input type=text name=Plural0 >";
  echo "<td><input type=checkbox name=Public0>";
  echo "<td><input type=checkbox name=HasDance0>";
  echo "<td><input type=checkbox name=HasMusic0>";
  echo "<td><input type=checkbox name=HasOther0>";
  echo "<td><input type=checkbox name=NotCrit0>";
  echo "<td><input type=checkbox name=UseImp0>";
  echo "<td><input type=number min=0 max=1000 name=Format0>";
  echo "<td>" . fm_select($EType_States,$t,"State0");
  echo "<td><input type=checkbox name=IncType0>";
  echo "<td><input type=checkbox name=NoPart0>";
  echo "<td><input type=number name=FirstYear0 value=$PLANYEAR>";
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
