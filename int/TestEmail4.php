<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("Email Test");

function Sendmail($to,$sub,$cont) {
  include_once("Mail.php");

  $username = "wwmf@wimbornefolk.co.uk";
  $password = "Sj2$3j4t";
  $email_from = "wwmf@wimbornefolk.co.uk";

$headers = array ('From' => $email_from, 'To' => $to, 'Subject' => $email_subject);
$smtp = Mail::factory('smtp', 
    array ('auth' => true, 
           'username' => $username ,
           'password' => $password, 
           'auth' => "PLAIN",
           'socket_options' => array('ssl' => array('verify_peer_name' => false))
          ),
  );
$mail = $smtp->send($to, $headers, $cont);

  if (PEAR::isError($mail)) {
    echo("<p>" . $mail->getMessage() . "</p>");
  } else {
    echo("<p>Message successfully sent!</p>");
  }
}  

  Sendmail("richard@wavwebs.com","Test Email",'Test Message');

  dotail();
?>
