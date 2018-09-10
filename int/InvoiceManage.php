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

  global $YEAR,$PLANYEAR,$BUDGET,$USER;

//var_dump($_REQUEST);

  echo "<script>
    function diffprompt(id){
      $('#amt' + id).val(prompt('How much £?'))
    }
    </script>";
    
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
      $inv['History'] .= "Fully Paid on " . date('j/n/Y') . " by " . $USER['Login'] . "\n";
      $inv['PaidTotal'] = $inv['Total'];
      Put_Invoice($inv);
      if ($inv['Source'] == 1) Trade_F_Action($inv['SourceId'],'Paid',$inv['Total']); // TBW
      break;
    
    case 'DIFF': // Need to find out ammount 
      $amt = $_REQUEST["amt$id"]*100;
      if ($amt >= $inv['Total']) {
        $inv['PayDate'] = time();
        $inv['History'] .= "Fully Paid on " . date('j/n/Y') . " by " . $USER['Login'] . "\n";
      } else {
        $inv['History'] .= "Partially Paid " . Print_Pence($amt) . " on " . date('j/n/Y') . " by " . $USER['Login'] . "\n";
      }
      $inv['PaidTotal'] += $amt;
      Put_Invoice($inv);
      if ($inv['Source'] == 1) Trade_F_Action($inv['SourceId'],'Paid',$amt); 
      break;
        
    case 'CREDIT' :
      break; // TODO
      
    case 'NEW' :
      Create_Invoice();
      dotail();
      break;
      
    case 'UPDATE' :
      echo "HERE<p>";
      Update_db_post('Invoices',$inv);
      break;
      
    case 'CREATE' : 
//    var_dump($_REQUEST);
      $Details = [];
      for ($i = 1;$i<=3; $i++) if (isset($_REQUEST["Amount$i"]) && $_REQUEST["Amount$i"]) $Details[] = [$_REQUEST["Desc$i"], round($_REQUEST["Amount$i"]*100)];
      $Who = ($_REQUEST['OrgType'] ? Get_Organisation($_POST['Oid']) : Get_Trader($_REQUEST['Tid'])); 
      New_Invoice($Who,$Details,$_REQUEST['Reason'],$_REQUEST['InvoiceCode'],$_REQUEST['Source']);
      break;

    case 'PRINTPDF':
      Invoice_Print($inv);
      break;
    }

  
  }
  
  if (isset($_REQUEST['Show'])) {
    Show_Invoice($_REQUEST['Show']);
    dotail();
  }
  
  $NewXtra = '';
  if (isset($_REQUEST['ALL'])) {
    $Invs = Get_Invoices();
    echo "<h2><a href=InvoiceManage.php?Y=$YEAR>Show Outstanding Only</a></h2>\n";
    $All = 1;
  } elseif (isset($_REQUEST['FOR'])) {
    $Trad = Get_Trader($_REQUEST['FOR']);
    $Invs = Get_Invoices(" OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
    $All = 1;
    $NewXtra = "&Tid=" . $Trad['Tid'];
  } else {
    $Invs = Get_Invoices('PayDate=0 AND Total>0');  
    echo "<h2><a href=InvoiceManage.php?Y=$YEAR&ALL>Show All Invoices and Credit notes</a></h2>\n";
    $All = 0;
  }

  
  $Now = time();
  $coln = 0;
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  if ($All) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Inv/CN</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Our Ref</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Date Raised</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Date Due</a>\n";
  if ($All) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Date Paid</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Amount (left)</a>\n";
  if ($All) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Paid Amount</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Reason</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Actions</a>\n";
  echo "</thead><tbody>";
  foreach($Invs as $i=>$inv) {
    $id = $inv['id'];
    echo "<tr><td><a href=InvoiceManage.php?Show=$id>$id</a>";
    if ($All) echo "<td>" . ($inv['Total']>=0 ? 'Invoice' : 'Credit Note') ; 
    echo "<td>" . $inv['BZ']; // Make link soon
    echo "<td>" . $inv['OurRef'] . '/' . $inv['id'];
    echo "<td>" . date('j/n/Y',$inv['IssueDate']);
    echo "<td>";
    if ($inv['Total'] > 0) {
      if  ($inv['DueDate'] < $Now && $inv['PaidTotal']<$inv['Total']) {
        echo "<span class=red>" . date('j/n/Y',$inv['DueDate']) . "</span>";
      } else {
        echo date('j/n/Y',$inv['DueDate'] );
      }
    }
    if ($All) echo "<td>" . ($inv['PayDate']? date('j/n/Y',abs($inv['PayDate'])) : "" );
    echo "<td>" . Print_Pence($inv['Total']);
    if ($inv['PaidTotal'] > 0 && $inv['PaidTotal'] < $inv['Total']) echo " (" . Print_Pence($inv['Total'] - $inv['PaidTotal']) . ")";
    if ($All) echo "<td>" . Print_Pence($inv['PaidTotal']);
    echo "<td>" . $inv['Reason'];
    echo "<td>"; 
      if ($inv['PayDate'] == 0 && $inv['Total']>0) {// Pay, pay diff, cancel/credit, change
        echo "<form method=post>" . fm_hidden('i',$id) . fm_hidden("amt$id",0);
        echo "<button name=ACTION value=PAID>Paid</button> ";
        echo "<button name=ACTION value=DIFF onclick=diffprompt($id) >Paid Different</button> ";
        echo "<button name=ACTION value=CREDIT>Cancel/credit</button> ";
        echo "</form>";
      }
    echo "\n";
  }
  echo "</table>\n";
  
  echo "<h2><a href=InvoiceManage.php?ACTION=NEW$NewXtra>New Invoice</a></h2>";  
  dotail();

?>
