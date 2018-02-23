<?php
// Common Bugs Library

$Bug_Status = array('Raised','Quered','Confirmed','Fixed','Reopened','Finalised','Implimented');
$Severities = array('Request','Nitpic','Formating','Not Nice','Bug','Serious','Catastrophic');
$ReportSevs = array(1        ,0       ,0          ,0         ,1    ,1        ,1);
foreach ($Bug_Status as $level=>$text) { $Bug_Type[$text] = $level; };

function Set_Bug_Help() {
  static $t = array(
 	'SName'=>'Short title for bug/Feature'
  );
  Set_Help_Table($t);
}

function Get_Bug($id) {
  static $Bugs;
  global $db;
  if (isset($Bugs[$id])) return $Bugs[$id];
  $res=$db->query("SELECT * FROM Bugs WHERE BugId=$id");
  if ($res) {
    $ans = $res->fetch_assoc();
    $Bugs[$id] = $ans;
    return $ans;
  }
  return 0; 
}

function Put_Bug(&$now) {
  $e=$now['BugId'];
  $Cur = Get_Bug($e);
  Update_db('Bugs',$Cur,$now);
}

?>
