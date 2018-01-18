<?php
  include_once("fest.php");

  dohead("Venue Details");

  include_once("ProgLib.php");
  include_once("int/MapLib.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  global $db, $YEAR;

  $V = $_GET['v'];
  $Ven = Get_Venue($V);

  echo "<h2 class=subtitle>" . $Ven['Name'] . "</h2>";

  /* Desc        Picture
     Address	 Map

     Programme
  */

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

  if ($Ven['Description']) echo $Ven['Description'] . "<p>\n";
  if ($Ven['Address']) echo "Address: " . $Ven['Address'] . " " . $Ven['PostCode'] ."<p>\n";
  if ($Ven['Bar'] || $Ven['Food'] || $Ven['BarFoodText']) {
    if ($Ven['Bar']) echo "<img src=/images/icons/baricon.png width=50 title='There is a bar'> ";
    if ($Ven['Food']) echo "<img src=/images/icons/foodicon.jpeg width=50 title='There is Food'> ";
    if ($Ven['BarFoodText']) echo " " . $Ven['BarFoodText'] . "<P>\n";
  }

  echo "<h2 class=subtitle>PROGRAMME OF EVENTS</h2>";
  echo "Click on the event name or time to get more detail<p>";

  $sides=&Select_Come_All();
  $Acts=&Select_All_Acts();
  $Other=&Select_All_Other();

  $res = $db->query("SELECT * FROM Events WHERE Year=$YEAR AND Venue=$V ORDER BY Day, Start");
  $parname = '';
  $Something = 0;

//  echo "<table class=EventShow>";
  if ($res) while ($e = $res->fetch_assoc()) {
    $Something = 1;
    $imps=array();
    $things = 0;
    for($i=1;$i<5;$i++) {
      if (isset($e["Side$i"])) { if ($ee = $e["Side$i"])  { $s = $sides[$ee];  if ($s) $imps[$s['Importance']][] = $s; }; };
      if (isset($e["Act$i"]))  { if ($ee = $e["Act$i"])   { $s = $Acts[$ee];   if ($s) $imps[$s['Importance']][] = $s; }; };
      if (isset($e["Other$i"])){ if ($ee = $e["Other$i"]) { $s = $Other[$ee];  if ($s) $imps[$s['Importance']][] = $s; }; };
    }

    if ($e['SubEvent'] <1) {
      $parname = $e['Name'];
      echo "<p class=Vuse2><a href=EventShow.php?e=" . $e['EventId'] . ">" . $DayList[$e['Day']] . " " . $e['Start'] . " - " . $e['End'] . " " . $parname . "</a>";
    }
    if ($imps) {
      if ($e['SubEvent'] < 0) echo "<p class=Vuse3>" . $e['Start'] . " - " . $e['SlotEnd'] . " ";
      if ($e['SubEvent'] > 0) { 
	echo "<p class=Vuse3>" . $e['Start'] . " - " . $e['End'] . " ";
	if ($e['Name'] && $e['Name'] != $parname) echo " &nbsp; &nbsp; " . $e['Name'] . " &nbsp; ";
      }

//      echo "&nbsp; &nbsp; &nbsp; ";
      $ks = array_keys($imps);
      sort($ks);	
      foreach ( array_reverse($ks) as $imp) {
        if ($imp) echo "<span style='font-size:" . (15+$imp*1) . "'>";
          foreach ($imps[$imp] as $thing) {
          if ($things++) echo " , ";
          echo $thing['Name'];
          if (isset($thing['Type'])) echo " (" . $thing['Type'] . ") ";
        }
        if ($imp) echo "</span>";
      }
    } else {
//      echo "&nbsp; &nbsp; &nbsp; ";
    };
    echo "</p>\n";
  }
  if (!$Something) echo "<h3>No Currently Scheduled Events Here</h3>\n";

  dotail();
?>
