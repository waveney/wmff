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
  $tar = (isset($_GET['N'])? $_GET['N'] :'');
  ViewFile("Store" . File_FullPName($_GET['d']),0,$tar);
  exit;
} else if (isset($_GET{'D'})) {
  $tar = (isset($_GET['N'])? $_GET['N'] :'');
  ViewFile($_GET['D'],0,$tar);
  exit;
}
  dotail();

?>
