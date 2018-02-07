<?php

$lnlclasses = array('','Live and Louder (under 16s)','Live and Loud (17-44)','Still Alive and Loud (45+)');
$Colours = array('white','lime','orange','grey');
$yesno = array('','Yes','No');
$States = array('Submitted','Paid','Cancelled');
$StewClasses = array('Stewarding'=> '(Info Points, Concerts, Road Closures, Street Collecting etc)',
		'Technical' => '(Stage Crew, Runners, Setup/Packdown etc)',
		'Artistic' => '(Setting up art displays, town decorations etc)', 
		'Media' => '(Photography, Videography etc)');
$Days = array('Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday','Mon'=>'Monday','Tue'=>'Tuesday');
$Relations = array('Husband','Wife','Partner','Son','Daughter','Mother','Father','Brother','Sister','Grandchild','Grandparent','Guardian','Uncle','Aunty',
		'Son/Daughter in law', 'Friend','Other');




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
//  $Mess = preg_replace('/\*LINK\*/',$Link,$Mess);
//  $Mess = preg_replace('/\*WMFFLINK\*/',$WmffLink,$Mess);

  $Mess = preg_replace('/\*THISYEAR\*/',$THISYEAR,$Mess);
  $Mess = preg_replace('/\*DATES\*/',$Dates,$Mess);
  $Mess = preg_replace('/\*DETAILS\*/',$Details,$Mess);

  if (file_exists("testing")) {
    SendEmail("Richard@wavwebs.com","Live and Loud $THISYEAR and " . $lnl['Name'],$Mess);
  } else {
    SendEmail($whoto,"Live and Loud $THISYEAR and " . $lnl['Name'],$Mess);
  }

  $logf = fopen("LogFiles/LiveNLoudLog.txt","a");
  if( $logf) {
    fwrite($logf,"\n\nEmail to : " . $whoto . "\n\n" . $Mess);
    fclose($logf);
  }
}

function Get_Stew_Details(&$stew) {
  global $StewClasses,$Days,$Relations;
  $Body = "\nName: " . $stew['Name'] . "\n";
  $Body .= "Email: <a href=mailto:" . $stew['Email'] . ">" . $stew['Email'] . "</a>\n";
  if ($stew['Phone']) $Body .= "Phone: " . $stew['Phone'] . "\n";
  $Body .= "Address: " . $stew['Address'] . "\n";
  $Body .= "PostCode: " . $stew['PostCode'] . "\n\n";

  $Body .= "Birthday: " . $stew['Birthday'] . "\n";
  $Body .= "\n\n";

  foreach ($StewClasses as $s=>$sl) if ($stew["SC_$s"]) $Body .= "Team: $s\n";

  $Body .= "\nPrefer: " . $stew['Prefer'] . "\n";
  $Body .= "Dislike: " . $stew['Dislike'] . "\n";

  $Body .= "\nDBS: " . ($stew['DBS']?$stew['DBS'] : 'No') . "\n\n";

  foreach ($Days as $d=>$dl) if ($stew["Avail$d"]) $Body .= "Available $dl: " . $stew["Avail$d"] . "\n";

  $Body .= "Emergency Contact Name: " . $stew['ContactName'] . "\n";
  $Body .= "Phone: " . $stew['ContactPhone'] . "\n";
  $Body .= "Relationship: " . $Relations[$stew['Relation']] . "\n";
  return $Body;
}

function Email_Steward(&$stew,$messcat,$whoto) {
  global $THISYEAR,$USER,$MASTER;
  include_once("int/TradeLib.php");

  $Prof = Get_Email_Proforma($messcat);
  $Mess = ($Prof? $Prof['Body'] : "Unknown message $messcat");

  $Contact = firstword($stew['Name']);

  $Details = Get_Stew_Details($stew);
  $Dates = ($MASTER['DateFri']+1) . "," . ($MASTER['DateFri']+2) ."th June $THISYEAR";
  
  $Mess = preg_replace('/\*WHO\*/',$Contact,$Mess);
  $Mess = preg_replace('/\*LINK\*/',$Link,$Mess);
  $Mess = preg_replace('/\*WMFFLINK\*/',$WmffLink,$Mess);

  $Mess = preg_replace('/\*THISYEAR\*/',$THISYEAR,$Mess);
  $Mess = preg_replace('/\*DATES\*/',$Dates,$Mess);
  $Mess = preg_replace('/\*DETAILS\*/',$Details,$Mess);

  if (file_exists("testing")) {
    SendEmail("Richard@wavwebs.com","Volunteer WMFF $THISYEAR and " . $stew['Name'],$Mess);
  } else {
    SendEmail($whoto,"Volunteer WMFF $THISYEAR and " . $stew['Name'],$Mess);
  }

  $logf = fopen("LogFiles/Steward.txt","a");
  fwrite($logf,"\n\nEmail to : " . $whoto . "\n\n" . $Mess);
  fclose($logf);
}

function Get_Steward($id) {
  global $db;
  $res = $db->query("SELECT * FROM Stewards WHERE id=$id");
  if ($res) return $res->fetch_assoc();
}
?>
