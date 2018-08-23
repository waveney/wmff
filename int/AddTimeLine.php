<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("Add/Change Timeline Entry", "js/jquery-ui.min.js", "css/jquery-ui.min.css", "js/timeline.jsdefer");

  include_once("DocLib.php");
  include_once("TLLib.php");
  global $USERID,$USER,$YEAR,$TL_Importance,$PLANYEAR;

  Set_TimeLine_Help();

  $All = Get_AllUsers(2);
  $AllActive = [];
  foreach ($All as $usr) {
    $id = $usr['UserId'];
    if ($usr['AccessLevel'] >=2 && $usr['AccessLevel']<= 6 && $usr['NoTasks']==0 ) $AllActive[$id] = $usr['SName'];  
  }

  $now = time();
  $Editable = Access('Committee','Tline');
//var_dump($All);echo"<p>";var_dump($AllActive);


  echo "<div class='content'><h2>Add/Edit Time Line Items</h2>\n";

  if (isset($_POST['TLid'])) { // Response to update button
    $tl = $_POST['TLid'];
    if ($tl > 0) {
      $tle = Get_TLent($tl);
      $proc = 1;
      if (isset($_POST['ACTION'])) {
        $otl = Get_TLent($_POST['TLid']);
        
        switch ($_POST['ACTION']) {
          case 'Completed':
            $_POST['Progress'] = 100;
            $_POST['Completed'] = $now;
            $_POST['History'] .= " Completed by " . $USER['Login'] . " on " . date('d/m/Y');
            break;
          case 'Re Open':
            $_POST['Progress'] = (($otl['Progress']>50)?50:0);
            $_POST['History'] .= " Re Opened by " . $USER['Login'] . " on " . date('d/m/Y');
            break;
          case 'Cancel':
            $_POST['Progress'] = -1;
            $_POST['History'] .= " Cancelled by " . $USER['Login'] . " on " . date('d/m/Y');
            break;
          case "Copy to $PLANYEAR":
            $_POST['TLid'] = -1;
            $_POST['Year'] = $PLANYEAR;
            $_POST['Progress'] = 0;
            $_POST['Created'] = $now;
            $_POST['CreatedBy'] = $USERID;
            $_POST['Start'] =  ((isset($_POST['Start']) && $_POST['Start']>0) ? strtotime(date("Y-m-d",$_POST['Start']) . " + 365 day") : $now);
            $_POST['Due'] =  strtotime(date("Y-m-d", ((isset($_POST['Due']) && $_POST['Due'] > 0) ? $_POST['Due']: $now)) . " + 365 day");
            $_POST['ProgText'] = $_POST['History'] = '';
            $tl = Insert_db_post('TimeLine',$tle,$proc);
            $otl['NextYearId'] = $tl;
            Put_TLent($otl);
            $proc = 0;
            break;

        }
      }
      if ($proc) {
        $_POST['Start'] = Date_BestGuess($_POST['NewStart']);
        $_POST['Due'] = Date_BestGuess($_POST['NewDue']);
        Update_db_post('TimeLine',$tle);
      }
    } else { // New
      $proc = 1;
      $_POST['Created'] = $now;
      $_POST['CreatedBy'] = $USERID;
      $_POST['Start'] = Date_BestGuess($_POST['NewStart']);
      $_POST['Due'] = Date_BestGuess($_POST['NewDue']);
      if (!isset($_POST['Title'])  || strlen($_POST['Title']) < 2) { // 
        echo "<h2 class=ERR>NO TITLE GIVEN</h2>\n";
        $proc = 0;
      }
      $tl = Insert_db_post('TimeLine',$tle,$proc); 
    }
  } elseif (isset($_GET['TLid'])) {
    $tl = $_GET['TLid'];
    $tle = Get_TLent($tl);
  } else {
    $tl = -1;
    $tle = array();
    $tle['Assigned'] = $USERID;
    $tle['CreatedBy'] = $USERID;
    $tle['Created'] = $now;
    $tle['Year'] = $PLANYEAR;
    $tle['Progress'] = 0;
  }

  if (isset($Err)) echo "<h2 class=ERR>$Err</h2>\n";

  if ($Editable || $tl < 0 || $tle['Assigned'] == $USERID || $tle['CreatedBy'] == $USERID) $Editable = true;
  if (1) {
    if ($Editable) {
      echo "<form method=post action=AddTimeLine.php>\n";
    } else {
      echo "This can only be editted by the creator, the assignee or those responsible for time lines.<p>";
    }
    echo "<table width=90% border>\n";
      echo "<tr>" . fm_text('Title',$tle,'Title',2,'','placeholder="Please give entry a short Title"');
      if (isset($tl) && $tl > 0) {
        echo "<td>Id:<td>";
        echo $tl . fm_hidden('TLid',$tl);
      } else {
        echo fm_hidden('TLid',-1);
      }


      // Hide stored, display munged from stored if on update convert to stored
      $CurDue['NewStart'] = date('d/m/Y',(isset($tle['Start']) && $tle['Start'] != 0)?$tle['Start']:$now);
      echo "<tr>" . (isset($tle['Start'])?fm_hidden('Start',$tle['Start']):"") . fm_text("Start",$CurDue,'NewStart');
      $CurDue['NewDue'] = date('d/m/Y',(isset($tle['Due']) && $tle['Due'] != 0)?$tle['Due']:$now);
      echo "<tr>" . (isset($tle['Due'])?fm_hidden('Due',$tle['Due']):"") . fm_text("Due by",$CurDue,'NewDue');
      echo "<td>Put a Month eg Jan or January (will be end of) or a date as in 20/1 or 20th Jan or 20/1/18 or Jan 20(th).";
      echo fm_number('For',$tle,'Year','',' min=2016 max=2099 ');

      echo "<tr><td>Assigned to:<td>" . fm_select($AllActive,$tle,'Assigned',1);
        echo "<td>Created by: ";
        echo ((isset($tle['CreatedBy']) && ($tle['CreatedBy'] != 0)) ? ($AllActive[$tle['CreatedBy']]) : "UNKNOWN" );
        echo " On " . date('d/m/Y',$tle['Created']);
      
      if ((isset($tle['Due']) && $tle['Due'] > 0 && $now > $tle['Due'] && $tle['Progress']< 100) || ((isset($tle['Start']) && $tle['Start'] > 0 && $now > $tle['Start'] && $tle['Progress'] == 0))) {
        echo "<td class=red>OVERDUE\n";

      }
      echo "<tr><td>Importance:<td>" . fm_select($TL_Importance,$tle,'Importance');
        echo "<td>" . fm_checkbox("Recuring",$tle,'Recuring');
        if (isset($tle['NextYearId']) && ($tle['NextYearId']>0)) echo "<td><a href=AddTimeLine.php?Y=$YEAR&TLid=" . $tle['NextYearId'] . ">Copied</a>";

      echo "<tr>" . fm_textarea("Notes",$tle,'Notes',8,2);
      
      echo "<tr>" . TL_State($tle,1);
        if ($tle['Progress'] >= 100) {
          echo " On<td>" . date('d/m/Y',$tle['Completed']);
        } else {
          echo "<td><div style='max-width=500; overflow: contain'><div  id=slider class=slider></div></div>";
        }
        
      echo "<tr>" . fm_textarea('Progress Text',$tle,'ProgText',8,2);

      echo "<tr>" . fm_textarea('History',$tle,'History',8,2);

      echo "</table>\n";
  
    if ($Editable) {
      if ($tl > 0) {
        echo "<Center><input type=Submit name='Update' value='Update'>\n";
        echo "<input type=Submit name='ACTION' value='Completed'>\n";
        echo "<input type=Submit name='ACTION' value='Re Open'>\n";
        echo "<input type=Submit name='ACTION' value='Cancel'>\n";
        echo "<input type=Submit name='ACTION' value='Add Another'>\n";
        if ((!isset($tle['Year']) || $tle['Year'] != $PLANYEAR ) && ($tle['NextYearId'] == 0)) echo "<input type=Submit name=ACTION value='Copy to $PLANYEAR'>\n";
        echo "</center>\n";
      } else { 
        echo "<Center><input type=Submit name=Create value='Create'></center>\n";
      }
      echo "</form>\n";
    }
  }
  echo "<h2><a href=TimeLine.php>Back to Time Line Management</a></h2>\n</div>";
  
  

  dotail();
?>
