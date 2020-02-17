<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("Manage Email Proformas");

  include_once("Email.php");
  echo "<div class='content'><h2>Manage Email Proformas</h2>\n";
  
  $prefixes = ['BB'=>Capability("EnableMisc"),'Dance'=>Capability('EnableDance'),'Finance'=>Capability('EnableFinance'),'LNL'=>Capability("EnableMisc"),'Login'=>1,
               'Trade'=>Capability("EnableTrade"),'lol'=>Capability("EnableMisc"), 'Stew'=>Capability("EnableMisc"),'Vol'=>Capability("EnableMisc"),
               'Invoice'=>Capability('EnableFinance') || Capability('EnableTrade')];
  
  if (Access('SysAdmin')) {
    $Edit = 1;
    echo "The Prefix of a name (the bit before the first _) has to have set values, do not introduce new ones without consulting Richard<p>";
  } else {
    echo "These are the proforma messages.  You cannot change them, email changes to Richard/SysAdmin.<p>";
    fm_addall('disabled readonly');
    $Edit = 0;
  }
  
  $Pros=Get_Email_Proformas(1);

  if ($Edit && UpdateMany('EmailProformas','Put_Email_Proforma',$Pros,0)) $Pros=Get_Email_Proformas(1);

  Replace_Help();

  $coln = 0;
  echo "<form method=post action=TEmailProformas>";
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th colspan=2><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Body of Message</a>\n";
  echo "</thead><tbody>";
  if ($Pros) foreach($Pros as $t) {
    $nam = $t['SN'];
    preg_match('/(.*?)_/',$nam,$res);
    if (!Access('Internal') && (!isset($res[1]) || !$prefixes[$res[1]])) continue;
    $i = $t['id'];
    echo "<tr><td>$i" . fm_text1("",$t,'SN',2,'','',"SN$i");
    echo "<td>" . fm_basictextarea($t,'Body',6,8,'',"Body$i");
    echo "\n";
  }
  if ($Edit) {
    echo "<tr><td><td colspan=2><input type=text name=SN0 size=32>";
    echo "<td><textarea name=Body0 rows=6 cols=120></textarea>";
  }
  echo "</table></div>\n";
  if ($Edit) echo "<input type=submit name=Update value=Update>\n";
  
  echo "</form></div>";

  dotail();

?>
