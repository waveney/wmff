<?php
  include_once("fest.php");
  
  $V = $_REQUEST['pa4v'];
  A_Check('Participant','Venue',$V);
  
  include_once("ProgLib.php");
  include_once("DanceLib.php");
  include_once("ViewLib.php");
  global $YEAR,$FESTSYS;
    
  $Ven = Get_Venue($V);

  $ShowMode = '';
  $AtEnd = [];
  if (isset($_REQUEST['Embed'])) $ShowMode = 'Embed';
  if (isset($_REQUEST['HeaderFree'])) $ShowMode = 'HeaderFree';
  
  if ($ShowMode == 'HeaderFree') {
    dominimalhead("PA Requirements for " . $Ven['SN'],'js/Tools.js',['files/Newstyle.css','files/festconstyle.css',"js/qrcode.js"]);
  } else {
    dostaffhead("PA Requirements for " . $Ven['SN'],["js/qrcode.js"]);
  }


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

//  echo "<h2 class=FakeButton><a href=PAShow?pa4v=$V>Browsing Format</a>, <a href=PAShow?pa4v=$V&FILES=1>Embed Files</a>,  <a href=PAShow?pa4v=$V&FILES=2>Header Free for Printing</a></h2>";

  if ($ShowMode != 'HeaderFree') {
    echo "<form>" . fm_hidden('pa4v',$V);
    echo "<input type=submit name=Basic value='Browsing Format'> ";
    echo "<input type=submit name=Embed value='Embed Files'> ";
    echo "<input type=submit name=HeaderFree value='Editable/Printer Version'> ";
    echo "</form>";
  } else {
    echo "<div style='width:1000;'>";
  }

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
      echo "<tr><td rowspan=$rows>". timecolon($e['Start'] - $e['Setup']) . "-" . timecolon($e['End']) . "<td rowspan=$rows>" . ($e['SubEvent']<1?$e['SN']:"") ;
      $tr = 0;
      if ($e['StagePA']) { echo "<td><td>" . $e['StagePA']; $tr=1;}
      if (isset($e['With'])) foreach ($e['With'] as $snum) {
        if ($tr++) echo "<tr>";
        $side = Get_Side($snum);
        echo "<td>" . $side['SN'] . "<td>";
        if ($side['StagePA'] == '@@FILE@@') {
          $files = glob("PAspecs/$snum.*");
          if ($files) {
            $Current = $files[0];
            $Cursfx = pathinfo($Current,PATHINFO_EXTENSION );
            if (file_exists("PAspecs/$snum.$Cursfx")) {
              if ($ShowMode) {
                $AtEnd[$snum] = "PAspecs/$snum.$Cursfx";
                echo "See Below";
              } else {
                echo "<a href=ShowFile?l=PAspecs/$snum.$Cursfx>View File</a>";
              }
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
  
  if ($AtEnd) {
    foreach($AtEnd as $snum=>$IncFile) {
      $side = Get_Side($snum);
      echo "<h2>" . $side['SN'] . "</h2>";
      ViewFile($IncFile,1,'',0);
    }
  }
 
  if ($ShowMode == 'HeaderFree') {

    echo "<h3> To find out more scan this:</h3>"; // pixels should be multiple of 41
    echo "<br clear=all><div id=qrcode></div>";
    echo '<script type="text/javascript">
      var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: "https://' . $FESTSYS['HostURL'] . "/int/Access?Y=$YEAR&t=p&i=$V&k=" . $Ven['AccessKey'] . '",
        width: 205,
        height: 205,
      });
      </script>';

    exit;
  }
  
  if (Access('Staff')) {

    echo "<h3>Link to send to Engineer: https://" . $FESTSYS['HostURL'] . "/int/Access?Y=$YEAR&t=p&i=$V&k=" . $Ven['AccessKey'];
    if (Access('SysAdmin')) echo "<a href='Access?Y=$YEAR&t=p&i=$V&k=" . $Ven['AccessKey'] . "'> Use\n";
    echo "</h3>\n";
  }
  dotail();

?>
