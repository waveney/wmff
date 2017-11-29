<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("Email Test");

function Sendmail($to,$sub,$cont) {
  include_once("Mail.php");

  $username = "wmff@wimbornefolk.co.uk";
  $password = "Zzz02j9@";
  $email_from = "wmff@wimbornefolk.co.uk";

$headers = array ('From' => $email_from, 'To' => $to, 'Subject' => $sub);
$smtp = Mail::factory('smtp', array ('auth' => true, 'username' => $username ,'password' => $password));
$mail = $smtp->send($to, $headers, $cont);

  if (PEAR::isError($mail)) {
    echo("<p>" . $mail->getMessage() . "</p>");
  } else {
    echo("<p>Message successfully sent!</p>");
  }
}  

  Sendmail("richardjproctor42@gmail.com","Test Email",'Test Message number 627B lets see if this passes the headers at gmail as well now.ZZ');
//  Sendmail("lveJpW6LTQ71P9@dkimvalidator.com","Test Email",'Test Message');

  dotail();
?>
