<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("Venue Use");

  global $db, $YEAR;
  include_once("ProgLib.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  $sides=&Select_Come_All();
  $Acts=&Select_All_Acts();
  $Other=&Select_All_Other();

  if (isset($_POST['v'])) { $ven = $_POST['v']; }
  else if (isset($_GET['v'])) { $ven = $_GET['v']; }
  else $ven=1;

  $venue = Get_Venue($ven);
  echo "<h2>Use of " . ($venue['SName']?$venue['SName']:$venue['ShortName']) . "</h2>";
  if ($venue['ShortName'] && $venue['ShortName'] != $venue['SName']) echo "<h3>(" . $venue['ShortName'] . ")</h3>\n";

  $res = $db->query("SELECT * FROM Events WHERE Year=$YEAR AND Venue=$ven ORDER BY Day, Start");
  $parname = '';
  if ($res) {
    while ($e = $res->fetch_assoc()) {
      $imps=array();
      $things = 0;
      for($i=1;$i<5;$i++) {
        if (isset($e["Side$i"])) { if ($ee = $e["Side$i"])  { $s = $sides[$ee];  if ($s) $imps[$s['Importance']][] = $s; }; };
        if (isset($e["Act$i"]))  { if ($ee = $e["Act$i"])   { $s = $Acts[$ee];   if ($s) $imps[$s['Importance']][] = $s; }; };
        if (isset($e["Other$i"])){ if ($ee = $e["Other$i"]) { $s = $Other[$ee];  if ($s) $imps[$s['Importance']][] = $s; }; };
      }

      if ($e['SubEvent'] <1) {
        $parname = $e['SName'];
        echo "<p class=Vuse2>" . $DayList[$e['Day']] . " " . $e['Start'] . " - " . $e['End'] . " " . $parname;
      }
      if ($imps) {
        if ($e['SubEvent'] < 0) echo "<p class=Vuse3>" . $e['Start'] . " - " . $e['SlotEnd'] . " ";
        if ($e['SubEvent'] > 0) { 
          echo "<p class=Vuse3>" . $e['Start'] . " - " . $e['End'] . " ";
          if ($e['SName'] && $e['SName'] != $parname) echo " &nbsp; &nbsp; " . $e['SName'] . " &nbsp; ";
        }

        echo "&nbsp; &nbsp; &nbsp; ";
        $ks = array_keys($imps);
        sort($ks);        
        foreach ( array_reverse($ks) as $imp) {
          if ($imp) echo "<span style='font-size:" . (15+$imp*2) . "'>";
            foreach ($imps[$imp] as $thing) {
            if ($things++) echo " , ";
            echo $thing['SName'];
            if (isset($thing['Type'])) echo " (" . $thing['Type'] . ") ";
          }
          if ($imp) echo "</span>";
        }
      } else {
        echo "&nbsp; &nbsp; &nbsp; ";
      };
      echo "</p>\n";
    }
  } else {
    echo "<h2>No Events here</h2>\n";
  }
  dotail();
?>
