<?php
  include_once("fest.php");
  $snum=$_GET{'sidenum'};
  A_Check('Participant','Side',$snum);

  dohead("Request");
  echo "<h2>Requested</h2>\n";

  $Side=Get_Side($snum);

  $emails = Get_Emails('Dance');

  SendEmail($emails,$Side['SName'] . " request invite",$Side['SName'] . " request an invite for $YEAR");

  Show_Side($snum);

  dotail();
?>
