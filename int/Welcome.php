<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("Welcome");
  include_once("UserLib.php");
  include_once("Email.php");
  
  global $FESTSYS;

  if (isset($_GET['U'])) {
    $uid = $_GET['U'];
    $User = Get_User($uid);

    if (!$User['Email']) {
      Error_Page('No Email Set up for ' . $User['SN']);
    };
    $newpwd = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 10 );
    $hash = crypt($newpwd,"WM");
    $User['password'] = $hash;
    Put_User($User);
    $User['ActualPwd'] = $newpwd; // Not stored
 
    $subject = "Welcome " . firstword($User['SN']) . " to " . $FESTSYS['ShortName'] . " Staff pages";
    $letter = Email_Proforma($User['Email'],'Login_Welcome',$subject,'Login_Details',$User,'LoginLog.txt');
    echo "Email sent:<p>$letter";
  } else {
    echo "No user..."; 
  }
  dotail();
?>
