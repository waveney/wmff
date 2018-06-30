<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("Welcome");
  include_once("UserLib.php");

  if (isset($_GET['U'])) {
    $uid = $_GET['U'];
    $User = Get_User($uid);

    if (!$User['Email']) {
      Error_Page('No Email Set up for ' . $User['SName']);
    };
    $newpwd = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 10 );
    $hash = crypt($newpwd,"WM");
    $User['password'] = $hash;
    Put_User($User);

    $letter = firstword($User['SName']) . "<p>Welcome to the Wimborne Minster Folk Festival staff pages.<p>" .
        "It is initially accessed by using the <a href=https://wimbornefolk.co.uk/int/Login.php>Login</a> at the bottom of any page below " .
        "the copyright statement on any page of the <a href=https://wimbornefolk.co.uk>website</a>.<p>" .
        "Your username is : " . $User['Login'] ."<br>" .
        "Initial password : $newpwd<p>" .
        "When you are logged in, an extra tab will apear on the navigation bar 'Staff Tools' this gives access to the database and ".
        "document storage.<p>" .
        "Everyone can use the document storage.  To save any files for use by the festival.  <p>" .
        "Everyone can use the simple reporting of problems and requesting features.<p>" .
        "Access to other areas is restricted, most things can be read by everbody, " .
        "but the creation and editing is restricted to relevant people.<p>" .
        "Dance is ready for full use, as are most features of Music, Trade, News and Sponsors.<p>" .
        "If something is not obvious please tell me and I will try and improve it.<p>" .
        "Richard";
 
    SendEmail($User['Email'],"Welcome " . firstword($User['SName']) . " to WMFF Staff pages",$letter);

    echo "Email sent:<p>$letter";
  } else {
    echo "No user..."; 
  }
  dotail();
?>
