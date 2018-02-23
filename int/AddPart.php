<?php
  include_once("fest.php");
  A_Check('Committee','Other');
?>

<html>
<head>
<title>WMFF Staff | Add/Change Participant</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("PartLib.php"); ?>
<script src="/js/clipboard.min.js"></script>
<script src="/js/emailclick.js"></script>
<script src="/js/Participants.js"></script>
</head>
<body>

<?php
  global $YEAR, $PTypes;
  if (isset($_GET['T'])) { $type = $PTypes[$_GET['T']]; }
  else if (isset($_POST['T'])) { $type = $PTypes[$_POST['T']]; }
  else $type = "Other";

  include("files/navigation.php");
  echo '<div class="content"><h2>Add/Edit Participant</h2>';

//var_dump($_POST);
  if (isset($_POST{'NewAccessKey'})) $_POST{'AccessKey'} = rand_string(40);
  if (isset($_POST{'OtherId'})) { /* Response to update button */
    $onum = $_POST{'OtherId'};
    if ($onum > 0) { 				// existing 
      $Other = Get_Other_Person($onum);
      $OtherY = Get_Other_Person_Year($onum);

      Update_db_post('OtherPart',$Other);
      $_POST['Other'] = $onum;
      if ($_POST{'Year'} == $YEAR) {
        if (isset($OtherY['OpyId']) && $OtherY['OpyId'] > 0 ) {
          Update_db_post('OtherPartYear',$OtherY);
        } else {
	  $_POST['Year'] = $YEAR;
	  Insert_db_post('OtherPartYear',$OtherY);
	};
      }
    } else { /* New  */
      $proc = 1;
      if (!isset($_POST['SName'])) {
	echo "<h2 class=ERR>NO NAME GIVEN</h2>\n";
	$proc = 0;
      }
      $_POST['AccessKey'] = rand_string(40);
      $onum = Insert_db_post('OtherPart',$Other,$proc);
      if ($onum) {
        $_POST['Year'] = $YEAR;
        $_POST['Other'] = $onum;
        Insert_db_post('OtherPartYear',$OtherY,$proc);
      }
    }

  } elseif (isset($_GET{'othernum'})) { /* Link from elsewhere */
    $onum = $_GET{'othernum'};
    $Other = Get_Other_Person($onum);
    $OtherY = Get_Other_Person_Year($onum);
  } else {
    $OtherY = array();
    $onum = -1;
  }

  Set_Other_Help();
  echo "<input class=floatright type=Submit name='Update' value='Save Changes' form=mainform>";
  if (isset($Other['Email']) && $Other['Email']) {
    echo "If you click on the " . linkemailhtml($Other,'Other');
    if (isset($Other['AltEmail']) && $Other['AltEmail']) echo " or " . linkemailhtml($Other,'Other','Alt');
    echo ", press control-V afterwards to paste the <button type=button onclick=Copy2Div('Email$onum','SideLink$onum')>standard link</button> and " .
          "<button type=button onclick=Copy2Div('Email$onum','SideProg$onum')>programme</button> into message.<p>";
  }
  echo "<span class=NotSide>Fields marked are not visible to participant.</span><p>";
  
  echo '<form method=post action="AddPart.php">';
  echo "<table width=90% border class=SideTable>\n";
    echo "<tr><th colspan=8><b>Public Information</b>";
    echo "<tr>" . fm_text('SName', $Other,'SName',3,'','autocomplete=off');
      echo fm_text('Short Name', $Other,'ShortName') . "\n";
      echo fm_text('Type', $Other,'Type') . "\n";

    echo "<tr>" . fm_textarea('Description',$Other,'Description',3,2,'size=150'); 
    echo fm_textarea('Blurb',$Other,'Blurb',3,2,'', 'size=2000' ) . "\n";;
    echo "<tr>";
      if (isset($Side['Website']) && strlen($Side['Website'])>1) {
	echo fm_text(weblink($Side['Website']),$Side,'Website');
      } else {
	echo fm_text('Website',$Side,'Website');
      };
      if (isset($Side['Video']) && $Side['Video'] != '') {
	echo fm_text('<a href=' . videolink($Side['Video']) . ">Recent Video</a>",$Side,'Video');
      } else {
	echo fm_text('Recent Video',$Side,'Video');
      };
      echo fm_text('Recent Photo',$Side,'Photo'); /* Place holder for doing it properly */
    echo "<tr>";
      echo fm_text(Social_Link($Side,'Facebook' ),$Side,'Facebook');
      echo fm_text(Social_Link($Side,'Twitter'  ),$Side,'Twitter');
      echo fm_text(Social_Link($Side,'Instagram'),$Side,'Instagram');

    echo "<tr><th colspan=8><b>Private Information</b>";
    echo "<tr><td class=NotSide>Id:";//<td class=NotSide>";
      if (isset($onum) && $onum > 0) {
	echo $onum . fm_hidden('OtherId',$onum);
      } else {
	echo fm_hidden('OtherId',-1);
      }
      echo "<td class=NotSide>State:" . fm_select($Side_Statuses,$Other,'OtherStatus') . "\n";
      echo "<td class=NotSide colspan=2>Importance:" . fm_select($Importance, $Other,'Importance');
    echo "<tr>" . fm_text('Contact',$Other,'Contact');
      echo fm_text('Email',$Other,'Email');
      echo fm_text('Phone',$Other,'Phone');
      echo fm_text('Mobile',$Other,'Mobile')."\n";;
    echo "<tr>" . fm_text('Address',$Other,'Address',5);
      echo fm_text('Post Code',$Other,'PostCode')."\n";
    echo "<tr>" . fm_text('Alt Contact',$Other,'AltContact');
      echo fm_text('Alt Email',$Other,'AltEmail');
      echo fm_text('Alt Phone',$Other,'AltPhone');
      echo fm_text('Alt Mobile',$Other,'AltMobile')."\n";
    echo "<tr>" . fm_nontext('Access Key',$Other,'AccessKey',5,'class=NotSide'); 
      if (Access('SysAdmin') && isset($Other['AccessKey'])) {
        echo "<td class=NotSide><a href=Direct.php?t=Other&id=$onum&key=" . $Other['AccessKey'] . ">Use</a>" . help('Testing');
      }
    echo "<tr>" . fm_textarea('Notes',$Other,'Notes',7,2,'class=NotSide','class=NotSide');

    echo "</table>\n";

//***************** YEAR ***************
  Set_Other_Year_Help();
  echo "<h2>This year $YEAR</h2>\n";
    echo fm_hidden('Year',$YEAR);
    if (isset($OtherY['OpyId'])) echo fm_hidden('OpyId',$OtherY['OpyId']);
    echo "<table width=100% border class=SideTable>\n";
    echo "<tr>";
      echo "<td>" . fm_checkbox('Fri',$OtherY,'Fri');
      echo "<td>" . fm_checkbox('Sat',$OtherY,'Sat');
      echo "<td>" . fm_checkbox('Sun',$OtherY,'Sun');
    echo "<tr>";
      echo "<td>" . fm_checkbox('Insurance',$OtherY,'Insurance');
      if (glob("Insurance/Other/$YEAR/$onum.*")) echo " <a href=ViewInsurance.php?othernum=$onum>View</a>";
      echo fm_text('Number of Performers',$OtherY,'Performers');
      echo fm_text('Car Park Tickets',$OtherY,'CarPark');
    echo "<tr>";
    echo "<tr>" . fm_textarea('Notes',$OtherY,'YNotes',7,2);
    echo "<tr>" . fm_textarea('Private Notes',$OtherY,'PrivNotes',7,2,'class=NotSide','class=NotSide');
    echo "</table>\n";
    

  if (isset($SideyearSQ)) { // Not used or developed in 2017
    echo "<h2>Other years</h2>\n";
  }

  if ($onum > 0) {
    echo "<div class=floatright><input type=Submit id=smallsubmit name='NewAccessKey' value='New Access Key'></div>\n";
    echo "<Center><input type=Submit name='Update' value='Save Changes'>\n";
    echo "</center>\n";
  } else { 
    echo "<Center><input type=Submit name=Create value='Create'></center>\n";
  }
  echo "</form>\n";

  if ($onum > 0) {
    echo "<table><tr><td>";
    echo "<h2>Upload Photo</h2>";
    if ($Mess && $Where == 'Photo') echo "<h2>$Mess</h2>\n";
    echo '<form action="DancePhoto.php" method="post" enctype="multipart/form-data">';
    echo "Select image to upload:";
    echo fm_hidden('Type', 'Other');
    echo fm_hidden('Id', $onum);
    echo fm_hidden('From','AddPart.php');
    echo '<input type="file" name="fileToUpload" id="fileToUpload" onchange=this.form.submit() >';
    echo "</form><td>\n";
    echo "<h2>Upload Insurance</h2>";
    if ($Mess && $Where == 'Insurance') echo "<h2>$Mess</h2>\n";
    echo '<form action="DanceInsurance.php" method="post" enctype="multipart/form-data">';
    echo "Select file to upload:";
    echo fm_hidden('Type', 'Other');
    echo fm_hidden('Id', $onum);
    echo fm_hidden('From','AddPart.php');
    echo '<input type="file" name="fileToUpload" id="fileToUpload" onchange=this.form.submit() >';
    echo "</form>\ni</table>";
  }

  echo Show_Prog('Other',$onum);

?>

</div>

<?php include("files/footer.php"); ?>
</body>
</html>
