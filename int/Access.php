<?php
// Direct improved....

  include_once("fest.php");
  global $USER,$USERID;
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("TradeLib.php");
  include_once("SignupLib.php");
  include_once("ProgLib.php");
  include_once("VolLib.php");

  if ( !isset($_GET['i']) || !isset($_GET['k']) || !isset($_GET['t'])) Error_Page("Invalid link"); // No return

  $id = $_GET['i'];
  $key = $_GET['k'];
  $t = $_GET['t'];

// Hacking prevention
  if (strlen($id)>6 || strlen($key)!=40 || strlen($t)>6 || preg_match('/[^A-Z]/',$key) || !is_numeric($id) ) Error_Page("Invalid Access link");
//var_dump($_REQUEST);
  switch ($t) {
    case 's' : // Side
    case 'a' : // Act
    case 'o' : // Other
      $Data = Get_Side($id);
      if (isset($_REQUEST['TB'])) $_POST['TICKBOX'] = $_REQUEST['TB'];
      break;

    case 't' : // Trader
      $Data = Get_Trader($id);
      break;

    case 'w' : // Steward
      $Data = Get_Steward($id);
      break;

    case 'v' : // Volunteer
      $Data = Get_Volunteer($id);
      $_REQUEST['A'] = 'View';
      $_REQUEST['id'] = $id;
      break;

    case 'u' : // Sign Up
      $Data = Get_SignUp($id);
      break;
      
    case 'p' : // Sound Engineers for venues
      $Data = Get_Venue($id);
      $_REQUEST['pa4v'] = $id;
      break;

    case 'c' : // Staff - not yet
      $Data = Get_User($id);
      break;
      
    case 'ART' :  // ART
      $Data = Get_SignUp($id);
      break;
      
    default:
      Error_Page("Invalid link - type XX $t not recognised");
  }

  if ($Data['AccessKey'] != $key) Error_Page("Sorry - This is not the right key");

  $CakeTypes = ['s'=>'Side','a'=>'Act','o'=>'Other','t'=>'Trader','w'=>'Steward','v'=>'Volunteer','u'=>'SignUp','c'=>'Staff','p'=>'Venue','ART'=>'SignUp'];// Not Sure on staff
  $includes = ['s'=>'AddPerf.php','a'=>'AddPerf.php','o'=>'AddPerf.php','t'=>'TraderPage.php','w'=>'ViewStew.php',
               'v'=>'Volunteers.php','u'=>'SignUp','c'=>'Staff','p'=>'PAShow.php','ART'=>'ArtForm.php'];  
  $DoHead = ['s'=>1,'a'=>1,'o'=>1,'t'=>1,'w'=>1,'v'=>0,'u'=>1,'c'=>1,'p'=>1,'ART'=>1];

  $Cake = sprintf("%s:%d:%06d",$CakeTypes[$t],$Access_Type['Participant'],$id ); 
  $biscuit = openssl_encrypt($Cake,'aes-128-ctr','Quarterjack',0,'BrianMBispHarris');
  setcookie('WMFFD',$biscuit,0,'/');

  $USER{'AccessLevel'} = $Access_Type['Participant'];
  $USER{'Subtype'} = $CakeTypes[$t];
  $USER{'UserId'} = $USERID = $id;

  $_GET['id'] = $id;
  if ($DoHead[$t]) dostaffhead($CakeTypes[$t],["/js/clipboard.min.js", "/js/emailclick.js", "/js/Participants.js"]);
  include_once($includes[$t]); // Should not return
  dotail();
?>
