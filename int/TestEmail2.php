<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("Test Email");
  include_once("UserLib.php");


//    mail("richard@wavwebs.com","Test Email",'Test Message 2');
    mail("richardjproctor42@gmail.com","Test Email",'Test Message 2');

    echo "Email sent:<p>$letter";
  dotail();
?>
