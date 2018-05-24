<html>
<head>
<title>Wimborne Minister Folk Festival | Side Editing</title>
<?php include_once("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("MusicLib.php"); ?>
<?php include_once("DanceLib.php"); ?>
<?php include_once("PLib.php"); ?>
<script src="/js/Participants.js"></script>
<meta http-equiv="cache-control" content="no-cache">
</head>
<body>
<?php include_once("files/navigation.php"); ?>
<div class="content">

<?php
  global $Mess,$Action,$MASTER,$YEAR,$THISYEAR,$Book_State;

//var_dump($_POST);
  $Action = 0; 
  $Mess = '';
/*    User Editing of Side Data */ ////////// DUFF
  if (isset($_POST{'Action'})) {
    include_once("Uploading.php");
    $Action = $_POST{'Action'};
    switch ($Action) {
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

  if (isset($_POST{'SideId'})) { /* Response to update button */
    $snum = $_POST{'SideId'};
    A_Check('Participant','Act',$snum);
    if ($snum > 0) { 				// existing Side 
      $Side = Get_Side($snum);
      if ($Side) {
        $Sideyrs = Get_Actyears($snum);
        if (isset($Sideyrs[$THISYEAR])) $Sidey = $Sideyrs[$THISYEAR];
      } else {
        echo "<h2 class=ERR>Could not find Side $snum</h2>\n";
      }

      Clean_Email($_POST{'Email'});
      Clean_Email($_POST{'AltEmail'});
      if ($_POST{'Photo'} != $Side{'Photo'}) {
	include_once("ImageLib.php");
	$Mess = Image_Validate($_POST{'Photo'});
	if ($Mess) $_POST{'Photo'} = $Side['Photo'];
      }
      if (isset($_POST{'Contract'})) { 
	Contract_Save($Side,$Sidey,1); 
      } elseif (isset($_POST{'Decline'})) { 
	Contract_Decline($Side,$Sidey,1); 
      }
      Update_db_post('Sides',$Side,1);
      if ($_POST{'Year'} >= $THISYEAR) {
        if (isset($Sidey) && $Sidey){
	  $Sve_Sidey = $Sidey;
          Update_db_post('ActYear',$Sidey);
	  if (ActYear_Check4_Change($Sve_Sidey,$Sidey)) $Sidey = Get_Actyear($snum);
        } else {
	  $Sidey['Year'] = $THISYEAR;
	  $ActId = Insert_db_post('ActYear',$Sidey);
	  $Sidey['ActId'] = $ActId;
	};
      }
      UpdateBand($snum);
      Report_Log('Music');
    }
  } elseif (isset($_GET{'sidenum'}) || isset($_GET{'id'})) { /* Link from elsewhere */
    $snum = (isset($_GET{'sidenum'})?$_GET{'sidenum'}:$_GET{'id'});
    A_Check('Participant','Act',$snum);
    $Side = Get_Side($snum);
    if ($Side) {
      $Sideyrs = Get_Actyears($snum);
      if (isset($Sideyrs[$YEAR])) {
        $Sidey = $Sideyrs[$YEAR];
      } else {
        $Sidey = Default_AY();
      }
    } else {
      echo "<h2 class=ERR>Could not find Side $snum</h2>\n";
    }
  }

  $type = 'Act';
  if (!$Side['IsAnAct'] && $Side['IsOther']) $type = 'Other';

  Show_Part($Side,$type,0,'MusicEdit.php');
  Show_Music_Year($snum,$Sidey,$YEAR,$type,0);
    
  echo "<button onclick=ShowAdv(event) id=ShowMore type=button class=floatright>More features</button>";
  echo "<center><input type=Submit name='Update' value='Save Changes'></center>\n";
  echo "</form>\n";

  echo Show_Prog('Act',$snum);
  echo Extended_Prog('Act',$snum);

  echo "</div>\n";
  dotail();
?>
