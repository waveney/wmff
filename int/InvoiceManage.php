<?php
  include_once("fest.php");
  
  A_Check('Committee', 'Finance');

  dostaffhead("Invoice Management");

/* List all budget areas for year, if current allow edits, if historical not.
   Add Items, change items etc 
   Aimed for Treasurer/Me to update ONLY.  
   
   No user control to budgets YET think sections, think people, think...
   
   There is a much simpler BudgetView for lesser committee members
*/

  include_once("BudgetLib.php");
  include_once("InvoiceLib.php");
  include_once("TradeLib.php");

  global $YEAR,$PLANYEAR,$BUDGET,$USERID;

  echo "<h2>Manage Invoices - $YEAR</h2>\n";
  
  if (isset($_REQUEST['ACTION'])) {
    if (isset($_REQUEST['i'])) {
      $id = $_REQUEST['i'];
      $inv = Get_Invoice($id);  
    } else {
      $id = -1;
    }

    switch ($_REQUEST['ACTION']) {
    case 'PAID':
      $inv['PayDate'] = time();
      $inv['History'] .= "Fully Paid on " . date('j/n/Y') . " by $USERID\n";
      if ($Source == 1) Trade_F_Action($inv['SourceId'],'Paid',$inv['Total']); // TBW
      $inv['PaidTotal'] = $inv['Total'];
      break;
    
    case 'DIFF': // Need to find out ammount 
      $amt = $_REQUEST['amt']*100;
      if ($amt >= $inv['Total']) {
        $inv['PayDate'] = time();
        $inv['History'] .= "Fully Paid on " . date('j/n/Y') . " by $USERID\n";
      } else {
        $inv['History'] .= "Partially Paid " . Print_Pence($amt) . " on " . date('j/n/Y') . " by $USERID\n";
      }
      $inv['PaidTotal'] += $amt;
      if ($Source == 1) Trade_F_Action($inv['SourceId'],'Paid',$amt); 
      break;
        
    case 'CREDIT' :
      break; // TODO
      
    case 'NEW' :
      Create_Invoice();
      dotail();
      break;
      
    case 'UPDATE' :
      Update_db_post('Invoices',$inv);
      break;
      
    case 'CREATE' : 
    var_dump($_REQUEST);
      $Details = [];
      for ($i = 1;$i<=3; $i++) if (isset($_REQUEST["Amount$i"]) && $_REQUEST["Amount$i"]) $Details[] = [$_REQUEST["Desc$i"], round($_REQUEST["Amount$i"]*100), $_REQUEST["Budget$i"]];
      $Who = ($_REQUEST['OrgType'] ? Get_Organisation($_POST['Oid']) : Get_Trader($_REQUEST['Tid'])); 
      New_Invoice($Who,$Details,$_REQUEST['Reason'],$_REQUEST['InvCode'],$_REQUEST['Source']);
      break;
    }

  
  }
  
  if (isset($_REQUEST['Show'])) {
    // Show Invoice to be coded  
  }
  
  if (isset($_REQUEST['ALL'])) {
    $Invs = Get_Invoices();
    echo "<h2><a href=InvoiceManage.php?Y=$YEAR>Show Outstanding Only</a></h2>\n";
    $All = 1;
  } else {
    $Invs = Get_Invoices('PayDate=0 AND Total>0');  
    echo "<h2><a href=InvoiceManage.php?Y=$YEAR&ALL>Show All Invoices and Credit notes</a></h2>\n";
    $All = 0;
  }
   
  $coln = 0;
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Invoice Code</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Date Raised</a>\n";
  if ($All) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Date Paid</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Amount</a>\n";
  if ($All) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Paid Amount</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Reason</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Actions</a>\n";
  echo "</thead><tbody>";
  foreach($Invs as $i=>$inv) {
    $id = $inv['id'];
    echo "<tr><td><a href=InvoiceManage.php?Show=$id>$id</a>";
    echo "<td>" . $inv['BName']; // Make link soon
    echo "<td>" . $inv['InvoiceCode'];
    echo "<td>" . date('j/n/Y',$inv['IssueDate']);
    if ($All) echo "<td>" . ($inv['PayDate']? date('j/n/Y',$inv['PayDate']) : "" );
    echo "<td>" . Print_Pence($inv['Total']);
    if ($All) echo "<td>" . Print_Pence($inv['PaidTotal']);
    echo "<td>" . $inv['Reason'];
    echo "<td>"; 
      if ($inv['PayDate'] == 0 && $inv['Total']>0) {// Pay, pay diff, cancel/credit, change
        echo "<form method=post>" . fm_hidden('i',$id) . fm_hidden('amt',0);
        echo "<button name=ACTION value=PAID>Paid</button> ";
        echo "<button name=ACTION value=DIFF onclick='function(){ $(\'#amt\').val(prompt(\"How much?\",0))}'>Paid Different</button> ";
        echo "<button name=ACTION value=CREDIT>Cancel/credit</button> ";
      }
    echo "\n";
  }
  echo "</table>\n";
  
  echo "<h2><a href=InvoiceManage.php?ACTION=NEW>New Invoice</a></h2>";  
  dotail();

?>
