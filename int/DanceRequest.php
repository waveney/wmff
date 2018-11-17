<?php
  include_once("fest.php");
  $snum=$_GET{'sidenum'};
  A_Check('Participant','Side',$snum);

  dohead("Request");
  echo "<h2>Requested</h2>\n";

  $Side=Get_Side($snum);

  $emails = Get_Emails('Dance');

  NewSendEmail($emails,$Side['SN'] . " request invite",$Side['SN'] . " request an invite for $YEAR");

  Show_Side($snum);

  dotail();
?>
