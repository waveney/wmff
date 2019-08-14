<?php
  include_once("fest.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php"); // TODO Merge two libs
  include_once("DateTime.php");
  include_once("ProgLib.php");
  include_once("PLib.php");

// TODO change for all access types inc participant
  global $USER,$USERID,$Access_Type;
  // 2D Access check hard coded here -- if needed anywhere else move to fest
  if (isset($_REQUEST['SideId'])) { $snum = $_REQUEST['SideId']; }
  elseif (isset($_REQUEST['sidenum'])) { $snum = $_REQUEST['sidenum']; }
  elseif (isset($_REQUEST['id'])) { $snum = $_REQUEST['id'];} 
  elseif (isset($_REQUEST['i'])) { $snum = $_REQUEST['i'];} 
  else { $snum = 0; }
  switch ($USER['AccessLevel']) {
  case $Access_Type['Participant'] : 
    if ($USER['Subtype'] != 'Perf' && $USER['Subtype'] != 'Side'  && $USER['Subtype'] != 'Act' && $USER['Subtype'] != 'Other') Error_Page("Not accessable to you");  // TODO Side-Other can be deleted in time
    if ($snum != $USERID) Error_Page("Not accessable to you");
    break;

  case $Access_Type['Upload'] :
  case $Access_Type['Steward'] :
    Error_Page("Not accessable to you");

  case $Access_Type['Staff'] :
  case $Access_Type['Committee'] :
    $capmatch = 0;
    $Side = Get_Side($snum);
    foreach ($FestTypes as $p=>$d) if ($Side[$d[0]] && $USER[$d[2]]) $capmatch = 1;
    if (!$capmatch) fm_addall('disabled readonly');    
    break;

  case $Access_Type['Internal'] : 
  case $Access_Type['SysAdmin'] : 
    break;
  }  

  dostaffhead("Add/Change Performer", ["/js/clipboard.min.js", "/js/emailclick.js", "/js/Participants.js","js/dropzone.js","css/dropzone.css", "js/InviteThings.js"]);
  global $YEAR,$PLANYEAR,$Mess,$BUTTON;  // TODO Take Mess local

  echo '<h2>Add/Edit Performer</h2>'; // TODO CHANGE
  global $Mess,$Action,$Dance_TimeFeilds;
  $DateFlds = ['ReleaseDate'];
// var_dump($_POST);
// TODO Change this to not do changes at a distance and needing global things
  $Action = ''; 
  $Mess = '';
  if (isset($_POST['Action'])) {
    include_once("Uploading.php");
    $Action = $_POST['Action'];
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
    case (preg_match('/DeleteOlap(\d*)/',$Action,$mtch)?true:false):
      // Delete Olap
      $snum=$_POST['SideId'];
      $olaps = Get_Overlaps_For($snum);
//      echo "<br>"; var_dump($olaps);
      if (isset($olaps[$mtch[1]])) {
        db_delete("Overlaps",$olaps[$mtch[1]]['id']);
      } 
      break;
    case 'TICKBOX':

      break; // Action is taken later after loading
    
    default:
      $Mess = "!!!";
    }
  }
//  echo "<!-- " . var_dump($_POST) . " -->\n";
  if (isset($_POST{'SideId'})) { // Response to update button 
    
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
      } elseif (isset($_POST{'Contract2'})) { 
        Contract_Save($Side,$Sidey,2,1); 
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
//      UpdateBand($snum);
      Report_Log("Dance"); // TODO Dance needs to depend on IsAs
//      UpdateOverlaps($snum);
    } else { //New Side
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

  } elseif ($snum > 0) { //Link from elsewhere 
    $Side = Get_Side($snum);
    if ($Side) {
      $Sideyrs = Get_Sideyears($snum);
      if (isset($Sideyrs[$YEAR])) {
        $Sidey = $Sideyrs[$YEAR];
      } else {
        $Sidey = Default_SY();
      }
      
      if (isset($_POST['TICKBOX'])) {
        switch ($_POST['TICKBOX']) {
        case 1: case 2: case 3: case 4:
          $Sidey["TickBox" . $_POST['TICKBOX']] = 1;
          break;
          
        case 'Rec': 
          if (!isset($Sidey['Coming']) || !$Sidey['Coming'] ) $Sidey['Coming'] = 1;
          break;
          
        default:
          echo "<h2>Unrecognised Button</h2>";
          
        }
        Put_SideYear($Sidey);
        echo "<h2>Thankyou for recording that, your other records are below</h2>";
      }
      
    } else {
      echo "<h2 class=ERR>Could not find Performer $snum</h2>\n";
    }
  } else {
    $Sidey = Default_SY();
    $Side = ['SideId'=>$snum]; 
  }

  Show_Part($Side,'Side',Access('Staff'),'AddPerf');
  Show_Perf_Year($snum,$Sidey,$YEAR,Access('Staff'));

  if ($snum > 0) {
    if (Access('SysAdmin')) {
      echo "<div class=floatright>";
      echo "<input type=Submit id=smallsubmit name='NewAccessKey' class=Button$BUTTON value='New Access Key'>";
      echo "<input type=Submit id=smallsubmit name='Contract2' class=Button$BUTTON value='Confirm Contract'>";
      echo "</div>\n";
    }
    echo "<Center><input type=Submit name='Update' value='Save Changes' class=Button$BUTTON >\n";
    if (Access('Staff','Dance')) {
      if (!isset($Sidey['Coming']) || $Sidey['Coming'] == 0) {
        if (!isset($Sidey['Invited']) || $Sidey['Invited'] == '') {
//          echo " <input type=submit name=InviteAct value=Invite  class=Button$BUTTON > ";
        } else {
//          echo " <input type=submit name=ReminderAct value=Reminder class=Button$BUTTON > ";
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
