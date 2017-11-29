<?php
// Participant finder

  include("fest.php");
  $x = '';
  $k = $_GET['S'];
  if (isset($_GET['X'])) $x = "AND " . $_GET['X'];
  if (isset($_GET['Y'])) {
    $y = $_GET['Y'];
    $qry = $db->query("SELECT s.SideId, s.Name FROM Side s, SideYear y WHERE s.SideId=y.SideId AND s.name LIKE '%$k%' AND $y $x LIMIT 10");
  } else {
    $qry = $db->query("SELECT s.SideId, s.Name FROM Side s WHERE s.name LIKE '%$k%' $x LIMIT 10");
  }
  $res = array();
  while ($ans = $qry->fetch_assoc()) {
    $res[$ans['SideId']] = $ans['Name'];
  }
  echo json_encode($res);

?>
