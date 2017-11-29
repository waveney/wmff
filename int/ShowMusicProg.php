<?php
  include_once("fest.php");
//  A_Check('Steward');

  global $DAY;
  include_once("ProgramLib.php");

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
    Grab_Music_Data('Fri');
//    Scan_Data($Cond);
    if ($Public) echo "<p><h2>Friday Music</h2><p>\n";
    Prog_Music_Grid(0,0,$Cond,$Public);
  }
  if ($day != 'Sat') {
    Grab_Music_Data("Sat");
//      Scan_Data($Cond);
    echo "<p><h2>Saturday Music</h2></p>\n";
    Prog_Music_Grid(0,0,$Cond,$Public);
  }
  if ($day != 'Sun') {
    Grab_Music_Data("unt");
//      Scan_Data($Cond);
    echo "<p><h2>Sunday Music</h2></p>\n";
    Prog_Music_Grid(0,0,$Cond,$Public);
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

