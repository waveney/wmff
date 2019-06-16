<?php

/* Get/Put etc, also standard code to update a budget used number

*/

function Get_Budget() {
  global $YEAR,$db;  
  $full = [];
  $res = $db->query("SELECT * FROM BudgetAreas WHERE Year=$YEAR ORDER BY id ");
  if ($res) while ($spon = $res->fetch_assoc()) $full[$spon['id']] = $spon;
  $full[0] = ['id'=>0,'SN'=>'','Year'=>$YEAR,'CommittedSoFar'=>0];
  return $full;  
}


function Get_BudgetItem($id) {
  global $db;
  $res=$db->query("SELECT * FROM BudgetAreas WHERE id=$id");
  if ($res) return $res->fetch_assoc();
  return 0;   
}

function Put_BudgetItem(&$now) {
  $e=$now['id'];
  $Cur = Get_BudgetItem($e);
  return Update_db('BudgetAreas',$Cur,$now);
}

function Budget_Update($area,$value,$oldvalue=0) {

}

function Budget_Scan($Detail=0) {
  global $YEAR,$db,$BUDGET,$Coming_Idx,$YEARDATA;
  foreach ($BUDGET as $B) $B['CommittedSoFar'] = 0;

  include_once("DanceLib.php");
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$YEAR AND (y.Coming=2 OR y.YearState>=2) AND " .
         " ( TotalFee>0 OR OtherPayCost>0 OR (CampFri>0 OR CampSat>0 OR CampSun>0))";
  $res = $db->query($qry);
  if ($res) while ($sy = $res->fetch_assoc()) {
    if (preg_match('/N/',$Coming_Idx[$sy['Coming']]) && ($sy['YearState'] < 2)) continue;
    $Fee = $sy['TotalFee']+$sy['OtherPayCost'];
    $Camps =  ($sy['CampFri'] + $sy['CampSat'] + $sy['CampSun']) * $YEARDATA['CampingCost'];
//if ($sy['SideId'] == 484) { echo "Camps = $Camps<br>"; 
    $Fee += $Camps;
    if ($sy['BudgetArea2']) {
      $BUDGET[$sy['BudgetArea2']]['CommittedSoFar'] += $sy['BudgetValue2'];
      if ($Detail) $BUDGET[$sy['BudgetArea2']]['Detail'][] = [ $sy['SideId'], $sy['BudgetValue2']];
      $Fee -= $sy['BudgetValue2'];
    }
    if ($sy['BudgetArea3']) {
      $BUDGET[$sy['BudgetArea3']]['CommittedSoFar'] += $sy['BudgetValue3'];
      if ($Detail) $BUDGET[$sy['BudgetArea3']]['Detail'][] = [ $sy['SideId'], $sy['BudgetValue3']];
      $Fee -= $sy['BudgetValue3'];
    }
    $BUDGET[$sy['BudgetArea']]['CommittedSoFar'] += $Fee;
    if ($Detail) $BUDGET[$sy['BudgetArea']]['Detail'][] = [ $sy['SideId'], $Fee];
  }
}

global $BUDGET;
$BUDGET = Get_Budget();

function FindBudget($area) {
  global $BUDGET;
  foreach ($BUDGET as $i=>$b) if ($b['SN'] == $area) return $i;
  return 0;
}

function Budget_List() {
  global $BUDGET;
  static $blist;
  if ($blist || empty($BUDGET)) return $blist;
  $blist[0]='';
  foreach ($BUDGET as $i=>$b) $blist[$b['id']] = $b['SN'];
  return $blist;
}

/*
// Side snum has change - check budget effects, if present olddata is the older data
// Check Booking State and values
function UpdateBudget(&$newdata,&$olddata=0,$Type=0) { // Type 1 for Dance, 0 for Music/Other
  $UNeeded = 0
  if ($olddata)
    if ($olddata['TotalFee'] != $newdata['TotalFee']
  
  } else {
  
  }

}
*/


?>
