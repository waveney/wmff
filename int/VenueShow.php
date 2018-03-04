<?php
  include_once("fest.php");

  dohead("Venue Details");

  include_once("ProgLib.php");
  include_once("int/MapLib.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("DispLib.php");
  
  global $db, $YEAR;

function PrintImps(&$imps) {
  $ks = array_keys($imps);
  sort($ks);	
  foreach ( array_reverse($ks) as $imp) {
    if ($imp) echo "<span style='font-size:" . (15+$imp*1) . "'>";
      foreach ($imps[$imp] as $thing) {
        if ($things++) echo ", ";
        $str = "<a href=/int/ShowDance.php?sidenum=" . $thing['SideId'] . ">" . NoBreak($thing['SName']) . "</a>";
        if (isset($thing['Type']) && (strlen($thing['Type'])>1)) $str .= NoBreak(" (" . $thing['Type'] . ")");
        if ($thing['Photo']) $str .= " <a href=/int/ShowDance.php?sidenum=" . $thing['SideId'] . 
		"><img style='vertical-align:middle;max-height:" . (100+20*$imp) .";' height=" . (100+20*$imp) . " src=" . $thing['Photo'] . "></a>";
        echo $str;
      }
    if ($imp) echo "</span>";
  }
}

  $V = $_GET['v'];
  if (!is_numeric($V)) exit("Invalid Venue Number");
  $Ven = Get_Venue($V);
  if ($Ven['IsVirtual']) {
    $VirtVen = $Ven;
    $VenList = array();
    $VenNames = array();
    $Vens = Get_Real_Venues(1);  
    foreach($Vens as $vi=>$ov) if ($ov['PartVirt'] == $V) {
      $VenList[] = $vi;
      $VenNames[] = $ov['SName'];
      foreach ($ov as $key=>$val) if ($val) $Ven[$key] = $val;
    }
  }

  echo "<h2 class=subtitle>" . $Ven['SName'] . "</h2>";

  /* Desc        Picture
     Address	 Map

     Programme
  */

  if ($Ven['Description']) echo $Ven['Description'] . "<p>\n";
  if ($Ven['Address']) echo "Address: " . $Ven['Address'] . " " . $Ven['PostCode'] ."<p>\n";
  echo "<button onclick=ShowDirect($V)>Directions</button>\n";
  if ($Ven['Bar'] || $Ven['Food'] || $Ven['BarFoodText']) {
    if ($Ven['Bar']) echo "<img src=/images/icons/baricon.png width=50 title='There is a bar'> ";
    if ($Ven['Food']) echo "<img src=/images/icons/foodicon.jpeg width=50 title='There is Food'> ";
    if ($Ven['BarFoodText']) echo " " . $Ven['BarFoodText'] . "<P>\n";
  }

  echo "<div class=venueimg>";
    if ($Ven['Image']) {
      echo "<img width=100% src=" . $Ven['Image'] . ">";
    } else {
      echo "No Image Yet<p>";
    }
    echo "<p><div id=MapWrap>";
    echo "<div id=DirPaneWrap><div id=DirPane><div id=DirPaneTop></div><div id=Directions></div></div></div>";
    echo "<p><div id=map></div></div>";
    echo "</div>\n";
    Init_Map(0,$V,18);

  $ETs = Get_Event_Types(1);
  $AllDone = 1;
  foreach ($ETs as $ei=>$et) if ($et['State'] != 4) $AllDone = 0;
  $comps = array('Family','Special');
  foreach($comps as $c) if ($MASTER[$c . "State"] != 4) $AllDone = 0;

  echo "<h2 class=subtitle>" . ($AllDone?'':" CURRENT ") . "PROGRAMME OF EVENTS" . ($AllDone?'':" (Others may follow)") . "</h2>";
  echo "Click on the event name or time to get more detail.<p>";

  $sides=&Select_Come_All();
  $Acts=&Select_Act_Full();
  $Others=&Select_Other_Full();

  $res = $db->query("SELECT * FROM Events WHERE Year=$YEAR AND (Venue=$V OR BigEvent=1) ORDER BY Day, Start");
  if (!$res) {
    "<h3>There are currently no scheduled events here</h3>\n";
    dotail();
    exit;
  }
  
  $NotAllFree=0;
  while ($e = $res->fetch_assoc()) {
    if ($e['BigEvent']) {
      $O = Get_Other_Things_For($e['EventId']);
      $found = ($e['Venue'] == $V); 
      if (!$O && !$found) continue;
      foreach ($O as $i=>$thing) {
	switch ($thing['Type']) {
	  case 'Venue':
	    if ($thing['Identifier']==$V) $found = 1; 
	    break;
	  case 'Side':
            $e['With'][$sides[$thing['Identifier']]['Importance']][] = $sides[$thing['Identifier']];
	    break;
	  case 'Act':
            $e['With'][$Acts[$thing['Identifier']]['Importance']][] = $Acts[$thing['Identifier']];
	    break;
	  case 'Other':
            $e['With'][$Others[$thing['Identifier']]['Importance']][] = $Others[$thing['Identifier']];
	    break;
	  default:
	    break;
	}
      }
      if ($found == 0) continue;
    }
    $EVs[$e['EventId']] = $e;
    if ($e['DoorPrice']) $NotAllFree=1;
  }
  if (!isset($EVs) || !$EVs) {
    "<h3>There are currently no scheduled events here</h3>\n";
    dotail();
    exit;
  }

  if (!$NotAllFree) echo "All events here are Free.<p>\n";

  $lastevent = -99;
  foreach ($EVs as $ei=>$e) {
    $eid = $e['EventId'];
    if (DayTable($e['Day'],"Events")) {
      echo "<tr><td>Time<td colspan=2>What<td>With";
      if ($NotAllFree) echo "<td>Price\n";
      $lastevent = -99;
    }

    Get_Imps($e,$imps,1,(Access('Staff')?1:0));
    $things = 0;
    if ($e['With']) $imps = $e['With'];

    if ($e['SubEvent'] <0) { // has subes
      if ($e['LongEvent'] && !$imps) continue;
      $parname = $e['SName']; 
      $lastevent = $ei;
      echo "<tr><td><a href=EventShow.php?e=$eid>" . timecolon($e['Start']) . " - " . timecolon($e['End']) . 
		"</a><td colspan=2><a href=EventShow.php?e=$eid>" . $parname . "</a>";
      if ($e['Description']) echo "<br>" . $e['Description'];
      echo "<td>&nbsp;";
      if (!$e['LongEvent']) if ($NotAllFree) echo "<td>" . Price_Show($e);

      if ($imps) {
        if (!$e['LongEvent']) echo "<tr><td>&nbsp;<td>" . timecolon($e['Start']) . " - " . timecolon($e['SlotEnd']) . "<td>&nbsp;<td>";
        PrintImps($imps);
        if ($NotAllFree) echo "<td>" . Price_Show($e);
      }
    } else if ($e['SubEvent'] == 0) { // Is stand alone
      $lastDay = $e['Day'];
      $parname = $e['SName'];
      echo "<tr><td><a href=EventShow.php?e=$eid>" . timecolon($e['Start']) . " - " . timecolon($e['End']) . 
		"</a><td colspan=2><a href=EventShow.php?e=$eid>" . $parname . "</a>";
      if ($e['Description']) echo "<br>" . $e['Description'];
      echo "<td>";
      PrintImps($imps);
      if ($NotAllFree) echo "<td>" . Price_Show($e);
    } else { // Is a sube
      if ($e['LongEvent'] && $lastevent != $e['SubEvent']) {
	$lastevent = $e['SubEvent'];
        $pare = &$EVs[$lastevent]; 
        $parname = $pare['SName']; 
        echo "<tr><td><a href=EventShow.php?e=$lastevent>" . timecolon($e['Start']) . " - " . timecolon($e['End']) . 
		"</a><td colspan=2><a href=EventShow.php?e=$lastevent>" . $parname . "</a>";
        if ($pare['Description']) echo "<br>" . $pare['Description'];
	echo "<td>";
        if ($imps) PrintImps($imps); 
        if ($NotAllFree) echo "<td>" . Price_show($EVs[$e['SubEvent']]);
      } else if ($imps) {
        echo "<tr><td>&nbsp;<td colspan=2>";
//	if ($parname != $e['SName']) 
	echo "<a href=EventShow.php?e=" . $e['SubEvent'] . ">" . $e['SName'] . "</a><br>";
	echo timecolon($e['Start']) . " - " . timecolon($e['End']) . "<td>";
        PrintImps($imps);
        if ($NotAllFree) echo "<td>&nbsp;";
      }
    }
  }
  echo "</table>\n";

  dotail();
?>
