<?php
  include_once("fest.php");
  
  $field = $_POST['F'];
  $Value = $_POST['V'];
  $id    = $_POST['I'];
  $type  = $_POST['D'];

  var_dump($_POST);  
  switch ($type) {
  case 'Performer':
    include_once("DanceLib.php");
    $Perf = Get_Side($id);
    if (isset($Perf[$field])) {
      $Perf[$field] = $Value;
      echo Put_Side($Perf);
      exit;
    }
    $Perfy = Get_SideYear($id);
    if (isset($Perfy[$field])) {
      $Perfy[$field] = $Value;
      echo Put_SideYear($Perfy);
      exit;
    }
    include_once("MusicLib.php");
    $Perfy = Get_ActYear($id);
    if (isset($Perfy[$field])) {
      $Perfy[$field] = $Value;
      echo Put_ActYear($Perfy);
      exit;
    }
    // SHOULD never get here...
  }
?>

