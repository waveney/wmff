<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("View Email Log");
  include_once("DanceLib.php");
  include_once("Email.php");
  
  global $FESTSYS,$db;
  
function decode_addr($addr) {
    $to = json_decode($addr,true);
    if (!$to) $to = $addr;
    if (is_array($to)) {
      if (is_array($to[0])) {
        foreach ($to as $i=>$too) {
          if (!isset($too[0])) continue;
          $a = $too[1];
          $n = (isset($too[2])?$too[2]:'');
          switch ($too[0]) {
            case 'to':
              echo NoBreak(htmlspec("To: $n<$a>")); 
              break;
            case 'cc':
              echo NoBreak(htmlspec("CC: $n<$a>")); 
              break;
            case 'bcc':
              echo NoBreak(htmlspec("BCC: $n<$a>"));      
              break;
            case 'replyto':
              continue 2;
//              echo NoBreak(htmlspec("Replyto: $n<$a>"));      
              break;
            case 'from':
              continue 2;
//              echo NoBreak(htmlspec("From: $n<$a>"));      
              break;
          } 
          echo "<br>";
        }
      } else {
        if (isset($to[1])) {
          echo NoBreak(htmlspec( "From: " . $to[1] . "<" . $to[0] . ">"));
        } else {
          echo NoBreak(htmlspec( $to[0] ));
        }
      }
    } else {
      echo NoBreak(htmlspec($to));
    }
}  

 if (isset($_REQUEST['MESSAGE'])) {
   $Log = Get_Email_Log($_REQUEST['MESSAGE']);
   
   echo "<table border>";
   echo "<tr><td>Sent : <td>" . date('j M Y H:i',$Log['Date']);
   echo "<tr><td>From : <td>" . htmlspec($Log['FromAddr']);
   echo "<tr><td><td>";
   decode_addr($Log['ToAddr']);
   echo "</table>";
   
   echo "<div style='background:white;border:2;border-color:blue;padding:20;margin:20;width:90%;max-width:80ch;height:50%;overflow:scroll' >" . $Log['TextBody'] . "</div>";
   
   $Atts = Get_Email_Attachments($_REQUEST['MESSAGE']);
   
   if ($Atts) {
     if (isset($Atts[1])) {
       echo "Attachments : ";
       foreach ($Atts as $Att) {
         if ($Att['AttType'] ==0 ) {
           echo  "<a target=_blank href='ShowFile?l=" . $Att['AttFileName'] . "'>" . $Att['AttFileName'] . "</a> " ;      
         } else { // TODO images
         
         }
       }
     } else {
       echo "Attachment : <a target=_blank href='ShowFile?l=" . $Atts[0]['AttFileName'] . "'>" . $Atts[0]['AttFileName'] . "</a>" ;
     }
   }
   
   if ($Log['Type'] == 1) {
     echo "<form method=post action=SendProfEmail target=_blank>" . fm_hidden('LogId',$_REQUEST['MESSAGE']) . "<input type=submit name=REEDIT value=Reedit>";
     echo "</form>";
   }
   dotail();
 }

  // Initially Dance, will extend to others as integrated

  if (!isset($_REQUEST['Src'])) { Error_Page("No Source Type given"); };
  $Src = $_REQUEST['Src'];
  
  if (!isset($_REQUEST['id'])) { Error_Page("No Source id given"); };
  $SrcId = $_REQUEST['id'];

  $Logs = Get_Email_Logs($Src,$SrcId);
  if (!$Logs) {
    echo "No Emails in the Log";
    dotail();
  }
  
  echo "Click on the date for full message<p>";
  echo "<table border><tr><td>Date<td>From<td>To<td>Start of Message";
  
  foreach ($Logs as $Log) {
    echo "<tr><td><a href=ViewEmailLog?MESSAGE=" . $Log['id'] . ">" . date('j M Y H:i',$Log['Date']) . "</a><td>" . htmlspec($Log['FromAddr']) . "<td>";
    decode_addr($Log['ToAddr']);
    echo "<td>" . strip_tags(substr($Log['TextBody'],0,200),'<p><br><b>') . " ...";
  }
  echo "</table>";

  dotail();
  
  // TODO make it work for embeded images
?>
