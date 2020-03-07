<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("Email Test");
  NewSendEmail(0,0,"richardjproctor42@gmail.com","Test Email",'Test Message');

  dotail();
?>
