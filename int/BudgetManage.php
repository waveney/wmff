<?php
  include_once("fest.php");
  
  A_Check('Committee');
  $Manage = 0;
  if (Access('Committee','Finance')) $Manage = 1;
  if (isset($_GET['VIEW'])) $Manage = 0;

  dostaffhead(($Manage?"Manage":"View") . " Budget Items");

/* List all budget areas for year, if current allow edits, if historical not.
   Add Items, change items etc 
   Aimed for Treasurer/Me to update ONLY.  
   
   No user control to budgets YET think sections, think people, think...
   
   There is a much simpler BudgetView for lesser committee members
*/

  include_once("BudgetLib.php");
  global $YEAR,$PLANYEAR,$BUDGET;

  echo "<h2>Manage Budget - $YEAR</h2>\n";
  $_POST['Year0'] = $PLANYEAR;
  
  if (UpdateMany('BudgetAreas','Put_BudgetItem',$BUDGET)) $BUDGET = Get_Budget();

  Budget_Scan();

  $BUDGET[0]['id'] =-1;
//var_dump($BUDGET);
//  echo "Do not edit the <b>Used So Far</b> column - it should be autogenerated (rescan if in doubt)<p>\n";

  
  $coln = 0;
  if ($Manage) echo "<form method=post action=BudgetManage.php>";
  echo fm_hidden('Y',$YEAR);
  echo fm_hidden('Year0',$PLANYEAR);
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";
  if ($Manage) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Year</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Detail</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Budget</a>\n";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Used So Far</a>\n";
  /*
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Who</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Who 2</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Who 3</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Who 4</a>\n";
*/
  echo "</thead><tbody>";
  foreach($BUDGET as $i=>$b) {
    if ($i == $b['id']) {
      if ($Manage) {
        echo "<tr><td>$i";
        echo fm_text1("",$b,'SN',1,'','',"SN$i") . "</a>";
        echo "<td>" . $b['Year'] . fm_hidden("Year$i", $b['Year']);
        echo "<td><a href=BudgetDetail.php?b=$i>Detail</a>";
        echo fm_text1("",$b,'Budget',1,'','',"Budget$i") . "</a>";
        echo "<td>" . $b['CommittedSoFar'];
      } else {
        echo "<tr><td>" . $b['SN'] . "<td>" . $b['Year'] . "<td><a href=BudgetDetail.php?b=$i>Detail</a><td>" . $b['Budget'] . "<td>" . $b['CommittedSoFar']; 
      }
    } else {
      if ($Manage) {
        echo "<tr><td>-1<td>HOMELESS PERFORMERS<td><td><a href=BudgetDetail.php?b=0>Detail</a><td><td>" . $b['CommittedSoFar'];
      } else {
        echo "<tr><td>HOMELESS PERFORMERS<td><td><a href=BudgetDetail.php?b=0>Detail</a><td><td>" . $b['CommittedSoFar'];        
      }
    }
    echo "\n";
  }
  if (($YEAR == $PLANYEAR) && $Manage) {
    echo "<tr><td><td><input type=text name=SN0 ><td><input type=number value=$PLANYEAR name=Year0>";
    echo "<td><td><input type=number name=Budget0 >";
  }
  echo "</table></div>\n";
  
  if ($Manage) {  
    echo "<input type=submit name=Update value=Update>\n";
    echo "</form>";
    echo "<h2><a href=BudgetManage.php?VIEW>Budget View</a></h2><p>\n";
  }
  
  dotail();

?>
