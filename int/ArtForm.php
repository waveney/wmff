<?php
  include_once("fest.php");

  dostaffhead("Art Application", ["/js/Participants.js"]);

  include_once("SignupLib.php");
  include_once("InvoiceLib.php");
  global $USER,$USERID,$db,$PLANYEAR,$SignupStates,$SignupStateColours,$ArtClasses,$ArtValues;

/*  if (!Access('Staff')) {
    echo "<h2>Applications are closed</h2>";
    dotail();
  } */



  $id = -1;
 /* In the longer term this will be based on participants, but I want to do this quickly for 2018 so it is stand alone for now */
//var_dump($_POST);
  if (isset($_POST['submit'])) {
    $err = 0;
    if (strlen($_POST['SN']) < 2) { echo "<p class=Err>Please give your name\n"; $err=1; };
    Clean_Email($_POST{'Email'});
    if (strlen($_POST['Email']) < 6) { echo "<p class=Err>Please give your Email\n"; $err=1; };
    if (strlen($_POST['Phone']) < 6) { echo "<p class=Err>Please give your Phone number\n"; $err=1; };
    if (strlen($_POST['Address']) < 20) { echo "<p class=Err>Please give your Address\n"; $err=1; };
    if (!isset($_POST['Tickbox1'])) { echo "<p class=Err>Are you displaying Art, selling Art or both?\n"; $err=1; };
    if (!isset($_POST['Instr1'])) { echo "<p class=Err>Are you a member of any Art Clubs/Societies?\n"; $err=1; };
    if (!isset($_POST['Tickbox2'])) { echo "<p class=Err>Is this a hobby or a profession?\n"; $err=1; };
    if (!isset($_POST['Style']) || strlen($_POST['Style'])<2) { echo "<p class=Err>Describe your genre of art\n"; $err=1; };
    if (!isset($_POST['Tickbox3'])) { echo "<p class=Err>Inside or Outside?\n"; $err=1; };

    if (!$err) {
//      echo "<P>VALID...<P>";
      $_POST['AccessKey'] = rand_string(40);
      $_POST['Year'] = $PLANYEAR;
      $_POST['Activity'] = 5;
      $_POST['State'] = 0;
      $id = Insert_db_post('SignUp',$art);
    
      ART_Email_Signup($art,'ART_Application',$art['Email']);
      ART_Email_Signup($art,'ART_Debbie','art@wimbornefolk.co.uk');
      
      echo "<h2 class=subtitle>Thankyou for submitting your application</h2>";
      if (Access('Staff')) echo "<h2><a href=ArtView>Back to List of applications</a></h2>";
      dotail();
      exit();
    }
  } else if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $art = Get_Signup($id);
    Update_db_post('SignUp',$art);
    if (!Access('Staff')) ART_Email_Signup($art,'ART_Debbie_Update','art@wimbornefolk.co.uk');    
    $_POST = $art;
  } else if (isset($_POST['ACTION'])) {
    $id = $_POST['id'];
    $action = $_POST['ACTION'];
    ART_Action($action,$id);
    $_POST = Get_Signup($id);
  } else if (isset($_GET['i'])) {
    $id = $_GET['i'];
    $_POST = Get_Signup($id);

  }

  echo "<h2 class=subtitle>Art " . substr($PLANYEAR,0,4) . " Application Form</h2>\n";
  echo "<form method=post action=ArtForm>";
  if (isset($id) && $id>0) echo fm_hidden('id',$id);
  echo "<div class=tablecont><table border>\n";
  if (isset($_POST['State'])) {
    if (Access('SysAdmin')) {
      echo "<tr>" . fm_radio("Booking State",$SignupStates,$_POST,'State','',1,'colspan=4','',$SignupStateColours);
    } else {
      echo "<tr><td>Booking State<td>" . $SignupStates[$_POST['State']];
    }
  }
  echo "<tr>" . fm_text('Name',$_POST,'SN',2);
  echo "<tr>" . fm_text('Email',$_POST,'Email',2);
  echo "<tr>" . fm_text('Phone',$_POST,'Phone');
  echo "<tr>" . fm_text('Address',$_POST,'Address',4);
  echo "<tr>" . fm_text('Website (if you have one)',$_POST,'Website',2);
  echo "<tr>" . fm_text('Age (if under 18)',$_POST,'Age');
  echo "<tr>" . fm_text('Are you a member of any Art clubs/societies',$_POST,'Instr1',2);
  echo "<tr>" . fm_text('Do you have any disabilities',$_POST,'Instr2',2);
  echo "<tr>" . fm_radio('Are you',$ArtClasses,$_POST,'Tickbox1') . "<td colspan=3>There will be a &pound;25 charge if you are selling";
  echo "<tr>" . fm_text('What would you like to achieve by displaying art at Art @ the  Folk Festival',$_POST,'Instr3',4);  
  echo "<tr>" . fm_radio('Is this a',$ArtValues,$_POST,'Tickbox2');
  echo "<tr>" . fm_text('Describe your genre of art',$_POST,'Style',4);  
  echo "<tr>" . fm_radio('Do you require a stall',$ArtPosition,$_POST,'Tickbox3');  
  echo "<tr>" . fm_text('Are you prepared to deliver an hour’s workshop to the public',$_POST,'Instr4',3) . "<td>The festival may be prepared to assist with any reasonable expenses for products";
  echo "<tr>" . fm_text("Social Media link(s)",$_POST,'Social',4);
  if (Access('SysAdmin')) {
    if ($id>0) echo "<tr><td class=NotSide>" . $_POST['AccessKey'] . "<td><a href=Access?i=$id&t=ART&k=" . $_POST['AccessKey'] . ">Use</a>" . help('Testing');
  }
  echo "</table></div><p>";
  if ( $id > 0) {
    echo "<input type=submit name=update value='Update Application'>";
    if (Access('Staff')) {
      echo SignupActions('ART',$_POST['State']);
    } else {
    
    }
  } else {
    echo "Please submit your application, you can update it at any time.<p>  You will be notified by email if you are sucessful, and be asked to pay any fees if you are selling<p>";
    echo "<input type=submit name=submit value='Submit Application' onclick=$('#Patience').show()><p>\n";   
    echo "<h2 hidden class=Err id=Patience>This takes a few moments, please be patient</h2>";
  }
  echo "</form>";
 
  if (Access('Staff')) echo "<h2><a href=ArtView>Back to List of applications</a></h2>";
  dotail();

?>
