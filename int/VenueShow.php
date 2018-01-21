<?php
  include_once("fest.php");

  dohead("Venue Details");

  include_once("ProgLib.php");
  include_once("int/MapLib.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("DispLib.php");
  
  global $db, $YEAR;

  $V = $_GET['v'];
  $Ven = Get_Venue($V);

function PrintImps(&$imps) {
  $ks = array_keys($imps);
  sort($ks);	
  foreach ( array_reverse($ks) as $imp) {
    if ($imp) echo "<span style='font-size:" . (15+$imp*1) . "'>";
      foreach ($imps[$imp] as $thing) {
        if ($things++) echo ", ";
        $str = "<a href=/int/ShowDance.php?sidenum=" . $thing['SideId'] . ">" . NoBreak($thing['Name']) . "</a>";
        if (isset($thing['Type']) && (strlen($thing['Type'])>1)) $str .= NoBreak(" (" . $thing['Type'] . ")");
        echo $str;
      }
    if ($imp) echo "</span>";
  }
}

  echo "<h2 class=subtitle>" . $Ven['Name'] . "</h2>";

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

  $comps = array('Ceildih','Session','Workshop','Concert','Family','Comedy','Special','Craft');
  $AllDone = 1;
  foreach($comps as $c) if (!$MASTER[$c . "Complete"]) $AllDone = 0;

  echo "<h2 class=subtitle>" . ($AllDone?'':" CURRENT ") . "PROGRAMME OF EVENTS" . ($AllDone?'':" (Others may follow)") . "</h2>";
  echo "Click on the event name or time to get more detail.<p>";

  $sides=&Select_Come_All();
  $Acts=&Select_Act_Come_All();
  $Other=&Select_Other_Come();

  $res = $db->query("SELECT * FROM Events WHERE Year=$YEAR AND Venue=$V ORDER BY Day, Start");
  if (!$res) {
    "<h3>There are currently no scheduled events here</h3>\n";
    dotail();
    exit;
  }
  
  $NotAllFree=0;
  while ($e = $res->fetch_assoc()) {
    $EVs[] = $e;
    if ($e['DoorPrice']) $NotAllFree=1;
  }

  if (!$NotAllFree) echo "All events here are Free.<p>\n";

  foreach ($EVs as $ei=>$e) {
    $eid = $e['EventId'];
    if (DayTable($e['Day'],"Events")) {
      echo "<tr><td>Time<td>What<td>With";
      if ($NotAllFree) echo "<td>Price\n";
    }

    Get_Imps($e,$imps);
    $things = 0;

    if ($e['SubEvent'] <0) {
      $parname = $e['Name']; // has subes
      echo "<tr><td><a href=EventShow.php?e=$eid>" . $e['Start'] . " - " . $e['End'] . "</a><td><a href=EventShow.php?e=$eid>" . $parname . "</a>";
      if ($e['Description']) echo "<br>" . $e['Description'];
      echo "<td>&nbsp;";
      if ($NotAllFree) echo "<td>" . Price_Show($e);

      if ($imps) {
        echo "<tr><td>" . $e['Start'] . " - " . $e['SlotEnd'] . "<td>&nbsp;<td>";
        PrintImps($imps);
        if ($NotAllFree) echo "<td>&nbsp;";
      }
    } else if ($e['SubEvent'] == 0) { // Is stand alone
      $parname = $e['Name'];
      echo "<tr><td><a href=EventShow.php?e=$eid>" . $e['Start'] . " - " . $e['End'] . "</a><td><a href=EventShow.php?e=$eid>" . $parname . "</a>";
      if ($e['Description']) echo "<br>" . $e['Description'];
      echo "<td>";
      PrintImps($imps);
      if ($NotAllFree) echo "<td>" . Price_Show($e);
    } else { // Is a sube
      if ($imps) {
        echo "<tr><td>" . $e['Start'] . " - " . $e['End'] . "<td>&nbsp;<td>";
        PrintImps($imps);
        if ($NotAllFree) echo "<td>&nbsp;";
      }
    }
  }
  echo "</table>\n";

  dotail();
?>
