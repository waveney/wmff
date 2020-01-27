<?php
  include_once("fest.php");
  global $USER,$USERID;
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("TradeLib.php");
  include_once("InvoiceLib.php");

  if ( !isset($_GET{'id'}) || !isset($_GET{'key'})) Error_Page("Invalid link"); // No return

  if (isset($_GET{'t'}) && strtolower($_GET{'t'}) == 'trade') { 
    $Tid = $_GET{'id'};
    if (!is_numeric($Tid)) Error_Page("Invalid Identifier");
    $Trad = Get_Trader($Tid);

    if ($_GET['key'] == '') {

    } 
    elseif ($Trad['AccessKey'] != $_GET{'key'}) Error_Page("Sorry - This is not the right key");  // No return

    $Cake = sprintf("%s:%d:%06d",'Trader',$Access_Type['Participant'],$Tid ); 
    $biscuit = openssl_encrypt($Cake,'aes-128-ctr','Quarterjack',0,'BrianMBispHarris');
    setcookie('WMFFD',$biscuit,0,'/');
    $_COOKIE['WMFFD'] = $biscuit;

    dostaffhead("Trader", ["/js/Participants.js", "/js/dropzone.js"]);

    $USER{'AccessLevel'} = $Access_Type['Participant'];
    $USER{'Subtype'} = 'Trader';
    $USER{'UserId'} = $USERID = $Tid;
    if (basename($_SERVER['PHP_SELF']) == 'Remove.php') {
      $Trad = Get_Trader($Tid);
      $Trad['Status'] = 2;
      Put_Trader($Trad);
      $Trady = Get_Trade_Year($Tid);
      if ($Trady && $Trady['BookingState']>=$Trade_State['Submitted']) {
        $Trady['BookingState'] = $Trade_State['Cancelled'];
        Put_Trade_Year($Trady);
      }
      echo "<h2>Thank you for letting us know</h2>";
      
      dotail(); 
    } else {
      include_once("TraderPage.php");
    }
    exit;
  } else { 
    $SideId = $_GET{'id'};
    if (!is_numeric($SideId)) Error_Page("Invalid Identifier");
    $Side = Get_Side($SideId);
    if (isset($_GET{'t'})) {
      $Type = $_GET{'t'};
    } else {
      $Type = 'Perf'; //($Side['IsASide']?'Side': ($Side['IsAnAct'] ? 'Act' : 'Other'));
    }

//    echo "Key should be: " . $Side['AccessKey'] . " is " . $_GET['key'] ."<p>";
//    var_dump($Side);
    
    if ($Side['AccessKey'] != $_GET{'key'}) Error_Page("Sorry - This is not the right key");  // No return

    $Cake = sprintf("%s:%d:%06d",$Type,$Access_Type['Participant'],$SideId ); 
    $biscuit = openssl_encrypt($Cake,'aes-128-ctr','Quarterjack',0,'BrianMBispHarris');
    setcookie('WMFFD',$biscuit,0,'/');
    $_COOKIE['WMFFD'] = $biscuit;

    dostaffhead($Type, ["/js/Participants.js", "/js/dropzone.js"]);

    $USER{'AccessLevel'} = $Access_Type['Participant'];
    $USER{'Subtype'} = $Type;
    $USER{'UserId'} = $USERID = $SideId;
    
    include_once("AddPerf.php"); // Should not return
  }
  dotail();
?>

