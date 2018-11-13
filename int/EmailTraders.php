<?php
  include_once("fest.php");
  A_Check('Committee','Trade');

  dostaffhead("Trade Emails");

  include_once("TradeLib.php");
  include_once("DateTime.php"); 
  include_once("Email.php"); 
  include_once("InvoiceLib.php");
 
  global $db,$YEAR,$PLANYEAR,$Trade_State,$Trade_States,$Trade_State_Colours,$USER;

  $Messages = Get_Email_Proformas();
  $Trade_Loc_Names = Get_Trade_Locs(0);
  $Trade_Type_Names = [];
  $Trade_Type_Colours = [];
  foreach ($TradeTypeData as $i=>$tt) { 
    $Trade_Type_Names[$i] = $tt['SN']; 
    $Trade_Type_Colours[$i] = $tt['Colour']; 
  };

  if (!isset($_POST{'SEND'})) {
    // Basic message text
    // Select (BID &/ TC), Previous (not BID/TC), All
    // Select list?

    echo "<h3>Select subset of traders - if no States/Locations/types are selected that category is treated as all</h3><p>";
    echo "<form method=post><table class=Devemail>";
    
    echo "<tr>" . fm_radio("State",$Trade_States,$_POST,'Tr_State','',1,'','',$Trade_State_Colours,1);
    echo "<tr>" . fm_radio("Location",$Trade_Loc_Names,$_POST,'Tr_Loc','',1,'','',null,1);
    echo "<tr>" . fm_radio("Trade Type",$Trade_Type_Names,$_POST,'Tr_Type','',1,'','',$Trade_Type_Colours,1);
    echo "</table><p>";
    
    
    echo "This is the message that will be sent.<p>"; 
    echo "The Message here is editable to send, but the edited form is not stored.<p>";

    $Messkey = isset($_GET['MessNum'])?$_GET['MessNum']:1;

    echo "<h2>";
    foreach ($Messages as $mes) {
      if (!preg_match('/Trade_/',$mes['SN'])) continue;
      if ($mes['id'] == $Messkey) {
        echo $mes['SN'];
        $Mess = $mes['Body'];
      } else {
        echo "<a href=EmailTraders.php?MessNum=" . $mes['id'] . ">" . $mes['SN'] . "</a>";
      }
      echo "&nbsp; &nbsp; &nbsp; ";
    }
    echo "</h2>\n";

    if (!isset($Mess)) $Mess = $Messages[1]['Body'];
    $Sender = $USER['SN'];
    $Mess = preg_replace('/\$PLANYEAR/',$PLANYEAR,$Mess);

    $_POST['Mess'] = preg_replace('/\*SENDER\*/',$Sender,$Mess);

    echo "<form method=post><table class=Devemail>";
    echo "<tr><td colspan=8>" . fm_checkbox("Just list who it would go to do not actually send anything",$_POST,'JustList');
    echo "<tr>" . fm_text('Start at', $_POST,'STARTAT');
    echo "<tr>" . fm_textarea('Message',$_POST,'Mess',10,25);
    /*
    echo "<tr><td colspan=10><input type=submit name=SEND value='BID and Chamber of Trade only'>\n";
    echo "<input type=submit name=SEND value='Previous traders not members of BID'>\n";
    echo "<input type=submit name=SEND value='Other traders'>\n";
    echo "<input type=submit name=SEND value='All traders'>\n";
    echo "<input type=submit name=SEND value='All Accepted Traders'>\n";
    echo "<input type=submit name=SEND value='All Non - Accepted Traders'>\n"; */
    echo "<input type=submit name=SEND value=Send>\n";
    
    echo "</table><form>\n";
  } else {
    $Limited = '';
    switch ($_POST['SEND']) {
    case 'BID and Chamber of Trade only': // Not used
      $qry = "SELECT * FROM Trade t WHERE t.status=0 AND t.BID=1";
      $Limited = "This early invite is being sent to members of the Wimborne BID and Chamber of Trade.<p>";
      break;

    case 'Previous traders not member of BID': // Not used
      $qry = "SELECT * FROM Trade t WHERE t.status=0 AND t.BID=0 AND t.Previous=1";
      $Limited = "This early invite is being sent to previous traders at Wimborne Minster Folk Festival.<p>";
      break;

    case 'Other traders': // Not used
      $qry = "SELECT * FROM Trade t WHERE t.status=0 AND t.BID=0 AND t.Previous=0";
      break;

    case 'All traders': // Not used
      $qry = "SELECT * FROM Trade t WHERE t.status=0";
      break;

    case 'All Accepted Traders': // Not used
      $qry = "SELECT t.*, y.* FROM Trade t, TradeYear y WHERE t.Tid=y.Tid AND y.Year=$YEAR AND y.BookingState>=" . $Trade_State['Accepted'];
      break;

    case 'All Non - Accepted Traders': // Not used
      $qry = "SELECT t.*, y.* FROM Trade t, TradeYear y WHERE t.Tid=y.Tid AND y.Year=$YEAR AND y.BookingState<" . $Trade_State['Accepted'];
      break;
      
    case 'Send' : 
      // SCan for State
//var_dump($_POST);
      $ts = [];
      $st = '';
      foreach ($Trade_States as $i=>$n) if (isset($_POST["Tr_State$i"] )) $ts[] = "y.BookingState=$i";
      if (!empty($ts)) $st .= " AND ( " . implode(" OR ", $ts) . " ) ";
      $ts = [];
      foreach ($Trade_Loc_Names as $i=>$n) if (isset($_POST["Tr_Loc$i"] )) $ts[] = "( y.PitchLoc0=$i OR y.PitchLoc1=$i OR y.PitchLoc2=$i )";
      if (!empty($ts)) $st .= " AND ( " . implode(" OR ", $ts) . " ) ";
      $ts = [];
      foreach ($TradeTypeData as $i=>$td) if (isset($_POST["Tr_Type$i"] )) $ts[] = "t.TradeType=$i";
      if (!empty($ts)) $st .= " AND ( " . implode(" OR ", $ts) . " ) ";

      $qry = "SELECT t.*, y.* FROM Trade t, TradeYear y WHERE t.Tid=y.Tid $st ";      
    
    
      break;

    default:  // Not used
        echo "Don't know that selection sorry";
        dotail();
    }
    $Mess = $_POST['Mess'];

    $res = $db->query($qry);
    if (!$res || $res->num_rows==0) {
      echo "None found!";
      dotail();
    }
    
//echo "QRY was $qry<p>";

    $Sent_Count = 0;
    $StartAt = (isset($_POST['STARTAT']) ? ($_POST['STARTAT']?$_POST['STARTAT']:0) : 0);

    $EndAt = $StartAt +20;// Batch size 5 for testing 20 in real life  // TODO review that

    while ($Trad = $res->fetch_assoc()) {
      $Key = $Trad['AccessKey'];
      if (!$Key) {
        echo "Ommitting " . $Trad['SN'] . " as it does not have an Access Key.<br>";
        continue;
      };

      if ($Sent_Count >= $StartAt && $Sent_Count < $EndAt) {

        Send_Trader_Email($Trad,$Trad,$Mess);
      
        if (file_exists("testing")) {
          echo "Would send to " . $Trad['SN'] . "<p>";
        } else {
          echo "Sent to " . $Trad['SN'] . "<br>";
        }
      }
      $Sent_Count++;
    }
    if ($Sent_Count > $EndAt) {
      echo "<P><form method=post>";
      echo fm_hidden('STARTAT', $EndAt+1) . fm_hidden('Mess',$Mess) . fm_hidden('SEND',$_POST['SEND']) ; 
      if (isset($_POST['JustList'])) echo fm_hidden('JustList',$_POST['JustList']);
      echo "<input type=submit name=MORE value='Next batch " . $EndAt . "'>\n";
    } else {
      echo "All Done";
    }
  }

  dotail();
?>
