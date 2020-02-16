<?php
  include_once("int/fest.php");
  global $Access_Type,$USER,$YEAR;
  Set_User();
  $hpre = Feature('HeaderPreTools');
  if ($hpre) echo $hpre;
  if (isset($_COOKIE{'WMFF'}) || isset($_COOKIE{'WMFF2'})) {
    echo "<div class=Staff><div class=navigation>";
    echo "<a href=/int/Staff.php onmouseover=NoHoverSticky()>Staff Tools</a>";
    if (!isset($USER{'Subtype'})) {
      if (Capability("EnableDocs")) echo "<a href=/int/Dir.php onmouseover=NoHoverSticky()>Docs</a>";
      if (Capability("EnableTLine")) echo "<a href=/int/TimeLine.php?Y=$YEAR onmouseover=NoHoverSticky()>Time Line</a>";
      echo "<a href='/int/Login.php?ACTION=LOGOUT' onmouseover=NoHoverSticky()>Logout " . $USER['Login'] . "</a>\n";
    }
    echo "</div></div>";
  }
  $host= "https://" . $_SERVER['HTTP_HOST'];

  global $USERID,$PerfTypes;
  if ( isset($USER{'AccessLevel'}) && $USER{'AccessLevel'} == $Access_Type['Participant'] ) {
    echo "<div class=Staff><div class=navigation>";
    switch ($USER{'Subtype'}) {
    case 'Perf':
      include_once("DanceLib.php");
      $Side = Get_Side($USERID);
      $NotD = 0;
      foreach ($PerfTypes as $p=>$d) if (($d[0] != 'IsASide') && $Side[$d[0]]) $NotD = 1;
      echo "<a href=$host/int/AddPerf.php?sidenum=$USERID onmouseover=NoHoverSticky()>Edit Data</a>";
      echo "<a href=$host/int/ShowDance.php?sidenum=$USERID onmouseover=NoHoverSticky()>Public view</a>";
      if ($Side['IsASide']) echo "<a href=$host/int/DanceFAQ.php onmouseover=NoHoverSticky()>Dance FAQ</a>";
      if ($NotD) echo "<a href=$host/int/MusicFAQ.php onmouseover=NoHoverSticky()>Performer T&amp;Cs</a>";
      break;

    case 'Side':
      echo "<a href=$host/int/AddPerf.php?sidenum=$USERID onmouseover=NoHoverSticky()>Edit Side</a>";
      echo "<a href=$host/int/ShowDance.php?sidenum=$USERID onmouseover=NoHoverSticky()>Public view of Side</a>";
      echo "<a href=$host/int/DanceFAQ.php onmouseover=NoHoverSticky()>Dance FAQ</a>";
      break;
    case 'Act':
      echo "<a href=$host/int/AddPerf.php?sidenum=$USERID onmouseover=NoHoverSticky()>Edit Act</a>";
      echo "<a href=$host/int/ShowMusic.php?sidenum=$USERID onmouseover=NoHoverSticky()>Public view of Act</a>";
      echo "<a href=$host/int/MusicFAQ.php onmouseover=NoHoverSticky()>Music FAQ</a>";
      break;
    case 'Other':
      echo "<a href=$host/int/AddPerf.php?sidenum=$USERID&t=O onmouseover=NoHoverSticky()>Edit Act</a>";
      echo "<a href=$host/int/ShowMusic.php?sidenum=$USERID&t=O onmouseover=NoHoverSticky()>Public view of Act</a>";
      break;
    case 'Trader':
      echo "<a href=$host/int/TraderPage.php?id=$USERID onmouseover=NoHoverSticky()>Edit Trader Info</a>";
//      echo "<a href=$host/int/ShowTrade.php?id=$USERID onmouseover=NoHoverSticky()>Public view of Trader</a>";
      echo "<a href=$host/int/TradeFAQ.php onmouseover=NoHoverSticky()>Trade FAQ</a>";
      break;
    }
    echo "</div></div>\n";
  }
  $hpost = Feature('HeaderPostTools');
  if ($hpost) echo $hpost;
  
  
?>

