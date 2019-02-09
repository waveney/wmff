<?php
  include_once("fest.php");
  include_once("DateTime.php");
  
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

  global $YEAR,$PLANYEAR,$BUDGET,$USER,$OpayStates,$NewInvoiceId,$NewInv;

  Set_Invoice_Help();
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
    } elseif (isset($_REQUEST['id'])) {
      $id = $_REQUEST['id'];
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
      $inv['PaidTotal'] += $amt;
      if ($inv['PaidTotal'] == $inv['Total']) {
        $inv['PayDate'] = time();
        $inv['History'] .= "Fully Paid on " . date('j/n/Y') . " by " . $USER['Login'] . "\n";
      } else  if ($inv['PaidTotal'] > $inv['Total']) {

        if ($inv['Source'] == 1) {
          $inv['History'] .= "Overpaid Paid total of " . $amt/100 . " on " . date('j/n/Y') . " by " . $USER['Login'] . "\n";
          Put_Invoice($inv);
          Trade_F_Action($inv['SourceId'],'Paid',$amt/100,$id); // Will cause update to invoice - hence saved before call


          break;
        }
        // TODO Overpayment non trade Invoices
      } else {
        $inv['History'] .= "Partially Paid " . Print_Pence($amt) . " on " . date('j/n/Y') . " by " . $USER['Login'] . "\n";
      }

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
      if (isset($_REQUEST['PAYCODES'])) {
        Pay_Update($id);
        break;
      }
      $Dateflds = ['DueDate'];
      Parse_DateInputs($Dateflds);
      Update_db_post('Invoices',$inv);
      Show_Invoice($_REQUEST['i'],$ViewOnly);
      dotail();      
      break;
      
    case 'CREATE' : 
//    var_dump($_REQUEST);
      $Details = [];
      for ($i = 1;$i<=3; $i++) if (isset($_REQUEST["Amount$i"]) && $_REQUEST["Amount$i"]) $Details[] = [$_REQUEST["Desc$i"], round($_REQUEST["Amount$i"]*100)];
      $Who = ($_REQUEST['OrgType'] ? Get_Trader($_REQUEST['Oid']) : Get_Trader($_REQUEST['Tid'])); 
      
      New_Invoice($Who,$Details,$_REQUEST['Reason'],$_REQUEST['InvoiceCode'],$_REQUEST['Source'],$_REQUEST['DueDays']);
      $NewInv['CoverNote'] = Get_Email_Proforma('Finance_Default_Cover')['Body'];
      $NewInv['id'] = $NewInvoiceId;
      Put_Invoice($NewInv);
      Show_Invoice($NewInvoiceId,$ViewOnly);
      dotail();      
      break;

    case 'PRINTPDF':
      Invoice_Print($inv);
      break;
      
      
    case 'PCLOSE' :
      $pay = Get_PayCode($id);
      $pay['Year'] = - $pay['Year'];
      Put_PayCode($pay);
      break;

    case 'PPAID' :
      $pay = Get_PayCode($id);
      $pay['State'] = array_flip($OpayStates)['Paid'];
      $pay['PayDate'] = time();
      Put_PayCode($pay);
      Call_Invoice_User($pay['Source'],$pay['Code'],'Paid');
      break;
    
    case 'PSHOW' :
      Pay_Show($_REQUEST['PShow']);
      break;
      
    case 'SEND' :
      // Get cover note and send out and record
      $subject = $MASTER_DATA['FestName'] . " $PLANYEAR and " . $inv['BZ'];
      $too = [['to',$inv['Email'],$inv['Contact']],['from','Finance@' . $MASTER_DATA['HostURL'],'Wimborne Finance'],['replyto','Finance@' . $MASTER_DATA['HostURL'],'Wimborne Finance']];
      $pdf = Get_Invoice_Pdf($id,'',$inv['Revision']);
      echo Email_Proforma($too,$inv['CoverNote'],$subject,'Invoice_Email_Details',$inv,$logfile='Invoices',$pdf);
      $inv['EmailDate'] = time();
      echo "Invoice " . $id . " sent to " . $inv['Contact'] . " at " . $inv['BZ'] . "<p>";
      Put_Invoice($inv);
      break;
      
    case 'BESPOKE' :
      // Get cover note and use SendFinanceProfEmail and record

      Bespoke_Inv_CoverNote($id,$inv);
      break;
    
    case 'RESEND' :
      // Resend current cover note
      $subject = $MASTER_DATA['FestName'] . " $PLANYEAR and " . $inv['BZ'];
      $too = [['to',$inv['Email'],$inv['Contact']],['from','Finance@' . $MASTER_DATA['HostURL'],'Wimborne Finance'],['replyto','Finance@' . $MASTER_DATA['HostURL'],'Wimborne Finance']];
      $pdf = Get_Invoice_Pdf($id,'',$inv['Revision']);
      echo Email_Proforma($too,$inv['CoverNote'],$subject,'Invoice_Email_Details',$inv,$logfile='Invoices',$pdf);

      echo "Invoice " . $id . " resent to " . $inv['Contact'] . " at " . $inv['BZ'] . "<p>";
      Put_Invoice($inv);
      break;
          
    case 'SENT' :
      // Just record it has been sent (not using system)
      $inv['EmailDate'] = time();
      $inv['History'] .= "Recorded as sent: " . date('j/n/d') . "\n";
      echo "Invoice " . $id . " recorded as sent to " . $inv['Contact'] . " at " . $inv['BZ'] . "<p>";
      Put_Invoice($inv);
      break;

    
    case 'DOWNLOAD' :
      // Download pdf of invoice - no actions to be taken
      
      break;
      
    }

  
  }
  
  if (isset($_REQUEST['Show'])) {
    Show_Invoice($_REQUEST['Show'],$ViewOnly);
    dotail();
  }
  
  $NewXtra = $NewXtraTxt = '';
  $Pays = [];
  if (isset($_REQUEST['ALL'])) {
    echo "<h2>Manage Invoices - $YEAR</h2>\n";
    $Invs = Get_Invoices();
    $Pays = Get_PayCodes("");
    echo "<h2><a href=InvoiceManage.php?Y=$YEAR>Show Outstanding Only</a></h2>\n";
    $All = 1;
    if ($All && Access('SysAdmin')) echo "The Paid Special is to re-trigger Paid analysis - input 0 in most cases";
  } elseif (isset($_REQUEST['FOR'])) {
    $Trad = Get_Trader($_REQUEST['FOR']);
    $Tname = $Trad['SN'];

    $Invs = Get_Invoices(" OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
    $All = 1;
    $NewXtra = "&Tid=" . $Trad['Tid'];
    $NewXtraTxt = " For $Tname";
    if ($Invs) {
      echo "<h2>Manage Invoices - $YEAR - $Tname</h2>\n";
    } else {
      echo "<h2>There are no Invoices currently for " . $Trad['SN'] . "</h2>";
    } 
  } else {
    echo "<h2>Manage Invoices - $YEAR</h2>\n";
    $Pays = Get_PayCodes("State=0");
    $Invs = Get_Invoices('PayDate=0 AND Total>0');  
    echo "<h2><a href=InvoiceManage.php?Y=$YEAR&ALL>Show All Invoices and Credit notes</a></h2>\n";
    $All = 0;
  }


  if ($Invs) {  
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
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State</a>\n";
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
    echo "<td>" ; // Status
      $stat = 0;
      if (($inv['Source'] == 2) && ($inv['EmailDate'] == 0)) {
        if ($stat++) echo ", ";
        echo "Not Sent Yet";
      } else {
      if ($inv['PaidTotal'] == 0) {
        if ($stat++) echo ", ";
        echo "Not Paid";               
        }
      elseif ($inv['PaidTotal'] < $inv['Total']) {
        if ($stat++) echo ", ";
        echo "Part Paid";
        }
      if (($inv['PaidTotal'] < $inv['Total']) && ($inv['DueDate'] < $Now)) {
        if ($stat++) echo ", ";     
        echo "<span class=Err>Overdue</span>";
        }
      }
    
    if (!$ViewOnly) { 
      echo "<td>"; 
      echo "<form method=post>" . fm_hidden('i',$id) . fm_hidden("amt$id",0) . fm_hidden("reason$id",'');
      if ($inv['PayDate'] == 0 && $inv['Total']>0) {// Pay, pay diff, cancel/credit, change
        echo "<button name=ACTION value=PAID>Paid</button> ";
        echo "<button name=ACTION value=DIFF onclick=diffprompt($id) >Paid Different</button> ";
        echo "<button name=ACTION value=CREDIT onclick=reasonprompt($id) >Cancel/credit</button> ";
      }
      if ($All && Access('SysAdmin')) echo "<button name=ACTION value=DIFF onclick=diffprompt($id) >Paid Special</button> ";
      echo "</form>";
    }
    
    $Rev = ($inv['Revision']?"R" .$inv['Revision']:"");
    echo "<td><a href=ShowFile.php?l=" . Get_Invoice_Pdf($id,'',$Rev) . ">View</a>";
    if ($All && $inv['PayDate']<0) echo ", <a href=ShowFile.php?l=" . Get_Invoice_Pdf($id,'CN',$Rev) . ">Credit&nbsp;Note</a>";
    echo "\n";
  }
  
  
// PayCodes  


  foreach($Pays as $i=>$pay) {
    $id = $pay['id'];
    echo "<tr><td><a href=InvoiceManage.php?ACTION=PSHOW&PShow=$id>$id</a>";
    if ($All) echo "<td>Payment"; 
    echo "<td>" .$pay['SN']; // . $pay['BZ']; // Make link soon TODO
    echo "<td>" . $pay['Code'];
    echo "<td>" . date('j/n/Y',$pay['IssueDate']);
    echo "<td>"; // Due Date
    if ($All) echo "<td>" . ($pay['State']==1? date('j/n/Y',abs($inv['PayDate'])) : ($inv['PayDate']<0? "NA": ""));
    echo "<td>" . Print_Pence($pay['Amount']);
    if ($All) echo "<td>";
    echo "<td>" . $pay['Reason'] . "<td>";
    if (!$ViewOnly) { 
      echo "<td>"; 
      echo "<form method=post>" . fm_hidden('i',$id) . fm_hidden("amt$id",0) . fm_hidden("reason$id",'');
        if ($pay['PayDate'] == 0 && $pay['Amount']>0) {// Pay, pay diff, cancel/credit, change
          echo "<button name=ACTION value=PPAID>Paid</button> "; 
        }
      if (Access('SysAdmin')) echo "<button name=ACTION value=PCLOSE>Close</button> ";
//        echo "<button name=ACTION value=DIFF onclick=diffprompt($id) >Paid Different</button> ";
//        echo "<button name=ACTION value=CREDIT onclick=reasonprompt($id) >Cancel/credit</button> ";
      echo "</form>";
    }
    echo "<td>";
    echo "\n";
  }
  
  echo "</table>\n";
  }
  
  echo "<h2><a href=InvoiceManage.php?ACTION=NEW$NewXtra>New Invoice $NewXtraTxt</a>";  
  if (isset($Tname)) echo ", <a href=Trade.php?id=" . $_REQUEST['FOR'] . "&Y=$YEAR>Back to $Tname</a>";
  echo "</h2>";
  
  dotail();

?>
