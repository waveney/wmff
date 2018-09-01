<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("Add/Change Bug");
  include_once("DocLib.php");
  include_once("BugLib.php");
  global $USERID,$USER;

  Set_Bug_Help();

  $AllU = Get_AllUsers(0);
  $AllA = Get_AllUsers(1);
  $AllActive = array();
  foreach ($AllU as $id=>$name) if ($AllA[$id] >= 2 && $AllA[$id] <= 6) $AllActive[$id]=$name;
  $Skip = 0;

  echo "<div class='content'><h2>Add/Edit Bugs</h2>\n";
  echo "<form method=post action='AddBug.php'>\n";
  if (isset($_POST{'BugId'})) { // Response to update button
    $b = $_POST{'BugId'};
    if ($b > 0) $Bug = Get_Bug($b);
    if (isset($_POST{'ACTION'})) {
      switch ($_POST{'ACTION'}) {
      case 'Divide':
        break;
      case 'Delete':
        break;
      }
    } elseif ($b > 0) {         // existing Event
      Update_db_post('Bugs',$Bug);
    } else { // New
      $proc = 1;
      $_POST['Created'] = time();
      if (!isset($_POST['SN'])  || strlen($_POST['SN']) < 2) { // 
        echo "<h2 class=ERR>NO NAME GIVEN</h2>\n";
        $proc = 0;
      }
      $b = Insert_db_post('Bugs',$Bug,$proc); 
    }
    if (!$USER['Bugs'] && $ReportSevs[$Bug['Severity']] && !file_exists('testing')) {
      SendEmail(Get_Emails('Bugs'),"WMFF Bug report by " .$USER['SN'],json_encode($Bug));
    }
  } elseif (isset($_GET{'b'})) {
    $b = $_GET{'b'};
    $Bug = Get_Bug($b);
  } else {
    $b = -1;
    $Bug = array();
    $Bug['Who'] = $USERID;
    $Bug['Created'] = time();
  }

  if (isset($Err)) echo "<h2 class=ERR>$Err</h2>\n";

//var_dump($Bug);

  if (!$Skip) {
    echo "<table width=90% border>\n";
      if (isset($b) && $b > 0) {
        echo "<tr><td>Bug Id:<td>";
        echo $b . fm_hidden('BugId',$b);
      } else {
        echo fm_hidden('BugId',-1);
      }
      echo fm_text('Title',$Bug,'SN',2,'','placeholder="Please give bug a short name"');
      echo  "<td>Severity:" . fm_select($Severities, $Bug,'Severity');
      echo "<td>State:" . fm_select($Bug_Status,$Bug,'State');
      echo "<tr><td>Raised by:<td>" . fm_select($AllActive,$Bug,'Who'); 
        echo "<td><td>Created:" . date('d/m/y H:i:s',$Bug['Created']);

      echo "<tr>" . fm_textarea('Description',$Bug,'Description',5,10);
      echo "<tr>" . fm_textarea('Response',$Bug,'Response',5,10);

      echo "</table>\n";
  
    if ($b > 0) {
      echo "<Center><input type=Submit name='Update' value='Update'>\n";
      echo "</center>\n";
    } else { 
      echo "<Center><input type=Submit name=Create value='Create'></center>\n";
    }
    echo "</form>\n";
  }
  echo "<h2><a href=ListBugs.php>List Bugs/Feature Requests</a></h2>\n";

  dotail();
?>
