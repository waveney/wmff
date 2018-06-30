<?php
  include_once("fest.php");

  dostaffhead("Side Editing","/js/Participants.js" );
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("DateTime.php");
  include_once("PLib.php");
  global $Mess,$Action,$MASTER,$Cat_Type,$YEAR,$THISYEAR,$Dance_TimeFeilds;      

//var_dump($_POST);
  $Action = 0; 
  $Mess = '';
/*    User Editing of Side Data */ ////////// DUFF
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

  if (isset($_POST{'SideId'})) { /* Response to update button */
    $snum = $_POST{'SideId'};
    A_Check('Participant','Side',$snum);
    if ($snum > 0) {                                 // existing Side 
      $Side = Get_Side($snum);
      if ($Side) {
        $Sideyrs = Get_Sideyears($snum);
        if (isset($Sideyrs[$YEAR])) $Sidey = $Sideyrs[$YEAR];
      } else {
        echo "<h2 class=ERR>Could not find Side $snum</h2>\n";
      }

      Clean_Email($_POST{'Email'});
      Clean_Email($_POST{'AltEmail'});
      Parse_TimeInputs($Dance_TimeFeilds);      

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

      Update_db_post('Sides',$Side);
      if ($_POST{'Year'} >= $THISYEAR) {
        if (isset($Sidey) && $Sidey){
          Update_db_post('SideYear',$Sidey);
        } else {
          $Sidey['Year'] = $THISYEAR;
          $syId = Insert_db_post('SideYear',$Sidey);
          $Sidey['syID'] = $syId;
        };
      }
      UpdateBand($snum);
      Report_Log('Dance');
    }
  } elseif (isset($_GET{'sidenum'})) { /* Link from elsewhere */
    $snum = $_GET{'sidenum'};
    A_Check('Participant','Side',$snum);
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
  } elseif (isset($_GET{'id'})) { /* Link from elsewhere */
    $snum = $_GET{'id'};
    A_Check('Participant','Side',$snum);
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
  }

  Show_Part($Side,'Side',0,'DanceEdit.php');
  Show_Part_Year($snum,$Sidey,$YEAR,'Side',0);
    
//  echo "<button onclick=ShowAdv(event) id=ShowMore type=button class=floatright>More features</button>";
  echo "<center><input type=Submit name='Update' value='Save Changes'></center>\n";
  echo "</form>\n";

  echo Show_Prog('Side',$snum);
  echo Extended_Prog('Side',$snum);

  dotail();
?>
