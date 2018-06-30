<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("Add/Change Timeline Entry");

  include_once("DocLib.php");
  include_once("TLLib.php");
  global $USERID,$USER,$YEAR,$TL_States,$TL_State,$TL_Importance;

  Set_TimeLine_Help();

  $AllU = Get_AllUsers(0);
  $AllA = Get_AllUsers(1);
  $AllActive = array();
  foreach ($AllU as $id=>$name) if ($AllA[$id] >= 2 && $AllA[$id] <= 6) $AllActive[$id]=$name;
  $now = time();

  echo "<div class='content'><h2>Add/Edit Time Line Items</h2>\n";
  echo "<form method=post action=AddTimeLine.php>\n";
  if (isset($_POST{'TLid'})) { // Response to update button
    $tl = $_POST{'TLid'};
    if ($tl > 0) {
      $tle = Get_TLent($tl);
      if (isset($_POST{'ACTION'})) {
        switch ($_POST{'ACTION'}) {
          case 'Completed':
            $_POST{'State'} = $TL_State['Completed'];
            $_POST{'History'} .= " Completed by " . $USER['Login'] . " on " . date('d/m/Y');
            break;
          case 'Re Open':
            $_POST{'State'} = $TL_State['Open'];
            $_POST{'History'} .= " Re Opened by " . $USER['Login'] . " on " . date('d/m/Y');
            break;
          case 'Cancel':
            $_POST{'State'} = $TL_State['Cancelled'];
            $_POST{'History'} .= " Cancelled by " . $USER['Login'] . " on " . date('d/m/Y');
            break;
        }
      }
      $_POST['Due'] = Date_BestGuess($_POST['NewDue']);
      Update_db_post('TimeLine',$tle);
    } else { // New
      $proc = 1;
      $_POST['Created'] = time();
      $_POST['CreatedBy'] = $USERID;
      $_POST['Due'] = Date_BestGuess($_POST['NewDue']);
      if (!isset($_POST['Title'])  || strlen($_POST['Title']) < 2) { // 
        echo "<h2 class=ERR>NO TITLE GIVEN</h2>\n";
        $proc = 0;
      }
      $tl = Insert_db_post('TimeLine',$tle,$proc); 
    }
  } elseif (isset($_GET{'TLid'})) {
    $tl = $_GET{'TLid'};
    $tle = Get_TLent($tl);
  } else {
    $tl = -1;
    $tle = array();
    $tle['Assigned'] = $USERID;
    $tle['CreatedBy'] = $USERID;
    $tle['Created'] = time();
    $tle['Year'] = $YEAR;
  }

  if (isset($Err)) echo "<h2 class=ERR>$Err</h2>\n";

  if (!$Skip) {
    echo "<table width=90% border>\n";
      echo "<tr>" . fm_text('Title',$tle,'Title',3,'','placeholder="Please give entry a short Title"');
      if (isset($tl) && $tl > 0) {
        echo "<td>Id:<td>";
        echo $tl . fm_hidden('TLid',$tl);
        echo "<td>For " . $tle['Year'];
        echo fm_hidden('Year',$tle['Year']);
      } else {
        echo fm_hidden('TLid',-1);
        echo fm_hidden('Year',$tle['Year']);
      }

      // Hide stored, display munged from stored if on update convert to stored
      $CurDue['NewDue'] = ($tle['Due']?date('d/m/Y',$tle['Due']):"");
      echo "<tr>" . fm_hidden('Due',$tle['Due']) . fm_text("Due by",$CurDue,'NewDue');
      echo "<td>Put a Month eg Jan or January (will be end of) or a date as in 20/1 or 20th Jan or 20/1/18 or Jan 20(th).";

      echo "<tr><td>Assigned to:<td>" . fm_select($AllActive,$tle,'Assigned',1);
      if (isset($tle['Due']) && $tle['Due'] > 0 && $now > $tle['Due']) {
              echo "<td class=red>OVERDUE\n";
      }
      echo "<tr><td>Importance:<td>" . fm_select($TL_Importance,$tle,'Importance');
      echo "<tr>" . fm_textarea("Notes",$tle,'Notes',8,2);
      
      echo "<tr><td>Created by:<td>" . $AllU[$tle['CreatedBy']] . " On " . date('d/m/Y',$tle['Created']);
      if ($tle['Completed'] < 0) {
              echo "<tr><td>State:<td>Cancelled\n";
      } else if ($tle['Completed'] == 0) {
              echo "<tr><td>State:<td>Open\n";
      } else {
        echo "<tr><td>Completed On<td>" . date('d/m/Y',$tle['Completed']);
      }

      echo "<tr>" . fm_textarea('Progress',$tle,'Progress',8,2);

      echo "<tr>" . fm_textarea('History',$tle,'History',8,2);

      echo "</table>\n";
  
    if ($tl > 0) {
      echo "<Center><input type=Submit name='Update' value='Update'>\n";
      echo "<input type=Submit name='ACTION' value='Completed'>\n";
      echo "<input type=Submit name='ACTION' value='Re Open'>\n";
      echo "<input type=Submit name='ACTION' value='Cancel'>\n";
      echo "<input type=Submit name='ACTION' value='Add Another'>\n";
      echo "</center>\n";
    } else { 
      echo "<Center><input type=Submit name=Create value='Create'></center>\n";
    }
    echo "</form>\n";
  }
  echo "<h2><a href=TimeLine.php>Back to Time Line Management</a></h2>\n</div>";

  dotail();
?>
