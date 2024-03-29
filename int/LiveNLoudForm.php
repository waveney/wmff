<?php
  include_once("fest.php");

  dostaffhead("Live and Loud Application", ["/js/Participants.js"]);

  include_once("SignupLib.php");
  include_once("InvoiceLib.php");
  global $USER,$USERID,$db,$PLANYEAR,$SignupStates,$SignupStateColours;

  if (0 && !Access('Staff')) {
    echo "<h2>Applications are closed</h2>";
    dotail();
  }

  $id = -1;
 /* In the longer term this will be based on participants, but I want to do this quickly for 2018 so it is stand alone for now */
//var_dump($_POST);
  if (isset($_POST['submit'])) {
    $err = 0;
    if (!isset($_POST['LnlCat']) || $_POST['LnlCat']<1) { echo "<p class=Err>Please select a competition category\n"; $err=1; };
    if (strlen($_POST['SN']) < 2) { echo "<p class=Err>Please give your band's name\n"; $err=1; };
    if (strlen($_POST['Style']) < 2) { echo "<p class=Err>Please give your band's music style\n"; $err=1; };
    if (!($_POST['TotalSize'] || strlen($_POST['Name1'])>2)) { echo "<p class=Err>Who is in the band?\n"; $err=1; };
    if (strlen($_POST['Contact']) < 6) { echo "<p class=Err>Please give the contact name\n"; $err=1; };
    Clean_Email($_POST{'Email'});
    if (strlen($_POST['Email']) < 6) { echo "<p class=Err>Please give the contacts Email\n"; $err=1; };
    if (strlen($_POST['Phone']) < 6) { echo "<p class=Err>Please give the contacts Phone number\n"; $err=1; };
    if (strlen($_POST['Address']) < 20) { echo "<p class=Err>Please give the contacts Address\n"; $err=1; };
    if (!isset($_POST['FolkFest'])) { echo "<p class=Err>Are you available to attend both the audition day and Live Final on Sunday 14th April?\n"; $err=1; };
    if (!isset($_POST['FFSat'])) { echo "<p class=Err>Please indicate if you could do a 30 minute set at the festival\n"; $err=1; };
    if (!isset($_POST['Example'])) { echo "<p class=Err>Please provide a video link (Youtube etc)\n"; $err=1; };
    if (strlen($_POST['Bio']) < 20) { echo "<p class=Err>Please give a Bio\n"; $err=1; };
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
  } else if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $data = Get_Signup($id);
    Update_db_post('SignUp',$data);
    $_POST = $data;
  } else if (isset($_POST['ACTION'])) {
    $id = $_POST['id'];
    $action = $_POST['ACTION'];
    LNL_Action($action,$id);
    $_POST = Get_Signup($id);
  } else if (isset($_GET['i'])) {
    $id = $_GET['i'];
    $_POST = Get_Signup($id);

  }


  echo "<h2 class=subtitle>Live And Loud " . substr($PLANYEAR,0,4) . " Application Form</h2>\n";
  echo "<form method=post action=LiveNLoudForm>";
  if (isset($id) && $id>0) echo fm_hidden('id',$id);
  echo "<div class=tablecont><table border>\n";
  echo "<tr>" . fm_radio('Category',$lnlclasses,$_POST,"LnlCat",'',2) . "<td colspan=2>Choose the category of the majority of the members, in doubt select older\n";
  echo "<tr>" . fm_text1("Band/Group/Act Name",$_POST,'SN',2);
  echo fm_text1('Style of Music',$_POST,'Style');
  echo "<tr><td colspan=2>Band/Act Members, or significant members of large groups\n";
  echo fm_text1("Total Size of Band/Act if more than 6",$_POST,'TotalSize',2);
  echo "<tr><td>Name<td>Instrument<td>Name<td>Instrument\n";
  for ($i=1;$i<=6;$i++) {
    echo (($i&1)?"<tr>":""); 
    echo fm_text1($i,$_POST,"Name$i") . fm_text1('',$_POST,"Instr$i");
  }
  if (isset($_POST['State'])) echo "<tr>" . fm_radio("Booking State",$SignupStates,$_POST,'State','',1,'','',$SignupStateColours);
  echo "<tr><td colspan=4>Main Contact:\n";
  echo "<tr>" . fm_text('Name',$_POST,'Contact');
  echo "<tr>" . fm_text('Email',$_POST,'Email');
  echo "<tr>" . fm_text('Phone',$_POST,'Phone');
  echo "<tr>" . fm_text('Address',$_POST,'Address',4);
  echo "<tr>" . fm_text('Postcode',$_POST,'PostCode');
  echo "<tr>" . fm_text('2 Song Titles',$_POST,'Songs',3);
  echo " (It does not matter if you choose to perform different songs on the night)";
  echo "<tr>" . fm_text('Equipment Needed',$_POST,'Equipment',3) . 
                " Mics &amp; Mic Stands, Sound Desk, Amps, Lighting and Tech support will be provided.<br>PLEASE NOTE that if you require the use of a drum kit, " .
                "you will need to provide this yourselves)";
  echo "<tr><td colspan=2>Are you available to attend both the audition day and Live Final on Sunday 14th April?<td>" . fm_radio('',$yesno,$_POST,'FolkFest','',0);
//  echo "<tr><td colspan=2>Are you available on Friday 8th June, during the Folk Festival?<td>" . fm_radio('',$yesno,$_POST,'FFFri','',0);
  echo "<tr><td colspan=2>Are you available to perform a 30 minute set on Saturday 8th June, during the Folk Festival?<td>" . fm_radio('',$yesno,$_POST,'FFSat','',0);
//  echo "<tr><td colspan=2>Are you available on Sunday 10th June, during the Folk Festival?<td>" . fm_radio('',$yesno,$_POST,'FFSun','',0);
  echo "<tr><td colspan=4><b>Bio</b><br>
Please compose up to 150 words to describe what you do. This information will be
used to introduce you to the audience. You may wish to include:<br>
<ul><li>A description of the style of music that you play
<li>References to musicians who inspire you
<li>Whether your songs are originals or covers
<li>Age of the performers in your act and how long you’ve been working together
<li>How regularly you gig and any notable places you’re performed
<li>What you hope to achieve with your music</ul>";
  echo fm_basictextarea($_POST,'Bio',4,4);
  echo "<tr>" . fm_text("Social Media link(s)",$_POST,'Social');
  echo "<tr>" . fm_text("Video link(s)",$_POST,'Example') . "<td colspan=2>(This is what we will use to decide your suitability for the event)";
  echo "</table></div><p>";
  if (!Access('Staff') && $id > 0) {
    echo "<input type=submit name=update value='Update Application'>" . SignupActions('LNL',$_POST['State']);
  } else {
    echo "<input type=submit name=submit value='Submit Application' onclick=$('#Patience').show()><p>\n";   
    echo "<h2 hidden class=Err id=Patience>This takes a few moments, please be patient</h2>";
  }
  echo "</form>";
 
  echo "<h2><a href=LiveNLoudView>Back to List of applications</a></h2>";
  dotail();

?>
