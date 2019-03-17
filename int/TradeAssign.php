<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Manage Trade Pitches",'js/Trade.js');

  include_once("TradeLib.php");

  global $Pitches,$tloc,$loc,$Traders,$Trade_State,$db,$Trade_Types,$Trade_Types;
  $Trade_Types = Get_Trade_Types(1);

  function TraderList() {
    global $Pitches,$tloc,$loc,$Traders,$Trade_Types;
    echo "<div class=PitchWrap><div class=PitchCont>";
    if (!$Traders) {
      echo "No Traders Here Yet";
    } else {
      echo "<table border><tr><td>Name<td>i<td>Size<td>Pitch";
      foreach ($Traders as $Trad) {
        $tid = $Trad['Tid'];
        echo "<tr><td draggable=true class='TradeName Trader$tid' id=TradeN$tid ondragstart=drag(event) ondragover=allow(event) ondrop=drop(event) " .
             "style='background:" . $Trade_Types[$Trad['TradeType']]['Colour'] . "'>" . $Trad['SN'] . "<td>";
        echo "<img src=/images/icons/information.png width=20 title='" . $Trad['GoodsDesc'] . "'><td>";
        for ($i=0; $i<3; $i++) 
          if ($Trad["PitchLoc$i"] == $loc) {
            echo $Trad["PitchSize$i"] . "<td id=PitchLoc$i>";
            echo fm_text0('',$Trad,"PitchNum$i",0.25,'','',"PitchNum$i:$tid");
            //if ($Trad["PitchNum$i"]) echo $Trad["PitchNum$i"];
            break;
          }
      }
      echo "</table></div>";
      echo "<input type=submit name=Update value=Update>";
    }
//    echo "<h2><a href=Staff.php>Staff Tools</a>";
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

  $loc = $_REQUEST['i'];
  if (isset($_POST['Update'])) Update_Pitches(); // Note this can't use Update Many as weird format of ids
  $Pitches = Get_Trade_Pitches($loc);

  $tloc = Get_Trade_Loc($loc);
  
  $Traders = Get_Traders_For($loc);
  
  echo "<form method=post>";
  echo fm_hidden('i',$loc);

  echo "<h2>Pitch setup for " . $tloc['SN'] . "</h2>";
  
  Pitch_Map($tloc,$Pitches,$Traders);
  TraderList();
  dotail();
 
  
?>

