<?php
  include_once("fest.php");
  global $USER,$USERID;
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("TradeLib.php");

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

    echo " <html> <head> <title>Wimborne Minster Folk Festival | Trader</title>";
    include_once("files/header.php");
    include_once("festcon.php"); 
    echo "</head> <body>";
    $USER{'AccessLevel'} = $Access_Type['Participant'];
    $USER{'Subtype'} = 'Trader';
    $USER{'UserId'} = $USERID = $Tid;
    include_once("files/navigation.php");
    include_once("TraderPage.php");
    exit;
  } else { 
    $SideId = $_GET{'id'};
    if (!is_numeric($SideId)) Error_Page("Invalid Identifier");
    $Side = Get_Side($SideId);
    if (isset($_GET{'t'})) {
      $Type = $_GET{'t'};
    } else {
      $Type = ($Side['IsASide']?'Side': ($Side['IsAnAct'] ? 'Act' : 'Other'));
    }

    if ($Side['AccessKey'] != $_GET{'key'}) Error_Page("Sorry - This is not the right key");  // No return

    $Cake = sprintf("%s:%d:%06d",$Type,$Access_Type['Participant'],$SideId ); 
    $biscuit = openssl_encrypt($Cake,'aes-128-ctr','Quarterjack',0,'BrianMBispHarris');
    setcookie('WMFFD',$biscuit,0,'/');

    echo " <html> <head> <title>Wimborne Minster Folk Festival | $Type</title>";
    include_once("files/header.php");
    include_once("festcon.php"); 
    echo "</head> <body>";
    $USER{'AccessLevel'} = $Access_Type['Participant'];
    $USER{'Subtype'} = $Type;
    $USER{'UserId'} = $USERID = $SideId;
    include_once("files/navigation.php");
    switch ($Type) {
    case 'Side':
      include_once("DanceEdit.php");
      exit;
    case 'Act':
    case 'Other':
      include_once("MusicEdit.php");
      exit;
    default:
      include_once("OtherEdit.php");
      exit;
    }
  }
  dotail();
?>

