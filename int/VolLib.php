<?php
  include_once("fest.php");

//  dostaffhead("Steward / Volunteer Application", ["/js/Volunteers.js"]);

  include_once("Email.php");
//  include_once("SignupLib.php");
  global $USER,$USERID,$db,$PLANYEAR,$StewClasses,$Relations;
  
$yesno = array('','Yes','No');
// shortname =>[ longname, description, listofwhen, NotUsed days,emailto, Otherbox, VolExtra, [extrarows]] - extrarows = [field,shorttxt,longtxt]
// This data and that at the top of Volunteers.js must be kept in synch
$volClasses = [ 'Stew' => ['Stewarding', 'Info Points, Concerts, Road Closures, Street Collecting etc',[0,1,2],'stewards',0,0,
                           [['Stew_Prefer','Prefered Stewarding Duties',"Include any activity you would particularly like to be a steward for"],
                            ['Stew_Dislike','Disliked Stewarding Duties',"Include any activity you would particularly NOT like to be a steward for"]]],

                'Setup' => ['Setup/Cleardown', 'Banners, Bunting, Posters, Stages, Marquees, Venues, Furniture etc',['Before',-2,-1,0,1,2,3],'setup',0,1,
                            [['Setup_Like','Prefered Setup Tasks',"Setup/Cleardown tasks you would like to do"],
                             ['Setup_Dislike','Disliked Setup Tasks',"Any Setup/Cleardown tasks you would wish to avoid"]]],

                'Art' => ['Artistic', 'Setting up art displays, town decorations etc',['Before',-1,0,1,2,3],'art',0,1,
                          [['Art_Like', 'Prefered Art Tasks',"What would you like to help decorate"],
                           ['Art_Dislike', 'Disliked Art Tasks',"What would you not like to do"]]],

                'Media' => ['Media', 'Photography, Videography etc',[0,1,2],'media',0,0,
                            [['Media_Like','Prefered Media Areas',"What events would you like to cover"],
                             ['Media_Dislike', 'Disliked Media Areas',"What events would you not want to cover"],
                             ['Examples','Links to examples of your work','Separate examples by spaces']]],

                'Other' => ['Other', '',['Before',-1,0,1,2,3],'webYEARDATA',0,0,
                            [['OtherText','Please elaborate','']]],
               ];
               
$Relations = array('','Husband','Wife','Partner','Son','Daughter','Mother','Father','Brother','Sister','Grandchild','Grandparent','Guardian','Uncle','Aunty',
                'Son/Daughter in law', 'Friend','Other');
 

function Get_Vol_Details(&$vol) {
  global $volClasses,$Relations,$YEARDATA;
  $Body = "\nName: " . $vol['SN'] . "<br>\n";
  $Body .= "Email: <a href=mailto:" . $vol['Email'] . ">" . $vol['Email'] . "</a><br>\n";
  if ($vol['Phone']) $Body .= "Phone: " . $vol['Phone'] . "<br>\n";
  $Body .= "Address: " . $vol['Address'] . "<br>\n";
  if (isset($vol['PostCode'])) $Body .= "PostCode: " . $vol['PostCode'] . "<br>\n\n";

//  $Body .= "Birthday: " . $vol['Birthday'] . "<br>\n";
  $Body .= "\n\n";

  foreach ($volClasses as $s=>$sl) 
    if (isset($vol["SC_$s"]) && $vol["SC_$s"]) {
      $Body .= "<p>Team: " . $sl[0] . "<br>\n";
      
      if (is_array($sl[6]))
        foreach ($sl[6] as $xq) 
          if (isset($vol[$xq[0]]) && $vol[$xq[0]]) $Body .= $xq[1] . ": " . $vol[$xq[0]] . "<br>\n";
      if ($sl[4]) $Body .= "Other tasks:" . $vol['OtherText'] . "<br>\n";
    }
  $Body .= "<p>\n";
  $Body . "Available:<p>\n";
  if (isset($vol['AvailBefore']) && $vol['AvailBefore'])  $Body .= "Before Festival: " . $vol["AvailBefore"] . "<br>\n";
  for ($day = $YEARDATA['FirstDay']-1; $day<= $YEARDATA['LastDay']+1; $day++) {
    $av = "Avail" . ($day <0 ? "_" . (-$day) : $day);
    if (isset($vol[$av]) && $vol[$av]) $Body .= FestDate($day,'M') . ": " . $vol[$av] . "<br>\n";
  }
  
  if (isset($Vol['Notes']) && $Vol['Notes']) $Body .= "<p>Notes: " . $Vol['Notes'] . "<p>\n";

  if (Feature('VolDBS')) {
    $Body .= "<p>DBS: " . ((isset($Vol['VYid']) && $Vol['VYid'])?$vol['DBS'] : 'No') . "<p>\n\n";
  }

  $Body .= "Emergency Contact<br>\nName: " . $vol['ContactName'] . "<br>\n";
  $Body .= "Phone: " . $vol['ContactPhone'] . "<br>\n";
  $Body .= "Relationship: " . $Relations[$vol['Relation']] . "<br>\n";
  return $Body;
}

function Vol_Details($key,&$vol) {
  global $FESTSYS;
  switch ($key) {
  case 'WHO': return firstword($vol['SN']);
  case 'DETAILS': return Get_Vol_Details($vol);
  case 'LINK' : return "<a href='https://" . $_SERVER['HTTP_HOST'] . "/int/Access?t=v&i=" . $vol['id'] . "&k=" . $vol['AccessKey'] . "'><b>link</b></a>";
  case 'FESTLINK' :
  case 'WMFFLINK' : return "<a href='https://" . $_SERVER['HTTP_HOST'] . "/int/Volunteers?A=View&id=" . $vol['id'] . "'><b>link</b></a>";
  }
}

function Email_Volunteer(&$vol,$messcat,$whoto) {
  global $PLANYEAR,$USER,$FESTSYS;
  Email_Proforma(5,$vol['id'],$whoto,$messcat,$FESTSYS['FestName'] . " $PLANYEAR and " . $vol['SN'],'Vol_Details',$vol,'Volunteer.txt');
}

function Get_Volunteer($id) {
  global $db;
  $res = $db->query("SELECT * FROM Volunteers WHERE id=$id");
  if ($res) return $res->fetch_assoc();
}

function Put_Volunteer(&$now) {
  $e=$now['id'];
  $Cur = Get_Volunteer($e);
  return Update_db('Volunteers',$Cur,$now);
}

function Get_Vol_Year($id,$year=0) {  // 
  global $YEAR,$db,$PLANYEAR;
  if (!$year) {
    $res = $db->query("SELECT * FROM VolYear WHERE Volid=$id ORDER BY Year DESC");
    if ($res && $res->num_rows) return $res->fetch_assoc();
    if ($YEAR != $PLANYEAR) return null;
    return ['VYid'=>-1,'Year'=>$year, 'id'=>$id];
  } 
  $res = $db->query("SELECT * FROM VolYear WHERE Volid=$id AND Year='$Year'");
  if ($res && $res->num_rows) return $res->fetch_assoc();
  return null;
}

function Put_Vol_Year(&$now) {
  global $YEAR,$db,$PLANYEAR;
  $yid = $now['VYid'];
  if ($yid > 0) {
    if ($now['Year'] != $PLANYEAR) return;
    $Cur = Get_Vol_Year($now['id'],$now['Year']);
    return Update_db('VolYear',$Cur,$now);
  }
  $now['Volid'] = $now['id'];
  return Insert_db('VolYear',$now);
}

function BeforeTeams() {
  global $volClasses;
  static $txt = '';
  if ($txt) return $txt;
  $teams = [];
  foreach ($volClasses as $c=>$exp) if ( $exp[5]) $teams[] = $exp[0];
  $txt = " Teams: " . implode(", ",$teams);
  return $txt;
}

function VolForm(&$Vol,$Err='') {
  global $volClasses,$YEARDATA,$PLANYEAR,$YEAR,$Relations;
  echo "<h2 class=subtitle>Steward / Volunteer Application Form</h2>\n";
  echo "<p class=Err>$Err<p>";
  echo "<form method=post action=Volunteers>";
  echo "<div class=tablecont><table border style='table-layout:fixed'>\n";
  echo "<tr><td colspan=5><h3><center>Volunteer</center></h3>";
  if (Access('SysAdmin')) echo "<tr><td>id: " . $Vol['id'] . " VYid: " . $Vol['VYid'];
//  echo "<tr><td style='max-width:100;width:100'>Name:" . fm_text1('',$Vol,'SN',2,'');
  echo "<tr>" . fm_text('Name',$Vol,'SN',2,'');
  echo "<tr>" . fm_text('Email',$Vol,'Email',2);
  echo "<tr>" . fm_text('Phone(s)',$Vol,'Phone',2);
  echo "<tr>" . fm_text('Address',$Vol,'Address',4);
  echo "<tr><td>" . fm_checkbox("I am over 18",$Vol,'Over18',"","",1);
//  echo "<tr>" . fm_text('Date of Birth',$Vol,'Birthday');
  if (Feature('VolDBS')) {
    echo "<tr><td colspan=4><h3>Legal</h3>\n";
    echo "Do you have a current DBS certificate? if so please give details<br>" . fm_textinput('DBS',(isset($Vol['DBS'])?$Vol['DBS']:''),'size=100');
  }
  echo "<tr><td colspan=5><h3>Emergency Contact</h3>\n";
  echo "<tr>" . fm_text('Contact Name',$Vol,'ContactName',2);
  echo "<tr>" . fm_text('Contact Phone',$Vol,'ContactPhone',2);
  echo "<tr><td>Relationship:<td>" . fm_select($Relations,$Vol,'Relation');

  echo "<tr><td colspan=5><h3><center>Volunteering in $YEAR</center></h3>";
  if (isset($Vol['Year']) && $YEAR != $Vol['Year']) {
    echo "<center>This shows what you filled in for " . $Vol['Year'] . " please update as appropriate</center>";
    $Vol['VYid'] = -1;
  }
  echo "<tr><td colspan=5><h3>Which Team(s) would you like to volunteer for?</h3>\n";

  foreach ($volClasses as $c=>$exp) {
    $rows = 1;
    if (@ is_array($exp[6])) $rows += count($exp[6]);
    echo "<tr><td>" .  fm_checkbox($exp[0],$Vol,"SC_$c",'onchange=Update_VolClasses()','',1,' colspan=4') . " ";
    if ($rows == 1) {
      echo $exp[1];
//      if ($exp[4]) echo  fm_simpletext("",$Vol,'OtherText','size=100');
    } else {
      echo $exp[1];
      foreach($exp[6] as $xtr)
        echo "<br><span class=SC_$c>" . fm_text0($xtr[1],$Vol,$xtr[0],3) . " " . $xtr[2];
    }
  }

  echo "<tr><td colspan=5><h3>Availability</h3>If you could help on the days below, please give the times you would be available\n";
  echo "<tr class=SC_Days>" . fm_text("Months before the festival",$Vol,"AvailBefore",4) . BeforeTeams();
  $D = -2;
  for ($day = $YEARDATA['FirstDay']-1; $day<=$YEARDATA['LastDay']+1; $day++) {
    $av = "Avail" . ($day <0 ? "_" . (-$day) : $day);
    $rs = (($day<$YEARDATA['FirstDay'] || $day> $YEARDATA['LastDay']));
    echo "<tr " . ($rs?'class=SC_Days':'') . ">" . fm_text("On " . FestDate($day,'M'), $Vol,$av,4) . ($rs? BeforeTeams(): " <span class=SC_Days hidden> All Teams</span>");
  }

  echo "<tr><td><h3>Anything Else /Notes:</h3><td colspan=4>" . fm_basictextarea($Vol,'Notes',4,3);

  echo "<tr><td><td colspan=4><div class=tablecont><table border=0><tr><td width=33%>";
  if ($Vol['VYid'] < 0) {
    echo "<input type=submit name=Submit value='Submit Application'>\n"; 
    echo fm_hidden('A','Submit');
  } else {
    echo "<input type=submit name=Submit value='Update Application'>\n"; 
    echo fm_hidden('A','Update');  
  }  
  echo fm_hidden('id',$Vol['id']) . fm_hidden('VYid',$Vol['VYid']);
  if ($Vol['id'] > 0) {
    echo "<td width=33%><input type=submit name=NotThisYear value='Sorry not this Year'>";
    echo "<td><input class=floatright type=submit name=Delete value='Remove me from the festival records' onClick=\"javascript:return confirm('Please confirm delete?');\">";
  }
  echo "</table></div>";

  echo "</table></div><p>";
  if (Access('Staff')) echo "<h2><a href=Volunteers?A=List>Back to list of Volunteers</a></h2>";
  
  echo "<h3>Terms and Conditions</h3>\n";
  echo "<ul><li>I am, or will be over 18 years of age on " . FestDate($YEARDATA['FirstDay'],'L');
  echo "<li>You will be responsible for the health and safety of the general public, yourself and others around you " .
        "and must co-operate with festival organisers and supervisors at all times.\n";
  echo "<li>All volunteers must ensure that they are never, under any circumstances, alone with any person under the age of 18.\n";
  echo "<li>The festival organisers reserve the right to refuse volunteer applications and without explanation.\n";
  echo "<li>The festival organisers accept no liability for lost, damaged or stolen property.\n";
  echo "<li>All information specified on this form is treated as strictly confidential and will be held securely.\n";
  echo "</ul>\n";
  echo "<h3>Information</h3>\n";
  echo "Once submitted, an email will be sent to the leaders of the teams you have selected.<p>";
  echo "You will also get an email confirming what you have input and providing you a private link to view, edit and change your volunteer records.<p>";
  echo "Thank you for volunteering.<p>";
  echo "</form>\n";

  if ($Vol['VYid'] < 0) {
    echo "<input type=submit name=Submit value='Submit Application'><p>\n"; 
  } else {
    echo "<input type=submit name=Submit value='Update Application'><p>\n"; 
  }  

  dotail();
}

function Vol_Validate(&$Vol) {
  global $YEARDATA,$volClasses;

  if (strlen($Vol['SN']) < 2) return "Please give your name";
  if (strlen($Vol['Email']) < 6) return "Please give your Email";
  if (strlen($Vol['Phone']) < 6) return "Please give your Phone number(s)";
  if (strlen($Vol['Address']) < 10) return "Please give your Address";
  if (!isset($Vol['Over18']) || !$Vol['Over18']) return "Please confirm you are over 18";
//  if (strlen($Vol['Birthday']) < 2) return "Please give your age";

  $Clss=0;
  foreach ($volClasses as $c=>$exp) if (isset($Vol["SC_$c"]) && $Vol["SC_$c"]) $Clss++;
  if ($Clss == 0) return "Please select at least one team";

  $Avail=0;
  if (strlen($Vol["AvailBefore"]) > 1) $Avail++;
  for ($day =$YEARDATA['FirstDay']-1; $day<=$YEARDATA['LastDay']+1; $day++) {
    $av = "Avail" . ($day <0 ? "_" . (-$day) : $day);
    if (strlen($Vol[$av]) > 1) $Avail++;
  }

  if ($Avail == 0) return "Please give your availabilty";
  if (strlen($Vol['ContactName']) < 2) return "Please give an emergency contact";
  if (strlen($Vol['ContactPhone']) < 6) return ">Please give emergency Phone number(s)";
  if (!isset($Vol['Relation']) || !$Vol['Relation']) return "Please give your emergency contact relationship to you";

  Clean_Email($Vol['Email']);  
  return 0;
}

function Vol_Emails(&$Vol,$reason='Submit') {// Allow diff message on reason=update
  global $FESTSYS,$volClasses;
  Email_Volunteer($Vol,"Vol_Application_$reason",$Vol['Email']);
  foreach($volClasses as $vc=>$vd) {
    if (isset($Vol["SC_" . $vc]) && $Vol["SC_" . $vc]) Email_Volunteer($Vol,"Vol_Staff_$reason",$vd[3]. "@" . $FESTSYS['HostURL']);
  }
  echo "<h2 class=subtitle>Thankyou for " . (($reason == 'Submit')?"submitting":"updating") . " your application</h2>";
  if (Access('Staff')) echo "<h2><a href=Volunteers?A=List>Back to list of Volunteers</a></h2>";
  dotail();
}

function Vol_Staff_Emails(&$Vol,$reason='NotThisYear') {// Allow diff message on reason=update
  global $FESTSYS,$volClasses;
  foreach($volClasses as $vc=>$vd) {
    if (isset($Vol["SC_" . $vc]) && $Vol["SC_" . $vc]) Email_Volunteer($Vol,"Vol_Staff_$reason",$vd[3]. "@" . $FESTSYS['HostURL']);
  }
}


function List_Vols() {
  global $YEAR,$db,$volClasses,$YEARDATA,$PLANYEAR;
  echo "<button class='floatright FullD' onclick=\"($('.FullD').toggle())\">All Applications</button><button class='floatright FullD' hidden onclick=\"($('.FullD').toggle())\">Curent Aplications</button> ";


  echo "Click on name for full info<p>";
  
  echo "Where it says EXPAND under availability, means there is a longer entry - click on the persons name to see more info on their availabilty<p>";
  
  $coln = 0;  
  echo "<form method=post>";
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Phone</a>\n";
  echo "<th class=FullD hidden><a href=javascript:SortTable(" . $coln++ . ",'N')>Year</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Steward</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Setup</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Artistic</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Media</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Other</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Before</a>\n";
  for ($day = $YEARDATA['FirstDay']-1; $day<= $YEARDATA['LastDay']+1; $day++) {
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>" . FestDate($day,'s') . "</a>\n";
  }
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM Volunteers WHERE Status=0 ORDER BY SN");
  
  if ($res) while ($Vol = $res->fetch_assoc()) {
    $id = $Vol['id'];
    $VY = Get_Vol_Year($id);
//    var_dump($VY);
    $link = "<a href=Volunteers?A=View&id=$id>";
    echo "<tr" . (($VY['Year'] != $PLANYEAR)?" class=FullD hidden" : "" ) . ">";
    echo "<td>$id";
    echo "<td>$link" . $Vol['SN'] . "</a>";
    echo "<td>" . $Vol['Email'];
    echo "<td>" . $Vol['Phone'];
    echo "<td class=FullD hidden>" . $VY['Year'];
    foreach ($volClasses as $c=>$exp) echo "<td>" . (isset($VY["SC_$c"]) && $VY["SC_$c"]?'Y':'');
    echo "<td>" . (isset($VY['AvailBefore'])?$VY['AvailBefore']:"");
    for ($day = $YEARDATA['FirstDay']-1; $day<= $YEARDATA['LastDay']+1; $day++) {
      $av = "Avail" . ($day <0 ? "_" . (-$day) : $day);
      echo "<td>";
      if (isset($VY[$av])) echo (strlen($VY[$av])<12? $VY[$av] : $link. "Expand</a>") . "\n";
    }
  }
  echo "</tbody></table></div>\n";

  echo "<h2><a href=Volunteers?A=New>Add a Volunteer</a></h2>";
  dotail();
}

function Email_Form_Only($Vol,$mess='') {
  $coln = 0;
  echo "<h2>Stage 1 - Who are you?</h2>";
  if ($mess) echo "<h2 class=Err>$mess</h2>";
  echo "<form method=post>";
  echo "<div class=tablecont><table border>";
  echo "<tr>" . fm_text('Name',$Vol,'SN',2);
  echo "<tr>" . fm_text('Email',$Vol,'Email',2);
  echo fm_hidden('A','NewStage2');
  echo "</table></div><p><input type=Submit>\n";
  dotail();
}

function Check_Unique() { // Is email Email already registered - if so send new email back with link to update
  global $db;
  $adr = trim($_POST['Email']);
  if (!filter_var($adr,FILTER_VALIDATE_EMAIL)) Email_Form_Only($_POST,"Please give an email address");
  $res = $db->query("SELECT * FROM Volunteers WHERE Email LIKE '%$adr%'");
  if ($res && $res->num_rows) {
    $Vol = $res->fetch_assoc();
    if (!Access('Staff')) {
      Email_Volunteer($Vol,"Vol_Link_Message",$Vol['Email']);
      echo "<h2>You are already recorded as a Volunteer</h2>";
      echo "An email has been sent to you with a link to your record, only information about this years volunteering is now needed.<p>";
      dotail();
    }
    echo "<h2>" . $Vol['SN'] . " Is already a volunteer</h2>";
    $id = $Vol['id'];
    $Vol = array_merge($Vol, Get_Vol_Year($id));
    VolForm($Vol);
  } // else new - full through
}

function VolAction($Action) {
  global $PLANYEAR;

  dostaffhead("Steward / Volunteer Application", ["/js/Volunteers.js"]);
  switch ($Action) {
  
  case 'New': // New Volunteer
  default:
    $Vol = ['id'=>-1, 'VYid'=>-1, 'Year'=>$PLANYEAR];
    Email_Form_Only($Vol);
    break;

  case 'NewStage2': 
    Check_Unique(); // Deliberate drop through
  

  case 'Form': // New stage 2
    $Vol = ['id'=>-1, 'VYid'=>-1, 'Year'=>$PLANYEAR, 'SN'=>$_POST['SN'], 'Email'=>$_POST['Email']];
    VolForm($Vol);
    
  case 'List': // List Volunteers
    List_Vols();
    break;
    
  case 'Create': // Volunteer hass clicked 'Submit', store and email staff
  case 'Submit':
  case 'Update': // Volunteer/Staff has updated entry - if Volunteer, remail relevant staff
    $res = Vol_Validate($_REQUEST);
    if ($res) VolForm($_REQUEST,$res);
    
    if (!isset($_REQUEST['id']) || $_REQUEST['id'] < 0) { // New
      $_POST['AccessKey'] = rand_string(40);
      $id = Insert_db_post('Volunteers',$Vol);
      $_REQUEST['id'] = $id;
    } else {
      $Vol = Get_Volunteer($_REQUEST['id']);
      Update_db_post('Volunteers',$Vol);
      $id = $Vol['id'];
    }
    
    if (!isset($_REQUEST['VYid']) || $_REQUEST['VYid'] < 0) { // New year
      $_POST['Year'] = $PLANYEAR;
      $_POST['Volid'] = $id;
      $Vol['VYid'] = Insert_db_post('VolYear',$Vol);
    } else {
      $Vol = array_merge($Vol, Get_Vol_Year($id));
      Update_db_post('VolYear',$Vol);
    }
    
    Vol_Emails($Vol,$Action);
    break;
  
  case 'View':
    $Vol = Get_Volunteer($_REQUEST['id']);
    $id = $Vol['id'];
    $Vol = array_merge($Vol, Get_Vol_Year($id));

    VolForm($Vol);
    break;
     
  case 'Email': // Send Invite email out
  
    break;
    
  case 'NotThisYear':
    if (!isset($_REQUEST['VYid']) || $_REQUEST['VYid'] < 0) { // Not this year anyway
    } else {
      $Vol = Get_Volunteer($id = $_REQUEST['id']);
      $Vol = array_merge($Vol, Get_Vol_Year($id));
      db_delete('VolYear',$Vol['VYid']);
      Vol_Staff_Emails($Vol);
    }
    
    echo "<h2>Thankyou for letting us know</h2>";
    break;
    
  case 'Delete': // Delete Volunteer
    $Vol = Get_Volunteer($id = $_REQUEST['id']);
    $Vol['Status']=1;
    Put_Volunteer($Vol);
    $OldVol = $Vol = array_merge($Vol, Get_Vol_Year($id));
    if ($Vol['Year'] == $PLANYEAR) Vol_Staff_Emails($Vol);

    echo "<h2>Thankyou for Volunteering in the past, you are no longer recorded</h2>";    
    break;
    
  }  
}

  
/*
  TODO
  1) DBS upload
  6) Email all/subsets
  7) Form validation - hack prevention

*/

?>
