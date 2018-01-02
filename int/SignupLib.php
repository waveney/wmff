<?php

$lnlclasses = array('','Live and Louder (under 16s)','Live and Loud (17-44)','Still Live and Loud (45+)');
$Colours = array('white','lime','orange','grey');
$yesno = array('','Yes','No');
$States = array('Submitted','Paid','Cancelled');

function Get_lnl_Details(&$lnl) {
  global $lnlclasses,$yesno;
  $Body = "\nCompetition: " . $lnlclasses[$lnl['Activity']] . "\n";
  $Body .= "\nBand: " . $lnl['Name'] . "\n";
  $Body .= "Style: " . $lnl['Style'] . "\n\n";
  $Body .= "Contact: " . $lnl['Contact'] . "\n";
  if ($lnl['Phone']) $Body .= "Phone: " . $lnl['Phone'] . "\n";
  $Body .= "Email: <a href=mailto:" . $lnl['Email'] . ">" . $lnl['Email'] . "</a>\n";
  $Body .= "Address: " . $lnl['Address'] . "\n";
  $Body .= "PostCode: " . $lnl['PostCode'] . "\n\n";
  $Body .= "\n\n";

  $Body .= "Members:\n";
  for ($i=1;$i<7;$i++) if ($lnl["Name$i"]) $Body .= "$i: " . $lnl["Name$i"] . " - " . $lnl["Instr$i"] . "\n";
  if ($lnl['TotalSize']) $Body .= "Total Size: " . $lnl['TotalSize'] . "\n";

  $Body .= "\nSongs: " . $lnl['Songs'] . "\n";
  $Body .= "Equipment: " . $lnl['Equipment'] . "\n";
  $Body .= "Days: " . $Trade_Days[$Trady['Days']] . "\n\n";
  $Body .= "Can Play for 30 mins? " . $yesno[$lnl['FolkFest']] . "\n";
  $Body .= "Available on Friday? " . $yesno[$lnl['FFFri']] . "\n";
  $Body .= "Available on Saturday? " . $yesno[$lnl['FFSat']] . "\n";
  $Body .= "Available on Sunday? " . $yesno[$lnl['FFSun']] . "\n";
  return $Body;
}

function Email_Signup(&$lnl,$messcat,$whoto) {
  global $THISYEAR,$USER,$MASTER;
  include_once("int/TradeLib.php");

  $Prof = Get_Email_Proforma($messcat);
  $Mess = ($Prof? $Prof['Body'] : "Unknown message $messcat");

  $Contact = $lnl['Contact']? firstword($lnl['Contact']) : $lnl['Name'];

  $Details = Get_lnl_Details($lnl);
  $Dates = ($MASTER['DateFri']+1) . "," . ($MASTER['DateFri']+2) ."th June $THISYEAR";
  
  $Mess = preg_replace('/\*WHO\*/',$Contact,$Mess);
  $Mess = preg_replace('/\*LINK\*/',$Link,$Mess);
  $Mess = preg_replace('/\*WMFFLINK\*/',$WmffLink,$Mess);

  $Mess = preg_replace('/\*THISYEAR\*/',$THISYEAR,$Mess);
  $Mess = preg_replace('/\*DATES\*/',$Dates,$Mess);
  $Mess = preg_replace('/\*DETAILS\*/',$Details,$Mess);

  if (file_exists("testing")) {
    SendEmail("Richard@wavwebs.com","Live and Loud $THISYEAR and " . $lnl['Name'],$Mess);
  } else {
    SendEmail($whoto,"Live and Loud $THISYEAR and " . $lnl['Name'],$Mess);
  }

  $logf = fopen("LogFiles/LiveNLoudLog.txt","a");
  fwrite($logf,"\n\nEmail to : " . $whoto . "\n\n" . $Mess);
  fclose($logf);
}

?>
