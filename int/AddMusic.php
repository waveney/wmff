<?php
  include_once("fest.php");
  A_Check('Staff','Music');

  dostaffhead("Add/Change Music Act","/js/clipboard.min.js", "/js/emailclick.js", "/js/Participants.js");

  global $YEAR,$PLANYEAR,$Mess,$Book_State,$Action;
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("DateTime.php");
  include_once("PLib.php");
  include_once("BudgetLib.php");
  echo '<h2>Add/Edit Music Act</h2>';

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
    if ($snum > 0) {                                 // existing Side 
      $Side = Get_Side($snum);
      if ($Side) {
        $Actyrs = Get_Actyears($snum);
        if (isset($Actyrs[$YEAR])) $Sidey = $Actyrs[$YEAR];
      } else {
        echo "<h2 class=ERR>Could not find Act $snum</h2>\n";
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

      Update_db_post('Sides',$Side);
      if ($_POST{'Year'} >= $PLANYEAR) {
        if (isset($Sidey) && $Sidey){
          $Sve_Sidey = $Sidey;
          Update_db_post('ActYear',$Sidey);
          if (ActYear_Check4_Change($Sve_Sidey,$Sidey)) $Sidey = Get_Actyear($snum);
//          UpdateBudget($Sidey,$Sve_Sidey);
        } else {
          $Sidey['Year'] = $PLANYEAR;
          $ActId = Insert_db_post('ActYear',$Sidey);
          $Sidey['ActId'] = $ActId;
//          UpdateBudget($Sidey);
        };

      }
      UpdateBand($snum);
      UpdateOverlaps($snum);

    } else { /* New Act */
      $proc = 1;
      $Side = array();
      if (!isset($_POST['SN'])) {
        echo "<h2 class=ERR>NO NAME GIVEN</h2>\n";
        $proc = 0;
      }
      $_POST['AccessKey'] = rand_string(40);
      Clean_Email($_POST{'Email'});
      Clean_Email($_POST{'AltEmail'});
      $snum = Insert_db_post('Sides',$Side,$proc);
      if ($snum) Insert_db_post('ActYear',$Sidey,$proc);
      UpdateBand($snum);
      UpdateOverlaps($snum);
//      UpdateBudget($Sidey);
    }
    $type = 'Act';
    if (!$Side['IsAnAct'] && $Side['IsOther']) $type = 'Other';

  } elseif (isset($_GET{'sidenum'})) { /* Link from elsewhere */
    $snum = $_GET{'sidenum'};
    $Side = Get_Side($snum);
    if ($Side) {
      $Actyrs = Get_Actyears($snum);
      if (isset($Actyrs[$YEAR])) {
        $Sidey = $Actyrs[$YEAR];
      } else {
        $Sidey = Default_AY();
      }
    } else {
      echo "<h2 class=ERR>Could not find Act $snum</h2>\n";
    }
    $type = 'Act';
    if (!$Side['IsAnAct'] && $Side['IsOther']) $type = 'Other';
  } else {
    $Sidey = Default_AY();
    $type = 'Act';
    if (isset($_GET['t']) && $_GET['t'] =='O') $type = 'Other';
    $Side = ['SideId'=>$snum,'IsASide'=>0,'IsAnAct'=>($type == 'Act'?1:0),'IsOther'=>($type=='Act'?0:1)];
  }

  Show_Part($Side,$type,1,'AddMusic.php');
  Show_Music_Year($snum,$Sidey,$YEAR,$type,1);

  if ($snum > 0) {
    if (Access('SysAdmin')) echo "<div class=floatright><input type=Submit id=smallsubmit name='NewAccessKey' value='New Access Key'></div>\n";
    echo "<Center><input type=Submit name='Update' value='Save Changes'>\n";
//    echo "<a class=buttonlink href=EventAdd.php?Act=$snum>Add an Event</a>";
    echo "</center>\n";
  } else { 
    echo "<Center><input type=Submit name=Create value='Create'></center>\n";
//    echo "Note you can only add an Act to an Event ONCE the Act has been created.<p>\n";
  }
  echo "</form>\n";

  echo Show_Prog('Act',$snum,1);
  echo Extended_Prog('Act',$snum,1);
  dotail();
?>
