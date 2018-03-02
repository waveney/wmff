<?php
  include_once("fest.php");
//  A_Check('Steward');

  global $DAY;
  include_once("NewProgramLib.php");

  $Cond = 0;
  if (isset($_GET{'Cond'})) $Cond = $_GET{'Cond'};
  if (isset($_POST{'Cond'})) $Cond = $_POST{'Cond'};

  $day = "All";
  if (isset($_GET{'Day'})) $day = $_GET{'Day'};

  $head = 1;
  if (isset($_GET{'Head'})) $head = $_GET{'Head'};

  $Public='';
  if (isset($_GET{'Pub'})) $Public=1;

  Prog_Headers($Public,$head,'Music');
  if ($day != 'Fri') {
    Grab_Music_Data('Fri','Music');
    Scan_Data($Cond,'Music');
    if ($Public) echo "<p><h2>Friday Music</h2><p>\n";
    Create_Grid($Cond,'Music');
    Print_Grid(0,0,$Cond,$Public,'Music');
  }
  if ($day != 'Sat') {
    Grab_Music_Data("Sat",'Music');
    Scan_Data($Cond,'Music');
    echo "<p><h2>Saturday Music</h2></p>\n";
    Create_Grid($Cond,'Music');
    Print_Grid(0,0,$Cond,$Public,'Music');
  }
  if ($day != 'Sun') {
    Grab_Music_Data("Sun",'Music');
    Scan_Data($Cond,'Music');
    echo "<p><h2>Sunday Music</h2></p>\n";
    Create_Grid($Cond,'Music');
    Print_Grid(0,0,$Cond,$Public,'Music');
  }

  if ($head) {
    dotail();
  } else {
    Notes_Music_Pane();
    Controls(0,$Cond);
    ErrorPane(0);
    echo "</body></html>\n";
  }

?>

