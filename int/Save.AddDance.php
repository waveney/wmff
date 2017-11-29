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
<script src="/js/clipboard.min.js"></script>
<script src="/js/emailclick.js"></script>
<script src="/js/Participants.js"></script>
<meta http-equiv="cache-control" content="no-cache">
</head>
<body>

<?php
  global $YEAR,$Mess;
  include("files/navigation.php");
  echo '<div class="content"><h2>Add/Edit Dance Side</h2>';
  global $Mess;

//var_dump($_POST);
  $Action = 0; 
  $Mess = '';
/*    User Editing of Side Data */
  if (isset($_POST{'Action'})) {
    include("Uploading.php");
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

//  echo "<!-- " . var_dump($_POST) . " -->\n";
  if (isset($_POST{'SideId'})) { /* Response to update button */
    $snum = $_POST{'SideId'};
    if ($snum > 0) { 				// existing Side 
      $SideQ = $db->query("SELECT * FROM Sides WHERE SideId=$snum");
      $Side = $SideQ->fetch_assoc();
      $SideyearQ = $db->query("SELECT * FROM SideYear WHERE SideId=$snum AND Year=$YEAR");
      if ($SideyearQ) { $Sidey  = $SideyearQ->fetch_assoc(); }
      else { $Sidey = Default_SY(); }

//      $SideyearSQ = $db->query("SELECT * FROM SideYear WHERE SideId=$snum Year<>$YEAR");
//      $Sideyrs = $SideyearSQ->fetch_all();

      if (isset($_POST{'InviteAct'}) || isset($_POST{'ReminderAct'})) {
	date_default_timezone_set('GMT');
	if (strlen($_POST['Invited'])) $_POST['Invited'] .= ", ";
	$_POST['Invited'] .= date('j/n');
      } elseif (isset($_POST{'NewAccessKey'})) $_POST{'AccessKey'} = rand_string(40);
      Clean_Email($_POST{'Email'});
      Clean_Email($_POST{'AltEmail'});
      Update_db_post('Sides',$Side);
      if ($_POST{'Year'} == $YEAR) {
        if ($Sidey) {
          Update_db_post('SideYear',$Sidey);
        } else {
	  $Sidey['Year'] = $YEAR;
	  Insert_db_post('SideYear',$Sidey);
	};
      }
    } else { /* New Side */
      $proc = 1;
      if (!isset($_POST['Name'])) {
	echo "<h2 class=ERR>NO NAME GIVEN</h2>\n";
	$proc = 0;
      }
      $_POST['AccessKey'] = rand_string(40);
      Clean_Email($_POST{'Email'});
      Clean_Email($_POST{'AltEmail'});
      $snum = Insert_db_post('Sides',$Side,$proc);
      if ($snum) {
        $Sidey['Year'] = $YEAR;
        Insert_db_post('SideYear',$Sidey,$proc);
      }
    }

  } elseif (isset($_GET{'sidenum'})) { /* Link from elsewhere */
    $snum = $_GET{'sidenum'};
    $SideQ = $db->query("SELECT * FROM Sides WHERE SideId=$snum");
    if ($SideQ) {
      $Side = $SideQ->fetch_assoc();
      $SideyearQ = $db->query("SELECT * FROM SideYear WHERE SideId=$snum AND Year=$YEAR");
      if ($SideyearQ) { $Sidey  = $SideyearQ->fetch_assoc(); }
      else { $Sidey = Default_SY(); }
//      $SideyearSQ = $db->query("SELECT * FROM SideYear WHERE SideId=$snum Year<>$YEAR");
//      $Sideyrs = $SideyearSQ->fetch_all();
    } else {
      echo "<h2 class=ERR>Could not find Side $snum</h2>\n";
    }
  } else {
    $Sidey = Default_SY();
  }

  Set_Side_Help();
//  echo "<!-- " . var_dump($Side) . " -->\n";
  echo "<input  class=floatright type=Submit name='Update' value='Save Changes' form=mainform>";
  if ($Mess) echo "<h2>$Mess</h2>\n";
//  echo "<h2>Side Information</h2>\n";
  if (isset($Side['Email']) && strlen($Side['Email']) > 5) {
    echo "If you click on the " . linkemailhtml($Side);
    if (isset($Side['AltEmail']) && $Side['AltEmail']) echo " or " . linkemailhtml($Side,'Side','Alt');
    echo ", press control-V afterwards to paste the <button type=button onclick=Copy2Div('Email$snum','SideLink$snum')>standard link</button> and " .
          "<button type=button onclick=Copy2Div('Email$snum','SideProg$snum')>programme</button> into message.<p>";
  }
  echo "<span class=NotSide>Fields marked are not visible to side.</span>";

  echo '<form method=post action="AddDance.php" id=mainform enctype="multipart/form-data">';
  echo "<table width=90% border class=SideTable>\n";
    echo "<tr><th colspan=8><b>Public Information</b>";
    echo "<tr>" . fm_text('Name', $Side,'Name',3,'','autocomplete=off onchange=nameedit(event) oninput=nameedit(event) id=Name');
      $snx = 'class=ShortName';
      if (((isset($Side['Name'])) && (strlen($Side['Name']) > 20) ) || (strlen($Side['ShortName']) != 0)) { 
	if (strlen($Side['ShortName']) == 0) $Side['ShortName'] = substr($Side['Name'],0,20);
      } else {
	$snx .= ' hidden';
      }
      echo fm_text('Grid Name', $Side,'ShortName',1,$snx,$snx . " id=ShortName") . "\n";
      echo fm_text('Type', $Side,'Type') . "\n";

    echo "<tr>" . fm_textarea('Description',$Side,'Description',3,2,'size=150'); 
    echo fm_textarea('Blurb',$Side,'Blurb',3,2,'', 'size=2000' ) . "\n";;
    echo "<tr>";
      if (isset($Side['Website']) && strlen($Side['Website'])>1) {
	echo fm_text(weblink($Side['Website']),$Side,'Website');
      } else {
	echo fm_text('Website',$Side,'Website');
      };
      echo fm_text('Recent Photo',$Side,'Photo',1,'style="min-width:145;"'); 

      echo "<td colspan=4>Select Photo file to upload:";
      echo "<input type=file name=PhotoForm id=PhotoForm onchange=document.getElementById('PhotoButton').click()>";
      echo "<input hidden type=submit name=Action value=Photo id=PhotoButton>";
      if ($Mess && $Action == 'Photo') echo "<br>$Mess\n";
    echo "<tr>";
      if (isset($Side['Video']) && $Side['Video'] != '') {
	echo fm_text('<a href=' . videolink($Side['Video']) . ">Recent Video</a>",$Side,'Video');
      } else {
	echo fm_text('Recent Video',$Side,'Video');
      };
      echo fm_text(Social_Link($Side,'Facebook' ),$Side,'Facebook');
      echo fm_text(Social_Link($Side,'Twitter'  ),$Side,'Twitter');
      echo fm_text(Social_Link($Side,'Instagram'),$Side,'Instagram');

    echo "<tr><th colspan=8><b>Private Information</b>";
    echo "<tr><td class=NotSide>Side Id:";//<td class=NotSide>";
      echo fm_hidden('Cat', 'Side');
      if (isset($snum) && $snum > 0) {
	echo $snum . fm_hidden('SideId',$snum);
        echo fm_hidden('Id',$snum);
      } else {
	echo fm_hidden('SideId',-1);
        echo fm_hidden('Id',-1);
      }
      echo "<td class=NotSide>State:" . fm_select($Side_Statuses,$Side,'SideStatus') . "\n";
      echo "<td class=NotSide colspan=2>Importance:" . fm_select($Importance, $Side,'Importance');
//      echo "<td class=NotSide>Last Checked:" . help('DataCheck'] . "<td class=NotSide>" . $Side['DataCheck'] . "\n";
    echo "<tr>" . fm_text('Contact',$Side,'Contact');
      echo fm_text('Email',$Side,'Email');
      echo fm_text('Phone',$Side,'Phone');
      echo fm_text('Mobile',$Side,'Mobile')."\n";;
    echo "<tr>" . fm_text('Address',$Side,'Address',5);
      echo fm_text('Post Code',$Side,'PostCode')."\n";
    echo "<tr>" . fm_text('Alt Contact',$Side,'AltContact');
      echo fm_text('Alt Email',$Side,'AltEmail');
      echo fm_text('Alt Phone',$Side,'AltPhone');
      echo fm_text('Alt Mobile',$Side,'AltMobile')."\n";;
    echo "<tr>" . fm_textarea('Requests',$Side,'Likes',3,2);
//      echo fm_textarea('Dislikes',$Side,'Dislikes',3,2)."\n";
    echo "<tr><td>Surfaces:" . help('Surfaces');
      for($st=1;$st<5;$st++) {
	$surf = $Surfaces[$st];
	echo "<td>" . fm_checkbox($surf,$Side,"Surface_$surf");
      };
      echo "<td colspan=2>Music Volume: " . fm_select($Noise_Levels,$Side,'NoiseLevel');
    echo "<tr>" . fm_textarea('Workshops',$Side,'Workshops',3,2);
      if ($Side['StagePA'] == '') $Side['StagePA'] = 'None';
      echo fm_textarea('PA Requirements',$Side,'StagePA',3,2);
    echo "<tr>" . fm_text('Animal',$Side,'MorrisAnimal');
 //     echo fm_text('MinStage',$Side,'MinStage');
 //     echo "<td>" . fm_checkbox('Processional Dance',$Side,'ProcessionalDance');
      echo fm_text('Pre2017',$Side,'Pre2017',1,'class=NotSide','class=NotSide'); // This sort of info from SideYear in future
    echo "<tr>" . fm_nontext('Access Key',$Side,'AccessKey',5,'class=NotSide','class=NotSide'); 
      if (Access('SysAdmin') && isset($Side['AccessKey'])) {
        echo "<td class=NotSide><a href=Direct.php?t=Side&id=$snum&key=" . $Side['AccessKey'] . ">Use</a>" . help('Testing');
      }
    echo "<tr>" . fm_textarea('Notes',$Side,'Notes',7,2,'class=NotSide','class=NotSide');

    echo "</table>\n";

//***************** YEAR ***************
  Set_Side_Year_Help();
  echo "<h2>This year $YEAR</h2>\n";
    echo fm_hidden('Year',$YEAR);
    if ($Sidey['syId']) echo fm_hidden('syId',$Sidey['syId']);
    echo "<table width=100% border class=SideTable>\n";
      echo "<tr><td class=NotSide>Invite:<td class=NotSide>" . fm_select($Invite_States,$Sidey,'Invite');
      echo fm_text('Invited',$Sidey,'Invited',1,'class=NotSide');
    echo "<tr><td>Status:<td>" . fm_select($Coming_States ,$Sidey,'Coming');
      echo fm_text('How Many Wristbands',$Sidey,'Performers',0.5);
      echo fm_checkbox("Sent",$Sidey,"WristbandsSent"); 
      echo fm_text('QE Car Park Tickets',$Sidey,'CarPark');
//    echo "<tr>";
//      echo "<td>" . fm_checkbox('Friday',$Sidey,'Sat') . fm_text('Spots',$Sidey,'FriDance');
    echo "<tr><td rowspan=2>Coming on:";
      echo "<td>" . fm_checkbox('Saturday',$Sidey,'Sat') . fm_text('Spots',$Sidey,'SatDance');
      echo "<td>" . fm_checkbox('Plus the Procession',$Sidey,'Procession');
    echo "<tr>";
      echo "<td>" . fm_checkbox('Sunday',$Sidey,'Sun') . fm_text('Spots',$Sidey,'SunDance');
    echo "<tr>";
        echo "<td colspan=3>Select insurance file to upload:";
	echo "<input type=file name=InsuranceForm id=InsuranceForm onchange=document.getElementById('InsuranceButton').click()>";
        echo "<input hidden type=submit name=Action value=Insurance id=InsuranceButton>";

        echo "<td>" . fm_checkbox('Insurance',$Sidey,'Insurance');
      if ($Sidey['Insurance']) {
        $files = glob("Insurance/Sides/$YEAR/$snum.*");
        $Current = $files[0];
        $Cursfx = pathinfo($Current,PATHINFO_EXTENSION );
        echo " <a href=ShowFile.php?l=Insurance/Sides/$YEAR/$snum.$Cursfx>View</a>";
      }
      if ($Mess && $Action == 'Insurance') echo "<td colspan=2>$Mess\n";
    echo "<tr>";
      echo "<td>Shared Spots:<td>" . fm_select($Share_Spots,$Sidey,'Share');
      echo fm_text('Earliest Spot',$Sidey,'Arrive');
      echo fm_text('Latest Spot',$Sidey,'Depart');
    echo "<tr><td>Dancer Overlaps:" . help('OverlapsD');
      echo "<td colspan=6>" . fm_select(Sides_All($snum),$Sidey,'Overlap1',1);
      echo fm_select(Sides_All($snum),$Sidey,'Overlap2',1);
    echo "<tr><td>Musician Overlaps:" . help('OverlapsM');
      echo "<td colspan=6>" .fm_select(Sides_All($snum),$Sidey,'Overlap3',1);
      echo fm_select(Sides_All($snum),$Sidey,'Overlap4',1);
    echo "<tr>" . fm_textarea('Notes',$Sidey,'YNotes',7,2);
    echo "<tr>" . fm_textarea('Private Notes',$Sidey,'PrivNotes',7,2,'class=NotSide','class=NotSide');
    echo "</table>\n";
    

  if (isset($SideyearSQ)) { // Not used or developed in 2017
    echo "<h2>Other years</h2>\n";
  }

  if ($snum > 0) {
    echo "<div class=floatright><input type=Submit id=smallsubmit name='NewAccessKey' value='New Access Key'></div>\n";
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

  echo Show_Prog('Side',$snum,0,1);

?>

</div>

<?php include("files/footer.php"); ?>
</body>
</html>
