<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Manage Trade Pitches");

  include_once("TradeLib.php");

  // Work out pitch locs for a map
  // Map on left locs on right 
  // Need lib for doing trade map - background, svg for plots and text - initially pitch #
  global $Pitches,$tloc,$loc,$YEAR,$PLANYEAR;



  function PitchList() {
    global $Pitches,$tloc,$loc,$TradeMapPoints,$YEAR;
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
      echo fm_hidden("Year$i",$Pitch['Year']);
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
    echo fm_hidden('Year0',$YEAR);
    echo "</table></div></div>";
    echo "<input type=submit name=Update value=Update> ";
    echo "<a href=TradeAssign?i=$loc style='font-size:20;'>Assign</a>";
    echo "</div>";
  }

  $loc = $_REQUEST['i'];
  $Pitches = Get_Trade_Pitches($loc,$YEAR);  
//  var_dump($Pitches);
  // START HERE
  if (isset($_POST['Update'])) {
//    var_dump($_POST);
    if (UpdateMany('TradePitch','Put_Trade_Pitch',$Pitches,1,'','','X',0)) $Pitches = Get_Trade_Pitches($loc,$YEAR);
  }
  if (isset($_REQUEST['COPY'])) {
    foreach($Pitches as $Pitch) {
      $Pitch['Year'] = $PLANYEAR;
      Insert_db('TradePitch', $Pitch);
    }
    echo "Pitches copied";
    echo "<h2><a href=TradeSetup?i=$loc>Edit/View</a></h2>";
   
    dotail();
  }

  $tloc = Get_Trade_Loc($loc);
  
  

  echo "<form method=post>";
  echo fm_hidden('i',$loc);

  echo "<h2>Pitch setup for " . $tloc['SN'] . "</h2>";
  
  Pitch_Map($tloc,$Pitches);
  PitchList();
  
  if ($YEAR < $PLANYEAR) {
    echo "<h2><a href=TradeSetup?COPY&i=$loc&Y=$YEAR>Copy to Current</a></h2>";
  } else {
    $LYear = $YEAR-1;
    echo "<h2><a href=TradeSetup?COPY&i=$loc&Y=$LYear>Copy $LYear to Current</a></h2>";
  }
  dotail();
 
  
?>

