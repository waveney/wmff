<?php
  include_once("fest.php");
  include_once("Email.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php"); 
  include_once("PLib.php");
    
  $snum=$_GET{'sidenum'};
  A_Check('Participant');

  dostaffhead("Requested", ["/js/clipboard.min.js", "/js/emailclick.js", "/js/Participants.js","js/dropzone.js","css/dropzone.css", "js/InviteThings.js"],1);
  echo "<h2>Requested</h2>\n";
  include_once("DanceLib.php");
  global $YEAR,$FESTSYS,$PerfTypes;

  $Side=Get_Side($snum);
  $Sidey = Get_SideYear($snum);
  
  $emails = [];
  foreach ($PerfTypes as $n=>$p) if ($Side[$p[0]]) $emails[] = $p[1] . "@" . $FESTSYS['HostURL'];
  $txt = $Side['SN'] . " request an invite for $YEAR";

  NewSendEmail(1,$snum,$emails,$Side['SN'] . " request invite for $YEAR",$txt);

  Show_Part($Side,'Side',Access('Staff'),'AddPerf');
  Show_Perf_Year($snum,$Sidey,$YEAR,Access('Staff'));

  dotail();
?>
