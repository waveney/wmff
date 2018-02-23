<?php

/* Library for Other Participants - children's entertainers, comedy, craft, etc etc
 */
$PTypes = array('S'=>'Side','A'=>'Act','O'=>'Other','J'=>'Old');

function Get_Other_People($y=0) { // year 0 all current year, -1 all - no year info, year>1 all that year
  global $db,$YEAR;
  if ($y == 0) {
    $res = $db->query("SELECT o.*, y.* FROM OtherPart o, OtherPartYear y WHERE o.OtherId=y.Other AND y.Year=$YEAR ORDER BY SName");
  } else if ($y < 0) {
    $res = $db->query("SELECT o.*, y.* FROM OtherPart o LEFT JOIN OtherPartYear y ON o.OtherId=y.Other AND y.Year=$YEAR ORDER BY SName");
  } else {
    $res = $db->query("SELECT o.*, y.* FROM OtherPart o, OtherPartYear y WHERE o.OtherId=y.Other AND y.Year=$y ORDER BY SName");
  }
  if ($res) {
    while ($o = $res->fetch_assoc()) { $othrs[$o['OtherId']] = $o; };
    return $othrs;
  }
}

function Get_Other_Person($pid) {
  global $db,$YEAR;
  static $Others;
  if (isset($Others[$pid])) return $Others[$pid];
  $res = $db->query("SELECT * FROM OtherPart WHERE OtherId=$pid");
  if ($res) {
    $o = $res->fetch_assoc();
    $Others[$pid] = $o;
    return $o;
  }
}

function Get_Other_Person_Year($pid,$year=0) {
  global $db,$YEAR;
  static $Others;
  if (!$year) $year=$YEAR;
  if (isset($Others[$pid][$year])) return $Others[$pid][$year];
  $res = $db->query("SELECT * FROM OtherPartYear WHERE Other=$pid AND Year=$year");
  if ($res) {
    $o = $res->fetch_assoc();
    $Others[$pid][$year] = $o;
    return $o;
  }
}

function Get_Other_People_Day($day) { 
  global $db,$YEAR;
  $res = $db->query("SELECT o.*, y.* FROM OtherPart o, OtherPartYear y WHERE o.OtherId=y.Other AND y.Year=$YEAR AND y.$day=1 ORDER BY SName");
  if ($res) {
    while ($o = $res->fetch_assoc()) { $othrs[$o['OtherId']] = $o; };
    return $othrs;
  }
}

function Put_Other_Person($new) {
  global $db;

  $e=$now['OtherId'];
  $Cur = Get_Other_Person($e);
  Update_db('OtherPart',$Cur,$now);
}

function Put_Other_Person_Year($new) {
  global $db;

  $e=$now['OpyId'];
  $Cur = Get_Other_Person_Year($e);
  Update_db('OtherPartYear',$Cur,$now);
}

function Set_Other_Help() {

}

function Set_Other_Year_Help() {

}

function Other_All($Except=-1) {
  global $db;
  static $Other_All = array();
  static $Sides_Loaded = 0;
  if ($Other_Loaded == $Except) return $Other_All;
  $Other_All = array();
  $slist = $db->query("SELECT OtherId, Name FROM OtherPart ORDER BY SName");
  while ($row = $slist->fetch_assoc()) {
    $Other_All[$row['OtherId']] = $row['SName'];
  }
  $Other_Loaded = $Except;;
  return $Other_All;
}

?>
