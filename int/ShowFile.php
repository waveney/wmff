<?php
  include_once("fest.php");
  A_Check('Staff');
  include ("DocLib.php");
  include ("ViewLib.php");
  global $USERID;
  $read = 1;

if (isset($_GET{'f'})) {
  dostaffhead("View File");
  ViewFile("Store" . File_FullPName($_GET['f']));
} else if (isset($_GET{'l'})) {
  $l = $_GET['l'];
  ViewFile($l);
} else if (isset($_GET{'d'})) {
  ViewFile("Store" . File_FullPName($_GET['d']),0);
  exit;
}

  dotail();

?>
