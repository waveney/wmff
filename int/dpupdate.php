<?php
// Updates to data following on screen drag drops, returns info pane html
//    $("#Infomation").load("dpupdate.php", "D=" + dstId + "&S=" + srcId + "&Y=" + $("#DayId").text() + "&E=" + 
//                        $("input[type='radio'][name='EInfo']:checked").val()        );
//  include_once("minimalfiles/header.php");
  include_once("fest.php");
  include_once("ProgLib.php");
  include_once("CheckDance.php");

  if (isset($_GET['D'])) {
    $dstId = $_GET['D'];  
    $srcId = $_GET['S'];  
    $Day   = $_GET['A'];
    $DayN  = $Day_Type[$Day];
    $sSide   = $_GET['I'];
  
    if (preg_match('/SideN(\d*)/',$srcId,$sres)) {
      $src=0;
    } elseif (preg_match('/G:(\d*):(\d*):(\d*)/',$srcId,$sres)) {
      $src=1;
    } else {
      echo "Something went wrong with source $srcId";
      exit;
    }
  
    if (preg_match('/SideN(\d*)/',$dstId,$dres)) {
      $dst=0;
    } elseif (preg_match('/G:(\d*):(\d*):(\d*)/',$dstId,$dres)) {
      $dst=1;
    } else {
      echo "Something went wrong with dest $dstId";
      exit;
    }

    if ($src == 0 && $dst == 0 ) { // Nothing to do...
    } elseif ($src == 0) { // From Side to Grid
      $Event = Get_Event_VT($dres[1],$dres[2],$DayN);
      for ($i = 1; $i<5; $i++) if ($Event["Side$i"] == 0) { $Event["Side$i"] = $sSide; break; };
      Put_Event($Event);
    } elseif ($dst == 0) { // To Side
      $Event = Get_Event_VT($sres[1],$sres[2],$DayN);
      for ($i = 1; $i<5; $i++) if ($Event["Side$i"] == $sSide) { $Event["Side$i"] = 0; break; };
      Put_Event($Event);
    } else { // Grid to Grid
      $Event = Get_Event_VT($sres[1],$sres[2],$DayN);
      for ($i = 1; $i<5; $i++) if ($Event["Side$i"] == $sSide) { $Event["Side$i"] = 0; break; };
      Put_Event($Event);

      $Event = Get_Event_VT($dres[1],$dres[2],$DayN);
      for ($i = 1; $i<5; $i++) if ($Event["Side$i"] == 0) { $Event["Side$i"] = $sSide; break; };
      Put_Event($Event,1);
    }

  }
 // Return setup
 
//var_dump($_GET);
  $Ei    = $_GET['E'];  // Used for return info
  if (isset($_GET['P'])) UserSetPref('ProgErr',$Ei);
  CheckDance($Ei);
  
?>

