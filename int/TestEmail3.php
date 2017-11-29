<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("Email Test");
  SendEmail("richardjproctor42@gmail.com","Test Email",'Test Message');

  dotail();
?>
