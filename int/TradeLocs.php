<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Manage Trade Locations");

  include_once("TradeLib.php");
  echo "<div class='content'><h2>Manage Trade Locations</h2>\n";
  
  echo "Artisan Messages trigger local Artisan /Han related emails<p>";
  $Locs=Get_Trade_Locs(1);

  if (UpdateMany('TradeLocs','Put_Trade_Loc',$Locs,0)) $Locs=Get_Trade_Locs(1);

  $coln = 0;
  echo "<form method=post action=TradeLocs.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Prefix</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Has Power</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Pitches</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>In Use</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Days</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Artisan Msgs</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
  echo "</thead><tbody>";
  foreach($Locs as $t) {
    $i = $t['TLocId'];
    echo "<tr><td>$i" . fm_text1("",$t,'Name',1,'','',"Name$i");
    echo "<td>" . fm_select($Prefixes,$t,"prefix",0,'',"prefix$i");
    echo "<td>" . fm_checkbox('',$t,'HasPower','',"HasPower$i");
    echo fm_number1('',$t,'Pitches','','',"Pitches$i");
    echo "<td>" . fm_checkbox('',$t,'InUse','',"InUse$i");
    echo "<td>" . fm_select($Trade_Days,$t,"Days",0,'',"Days$i");
    echo "<td>" . fm_checkbox("",$t,'ArtisanMsgs','',"ArtisanMsgs$i");
    echo fm_text1('',$t,'Notes',3,'','',"Notes$i");
    echo "\n";
  }
  echo "<tr><td><td><input type=text name=Name0 >";
  echo "<td><input type=checkbox name=HasPower0>";
  echo "<td>" . fm_select2($Prefixes,0,'prefix0');
  echo "<td><input type=number name=Pitches0>";
  echo "<td><input type=checkbox name=InUse0>";
  echo "<td>" . fm_select2($Trade_Days,0,'Days0');
  echo "<td><input type=checkbox name=ArtisanMsgs0>";
  echo "<td><input type=text name=Notes0 size=48>";
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
