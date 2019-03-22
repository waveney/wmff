<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Manage Trade Pitches");

  include_once("TradeLib.php");

  // Work out pitch locs for a map
  // Map on left locs on right 
  // Need lib for doing trade map - background, svg for plots and text - initially pitch #
  global $Pitches,$tloc,$loc;



  function PitchList() {
    global $Pitches,$tloc,$loc,$TradeMapPoints;
    echo "<div class=PitchWrap><div class=PitchCont>";
    echo "<table border><tr><td>#<td>X<td>Y<td>Ang<td>Xsize<td>Ysize<td>Type<td>Txt<td>Colr";
    $posn = 0;
    foreach ($Pitches as $Pitch) {
      $i = $Pitch['id'];
      echo "<tr>" . fm_text1("",$Pitch,'Posn',0.1,'','',"Posn$i");
      echo fm_text1("",$Pitch,'X',0.15,'','',"X$i") . fm_text1("",$Pitch,'Y',0.15,'','',"Y$i");
      echo fm_text1("",$Pitch,'Angle',0.15,'','',"Angle$i");
      echo fm_text1("",$Pitch,'Xsize',0.15,'','',"Xsize$i") . fm_text1("",$Pitch,'Ysize',0.15,'','',"Ysize$i");
      echo "<td>" . fm_select($TradeMapPoints,$Pitch,'Type',0,'',"Type$i");
      echo fm_text1("",$Pitch,'SN',0.15,'','',"SN$i") . fm_text1("",$Pitch,'Colour',0.15,'','',"Colour$i");
      echo fm_hidden("Loc$i",$loc);
      $posn = max($posn, $Pitch['Posn']);
    }
    $Pitch['Posn'] = $posn+1; 
    $Pitch['X'] = $Pitch['Y'] = $Pitch['Angle'] = 0;
    echo "<tr>" . fm_text1("",$Pitch,'Posn',0.1,'','',"Posn0");
    echo fm_text1("",$Pitch,'X',0.15,'','',"X0") . fm_text1("",$Pitch,'Y',0.15,'','',"Y0");
    echo fm_text1("",$Pitch,'Angle',0.15,'','',"Angle0");
    $Pitch['Xsize'] = $Pitch['Ysize'] = 3;
    echo fm_text1("",$Pitch,'Xsize',0.15,'','',"Xsize0") . fm_text1("",$Pitch,'Ysize',0.15,'','',"Ysize0");
    echo "<td>" . fm_select($TradeMapPoints,$Pitch,'Type',0,'',"Type0");
    echo fm_text1("",$Pitch,'SN',0.15,'','',"SN0") . fm_text1("",$Pitch,'Colour',0.15,'','',"Colour0");
    echo fm_hidden('Loc0',$loc);
    echo "</table></div>";
    echo "<input type=submit name=Update value=Update> ";
    echo "<a href=TradeAssign.php?i=$loc style='font-size:20;'>Assign</a>";
    echo "</div>";
  }

  $loc = $_REQUEST['i'];
  $Pitches = Get_Trade_Pitches($loc);  
  // START HERE
  if (isset($_POST['Update'])) {
//    var_dump($_POST);
    if (UpdateMany('TradePitch','Put_Trade_Pitch',$Pitches,1,'','','X',0)) $Pitches = Get_Trade_Pitches($loc);
  }  

  $tloc = Get_Trade_Loc($loc);
  
  

  echo "<form method=post>";
  echo fm_hidden('i',$loc);

  echo "<h2>Pitch setup for " . $tloc['SN'] . "</h2>";
  
  Pitch_Map($tloc,$Pitches);
  PitchList();
  dotail();
 
  
?>

