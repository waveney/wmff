<?php
  include_once("fest.php");

  dostaffhead("Steward / Volunteer Application", "/js/Participants.js");

//  include_once("SignupLib.php");
  global $USER,$USERID,$db,$PLANYEAR,$StewClasses,$Relations,$Days;
  
$yesno = array('','Yes','No');
$volClasses = array('Stewarding'=> ['Info Points, Concerts, Road Closures, Street Collecting etc',[0,1,2],'stewards',0],
                'Setup' => ['Banners, Bunting, Posters, Stages, Marquees, Venues, Furniture etc',['Before',-2,-1,0,1,2,3],'setup',0],
                'Artistic' => ['Setting up art displays, town decorations etc',['Before',-1,0,1,2,3],'Art',0],
                'Media' => ['Photography, Videography etc',[0,1,2],'Media',0],
                'Other' => ['Please elaborate',['Before',-1,0,1,2,3],'webmaster',1]);
$Days = array('Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday','Mon'=>'Monday','Tue'=>'Tuesday');
$Relations = array('Husband','Wife','Partner','Son','Daughter','Mother','Father','Brother','Sister','Grandchild','Grandparent','Guardian','Uncle','Aunty',
                'Son/Daughter in law', 'Friend','Other');
 

function Get_Vol_Details(&$vol) {
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

function Vol_Details($key,&$vol) {
  switch ($key) {
  case 'WHO': return firstword($vol['SN']);
  case 'DETAILS': return Get_Vol_Details($stwe);
  case 'LINK' : return "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Access.php?t=w&id=" . $vol['id'] . "&key=" . $vol['AccessKey'] . "><b>link</b></a>";
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

function VolForm(&$Vol,$Err='') {
  global $volClasses,$MASTER,$PLANYEAR,$Relations;
  echo "<h2 class=subtitle>Steward / Volunteer Application Form</h2>\n";
  echo "<p class=Err>$Err<p>";
  echo "<form method=post action=StewardForm.php>";
  echo "<table border>\n";
  echo "<tr>" . fm_text('Name',$Vol,'SN',2);
  echo "<tr>" . fm_text('Email',$Vol,'Email',2);
  echo "<tr>" . fm_text('Phone(s)',$Vol,'Phone',2);
  echo "<tr>" . fm_text('Address',$Vol,'Address',4);
  echo "<tr>" . fm_text('Postcode',$Vol,'PostCode');
  echo "<tr>" . fm_text('Date of Birth',$Vol,'Birthday');

  echo "<tr><td colspan=4><h3>Which Team(s) would you like to volunteer for?</h3>\n";
  foreach ($volClasses as $c=>$exp) {
    echo "<tr><td>" .  fm_checkbox($c,$Vol,"SC_$c",'','',1) . " " . $exp[0];
    if ($exp[3]) echo  fm_simpletext("",$Vol,'OtherText','size=100');
  }

  echo "<tr>" . fm_text('Prefered Duties',$Vol,'Prefer',4) . "<br>Include any activity you would particularly like to be a steward for";
  echo "<tr>" . fm_text('Disliked Duties',$Vol,'Dislike',4) . "<br>Include any activity you would particularly like to NOT be a steward for";

  if (!isset($Vol['SC_Setup'])) $Vol['SC_Setup']=0;
  echo "<tr><td colspan=4><h3>Availability</h3>If you could help on the days below, please give the times you would be available\n";
  echo "<tr class=SC_Setup hidden>" . fm_text("Before the festival",$Vol,"AvailBefore");
  $D = -2;
  foreach ($Days as $d=>$ld) {
    echo "<tr class=SC_Setup " . ($Vol['SC_Setup']?'hidden':'') . ">" . fm_text(FestDate($D,'L'),$Vol,"Avail$d",4);
  }

  echo "<tr><td colspan=4><h3>Legal</h3>\n";
  echo "Do you have a current DBS check? if so please give details<br>" . fm_textinput('DBS',(isset($Vol['DBS'])?$Vol['DBS']:''),'size=100');
  echo "<tr><td colspan=4><h3>Emergency Contact</h3>\n";
  echo "<tr>" . fm_text('Contact Name',$Vol,'ContactName',2);
  echo "<tr>" . fm_text('Contact Phone',$Vol,'ContactPhone',2);
  echo "<tr><td>Relationship:<td>" . fm_select($Relations,$Vol,'Relation');
  echo "</table><p>";
  echo "<input type=submit name=submit value='Submit Application'><p>\n"; 
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
  dotail();
}

function Vol_Validate(&$Vol) {
  if (strlen($Vol['SN']) < 2) return "Please give your name";
  if (strlen($Vol['Email']) < 6) return "Please give your Email";
  if (strlen($Vol['Phone']) < 6) return "Please give your Phone number(s)";
  if (strlen($Vol['Address']) < 20) return "Please give the contacts Address";
  if (strlen($Vol['Birthday']) < 4) return "Please give your birthday";

  $Clss=0;
  foreach ($volClasses as $c=>$exp) if ($Vol["SC_$c"]) $Clss++;
  if ($Clss == 0) return "Please select at least once team";

  $Avail=0;
  if (strlen($Vol["AvailBefore"]) > 1) $Avail++;
  foreach ($Days as $d=>$ld) if (strlen($Vol["Avail$d"]) > 1) $Avail++;

  if ($Avail == 0) return "Please give your availabilty";
  if (strlen($Vol['ContactName']) < 2) return "Please give an emergency contact";
  if (strlen($Vol['ContactPhone']) < 6) return ">Please give emergency Phone number(s)";

  Clean_Email($Vol['Email']);  
  return 0;
}

function Vol_Emails(&$Vol) {
  global $Master_DATA;
  Email_Steward($Vol,'Vol_Application',$Vol['Email']);
  foreach($volClass as $vc=>$vd) {
    if ($Vol["SC_" . $vc]) Email_Steward($stew,'Vol_Staff',$vd[2]. "@" . $MASTER_DATA['HostURL']);
  }
  echo "<h2 class=subtitle>Thankyou for submitting your application</h2>";
  dotail();
}

function List_Vols() {
  global $YEAR,$db,$volClasses;
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
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Steward</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Setup</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Artistic</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Media</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Before</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Thu</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Fri</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sat</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sun</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Mon</a>\n";
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM Volunteers ORDER BY SN");
  
  if ($res) while ($Vol = $res->fetch_assoc()) {
    $id = $Vol['id'];
    echo "<tr" . (($Vol['Year'] == $YEAR)?" class=FullD hidden" : "" ) . ">";
    echo "<td>$id";
    echo "<td><a href=Volunteers.php?A=View&id=$id>" . $Vol['SN'] . "</a>";
    echo "<td>" . $Vol['Email'];
    echo "<td>" . $Vol['Phone'];
    foreach ($volClasses as $c=>$exp) echo "<td>" . ['','Y'][$Vol["SC_$c"]];
    echo "<td>" . $Vol['AvailBefore'];
    echo "<td>" . $Vol['AvailThu'];
    echo "<td>" . $Vol['AvailFri'];
    echo "<td>" . $Vol['AvailSat'];
    echo "<td>" . $Vol['AvailSun'];
    echo "<td>" . $Vol['AvailMon'];
  }
  echo "</tbody></table>\n";

  dotail();
}


function VolAction($Action) {
  global $PLANYEAR;
  
  switch ($Action) {
  
  case 'New': // New Volunteer
  default:
    $Vol = ['id'=>-1,'Relation'=>-1, 'Year'=>$PLANYEAR];

    VolForm($Vol);
    
  case 'List': // List Volunteers
    List_Vols();
    break;
    
  case 'Create': // Volunteer hass clicked 'Submit', store and email staff
  case 'Submit':
    $res = Vol_Validate($_POST);
    if ($res) Vol_Form($_POST,$res);
    
    if ($_POST['id'] < 0) { // New
      $Vol['AccessKey'] = rand_string(40);
      $id = Insert_db_post('Volunteers',$Vol);
    } else {
      $Vol = Get_Voluteer($_POST['id']);
      Update_db_post('Volunteers',$Vol);
    }
    Vol_Emails($Vol);
    break;
  
  case 'View':
    $Vol = Get_Voluteer($_POST['id']);
    VolForm($Vol);
    break;
  
  case 'Update': // Volunteer/Staff has updated entry - if Volunteer, remail relevant staff
    $Vol = Get_Voluteer($_POST['id']);
    Update_db_post('Volunteers',$Vol);
//    if (); // TODO
    break;
   
  case 'Email': // Send Invite email out
  
    break;
    
  case 'Copy': // Create entry for PLANYEAR, from Most recent year
  
    break;
  }  
}

  
/*
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
