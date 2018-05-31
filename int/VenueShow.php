<?php
  include_once("fest.php");

  include_once("ProgLib.php");
  include_once("int/MapLib.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("DispLib.php");
  
  global $db, $YEAR,$ll,$SpecialImage,$Pictures;

function PrintImps(&$imps,$NotAllFree,$Price,$rows,$ImpC,$maxwith=100) {
  global $ll,$SpecialImage,$Pictures;
  $things = 0;
//var_dump($imps);
  if ($ImpC) {
    $ks = array_keys($imps);
    sort($ks);	
    if ($ImpC > $maxwith) echo "With $ImpC participants including: ";
    foreach ( array_reverse($ks) as $imp) {
      if ($imp) echo "<span style='font-size:" . (15+$imp*1) . "'>";
        foreach ($imps[$imp] as $thing) {
          if ($things >= $maxwith) continue;
          $things++;
	  if ((($things % $ll) == 1) && ($things != 1)) echo "<tr>"; // <td><td>";
          echo ($ll > 1 && $things == $ImpC && ($ImpC %2) == 1)?"<td colspan=$ll>":"<td>";
	  $scale = $thing['Importance'];
//var_dump($thing);
	  if (( $thing['IsASide'] && $thing['Coming'] != 2) || (($thing['IsAnAct'] || $thing['IsOther']) && $thing['YearState'] < 2)) {
	    echo "<a href=/int/ShowDance.php?sidenum=" . $thing['SideId'] . ">" . NoBreak($thing['SName'],3) . "</a>";
	    echo " are no longer coming";
	  } else {
	    echo " <a href=/int/ShowDance.php?sidenum=" . $thing['SideId'] . ">";
//echo "HELLO $Pictures<p>";
	    if ($Pictures) {
	      if ($SpecialImage) { echo "<img style='vertical-align:middle;float:left;border:5;margin:2;max-height:" . 
		    (100+20*$scale) .";' height=" . (100+20*$scale) . " src=" . $SpecialImage . ">";
	      } elseif ($thing['Photo']) echo "<img style='vertical-align:middle;float:left;border:5;margin:2;max-height:" . 
		    (100+20*$scale) .";' height=" . (100+20*$scale) . " src=" . $thing['Photo'] . ">";
	    }
	    echo "<a href=/int/ShowDance.php?sidenum=" . $thing['SideId'] . ">" . NoBreak($thing['SName'],3) . "</a>";
            if (isset($thing['Type']) && (strlen($thing['Type'])>1)) echo " " . NoBreak("(" . $thing['Type'] . ")");
	  }
          if ($NotAllFree && ($things == $ll)) echo "<td rowspan=$rows valign=top>$Price";
        }
      if ($imp) echo "</span>";
    } 
  } else  echo "<td>&nbsp;";
  if ($things > $ll && ($things % $ll) == 1) echo "<td>&nbsp;";
  if ($NotAllFree && ($things < $ll)) echo "<td rowspan=$rows valign=top>$Price";
}

  $V = (isset($_GET['v'])? $_GET['v']: $_POST['v']);

  $Mode = (isset($_GET['Mode']) ? $_GET['Mode'] : 0 ) ; // If present show everything
  $Poster = (isset($_GET['Poster']) ? $_GET['Poster'] : 0 ) ; // If present do Poster Mode - No Navigation/Trailer, but add new trailer with web and QR

  if ($Poster) {
    doheadpart("Venue Details","js/qrcode.js","files/festconstyle.css");
    echo "</head><body>\n";
    $Pictures = (isset($_POST['Pics']) && ($_POST['Pics'] == 'on'));
  } else {
    dohead("Venue Details");
    $Pictures = 1;
  }

  if (!is_numeric($V)) exit("Invalid Venue Number");
  $Ven = Get_Venue($V);

  if ($Ven['PartVirt'] && !$Poster) {
    $V = $Ven['PartVirt'];
    $Ven = Get_Venue($V);
  }

  if ($Ven['IsVirtual']) {
    $VirtVen = $Ven;
    $VenName = $Ven['SName'];
    $VenList = array();
    $VenNames = array();
    $Vens = Get_Real_Venues(1);  
    foreach($Vens as $vi=>$ov) if ($ov['PartVirt'] == $V) {
      $VenList[] = $vi;
      $VenNames[$ov['VenueId']] = preg_replace('/' . $VenName . '/','',$ov['SName']);
    }
  } else {
    $VirtVen = 0;
  }

  if ($Poster) {
    echo "<div id=Poster>"; 
    echo "<center>";
    echo "<h2 class=subtitle id=Postertitle>";
    echo "<img src=/images/icons/Long-Banner-Logo.png height=100><br>";
    echo $Ven['SName'] . "</h2>";
  } else {
    echo "<h2 class=subtitle>" . $Ven['SName'] . "</h2>";
  }

  /* Desc        Picture
     Address	 Map

     Programme
  */

  if (!$Poster) {
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
   }

  $ETs = Get_Event_Types(1);

  $sides=&Select_Come_All();
  $Acts=&Select_Act_Full();
  $Others=&Select_Other_Full();

  $xtr = $Mode?'':"AND (e.Public=1 OR (e.Type=t.ETypeNo AND t.State>1 AND e.Public<2 )) AND t.Public=1 ";
  if ($Poster) {
    if ($_POST['DAYS'] == 1 ) $xtr .= " AND Day!=2";
    if ($_POST['DAYS'] == 2 ) $xtr .= " AND Day=2";
  }

  $VenList[] = $V;
  if ($Ven['IsVirtual']) {
    $res = $db->query("SELECT DISTINCT e.* FROM Events e, Venues v, EventTypes t WHERE e.Year=$YEAR AND (e.Venue=$V OR e.BigEvent=1 OR " .
                "( e.Venue=v.VenueId AND v.PartVirt=$V )) $xtr ORDER BY Day, Start");
    $parts = $db->query("SELECT VenueId FROM Venues v WHERE v.PartVirt=$V");
    while ($part = $parts->fetch_assoc()) $VenList[] = $part['VenueId'];
  } else {
    $res = $db->query("SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND (e.Venue=$V OR e.BigEvent=1) $xtr " .
                " ORDER BY Day, Start");
  }

  if (!$res || $res->num_rows==0) {
    echo "<h3>There are currently no publicised events here</h3>\n";
    dotail();
    exit;
  }
  
  $NotAllFree=0; 
  $LastDay = -99;
  $ETused = array();
  $MaxEvDay = array();
  while ($e = $res->fetch_assoc()) {
    if ($LastDay != $e['Day']) { $MaxEv = 0; $LastDay = $e['Day']; };
    $WithC = 0;
    if ($e['BigEvent']) {
      $O = Get_Other_Things_For($e['EventId']);
      $found = ($e['Venue'] == $V); 
      if (!$O && !$found) continue;
      foreach ($O as $i=>$thing) {
	switch ($thing['Type']) {
	  case 'Venue':
	    if (in_array($thing['Identifier'],$VenList)) $found = 1; 
	    break;
	  case 'Side':
            if ($thing['Identifier']) $e['With'][($Poster?$sides[$thing['Identifier']]['Importance']:0)][] = $sides[$thing['Identifier']];
	    $WithC++;
	    break;
	  case 'Act':
            if ($thing['Identifier']) $e['With'][($Poster?$Acts[$thing['Identifier']]['Importance']:0)][] = $Acts[$thing['Identifier']];
	    $WithC++;
	    break;
	  case 'Other':
            if ($thing['Identifier']) $e['With'][($Poster?$Others[$thing['Identifier']]['Importance']:0)][] = $Others[$thing['Identifier']];
	    $WithC++;
	    break;
	  default:
	    break;
	}
      }
      if ($found == 0) continue;
    } else {
      for($i=1;$i<5;$i++) {
        if ($e["Side$i"]) $WithC++;
        if ($e["Act$i"]) $WithC++;
        if ($e["Other$i"]) $WithC++;
      }
    }
    $EVs[$e['EventId']] = $e;
    $ETused[$e['Type']] = 1;
    $MaxEvDay[$e['Day']] = $MaxEv = max($MaxEv,$WithC);
    if ($e['DoorPrice'] || $e['Price1'] || $e['SpecPrice']) $NotAllFree=1;
  }

  if (!isset($EVs) || !$EVs) {
    echo "<h3>There are currently no publicised events here</h3>\n";
    dotail();
    exit;
  }

  $AllDone = 1;
  foreach ($ETs as $ei=>$et) if (isset($ETused[$ei]) && $ETused[$ei] && $et['State'] != 4) $AllDone = 0;
  $comps = array('Family','Special');
  foreach($comps as $c) if ($MASTER[$c . "State"] != 4) $AllDone = 0;

  if (!$Poster) {
    echo "<h2 class=subtitle>" . ($AllDone?'':" CURRENT ") . "PROGRAMME OF EVENTS" . ($AllDone?'':" (Others may follow)") . "</h2></center>";
    echo "Click on the event name or time to get more detail.<p>";
    if (!$NotAllFree && $Ven['SupressFree']==0) echo "All events here are Free.<p>\n";
  }

//var_dump($VirtVen);


  $lastevent = -99;
  foreach ($EVs as $ei=>$e) {
    $eid = $e['EventId'];
    $ll = ($MaxEvDay[$e['Day']]<2?1:2);
    if (DayTable($e['Day'],"Events")) {
      echo "<tr><td>Time<td >What<td colspan=$ll>With";
      if ($NotAllFree) echo "<td>Price\n";
      $lastevent = -99;
    }

    $SpecialImage = 0;
    Get_Imps($e,$imps,1,$Mode);
    $things = 0;
    if (isset($e['With']) && $e['With']) $imps = $e['With'];
    $ImpC = ImpCount($imps);
    $rows = max(1,ceil($ImpC/2));

    if ($e['SubEvent'] <0) { // has subes
      if ($e['LongEvent'] && !$imps) continue;
      $parname = $e['SName']; 
      $lastevent = $ei;
      echo "<tr><td rowspan=$rows valign=top><a href=EventShow.php?e=$eid>" . timecolon($e['Start']) . " - " . timecolon($e['End']) .  "</a>";
      if ($VirtVen) echo "<br>" . $VenNames[$e['Venue']];
      echo "<td colspan=" . ($imps?$ll+($e['LongEvent']?0:1):$ll+1) . " valign=top><a href=EventShow.php?e=$eid>" . $parname . "</a>";
      if ($e['Status'] == 1) echo "<br><div class=Cancel>CANCELLED</div>";
      if ($e['Description']) echo "<br>" . $e['Description'];

      if ($imps) {
        if (!$e['LongEvent']) echo "<tr><td rowspan=$rows >&nbsp;<td rowspan=$rows  valign=top>" . timecolon($e['Start']) . " - " . timecolon($e['SlotEnd']);
        PrintImps($imps,$NotAllFree,Price_Show($e),$rows,$ImpC);
      } else if (!$e['LongEvent'] && $NotAllFree) echo "<td>" . Price_Show($e);
    } else if ($e['SubEvent'] == 0) { // Is stand alone
      $lastDay = $e['Day'];
      $parname = $e['SName'];
      if ($Poster) $rows = 1; // Only ever show first row
      echo "<tr><td rowspan=$rows valign=top><a href=EventShow.php?e=$eid valign=top>" . timecolon($e['Start']) . " - " . timecolon($e['End']) . "</a>";
      if ($VirtVen) echo "<br>" . $VenNames[$e['Venue']];
      echo "<td rowspan=$rows  valign=top><a href=EventShow.php?e=$eid>" . $parname . "</a>";
      if ($e['Status'] == 1) echo "<br><div class=Cancel>CANCELLED</div>";
      if ($e['Description']) echo "<br>" . $e['Description'];
      if ($e['Image']) $SpecialImage = $e['Image'];
      PrintImps($imps,$NotAllFree,Price_Show($e),$rows,$ImpC,($Poster?2:100));
    } else { // Is a sube
      if ($e['LongEvent'] && $lastevent != $e['SubEvent']) {
	$lastevent = $e['SubEvent'];
        $pare = &$EVs[$lastevent]; 
        $parname = $pare['SName']; 
        echo "<tr><td rowspan=$rows valign=top ><a href=EventShow.php?e=$lastevent>" . timecolon($e['Start']) . " - " . timecolon($e['End']) . "</a>";
        if ($VirtVen) echo "<br>" . $VenNames[$e['Venue']];
	echo "<td rowspan=$rows valign=top ><a href=EventShow.php?e=$lastevent>" . $parname . "</a>";
        if ($e['Status'] == 1) echo "<br><div class=Cancel>CANCELLED</div>";
        if ($pare['Description']) echo "<br>" . $pare['Description'];
        if ($imps) PrintImps($imps,$NotAllFree,Price_show($EVs[$e['SubEvent']]),$rows,$ImpC);
      } else if ($imps) {
        echo "<tr><td rowspan=$rows >&nbsp;<td rowspan=$rows  valign=top>";
	if ($parname != $e['SName']) {
	  echo "<a href=EventShow.php?e=" . $e['SubEvent'] . ">" . $e['SName'] . "</a><br>";
          $parname = (isset($pare['SName'])? $pare['SName'] : "Unknown"); 
	}
	echo timecolon($e['Start']) . " - " . timecolon($e['End']);
        PrintImps($imps,$NotAllFree,'&nbsp;',$rows,$ImpC);
      }
    }
  }
  echo "</table>\n";

  if ($Poster) {
    echo "<div class=floatright id=qrcode></div>";
    echo "<h3> To find out more scan this:<br>to visit wimbornefolk.co.uk</h3>"; // pixels should be multiple of 41
    echo '<script type="text/javascript">
      var qrcode = new QRCode(document.getElementById("qrcode"), {
	text: "https://wimbornefolk.co.uk/int/VenueShow.php?V=' . $V . '",
	width: 123,
	height: 123,
      });
      </script>';
    echo "</body></html>\n";
  } else {
    dotail();
  }
?>
