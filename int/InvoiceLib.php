<?php

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
function Print_Invoice($id) {

}

// Returns the Pdf of a previously printed invoice
function Get_Invoice_Pdf($id) {

}

// Create an invoice for whose, details covers the amount(s) note details can be negative
// Source 1=Trade, 2 = Sponsor/Adverts, 3= Other) returns incoice number - for WMFF I will start invoices at 2000
function New_Invoice($Whose,$Details,$Source=1) {
  $inv['Source'] = $Source;
  $inv['BName'] = $Whose['SName'];
  $inv['CName'] = $Whose['Contact'];
  $inv['Address'] = $Whose['Address'];
  $inv['PostCode'] = $Whose['PostCode'];
  $inv['issueDate'] = time();
  $inv['OurRef'] = $Whose['RefCode'] . "/?";
  if (isset($Whose['Tid'])) {
    $inv['SourceId'] = $Whose['Tid'];
  } else if (isset($Whose['id'])) {
    $inv['SourceId'] = $Whose['id'];
  }
  
  

}

/*
  Show Outstanding Invoices
  Paid button on list
  Show All Invoices
  





*/

?>
