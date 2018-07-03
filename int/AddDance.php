<?php
  include_once("fest.php");
  A_Check('Committee','Dance');

  dostaffhead("Add/Change Dance Side", "/js/clipboard.min.js", "/js/emailclick.js", "/js/Participants.js");
  include_once("files/header.php");
  include_once("festcon.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("DateTime.php");
  include_once("PLib.php");

  global $YEAR,$PLANYEAR,$Mess,$BUTTON;

  echo '<h2>Add/Edit Dance Side</h2>';
  global $Mess,$Action,$Dance_TimeFeilds;      

//var_dump($_POST);
  $Action = 0; 
  $Mess = '';
  $snum = -1;
  if (isset($_POST{'Action'})) {
    include_once("Uploading.php");
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
    if ($snum > 0) {         // existing Side 
      $Side = Get_Side($snum);
      if ($Side) {
        $Sideyrs = Get_Sideyears($snum);
        if (isset($Sideyrs[$YEAR])) $Sidey = $Sideyrs[$YEAR];
      } else {
        echo "<h2 class=ERR>Could not find Side $snum</h2>\n";
      }

      if (isset($_POST{'InviteAct'}) || isset($_POST{'ReminderAct'})) {
        date_default_timezone_set('GMT');
        if (strlen($_POST['Invited'])) $_POST['Invited'] .= ", ";
        $_POST['Invited'] .= date('j/n');
        } elseif (isset($_POST{'NewAccessKey'})) {
        $_POST{'AccessKey'} = rand_string(40);
      } elseif (isset($_POST{'Contract'})) { 
        Contract_Save($Side,$Sidey,2); 
      } elseif (isset($_POST{'Decline'})) { 
        Contract_Decline($Side,$Sidey,2); 
      }

      Clean_Email($_POST{'Email'});
      Clean_Email($_POST{'AltEmail'});
      Parse_TimeInputs($Dance_TimeFeilds);      

      Update_db_post('Sides',$Side);
      if ($_POST{'Year'} >= $PLANYEAR) {
        if (isset($Sidey) && $Sidey){
          Update_db_post('SideYear',$Sidey);
        } else {
          $Sidey['Year'] = $PLANYEAR;
          $syId = Insert_db_post('SideYear',$Sidey);
          $Sidey['syID'] = $syId;
        };
      }
      UpdateBand($snum);
      UpdateOverlaps($snum);
    } else { /* New Side */
      $proc = 1;
      $Side = array();
      if (!isset($_POST['SName'])) {
      echo "<h2 class=ERR>NO NAME GIVEN</h2>\n";
      $proc = 0;
      }
      $_POST['AccessKey'] = rand_string(40);
      Clean_Email($_POST{'Email'});
      Clean_Email($_POST{'AltEmail'});
      $snum = Insert_db_post('Sides',$Side,$proc);
      if ($snum) Insert_db_post('SideYear',$Sidey,$proc);
      UpdateBand($snum);
      UpdateOverlaps($snum);
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
    $Side = ['SideId'=>$snum,'IsASide'=>1,'IsAnAct'=>0,'IsOther'=>0];
  }

  Show_Part($Side,'Side',1,'AddDance.php');
  Show_Part_Year($snum,$Sidey,$YEAR,'Side',1);
  if ($Side['IsAnAct'] || $Side['IsOther']) {
    $type = 'Act';
    if (!$Side['IsAnAct'] && $Side['IsOther']) $type = 'Other';
//    Show_Act_Year($snum,$acty,$YEAR,$type,1);
  }

  if ($snum > 0) {
    if (Access('SysAdmin')) echo "<div class=floatright><input type=Submit id=smallsubmit name='NewAccessKey' class=Button$BUTTON value='New Access Key'></div>\n";
    echo "<Center><input type=Submit name='Update' value='Save Changes' class=Button$BUTTON >\n";
    if (!isset($Sidey['Coming']) || $Sidey['Coming'] == 0) {
      if (!isset($Sidey['Invited']) || $Sidey['Invited'] == '') {
        echo " <input type=submit name=InviteAct value=Invite  class=Button$BUTTON > ";
      } else {
        echo " <input type=submit name=ReminderAct value=Reminder class=Button$BUTTON > ";
      }
    } 
    echo "</center>\n";
  } else { 
    echo "<Center><input type=Submit name=Create value='Create' class=Button$BUTTON ></center>\n";
  }
  echo "</form>\n";

  echo Show_Prog('Side',$snum,1);
  echo Extended_Prog('Side',$snum,1);

  dotail();
?>
