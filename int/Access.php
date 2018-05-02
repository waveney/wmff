<?php
// Direct improved....

  include("fest.php");
  global $USER,$USERID;
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("TradeLib.php");
  include_once("SignupLib.php");


  if ( !isset($_GET['i']) || !isset($_GET['k']) || !isset($_GET['t'])) Error_Page("Invalid link"); // No return

  $id = $_GET['i'];
  $key = $_GET['k'];
  $t = $_GET['t'];

// Hacking prevention
  if (strlen($id)>6 || strlen($key)!=40 || strlen($t)!=1 || preg_match('/[^A-Z]/',$key) || !is_numeric($id) ) Error_Page("Invalid link");

  switch ($t) {
    case 's' : // Side
    case 'a' : // Act
    case 'o' : // Other
      $Data = Get_Side($id);
      break;

    case 't' : // Trader
      $Data = Get_Trader($id);
      break;

    case 'w' : // Steward
      $Data = Get_Steward($id);
      break;

    case 'u' : // Sign Up
      $Data = Get_SignUp($id);
      break;

    case 'c' : // Staff - not yet
    default:
      Error_Page("Invalid link - type not recognised");
  }

  if ($Data['AccessKey'] != $key) Error_Page("Sorry - This is not the right key");

  $CakeTypes = ['s'=>'Side','a'=>'Act','o'=>'Other','t'=>'Trader','w'=>'Steward','u'=>'SignUp','c'=>'Staff'];// Not Sure on staff
  $includes = ['s'=>'DanceEdit.php','a'=>'MusicEdit.php','o'=>'MusicEdit.php','t'=>'TraderPage.php','w'=>'ViewStew.php','u'=>'SignUp','c'=>'Staff'];// Not Sure on staff

  $Cake = sprintf("%s:%d:%06d",$CakeTypes[$t],$Access_Type['Participant'],$id ); 
  $biscuit = openssl_encrypt($Cake,'aes-128-ctr','Quarterjack',0,'BrianMBispHarris');
  setcookie('WMFFD',$biscuit,0,'/');

  $USER{'AccessLevel'} = $Access_Type['Participant'];
  $USER{'Subtype'} = $CakeTypes[$t];
  $USER{'UserId'} = $USERID = $id;

  $_GET['id'] = $id;
  dostaffhead($CakeTypes[$t]);
  include($includes[$t]); // Should not return
  dotail();
?>
