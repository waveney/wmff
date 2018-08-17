<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';


function NewSendEmail($to,$sub,&$letter,&$attachments=0) { //$to can be single address, a [address, name] or [[to,address,name],[cc,addr,name],bcc,addr,name],replyto,addr,name]...]
  global $MASTER_DATA;
  if (0 && file_exists("testing")) {
    echo "<p>Would send email to $to with subject: $sub<p>Content:<p>$letter<p>\n";
    return;
  }
  
  $email = new PhpMailer(true);
  try {
    $email->SMTPDebug = 4;
    $email->isSMTP();
    $email->Host = $MASTER_DATA['HostURL'];
    $email->SMTPAuth = true;
    $email->AuthType = 'LOGIN';
    $email->From = $email->Username = $MASTER_DATA['SMTPuser'] . "@" . $MASTER_DATA['HostURL'];
    $email->Password = $MASTER_DATA['SMTPpwd'];
    $email->SMTPSecure = 'tls';
    $email->Port = 587;
    $email->SMTPOptions = ['ssl' => [ 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
    
    if (is_array($to)) {
      if (is_array($to[0])) {
        foreach ($to as $i=>$too) {
          $a = $too[1];
          $n = (isset($too[2])?$too[2]:'');
          switch ($too[0]) {
            case 'to':
              $email->addAddress($a,$n);
              break;
            case 'cc':
              $email->addCC($a,$n);
              break;
            case 'bcc':
              $email->addBCC($a.$n);
              break;
            case 'replyto':
              $email->addReplyTo($a,$n);
              break;
            case 'from':
              $email->setFrom($a,$n);
              break;
          } 
        }
      } else {
        $email->addAddress($to[0],(isset($to[1])?$to[1]:''));      
      }
    } else {
      $email->addAddress($to);
    }
    $email->Subject = $sub;
    $email->Body = $letter;
//    $email->AltBody = 
    $email->Send();
  
  
  } catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $email->ErrorInfo;
  }
    
}

// Setup SMTP to outgoing mail
// Add message
// Add any attachments
// sign with DKIM
// Send and log

/*

  PHPmailer
  PDF docs
  Invoice records
  THEN do it to trade



*/
/* this below is historic test code

// Fudge to get email working I hope

function SendEmail($to,$sub,$letter) {
  $url = 'http://www.wimbornefolk.org/RemoteEmail.php';
  $data = array('TO' => $to, 'SUBJECT' => $sub, 'CONTENT'=>$letter, 'KEY' => 'UGgugue2eun23@');

  // use key 'http' even if you send the request to https://...
  $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
  );
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  if ($result === FALSE) { /* Handle error  }
}

SendEmail("richardjproctor42@gmail.com","test message","Test message via other domain");
echo "Done!";


*/

?>
