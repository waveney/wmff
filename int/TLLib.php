<?php
// Common TimeLine Library

$TL_States = array('Open','Completed','Cancelled');
$TL_State = array_flip($TL_States);
$TL_Importance = array('Minor','Major','Critical');
include_once("DateTime.php");

function Set_TimeLine_Help() {
  static $t = array(
  );
  Set_Help_Table($t);
}

function Get_TLent($id) {
  static $TLs;
  global $db;
  if (isset($TLs[$id])) return $TLs[$id];
  $res=$db->query("SELECT * FROM TimeLine WHERE TLid=$id");
  if ($res) {
    $ans = $res->fetch_assoc();
    $TLs[$id] = $ans;
    return $ans;
  }
  return 0; 
}

function Put_TLent(&$now) {
  $e=$now['TLid'];
  $Cur = Get_TLent($e);
  Update_db('TimeLine',$Cur,$now);
}

function TL_Select($V) {
  global $db,$USERID,$YEAR;
  $data = array();

  switch ($V) {
  case 'MINE':
    $xtr = "AND ( Assigned=$USERID OR Assigned=0";    
    break;
  case 'OPEN':
    $xtr = "AND Status=0";    
    break;
  case 'ALL':
    $xtr = "";    
    break;
  case 'MONTH':
    $now = getdate();
    $month = mktime(0,0,0,$now['mon']+1,$now['mday'],$now['year']);
    $xtr = "AND Due<$month AND Status=0";    
    break;
  case 'OVERDUE':
    $now = time();
    $xtr = "AND Due<$now AND Status=0";    
    break;
  default :
    $xtr = "AND Status=0 AND ( Assigned=$USERID OR Assigned=0) ";    
    break;
  }
  $q = "SELECT * FROM TimeLine WHERE Year=$YEAR $xtr ORDER BY Due";
  $res = $db->query($q);
  if ($res) {
    while ($tle = $res->fetch_assoc()) {
      $data[] = $tle;
    }
  }
  return $data;
}
?>
