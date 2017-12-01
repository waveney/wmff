<?php
  include("fest.php");
  global $USER,$USERID,$Part_Types;
  include("DanceLib.php");
  include("MusicLib.php");
  include_once("TradeLib.php");

  if ( !isset($_GET{'id'}) || !isset($_GET{'key'})) Error_Page("Invalid link"); // No return

  if (isset($_GET{'t'}) && strtolower($_GET{'t'}) == 'trade') { 
    $Tid = $_GET{'id'};
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
    include("files/navigation.php");
    include("TraderPage.php");
    exit;
  } else { 
    $SideId = $_GET{'id'};
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
    include("files/navigation.php");
    switch ($Type) {
    case 'Side':
      include("DanceEdit.php");
      exit;
    case 'Act':
    case 'Other':
      include("MusicEdit.php");
      exit;
    default:
      include("OtherEdit.php");
      exit;
    }
  }

?>
</div>
<?php include("files/footer.php"); ?>
</body>
</html>

