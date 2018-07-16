<?php
// Common TimeLine Library

$TL_Importance = array('','Major','Critical');
include_once("DateTime.php");

function Set_TimeLine_Help() {
  static $t = array(
    'Title' => 'Name of item to appear in lists - not too long, expand in notes if needed',
    'Assigned' => 'Who is this task for?  Only leave blank if you realy do not know',
    'NewDue' => 'Express the end date in a way that makes sense',
    'Recuring' => 'Tick this if the item should appear every year - it will then be copied forward when appropriate',
    'Notes' => 'Expand on what the item is',
    'Progress' => 'Use to mark progress, -1 for cancelling',
    'History' => 'Used to record when things happen and changes',
    'Year' => 'Festival Year the item is for',
  );
  Set_Help_Table($t);
}

function TL_State(&$tl,$mode=0) { // mode 0 = display mode 1 fm_number 
/*
  if (!isset($tl['Progress'])) return $xtra . 'Not Started' . $xtra2;
  if ($tl['Progress'] < 0) return $xtra . 'Cancelled' . $xtra2;
  if ($tl['Progress'] >= 100) return $xtra . 'Complete' . $xtra2;
  if (($tl['Progress'] == 0) && ($mode == 0)) return $xtra . 'Not Started' . $xtra2;
*/
  if ($mode) return fm_number('Progress',$tl,'Progress','',' max=100 min=-1') . "%";
  return '<span id=Progress' . $tl['TLid'] . ">" . $tl['Progress'] . '</span>' . "%";
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

/*
  switch ($V) {
  case 'MINE':
    $xtr = "( Assigned=$USERID OR Assigned=0 )";    
    break;
  case 'OPEN':
    $xtr = "Status=0";    
    break;
  case 'ALL':
    $xtr = "1=1";    
    break;
  case 'MONTH':
    $now = getdate();
    $month = mktime(0,0,0,$now['mon']+1,$now['mday'],$now['year']);
    $xtr = "Due<$month AND Status=0";    
    break;
  case 'OVERDUE':
    $now = time();
    $xtr = "Due<$now AND Status=0";    
    break;
  default :
    $xtr = "Status=0 AND ( Assigned=$USERID OR Assigned=0) ";    
    break;
  }*/
  
  $q = "SELECT * FROM TimeLine WHERE Year=$YEAR ORDER BY Due";
  $res = $db->query($q);
  if ($res) {
    while ($tle = $res->fetch_assoc()) {
      $data[] = $tle;
    }
  }
  return $data;
}
?>
