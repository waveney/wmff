<?php
  include_once("fest.php");
  $snum=$_GET{'sidenum'};
  A_Check('Participant','Side',$snum);

  dohead("Request",[],1);
  echo "<h2>Requested</h2>\n";
  include_once("DanceLib.php");
  global $YEAR,$FESTSYS,$PerfTypes;

  $Side=Get_Side($snum);

  $emails = [];
  foreach ($PerfTypes as $n=>$p) if ($Side[$p[0]]) $emails[] = $p[1] . "@" . $FESTSYS['HostURL'];

  NewSendEmail($emails,$Side['SN'] . " request invite",$Side['SN'] . " request an invite for $YEAR");

  Show_Side($snum);

  dotail();
?>
