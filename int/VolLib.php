<?php
  include_once("fest.php");

  dostaffhead("Steward / Volunteer Application", "/js/Volunteers.js");

  include_once("Email.php");
//  include_once("SignupLib.php");
  global $USER,$USERID,$db,$PLANYEAR,$StewClasses,$Relations;
  
$yesno = array('','Yes','No');
// shortname =>[ longname, description, listofwhen, NotUsed days,emailto, Otherbox,[extrarows]] - extrarows = [field,shorttxt,longtxt]
$volClasses = [ 'Stew' => ['Stewarding', 'Info Points, Concerts, Road Closures, Street Collecting etc',[0,1,2],'stewards',0,
                           [['Stew_Prefer','Prefered Stewarding Duties',"Include any activity you would particularly like to be a steward for"],
                            ['Stew_Dislike','Disliked Stewarding Duties',"Include any activity you would particularly NOT like to be a steward for"]]],

                'Setup' => ['Setup/Cleardown', 'Banners, Bunting, Posters, Stages, Marquees, Venues, Furniture etc',['Before',-2,-1,0,1,2,3],'setup',0,
                            [['Setup_Like','Prefered Setup Tasks',"Setup/Cleardown tasks you would like to do"],
                             ['Setup_Dislike','Disliked Setup Tasks',"Any Setup/Cleardown tasks you would wish to avoid"]]],

                'Art' => ['Artistic', 'Setting up art displays, town decorations etc',['Before',-1,0,1,2,3],'art',0,
                          [['Art_Like', 'Prefered Art Tasks',"What would you like to help decorate"],
                           ['Art_Dislike', 'Disliked Art Tasks',"What would you not like to do"]]],

                'Media' => ['Media', 'Photography, Videography etc',[0,1,2],'media',0,
                            [['Media_Like','Prefered Media Areas',"What events would you like to cover"],
                             ['Media_Dislike', 'Disliked Media Areas',"What events would you not want to cover"]]],

                'Other' => ['Other', '',['Before',-1,0,1,2,3],'webmaster',0,
                            [['OtherText','Please elaborate','']]],
               ];
               
$Relations = array('','Husband','Wife','Partner','Son','Daughter','Mother','Father','Brother','Sister','Grandchild','Grandparent','Guardian','Uncle','Aunty',
                'Son/Daughter in law', 'Friend','Other');
 

function Get_Vol_Details(&$vol) {
  global $volClasses,$Relations,$MASTER;
  $Body = "\nName: " . $vol['SN'] . "<br>\n";
  $Body .= "Email: <a href=mailto:" . $vol['Email'] . ">" . $vol['Email'] . "</a><br>\n";
  if ($vol['Phone']) $Body .= "Phone: " . $vol['Phone'] . "<br>\n";
  $Body .= "Address: " . $vol['Address'] . "<br>\n";
  if (isset($vol['PostCode'])) $Body .= "PostCode: " . $vol['PostCode'] . "<br>\n\n";

  $Body .= "Birthday: " . $vol['Birthday'] . "<br>\n";
  $Body .= "\n\n";

  foreach ($volClasses as $s=>$sl) 
    if (isset($vol["SC_$s"]) && $vol["SC_$s"]) {
      $Body .= "<p>Team: $s<br>\n";
      
      if (is_array($sl[5]))
        foreach ($sl[5] as $xq) 
          if (isset($vol[$xq[0]]) && $vol[$xq[0]]) $Body .= $xq[1] . ": " . $vol[$xq[0]] . "<br>\n";
      if ($sl[4]) $Body .= "Other tasks:" . $vol['OtherText'] . "<br>\n";
    }
  $Body .= "<p>\n";
  $Body . "Available:<p>\n";
  if (isset($vol['AvailBefore']))  $Body .= "Before Festival: " . $vol["AvailBefore"] . "<br>\n";
  for ($day = $MASTER['FirstDay']-1; $day<= $MASTER['LastDay']+1; $day++) {
    $av = "Avail" . ($day <0 ? "_" . (-$day) : $day);
    if (isset($vol[$av])) $Body .= FestDate($day,'M') . ": " . $vol[$av] . "<br>\n";
  }
  
  if (isset($Vol['Notes'])) $Body .= "<p>Notes: " . $Vol['Notes'] . "<p>\n";

  $Body .= "<p>DBS: " . ($vol['DBS']?$vol['DBS'] : 'No') . "<p>\n\n";

  $Body .= "Emergency Contact<br>\nName: " . $vol['ContactName'] . "<br>\n";
  $Body .= "Phone: " . $vol['ContactPhone'] . "<br>\n";
  $Body .= "Relationship: " . $Relations[$vol['Relation']] . "<br>\n";
  return $Body;
}

function Vol_Details($key,&$vol) {
  global $MASTER_DATA;
  switch ($key) {
  case 'WHO': return firstword($vol['SN']);
  case 'DETAILS': return Get_Vol_Details($vol);
  case 'LINK' : return "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Access.php?t=v&id=" . $vol['id'] . "&key=" . $vol['AccessKey'] . "><b>link</b></a>";
  case 'WMFFLINK' : return "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Volunteers.php?A=View&id=" . $vol['id'] . "><b>link</b></a>";
  }
}

function Email_Volunteer(&$vol,$messcat,$whoto) {
  global $PLANYEAR,$USER,$MASTER_DATA;
  Email_Proforma($whoto,$messcat,$MASTER_DATA['FestName'] . " $PLANYEAR and " . $vol['SN'],'Vol_Details',$vol,'Volunteer.txt');
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
  $res = $db->query("SELECT * FROM VolYear WHERE Volid=$id AND Year=$Year");
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

function VolForm(&$Vol,$Err='') {
  global $volClasses,$MASTER,$PLANYEAR,$YEAR,$Relations;
  echo "<h2 class=subtitle>Steward / Volunteer Application Form</h2>\n";
  echo "<p class=Err>$Err<p>";
  echo "<form method=post action=Volunteers.php>";
  echo "<table border>\n";
  echo "<tr><td colspan=4><h3><center>Volunteer</center></h3>";
  echo "<tr>" . fm_text('Name',$Vol,'SN',2);
  echo "<tr>" . fm_text('Email',$Vol,'Email',2);
  echo "<tr>" . fm_text('Phone(s)',$Vol,'Phone',2);
  echo "<tr>" . fm_text('Address',$Vol,'Address',4);
  echo "<tr>" . fm_text('Date of Birth',$Vol,'Birthday');
  echo "<tr><td colspan=4><h3>Legal</h3>\n";
  echo "Do you have a current DBS check? if so please give details<br>" . fm_textinput('DBS',(isset($Vol['DBS'])?$Vol['DBS']:''),'size=100');
  echo "<tr><td colspan=4><h3>Emergency Contact</h3>\n";
  echo "<tr>" . fm_text('Contact Name',$Vol,'ContactName',2);
  echo "<tr>" . fm_text('Contact Phone',$Vol,'ContactPhone',2);
  echo "<tr><td>Relationship:<td>" . fm_select($Relations,$Vol,'Relation');

  echo "<tr><td colspan=4><h3><center>Volunteering in $YEAR</center></h3>";
  echo "<tr><td colspan=4><h3>Which Team(s) would you like to volunteer for?</h3>\n";
  foreach ($volClasses as $c=>$exp) {
    $rows = 1;
    if (@ is_array($exp[5])) $rows += count($exp[5]);
    echo "<tr><td>" .  fm_checkbox($exp[0],$Vol,"SC_$c",'onchange=Update_VolClasses()','',1) . " ";
    if ($rows == 1) {
      echo $exp[1];
//      if ($exp[4]) echo  fm_simpletext("",$Vol,'OtherText','size=100');
    } else {
      echo $exp[1];
      foreach($exp[5] as $xtr)
        echo "<br><span class=SC_$c>" . fm_text0($xtr[1],$Vol,$xtr[0],3) . " " . $xtr[2];
    }
  }

  echo "<tr><td colspan=4><h3>Availability</h3>If you could help on the days below, please give the times you would be available\n";
  echo "<tr class=SC_Days>" . fm_text("Before the festival",$Vol,"AvailBefore",4);
  $D = -2;
  for ($day = $MASTER['FirstDay']-1; $day<=$MASTER['LastDay']+1; $day++) {
    $av = "Avail" . ($day <0 ? "_" . (-$day) : $day);
    echo "<tr " . (($day<$MASTER['FirstDay'] || $day> $MASTER['LastDay'])?'class=SC_Days':'') . ">" . fm_text("On " . FestDate($day,'M'), $Vol,$av,4);
  }

  echo "<tr><td><h3>Anything Else /Notes:</h3><td>" . fm_basictextarea($Vol,'Notes',4,3);
  echo "</table><p>";
  echo "<input type=submit name=Submit value='Submit Application'><p>\n"; 
  echo fm_hidden('A','Create') . fm_hidden('id',$id) . fm_hidden('VYid',$Vol['VYid']);
  echo "</form>\n";

  echo "<h3>Terms and Conditions</h3>\n";
  echo "<ul><li>I am, or will be over 18 years of age on Thursday " . FestDate(-1,'L');
  echo "<li>You will be responsible for the health and safety of the general public, yourself and others around you " .
        "and must co-operate with festival organisers and supervisors at all times.\n";
  echo "<li>All volunteers must ensure that they are never, under any circumstances, alone with any person under the age of 18.\n";
  echo "<li>The festival organisers reserve the right to refuse volunteer applications and without explanation.\n";
  echo "<li>The festival organisers accept no liability for lost, damaged or stolen property.\n";
  echo "<li>All information specified on this form is treated as strictly confidential and will be held securely.\n";
  echo "</ul>\n";
  echo "<h3>Information</h3>\n";
  echo "Once submitted, an email will be sent to the leaders of the teams you have selected.<p>";
  echo "You will also get an email confirming what you have input and providing you a private link to edit and change your volunteer records.<p>";
  echo "Thank you for volunteering.<p>";
  dotail();
}

function Vol_Validate(&$Vol) {
  global $MASTER,$volClasses;
  
  if (strlen($Vol['SN']) < 2) return "Please give your name";
  if (strlen($Vol['Email']) < 6) return "Please give your Email";
  if (strlen($Vol['Phone']) < 6) return "Please give your Phone number(s)";
  if (strlen($Vol['Address']) < 20) return "Please give the contacts Address";
  if (strlen($Vol['Birthday']) < 2) return "Please give your age";

  $Clss=0;
  foreach ($volClasses as $c=>$exp) if (isset($Vol["SC_$c"]) && $Vol["SC_$c"]) $Clss++;
  if ($Clss == 0) return "Please select at least one team";

  $Avail=0;
  if (strlen($Vol["AvailBefore"]) > 1) $Avail++;
  for ($day =$MASTER['FirstDay']-1; $day<=$MASTER['LastDay']+1; $day++) {
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

function Vol_Emails(&$Vol,$reason='') {// Allow diff message on reason=update
  global $MASTER_DATA,$volClasses;
  Email_Volunteer($Vol,'Vol_Application',$Vol['Email']);
  foreach($volClasses as $vc=>$vd) {
    if (isset($Vol["SC_" . $vc]) && $Vol["SC_" . $vc]) Email_Volunteer($Vol,'Vol_Staff',$vd[3]. "@" . $MASTER_DATA['HostURL']);
  }
  echo "<h2 class=subtitle>Thankyou for submitting your application</h2>";
  
  dotail();
}

function List_Vols() {
  global $YEAR,$db,$volClasses,$MASTER,$PLANYEAR;
  echo "<button class='floatright FullD' onclick=\"($('.FullD').toggle())\">All Applications</button><button class='floatright FullD' hidden onclick=\"($('.FullD').toggle())\">Curent Aplications</button> ";


  echo "Click on name for full info<p>";
  $coln = 0;  
  echo "<form method=post action=StewardView.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Phone</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Year</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Steward</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Setup</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Artistic</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Media</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Other</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Before</a>\n";
  for ($day = $MASTER['FirstDay']-1; $day<= $MASTER['LastDay']+1; $day++) {
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>" . FestDate($day,'s') . "</a>\n";
  }
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM Volunteers ORDER BY SN");
  
  if ($res) while ($Vol = $res->fetch_assoc()) {
    $id = $Vol['id'];
    $VY = Get_Vol_Year($id);
//    var_dump($VY);
    $link = "<a href=Volunteers.php?A=View&id=$id>";
    echo "<tr" . (($VY['Year'] != $PLANYEAR)?" class=FullD hidden" : "" ) . ">";
    echo "<td>$id";
    echo "<td>$link" . $Vol['SN'] . "</a>";
    echo "<td>" . $Vol['Email'];
    echo "<td>" . $Vol['Phone'];
    echo "<td>" . $VY['Year'];
    foreach ($volClasses as $c=>$exp) echo "<td>" . (isset($VY["SC_$c"])?'Y':'');
    echo "<td>" . (isset($VY['AvailBefore'])?$VY['AvailBefore']:"");
    for ($day = $MASTER['FirstDay']-1; $day<= $MASTER['LastDay']+1; $day++) {
      $av = "Avail" . ($day <0 ? "_" . (-$day) : $day);
      echo "<td>";
      if (isset($VY[$av])) echo (strlen($VY[$av])<12? $VY[$av] : $link. "Expand</a>") . "\n";
    }
  }
  echo "</tbody></table>\n";

  dotail();
}


function VolAction($Action) {
  global $PLANYEAR;
  
  switch ($Action) {
  
  case 'New': // New Volunteer
  default:
    $Vol = ['id'=>-1, 'VYid'=>-1, 'Year'=>$PLANYEAR];

    VolForm($Vol);
    
  case 'List': // List Volunteers
    List_Vols();
    break;
    
  case 'Create': // Volunteer hass clicked 'Submit', store and email staff
  case 'Submit':
  case 'Update': // Volunteer/Staff has updated entry - if Volunteer, remail relevant staff
    $res = Vol_Validate($_REQUEST);
    if ($res) VolForm($_REQUEST,$res);
var_dump($_POST);
    
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
    
  case 'Copy': // Create entry for PLANYEAR, from Most recent year
  
    break;
  }  
}

  
/*
  TODO
  1) DBS upload
  2) Year operation
  3) Revised application - change email to staff
  4) View to work with YEAR only - if not planyear - list to indicate if planyear submission
  5) multi year and access to current year
  6) Update...
  
  if viewold && newexists - no edit
  if viewold && !new - edit save new rec
  if viewcur - edit in place
  if norecord - new form
  
  VolYear



  if (isset($Vol['submit'])) {
    if (strlen($Vol['SN']) < 2) { echo "<p class=Err>Please give your name\n"; $err=1; };
    if (strlen($Vol['Email']) < 6) { echo "<p class=Err>Please give your Email\n"; $err=1; };
    if (strlen($Vol['Phone']) < 6) { echo "<p class=Err>Please give your Phone number(s)\n"; $err=1; };
    if (strlen($Vol['Address']) < 20) { echo "<p class=Err>Please give the contacts Address\n"; $err=1; };
    if (strlen($Vol['Birthday']) < 4) { echo "<p class=Err>Please give your birthday\n"; $err=1; };

    $Clss=0;
    foreach ($StewClasses as $c=>$exp) if ($Vol["SC_$c"]) $Clss++;
    if ($Clss == 0) { echo "<p class=Err>Please select at least once team\n"; $err=1; };

    $Avail=0;
    foreach ($Days as $d=>$ld) if (strlen($Vol["Avail$d"]) > 1) $Avail++;

    if ($Avail == 0) { echo "<p class=Err>Please give your availabilty\n"; $err=1; };
    if (strlen($Vol['ContactName']) < 2) { echo "<p class=Err>Please give an emergency contact\n"; $err=1; };
    if (strlen($Vol['ContactPhone']) < 6) { echo "<p class=Err>Please give emergency Phone number(s)\n"; $err=1; };

    Clean_Email($Vol['Email']);
    if (!$err) {
//      echo "<P>VALID...<P>";
      $Vol['AccessKey'] = rand_string(40);
      $Vol['Year'] = $PLANYEAR;
      $id = Insert_db_post('Volunteers',$stew);
    
      Email_Steward($stew,'Vol_Application',$stew['Email']);
      foreach($volClass as $vc=>$vd) {
        if ($Vol["SC_" . $vc]) Email_Steward($stew,'Vol_Staff',$vd[2]. "@" . $MASTER_DATA['HostURL']);

      echo "<h2 class=subtitle>Thankyou for submitting your application</h2>";
      dotail();
      exit();
    }
  }
  }
*/

?>
