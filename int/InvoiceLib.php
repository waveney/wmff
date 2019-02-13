<?php

global $Reserved_Codes,$Invoice_Sources,$Org_Cats,$OpayStates;

$Invoice_Sources = ['','Trade','Other Finance','Buskers Bash','Live and Loud'];
$Org_Cats = ['Trader','Business or Organistaion'];
$Reserved_Codes = ['BB','LNL'];
$OpayStates = ['Open','Paid','Cancelled'];

/* Invoice Notes 
  A credit note has a negative total and paydate set to issue date
  An invoice that has been credit noted will have its paydate set to negative


*/

include_once("SignupLib.php");

// Get all invoices for YEAR that meet cond
function Get_Invoices($cond = '',$order='id') {
  global $YEAR,$db;  
  $full = [];
  $res = $db->query("SELECT * FROM Invoices WHERE Year=$YEAR " . ($cond ? " AND ( $cond )" : "" ) . " ORDER BY $order ");
  if ($res) while ($inv = $res->fetch_assoc()) $full[] = $inv;
  return $full;  
}

function Get_InvoicesFor($id) {
  global $db;  
  $full = [];
  $res = $db->query("SELECT * FROM Invoices WHERE SourceId=$id ORDER BY id DESC");
  if ($res) while ($inv = $res->fetch_assoc()) $full[] = $inv;
  return $full;  
}

function Get_Invoice($id) {
  global $db;
  $res=$db->query("SELECT * FROM Invoices WHERE id=$id");
  if ($res) return $res->fetch_assoc();
  return 0;   
}

function Put_Invoice(&$now) {
  $e=$now['id'];
  $Cur = Get_Invoice($e);
  return Update_db('Invoices',$Cur,$now);
}

function Get_PayCodes($cond = '',$order='id') {
  global $YEAR,$db;  
  $full = [];
  $res = $db->query("SELECT * FROM OtherPayments WHERE Year=$YEAR " . ($cond ? " AND ( $cond )" : "" ) . " ORDER BY $order ");
  if ($res) while ($inv = $res->fetch_assoc()) $full[] = $inv;
  return $full;  
}

function Get_PayCode($id) {
  global $db;
  $res=$db->query("SELECT * FROM OtherPayments WHERE id=$id");
  if ($res) return $res->fetch_assoc();
  return 0;   
}

function Put_PayCode(&$now) {
  $e=$now['id'];
  $Cur = Get_PayCode($e);
  return Update_db('OtherPayments',$Cur,$now);
}

function Inv_Amt($amt) {
  $str = '';
  if ($amt < 0) { $str .= "-"; $amt = - $amt; }
  $str .= utf8_decode("£") . sprintf("%0.2f",$amt/100 );
  return $str;
}

// Print the Invoice pdf, returns 0 if it cant
function Invoice_Print(&$inv) {
  global $MASTER_DATA,$PLANYEAR;
  
  $CN = ((isset($inv['PayDate']) && $inv['PayDate']<0)?'CN':'');
  $Rev = ((isset($inv['Revision']) && $inv['Revision'])?("R" . $inv['Revision'] ):'');
  
  // File for printings dddDDD is Invoices/ddd/dddDDD.pdf
  // Credit notes Invoices/ddd/DDDCN.pdf
  include_once('fpdf.php');
  $cw = 3;
  $ch = 5;
  $fs = 13;
  $padx = 10;
  $pady = 10;
  
  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial','',$fs);
  $pdf->SetMargins(1,1,1,1);
  $pdf->Image('images/icons/Long-Banner-Logo.png',$padx,$pady,40*$cw);
  $pdf->SetLineWidth(0.5);

  $pdf->Text($padx+$cw,$pady+7*$ch,$inv['BZ']); // Who box
  $ln = 1;
  foreach (explode(',', $inv['Address'] . "," . $inv['PostCode']) as $bit) $pdf->Text($padx+$cw,$pady+(7+$ln++)*$ch,trim($bit));
  $pdf->Rect($padx,$pady+6*$ch,32*$cw,($ln+0.5)*$ch); 
  
  $pdf->Rect($padx+36*$cw,$pady+6*$ch,27*$cw,4.5*$ch); // Ref Box
  
  if (!$CN) {
    $pdf->Text($padx+37*$cw,$pady+7*$ch,"Invoice No:");
    $pdf->Text($padx+48*$cw,$pady+7*$ch,$inv['id'] . " $Rev");
  }
  
  $pdf->Text($padx+37*$cw,$pady+8*$ch,"Date:");
  $pdf->Text($padx+48*$cw,$pady+8*$ch,date('j F Y',($CN?-$inv['PayDate']:$inv['IssueDate'])));
  
  $pdf->Text($padx+37*$cw,$pady+9*$ch,"Your Ref:");
  $yourref = $inv['Contact'];
  if (strlen($yourref) > 15 ) {
    $yourref = firstword($yourref);
    if (strlen($yourref) > 15 ) $yourref = substr($yourref,0,15);
    }
  $pdf->Text($padx+48*$cw,$pady+9*$ch,$yourref);
  
  $pdf->Text($padx+37*$cw,$pady+10*$ch,"Our Ref:");
  $pdf->Text($padx+48*$cw,$pady+10*$ch,$inv['OurRef'] . '/' . $inv['id']);
  
  $pdf->SetFont('Arial','B',24);  // Main Header
  if ($CN) {
    $pdf->Text($padx+24.2*$cw,$pady+15*$ch,"Credit Note"); 
  } else {
    $pdf->Text($padx+27*$cw,$pady+15*$ch,"Invoice");   
  }
  $pdf->SetFont('Arial','',$fs);  
  
  $pdf->Rect($padx,$pady+16*$ch,$padx+60*$cw,$pady+17*$ch); // Main invoice BOx
  $pdf->SetLineWidth(0.25);  
  $pdf->Line($padx,$pady+18*$ch,$padx+63.2*$cw,$pady+18*$ch);
  $pdf->Line($padx+54*$cw,$pady+16*$ch,$padx+54*$cw,$pady+35*$ch);
  $pdf->Line($padx+44*$cw,$pady+16*$ch,$padx+44*$cw,$pady+35*$ch);
  
  $pdf->SetFont('Arial','B',$fs);
  $pdf->Text($padx+17*$cw,$pady+17.3*$ch,"DESCRIPTION");
  $pdf->Text($padx+47.5*$cw,$pady+17.3*$ch,"VAT");
  $pdf->Text($padx+55.5*$cw,$pady+17.3*$ch,"AMOUNT");

  $pdf->SetFont('Arial','BU',14);  
  $pdf->Text($padx+$cw,$pady+19.5*$ch,"Re: " . $MASTER_DATA['FestName'] . " " . $PLANYEAR);
  $pdf->SetFont('Arial','',$fs);

  if ($CN) {
    $pdf->Text($padx+$cw,$pady+ 21*$ch,"To negate our invoice " . $inv['OurRef'] . '/' . $inv['id'] ." issued on " . date('j/n/Y',$inv['IssueDate']));
    $pdf->Text($padx+46*$cw,$pady+21*$ch, utf8_decode("£0.00"));
    $pdf->Text($padx+56*$cw,$pady+21*$ch,Inv_Amt(-$inv['Total']));
    $pdf->Text($padx+$cw,$pady+ 23*$ch,$inv['CNReason']);
    if ($inv['PaidTotal']) {
      $pdf->SetTextColor(255,0,0);
      $pdf->Text($padx+$cw, $pady+25*$ch,"Less paid so far");
      $pdf->Text($padx+46*$cw,$pady+25*$ch,utf8_decode("£0.00"));
      $pdf->Text($padx+56*$cw,$pady+25*$ch,Inv_Amt(-$inv['PaidSoFar']));
    } 
  } else {  
    if ($inv['Desc1']) $pdf->Text($padx+$cw,$pady+ 21*$ch,$inv['Desc1']);
    if ($inv['Amount1']) $pdf->Text($padx+46*$cw,$pady+21*$ch, utf8_decode("£0.00"));
    if ($inv['Amount1']) $pdf->Text($padx+56*$cw,$pady+21*$ch,Inv_Amt($inv['Amount1']));
  
    if ($inv['Amount1']>0 && ($inv['Amount2'])<0) { $pdf->SetTextColor(255,0,0); } else $pdf->SetTextColor(0,0,0);
    if ($inv['Desc2']) $pdf->Text($padx+$cw,$pady+ 23*$ch,$inv['Desc2']);
    if ($inv['Amount2']) $pdf->Text($padx+46*$cw,$pady+23*$ch,utf8_decode("£0.00"));
    if ($inv['Amount2']) $pdf->Text($padx+56*$cw,$pady+23*$ch,Inv_Amt($inv['Amount2']));
  
    if ($inv['Amount1']>0 && ($inv['Amount3'])<0) { $pdf->SetTextColor(255,0,0); } else $pdf->SetTextColor(0,0,0);
    if ($inv['Desc3']) $pdf->Text($padx+$cw, $pady+25*$ch,$inv['Desc3']);
    if ($inv['Amount3']) $pdf->Text($padx+46*$cw,$pady+25*$ch,utf8_decode("£0.00"));
    if ($inv['Amount3']) $pdf->Text($padx+56*$cw,$pady+25*$ch,Inv_Amt($inv['Amount3']));
  }
  $pdf->SetTextColor(0,0,0);
      
  if (!$CN) {
    $pdf->SetFont('Arial','B',14);
    $pdf->Text($padx+$cw,$pady+29*$ch,"BACS PAYMENTS TO:");  
    $pdf->SetFont('Arial','',$fs);
    $pdf->Text($padx+$cw,$pady+31*$ch,"TSB Bank, 5 The Square, Wimborne, Dorset BH21 1JE");
    $pdf->Text($padx+$cw,$pady+32*$ch,"Sort Code:"); 
    $pdf->Text($padx+23*$cw,$pady+32*$ch,"77-50-27"); 
    $pdf->Text($padx+$cw,$pady+33*$ch,"Account No:");
    $pdf->Text($padx+23*$cw,$pady+33*$ch,"22719960");
    $pdf->Text($padx+$cw,$pady+34*$ch,"Quote Reference:");
    $pdf->Text($padx+23*$cw,$pady+34*$ch,$inv['OurRef'] . '/' . $inv['id'] );
  }
 
  // Totals
  
  $pdf->SetLineWidth(0.5); 
  $pdf->Rect($padx+54*$cw,$pady+35*$ch,9.35*$cw,6*$ch);
  $pdf->SetLineWidth(0.25);
  $pdf->Rect($padx+44*$cw,$pady+37*$ch,19.35*$cw,2*$ch);
  $pdf->Text($padx+36*$cw,$pady+36.5*$ch,"Net");
  $pdf->Text($padx+56*$cw,$pady+36.5*$ch,Inv_Amt($CN?$inv['PaidTotal']-$inv['Total']:$inv['Total']));
  $pdf->Text($padx+36*$cw,$pady+38.5*$ch,"VAT");
  $pdf->Text($padx+48*$cw,$pady+38.5*$ch,'"T0"');
  $pdf->Text($padx+56*$cw,$pady+38.5*$ch,utf8_decode(" £0.00"));
  $pdf->Text($padx+36*$cw,$pady+40.5*$ch,"Gross");
  $pdf->SetFont('Arial','B',$fs);
  $pdf->Text($padx+56*$cw,$pady+40.5*$ch,Inv_Amt($CN?$inv['PaidTotal']-$inv['Total']:$inv['Total']));
  $pdf->SetFont('Arial','',$fs);

  // Payment terms
  
  if (!$CN) {
    $pdf->SetFont('Arial','B',$fs+1);
    if (isset($inv['PayDate']) && $inv['PayDate']) {
      $pdf->Text($padx+10*$cw,$pady+44*$ch,"PAYMENT TERMS: PAID WITH THANKS " . date('j/n/Y',$inv['PayDate']) );
    } else {
      $pdf->Text($padx+10*$cw,$pady+44*$ch,"PAYMENT TERMS: PAYABLE BY " . date('j/n/Y',$inv['DueDate']) . " PLEASE");
    }
  }
  $pdf->SetFont('Arial','',$fs); 

  // footer
  $pdf->Text($padx+1*$cw,$pady+48*$ch,"Registered Office:");
  $pdf->Text($padx+15*$cw,$pady+48*$ch,"Wimborne Minster Folk Festival Ltd");
  $pdf->Text($padx+15*$cw,$pady+49*$ch,"12 Bramshaw Way");
  $pdf->Text($padx+15*$cw,$pady+50*$ch,"New Milton");
  $pdf->Text($padx+15*$cw,$pady+51*$ch,"Hampshire BH25 7ST");
  
  $pdf->Text($padx+1*$cw,$pady+53*$ch,"Email:Treasurer@wimbornefolk.co.uk");

  $pdf->Text($padx+36*$cw,$pady+53*$ch,"Registered in England 08290423");
  $pdf->Text($padx+36*$cw,$pady+54*$ch,"Non-VAT Registered");

  $id = $inv['id'];
  $dir = "Invoices/" . substr($id,0,-3 ) . "000";
  if (!file_exists($dir)) mkdir($dir,0777,1);
  $pdf->Output('F',"$dir/$id$CN$Rev.pdf");

//  $pdf->Output('F',"Temp/Invoice.pdf");
//  echo "<h2>pdf outputed</h2>";
  return "$dir/$id$CN$Rev.pdf";
}  

// Returns the file name of Pdf of a previously printed invoice
function Get_Invoice_Pdf($id,$CN='',$Rev='') {
  if ($Rev == 0) $Rev = '';
  if ($Rev && substr($Rev,0,1) != 'R') $Rev = "R$Rev";
  return "Invoices/" . substr($id,0,-3) . "000/$id$CN$Rev.pdf"; 
}

function Sage_Code(&$Whose) { // May only work for trade at the moment
  include_once("TradeLib.php");
  global $db,$Reserved_Codes;

  if (isset($Whose['SageCode']) && $Whose['SageCode']) return $Whose['SageCode'];
  // New code needed  
  $Nam = $Whose['SN'];
  $Nam = preg_replace('/The /i','',$Nam);
  $Nam = preg_replace('/ and /i','',$Nam);
  $Nam = preg_replace('/ /','',$Nam);
  $Nam = preg_replace('/\W/','',$Nam);
  $Nam = strtoupper($Nam);
  foreach ($Reserved_Codes as $CC) if (preg_match("/^$CC/",$Nam)) { $Nam = "X$Nam"; break; }
  
  for ($len = 5; $len<10; $len++) {
    $cod = strtoupper(substr($Nam,0,$len));
    $res = $db->query("SELECT * FROM Trade WHERE SageCode='$cod'");
    if ($res->num_rows) continue;
    $Whose['SageCode'] = $cod;
    Put_Trader($Whose);
    return $Whose['SageCode'];
  }
  
  for ($i = 0; $i<100; $i++) {
    $cod = strtoupper(substr($Nam,0,5)) . rand(1,100);
    $res = $db->query("SELECT * FROM Trade WHERE SageCode='$cod'");
    if ($res->num_rows) continue;
    $Whose['SageCode'] = $cod;
    Put_Trader($Whose);
    return $Whose['SageCode'];
  }
  
  $cod = "X" . rand(1,100000);
  $Whose['SageCode'] = $cod;
  Put_Trader($Whose);
  return $Whose['SageCode'];
}  
  
// Create an invoice for whose, details covers the amount(s) note details can be negative
// Source 1=Trade, 2 = Sponsor/Adverts, 3= Other) returns incoice number - for WMFF I will start invoices at 2000
// Due date < 365 = days, > 365 taken as actual date
function New_Invoice($Whose,$Details,$Reason='',$InvCode=0,$Source=1,$DueDate=-1) {
  global $db,$YEAR,$NewInvoiceId,$NewInv;
  if ($DueDate < 0) $DueDate=Feature('PaymentTerms',30);
  $inv['Source'] = $Source;
  $inv['Year'] = $YEAR;
  $inv['BZ'] = $Whose['SN'];
  $inv['Contact'] = $Whose['Contact'];
  $inv['Email'] = $Whose['Email'];
  $inv['Phone'] = $Whose['Phone'];
  $inv['Mobile'] = $Whose['Mobile'];
  $inv['Address'] = $Whose['Address'];
  $inv['PostCode'] = $Whose['PostCode'];
  $inv['IssueDate'] = time();
  if ($Source == 1) $inv['EmailDate'] = $inv['IssueDate'];
  $inv['DueDate'] = ($DueDate < 365? time() + $DueDate*24*60*60 : $DueDate);
  $inv['OurRef'] = Sage_Code($Whose);
  $inv['InvoiceCode'] = $InvCode;
  $inv['Reason'] = $Reason;
  $inv['History'] = '';
  $inv['YourRef'] = '';
  if (isset($Whose['Tid'])) {
    $inv['SourceId'] = $Whose['Tid'];
  } else if (isset($Whose['id'])) {
    $inv['SourceId'] = $Whose['id'];
  }
  for ($i = 1; $i<4;$i++) { $inv["Desc$i"] = ''; $inv["Amount$i"] = 0; }; 
  if (isset($Details[0])) {
    if (is_array($Details[0])) { // If set multi details
      $D = $Details[0];
      $inv['Desc1']   = $D[0];
      $inv['Total'] = $inv['Amount1'] = $D[1];

      if (isset($Details[1])) {   
        $D = $Details[1];
        $inv['Desc2']   = $D[0];
        $inv['Total'] += $inv['Amount2'] = $D[1];
      }
    
      if (isset($Details[2])) {   
        $D = $Details[2];
        $inv['Desc3']   = $D[0];
        $inv['Total'] += $inv['Amount3'] = $D[1];
      }
    } else {
      $inv['Desc1'] = $Details[0];
      $inv['Total'] = $inv['Amount1'] = $Details[1];
    }
  } else {
    $inv['Total'] = 0;
  }
  
  $NewInvoiceId = Insert_db("Invoices",$inv,$NewInv);
  
  return Invoice_Print($inv);
}

/*
  Show Outstanding Invoices
  Paid button on list
  Show All Invoices
  
*/


function Create_Invoice($Dat=0) { // form to fill in - not for trade
  global $Invoice_Sources,$Org_Cats;
  
  $hide1 = $hide2 = 'hidden';
  if ($Dat == 0) {
    $inv = [];
    if (isset($_REQUEST['Tid'])) {
      $tid = $inv['SourceId'] = $inv['Tid'] = $inv['Oid'] = $_REQUEST['Tid'];
      $Trad = Get_Trader($tid);
      if ($Trad['IsTrader']) {
        $inv['OrgType']= 0;
        $hide1 = '';
      } else {
        $inv['OrgType']= 1;
        $hide2 = '';
      }
    }
  } else {
    $inv = Get_Invoice($Dat);
    if ($inv['OrgType'] == 0) {
      $hide1 = '';
    } else {
      $hide2 = '';
    }   
  }
  
  $Traders = Get_All_Traders();
  $Orgs = Get_All_Businesses();
  $Budgets = Budget_List();
  $InvCodes = Get_InvoiceCodes(0);

  echo "This page is ONLY to be used to create invoices for things other than Trade.<p>";
  echo "<form method=post action=InvoiceManage.php>";
  if ($Dat) echo fm_hidden('i',$dat);
  echo "<table border>";
  echo "<tr>" . fm_radio("Organisation",$Org_Cats,$inv,'OrgType','onchange=InvoiceCatChange(event,###V)');
  echo "<td class=InvOrg1 $hide1 >" . fm_select($Traders,$inv,'Tid') . "<td class=InvOrg1  $hide1>If the trader, is not in list, then <a href=Trade.php><b>Create them</b></a> first"; 
  echo "<td class=InvOrg2 $hide2 >" . fm_select($Orgs,$inv,'Oid') . "<td class=InvOrg2  $hide2 >If the organisation, is not in list, then <a href=Trade.php?ORGS><b>Create them</b></a> first";

  echo "<tr><td colspan=5>Include UPTO 3 items, if the first is positive, and the others negative, the negative ones will be in red";
  echo "<tr><td colspan=2>Description<td>Amount";
  for ($i=1;$i<=3;$i++) {
    echo "<tr><td colspan=2>" . fm_basictextarea($inv,"Desc$i",3,1) . fm_text1("",$inv,"Amount$i") ;
  }
  
  echo "<tr><td>Invoice Code:" . fm_select($InvCodes,$inv,'InvoiceCode');  
  if (!isset($inv['DueDays'])) $inv['DueDays'] = Feature('PaymentTerms',30);
  echo fm_text('Payment Term (days)',$inv,'DueDays');
  echo "<tr>" . fm_text("Reason (this appears in local lists)",$inv,'Reason',2);
  if (Access('SysAdmin')) {
    if (!isset($inv['Source'])) $inv['Source'] = 2;  // Other finance
    echo "<tr><td>Source:" . fm_select($Invoice_Sources,$inv,'Source') . fm_text("Source Id",$inv,'SourceId');
  } else {
    fm_hidden('Source',2); 
  }
  echo "</table><p><input type=submit name=ACTION value=" . ($Dat? "UPDATE":"CREATE") . "></form>\n";
}


// Add Details (see above to invoice, up issue and save the revised pdf, return error message if any
function Update_Invoice($id,$Details,$AddReplace=0) { // AR=1 to replace data
  global $USER;
  $inv = Get_Invoice($id);
  
  $LastUsed = 0;
  if ($AddReplace==0) {
    for ($i = 1; $i<4;$i++) if ($inv["Amount$i"] || $inv["Desc$i"]) $LastUsed = $i;
  }

  foreach ((is_array($Details[0])?$Details: [$Details]) as $D) {
    if ($LastUsed++ >=3) return "No Space left to expand invoice";
    $inv["Desc$LastUsed"]   = $D[0];
    $inv["Amount$LastUsed"] = $D[1];
  }

// Redo total
  $total = 0;
  for ($i = 1; $i<4;$i++) $total += $inv["Amount$i"];
  $inv['Total'] = $total;

// Is it paid if so mark paid
  if ($inv['PaidTotal'] >= $inv['Total']) {
    $inv['PayDate'] = time();
    $inv['History'] .= "Fully Paid on " . date('j/n/Y') . " by " . $USER['Login'] . "\n";
    if ($inv['PaidTotal'] > $inv['Total']) {
      Send_SysAdmin_Email("Problem with Invoice " . $inv['id'] . " Paid Total > invoice Total",$inv);
    }
  }
// save and print revision

  $inv['Revision'] ++;
  $inv['IssueDate'] = time();
  Put_Invoice($inv);
  Invoice_Print($inv);
  return "";
}

function Validate_Invoice(&$inv) {
  if (!isset($inv['Email']) || !filter_var($inv['Email'], FILTER_VALIDATE_EMAIL)) return "Invalid Email Address";
  if ($inv['Desc1'] == '' && $inv['Desc2'] == '' && $inv['Desc3'] == '') return "Nothing to Invoice For";
  if ($inv['Amount1'] == 0 && $inv['Amount2'] == 0 && $inv['Amount3'] == 0) return "Nothing to Invoice For";
}

function Show_Invoice($id,$ViewOnly=0) { // Show details, limited edit
  global $Invoice_Sources,$Org_Cats,$YEAR;
  $inv = Get_Invoice($id);
  $InvCodes = Get_InvoiceCodes(0);
  $Rev = $inv['Revision'];
  $InValid =  Validate_Invoice($inv);

  if ($ViewOnly) fm_addall('disabled readonly');
  $RO = (Access('SysAdmin')?'': ' READONLY ');
  echo "<h2>Details of " . ($inv['Total'] < 0 ? "Credit Note ": "Invoice ") . $id . ($Rev?" Revision $Rev":"") . "</h2>\n";
  if ($InValid) echo "<span class=Err>$InValid</span>\n";
  echo "<form method=post action=InvoiceManage.php>";  
  echo "<table border>";
  echo fm_hidden('i',$id);
// Who
  echo "<tr>" . fm_text("Organisation",$inv,'BZ',1,$RO) . fm_text('Revision',$inv,'Revision',1,$RO);
  echo "<tr>" . fm_text("Address",$inv,'Address',4,$RO) . fm_text('Post Code',$inv,'PostCode',1,$RO);
  echo "<tr>" . fm_text("Contact",$inv,'Contact',2,$RO) . fm_text('Phone',$inv,'Phone',1,$RO) . fm_text('Mobile',$inv,'Mobile',1,$RO); 
  
// What
  echo "<tr>" . fm_text("Reason",$inv,'Reason',2,$RO) . fm_text('Email',$inv,'Email',1,$RO);
  echo "<tr><td>Invoice Code:<td>" . fm_select($InvCodes,$inv,'InvoiceCode',1,$RO);
  echo fm_text('Sage Code',$inv,'OurRef',1,$RO);
  echo "<tr><td colspan=3>What<td>Amount";
  for ($i=1;$i<4;$i++) {
    echo "<tr><td colspan=3>" . fm_basictextarea($inv,"Desc$i",4,1,$RO) . "<td>" . Print_Pence($inv["Amount$i"]);
  }
  echo "<tr><td colspan=3>Total<td>" . Print_Pence($inv['Total']);
  echo "<tr><td>Issued on:<td>" . date('d/m/y H:i:s',$inv['IssueDate']);
  if ($inv['EmailDate']) {
    echo "<td>Email Sent:<td>" . date('d/m/y H:i:s',$inv['EmailDate']);
  } else {
    echo "<td><b>Email Not Sent</b>";
  }
  echo fm_date('Due Date',$inv,'DueDate');       
// Status
  if ($inv['PayDate'] < 0) { 
     echo "<tr><td>Credited: <td>" . Print_Pence($inv['Total']);
     echo "<td>On " . date('j/n/Y',-$inv['PayDate']);
     echo  fm_text("CN Reason",$inv,'CNReason',2,$RO);
  } elseif ($inv['PaidTotal'] || $inv['PayDate']) {
     echo "<tr><td>Paid Total: <td>" . Print_Pence($inv['PaidTotal']);
     if ($inv['PayDate']) echo "<td>On " . date('j/n/Y',$inv['PayDate']);
  }

// Other - History, Source, SourceId
  echo "<tr><td>Source:<td>" . fm_select($Invoice_Sources,$inv,'Source',0,$RO) . fm_number("SourceId", $inv,'SourceId','',$RO); 
  echo "<tr>" . fm_textarea('History',$inv,'History',5,2,'','maxlength=2000');
  if ($inv['Source'] != 1) echo "<tr>" . fm_textarea('Cover Note',$inv,'CoverNote',5,4);
  echo "</table>";
  echo "<input type=submit name=ACTION value=UPDATE>";
  if ($inv['Email'] && !$InValid) {
    echo "<input type=submit name=ACTION value=" . ($inv['EmailDate']?"RESEND":"SEND") . ">";
    if (!$inv['EmailDate']) {
      echo "<input type=submit name=ACTION value=BESPOKE>";// formtarget=_blank formaction=SendFinanceProfEmail.php?id=$id>";
      echo "<input type=submit name=ACTION value=SENT>";
    }
  } else {
    echo "<input type=submit name=ACTION value=SENT>"; 
  }

  echo "<input type=submit name=ACTION value=DOWNLOAD formaction='ShowFile.php?D=" . Get_Invoice_Pdf($id,'',$inv['Revision']) . "'>";

  if ( Access('SysAdmin')) echo "<input type=submit name=ACTION value=PRINTPDF>";
  echo "</form><p>";
  echo "Click UPDATE to save changes, SEND to send with standard cover note, BESPOKE to have a bespoke cover note, RESEND to re-email the invoice and cover note, " .
       "SENT to record it has been sent by other means, DOWNLOAD to download the invoice to store/send by other means.<p>";
  
  echo "<h2><a href=InvoiceManage.php?Y=$YEAR>Back to Invoices</a> ";
  if ($inv['Source'] == 1) echo ", <a href=Trade.php?id=" . $inv['SourceId'] . "&Y=$YEAR>Back to Trader</a>";
  echo "</h2>";
  
  // TODO Link to trader info, show email and phone(s)
}

function Invoice_Credit_Note(&$inv, $Reason='Credit Note') { // issue credit note for invoice
  $inv['PayDate'] = -time();

//  $Ninv['Desc1'] = "To negate our invoice " . $Ninv['OurRef'] . '/' . $Oinv['id'] ." issued on " . date('j/n/Y',$Oinv['IssueDate']);
  $inv['CNReason'] = $Reason;
  
  Put_Invoice($inv);  // Mark invoice as credit noted
  return Invoice_Print($inv);
}

function Get_InvoiceCodes($type=0) {  // 0 simple list, 1 full data
  global $YEAR,$db;  
  $full = [];
  $res = $db->query("SELECT * FROM InvoiceCodes ORDER BY Code ");
  if ($res) while ($inv = $res->fetch_assoc()) $full[$inv['Code']] = ($type?$inv:($inv['Code'] . " " . $inv['SN']));
  return $full;  
}

function Get_InvoiceCode($id) {
  global $YEAR,$db;  
  $res = $db->query("SELECT * FROM InvoiceCodes WHERE id=$id");
  if ($res) while ($inv = $res->fetch_assoc()) return $inv;
  return 0;  

}

function Put_InvoiceCode(&$now) {
  $e=$now['id'];
  $Cur = Get_InvoiceCode($e);
  return Update_db('InvoiceCodes',$Cur,$now);
}

function Invoice_AssignCode($Code,$Val,$Src=0,$SrcId=0,$Name='',$Reason='') {
  global $db,$PLANYEAR;
  $ent = ['Year'=>$PLANYEAR,'Code'=>$Code,'Amount'=>$Val,'Source'=>$Src,'SourceId'=>$SrcId,'State'=>0,'IssueDate'=>time(),'SN'=>$Name, 'Reason'=>$Reason];
  Insert_db('OtherPayments', $ent);
}

function Invoice_RemoveCode($Code) {
  db_update('OtherPayments',"State=2","Code='$Code'");
}

function Call_Invoice_User($user,$uid=0,$action,$val=0) {
  global $Invoice_Sources;
  
// ['Other','Trade','Sponsor/Adverts','Buskers Bash','Live and Loud'];
  switch ($user) {
  case 0: // Other
    return; // TODO
    
  case 1: // Trade
    return Trade_F_Action($uid,$action,$val);

  case 2: // Sponsorship / adverts
    return; // TODO
    
  case 3: // BB
    preg_match('/(\d+)/',$uid,$data);
    $id = $data[1];
    return BB_Action($action,$id,$val);
    
  case 4: // LNL
    preg_match('/(\d+)/',$uid,$data);
    $id = $data[1];
    return LNL_Action($action,$id,$val);
  }
}

function Invoice_Email_Details($key,&$inv,$att=0) {
  switch ($key) {
  case 'WHO':  return $inv['Contact']? firstword($inv['Contact']) : $inv['BZ'];
  case 'DETAILS': 
    $det = "";
    $and = 0;
    for ($i=3;$i>0;$i--) 
      if ($inv["Desc$i"]) {
        $dtxt = $inv["Desc$i"];
        if ($damt = $inv["Amount$i"]) {
          if ($damt > 0) { $dtxt .= " at " . Print_Pence($damt); }
          elseif ($damt < 0) { $dtxt = "Less " . Print_Pence(abs($damt)) . " for $dtxt"; };
        }
        if ($det) { 
          if ($and) { 
            $det = "$dtxt, $det";
          } else {
            $det = $dtxt . " and " . $det;
            $and = 1;
          }
        } else $det = $dtxt;
      }
    return $det; 
    
  }
}

function Invoice_Cover_Note(&$inv) { // Returns Default cover note for invoice
  $Mess = Get_Email_Proforma($mescat);
  Parse_Proforma($Mess,'Invoice_Email_Details',$inv);
  return $Mess;
}

function Set_Invoice_Help() {
  static $t = array(
        'BZ'=>'Name of Business or Organisation',
        'Contact'=>'Name of the person to get the invoice',
        'Reason'=>'Appears in lists of invoices for festival use - not sent out',
        'CoverNote'=>'Click on Bespoke to edit this',
//        'InvoiceCode'=>'',
        
  );
  Set_Help_Table($t);
}

function Bespoke_Inv_CoverNote($id,&$inv) {
  global $MASTER_DATA,$PLANYEAR;
  dostaffhead("Cover Note for" . $inv['BZ']);
   
  $subject = $MASTER_DATA['FestName'] . " $PLANYEAR and " . $inv['BZ'];
  $inv['CoverNote'] = $Mess = (isset($_POST['Message'])?$_POST['Message']:$inv['CoverNote']);

  if (isset($_POST['SEND'])) {
    $too = [['to',$inv['Email'],$inv['Contact']],['from','treasurer@' . $MASTER_DATA['HostURL'],'Wimborne Treasurer'],['replyto','treasurer@' . $MASTER_DATA['HostURL'],'Wimborne Treasurer']];
    $pdf = Get_Invoice_Pdf($id,'',$inv['Revision']);
    echo Email_Proforma($too,$Mess,$subject,'Invoice_Email_Details',$inv,$logfile='Invoices',$pdf);
  
    $inv['EmailDate'] = time();
    Put_Invoice($inv);
    return;
  }

  echo "<h2>Email for " . $inv['BZ'] . " - " . $inv['Contact'] . "</h2>";
  if (isset($_POST['PREVIEW'])) {
    echo "<p><h3>Preview...</h2>";
    $MessP = $Mess;
    Parse_Proforma($MessP,$helper='Invoice_Email_Details',$inv);
    echo "<div style='background:white;border:2;border-color:blue;padding:20;margin:20;width:90%;height:30%;overflow:scroll' >$MessP</div>";
  }
  echo "<h3>Edit the message below, then click Preview or Send</h3>";
  echo "Put &lt;p&gt; for paras, &lt;br&gt; for line break, &lt;b&gt;<b>Bold</b>&lt;/b&gt;, &amp;amp; for &amp;, &amp;pound; for &pound; <p> ";

  echo "<form method=post>" . fm_hidden('i',$id) . fm_hidden('ACTION','BESPOKE');// . fm_hidden('L',$label);
  echo "<div style='width:90%;height:30%'><textarea name=Message id=OrigMsg style='background:white;border:2;border-color:blue;padding:20;margin:20'> " .
          htmlspec($Mess) . "</textarea></div><p><br><p>\n";

  echo " <input type=submit name=PREVIEW value=Preview> <input type=submit name=SEND value=Send><p>\n";

  echo "</form><p>";

  dotail();
}

function Pay_Show($id) {
  global $OpayStates,$Invoice_Sources;
  $pay = Get_PayCode($id);
  dostaffhead("Payment for other things");
  echo "<h2>Payment for Other things</h2>";
  echo "Currently just Live N Loud<p>";
  
  echo "<form method=post><table border>";
  echo "<tr><td>Id:<td>$id" . fm_text("Code",$pay,"Code") . fm_hidden('PAYCODES',1) .fm_text('Name',$pay,'SN') .fm_hidden('id',$id);
  echo "<tr>" . fm_number("Amount (pence)",$pay,"Amount") . "<td>issued on:<td>" . date('j/n/y',$pay['IssueDate']);
  echo "<tr><td>State:<td>" . fm_select($OpayStates,$pay,'State') . "<td>Source:" . fm_select($Invoice_Sources,$pay,'Source',0);
  echo "<tr>" . fm_text('Reason',$pay,'Reason');
  echo "<tr>" . fm_textarea("Notes", $pay,'Notes',5,1);
  echo "</table>";
  echo "<input type=submit name=ACTION value=UPDATE>";
  dotail();
}

function Pay_Update($id) {
  $pay = Get_PayCode($id);
  $Dateflds = ['IssueDate','PayDate'];
  Parse_DateInputs($Dateflds);
  Update_db_post('OtherPayments',$pay);
}

?>
