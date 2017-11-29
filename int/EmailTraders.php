<?php
  include_once("fest.php");
  A_Check('Committee','Stalls');

  dostaffhead("Trade Emails");

  include_once("TradeLib.php");
  include_once("DateTime.php"); 

  global $db,$YEAR,$THISYEAR,$Trade_State,$USER;

  $Messages = Get_Email_Proformas();

  if (!isset($_POST{'SEND'})) {
    // Basic message text
    // Select (BID &/ TC), Previous (not BID/TC), All
    // Select list?

    echo "This is the message that will be sent.<p>"; 
    echo "The *LIMITED* will indicate it is an early booking option if appropriate.<p>";
    echo "The *WHO* will be the first name of the contact.<p>";
    echo "The *LINK* and *HERE* will be the direct links to edit their data and remove entries - do NOT change these.<p>"; 
    echo "The *DETAILS* are the booking details.<p>";
    echo "The Message here is editable to send, but the edited form is not stored.<p>";

    $Messkey = isset($_GET['MessNum'])?$_GET['MessNum']:1;

    echo "<h2>";
    foreach ($Messages as $mes) {
      if ($mes['id'] == $Messkey) {
	echo $mes['Name'];
	$Mess = $mes['Body'];
      } else {
	echo "<a href=EmailTraders.php?MessNum=" . $mes['id'] . ">" . $mes['Name'] . "</a>";
      }
      echo "&nbsp; &nbsp; &nbsp; ";
    }
    echo "</h2>\n";

    $Sender = $USER['Name'];
    $Mess = preg_replace('/\$THISYEAR/',$THISYEAR,$Mess);

    $_POST['Mess'] = preg_replace('/\*SENDER\*/',$Sender,$Mess);

    echo "<form method=post><table class=Devemail>";
    echo "<tr>" . fm_textarea('Message',$_POST,'Mess',10,25);
    echo "<tr><td colspan=10><input type=submit name=SEND value='BID and Chamber of Trade only'>\n";
    echo "<input type=submit name=SEND value='Previous traders not members of BID'>\n";
    echo "<input type=submit name=SEND value='Other traders'>\n";
    echo "<input type=submit name=SEND value='All traders'>\n";
    echo "<input type=submit name=SEND value='All Accepted Traders'>\n";
    echo "</table><form>\n";
  } else {
    $Limited = '';
    switch ($_POST['SEND']) {
    case 'BID and Chamber of Trade only':
      $qry = "SELECT * FROM Trade t WHERE t.status=0 AND t.BID=1";
      $Limited = "This early invite is being sent to members of the Wimborne BID and Chamber of Trade.<p>";
      break;

    case 'Previous traders not member of BID':
      $qry = "SELECT * FROM Trade t WHERE t.status=0 AND t.BID=0 AND t.Previous=1";
      $Limited = "This early invite is being sent to previous traders at Wimborne Minster Folk Festival.<p>";
      break;

    case 'Other traders':
      $qry = "SELECT * FROM Trade t WHERE t.status=0 AND t.BID=0 AND t.Previous=0";
      break;

    case 'All traders':
      $qry = "SELECT * FROM Trade t WHERE t.status=0";
      break;

    case 'All Accepted Traders':
      $qry = "SELECT t.*, y.* FROM Trade t, TradeYear y WHERE t.Tid=y.Tid AND y.Year=$YEAR AND y.BookingState>=" . $Trade_State['Accepted'];
      break;

    default:
	echo "Don't know that selection sorry";
        dotail();
    }
    $Mess = $_POST['Mess'];

    $res = $db->query($qry);
    if (!$res || $res->num_rows==0) {
      echo "None found!";
      dotail();
    }
    while ($Trad = $res->fetch_assoc()) {
      $ThisMess = $Mess;
      $Tid = $Trad['Tid'];
      $Key = $Trad['AccessKey'];
      if (!$Key) {
	echo "Ommitting " . $Trad['Name'] . " as it does not have an Access Key.<br>";
	continue;
      };

      if ($Trad['Contact']) {
        $Contact = firstword($Trad['Contact']);
      } else {
	$Contact = $Trad['Name'];
      }
      $Link = "<a href=http://wimbornefolk.co.uk/int/Direct.php?t=Trade&id=$Tid&key=$Key><b>link</b></a>";
      $Remove = "<a href=http://wimbornefolk.co.uk/int/Remove.php?t=Trade&id=$Tid&key=$Key><b>here</b></a>";
    
      $ThisMess = preg_replace('/\*WHO\*/',$Contact,$ThisMess);
      $ThisMess = preg_replace('/\*LINK\*/',$Link,$ThisMess);
      $ThisMess = preg_replace('/\*HERE\*/',$Remove,$ThisMess);
      $ThisMess = preg_replace('/\*LIMITED\*/',$Limited,$ThisMess);
// DETAILS TBD
      $Trady = Get_TradeYear($Tid);
      $Trady['SentInvite'] = date();
      Put_TradeYear($Trady);
      if (file_exists("testing")) {
	echo "Would send to " . $Trad['Name'] . "<p> $ThisMess <p>";
      } else {
exit;
	SendEmail($Trad['Email'],"Wimborne Minster Folk Festival $THISYEAR and " . $Trad['Name'],$ThisMess,"Content-type: text/html;");
	echo "Sent to " . $Trad['Name'] . "<br>";
      }
    }
  }
    

  dotail();
?>
