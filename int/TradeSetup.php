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
    echo "<div class=tablecont><table border class=TradeTable><tr><td>#<td>X<td>Y<td>Ang<td>Xsz<td>Ysz<td>Type<td>Txt<td>Col<td>font";
    $posn = 0;
    foreach ($Pitches as $Pitch) {
      $i = $Pitch['id'];
      echo "<tr>" . fm_text1("",$Pitch,'Posn',0.08,'','',"Posn$i");
      echo fm_text1("",$Pitch,'X',0.10,'','',"X$i") . fm_text1("",$Pitch,'Y',0.10,'','',"Y$i");
      echo fm_text1("",$Pitch,'Angle',0.10,'','',"Angle$i");
      echo fm_text1("",$Pitch,'Xsize',0.10,'','',"Xsize$i") . fm_text1("",$Pitch,'Ysize',0.10,'','',"Ysize$i");
      echo "<td>" . fm_select($TradeMapPoints,$Pitch,'Type',0,'',"Type$i");
      echo fm_text1("",$Pitch,'SN',1.2,'','',"SN$i") . fm_text1("",$Pitch,'Colour',0.35,'','',"Colour$i");
      echo fm_text1("",$Pitch,'Font',0.10,'','',"Font$i");
      echo fm_hidden("Loc$i",$loc);
      $posn = max($posn, $Pitch['Posn']);
    }
    $Pitch['Posn'] = $posn+1; 
    $Pitch['X'] = $Pitch['Y'] = $Pitch['Angle'] = 0;
    echo "<tr>" . fm_text1("",$Pitch,'Posn',0.08,'','',"Posn0");
    echo fm_text1("",$Pitch,'X',0.10,'','',"X0") . fm_text1("",$Pitch,'Y',0.10,'','',"Y0");
    echo fm_text1("",$Pitch,'Angle',0.10,'','',"Angle0");
    $Pitch['Xsize'] = $Pitch['Ysize'] = 3;
    echo fm_text1("",$Pitch,'Xsize',0.10,'','',"Xsize0") . fm_text1("",$Pitch,'Ysize',0.10,'','',"Ysize0");
    echo "<td>" . fm_select($TradeMapPoints,$Pitch,'Type',0,'',"Type0");
    echo fm_text1("",$Pitch,'SN',1.2,'','',"SN0") . fm_text1("",$Pitch,'Colour',0.35,'','',"Colour0") . fm_text1("",$Pitch,'Font',0.10,'','',"Font0");
    echo fm_hidden('Loc0',$loc);
    echo "</table></div></div>";
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

