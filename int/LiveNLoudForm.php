<?php
  include_once("fest.php");

  dostaffhead("Live and Loud Application", "/js/Participants.js");
  A_Check('SysAdmin'); // lock out for this year
  include_once("SignupLib.php");
  global $USER,$USERID,$db,$PLANYEAR;

  /* In the longer term this will be based on participants, but I want to do this quickly for 2018 so it is stand alone for now */

  if (isset($_POST['submit'])) {
    if (!isset($_POST['LnlCat']) || $_POST['LnlCat']<1) { echo "<p class=Err>Please select a competition category\n"; $err=1; };
    if (strlen($_POST['SName']) < 2) { echo "<p class=Err>Please give your band's name\n"; $err=1; };
    if (strlen($_POST['Style']) < 2) { echo "<p class=Err>Please give your band's music style\n"; $err=1; };
    if (!($_POST['TotalSize'] || strlen($_POST['Name1'])>6)) { echo "<p class=Err>Who is in the band?\n"; $err=1; };
    if (strlen($_POST['Contact']) < 6) { echo "<p class=Err>Please give the contact name\n"; $err=1; };
    Clean_Email($_POST{'Email'});
    if (strlen($_POST['Email']) < 6) { echo "<p class=Err>Please give the contacts Email\n"; $err=1; };
    if (strlen($_POST['Phone']) < 6) { echo "<p class=Err>Please give the contacts Phone number\n"; $err=1; };
    if (strlen($_POST['Address']) < 20) { echo "<p class=Err>Please give the contacts Address\n"; $err=1; };
    if (!$_POST['FolkFest']) { echo "<p class=Err>Please indicate if you could do a 30 minute set at the festival\n"; $err=1; };
    if (!$err) {
//      echo "<P>VALID...<P>";
      $_POST['AccessKey'] = rand_string(40);
      $_POST['Year'] = $PLANYEAR;
      $_POST['Activity'] = $_POST['LnlCat'];
      $id = Insert_db_post('SignUp',$lnl);
    
      Email_Signup($lnl,'LNL_Application',$lnl['Email']);
      Email_Signup($lnl,'LNL_Nathan','nathanpotter89@hotmail.co.uk');
      
      echo "<h2 class=subtitle>Thankyou for submitting your application</h2>";
      dotail();
      exit();
    }
  }

  
  echo "<h2 class=subtitle>Live And Loud 2018 Application Form</h2>\n";
  echo "<form method=post action=LiveNLoudForm.php>";
  echo "<table border>\n";
  echo "<tr>" . fm_radio('Category',$lnlclasses,$_POST,"LnlCat",'',2) . "<td colspan=2>Choose the category of the majority of the members, in doubt select older\n";
  echo "<tr>" . fm_text1("Band/Group/Act Name",$_POST,'SName',2);
  echo fm_text1('Style of Music',$_POST,'Style');
  echo "<tr><td colspan=2>Band/Act Members, or significant members of large groups\n";
  echo fm_text1("Total Size of Band/Act if more than 6",$_POST,'TotalSize',2);
  echo "<tr><td>Name<td>Instrument<td>Name<td>Instrument\n";
  for ($i=1;$i<=6;$i++) {
    echo (($i&1)?"<tr>":""); 
    echo fm_text1($i,$_POST,"Name$i") . fm_text1('',$_POST,"Instr$i");
  }
  echo "<tr><td colspan=4>Main Contact:\n";
  echo "<tr>" . fm_text('SName',$_POST,'Contact');
  echo "<tr>" . fm_text('Email',$_POST,'Email');
  echo "<tr>" . fm_text('Phone',$_POST,'Phone');
  echo "<tr>" . fm_text('Address',$_POST,'Address',4);
  echo "<tr>" . fm_text('Postcode',$_POST,'PostCode');
  echo "<tr>" . fm_text('Song Titles',$_POST,'Songs',3);
  echo " (It does not matter if you choose to perform different songs on the night)";
  echo "<tr>" . fm_text('Equipment Needed',$_POST,'Equipment',3) . 
                " Drum Kit, Mics &amp; Mic Stands, Sound Desk, Amps, Lighting and Tech support will be provided.";
  echo "<tr><td colspan=2>Are you able to perform a 30 minute set at the Folk Festival?<td>" . fm_radio('',$yesno,$_POST,'FolkFest','',0);
  echo "<tr><td colspan=2>Are you available on Friday 8th June, during the Folk Festival?<td>" . fm_radio('',$yesno,$_POST,'FFFri','',0);
  echo "<tr><td colspan=2>Are you available on Saturday 9th June, during the Folk Festival?<td>" . fm_radio('',$yesno,$_POST,'FFSat','',0);
  echo "<tr><td colspan=2>Are you available on Sunday 10th June, during the Folk Festival?<td>" . fm_radio('',$yesno,$_POST,'FFSun','',0);
  echo "</table><p>";
  echo "<input type=submit name=submit value='Submit Application' onclick=$('#Patience').show()><p>\n";   
  echo "<h2 hidden class=Err id=Patience>This takes a few moments, please be patient</h2>";

  dotail();

?>
