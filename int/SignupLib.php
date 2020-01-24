<?php

$lnlclasses = array('','Live and Louder (under 16s)','Live and Loud (17+)');
$ArtClasses = ['Displaying Art','Selling Art','Both'];
$ArtValues = ['','Hobby','Profession'];
$ArtPosition = ['','Inside','Outside'];
$Colours = array('white','lime','orange','grey');
$yesno = array('','Yes','No');
$SignupStates = array('Submitted','Accepted not paid','Paid','Cancelled','Accepted','Declined');
$StatesSignup = array_flip($SignupStates);
$SignupStateColours = ['Yellow','Orange','Lime','lightgrey','lime','Grey'];
$StewClasses = array('Stewarding'=> ['Info Points, Concerts, Road Closures, Street Collecting etc',[0,1,2],'stewards'],
                'Setup' => ['Banners, Bunting, Posters, Stages, Marquees, Venues, Furniture etc',['Before',-1,0,1,2,3],'setup'],
                'Artistic' => ['Setting up art displays, town decorations etc',['Before',-1,0,1,2,3],'Art'],
                'Media' => ['Photography, Videography etc',[0,1,2],'Media']);
$Days = array('Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday','Mon'=>'Monday','Tue'=>'Tuesday');
$Relations = array('','Husband','Wife','Partner','Son','Daughter','Mother','Father','Brother','Sister','Grandchild','Grandparent','Guardian','Uncle','Aunty',
                'Son/Daughter in law', 'Friend','Other');
$SignupActions = [
  'BB' => ['Submitted'=>['Accept','Decline','Cancel'],
           'Accepted' => ['Resend','Cancel'],
           'Declined'=>[],
          ],
  'LNL'=> ['Submitted'=>['Accept','Decline','Cancel'],
           'Accepted not paid' => ['Paid','Resend','Cancel'],
           'Paid'=>['Cancel'],
           'Cancelled'=>[],
           'Declined'=>[],
          ],
  'ART' => ['Submitted'=>['Accept','Decline','Cancel'],
           'Accepted not paid' => ['Paid','Resend','Cancel'],
           'Paid'=>['Cancel'],
           'Cancelled'=>[],
           'Declined'=>[],
          ]
  ];

$SignUpActivities = array_merge($lnlclasses,['Buskers Bash','Laugh Out Loud','Art']);
$BBDepositValue = 0;
$LNLDepositValue = 5;
$ARTfee = 25;

include_once("Email.php");

function Get_Signup($id) {
  global $db;
  $res=$db->query("SELECT * FROM SignUp WHERE id=$id");
  if ($res) return $res->fetch_assoc();
  return 0; 
}

function Put_Signup(&$now) {
  $e=$now['id'];
  $Cur = Get_Signup($e);
  return Update_db('SignUp',$Cur,$now);
}

function SignupActions($name,$state) {
  global $SignupActions,$SignupStates;
  $txt = '';
  if (isset($SignupActions[$name][$SignupStates[$state]])) {
    foreach($SignupActions[$name][$SignupStates[$state]] as $ac) {
      $txt .= "<button type=submit name=ACTION value='$ac'>$ac</button>";
    }
  }
  return $txt;
}

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
  for ($i=1;$i<7;$i++) if (isset($lnl["SN$i"])) $Body .= "$i: " . $lnl["SN$i"] . " - " . $lnl["Instr$i"] . "\n";
  if (isset($lnl['TotalSize']) && $lnl['TotalSize']) $Body .= "Total Size: " . $lnl['TotalSize'] . "\n";

  if (isset($lnl['Songs'])) $Body .= "\nSongs: " . $lnl['Songs'] . "\n";
  $Body .= "Equipment: " . ((isset($lnl['TotalSize']) && $lnl['TotalSize'])?$lnl['Equipment']:"None") . "\n";
  $Body .= "Available for both Audition and Final " . $yesno[$lnl['FolkFest']] . "\n";
//  $Body .= "Available on Friday? " . $yesno[$lnl['FFFri']] . "\n";
  $Body .= "Available on Saturday of Folk Festival? " . $yesno[$lnl['FFSat']] . "\n";
//  $Body .= "Available on Sunday? " . $yesno[$lnl['FFSun']] . "\n";

  if (isset($lnl['Bio'])) $Body .= "Bio:" . $lnl['Bio'] . "\n";
  if (isset($lnl['Social'])) $Body .= "Social:" . $lnl['Bio'] . "\n";
  if (isset($lnl['Example'])) $Body .= "Video: <a href='" . $lnl['Example'] . "'>" .$lnl['Example'] . "</a>\n";
  
  $Body = preg_replace('/\n/',"<br>",$Body);
  return $Body;
}

function Lnl_Details($key,&$lnl) {
  global $LNLDepositValue;
  switch ($key) {
  case 'WHO': return firstword(($lnl['Contact']? $lnl['Contact'] : $lnl['SN']));
  case 'DETAILS': return Get_lnl_Details($lnl);
  case 'LNLREF': return "LNL" . $lnl['id'];
  case 'DEPOSIT': return Print_Pound($LNLDepositValue);
  }
}

function Email_Signup(&$lnl,$messcat,$whoto) {
  global $PLANYEAR,$USER,$FESTSYS;
  Email_Proforma($whoto,$messcat,$FESTSYS['FestName'] . " $PLANYEAR and " . $lnl['SN'],'lnl_Details',$lnl,'LiveNLoudLog.txt');
}

function LNL_Action($action,$id) {
  global $LNLDepositValue,$SignupStates,$StatesSignup;
//var_dump($id);
  $lnl = Get_Signup($id);

  switch ($action) {
  case 'Invite':
  case 'Accept':
    // Raise Invoice id
    // send invite email with BACS info and code
    // InvoiceLib needs list of reserved codes
    if ($LNLDepositValue) Invoice_AssignCode("LNL$id",$LNLDepositValue*100,4,$id,$lnl['SN'],"Live and Loud");
    Email_Signup($lnl,'LNL_Invite',$lnl['Email']);
    $lnl['State'] = $StatesSignup['Accepted not paid'];
    break;
    
  case 'Resend':
    Email_Signup($lnl,'LNL_Invite',$lnl['Email']);
    break;
  
  case 'Paid':
  case 'PPaid':
    $lnl['State'] = $StatesSignup['Paid'];
    break;
      
  case 'Cancel':
    $lnl['State'] = $StatesSignup['Cancelled'];
    if ($LNLDepositValue) Invoice_RemoveCode("LNL$id");
    break;
    
  case 'Decline':
    Email_Signup($lnl,'LNL_Decline',$lnl['Email']);
    $lnl['State'] = $StatesSignup['Declined'];
    break;
 
  } 
  Put_Signup($lnl);
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
  global $PLANYEAR,$USER,$FESTSYS;
  Email_Proforma($whoto,$messcat,$FESTSYS['FestName'] . " $PLANYEAR and " . $lol['SN'],'lol_Details',$lnl,'LaughOutLog.txt');
}

function Get_BB_Details(&$lnl) {
  global $lnlclasses,$yesno;
  $Body = "\nBand: " . $lnl['SN'] . "\n";
  $Body .= "Style: " . $lnl['Style'] . "\n\n";
  $Body .= "Contact: " . $lnl['Contact'] . "\n";
  if ($lnl['Phone']) $Body .= "Phone: " . $lnl['Phone'] . "\n";
  $Body .= "Email: <a href=mailto:" . $lnl['Email'] . ">" . $lnl['Email'] . "</a>\n";
  $Body .= "Address: " . $lnl['Address'] . "\n";
  $Body .= "PostCode: " . $lnl['PostCode'] . "\n\n";
  $Body .= "\n\n";

  $Body .= "Members:\n";
  for ($i=1;$i<7;$i++) if (isset($lnl["SN$i"])) $Body .= "$i: " . $lnl["SN$i"] . " - " . $lnl["Instr$i"] . "\n";
  if ($lnl['TotalSize']) $Body .= "Total Size: " . $lnl['TotalSize'] . "\n";

  $Body .= "Available for Buskers Bash " . $yesno[$lnl['FolkFest']] . "\n";
//  $Body .= "Available on Friday? " . $yesno[$lnl['FFFri']] . "\n";
  $Body .= "Available on Saturday of Folk Festival? " . $yesno[$lnl['FFSat']] . "\n";
  $Body .= "Available on Sunday of Folk Festival? " . $yesno[$lnl['FFSun']] . "\n";

  if (isset($lnl['Bio'])) $Body .= "Bio:" . $lnl['Bio'] . "\n";
  if (isset($lnl['Social'])) $Body .= "Social:" . $lnl['Bio'] . "\n";
  if (isset($lnl['Example'])) $Body .= "Video: <a href='" . $lnl['Example'] . "'>" .$lnl['Example'] . "</a>\n";
  
  $Body = preg_replace('/\n/',"<br>",$Body);
  return $Body;
}

function BB_Details($key,&$bb) {
  global $BBDepositValue;
  switch ($key) {
  case 'WHO': return $bb['Contact']? firstword($bb['Contact']) : $bb['SN'];
  case 'DETAILS': return Get_BB_Details($bb);
  case 'BBREF': return "BB" . $bb['id'];
  case 'DEPOSIT': return Print_Pound($BBDepositValue);
  }
}

function Email_BB_Signup(&$bb,$messcat,$whoto) {
  global $PLANYEAR,$USER,$FESTSYS;
  Email_Proforma($whoto,$messcat,$FESTSYS['FestName'] . " $PLANYEAR and " . $bb['SN'],'BB_Details',$bb,'BuskersBashLog.txt');
}

function BB_Action($action,$id) {
  global $BBDepositValue,$StatesSignup;
  $bb = Get_Signup($id);
  switch ($action) {
  case 'Invite':
  case 'Accept':
    // Raise Invoice id
    // send invite email with BACS info and code
    // InvoiceLib needs list of reserved codes
    $bb['State'] = $StatesSignup['Accepted'];
    if ($BBDepositValue) Invoice_AssignCode("BB$id",$BBDepositValue*100,3,$id,$bb['SN'],"Buskers Bash"); 
    Email_BB_Signup($bb,'BB_Invite',[['to',$bb['Email']],['replyto','BuskersBash@wimbornefolk.co.uk']]);
    break;
    
  case 'Resend':
    Email_BB_Signup($bb,'BB_Invite',$bb['Email']);
    break;
  
  case 'Paid':
  case 'PPaid':
    $bb['State'] = $StatesSignup['Paid'];
    break;
      
  case 'Cancel':
    $bb['State'] = $StatesSignup['Cancelled'];
    if ($BBDepositValue) Invoice_RemoveCode("BB$id");
    break;

    
  case 'Decline':
    Email_Signup($lnl,'BB_Decline',$bb['Email']);
    $bb['State'] = $StatesSignup['Declined'];
    break;
  
  } 
  Put_Signup($bb);
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
  case 'LINK' :return "<a href='https://" . $_SERVER['HTTP_HOST'] . "/int/Access?t=w&id=" . $vol['id'] . "&k=" . $vol['AccessKey'] . "'><b>link</b></a>";
  }
}

function Email_SVolunteer(&$vol,$messcat,$whoto) {
  global $PLANYEAR,$USER,$FESTSYS;
  Email_Proforma($whoto,$messcat,$FESTSYS['FestName'] . " $PLANYEAR and " . $vol['SN'],'Vol_SDetails',$vol,'Volunteer.txt');
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

// ART **************************************************************************************

function Get_Art_Details(&$art) {
  global $lnlclasses,$yesno,$ArtValues,$ArtClasses,$ArtPosition;
  $Body = "Art Application\n\n";

  $Body .= "Name: " . $art['SN'] . "\n";
  $Body .= "Phone: " . $art['Phone'] . "\n";
  $Body .= "Email: <a href=mailto:" . $art['Email'] . ">" . $art['Email'] . "</a>\n";
  $Body .= "Address: " . $art['Address'] . "\n";
  $Body .= "Age: " . $art['Age'] . "\n";
  if ($art['Instr2']) $Body .= "Disability: " . $art['Instr2'] . "\n";
  if (isset($art['Website']) && $art['Website']) $Body .= "Website: " . $art['Website'] . "\n";
  if (isset($art['Social']) && $art['Social']) $Body .= "Social: " . $art['Social'] . "\n";

  if ($art['Instr1']) $Body .= "Clubs/Societies: " . $art['Instr1'] . "\n\n";
  if ($art['Tickbox1']) $Body .= "Selling or Displaying: " . $ArtClasses[$_POST['Tickbox1']] . "\n\n";
  if ($art['Instr3']) $Body .= "Want to achieve: " . $art['Instr3'] . "\n\n";
  if ($art['Tickbox2']) $Body .= "This is a " . $ArtValues[$_POST['Tickbox2']] . "\n\n";
  if ($art['Style']) $Body .= "Genre: " . $art['Style'] . "\n\n";
  if ($art['Tickbox3']) $Body .= "Position: " . $ArtPosition[$_POST['Tickbox3']] . "\n\n"; 
  if (isset($art['Instr4']) && $art['Instr4']) $Body .= "Workshops: " . $art['Instr4'] . "\n\n";  

  $Body .= "\n\n";
  
  $Body = preg_replace('/\n/',"<br>",$Body);
  return $Body;
}

function ART_Details($key,&$art) {
  global $ARTfee;
  $id = $art['id'];
  switch ($key) {
  case 'WHO': return firstword($art['SN']);
  case 'DETAILS': return Get_ART_Details($art);
  case 'ARTREF': return "ART$id";
  case 'DEPOSIT': return Print_Pound($ARTfee);
  case 'LINK': return "<a href='https://" . $_SERVER['HTTP_HOST'] . "/int/Access?t=ART&i=$id&key=" . $art['AccessKey'] . "'  style='background:lightblue;'><b>link</b></a>";
  case 'FESTLINK' : return "<a href='https://" . $_SERVER['HTTP_HOST'] . "/int/ArtForm?id=$id'><b>link</b></a>";
  case 'PAYCODES' : 
    $Pay = Pay_Code_Find(5,$id);
    if ($Pay && $Pay['State']==0) {
      return "<b>Payment due</b><br>For: <b>" . $Pay['Reason'] . "</b><br>Due: " . date('j/n/Y',$Pay['DueDate']) . "<br>Please pay: " . Print_Pence($Pay['Amount']) . " to:<br>" . 
          Feature("FestBankAdr") . "<br>Sort Code: " . Feature("FestBankSortCode") . "<br>Account No: " . Feature("FestBankAccountNum") . "<p>Quote Reference: " .
          $Pay['Code'] . "<p>";
    };
    return "";

  }
}

function ART_Email_Signup(&$art,$messcat,$whoto) {
  global $PLANYEAR,$USER,$FESTSYS;
  Email_Proforma($whoto,$messcat,$FESTSYS['FestName'] . " $PLANYEAR and " . $art['SN'],'ART_Details',$art,'ArtLog.txt');
}

function ART_Action($action,$id,$val=0) {
  global $ARTfee,$SignupStates,$StatesSignup;
//var_dump($id);
  $art = Get_Signup($id);

  switch ($action) {
  case 'Invite':
  case 'Accept':
    // Raise Invoice id
    // send invite email with BACS info and code
    // InvoiceLib needs list of reserved codes
    if ($ARTfee && $art['Tickbox1'] > 0) {
      Invoice_AssignCode("ART$id",$ARTfee*100,5,$id,$art['SN'],"Art");
      $art['State'] = $StatesSignup['Accepted not paid'];
      ART_Email_Signup($art,'ART_Invite_Pay',$art['Email']);
    } else {
      ART_Email_Signup($art,'ART_Invite',$art['Email']);
      $art['State'] = $StatesSignup['Accepted'];
    }
    break;
    
  case 'Resend':
    ART_Email_Signup($art,'ART_Invite',$art['Email']);
    break;
  
  case 'Paid':
  case 'PPaid':
    $art['State'] = $StatesSignup['Paid']; // TODO Add invoice to a concirfamtion email

    $ipdf = New_Invoice($art,
                          [["Pitch for selling Art @ The Folk Festival",$ARTfee*100]],
                           /*$InvCode*/ 9999, 1, -1,0,0,1 ); // TODO change 9999 to something programmed
    ART_Email_Signup($art,'ART_Payment_Invoice',$art['Email'],$ipdf);                           
    break;
      
  case 'Cancel':
    $art['State'] = $StatesSignup['Cancelled'];
    if ($ARTfee) Invoice_RemoveCode("ART$id");
    break;
    
  case 'Decline':
    Email_Signup($art,'ART_Decline',$art['Email']);
    $art['State'] = $StatesSignup['Declined'];
    break;
 
  } 
  Put_Signup($art);
}



?>
