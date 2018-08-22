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
function Invoice_Print($id) {
  
}

// Returns the Pdf of a previously printed invoice
function Get_Invoice_Pdf($id) {

}

// Create an invoice for whose, details covers the amount(s) note details can be negative
// Source 1=Trade, 2 = Sponsor/Adverts, 3= Other) returns incoice number - for WMFF I will start invoices at 2000
function New_Invoice($Whose,$Details,$Reason='',$InvCode=0,$Source=1) {
  global $db,$YEAR;
  $inv['Source'] = $Source;
  $inv['Year'] = $YEAR;
  $inv['BName'] = $Whose['SName'];
  $inv['CName'] = $Whose['Contact'];
  $inv['Phone'] = $Whose['Phone'];
  $inv['Mobile'] = $Whose['Mobile'];
  $inv['Address'] = $Whose['Address'];
  $inv['PostCode'] = $Whose['PostCode'];
  $inv['issueDate'] = time();
  $inv['OurRef'] = $Whose['RefCode'] . "/?";
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
    $inv['Desc1']   = $D['T'];
    $inv['Total'] = $inv['Amount1'] = $D['A'];
    $inv['Budget1'] = (isset($D['B']) ? $D['B'] : 0);

    if (isset($Details[1])) {   
      $D = $Details[1];
      $inv['Desc2']   = $D['T'];
      $inv['Total'] += $inv['Amount2'] = $D['A'];
      $inv['Budget2'] = (isset($D['B']) ? $D['B'] : 0);
    }
    
    if (isset($Details[2])) {   
      $D = $Details[2];
      $inv['Desc3']   = $D['T'];
      $inv['Total'] += $inv['Amount3'] = $D['A'];
      $inv['Budget3'] = (isset($D['B']) ? $D['B'] : 0);
    }
  } else {
    $inv['Desc1'] = $Details['T'];
    $inv['Total'] = $inv['Amount1'] = $Details['A'];
    $inv['Budget1'] = (isset($Details['B']) ? $Details['B'] : 0);
  }
  
  db_insert("Invoices",$inv);
  
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

  echo "This page is ONLY to be used to create invoices for things other than Trade, Sponsors and adverts.<p>";
  echo "<form method=post action=InvoiceManage.php>";
  if ($Dat) echo fm_hidden('i',$dat);
  echo "<table border>";
  echo "<tr>" . fm_radio("Organisation",$Org_Cats,$inv,'OrgType','onchange=InvoiceCatChange(event,###V)');
  echo "<td class=InvOrg1 hidden >" . fm_select($Traders,$inv,'Tid') . "<td class=InvOrg1 hidden >If the trader, is not in list, then <a href=Trade.php><b>Create them</b></a> first"; 
  echo "<td class=InvOrg2 hidden >" . fm_select($Orgs,$inv,'Oid') . "<td class=InvOrg2 hidden >If the organisation, is not in list, then <a href=Org.php?NEW><b>Create them</b></a> first";

  echo "<tr><td colspan=5>Include UPTO 3 items, if the first is positive, and the others negative, the negative ones will be in red";
  echo "<tr><td colspan=2>Description<td>Amount<td>Budget";
  for ($i=1;$i<=3;$i++) {
    echo "<tr>" . fm_text1("",$inv,"Desc$i",2) . fm_text1("",$inv,"Amount$i") . "<td>" . fm_select($Budgets,$inv,"Budget$i");
  }
  
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
  global $Invoice_Sources,$Org_Cats;
  $inv = Get_Invoice($id);
  $Budgets = Budget_List();
  $RO = (Access('SysAdmin')?'': ' READONLY ');
  echo "<h2>Details of " . ($inv['Total'] < 0 ? "Credit Note ": "Invoice ") . $id . "</h2>\n";
  echo "<form method=post action=InvoiceManage.php>";  
  echo "<table border>";
  
// Who
  echo "<tr>" . fm_text("Oranisation",$inv,'BName',1,$RO);
  echo "<tr>" . fm_text("Address",$inv,'Address',4,$RO) . fm_text('Post Code',$inv,'PostCode',1,$RO);
  echo "<tr>" . fm_text("Contact",$inv,'CName',2,$RO) . fm_text('Phone',$inv,'Phone',1,$RO) . fm_text('Mobile',$inv,'Mobile',1,$RO); 
  
// What
  echo "<tr>" . fm_text("Reason",$inv,'Reason',2,$RO);
  echo "<tr><td colspan=4>What<td>Amount<td>Budget";
  for ($i=1;$i<4;$i++) {
    echo "<tr>" . fm_text1("",$inv,"Desc$i",4,$RO) . Print_Pence($inv["Amount$i"]) . "<td>" . fm_select($Budgets,$inv,"Budget$i",1,$RO);
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
}

function Get_InvoiceCodes() {
  global $YEAR,$db;  
  $full = [];
  $res = $db->query("SELECT * FROM InvoiceCodes ORDER BY Code ");
  if ($res) while ($inv = $res->fetch_assoc()) $full[$inv['Code']] = $inv;
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
