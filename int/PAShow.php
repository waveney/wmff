<?php
  include_once("fest.php");
  
  A_Check('Steward');
  
  $V = $_REQUEST['pa4v'];
  include_once("ProgLib.php");
  include_once("DanceLib.php");
    
  $Ven = Get_Venue($V);

  dostaffhead("PA Requirements for " . $Ven['SN']);

//  echo "TO BE WRITTEN";


 $VenList[] = $V;
  if ($Ven['IsVirtual']) {
    $res = $db->query("SELECT DISTINCT e.* FROM Events e, Venues v, EventTypes t WHERE e.Year=$YEAR AND (e.Venue=$V OR e.BigEvent=1 OR " .
                "( e.Venue=v.VenueId AND v.PartVirt=$V )) ORDER BY Day, Start");
    $parts = $db->query("SELECT VenueId FROM Venues v WHERE v.PartVirt=$V");
    while ($part = $parts->fetch_assoc()) $VenList[] = $part['VenueId'];
  } else {
    $res = $db->query("SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND (e.Venue=$V OR e.BigEvent=1) " .
                " ORDER BY Day, Start");
  }

  if (!$res || $res->num_rows==0) {
    echo "<h3>There are currently no events at " . $Ven['SN'] . " and hence no current PA Requirements</h3>\n";
    dotail();
    exit;
  }
  
  $LastDay = -99;
  while ($e = $res->fetch_assoc()) {
    if ($LastDay != $e['Day']) { $MaxEv = 0; $LastDay = $e['Day']; };
    $WithC = 0;
    if ($e['BigEvent']) {
      $O = Get_Other_Things_For($e['EventId']);
      $found = ($e['Venue'] == $V); 
//      if (!$O && !$found) continue;
      if ( !$found && $Ven['IsVirtual'] && in_array($e['Venue'],$VenList)) $found = 1; 
      foreach ($O as $i=>$thing) {
        switch ($thing['Type']) {
          case 'Venue':
            if (in_array($thing['Identifier'],$VenList)) $found = 1; 
            break;
          case 'Perf':
          case 'Side':
          case 'Act':
          case 'Other':
            if ($thing['Identifier']) $e['With'][] = $thing['Identifier'];
            break;
          default:
            break;
        }
      }
      if ($found == 0) continue;
    } else {
      for($i=1;$i<5;$i++) if ($e["Side$i"]) {
        $e['With'][] = $e["Side$i"];
      }
    }
    if ($e['ExcludePA']) $e['With'] = [];
    $EVs[$e['EventId']] = $e;
  }

  if (!isset($EVs) || !$EVs) {
    echo "<h3>There are currently no events at " . $Ven['SN'] . " and hence no current PA Requirements</h3>\n";
    dotail();
    exit;
  }

//var_dump($VirtVen);

  $lastevent = -99;
  foreach ($EVs as $ei=>$e) {
    $eid = $e['EventId'];
    if (DayTable($e['Day'],"PA Requirements for " . $Ven['SN'] ,'','style=font-size:24;')) {
      echo "<tr><td>Time<td >What<td>Who<td>PA Reqs";
      $lastevent = -99;
    }

    $rows = 0;
    if (isset($e['With'])) $rows += count($e['With']);
    if ($e['StagePA']) $rows++;
    
    if ($rows) {
      echo "<tr><td rowspan=$rows>". timecolon($e['Start'] - $e['Setup']) . "-" . timecolon($e['End']) . "<td rowspan=$rows>" . $e['SN'] ;
      $tr = 0;
      if ($e['StagePA']) { echo "<td><td>" . $e['StagePA']; $tr=1;}
      foreach ($e['With'] as $snum) {
        if ($tr++) echo "<tr>";
        $side = Get_Side($snum);
        echo "<td>" . $side['SN'] . "<td>";
        if ($side['StagePA'] == '@@FILE@@') {
          $files = glob("PAspecs/$snum.*");
          if ($files) {
            $Current = $files[0];
            $Cursfx = pathinfo($Current,PATHINFO_EXTENSION );
            if (file_exists("PAspecs/$snum.$Cursfx")) {
              echo "<a href=ShowFile.php?l=PAspecs/$snum.$Cursfx>View File</a>";
            } else {
              echo "None";
            }
          } else {
            echo "None";          
          }
        } else if ($side['StagePA']) {
          echo $side['StagePA'];
        } else echo "None";
      }
    } else {
      echo "<tr><td>" . timecolon($e['Start'] - $e['Setup']) . "-" . timecolon($e['End']) . "<td>" .  $e['SN'] . "<td><td>None";
    }
  }
  echo "</table>\n";
  
  dotail();
  
/* Need to see PA on venue list 
   Summary sheet per day, then details
   
   */

?>
