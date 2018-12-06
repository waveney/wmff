<?php
  include_once("fest.php");

// TODO change for all access types inc participant

  if (!Access('Staff','Dance')) {
    A_Check('Staff');
    fm_addall('disabled readonly');
  }

  dostaffhead("Add/Change Performer", "/js/clipboard.min.js", "/js/emailclick.js", "/js/Participants.js");
  include_once("DanceLib.php");
  include_once("MusicLib.php"); // TODO Merge two libs
  include_once("DateTime.php");
  include_once("PLib.php");

  global $YEAR,$PLANYEAR,$Mess,$BUTTON;  // TODO Take Mess local

  echo '<h2>Add/Edit Performer</h2>'; // TODO CHANGE
  global $Mess,$Action,$Dance_TimeFeilds;
  $DateFlds = ['ReleaseDate'];

//var_dump($_POST);
// TODO Change this to not do changes at a distance and needing global things
  $Action = ''; 
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
    
    Clean_Email($_POST{'Email'});
    Clean_Email($_POST{'AltEmail'});
    Parse_TimeInputs($Dance_TimeFeilds);    
    Parse_DateInputs($DateFlds);
 
    $Sidey = Default_SY();
    if ($snum > 0) {         // existing Side 
      $Side = Get_Side($snum);
      if ($Side) {
        $Sideyrs = Get_Sideyears($snum);
        if (isset($Sideyrs[$YEAR])) $Sidey = $Sideyrs[$YEAR];
      } else {
        echo "<h2 class=ERR>Could not find Performer $snum</h2>\n";
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

      Update_db_post('Sides',$Side);
      if (isset($_POST['Year']) && ($_POST['Year'] >= $PLANYEAR)) {
//      var_dump($Sidey);
        if (isset($Sidey) && $Sidey && isset($Sidey['syId']) && $Sidey['syId']){
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
      if (!isset($_POST['SN'])) {
        echo "<h2 class=ERR>NO NAME GIVEN</h2>\n";
        $proc = 0;
      }
      $_POST['AccessKey'] = rand_string(40);
      $snum = Insert_db_post('Sides',$Side,$proc);
      if ($snum) Insert_db_post('SideYear',$Sidey,$proc);
    }
    UpdateBand($snum);
    UpdateOverlaps($snum);


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
      echo "<h2 class=ERR>Could not find Performer $snum</h2>\n";
    }
  } else {
    $Sidey = Default_SY();
    $Side = ['SideId'=>$snum,'IsASide'=>1,'IsAnAct'=>0,'IsOther'=>0];
  }

  Show_Part($Side,'Side',1,'AddPerf.php');
  Show_Perf_Year($snum,$Sidey,$YEAR,Access('Staff'));

  if ($snum > 0) {
    if (Access('SysAdmin')) echo "<div class=floatright><input type=Submit id=smallsubmit name='NewAccessKey' class=Button$BUTTON value='New Access Key'></div>\n";
    echo "<Center><input type=Submit name='Update' value='Save Changes' class=Button$BUTTON >\n";
    if (Access('Staff','Dance')) {
      if (!isset($Sidey['Coming']) || $Sidey['Coming'] == 0) {
        if (!isset($Sidey['Invited']) || $Sidey['Invited'] == '') {
          echo " <input type=submit name=InviteAct value=Invite  class=Button$BUTTON > ";
        } else {
          echo " <input type=submit name=ReminderAct value=Reminder class=Button$BUTTON > ";
        }
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