<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

function Pretty_Print_To($to) {
  $str = '';
  if (is_array($to)) {
    if (is_array($to[0])) {
      foreach ($to as $i=>$too) {
        $a = $too[1];
        $n = (isset($too[2])?$too[2]:'');
        switch ($too[0]) {
          case 'to':
            $str .= " to: $a &lt;$n&gt;, ";
            break;
          case 'cc':
            $str .= " cc: $a &lt;$n&gt;, ";
            break;
          case 'bcc':
            $str .= " bcc: $a &lt;$n&gt;, ";
            break;
          case 'replyto':
            $str .= " replyto: $a &lt;$n&gt;, ";
            break;
          case 'from':
            $str .= " from: $a &lt;$n&gt;, ";
            break;
        } 
      }
    } else {
      $str .= "to: " . $to[0] . (isset($to[1])? " &lt;" . $to[1] . "&gt; ":'');      
    }
  } else {
    $str .= "to: " . $to;
  }
  return $str;
}

//$to can be single address, a [address, name] or [[to,address,name],[cc,addr,name],bcc,addr,name],replyto,addr,name]...]
//$atts can be simple fie or [[file, name],[file,name]...]

function NewSendEmail($to,$sub,&$letter,&$attachments=0) { 
  global $MASTER_DATA,$CONF;
  if (@ $CONF['testing']){
    if (strstr($CONF['testing'],'@')) { 
      $to = $CONF['testing'];
    } else {    
      echo "<p>Would send email to " . Pretty_Print_To($to) . " with subject: $sub<p>Content:<p>$letter<p>\n";
    
      if ($attachments) {
        if (is_array($attachments)) {
          foreach ($attachments as $i=>$att) {
            echo "Would attachment " . $att[0] . " as " . $att[1] . "<p>";
          }                 
        } else {
          echo "Would attach $attachments<p>";       
        }
      } else {
        echo "No Attachments<p>";
      }
      return;
    }
  }
  
  $email = new PhpMailer(true);
  try {
    $email->SMTPDebug = 0;  // 2 general testing, 4 problems...
    $email->isSMTP();
    $email->Host = $MASTER_DATA['HostURL'];
    $email->SMTPAuth = true;
    $email->AuthType = 'LOGIN';
    $email->From = $email->Username = $MASTER_DATA['SMTPuser'] . "@" . $MASTER_DATA['HostURL'];
    $email->FromName = $MASTER_DATA['FestName'];
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
              $email->addBCC($a,$n);
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
    $email->isHTML(true);
    $email->Body = $letter;
//    $email->AltBody = 

    if ($attachments) {
      if (is_array($attachments)) {
        foreach ($attachments as $i=>$att) {
          $email->addAttachment($att[0],$att[1]);
        }                 
      } else {
        $email->addAttachment($attachments);       
      }
    }

    $email->Send();
    
  } catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $email->ErrorInfo;
  }
}

function Get_Email_Proformas() { 
  global $db;
  $res = $db->query("SELECT * FROM EmailProformas ORDER BY SN ");
  if ($res) while ($typ = $res->fetch_assoc()) $full[$typ['id']] = $typ;
  return $full;
}

function Get_Email_Proforma($id) {
  global $db;
  if (is_numeric($id)) {
    $res=$db->query("SELECT * FROM EmailProformas WHERE id=$id");
  } else {
    $res=$db->query("SELECT * FROM EmailProformas WHERE SN='$id'");
  }
  if ($res) {
    $ans = $res->fetch_assoc();
    return $ans;
  }
  return 0; 
}

function Put_Email_Proforma(&$now) {
  $e=$now['id'];
  $Cur = Get_Email_Proforma($e);
  return Update_db('EmailProformas',$Cur,$now);
}

// helper is a function that takes (THING,helperdata,atts) to return THING - not needed for generic fields typical THINGs are DETAILS, DEPOSIT...
// if mescat > 30 chars it is assumed to be the proforma itself
function Email_Proforma($to,$mescat,$subject,$helper='',$helperdata=0,$logfile='',&$attachments=0) {
  global $PLANYEAR,$MASTER;
  if (strlen($mescat) < 30) {
    $Prof = Get_Email_Proforma($mescat);
    $Mess = ($Prof? $Prof['Body'] : "Unknown message $mescat");
  } else {
    $Mess = $mescat;
  }
  $Reps = [];

  while (preg_match('/\*(\w*)\*/',$Mess)) {
    if (preg_match_all('/\*(\w*)\*/',$Mess,$Matches)) {
      foreach($Matches[1] as $key) {
        if (!isset($Reps[$key])) {
          switch ($key) {
          case 'PLANYEAR': 
          case 'THISYEAR': // For historic proformas should be removed in time
            $rep = $PLANYEAR;
            break;
          case 'DATES':
            $rep = ($MASTER['DateFri']+1) . "," . ($MASTER['DateFri']+2) ."th June $PLANYEAR";
            break;
          default:
            $rep = ($helper?$helper($key,$helperdata,$attachments):"*$key*");
            break;
          }
        $Reps[$key] =$rep;
        }
      }
      foreach ($Reps as $k=>$v) $Mess = preg_replace("/\*$k\*/",$v,$Mess);
    }
  }
  NewSendEmail($to,$subject,$Mess,$attachments);
  
  if ($logfile) {
    $logf = fopen("LogFiles/$logfile.txt","a");
    fwrite($logf,"\n\nEmail to : " . Pretty_Print_To($to) . "Subject:$subject\n\n$Mess");
    if ($attachments) {
      if (is_array($attachments)) {
        foreach ($attachments as $i=>$att) fwrite($logf,"With attachment: " . $att[0] . " as " . $att[1] . "\n\n");
      } else {
        fwrite($logf,"With attachment $attachments\n\n");       
      }
    }
    fclose($logf);
  }
  return $Mess;
}

?>
