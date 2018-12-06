<?php
  include_once("fest.php");
  
  $ViewOnly = 0;
  if (!Access('Committee', 'Finance')) {
    A_Check('Committee');
    $ViewOnly = 1;
  }

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
     function reasonprompt(id){
      $('#reason' + id).val(prompt('Reason?'))
    }
   </script>";
    
  
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
      if ($inv['Source'] == 1) Trade_F_Action($inv['SourceId'],'Paid',$inv['Total']/100); // TBW
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
      if ($inv['Source'] == 1) Trade_F_Action($inv['SourceId'],'Paid',$amt/100); 
      break;
        
    case 'CREDIT' :
      $Reason = $_REQUEST["reason$id"];
      Invoice_Credit_Note($inv, $Reason);
      if ($inv['Source'] == 1) Trade_F_Action($inv['SourceId'],'Cancel',$Reason); 
      break;
      
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
    Show_Invoice($_REQUEST['Show'],$ViewOnly);
    dotail();
  }
  
  $NewXtra = $NewXtraTxt = '';
  if (isset($_REQUEST['ALL'])) {
    echo "<h2>Manage Invoices - $YEAR</h2>\n";
    $Invs = Get_Invoices();
    echo "<h2><a href=InvoiceManage.php?Y=$YEAR>Show Outstanding Only</a></h2>\n";
    $All = 1;
  } elseif (isset($_REQUEST['FOR'])) {
    $Trad = Get_Trader($_REQUEST['FOR']);
    $Tname = $Trad['SN'];
    echo "<h2>Manage Invoices - $YEAR - $Tname</h2>\n";
    $Invs = Get_Invoices(" OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
    $All = 1;
    $NewXtra = "&Tid=" . $Trad['Tid'];
    $NewXtraTxt = " For $Tname";
  } else {
    echo "<h2>Manage Invoices - $YEAR</h2>\n";
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
  if (!$ViewOnly) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Actions</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>View</a>\n";
  echo "</thead><tbody>";
  foreach($Invs as $i=>$inv) {
    $id = $inv['id'];
    echo "<tr><td><a href=InvoiceManage.php?Show=$id>$id</a>";
    if ($All) echo "<td>" . ($inv['PayDate']>=0 ? 'Invoice' : 'Credit Note') ; 
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
    if ($All) echo "<td>" . ($inv['PayDate']>0? date('j/n/Y',abs($inv['PayDate'])) : ($inv['PayDate']<0? "NA": ""));
    echo "<td>" . Print_Pence($inv['Total']);
    if ($inv['PaidTotal'] > 0 && $inv['PaidTotal'] != $inv['Total']) echo " (" . Print_Pence($inv['Total'] - $inv['PaidTotal']) . ")";
    if ($All) echo "<td>" . Print_Pence($inv['PaidTotal']);
    echo "<td>" . $inv['Reason'];
    if (!$ViewOnly) { 
      echo "<td>"; 
      if ($inv['PayDate'] == 0 && $inv['Total']>0) {// Pay, pay diff, cancel/credit, change
        echo "<form method=post>" . fm_hidden('i',$id) . fm_hidden("amt$id",0) . fm_hidden("reason$id",'');
        echo "<button name=ACTION value=PAID>Paid</button> ";
        echo "<button name=ACTION value=DIFF onclick=diffprompt($id) >Paid Different</button> ";
        echo "<button name=ACTION value=CREDIT onclick=reasonprompt($id) >Cancel/credit</button> ";
        echo "</form>";
      }
    }
    echo "<td><a href=ShowFile.php?l=" . Get_Invoice_Pdf($id) . ">View</a>";
    if ($All && $inv['PayDate']<0) echo ", <a href=ShowFile.php?l=" . Get_Invoice_Pdf($id,'CN') . ">Credit Note</a>";
    echo "\n";
  }
  echo "</table>\n";
  
  echo "<h2><a href=InvoiceManage.php?ACTION=NEW$NewXtra>New Invoice $NewXtraTxt</a>";  
  if (isset($Tname)) echo ", <a href=Trade.php?id=" . $_REQUEST['FOR'] . "&Y=$YEAR>Back to $Tname</a>";
  echo "</h2>";
  
  dotail();

?>
