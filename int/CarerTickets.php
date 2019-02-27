<?php
  include_once("fest.php");
  A_Check('Committee');

  dostaffhead("Manage Carer Tickets");

  function Get_Carers() {
    global $db,$YEAR;
    $cs = array();
    $res = $db->query("SELECT * FROM Tickets WHERE Year=$YEAR");
    if ($res) while($c = $res->fetch_assoc()) $cs[] = $c;
    return $cs;
  }

  function Get_Carer($id) {
    global $db;
    $res = $db->query("SELECT * FROM Tickets WHERE id=$id");
    if ($res) while($c = $res->fetch_assoc()) return $c;
  }

  function Put_Carer($now) {
    $e=$now['id'];
    $Cur = Get_Carer($e);
    return Update_db('Tickets',$Cur,$now);
  }

  include_once("TradeLib.php");
  global $USER,$PLANYEAR;
  
  $TicketTypes = ['Carer','&half; Price'];
  echo "<div class='content'><h2>Manage Carer/Half Price Tickets</h2>\n";
  
  echo "To request a carer or half price partner ticket, fill in the blank row at the bottom.<p>";

  $Carers=Get_Carers(1);

  if (UpdateMany('Tickets','Put_Carer',$Carers,0)) $Carers=Get_Carers(1);

  $coln = 0;
  echo "<form method=post action=CarerTickets.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Booked Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Carer/Team Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
  echo "</thead><tbody>";
  foreach($Carers as $t) {
    $i = $t['id'];
    echo "<tr><td>$i" . fm_text1("",$t,'SN',1,'','',"SN$i");
    echo "<td>" . fm_radio('',$TicketTypes,$t,'Type','',0,'',"Type$i");
    echo fm_text1('',$t,'Carer',1,'','',"Carer$i");
    echo fm_text1('',$t,'Notes',3,'','',"Notes$i");
    echo "\n";
  }
  echo "<tr><td><td><input type=text name=SN0 >";
  echo "<td>" . fm_radio('',$TicketTypes,$t,'Type','',0,'',"Type0");
  echo "<td><input type=text name=Carer0>";
  echo "<td><input type=text name=Notes0 size=60>";
  echo fm_hidden('Year0',$PLANYEAR);
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
