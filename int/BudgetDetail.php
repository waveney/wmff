<?php
  include_once("fest.php");
  
  if (Access('Committee','Finance')) $Manage = 1;

  dostaffhead("Budget Detail");

/* List all budget areas for year, if current allow edits, if historical not.
   Add Items, change items etc 
   Aimed for Treasurer/Me to update ONLY.  
   
   No user control to budgets YET think sections, think people, think...
   
   There is a much simpler BudgetView for lesser committee members
*/

  include_once("BudgetLib.php");
  global $YEAR,$PLANYEAR,$BUDGET;


  
  echo "<h2>Manage Budget - $YEAR</h2>\n";
  if (UpdateMany('BudgetAreas','Put_BudgetItem',$BUDGET)) $BUDGET = Get_Budget();

  Budget_Scan(1);
  
  $Bitem = $BUDGET[$_GET['b']]['Detail']; 
  
    
//var_dump($BUDGET);
//  echo "Do not edit the <b>Used So Far</b> column - it should be autogenerated (rescan if in doubt)<p>\n";

  
  $coln = 0;
  echo fm_hidden('Y',$YEAR);
  echo fm_hidden('Year0',$PLANYEAR);
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Type</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Cost</a>\n";
  echo "</thead><tbody>";
  foreach($Bitem as $i=>$b) {
    $act = Get_Side($b[0]);
    echo "<tr><td>";
    if ($act['IsASide']) echo "Side ";
    if ($act['IsAnAct']) echo "Act ";
    if ($act['IsOther']) echo "Other ";
    echo "<td>";
    echo "<a href=" . (($act['IsASide'])?"AddDance.php":"AddMusic.php") . "?sidenum=" . $b[0] . ">" . $act['SName'] . "</a>";
    echo "<td>";
    echo $b[1];
    }
  echo "</table>\n";
  
  echo "<h2><a href=BudgetManage.php?>Back Budget Management</a></h2><p>\n";
  
  dotail();

?>