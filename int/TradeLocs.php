<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Manage Trade Locations");

  include_once("TradeLib.php");
  include_once("InvoiceLib.php");
  
  echo "<div class='content'><h2>Manage Trade Locations</h2>\n";
  
  echo "Artisan Messages trigger local Artisan related emails<p>Only set the Invoice Code for locations that override normal trade type invoice codes<p>";
  
  echo "Set No List to exclude from venues on Show Trade<p>";
  
  $Locs=Get_Trade_Locs(1);
  

  if (UpdateMany('TradeLocs','Put_Trade_Loc',$Locs,0)) $Locs=Get_Trade_Locs(1);

  $coln = 0;
  $InvCodes =  Get_InvoiceCodes();
  
  echo "<form method=post action=TradeLocs.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Prefix</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Has Power</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>No List</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>In Use</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Days</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Artisan Msgs</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Invoice Code</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Map</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Map Scale</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Show Scale</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Setup</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Assign</a>\n";
  echo "</thead><tbody>";
  foreach($Locs as $t) {
    $i = $t['TLocId'];
    echo "<tr><td>$i" . fm_text1("",$t,'SN',1,'','',"SN$i");
    echo "<td>" . fm_select($Prefixes,$t,"prefix",0,'',"prefix$i");
    echo "<td>" . fm_checkbox('',$t,'HasPower','',"HasPower$i");
    echo "<td>" . fm_checkbox('',$t,'NoList','',"NoList$i");
//    echo fm_text1('',$t,'Pitches',0.25,'','',"Pitches$i");
    echo "<td>" . fm_checkbox('',$t,'InUse','',"InUse$i");
    echo "<td>" . fm_select($Trade_Days,$t,"Days",0,'',"Days$i");
    echo "<td>" . fm_checkbox("",$t,'ArtisanMsgs','',"ArtisanMsgs$i");
    echo "<td>" . fm_select($InvCodes,$t,'InvoiceCode',1,'',"InvoiceCode$i");
    echo fm_text1('',$t,'Notes',1,'','',"Notes$i");
    echo fm_text1('',$t,'MapImage',1,'','',"MapImage$i");
    echo fm_text1('',$t,'Mapscale',0.5,'','',"Mapscale$i");
    echo fm_text1('',$t,'Showscale',0.5,'','',"Showscale$i");
    echo "<td><a href=TradeSetup.php?i=$i>Setup</a>";
    echo "<td><a href=TradeAssign.php?i=$i>Assign</a>";
    echo "\n";
  }
  echo "<tr><td><td><input type=text name=SN0 >";
  echo "<td>" . fm_select2($Prefixes,0,'prefix0');
  echo "<td><input type=checkbox name=HasPower0>";
//  echo fm_text1('',$t,'Pitches',0.25,'','',"Pitches0");
  echo "<td><input type=checkbox name=NoList0>";
  echo "<td><input type=checkbox name=InUse0>";
  echo "<td>" . fm_select2($Trade_Days,0,'Days0');
  echo "<td><input type=checkbox name=ArtisanMsgs0>";
  echo "<td>" . fm_select($InvCodes,$t,'InvoiceCode',1,'',"InvoiceCode0");
  echo "<td><input type=text name=Notes0 >";
  echo "<td><input type=text name=MapImage0 >";
  echo fm_text1('',$t,'Mapscale',0.5,'','',"Mapscale0"); 
  echo fm_text1('',$t,'Showscale',0.5,'','',"Showscale0"); 
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
