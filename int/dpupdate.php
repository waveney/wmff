<?php
// Updates to data following on screen drag drops, returns info pane html
//    $("#Infomation").load("dpupdate.php", "D=" + dstId + "&S=" + srcId + "&Y=" + $("#DayId").text() + "&E=" + 
//			$("input[type='radio'][name='EInfo']:checked").val()	);
//  include("minimalfiles/header.php");
  include("fest.php");
  include("ProgLib.php");
  include("CheckDance.php");

  if (isset($_GET['D'])) {
    $dstId = $_GET['D'];  
    $srcId = $_GET['S'];  
    $Day   = $_GET['A'];
    $DayN  = $Day_Type[$Day];
  
    if (preg_match('/SideN(\d*)/',$srcId,$sres)) {
      $src=0;
      $sSide = $sres[1];
    } elseif (preg_match('/G(\d*):(\d*):(\d*):(\d*):(\d*)/',$srcId,$sres)) {
      $src=1;
      $sSide = $sres[5];
    } else {
      echo "Something went wrong with source $srcId";
      exit;
    }
  
    if (preg_match('/SideN(\d*)/',$dstId,$dres)) {
      $dst=0;
      $dSide = $dres[1];
    } elseif (preg_match('/G(\d*):(\d*):(\d*):(\d*):(\d*)/',$dstId,$dres)) {
      $dst=1;
      $dSide = $dres[5];
    } else {
      echo "Something went wrong with dest $dstId";
      exit;
    }

    if ($src == 0 && $dst == 0 ) { // Nothing to do...
    } elseif ($src == 0) { // From Side to Grid
      $Event = Get_Event($dres[1]);
      $Event["Side" . ($dres[4]+1)] = $sSide;
      Put_Event($Event);
    } elseif ($dst == 0) { // To Side
      $Event = Get_Event($sres[1]);
      $Event["Side" . ($sres[4]+1)] = 0;
      Put_Event($Event);
    } else { // Grid to Grid
      $Event = Get_Event($sres[1]);
      $Event["Side" . ($sres[4]+1)] = 0;
      Put_Event($Event);

      $Event = Get_Event($dres[1]);
      $Event["Side" . ($dres[4]+1)] = $sSide;
      Put_Event($Event);
    }

  }
 // Return setup
   
  $Ei    = $_GET['E'];  // Used for return info
  CheckDance($Ei);
  
?>

