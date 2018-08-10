<?php
  include_once("fest.php");
  include_once("DanceLib.php");
    
  $Type = 'Dance';
  $Mess = '';
  if (isset($_GET['T'])) $Type = $_GET['T'];
  if (isset($_POST['T'])) $Type = $_POST['T'];
  
  A_Check('Committee',$Type);
  $TypeSuf = ['Dance'=>'Dance Side', 'Music'=>'Musical Act', 'Other'=> 'Other Performer']; 

  if (isset($_POST['SName'])) {
    if (strlen($_POST['SName']) < 3) {
      $Mess = "Name too short";
    } else {
      $similar = Find_Perf_Similar($_POST['SName']);
      if (!isset($_POST['CONTINUE']) && $similar) {
        dostaffhead("Add Performer - already exists");
        echo "<h2> The following already exist:</h2><ul>";
        foreach ($similar as $i=>$side) {
          if ($side['IsASide']) {
            echo "<li><b><a href=AddDance.php?sidenum=" . $side['SideId'] . ">" . $side['SName'] . "</b> is a ";
            if ($side['SideStatus']) echo " Dead ";
            echo $side['Type'] . " Dance side</a><p>\n";
          } else if ($side['IsAnAct']) {
            echo "<li><b><a href=AddMusic.php?sidenum=" . $side['SideId'] . ">" . $side['SName'] . "</b> is a ";
            if ($side['SideStatus']) echo " Dead ";
            echo $side['Type'] . " Musical Act</a><p>\n";
          } else {
            echo "<li><b><a href=AddDance.php?sidenum=" . $side['SideId'] . ">" . $side['SName'] . "</b> is an ";
            if ($side['SideStatus']) echo " no longer active ";
            echo $side['Type'] . " Other performer</a><p>\n";
          }
        }
        echo "</ul>or <form method=post>" . fm_hidden('SName',$_POST['SName']) . fm_hidden('T',$Type) . "<input type=submit name=CONTINUE value=Continue><p>or<p>";
        
//        dotail();
      } else { // It is new
        switch ($Type) {
        case 'Dance':
          $_POST['IsASide'] = 1;
          break;
        case 'Music':
          $_POST['IsAnAct'] = 1;
          break;
        case 'Other':
        default:
          $_POST['IsOther'] = 1;
          break;
        }
          
        $pnum = Insert_db_post('Sides',$Side);
        $_POST['P'] = $_GET['sidenum'] = $pnum;
        unset($_POST); // For now
        if ($Type == 'Dance') include_once("AddDance.php"); // No return
        if ($Type == 'Other') $_GET['t'] = 'O';
        include_once("AddMusic.php"); // No Return
        
        include_once('ModPerf.php'); // No return  - this is future
      }
    }
  }

  dostaffhead("Add Performer", "/js/clipboard.min.js", "/js/emailclick.js", "/js/Participants.js");

  include_once("MusicLib.php");
  include_once("DateTime.php");
  include_once("PLib.php");

// This is a front end to Add Dance/Music creat the entry after verifying the name is unique then allow for incremental edits to stick
// After verify and save take straight to AddPerf
// 

  if ($Mess) echo "<div class=Err>$Mess</div><p>";
  echo "<form method=post>" . fm_hidden('T',$Type);
  echo fm_text('Name',$_POST,'SName',2);
  echo "<input type=submit name=Create value=Create>";
  echo "</form>";
  
  dotail();

?>
