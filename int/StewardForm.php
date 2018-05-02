<?php
  include("fest.php");

  dostaffhead("iSteward / Volunteer Application", "/js/Participants.js");

  include_once("SignupLib.php");
  global $USER,$USERID,$db,$THISYEAR,$StewClasses,$Relations,$Days;

  if (isset($_POST['submit'])) {
    if (strlen($_POST['SName']) < 2) { echo "<p class=Err>Please give your name\n"; $err=1; };
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
      $_POST['Year'] = $THISYEAR;
      $id = Insert_db_post('Stewards',$stew);
    
      Email_Steward($stew,'Stew_Application',$stew['Email']);
      Email_Steward($stew,'Stew_Paul','paulfolkfest@outlook.com');

      echo "<h2 class=subtitle>Thankyou for submitting your application</h2>";
      dotail();
      exit();
    }
  }
  
  echo "<h2 class=subtitle>Steward / Volunteer Application Form</h2>\n";
  echo "<form method=post action=StewardForm.php>";
  echo "<table border>\n";
  echo "<tr>" . fm_text('Name',$_POST,'SName',2);
  echo "<tr>" . fm_text('Email',$_POST,'Email',2);
  echo "<tr>" . fm_text('Phone(s)',$_POST,'Phone',2);
  echo "<tr>" . fm_text('Address',$_POST,'Address',4);
  echo "<tr>" . fm_text('Postcode',$_POST,'PostCode');
  echo "<tr>" . fm_text('Date of Birth',$_POST,'Birthday');

  echo "<tr><td colspan=4><h3>Which Team(s) would you like to volunteer for?</h3>\n";
  echo "<tr><td colspan=4>";
  foreach ($StewClasses as $c=>$exp) {
    echo fm_checkbox($c,$_POST,"SC_$c") . $exp . "<br>\n";
  }

  echo "<tr>" . fm_text('Prefered Duties',$_POST,'Prefer',4) . "<br>Include any activity you would particularly like to be a steward for";
  echo "<tr>" . fm_text('Disliked Duties',$_POST,'Dislike',4) . "<br>Include any activity you would particularly like to NOT be a steward for";

  echo "<tr><td colspan=4><h3>Availability</h3>If you could help on the days below, please give the times you would be available\n";
  $D = -2;
  foreach ($Days as $d=>$ld) {
    if ($D >=0 && $D<3) {
      echo "<tr>" . fm_text($ld . " " . ($MASTER['DateFri']+$D++) . "th June $THISYEAR",$_POST,"Avail$d",4);
    } else { $D++; };
  }

  echo "<tr><td colspan=4><h3>Legal</h3>\n";
  echo "<tr><td colspan=4>Do you have a current DBS check? if so please give details<br>" . fm_textinput('DBS',$_POST['DBS'],'size=100');
  echo "<tr><td colspan=4><h3>Emergency Contact</h3>\n";
  echo "<tr>" . fm_text('Contact Name',$_POST,'ContactName',2);
  echo "<tr>" . fm_text('Contact Phone',$_POST,'ContactPhone',2);
  echo "<tr><td>Relationship:<td>" . fm_select($Relations,$_POST,'Relation');
  echo "</table><p>";
  echo "<input type=submit name=submit value='Submit Application'><p>\n"; 
  echo "</form>\n";

  echo "<h3>Terms and Conditions</h3>\n";
  echo "<ul><li>I am, or will be over 18 years of age on Thursday " . ($MASTER['DateFri'] -1) . "th June $THISYEAR.\n";
  echo "<li>You will be responsible for the health and safety of the general public, yourself and others around you " .
	"and must co-operate with festival organisers and supervisors at all times.\n";
  echo "<li>All volunteers must ensure that they are never, under any circumstances, alone with any person under the age of 18.\n";
  echo "<li>The festival organisers reserve the right to refuse volunteer applications and without explanation.\n";
  echo "<li>The festival organisers accept no liability for lost, damaged or stolen property.\n";
  echo "<li>All information specified on this form is treated as strictly confidential and will be held securely.\n";
  echo "</ul>\n";


  dotail();

?>
