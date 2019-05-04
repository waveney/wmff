<?php
  include_once("int/fest.php");

  set_ShowYear();
  include_once("TradeLib.php");
  global $db,$YEAR,$SHOWYEAR,$PLANYEAR,$Trade_States,$Trade_State,$YEAR,$Trade_Days,$Prefixes;

  dohead("Traders in $YEAR",['files/festconstyle.css'],'https://wimbornefolk.co.uk/int/images/gallery/2018/Around/14_HSJX8086_14-2048-STEPHENAJONES.jpg');

  $Locs = Get_Trade_Locs(1);
  $TTypes = Get_Trade_Types(1);
  $Traders = Get_Traders_Coming(1,"Fee DESC");
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
  
  $Overview = 0;
  foreach ($Locs as $loc) if ($loc['SN'] == 'Overview') $Overview = $loc;

  
  echo "<form>" . fm_hidden('Y',$YEAR);
  echo "<table class=GreenTable>";
  echo "<tr><td>Show by Location:"; // <td>Show by Type

  echo "<td>";
    foreach($Locs as $loc) {
      if ($loc['InUse'] && isset($LocUsed[$loc['TLocId']]) && !$loc['NoList']) echo "<input type=submit name=SEL value='" . $loc['SN'] . "'> ";
    }
//    echo "<td>";
/*
    foreach($TTypes as $typ) {
      if (!$typ['Addition'] && isset($TTUsed[$typ['id']])) echo '<input type=submit name=SEL value="' . $typ['SN'] . '" style="background:' . $typ['Colour'] . ';color:black;"> ';
    }
*/
    echo "<td>";
      echo "<input type=submit name=SEL value='Show All'> ";
  echo "</table><p>";

  $List = [];
  $SLoc = 0;

  if (isset($_REQUEST['SEL'])) {
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

    echo "<h2>$Title</h2>";
  } else {
  
  }
  
  if ($SLoc) {
    Pitch_Map($SLoc,$Pitches,$Traders,1,1,1) ;
  } else if ($Overview) {
    $Pitches = Get_Trade_Pitches($Overview['TLocId']);
    Pitch_Map($Overview,$Pitches,0,1,1,2) ; 
  }
  echo "<br clear=all><p>";

  if (!isset($_REQUEST['SEL'])) dotail();  
   
  if ($YEAR < $PLANYEAR) {
    echo "These traders where at the Folk Festival.<p>";
  } else {
    echo "These traders will be at the Folk Festival.<p>";
  }
  echo "To become a trader see the <a href=/InfoTrade.php>trade application page</a>.  ";
  echo "Only those traders who have paid their deposits are shown here.<p>";

 
  echo "<div id=flex>\n";
  foreach($List as $ti) {
    $trad = $Traders[$ti];
 //var_dump($ti,$trad);
    echo "<div class=TradeFlexCont id=Trader" . $trad['Tid'] .  ">";
    if ($trad['Website']) echo weblinksimple($trad['Website']);

    if ($trad['Photo']) echo "<img src=" . $trad['Photo'] . ">";
    echo "<h2>" . $trad['SN'] . "</h2>";
    if ($trad['Website']) echo "</a>";
    
    $txt = nl2br($trad['GoodsDesc']);
    
    echo "<div class=Tradetext>$txt</div>"; // TODO Handle non html chars also do double nl to p
    
    if (!$SLoc) {
      if ($trad['PitchLoc0'] == $trad['PitchLoc1']) $trad['PitchLoc1'] = 0;
      if ($trad['PitchLoc0'] == $trad['PitchLoc2']) $trad['PitchLoc2'] = 0;    
      if ($trad['PitchLoc1'] == $trad['PitchLoc2']) $trad['PitchLoc2'] = 0;    
    
      if (isset($Locs[$trad['PitchLoc0']])) {
        echo ($YEAR >= $PLANYEAR?"<p>Will be trading ":"<p>Was trading ") . $Prefixes[$Locs[$trad['PitchLoc0']]['prefix']] . ' ' . $Locs[$trad['PitchLoc0']]['SN'];
        if ($trad['PitchLoc2']) {
          echo ", " . $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc1']]['SN'] . " and " 
           		. $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc2']]['SN'];
        } else if ($trad['PitchLoc1']) {
          echo " and " . $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc1']]['SN'];
        }
      if ($trad['Days']) echo " on " . $Trade_Days[$trad['Days']];
      } else {
        if ($trad['Days']) echo $Trade_Days[$trad['Days']];    
      }
    }
    echo "</div>";
  }
  echo "</div>";
//  dotail();
//  exit;  

/*

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
*/
  echo "<br clear=all>";
  dotail();
?>
