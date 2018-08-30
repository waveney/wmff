<?php

$Invoice_Sources = ['','Trade','Sponsor/Adverts','Other'];
$Org_Cats = ['Trade','Sponsor/Adverts'];

// Get all invoices for YEAR that meet cond
function Get_Invoices($cond = '') {
  global $YEAR,$db;  
  $full = [];
  $res = $db->query("SELECT * FROM Invoices WHERE Year=$YEAR " . ($cond ? " AND $cond " : "" ) . " ORDER BY id ");
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

// Print the Invoice pdf, returns 0 if it cant
function Invoice_Print(&$inv) {
  global $MASTER_DATA,$PLANYEAR;
  
  // File for printings dddDDD is Invoices/ddd/dddDDD.pdf
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
  $pdf->SetLineWidth(1);

  $pdf->Rect($padx,$pady+6*$ch,32*$cw,7*$ch); // Who box
  $pdf->Text($padx+$cw,$pady+7*$ch,$inv['BName']);
  $ln = 1;
  foreach (explode(',', $inv['Address'] . "," . $inv['PostCode']) as $bit) $pdf->Text($padx+2*$cw,$pady+(7+$ln++)*$ch,trim($bit));
  
//  $pdf->SetXY(3*$cw,9*$ch);
//  $pdf->Cell(21*$cw,7*$ch,$whotxt);

  $pdf->Rect($padx+36*$cw,$pady+6*$ch,27*$cw,7*$ch); // Ref Box
  $pdf->Text($padx+37*$cw,$pady+8*$ch,"Invoice No:");
  $pdf->Text($padx+48*$cw,$pady+8*$ch,$inv['id']);
  
  $pdf->Text($padx+37*$cw,$pady+9*$ch,"Date:");
  $pdf->Text($padx+48*$cw,$pady+9*$ch,date('J F Y',$inv['IssueDate']));
  
  $pdf->Text($padx+37*$cw,$pady+10*$ch,"Your Ref:");
  $pdf->Text($padx+48*$cw,$pady+10*$ch,$inv['CName']);
  
  $pdf->Text($padx+37*$cw,$pady+11*$ch,"Our Ref:");
  $pdf->Text($padx+48*$cw,$pady+11*$ch,$inv['OurRef'] . '/' . $inv['id']);
  
  $pdf->SetFont('Arial','B',24);  // Main Header
  $pdf->Text($padx+28*$cw,$pady+15*$ch,($inv['Total']>0?"Invoice":"Credit Note")); 
  $pdf->SetFont('Arial','',$fs);  
  
  $pdf->Rect($padx,$pady+16*$ch,$padx+60*$cw,$pady+17*$ch); // Main invoice BOx
  $pdf->SetLineWidth(0.5);  
  $pdf->Line($padx,$pady+18*$ch,$padx+63*$cw,$pady+18*$ch);
  $pdf->SetFont('Arial','B',$fs);
  $pdf->Text($padx+16*$cw,$pady+17.5*$ch,"Description");
  $pdf->Text($padx+46*$cw,$pady+17.5*$ch,"VAT");
  $pdf->Text($padx+55*$cw,$pady+17.5*$ch,"AMOUNT");

  $pdf->SetFont('Arial','BU',14);  
  $pdf->Text($padx+2*$cw,$pady+19*$ch,"RE: " . $MASTER_DATA['FestName'] . " " . $PLANYEAR);
  $pdf->SetFont('Arial','',$fs);

  if ($inv['Desc1']) $pdf->Text($padx+$cw,$pady+ 21*$ch,$inv['Desc1']);
  if ($inv['Amount1']) $pdf->Text($padx+46*$cw,$pady+21*$ch, utf8_decode("£0.00"));
  if ($inv['Amount1']) $pdf->Text($padx+56*$cw,$pady+21*$ch,utf8_decode("£") . sprintf("%0.2f",$inv['Amount1']/100 ));
  
  if ($inv['Amount1']>0 && ($inv['Amount2'])<0) { $pdf->SetTextColor(255,0,0); } else $pdf->SetTextColor(0,0,0);
  if ($inv['Desc2']) $pdf->Text($padx+$cw,$pady+ 23*$ch,$inv['Desc2']);
  if ($inv['Amount2']) $pdf->Text($padx+46*$cw,$pady+23*$ch,utf8_decode("£0.00"));
  if ($inv['Amount2']) $pdf->Text($padx+56*$cw,$pady+23*$ch,utf8_decode("£") . sprintf("%0.2f",$inv['Amount2']/100 ));
  
  if ($inv['Amount1']>0 && ($inv['Amount3'])<0) { $pdf->SetTextColor(255,0,0); } else $pdf->SetTextColor(0,0,0);
  if ($inv['Desc3']) $pdf->Text($padx+$cw, $pady+25*$ch,$inv['Desc3']);
  if ($inv['Amount3']) $pdf->Text($padx+46*$cw,$pady+25*$ch,utf8_decode("£0.00"));
  if ($inv['Amount3']) $pdf->Text($padx+56*$cw,$pady+25*$ch,utf8_decode("£") . sprintf("%0.2f",$inv['Amount3']/100 ));
  $pdf->SetTextColor(0,0,0);
  
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
 
  // Totals
  
  $pdf->SetLineWidth(1); 
  $pdf->Rect($padx+54*$cw,$pady+35*$ch,9.5*$cw,6*$ch);
  $pdf->SetLineWidth(.5);
  $pdf->Rect($padx+44*$cw,$pady+37*$ch,19.5*$cw,2*$ch);
  $pdf->Text($padx+36*$cw,$pady+36.5*$ch,"Net");
  $pdf->Text($padx+56*$cw,$pady+36.5*$ch,utf8_decode("£") . sprintf("%0.2f",$inv['Total']/100 ));
  $pdf->Text($padx+36*$cw,$pady+38.5*$ch,"VAT");
  $pdf->Text($padx+48*$cw,$pady+38.5*$ch,'"T0"');
  $pdf->Text($padx+56*$cw,$pady+38.5*$ch,utf8_decode(" £0.00"));
  $pdf->Text($padx+36*$cw,$pady+40.5*$ch,"Gross");
  $pdf->SetFont('Arial','B',$fs);
  $pdf->Text($padx+56*$cw,$pady+40.5*$ch,utf8_decode("£") . sprintf("%0.2f",$inv['Total']/100 ));
  $pdf->SetFont('Arial','',$fs);

  // Payment terms
  
  $pdf->SetFont('Arial','B',$fs+1);
  if ($inv['PayDate']) {
    $pdf->Text($padx+15*$cw,$pady+45*$ch,"PAYMENT TERMS: PAID WITH THANKS " . date('j/n/Y',$inv['PayDate']) );
  } else {
    $pdf->Text($padx+15*$cw,$pady+45*$ch,"PAYMENT TERMS: PAYABLE BY " . date('j/n/Y',$inv['DueDate']) . " PLEASE");
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

  // Temp print
  
  $pdf->Output('F',"Temp/Invoice.pdf");
  
  
  
  echo "<h2>pdf outputed</h2>";
}  

// Returns the Pdf of a previously printed invoice
function Get_Invoice_Pdf($id) {

}

function Sage_Code(&$Whose) { // May only work for trade at the moment
  include_once("TradeLib.php");
  global $db;
  if (isset($Whose['SageCode']) && $Whose['SageCode']) return $Whose['SageCode'];
  // New code needed  
  $Nam = $Whose['SName'];
  $Nam = preg_replace('/The /i','',$Nam);
  $Nam = preg_replace('/ and /i','',$Nam);
  $Nam = preg_replace('/ /','',$Nam);
  $Nam = preg_replace('/\W/','',$Nam);
  
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
function New_Invoice($Whose,$Details,$Reason='',$InvCode=0,$Source=1,$DueDate=30) {
  global $db,$YEAR;
  $inv['Source'] = $Source;
  $inv['Year'] = $YEAR;
  $inv['BName'] = $Whose['SName'];
  $inv['CName'] = $Whose['Contact'];
  $inv['Phone'] = $Whose['Phone'];
  $inv['Mobile'] = $Whose['Mobile'];
  $inv['Address'] = $Whose['Address'];
  $inv['PostCode'] = $Whose['PostCode'];
  $inv['IssueDate'] = time();
  $inv['DueDate'] = ($DueDate < 365? time() + $DueDate*24*60*60 : $DueDate);
  $inv['OurRef'] = Sage_Code($Whose);
  $inv['InvCode'] = $InvCode;
  $inv['Reason'] = $Reason;
  $inv['History'] = '';
  $inv['YourRef'] = '';
  if (isset($Whose['Tid'])) {
    $inv['SourceId'] = $Whose['Tid'];
  } else if (isset($Whose['id'])) {
    $inv['SourceId'] = $Whose['id'];
  }
  
  if (isset($Details[0])) { // If set multi details
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
  
  Insert_db("Invoices",$inv);
  
  // Then print, but not yet
}

/*
  Show Outstanding Invoices
  Paid button on list
  Show All Invoices
  
*/


function Create_Invoice($Dat=0) { // form to fill in - not for trade/sponsers/adverts
  global $Invoice_Sources,$Org_Cats;
  
  if ($Dat == 0) {
    $inv = [];
  } else {
    $inv = Get_Invoice($Dat);
  }
  
  $Traders = Get_All_Traders();
  $Orgs = ["These are not yet in the system"];  // TODO
  $Budgets = Budget_List();
  $InvCodes = Get_InvoiceCodes(0);

  echo "This page is ONLY to be used to create invoices for things other than Trade, Sponsors and adverts.<p>";
  echo "<form method=post action=InvoiceManage.php>";
  if ($Dat) echo fm_hidden('i',$dat);
  echo "<table border>";
  echo "<tr>" . fm_radio("Organisation",$Org_Cats,$inv,'OrgType','onchange=InvoiceCatChange(event,###V)');
  echo "<td class=InvOrg1 hidden >" . fm_select($Traders,$inv,'Tid') . "<td class=InvOrg1 hidden >If the trader, is not in list, then <a href=Trade.php><b>Create them</b></a> first"; 
  echo "<td class=InvOrg2 hidden >" . fm_select($Orgs,$inv,'Oid') . "<td class=InvOrg2 hidden >If the organisation, is not in list, then <a href=Org.php?NEW><b>Create them</b></a> first";

  echo "<tr><td colspan=5>Include UPTO 3 items, if the first is positive, and the others negative, the negative ones will be in red";
  echo "<tr><td colspan=2>Description<td>Amount";
  for ($i=1;$i<=3;$i++) {
    echo "<tr>" . fm_text1("",$inv,"Desc$i",2) . fm_text1("",$inv,"Amount$i") ;
  }
  
  echo "<tr><td>Source:" . fm_select($InvCodes,$inv,'InvoiceCode');  
  echo "<tr>" . fm_text("Reason (this appears in local lists)",$inv,'Reason',2);
  if (Access('SysAdmin')) {
    if (!isset($inv['Source'])) $inv['Source'] = 3;
    echo "<tr><td>Source:" . fm_select($Invoice_Sources,$inv,'Source');
  } else {
    fm_hidden('Source',3);
  }
  echo "</table><p><input type=submit name=ACTION value=" . ($Dat? "UPDATE":"CREATE") . "></form>\n";

}

function Show_Invoice($id) { // Show details, limited edit
  global $Invoice_Sources,$Org_Cats,$YEAR;
  $inv = Get_Invoice($id);
  $InvCodes = Get_InvoiceCodes(0);

  $RO = (Access('SysAdmin')?'': ' READONLY ');
  echo "<h2>Details of " . ($inv['Total'] < 0 ? "Credit Note ": "Invoice ") . $id . "</h2>\n";
  echo "<form method=post action=InvoiceManage.php>";  
  echo "<table border>";
  echo fm_hidden('i',$id);
// Who
  echo "<tr>" . fm_text("Oranisation",$inv,'BName',1,$RO);
  echo "<tr>" . fm_text("Address",$inv,'Address',4,$RO) . fm_text('Post Code',$inv,'PostCode',1,$RO);
  echo "<tr>" . fm_text("Contact",$inv,'CName',2,$RO) . fm_text('Phone',$inv,'Phone',1,$RO) . fm_text('Mobile',$inv,'Mobile',1,$RO); 
  
// What
  echo "<tr>" . fm_text("Reason",$inv,'Reason',2,$RO);
  echo "<td>" . fm_text('',$inv,'Email',1,$RO);
  echo "<tr><td>Invoice Code:<td>" . fm_select($InvCodes,$inv,'InvoiceCode',1,$RO);
  echo "<tr><td colspan=3>What<td>Amount";
  for ($i=1;$i<4;$i++) {
    echo "<tr>" . fm_text1("",$inv,"Desc$i",3,$RO) . "<td>" . Print_Pence($inv["Amount$i"]);
  }
  echo "<tr><td>Total<td colspan=4>" . fm_text1('',$inv,'Total',1,$RO);
  
// Status
  if ($inv['PaidTotal']) {
     echo "<tr><td>Paid Total: <td>" . Print_Pence($inv['PaidTotal']);
     if ($inv['PayDate']) echo "<td>On " . date('j/n/Y',$inv['PayDate']);
  }

// Other - History, Source, SourceId
  echo "<tr><td>" . fm_select($Invoice_Sources,$inv,'Source',1,$RO) . "<td>" . fm_number("SourceId", $inv,'SourceId','',$RO); 
  echo "<tr>" . fm_textarea('History',$inv,'History',5,2,'','maxlength=2000');
  echo "</table>";
  echo "<input type=submit name=ACTION value=UPDATE>";
  if (Access('SysAdmin')) echo "<input type=submit name=ACTION value=PRINTPDF>";
  echo "</form>";
  echo "<h2><a href=InvoiceManage.php?Y=$YEAR>Back to Invoices</h2>";
  
  // TODO Link to trader info, show email and phone(s)
}

function Get_InvoiceCodes($type=0) {  // 0 simple list, 1 full data
  global $YEAR,$db;  
  $full = [];
  $res = $db->query("SELECT * FROM InvoiceCodes ORDER BY Code ");
  if ($res) while ($inv = $res->fetch_assoc()) $full[$inv['Code']] = ($type?$inv:($inv['Code'] . " " . $inv['SName']));
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


?>
