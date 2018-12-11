<?php
  include_once("fest.php");
  $snum=$_GET{'sidenum'};
  A_Check('Participant','Side',$snum);

  dohead("Request");
  echo "<h2>Requested</h2>\n";
  include_once("DanceLib.php");
  global $YEAR,$MASTER_DATA,$PerfTypes;

  $Side=Get_Side($snum);

  $emails = [];
  foreach ($PerfTypes as $n=>$p) if ($Side[$p[0]]) $emails[] = $p[1] . "@" . $MASTER_DATA['HostURL'];

  NewSendEmail($emails,$Side['SN'] . " request invite",$Side['SN'] . " request an invite for $YEAR");

  Show_Side($snum);

  dotail();
?>
