<?php

$lnlclasses = array('','Live and Louder (under 16s)','Live and Loud (17-44)','Still Alive and Loud (45+)');
$Colours = array('white','lime','orange','grey');
$yesno = array('','Yes','No');
$States = array('Submitted','Paid','Cancelled');
$StewClasses = array('Stewarding'=> ['Info Points, Concerts, Road Closures, Street Collecting etc',[0,1,2],'stewards'],
                'Setup' => ['Banners, Bunting, Posters, Stages, Marquees, Venues, Furniture etc',['Before',-1,0,1,2,3],'setup'],
                'Artistic' => ['Setting up art displays, town decorations etc',['Before',-1,0,1,2,3],'Art'],
                'Media' => ['Photography, Videography etc',[0,1,2],'Media']);
$Days = array('Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday','Mon'=>'Monday','Tue'=>'Tuesday');
$Relations = array('','Husband','Wife','Partner','Son','Daughter','Mother','Father','Brother','Sister','Grandchild','Grandparent','Guardian','Uncle','Aunty',
                'Son/Daughter in law', 'Friend','Other');

$SignUpActivities = array_merge($lnlclasses,['Buskers Bash','Laugh Out Loud']);

include_once("Email.php");

function Get_lnl_Details(&$lnl) {
  global $lnlclasses,$yesno;
  $Body = "\nCompetition: " . $lnlclasses[$lnl['Activity']] . "\n";
  $Body .= "\nBand: " . $lnl['SN'] . "\n";
  $Body .= "Style: " . $lnl['Style'] . "\n\n";
  $Body .= "Contact: " . $lnl['Contact'] . "\n";
  if ($lnl['Phone']) $Body .= "Phone: " . $lnl['Phone'] . "\n";
  $Body .= "Email: <a href=mailto:" . $lnl['Email'] . ">" . $lnl['Email'] . "</a>\n";
  $Body .= "Address: " . $lnl['Address'] . "\n";
  $Body .= "PostCode: " . $lnl['PostCode'] . "\n\n";
  $Body .= "\n\n";

  $Body .= "Members:\n";
  for ($i=1;$i<7;$i++) if ($lnl["SN$i"]) $Body .= "$i: " . $lnl["SN$i"] . " - " . $lnl["Instr$i"] . "\n";
  if ($lnl['TotalSize']) $Body .= "Total Size: " . $lnl['TotalSize'] . "\n";

  $Body .= "\nSongs: " . $lnl['Songs'] . "\n";
  $Body .= "Equipment: " . $lnl['Equipment'] . "\n";
  $Body .= "Can Play for 30 mins? " . $yesno[$lnl['FolkFest']] . "\n";
  $Body .= "Available on Friday? " . $yesno[$lnl['FFFri']] . "\n";
  $Body .= "Available on Saturday? " . $yesno[$lnl['FFSat']] . "\n";
  $Body .= "Available on Sunday? " . $yesno[$lnl['FFSun']] . "\n";
  return $Body;
}

function Lnl_Details($key,&$lnl) {
  switch ($key) {
  case 'WHO': return firstword(($lnl['Contact']? $lnl['Contact'] : $lnl['SN']));
  case 'DETAILS': return Get_lnl_Details($lnl);
  }
}

function Email_Signup(&$lnl,$messcat,$whoto) {
  global $PLANYEAR,$USER,$MASTER_DATA;
  Email_Proforma($whoto,$messcat,$MASTER_DATA['FestName'] . " $PLANYEAR and " . $lnl['SN'],'lnl_Details',$lnl,'LiveNLoudLog.txt');
}

function Get_lol_Details(&$lol) {
  global $yesno;
  $Body = "Act: " . $lol['SN'] . "\n";
  $Body .= "Contact: " . $lol['Contact'] . "\n";
  if ($bb['Phone']) $Body .= "Phone: " . $lol['Phone'] . "\n";
  $Body .= "Email: <a href=mailto:" . $lol['Email'] . ">" . $lol['Email'] . "</a>\n";
  $Body .= "\n\n";

  $Body .= "Started:" . $lol['Started'] . "\n";
  if (isset($lol['Style'])) $Body .= "Style:" . $lol['Style'] . "\n";
  if (isset($lol['Example'])) $Body .= "Example:" . $lol['Example'] . "\n";
  if (isset($lol['Equipment'])) $Body .= "Equipment:" . $lol['Equipment'] . "\n";
  if (isset($lol['Bio'])) $Body .= "Bio:" . $lol['Bio'] . "\n";

  $Body .= "Available on Tuesday March 6th? " . $yesno[$lol['Avail1']] . "\n";
  $Body .= "Available on Tuesday April 10th? " . $yesno[$lol['Avail2']] . "\n";
  $Body .= "Available on Tuesday May 1st? " . $yesno[$lol['Avail3']] . "\n";

  return $Body;
}

function Lol_Details($key,&$lol) {
  switch ($key) {
  case 'WHO': return firstword(($lol['Contact']? $lol['Contact'] : $lol['SN']));
  case 'DETAILS': return Get_lol_Details($lol);
  }
}

function Email_lol_Signup(&$lol,$messcat,$whoto) {
  global $PLANYEAR,$USER,$MASTER_DATA;
  Email_Proforma($whoto,$messcat,$MASTER_DATA['FestName'] . " $PLANYEAR and " . $lol['SN'],'lol_Details',$lnl,'LaughOutLog.txt');
}

function Get_BB_Details(&$bb) {
  $Body .= "\nBand: " . $bb['SN'] . "\n";
  $Body .= "Contact: " . $bb['Contact'] . "\n";
  if ($bb['Phone']) $Body .= "Phone: " . $bb['Phone'] . "\n";
  $Body .= "Email: <a href=mailto:" . $bb['Email'] . ">" . $bb['Email'] . "</a>\n";
//  $Body .= "Address: " . $bb['Address'] . "\n";
//  $Body .= "PostCode: " . $bb['PostCode'] . "\n\n";
  $Body .= "\n\n";

  $Body .= "Example:" . $bb['Example'];
  return $Body;
}

function BB_Details($key,&$bb) {
  switch ($key) {
  case 'WHO': return $lnl['Contact']? firstword($bb['Contact']) : $bb['SN'];
  case 'DETAILS': return Get_BB_Details($bb);
  }
}

function Email_BB_Signup(&$bb,$messcat,$whoto) {
  global $PLANYEAR,$USER,$MASTER_DATA;
  Email_Proforma($whoto,$messcat,$MASTER_DATA['FestName'] . " $PLANYEAR and " . $bb['SN'],'BB_Details',$bb,'BuskersBashLog.txt');
}

function Get_SVol_Details(&$vol) {
  global $volClasses,$Days,$Relations;
  $Body = "\nName: " . $vol['SN'] . "\n";
  $Body .= "Email: <a href=mailto:" . $vol['Email'] . ">" . $vol['Email'] . "</a>\n";
  if ($vol['Phone']) $Body .= "Phone: " . $vol['Phone'] . "\n";
  $Body .= "Address: " . $vol['Address'] . "\n";
  $Body .= "PostCode: " . $vol['PostCode'] . "\n\n";

  $Body .= "Birthday: " . $vol['Birthday'] . "\n";
  $Body .= "\n\n";

  foreach ($volClasses as $s=>$sl) if ($vol["SC_$s"]) $Body .= "Team: $s\n";

  $Body .= "\nPrefer: " . $vol['Prefer'] . "\n";
  $Body .= "Dislike: " . $vol['Dislike'] . "\n";

  $Body .= "\nDBS: " . ($vol['DBS']?$vol['DBS'] : 'No') . "\n\n";

  foreach ($Days as $d=>$dl) if ($vol["Avail$d"]) $Body .= "Available $dl: " . $vol["Avail$d"] . "\n";

  $Body .= "Emergency Contact Name: " . $vol['ContactName'] . "\n";
  $Body .= "Phone: " . $vol['ContactPhone'] . "\n";
  $Body .= "Relationship: " . $Relations[$vol['Relation']] . "\n";
  return $Body;
}

function Vol_SDetails($key,&$vol) {
  switch ($key) {
  case 'WHO': return firstword($vol['SN']);
  case 'DETAILS': return Get_SVol_Details($stwe);
  case 'LINK' :return "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Access.php?t=w&id=" . $vol['id'] . "&k=" . $vol['AccessKey'] . "><b>link</b></a>";
  }
}

function Email_SVolunteer(&$vol,$messcat,$whoto) {
  global $PLANYEAR,$USER,$MASTER_DATA;
  Email_Proforma($whoto,$messcat,$MASTER_DATA['FestName'] . " $PLANYEAR and " . $vol['SN'],'Vol_SDetails',$vol,'Volunteer.txt');
}

function Get_SVolunteer($id) {
  global $db;
  $res = $db->query("SELECT * FROM Volunteers WHERE id=$id");
  if ($res) return $res->fetch_assoc();
}

function Put_SVolunteer(&$now) {
  $e=$now['id'];
  $Cur = Get_SVolunteer($e);
  return Update_db('Volunteers',$Cur,$now);
}

?>
