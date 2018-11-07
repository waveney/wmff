<?php
  include_once("fest.php");
//  A_Check('Staff');
  include ("DocLib.php");
  include ("ViewLib.php");
  global $USERID;
  $read = 1;

if (isset($_GET{'f'})) {
  ViewFile("Store" . File_FullPName($_GET['f']));
} else if (isset($_GET{'l'})) {
  ViewFile($_GET['l']);
} else if (isset($_GET{'l64'})) {
  ViewFile(base64_decode($_GET['l64']));
} else if (isset($_GET{'d'})) {
  ViewFile("Store" . File_FullPName($_GET['d']),0);
  exit;
} else if (isset($_GET{'D'})) {
  ViewFile($_GET['D'],0);
  exit;
}
  dotail();

?>
