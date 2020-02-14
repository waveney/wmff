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
        $n = ((isset($too[2]) && $too[2])?("&lt;" . $too[2] . "&gt;"):'');
        switch ($too[0]) {
          case 'to':
            $str .= " to: $a $n, ";
            break;
          case 'cc':
            $str .= " cc: $a $n, ";
            break;
          case 'bcc':
            $str .= " bcc: $a $n, ";
            break;
          case 'replyto':
            $str .= " replyto: $a $n, ";
            break;
          case 'from':
            $str .= " from: $a $n, ";
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

  require 'vendor/autoload.php';


  use Html2Text\Html2Text;

function ConvertHtmlToText(&$body) {
  $body2 = preg_replace('/<p>/i',"</p>\n<p>",$body);
  $html = new \Html2Text\Html2Text($body2,['do_links' => 'inline']);
  return $html->getText();
}

//$to can be single address, a [address, name] or [[to,address,name],[cc,addr,name],bcc,addr,name],replyto,addr,name]...]
//$atts can be simple fie or [[file, name],[file,name]...]

function NewSendEmail($to,$sub,&$letter,&$attachments=0,&$embeded=0,$from='') { 
  global $FESTSYS,$CONF;
  
//  echo "Debug: " .( UserGetPref('EmailDebug')?2:0) . "<p>";
  $Send = 1;
  if (@ $CONF['testing']){
    if (strstr($CONF['testing'],'@')) { 
      $to = $CONF['testing'];
    } else {    
      echo "<p>Would send email to " . Pretty_Print_To($to);
      if ($from) echo "From: " . Pretty_Print_To($from);
      echo " with subject: $sub<p>Content:<p>$letter<p>\n";
    
      echo "Text: " . ConvertHtmlToText($letter);
      if ($attachments) {
        if (is_array($attachments)) {
          foreach ($attachments as $i=>$att) {
            echo "Would attachment " . $att[0] . " as " . $att[1] . "<p>";
          }                 
        } else {
          echo "Would attach $attachments<p>";       
        }
      }
      if ($embeded) {
        if (is_array($embeded)) {
          foreach ($embeded as $i=>$att) {
            echo "Would embed attachment " . $att[0] . " as " . $att[1] . "<p>";
          }                 
        } else {
          echo "Would embed $embeded<p>";       
        }
      }
    $Send = 0;
    return;
    }
  }
  $From = $FESTSYS['SMTPuser'];
  $Atts = [];
  
  $email = new PhpMailer(true);
  try {
    $email->SMTPDebug = ((Access('SysAdmin') && UserGetPref('EmailDebug'))?2:0);  // 2 general testing, 4 problems...
    $email->isSMTP();
    $email->Host = $FESTSYS['HostURL'];
    $email->SMTPAuth = true;
    $email->AuthType = 'LOGIN';
    $email->From = $email->Username = $FESTSYS['SMTPuser'] . "@" . $FESTSYS['HostURL'];
    $email->FromName = $FESTSYS['FestName'];
    $email->Password = $FESTSYS['SMTPpwd'];
    $email->SMTPSecure = 'tls';
    $email->Port = 587;
    $email->SMTPOptions = ['ssl' => [ 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
    
    if ($from) {
      if (is_array($from)) {
        $email->setFrom($from[0],$from[1]);
      } else {
        $email->setFrom($from);
      }
    }
    
    if (is_array($to)) {
      if (is_array($to[0])) {
        foreach ($to as $i=>$too) {
          if (!isset($too[0])) continue;
          $a = $too[1];
          $n = (isset($too[2])?$too[2]:'');
          switch ($too[0]) {
            case 'to':
              $email->addAddress($a,$n);
              $To = "$n <$a>";
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
              $From = "$n <$a>";
              break;
          } 
        }
      } else {
        $email->addAddress($to[0],(isset($to[1])?$to[1]:''));
        $To = $to[0];  
      }
    } else {
      $email->addAddress($to);
    }
    $email->Subject = $sub;
    $email->isHTML(true);
    $email->Body = $letter; // HTML format
    $email->AltBody = ConvertHtmlToText($letter); // Text format

    if ($attachments) {
      if (is_array($attachments)) {
        foreach ($attachments as $i=>$att) {
          $email->addAttachment($att[0],$att[1]);
          $Atts[] = [$att[0],$att[1]];
        }                 
      } else {
        $email->addAttachment($attachments);
        $Atts[] = ["",$attachments];
      }
    }
    if ($embeded) {
      if (is_array($embeded)) {
        foreach ($embeded as $i=>$att) {
          $email->addEmbeddedImage($att[0],$att[1]);
          $Atts[] = [$att[0],$att[1]];
        }                 
      } else {
        $email->addEmbeddedImage($embeded);       
        $Atts[] = ["",$embeded];
      }
    }

    if ($Send) $email->Send();
    
  } catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $email->ErrorInfo;
  }
  
  $EmLog = ['Subject'=>$sub,'FromAddr'=>json_encode($From),'ToAddr'=>json_encode($to), 'TextBody'=>$letter,'Date'=>time()];
  $logid = Insert_db('EmailLog', $EmLog);
  if ($Atts && $logid) {
    foreach ($Atts as $at) {
      $atc = ['EmailId'=>$logid,'AttName'=>$at[0],'AttFileName'=>$at[1]];
      Insert_db('EmailAttachments',$atc);
    }
  }
}

function Get_Email_Proformas() { 
  global $db;
  $res = $db->query("SELECT * FROM EmailProformas ORDER BY SN ");
  if ($res) while ($typ = $res->fetch_assoc()) $full[$typ['id']] = $typ;
  return $full;
}

function Get_Email_Proformas_By_Name() { 
  global $db;
  $res = $db->query("SELECT * FROM EmailProformas ORDER BY SN ");
  if ($res) while ($typ = $res->fetch_assoc()) $full[$typ['SN']] = $typ;
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

function Parse_Proforma(&$Mess,$helper='',$helperdata=0,$Preview=0,&$attachments=0,&$embeded=[]) {
  global $PLANYEAR,$YEARDATA,$FESTSYS,$USERID;
  static $attnum = 0;
  $Reps = [];
  
  while (preg_match('/\*(\w*)\*/',$Mess)) {
    if (preg_match_all('/\*(\S*)\*/',$Mess,$Matches)) {
      foreach($Matches[1] as $key) {
        if (!isset($Reps[$key])) {
          switch ($key) {
          case 'PLANYEAR': 
          case 'THISYEAR': // For historic proformas should be removed in time
            $rep = $PLANYEAR;
            break;
          case 'NEXTYEAR': 
            $rep = $PLANYEAR+1;
            break;
          case 'DATES':
            $rep = FestDate($YEARDATA['FirstDay']) . " to " . FestDate($YEARDATA['LastDay'],'M') ;
            break;
          case 'FESTIVAL':
            $rep = $FESTSYS['FestName'];
            break;
          case 'HOST':
            $rep = $FESTSYS['HostURL'];
            break;
          case (preg_match('/MAILTO_(.*)/',$key,$mtch)?true:false):
            $rep = "<a href='mailto:" . $mtch[1] . "@" . $FESTSYS['HostURL'] . "'>" . $mtch[1] . "@" . $FESTSYS['HostURL'] . "</a>";
            break;
          case (preg_match('/WEB(:.*)/',$key,$mtch)?true:false):
            $bits = preg_split('/:/',$mtch[1],3);
            $url = '';
            $txt = $FESTSYS['HostURL'];
            if (isset($bits[1])) $url = $bits[1];
            if (isset($bits[2])) { $txt = $bits[2]; $txt = preg_replace('/_/',' ',$txt); }
            $rep = "<a href='https://" . $_SERVER{'HTTP_HOST'} . ($url? "/$url" : "") . "'>$txt</a>";
            break;
          case (preg_match('/READFILE_(.*)/',$key,$mtch)?true:false):
            $file = file_get_contents($mtch[1]);
            if ($file) {
              $rep = $file;
            } else {
              $rep = "File " . $mtch[1] . " not Found.<p>";
            }
            break;
          case (preg_match('/IMAGE_(.*)/',$key,$mtch)?true:false):
            if (!file_exists($mtch[1])) { $rep = "Image " . $mtch[1] . " Not found<p>"; break;  };
            $sfx = pathinfo($mtch[1],PATHINFO_EXTENSION );
            $embeded[] = [$mtch[1],"img_$attnum.$sfx"];

            if ($Preview) {
              Set_User();
              if (!$attnum) system("rm Temp/$USERID.*");
              $tf = $USERID . "." . $attnum . "." . time() . ".$sfx";
              copy($mtch[1],"Temp/$tf");    
              $rep = "<img src='Temp/$tf'>";
            } else {
              $rep = "<img src=cid:img_$attnum.$sfx>";
            }
            $attnum++;
            break;
          case (preg_match('/COPY_(.*)/',$key,$mtch)?true:false):
            $Prof = Get_Email_Proforma($mtch[1]);
            $rep = ($Prof?$Prof:("Unknown Email Proforma " . $mtch[1] . "<p>"));
            break;

          default:
            $rep = ($helper?$helper($key,$helperdata,$attachments,$embeded):"*$key*");
            break;
          }
        $Reps[$key] =$rep;
        }
      }
      foreach ($Reps as $k=>$v) {
        $qk = preg_quote($k,'/');
//var_dump($qk,$v);
        $Mess = preg_replace("/\*$qk\*/",$v,$Mess);
      }

    }
  }
  
  $Mess = preg_replace('/(?<!<p>)\n\s*\n+\s*/mi',"\n\n<p>",$Mess);
}


// helper is a function that takes (THING,helperdata,atts) to return THING - not needed for generic fields typical THINGs are DETAILS, DEPOSIT...
// if mescat > 30 chars it is assumed to be the proforma itself
function Email_Proforma($to,$mescat,$subject,$helper='',$helperdata=0,$logfile='',&$attachments=0,&$embeded=[],$from='') {
  global $PLANYEAR,$YEARDATA,$FESTSYS;
  if (strlen($mescat) < 30) {
    $Prof = Get_Email_Proforma($mescat);
    $Mess = ($Prof? $Prof['Body'] : "Unknown message $mescat ");
  } else {
    $Mess = $mescat;
  }
  Parse_Proforma($Mess,$helper,$helperdata,0,$attachments,$embeded);
  
  NewSendEmail($to,$subject,$Mess,$attachments,$embeded,$from);
  
  if ($logfile) {
    $logf = fopen("LogFiles/$logfile.txt","a");
    fwrite($logf,"\n\nEmail to : " . Pretty_Print_To($to) . "Subject:$subject\n");
    if ($from) fwrite($logf,"From: " . Pretty_Print_To($from));
    fwrite($logf,"\n\n$Mess");

    if ($attachments) {
      if (is_array($attachments)) {
        foreach ($attachments as $i=>$att) fwrite($logf," With attachment: " . $att[0] . " as " . $att[1] . "\n\n");
      } else {
        fwrite($logf," With attachment $attachments\n\n");       
      }
    }
    if ($embeded) {
      if (is_array($embeded)) {
        foreach ($embeded as $i=>$att) fwrite($logf," With embeded: " . $att[0] . " as " . $att[1] . "\n\n");
      } else {
        fwrite($logf," With embeded $embeded\n\n");       
      }
    }

    fclose($logf);
  }
  return $Mess;
}

function Replace_Help($Area='',$Right=0) {
  $Reps = [
  ['*WHO*','First name of contact','All'],
  ['*PLANYEAR*/*NEXTYEAR*','Year for the booking, Planyear+1','All'],
  ['*DATES*','Dates of Saturday and Sunday','All'],
  ['*LOCATION*','Location(s) of Pitches','Trade'],
  ['*PRICE*','Total Price quoted','Trade'],
  ['*LINK*','Personal Link for Participants','Trade, Volunteers, Performers'],
  ['*REMOVE*','Remove Request','Trade'],
  ['*FESTLINK*','Link for Committee members direct to that Trader/Volunteer/Performer etc','Trade, Volunteers'],
  ['*DEPOSIT*','Deposit Required','Trade, LNL, BB'],
  ['*BALANCE*','Balance Required','Trade'],
  ['*DETAILS*','Full details of booking etc','Trade, BB, LOL, LNL, Volunteers, Invoices'],
  ['*FINANCIAL*','Trade financial statement','Trade'],
  ['*STATE*','Decsription of application state','Trade'],
  ['*PAIDSOFAR*','Total payments so far','Trade'],
  ['*FESTIVAL*','Name of Festival','All'],
  ['*HOST*','Host URL for festival','All'],
  ['*MAILTO_name*','Inserts a mailto link to name@festival.org','All'],
  ['*BBREF*/*LNLREF*','Unique reference for payments','BB, LNL'],
  ['*PROG*','Programme for performer','Dance (will be all performers)'],
  ['*WEB:*/*WEB:URL:TEXT','Website for Festival, URL - to follow website, TEXT - To be displayed (NO SPACES - any _ will appear as spaces)','All'],
  ['*MISSING*','Important information missing from a dance side','Dance'],
  ['*SIDE*','Name of side','Dance'],
  ['*TICKBOX:b:TEXT*','Direct link to click a box, b=num(1-4)|Rec(eived)|..., TEXT to be displayed (NO SPACES - any _ will appear as spaces)','Dance'],
  ['*TRADEMAP*','Trade location and Map info','Trade'],
  ['*WEBSITESTUFF*','Traders photo and product description prompt','Trade'],
  ['*READFILE_file*','Read file as body of message - only use for VERY large messages, contact Richard to use','All'],
  ['*IMAGE_file*','Embed image from file - contact Richard to use','All'],
  ['*DEPCODE*/*BALCODE*/*OTHERCODE*','Payment codes for Deposit, Balance(All), Other Payment','Trade'],
  ['*DUEDATE*','Date Invoice/Payment is Due','Trade, Invoices'],
  ['*PAYCODES*','Details of payment to be made, ammount, account and code to be used','Trade'],
  ['*COPY_name*','Copy Email Proforma name into the current message','All'],
  ['*PAYDAYS','Days to pay an Invoice','Trade,Invoices'],
  ];

  echo "<span " . ($Right?' class=floatright':'') . " id=largeredsubmit onclick=($('.HelpDiv').toggle()) >Click to toggle Standard Replacements Help</span>";
  echo "<div class=HelpDiv hidden>";

  echo "<div class=tablecont><table border>\n";
  echo "<tr><td>Code<td>What it does<td>Areas \n";

  foreach($Reps as $r) {
    if ($Area =='' || preg_match("/(All)|($Area)/",$r[2])) echo "<tr><td>" . $r[0] . "<td>" . $r[1] . "<td>" . $r[2] . "\n";
  }
  echo "</table></div></div>\n";
}

?>
