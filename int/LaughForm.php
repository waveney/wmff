<?php
  include("fest.php");

  dostaffhead("Laugh Out Load Application", "/js/Participants.js");

  include_once("SignupLib.php");
  global $USER,$USERID,$db,$THISYEAR;

  /* In the longer term this will be based on participants, but I want to do this quickly for 2018 so it is stand alone for now */

  if (isset($_POST['submit'])) {
    if (strlen($_POST['SName']) < 2) { echo "<p class=Err>Please give your act's name\n"; $err=1; };
    if (strlen($_POST['Contact']) < 6) { echo "<p class=Err>Please give the contact name\n"; $err=1; };
    Clean_Email($_POST{'Email'});
    if (strlen($_POST['Email']) < 6 || !strpos($_POST['Email'],'@')) { echo "<p class=Err>Please give the contacts Email\n"; $err=1; };
    if (strlen($_POST['Phone']) < 6) { echo "<p class=Err>Please give the contacts Phone number\n"; $err=1; };
    if (strlen($_POST['Style']) < 3) { echo "<p class=Err>Please give your comedy style\n"; $err=1; };
    if (strlen($_POST['Started']) < 4) { echo "<p class=Err>When did you start?\n"; $err=1; };
    if (!$_POST['Avail1'] && !$_POST['Avail2'] && !$_POST['Avail3']) { echo "<p class=Err>What Dates are you Available?\n"; $err=1; };
    if (!$err) {
//      echo "<P>VALID...<P>";
      $_POST['AccessKey'] = rand_string(40);
      $_POST['Year'] = $THISYEAR;
      $_POST['Activity'] = 5;
      $id = Insert_db_post('SignUp',$lol);
    
      Email_lol_Signup($lol,'lol_Application',$lol['Email']);
      Email_lol_Signup($lol,'lol_Richard','Richard@wimbornecomedy.co.uk');
      
      echo "<h2 class=bigtitle>Thankyou for submitting your application</h2>";
      dotail();
      exit();
    }
  }

  
  echo "<h2 class=subtitle>Laugh Out Load 2018 Application Form</h2>\n";
  echo "<form method=post action=LaughForm.php>";
  echo "<table border>\n";
  echo "<tr>" . fm_text("Act Name",$_POST,'SName',2);
  echo "<tr><td colspan=4>Main Contact:\n";
  echo "<tr>" . fm_text('Name',$_POST,'Contact');
  echo "<tr>" . fm_text('Email',$_POST,'Email');
  echo "<tr>" . fm_text('Phone',$_POST,'Phone');
  echo "<tr>" . fm_text('Comedy style',$_POST,'Style');
  echo "<tr>" . fm_text('The date at which you started performing comedy',$_POST,'Started');
  echo "<br>(must be after Jan 1st 2013)";
  echo "<tr>" . fm_text('Any relevant biographical info, eg gigs, clubs and festivals (optional)',$_POST,'Bio',8);
  echo "<tr>" . fm_text('Example of you playing - YouTube or equivalent (optional)',$_POST,'Example');
  echo "<tr>" . fm_text('Any Equipment needed (optional)',$_POST,'Equipment',4); 
  echo "<br>Which for practical purposes should be kept as simple as possible (e.g. guitar input to PA, laptop/tablet link, etc). ";
  echo "We'll be as supportive as possible but if you don't ask at entry, you may well not get what you need!";

  echo "<tr><td>Available on Tuesday March 6th?<td>" . fm_checkbox('',$_POST,'Avail1');
  echo "<tr><td>Available on Tuesday April 10th?<td>" . fm_checkbox('',$_POST,'Avail2');
  echo "<tr><td>Available on Tuesday May 1st?<td>" . fm_checkbox('',$_POST,'Avail3');
  echo "</table><p>";
  echo "<input type=submit name=submit value='Submit Application' onclick=$('#Patience').show()><p>\n";   
  echo "<h2 hidden class=Err id=Patience>This takes a few moments, please be patient</h2>";

  echo "Successful entrants will be emailed by the end of Sunday 4th March 2018 and asked to confirm their attendance at the heat offered.<p>";
  echo "Valid entrants who are not initially offered a place will be emailed to that effect as soon as practically possible. ";
  echo "They will also go on a reserve list, in case successful entrants give notice of withdrawal or are disqualified from entry.<p>";

  echo "In any dispute or interpretation of these rules, the final decision will be with the Committee and Officials of the Wimborne Minster Folk Festival. ";
  echo "Their decision is final.<p>";

  dotail();

?>
