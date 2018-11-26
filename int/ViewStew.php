<?php
  include_once("fest.php");

  dostaffhead("View Stewarding form");
  include_once("SignupLib.php");
  global $USER,$USERID,$db,$PLANYEAR,$StewClasses,$Relations,$Days;
  
function Submit_Steward() {
  if (strlen($_POST['SN']) < 2) { echo "<p class=Err>Please give your name\n"; $err=1; };
  if (strlen($_POST['Email']) < 6) { echo "<p class=Err>Please give your Email\n"; $err=1; };
  if (strlen($_POST['Phone']) < 6) { echo "<p class=Err>Please give your Phone number(s)\n"; $err=1; };
  if (strlen($_POST['Address']) < 20) { echo "<p class=Err>Please give the contacts Address\n"; $err=1; };
  if (strlen($_POST['Birthday']) < 4) { echo "<p class=Err>Please give your birthday\n"; $err=1; };

  $Clss=0;
  foreach ($StewClasses as $c=>$exp) if ($_POST["SC_$c"]) $Clss++;
  if ($Clss == 0) { echo "<p class=Err>Please select at least once team\n"; $err=1; };

  $Avail=0;
  foreach ($Days as $d=>$ld) if (strlen($_POST["Avail$d"]) > 1) $Avail++;

  if ($Avail == 0) { echo "<p class=Err>Please give your availabilty\n"; $err=1; };
  if (strlen($_POST['ContactName']) < 2) { echo "<p class=Err>Please give an emergency contact\n"; $err=1; };
  if (strlen($_POST['ContactPhone']) < 6) { echo "<p class=Err>Please give emergency Phone number(s)\n"; $err=1; };

  Clean_Email($_POST{'Email'});
  if (!$err) {
//      echo "<P>VALID...<P>";
    $_POST['AccessKey'] = rand_string(40);
    $_POST['Year'] = $PLANYEAR;
    $id = Insert_db_post('Stewards',$stew);
    
    Email_Steward($stew,'Stew_Application',$stew['Email']);
    Email_Steward($stew,'Vol_Team_Message','paulfolkfest@outlook.com');

    echo "<h2 class=subtitle>Thankyou for submitting your application</h2>";
    dotail();
  }
}

 
  if (isset($_REQUEST['ACTION'])) { /* Response to Action button */
    $id = $_POST['id'];
    $stew = Get_Steward($id);
    A_Check('Participant','Steward',$id);
    Clean_Email($_POST{'Email'});
    Update_db_post('Stewards',$stew);
    switch ($_REQUEST['ACTION']) {
    case 'Submit':


      break;
    
    case 'Update':
      break;
    }
  } else if (isset($_GET['id'])) {
    $id = $_GET['id'];
    A_Check('Participant','Steward',$id);
    $stew = Get_Steward($id);
  } else { // New
  }

  echo "<h2 class=subtitle>Steward / Volunteer Application Form</h2>\n";
  echo "<form method=post action=ViewStew.php>";
  echo "<table border>\n";
  echo "<tr>" . fm_text('SN',$stew,'SN',2);
  echo fm_hidden('id',$id);
  echo "<tr>" . fm_text('Email',$stew,'Email',2);
  echo "<tr>" . fm_text('Phone(s)',$stew,'Phone',2);
  echo "<tr>" . fm_text('Address',$stew,'Address',4);
  echo "<tr>" . fm_text('Postcode',$stew,'PostCode');
  echo "<tr>" . fm_text('Date of Birth',$stew,'Birthday');

  echo "<tr><td colspan=4><h3>Which Team(s) would you like to volunteer for?</h3>\n";
  echo "<tr><td colspan=4>";
  foreach ($StewClasses as $c=>$exp) {
    echo fm_checkbox($c,$stew,"SC_$c") . $exp[0] . "<br>\n";
  }

  echo "<tr>" . fm_text('Prefered Duties',$stew,'Prefer',4) . "<br>Include any activity you would particularly like to be a steward for";
  echo "<tr>" . fm_text('Disliked Duties',$stew,'Dislike',4) . "<br>Include any activity you would particularly like to NOT be a steward for";

  echo "<tr><td colspan=4><h3>Availability</h3>If you could help on the days below, please give the times you would be available\n";
  $D = -2;
  foreach ($Days as $d=>$ld) {
    if ($D >=0 && $D<3) {
      echo "<tr>" . fm_text(FestDate($D,'L'),$stew,"Avail$d",4);
    } else { $D++; };
  }

  echo "<tr><td colspan=4><h3>Legal</h3>\n";
  echo "<tr><td colspan=4>Do you have a current DBS check? if so please give details<br>" . fm_textinput('DBS',$stew['DBS'],'size=100');
  echo "<tr><td colspan=4><h3>Emergency Contact</h3>\n";
  echo "<tr>" . fm_text('Contact Name',$stew,'ContactName',2);
  echo "<tr>" . fm_text('Contact Phone',$stew,'ContactPhone',2);
  echo "<tr><td>Relationship:<td>" . fm_select($Relations,$stew,'Relation');
  echo "</table><p>";
  echo "<input type=submit name=submit value='Change Application'><p>\n"; 
  echo "</form>\n";

  echo "<h2><a href=StewardView.php>Back to list of Stewards</a>";
  dotail();
?>
