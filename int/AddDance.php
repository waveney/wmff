<?php
  include_once("fest.php");
  A_Check('Committee','Dance');
?>

<html>
<head>
<title>WMFF Staff | Add/Change Dance Side</title>
<?php include_once("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
<?php include_once("MusicLib.php"); ?>
<?php include_once("DateTime.php"); ?>
<?php include_once("PLib.php"); ?>
<script src="/js/clipboard.min.js"></script>
<script src="/js/emailclick.js"></script>
<script src="/js/Participants.js"></script>
<meta http-equiv="cache-control" content="no-cache">
</head>
<body>

<?php
  global $YEAR,$THISYEAR,$Mess;
  include("files/navigation.php");
  echo '<div class="content"><h2>Add/Edit Dance Side</h2>';
  global $Mess,$Action,$Dance_TimeFeilds;      

//var_dump($_POST);
  $Action = 0; 
  $Mess = '';
  if (isset($_POST{'Action'})) {
    include("Uploading.php");
    $Action = $_POST{'Action'};
    switch ($Action) {
    case 'PASpecUpload':
      $Mess = Upload_PASpec();
      break;
    case 'Insurance':
      $Mess = Upload_Insurance();
      break;
    case 'Photo':
      $Mess = Upload_Photo();
      break;
    default:
      $Mess = "!!!";
    }
  }

//  echo "<!-- " . var_dump($_POST) . " -->\n";
  if (isset($_POST{'SideId'})) { /* Response to update button */
    $snum = $_POST{'SideId'};
    if ($snum > 0) { 				// existing Side 
      $Side = Get_Side($snum);
      if ($Side) {
        $Sideyrs = Get_Sideyears($snum);
        if (isset($Sideyrs[$THISYEAR])) $Sidey = $Sideyrs[$THISYEAR];
      } else {
        echo "<h2 class=ERR>Could not find Side $snum</h2>\n";
      }

      if (isset($_POST{'InviteAct'}) || isset($_POST{'ReminderAct'})) {
	date_default_timezone_set('GMT');
	if (strlen($_POST['Invited'])) $_POST['Invited'] .= ", ";
	$_POST['Invited'] .= date('j/n');
      } elseif (isset($_POST{'NewAccessKey'})) $_POST{'AccessKey'} = rand_string(40);

      Clean_Email($_POST{'Email'});
      Clean_Email($_POST{'AltEmail'});
      Parse_TimeInputs($Dance_TimeFeilds);      

      Update_db_post('Sides',$Side);
      if ($_POST{'Year'} == $THISYEAR) {
        if ($Sidey) {
          Update_db_post('SideYear',$Sidey);
        } else {
	  $Sidey['Year'] = $THISYEAR;
	  $syId = Insert_db_post('SideYear',$Sidey);
	  $Sidey['syID'] = $syId;
	};
      }
      UpdateBand($snum);
    } else { /* New Side */
      $proc = 1;
      $Side = array();
      if (!isset($_POST['Name'])) {
	echo "<h2 class=ERR>NO NAME GIVEN</h2>\n";
	$proc = 0;
      }
      $_POST['AccessKey'] = rand_string(40);
      Clean_Email($_POST{'Email'});
      Clean_Email($_POST{'AltEmail'});
      $snum = Insert_db_post('Sides',$Side,$proc);
      if ($snum) Insert_db_post('SideYear',$Sidey,$proc);
      UpdateBand($snum);
    }

  } elseif (isset($_GET{'sidenum'})) { /* Link from elsewhere */
    $snum = $_GET{'sidenum'};
    $Side = Get_Side($snum);
    if ($Side) {
      $Sideyrs = Get_Sideyears($snum);
      if (isset($Sideyrs[$YEAR])) {
        $Sidey = $Sideyrs[$YEAR];
      } else {
        $Sidey = Default_SY();
      }
    } else {
      echo "<h2 class=ERR>Could not find Side $snum</h2>\n";
    }
  } else {
    $Sidey = Default_SY();
  }

  Show_Part($Side,'Side',1,'AddDance.php');
  Show_Part_Year($snum,$Sidey,$YEAR,'Side',1);
  if ($Side['IsAnAct'] || $Side['IsOther']) {
    $type = 'Act';
    if (!$Side['IsAnAct'] && $Side['IsOther']) $type = 'Other';
    Show_Act_Year($snum,$acty,$YEAR,$type,1);
  }

  if ($snum > 0) {
    if (Access('SysAdmin')) echo "<div class=floatright><input type=Submit id=smallsubmit name='NewAccessKey' value='New Access Key'></div>\n";
    echo "<Center><input type=Submit name='Update' value='Save Changes'>\n";
    if (!isset($Sidey['Coming']) || $Sidey['Coming'] == 0) {
      if (!isset($Sidey['Invited']) || $Sidey['Invited'] == '') {
	echo " <input type=submit name=InviteAct value=Invite> ";
      } else {
	echo " <input type=submit name=ReminderAct value=Reminder> ";
      }
    } 
    echo "</center>\n";
  } else { 
    echo "<Center><input type=Submit name=Create value='Create'></center>\n";
  }
  echo "</form>\n";

  echo Show_Prog('Side',$snum,0,1,1);

  dotail();
?>
