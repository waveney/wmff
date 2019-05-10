<?php
  include_once("int/fest.php");

  set_ShowYear();
  $Types = $Type = $_GET['t'];
  if (strlen($Type) > 20) $Types = $Type = 'Dance';


  include_once("int/ProgLib.php");
  include_once("int/DateTime.php");
  global $db,$YEAR,$PLANYEAR,$SHOWYEAR,$MASTER,$DayList,$DayLongList;

  $Ets = Get_Event_Types(1);
  $Vens = Get_Venues(1);

  $Extras = array('Music'=>' OR e.ListMusic=1'); // Need Dance Equiv

//  var_dump($Type);
//  var_dump($Ets);
  //  Need check if year < first
  $Ett = -1;
  foreach($Ets as $eti=>$et) if ($et['SN'] == $Type) $Ett = $eti;

  $xtr = (isset($Extras[$Type]))? $Extras[$Type] : '';
  $Evs = array();
  $Complete = 0;
  $BackStop = 2018;
  
  if ($YEAR == $PLANYEAR) {
    $restrict = "AND ( e.Public=1 OR (e.Type=t.ETypeNo AND t.State>1 AND e.Public<2 ))";
  } else {
    $restrict = "";
    $Complete = 4;
  }
  
  $MapFeat = 0;
  $Banner = 1;
  if ($Ett >= 0) { 
    $qry = "SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND ( e.Type=$Ett $xtr ) AND ( e.SubEvent<1 OR e.ShowSubevent=1 ) AND e.Venue!=0 " .
                "$restrict ORDER BY e.Day, e.Start";
//    echo "$qry<p>";
    $ans = $db->query($qry); 
    if ($ans) while ($e = $ans->fetch_assoc()) $Evs[] = $e;
    if (count($Evs) > 1) $Types = $Ets[$Ett]['Plural'];
    if ($YEAR == $PLANYEAR) $Complete = $Ets[$Ett]['State'];
    $BackStop = $Ets[$Ett]['FirstYear'];
    if ($Ets[$Ett]['Banner']) $Banner = $Ets[$Ett]['Banner'];
    if ($Ets[$Ett]['MapFeatNum']) $MapFeat = $Ets[$Ett]['MapFeatNum'];
  } else { // Handle other Sherlock calls
    switch ($Type) {
      case 'Family':
        $ans = $db->query("SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND e.Family=1 AND e.SubEvent<1 AND e.Venue!=0 " .
                "$restrict ORDER BY e.Day, e.Start"); 
        if ($ans) while ($e = $ans->fetch_assoc()) $Evs[] = $e;
        $Types = "Family Event";
        if (count($Evs) != 1) $Types .= "s";
        if ($YEAR == $PLANYEAR) $Complete = $MASTER[$Type . 'State'];
        $MapFeat = 7;
        break;
      case 'Special':
        $ans = $db->query("SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND e.Special=1 AND (e.SubEvent<1 OR e.LongEvent=1) AND e.Venue!=0 " .
                "$restrict ORDER BY e.Day, e.Start"); 
        if ($ans) while ($e = $ans->fetch_assoc()) $Evs[] = $e;
        $Types = "Special Event";
        if (count($Evs) != 1) $Types .= "s";
        if ($YEAR == $PLANYEAR) $Complete = $MASTER[$Type . 'State'];
        // No MapFeat yet
        break;
      default:
        break;
    }
  }


  dohead("Timetable: $Types",[],$Banner);
  
  $Titles = array("", // Not used
                "Currently known $Types for $YEAR, there will be more", // Draft
                "Currently known $Types for $YEAR, there will be more", // Partial
                "Currently known $Types for $YEAR, there may be more", // Provisional
                "$Types for $YEAR", // Complete
                );

  $NotAllFree = 0;
  foreach($Evs as $e) if ($e['Price1']) $NotAllFree = 1;

  if ($Evs && $Complete) {
    if ($Complete <4 || $YEAR!=$SHOWYEAR) echo "<h2>" . $Titles[$Complete] . "</h2>";
    
    if ($MapFeat>0) {
      include_once("int/MapLib.php");
      echo "<h3 class='DanceMap Fakelink' onclick=$('.DanceMap').toggle()>Show $Type Locations</h3>";
      echo "<h3 class='DanceMap FakeLink' onclick=$('.DanceMap').toggle() hidden>Hide $Type Locations</h3>";
      echo "<div class=DanceMap hidden><div id=MapWrap>";
      echo "<div id=DirPaneWrap><div id=DirPane><div id=DirPaneTop></div><div id=Directions></div></div></div>";
      echo "<div id=map></div></div>";
      Init_Map(-1,0,17,$MapFeat);
      echo "</div>";    
    }
    
    echo "<div class='FullWidth WhenTable'>";

    if ($NotAllFree == 0) echo "All $Types are free.<p>";

    echo "Click on the event name for more information.<p>";

    foreach ($Evs as $i=>$E) {
      $eid = $E['EventId'];
      if (DayTable($E['Day'],$Types,($Complete>2?'':'(More to come)'),'','style=min-width:800')) {
        echo "<tr><td>When<td>What<td>Where<td>Description" . ($NotAllFree?"<td>Price\n":"\n");
      }

      echo "<tr>";
      echo "<td>"; 
        echo timecolon($E['Start']) . " to " . timecolon($E['End']);
      echo "<td><strong><a href=/int/EventShow.php?e=$eid>" . $E['SN'] . "</a></strong>"; 
      echo "<td><a href=/int/VenueShow.php?v=" . $E['Venue'] . ">" . $Vens[$E['Venue']]['SN'] . "</a>";
      if ($E['BigEvent']) {
        $Others = Get_Other_Things_For($eid);
        foreach ($Others as $i=>$o) {
          if ($o['Type'] == 'Venue') echo ", <a href=/int/VenueShow.php?v=" . $o['Identifier'] . ">" . $Vens[$o['Identifier']]['SN'] . "</a>";
        }
      }
      echo "<td>";
        if ($E['Description']) echo $E['Description'] . "<p>";
        if ($E['BigEvent']) {
          echo Get_Other_Participants($Others,0,1,15,1, 'With: ',$E);
        } else {
          echo Get_Event_Participants($eid,0,1,15,1, 'With: ');
        }
      if ($NotAllFree) echo "<td>" . Price_Show($E,1);
      echo "\n";
    }
    echo "</table></div><p>";

  } else {
    echo "<h3>Sorry there are currently no announced $Types for $YEAR, please check back later</h3>";
  }
  
  if ($YEAR > $BackStop) {
    echo "<h3><a href=Sherlock.php?t=$Type&Y=" . ($YEAR-1) . "> $Types in " . ($YEAR-1) . "</h3></a>";
  }
  dotail();

?>

