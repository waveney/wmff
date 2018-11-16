<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("Email Test");
  NewSendEmail("richardjproctor42@gmail.com","Test Email",'Test Message');

  dotail();
?>
