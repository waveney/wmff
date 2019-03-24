<?php
  include_once("int/fest.php");

  set_ShowYear();
  include_once("TradeLib.php");
  global $db,$YEAR,$SHOWYEAR,$PLANYEAR,$Trade_States,$Trade_State,$YEAR,$Trade_Days,$Prefixes;

  dohead("Traders in $YEAR",'files/festconstyle.css');

  $Locs = Get_Trade_Locs(1);
  $TTypes = Get_Trade_Types(1);
  $Traders = Get_Traders_Coming(1);
  $TTUsed = $LocUsed = $AllList = [];
  
  foreach ($Traders as $ti=>$Trad) {
    if (!$Trad['ListMe'] && !Access('Staff')) continue;
    $TT = $Trad['TradeType'];
    $TTUsed[$TT][] = $ti;
    
    for ($i=0; $i<3; $i++) {
      if (!isset($Trad["PitchLoc$i"])) continue;
      $L = $Trad["PitchLoc$i"];
      if ($L) {
        $LocUsed[$L][] = $ti;
      }
    }
    $AllList[] = $ti; 
  }

  
  echo "<form>" . fm_hidden('Y',$YEAR);
  echo "<table border class=lemontab>";
  echo "<tr><td>Show by Location<td>Show by Type<td>Show All";
  echo "<tr><td>";
    foreach($Locs as $loc) {
      if ($loc['InUse'] && isset($LocUsed[$loc['TLocId']])) echo "<input type=submit name=SEL value='" . $loc['SN'] . "'> ";
    }
    echo "<td>";
    foreach($TTypes as $typ) {
      if (!$typ['Addition'] && isset($TTUsed[$typ['id']])) echo '<input type=submit name=SEL value="' . $typ['SN'] . '" style="background:' . $typ['Colour'] . ';color:black;"> ';
    }
    echo "<td>";
      echo "<input type=submit name=SEL value='Show All'> ";
  echo "</table><p>";
  
  if (!isset($_REQUEST['SEL'])) dotail();
  
  $List = [];
  $SLoc = 0;
  $sel = $_REQUEST['SEL'];
  if ($sel == 'Show All') {
    $List = $AllList;
    $Title = 'All Traders';
  } else {
    foreach($Locs as $loc) 
      if ($sel == $loc['SN']) {
        $List = $LocUsed[$loc['TLocId']];
        $SLoc = $loc;
        $Title = 'All Traders ' . $Prefixes[$loc['prefix']] . ' ' . $loc['SN'];
        $Pitches = Get_Trade_Pitches($loc['TLocId']);
        break;
      }
    if (!$List) foreach($TTypes as $typ)
      if ($sel == $typ['SN']) {
        $List = $TTUsed[$typ['id']];
        $Title = 'All ' . $typ['SN'] . " Traders";
      }
  }
  
  echo "<h2>$Title (who have asked to be listed)</h2>";
  
  if ($SLoc) Pitch_Map($SLoc,$Pitches,$Traders,1) ;
  echo "<br clear=all><p>";
    
  if ($YEAR < $PLANYEAR) {
    echo "These traders where at the Folk Festival.<p>";
  } else {
    echo "These traders will be at the Folk Festival.<p>";
  }
  echo "To become a trader see the <a href=/info/trade>trade info page</a>.  ";
  echo "Only those traders who have paid their deposits and have asked to be listed are shown here.<p>";

  if (Access('SysAdmin')) {
  
  echo "<div id=flex>\n";
  foreach($List as $ti) {
    $trad = $Traders[$ti];
 //var_dump($ti,$trad);
    echo "<div class=TradeFlexCont>";
    if ($trad['Website']) echo weblinksimple($trad['Website']);
    echo "<h2>" . $trad['SN'] . "</h2>";
    if ($trad['Photo']) echo "<img src=" . $trad['Photo'] . ">";
    if ($trad['Website']) echo "</a>";
    
    $txt = $trad['GoodsDesc'];
    $txt = preg_replace("/\n\n/","<p>\n\n",$txt);
    echo "<div class=Tradetext>$txt</div>"; // TODO Handle non html chars also do double nl to p
    
    if (!$SLoc) {
      echo ($YEAR >= $PLANYEAR?"<p>Will be trading ":"<p>Was trading ") . $Prefixes[$Locs[$trad['PitchLoc0']]['prefix']] . ' ' . $Locs[$trad['PitchLoc0']]['SN'];
      if ($trad['PitchLoc2']) {
        echo ", " . $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc1']]['SN'] . " and " 
         		. $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc2']]['SN'];
      } else if ($trad['PitchLoc1']) {
        echo " and " . $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc1']]['SN'];
      }
      if ($trad['Days']) echo " on " . $Trade_Days[$trad['Days']];
    } else {
      if ($trad['Days']) echo "On " . $Trade_Days[$trad['Days']];    
    }
    echo "</div>";
  }
  echo "</div>";
//  dotail();
//  exit;  
  
  echo "<br clear=all>";
  echo "<h2>Old Version</h2>";    
  }
  // Old Code
  
  echo "<div id=flex>\n";
  foreach($List as $ti) {
    $trad = $Traders[$ti];
 //var_dump($ti,$trad);
    echo "<div class=article>";
    if ($trad['Website']) echo weblinksimple($trad['Website']);
    echo "<h2 class=articlettl>" . $trad['SN'] . "</h2>";
    if ($trad['Photo']) echo "<img class=articleimg src=" . $trad['Photo'] . ">";
    if ($trad['Website']) echo "</a>";
    echo "<p class=articletxt>" . $trad['GoodsDesc'];
    
    if (!$SLoc) {
      echo ($YEAR >= $PLANYEAR?"<p>Will be trading ":"<p>Was trading ") . $Prefixes[$Locs[$trad['PitchLoc0']]['prefix']] . ' ' . $Locs[$trad['PitchLoc0']]['SN'];
      if ($trad['PitchLoc2']) {
        echo ", " . $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc1']]['SN'] . " and " 
         		. $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc2']]['SN'];
      } else if ($trad['PitchLoc1']) {
        echo " and " . $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc1']]['SN'];
      }
      if ($trad['Days']) echo " on " . $Trade_Days[$trad['Days']];
    } else {
      if ($trad['Days']) echo "On " . $Trade_Days[$trad['Days']];    
    }
    echo "<p>";
    echo "</div>";
  }

  dotail();
?>
