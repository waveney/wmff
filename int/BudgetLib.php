<?php

/* Get/Put etc, also standard code to update a budget used number

*/

function Get_Budget($yr=0) {
  global $YEAR,$db;
  if ($yr == 0) $yr=$YEAR;
  
  $full = [];
  $res = $db->query("SELECT * FROM BudgetAreas WHERE Year=$yr ORDER BY SName ");
  if ($res) while ($spon = $res->fetch_assoc()) $full[] = $spon;
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

function Budget_rescan() {

}

?>
