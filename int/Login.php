<?php
/* 
 * Everything to do with logining in, new passwords etc
 * Many mini pages some of which are used in several places
 *
 */

  include_once("fest.php"); 
  include_once("Email.php"); 
  include_once("UserLib.php");
    
function Logon(&$use=0) {
  global $YEAR,$USER,$USERID,$CALYEAR;
  $Rem = 0;
  if (!$use) {
    $user = $_POST{'UserName'};
    $pwd = $_POST{'password'};
    if (isset($_POST{'RememberMe'})) $Rem = $_POST{'RememberMe'};
    $ans = Get_User($user);
  }
  if ($use || $ans) { // using crypt rather than password_hash so it works on php 3.3
    if (!$use && $ans) {
      $cry = crypt($pwd,'WM');
      if ($cry != $ans['password']) {
        setcookie('WMFF2','',-1,'/');
        return "Username/Password Error";
      }
    } else {
      $ans = $use;
    }
    if ($ans['AccessLevel']) {
      $ans['Yale'] = rand_string(40);
      setcookie('WMFF2',$ans['Yale'],($Rem ? mktime(0,0,0,1,1,$CALYEAR+1) : 0),'/' );
      $_COOKIE{'WMFF2'} = $ans['Yale'];
      Put_User($ans);
      $USER=$ans;
      $USERID = $USER{'UserId'};
      include_once ("Staff.php"); // no return wanted
      exit;
    }
    Login("$user no longer has access");
  }
  return "User not known";

}

function Forgot() {
  global $FESTSYS;
  $rand_hash = rand_string(40);
  $user = $_POST{'UserName'};
  if (strlen($user) > 2) {
    if ($ans = Get_User($user)) {
      if ($ans['UserId'] > 9 ) { 
        if ($ans['AccessLevel'] == 0) return "You no longer have access";
        $ans['ChangeSent'] = time();
        $ans['AccessKey'] = $rand_hash;
        Put_User($ans);

        Email_Proforma(4,$ans['UserId'],$ans['Email'],'Login_Forgot_Password', $FESTSYS['FestName'] . " Staff Access for " . $ans['SN'],'Login_Details',$ans,'LoginLog.txt');
        return "A limited use link has been emailed to you";
      }
    }
  }
  return "Username/Password Error";
}

function Set_Password($user,$msg='') {
  dostaffhead("Set Password");

  $ans = Get_User($user);
// var_dump($ans);exit;
  if ($ans) {
    if ($ans['ChangeSent']+36000 < time()) {
      $rand_hash = rand_string(40);
      $ans['ChangeSent'] = time();
      $ans['AccessKey'] = $rand_hash;
      Put_User($ans);
    } else {
      $rand_hash = $ans['AccessKey'];
    }
 
    if ($msg) echo "<h2 class=ERR>$msg</h2>\n";
    echo "Min length is 6.<p>";
    echo "<form method=post action=Login>";
    echo "<div class=tablecont><table>";
    echo "<tr><td>Password:<td><input type=password Name=password>\n";
    echo fm_hidden('UserId',$user) . fm_hidden('AccessKey',$rand_hash);
    echo "<tr><td>Confirm:<td><input type=password Name=confirm>\n";
    $_POST{'RememberMe'} = 1;
    echo "<tr><td>" . fm_checkbox("Remember Me",$_POST,'RememberMe') . "</table></div><p>\n";
    echo "<input type=submit Name=ACTION value='Set New Password'><p>\n";
    echo "</form></div>\n";

    dotail();
    exit;
  }
  return "User $user not known";
}

function Limited() {
  $who = $_GET{'U'};
  $hash = $_GET{'A'};

  if ($ans = Get_User($who)) {
    if ($ans['AccessKey'] == $hash && ($ans['ChangeSent']+36000 > time())) {
      return Set_Password($ans['UserId']);
    }
  } else {
    return "Limited use Username/Password Error";
  }
}

function Login($errmsg='', $message='') {
  global $db,$USER,$AccessType;
  Set_User();
  if (isset($USER)  && $USER && $USER{'AccessLevel'} > $AccessType['Participant']) include_once ("Staff.php");

  dostaffhead("Staff Login");
  if ($errmsg) echo "<h2 class=ERR>$errmsg</h2>";
  if ($message) echo "<h2>$message</h2>";

  echo "<form method=post action=Login>";
  echo "<div class=tablecont><table class=simpletable><tr><td>User Name or Email:<td><input type=text Name=UserName>\n";
  echo "<tr><td>Password:<td><input type=password Name=password>\n";
  $_POST{'RememberMe'} = 1;
  echo "<tr><td>" . fm_checkbox("Remember Me",$_POST,'RememberMe');
  echo "</table></div>\n";
  echo "<p><input type=submit Name=ACTION value=Logon><p>\n";

  echo "<input type=submit Name=ACTION value='Lost your password'>\n";
  echo "</form></div>\n";

  dotail();
  exit;
}

function NewPasswd() {
  global $YEAR,$USER,$USERID,$CALYEAR;
  $user = $_POST{'UserId'}; 
  if (!$user) $user = $USERID;
  if ($ans = Get_User($user) ) {
    if ($ans['AccessKey'] == $ans['AccessKey']) {
      if ($ans['ChangeSent']+36000 > time()) {
        if ($_POST{'password'} == $_POST{'confirm'}) {
          if (strlen($_POST{'password'}) > 5) { // using crypt rather than password_hash so it works on php 3.3
            $hash = crypt($_POST{'password'},"WM");
            $ans['password'] = $hash;
            $ans['Yale'] = rand_string(40);
            $USER = $ans;
            $USERID = $user;
            setcookie('WMFF2',$ans['Yale'],($_POST{'RememberMe'} ? mktime(0,0,0,1,1,$CALYEAR+1) : 0 ),'/');
                  Put_User($ans);
                 include ("Staff.php"); // no return wanted
            exit;
          }
          Set_Password($user,"Password too short");
        }
        Set_Password($user,"Password and Confirm did not match");
      }
      Login("Link timed out");
    }
    Login("Link invalid ");
  }
  Login("User not known");
}

/* MAIN CODE HERE */
  global $USERID,$CONF;
  Set_User();
  if(!isset($_GET{'ACTION'})) {
    if (!isset($_POST{'ACTION'})) Login(); // No Return
    $act = $_POST{'ACTION'};
  } else {
    $act = $_GET{'ACTION'};
  }

//  echo "<!-- " . var_dump($act) . " -->\n";
  switch ($act) {
    case 'Login' :
      Login(); // No Return
    case 'Logon' :
      Login(Logon()); // No Return    
    case 'LOGOUT' :
      $USER = 0;
      setcookie('WMFF2',0,1,'/');
      if (@ $CONF['testing']) Login();
      include_once("../index.php"); 
      exit;
    case 'LIMITED' :
      Login(Limited()); // No Return;
    case 'Set New Password' :
      Login(NewPasswd()); // No Return;
    case 'NEWPASSWD' :
      Login(Set_Password($USERID));
    case 'Lost your password' :
      Login(Forgot());
  }
  echo "Should not get here  $act ...";
?>
