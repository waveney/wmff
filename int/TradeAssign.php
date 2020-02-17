<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Manage Trade Pitches",['js/Trade.js']);

  include_once("TradeLib.php");

  global $Pitches,$tloc,$loc,$Traders,$Trade_State,$db,$Trade_Types,$Trade_Types;
  $Trade_Types = Get_Trade_Types(1);

  function TraderList($Message='') {
    global $Pitches,$tloc,$loc,$Traders,$Trade_Types,$Trade_State;
    echo "<div class=PitchWrap><div class=PitchCont>";
    if (!$Traders) {
      echo "No Traders Here Yet";
    } else {
      echo "<div class=tablecont><table border><tr><td>Name<td>info<td>Size<td>Pitch";
      foreach ($Traders as $Trad) {
        $tid = $Trad['Tid'];
        echo "<tr><td draggable=true class='TradeName Trader$tid' id=TradeN$tid ondragstart=drag(event) ondragover=allow(event) ondrop=drop(event) " .
             "style='background:" . ($Trad['PAID'] ? $Trade_Types[$Trad['TradeType']]['Colour'] : 'white' ) . "'>" . $Trad['SN'];
        if (!$Trad['PAID']) {
          echo " <span class=err>" . ($Trad['BookingState'] == $Trade_State['Quoted'] ?"NOT ACCEPTED": "NOT PAID") .  "</span>";
        }
        echo "<td><img src=/images/icons/information.png width=20 title='" . $Trad['GoodsDesc'] . "'><td>";          
        $pitched = 0;
        for ($i=0; $i<3; $i++) 
          if ($Trad["PitchLoc$i"] == $loc) {
            if ($pitched) {
              echo "<tr><td><td>&amp;<td>";
            }
            echo $Trad["PitchSize$i"] . "<td id=PitchLoc$i>";
            echo fm_text0('',$Trad,"PitchNum$i",0.25,'','',"PitchNum$i:$tid");
            $pitched = 1;
          }
      }
      echo "</table></div></div>";
      echo "<input type=submit name=Update value=Update> <span class=Err>$Message</span>";
      echo "<a href=TradeSetup?i=$loc style='font-size:20;'>Setup</a>";
    }
    echo "</div>";
  }

  function Update_Pitches() {
    $Change = 0;
    foreach($_POST as $P=>$V) {
      if (preg_match('/PitchNum(\d):(\d+)/',$P,$matches)) {
        $Tid = $matches[2];
        $Tpn = $matches[1];
        $Trady = Get_Trade_Year($Tid);
        if ($V != $Trady["PitchNum$Tpn"]) {
          $Trady["PitchNum$Tpn"]=$V;
          Put_Trade_Year($Trady);
          $Change = 1;
        }
      }
    }
    return $Change;
  }
  
  
  // No pitch used more than once, no invalid pitch #s (not = pitch and pitch for trade)
  // All traders have a pitch
  
  function Validate_Pitches_At($Loc) {
    global $Traders,$Pitches,$tloc,$Trade_State;
    
    $Usage = [];$TT = [];
    $NotAssign = '';
    $TLocId = $tloc['TLocId'];
    if ($Traders) {
      foreach ($Traders as $i=>$Trad) {
        if ( $Trad['BookingState'] == $Trade_State['Deposit Paid'] || $Trad['BookingState'] == $Trade_State['Balance Requested'] || $Trad['BookingState'] == $Trade_State['Fully Paid'] ) {
          $Traders[$i]['PAID'] = 1;
        } else {
          $Traders[$i]['PAID'] = 0;
        } 
        for ($i=0; $i<3; $i++) {
          if ($Trad["PitchLoc$i"] == $TLocId) {
            $Found = 0;
            $list = explode(',',$Trad["PitchNum$i"]);
            foreach ($list as $p) {
              if (!$p) continue;
              if (!$Traders[$i]['PAID']) continue;
              if (!isset($Pitches[$p])) return $Trad['SN'] . " assigned to an invalid pitch number $p";
              if (isset($Usage[$p])) return "Clash on pitch $p - " . $Usage[$p] . " and " . $Trad['SN'];
              if ($Pitches[$p]['Type']) return $Trad['SN'] . " assigned to a non pitch";
              $Usage[$p] = $Trad['SN'];
              $TT[$p] = $Trad['TradeType'];
              $Found = $p;
            }
            if ($Traders[$i]['PAID'] && !$Found) $NotAssign = "No pitch for " . $Trad['SN'];
          }
        }
      }
    }
    return $NotAssign;
  }
  

  $loc = $_REQUEST['i'];
  if (isset($_POST['Update'])) Update_Pitches(); // Note this can't use Update Many as weird format of ids
  $Pitches = Get_Trade_Pitches($loc);
  $tloc = Get_Trade_Loc($loc);
  
  $Traders = Get_Traders_For($loc,1);
  
  echo "<form method=post>";
  echo fm_hidden('i',$loc);

  echo "<h2>Pitch setup for " . $tloc['SN'] . "</h2>";
  $Message = Validate_Pitches_At($loc);

  Pitch_Map($tloc,$Pitches,$Traders);
  TraderList($Message);
  echo "<h2><a href=TradeLocs?Y=$YEAR>Trade Locs</a></h2>";
  dotail();
 
  
?>

